<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'auto_renew',
        'payment_method',
        'payment_reference'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    /**
     * Get the user for this subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan for this subscription.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Get the payment transactions for this subscription.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'subscription_id');
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->ends_at->isFuture()
            && !$this->cancelled_at;
    }

    /**
     * Check if subscription is expired.
     */
    public function isExpired(): bool
    {
        return $this->ends_at->isPast();
    }

    /**
     * Check if subscription is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled' || $this->cancelled_at !== null;
    }

    /**
     * Cancel the subscription.
     */
    public function cancel(bool $immediately = false): void
    {
        $this->cancelled_at = now();
        
        if ($immediately) {
            $this->status = 'cancelled';
            $this->ends_at = now();
        } else {
            // Let it run until the end of the period
            $this->auto_renew = false;
        }
        
        $this->save();
    }

    /**
     * Resume a cancelled subscription.
     */
    public function resume(): void
    {
        if ($this->isExpired()) {
            throw new \Exception('Cannot resume an expired subscription.');
        }

        $this->cancelled_at = null;
        $this->auto_renew = true;
        $this->status = 'active';
        $this->save();
    }

    /**
     * Renew the subscription.
     */
    public function renew(): void
    {
        $this->starts_at = $this->ends_at;
        $this->ends_at = $this->ends_at->addDays($this->plan->duration_days);
        $this->status = 'active';
        $this->save();
    }

    /**
     * Get days remaining.
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return max(0, now()->diffInDays($this->ends_at, false));
    }

    /**
     * Get active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('ends_at', '>', now())
            ->whereNull('cancelled_at');
    }

    /**
     * Get expiring soon subscriptions.
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->active()
            ->whereBetween('ends_at', [now(), now()->addDays($days)]);
    }
}