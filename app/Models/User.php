<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\PaymentTransaction;
use App\Models\StudentAttempt;



class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

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
        'phone_number',
    'phone_verified_at',
    'google_id',
    'facebook_id',
    'avatar_url',
    'login_method',
    'country_code',
    'country_name',
    'city',
    'timezone',
    'currency',
    'is_social_signup',
    'avatar_url',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'last_subscription_check' => 'datetime',
        'tests_taken_this_month' => 'integer',
        'ai_evaluations_used' => 'integer',
        'phone_verified_at' => 'datetime',
    'is_social_signup' => 'boolean',
    ];


public function devices()
{
    return $this->hasMany(UserDevice::class);
}

public function otpVerifications()
{
    return $this->hasMany(OtpVerification::class, 'identifier', 'email');
}

public function hasVerifiedPhone(): bool
{
    return !is_null($this->phone_verified_at);
}

public function markPhoneAsVerified(): void
{
    $this->update(['phone_verified_at' => now()]);
}

public function getCountryFlagAttribute(): string
{
    return $this->country_code 
        ? "https://flagcdn.com/w40/" . strtolower($this->country_code) . ".png"
        : '';
}

public function trustedDevices()
{
    return $this->devices()->where('is_trusted', true);
}

public function hasTrustedDevice(string $fingerprint): bool
{
    return $this->trustedDevices()->where('device_fingerprint', $fingerprint)->exists();
}

     public function attempts(): HasMany
    {
        return $this->hasMany(StudentAttempt::class);
    }


    protected static function boot()
{
    parent::boot();
    
    // When user is created, give them free plan
    static::created(function ($user) {
        if (!$user->is_admin) {
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();
            if ($freePlan) {
                $user->subscribeTo($freePlan);
            }
        }
    });
}


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
        // Use SubscriptionManager service
        $subscriptionManager = app(\App\Services\Subscription\SubscriptionManager::class);
        $subscription = $subscriptionManager->subscribe($this, $plan, $paymentDetails);
        
        // Send welcome notification
        $this->notify(new \App\Notifications\SubscriptionCreated($subscription));
        
        return $subscription;
    }


    // Add to User model
public function goals()
{
    return $this->hasMany(UserGoal::class);
}

public function achievements()
{
    return $this->hasMany(UserAchievement::class);
}

public function activeGoal()
{
    return $this->hasOne(UserGoal::class)->where('is_active', true);
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