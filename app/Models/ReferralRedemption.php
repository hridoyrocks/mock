<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'redemption_type',
        'amount_spent',
        'currency',
        'tokens_received',
        'subscription_plan_id',
        'subscription_days',
        'metadata',
    ];

    protected $casts = [
        'amount_spent' => 'decimal:2',
        'metadata' => 'json',
    ];

    /**
     * Get the user who made this redemption
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription plan if redeemed for subscription
     */
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Get formatted redemption details
     */
    public function getDetailsAttribute(): string
    {
        switch ($this->redemption_type) {
            case 'tokens':
                return "Redeemed {$this->tokens_received} tokens";
            case 'subscription':
                $plan = $this->subscriptionPlan;
                return $plan ? "Redeemed {$plan->name} subscription for {$this->subscription_days} days" : "Subscription redemption";
            default:
                return "Redemption";
        }
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        $symbols = [
            'BDT' => '৳',
            'USD' => '$',
            'EUR' => '€',
        ];

        $symbol = $symbols[$this->currency] ?? $this->currency;
        return $symbol . ' ' . number_format($this->amount_spent, 2);
    }
}
