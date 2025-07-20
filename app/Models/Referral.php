<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'status',
        'reward_amount',
        'reward_currency',
        'completed_at',
        'completion_condition',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'reward_amount' => 'decimal:2',
    ];

    /**
     * Get the referrer (user who made the referral)
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Get the rewards for this referral
     */
    public function rewards(): HasMany
    {
        return $this->hasMany(ReferralReward::class);
    }

    /**
     * Mark referral as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if referral is eligible for reward
     */
    public function isEligibleForReward(): bool
    {
        return $this->status === 'pending' && $this->checkCompletionCondition();
    }

    /**
     * Check if completion condition is met
     */
    protected function checkCompletionCondition(): bool
    {
        switch ($this->completion_condition) {
            case 'first_test':
                return $this->referred->attempts()->exists();
            case 'first_purchase':
                return $this->referred->transactions()
                    ->where('status', 'completed')
                    ->exists();
            case 'first_subscription':
                return $this->referred->subscriptions()
                    ->where('status', 'active')
                    ->where('plan_id', '>', 1) // Not free plan
                    ->exists();
            default:
                return false;
        }
    }

    /**
     * Create reward for completed referral
     */
    public function createReward(): ReferralReward
    {
        return ReferralReward::create([
            'user_id' => $this->referrer_id,
            'referral_id' => $this->id,
            'reward_type' => 'cash',
            'amount' => $this->reward_amount,
            'currency' => $this->reward_currency,
            'status' => 'credited',
            'credited_at' => now(),
        ]);
    }
}
