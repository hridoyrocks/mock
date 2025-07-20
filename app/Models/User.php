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
    'referral_code',
    'referred_by',
    'referral_balance',
    'total_referrals',
    'successful_referrals',
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
    'referral_balance' => 'decimal:2',
    'total_referrals' => 'integer',
    'successful_referrals' => 'integer',
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
        
        // When user is created, give them free plan and generate referral code
        static::created(function ($user) {
            // Generate unique referral code
            if (empty($user->referral_code)) {
                $user->referral_code = $user->generateUniqueReferralCode();
                $user->saveQuietly(); // Use saveQuietly to avoid triggering events again
            }
            
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
     * Get user's active subscription relationship.
     */
    public function activeSubscriptionRelation()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }
    
    /**
     * Get user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->activeSubscriptionRelation()->first();
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
        return $this->activeSubscriptionRelation()->exists();
    }

    /**
     * Check if user has specific plan.
     */
    public function hasPlan(string $planSlug): bool
    {
        $subscription = $this->activeSubscription();
        
        return $subscription && $subscription->plan && $subscription->plan->slug === $planSlug;
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
        
        // Load plan with features if not already loaded
        if (!$subscription->relationLoaded('plan')) {
            $subscription->load('plan.features');
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
        
        // Load plan with features if not already loaded
        if (!$subscription->relationLoaded('plan')) {
            $subscription->load('plan.features');
        }

        return $subscription->plan->getFeatureValue($featureKey);
    }

    /**
     * Get monthly test limit as integer or string
     */
    public function getMonthlyTestLimit()
    {
        $limit = $this->getFeatureLimit('mock_tests_per_month');
        
        if ($limit === 'unlimited' || $limit === 'Unlimited' || !is_numeric($limit)) {
            return 'unlimited';
        }
        
        return intval($limit);
    }
    
    /**
     * Get test usage percentage
     */
    public function getTestUsagePercentage(): float
    {
        $limit = $this->getMonthlyTestLimit();
        
        if ($limit === 'unlimited' || $limit === 0) {
            return 0;
        }
        
        return min(100, ($this->tests_taken_this_month / $limit) * 100);
    }
    
    /**
     * Check if user can take more tests this month.
     */
    public function canTakeMoreTests(): bool
    {
        $limit = $this->getMonthlyTestLimit();
        
        if ($limit === 'unlimited') {
            return true;
        }
        
        return $this->tests_taken_this_month < $limit;
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
    
    /**
     * Get user's evaluation tokens
     */
    public function evaluationTokens()
    {
        return $this->hasOne(UserEvaluationToken::class);
    }
    
    /**
     * Get active subscription data (not a relationship)
     */
    public function getActiveSubscriptionData()
    {
        $subscription = $this->activeSubscription();
        if ($subscription && !$subscription->relationLoaded('plan')) {
            $subscription->load('plan');
        }
        return $subscription;
    }
    
    /**
     * Referral relationships
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
    
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }
    
    public function successfulReferrals()
    {
        return $this->referrals()->where('status', 'completed');
    }
    
    public function referralRewards()
    {
        return $this->hasMany(ReferralReward::class);
    }
    
    public function referralRedemptions()
    {
        return $this->hasMany(ReferralRedemption::class);
    }
    
    /**
     * Generate unique referral code
     */
    public function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (User::where('referral_code', $code)->exists());
        
        return $code;
    }
    
    /**
     * Get referral link
     */
    public function getReferralLinkAttribute(): string
    {
        return url('/register?ref=' . $this->referral_code);
    }
    
    /**
     * Add referral balance
     */
    public function addReferralBalance(float $amount): void
    {
        $this->increment('referral_balance', $amount);
    }
    
    /**
     * Deduct referral balance
     */
    public function deductReferralBalance(float $amount): void
    {
        $this->decrement('referral_balance', $amount);
    }
    
    /**
     * Check if user can redeem balance
     */
    public function canRedeemBalance(): bool
    {
        $minAmount = ReferralSetting::getValue('min_redemption_amount', 50);
        return $this->referral_balance >= $minAmount;
    }
    
    /**
     * Get available referral balance
     */
    public function getAvailableReferralBalanceAttribute(): float
    {
        return $this->referral_balance;
    }
    
    /**
     * Get formatted referral balance
     */
    public function getFormattedReferralBalanceAttribute(): string
    {
        return '৳ ' . number_format($this->referral_balance, 2);
    }
}