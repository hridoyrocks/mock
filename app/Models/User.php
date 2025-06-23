<?php

namespace App\Models;

// ... existing imports ...

class User extends Authenticatable
{
    // ... existing code ...

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'subscription_status',
        'subscription_ends_at',
        'tests_taken_this_month',
        'ai_evaluations_used',
        'last_subscription_check',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'last_subscription_check' => 'datetime',
        'tests_taken_this_month' => 'integer',
        'ai_evaluations_used' => 'integer',
    ];

    // ... existing relationships ...

    /**
     * Get user's subscriptions.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->active()
            ->with('plan', 'plan.features')
            ->latest()
            ->first();
    }

    /**
     * Get user's payment transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Check if user has active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    /**
     * Check if user has specific plan.
     */
    public function hasPlan(string $planSlug): bool
    {
        $subscription = $this->activeSubscription();
        
        return $subscription && $subscription->plan->slug === $planSlug;
    }

    /**
     * Check if user has access to a feature.
     */
    public function hasFeature(string $featureKey): bool
    {
        $subscription = $this->activeSubscription();
        
        if (!$subscription) {
            // Check free plan features
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();
            return $freePlan ? $freePlan->hasFeature($featureKey) : false;
        }

        return $subscription->plan->hasFeature($featureKey);
    }

    /**
     * Get feature limit/value.
     */
    public function getFeatureLimit(string $featureKey)
    {
        $subscription = $this->activeSubscription();
        
        if (!$subscription) {
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();
            return $freePlan ? $freePlan->getFeatureValue($featureKey) : null;
        }

        return $subscription->plan->getFeatureValue($featureKey);
    }

    /**
     * Check if user can take more tests this month.
     */
    public function canTakeMoreTests(): bool
    {
        $limit = $this->getFeatureLimit('mock_tests_per_month');
        
        if ($limit === 'unlimited') {
            return true;
        }
        
        return $this->tests_taken_this_month < (int) $limit;
    }

    /**
     * Check if user can use AI evaluation.
     */
    public function canUseAIEvaluation(): bool
    {
        return $this->hasFeature('ai_writing_evaluation') || 
               $this->hasFeature('ai_speaking_evaluation');
    }

    /**
     * Increment test count.
     */
    public function incrementTestCount(): void
    {
        $this->increment('tests_taken_this_month');
    }

    /**
     * Increment AI evaluation count.
     */
    public function incrementAIEvaluationCount(): void
    {
        $this->increment('ai_evaluations_used');
    }

    /**
     * Reset monthly counters.
     */
    public function resetMonthlyCounters(): void
    {
        $this->update([
            'tests_taken_this_month' => 0,
            'ai_evaluations_used' => 0,
        ]);
    }

    /**
     * Subscribe to a plan.
     */
    public function subscribeTo(SubscriptionPlan $plan, array $paymentDetails = []): UserSubscription
    {
        // Cancel any existing active subscription
        $this->subscriptions()->active()->update(['status' => 'cancelled']);

        // Create new subscription
        $subscription = $this->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->duration_days),
            'payment_method' => $paymentDetails['payment_method'] ?? null,
            'payment_reference' => $paymentDetails['payment_reference'] ?? null,
        ]);

        // Update user's subscription status
        $this->update([
            'subscription_status' => $plan->slug,
            'subscription_ends_at' => $subscription->ends_at,
        ]);

        return $subscription;
    }

    /**
     * Get subscription badge/label.
     */
    public function getSubscriptionBadgeAttribute(): array
    {
        $badges = [
            'free' => ['label' => 'Free', 'class' => 'bg-gray-100 text-gray-800'],
            'premium' => ['label' => 'Premium', 'class' => 'bg-blue-100 text-blue-800'],
            'pro' => ['label' => 'Pro', 'class' => 'bg-purple-100 text-purple-800'],
        ];

        return $badges[$this->subscription_status] ?? $badges['free'];
    }
}