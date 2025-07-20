<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referral_id',
        'reward_type',
        'amount',
        'currency',
        'status',
        'credited_at',
        'redeemed_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'credited_at' => 'datetime',
        'redeemed_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'json',
    ];

    /**
     * Get the user who owns this reward
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the referral that generated this reward
     */
    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    /**
     * Check if reward is redeemable
     */
    public function isRedeemable(): bool
    {
        return $this->status === 'credited' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }

    /**
     * Mark reward as redeemed
     */
    public function markAsRedeemed(): void
    {
        $this->update([
            'status' => 'redeemed',
            'redeemed_at' => now(),
        ]);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        $symbols = [
            'BDT' => '৳',
            'USD' => '$',
            'EUR' => '€',
        ];

        $symbol = $symbols[$this->currency] ?? $this->currency;
        return $symbol . ' ' . number_format($this->amount, 2);
    }
}
