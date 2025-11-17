<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\PaymentTransaction;
use App\Models\StudentAttempt;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;



class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role_id',
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
        'banned_at',
        'ban_reason',
        'ban_type',
        'ban_expires_at',
        'banned_by',
        'created_by',
        'last_login_at',
        'achievement_points',
        'study_streak_days',
        'last_study_date',
        'show_on_leaderboard',
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
        'banned_at' => 'datetime',
        'ban_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'achievement_points' => 'integer',
        'study_streak_days' => 'integer',
        'last_study_date' => 'date',
        'show_on_leaderboard' => 'boolean',
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

    /**
     * Get user's full test attempts.
     */
    public function fullTestAttempts(): HasMany
    {
        return $this->hasMany(FullTestAttempt::class);
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
     public function subscribeTo(SubscriptionPlan $plan, array $paymentDetails = [], ?int $customDurationDays = null): UserSubscription
    {
        // Use SubscriptionManager service
        $subscriptionManager = app(\App\Services\Subscription\SubscriptionManager::class);
        return $subscriptionManager->subscribe($this, $plan, $paymentDetails, $customDurationDays);
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
    
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
    
    /**
     * Teacher relationship
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }
    
    /**
     * Student attempts relationship
     */
    public function studentAttempts()
    {
        return $this->hasMany(StudentAttempt::class);
    }
    
    /**
     * Authentication logs relationship
     */
    public function authenticationLogs()
    {
        if (Schema::hasTable('authentication_log')) {
            return $this->hasMany(\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog::class, 'authenticatable_id');
        }
        return $this->hasMany(\stdClass::class, 'id'); // Return empty relationship if table doesn't exist
    }
    
    /**
     * Get the user who referred this user
     */
    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
    
    /**
     * Get current subscription
     */
    public function currentSubscription()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }
    
    /**
     * Check if user is banned
     */
    public function isBanned(): bool
    {
        if (is_null($this->banned_at)) {
            return false;
        }
        
        // Check if temporary ban has expired
        if ($this->ban_type === 'temporary' && $this->ban_expires_at && $this->ban_expires_at->isPast()) {
            // Auto-unban if temporary ban expired
            $this->unban();
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if ban is permanent
     */
    public function isPermanentlyBanned(): bool
    {
        return $this->isBanned() && $this->ban_type === 'permanent';
    }
    
    /**
     * Check if ban is temporary
     */
    public function isTemporarilyBanned(): bool
    {
        return $this->isBanned() && $this->ban_type === 'temporary';
    }
    
    /**
     * Get ban expiry date for temporary bans
     */
    public function getBanExpiryDate(): ?string
    {
        if ($this->isTemporarilyBanned() && $this->ban_expires_at) {
            return $this->ban_expires_at->format('F j, Y g:i A');
        }
        return null;
    }
    
    /**
     * Ban appeals relationship
     */
    public function banAppeals()
    {
        return $this->hasMany(BanAppeal::class);
    }
    
    /**
     * Get latest ban appeal
     */
    public function latestBanAppeal()
    {
        return $this->hasOne(BanAppeal::class)->latest();
    }
    
    /**
     * Check if user has pending appeal
     */
    public function hasPendingAppeal(): bool
    {
        return $this->banAppeals()->where('status', 'pending')->exists();
    }
    
    /**
     * Ban the user
     */
    public function ban(string $reason, string $type = 'temporary', $expiresAt = null, ?User $bannedBy = null): void
    {
        $banData = [
            'banned_at' => now(),
            'ban_reason' => $reason,
            'ban_type' => $type,
            'banned_by' => $bannedBy?->id
        ];
        
        if ($type === 'temporary') {
            if ($expiresAt instanceof \Carbon\Carbon) {
                $banData['ban_expires_at'] = $expiresAt;
            } elseif (is_string($expiresAt)) {
                $banData['ban_expires_at'] = Carbon::parse($expiresAt);
            } elseif (is_null($expiresAt)) {
                $banData['ban_expires_at'] = now()->addDays(7); // Default 7 days
            } else {
                $banData['ban_expires_at'] = $expiresAt;
            }
        } else {
            $banData['ban_expires_at'] = null;
        }
        
        $this->update($banData);
    }
    
    /**
     * Unban the user
     */
    public function unban(): void
    {
        $this->update([
            'banned_at' => null,
            'ban_reason' => null,
            'ban_type' => 'temporary',
            'ban_expires_at' => null,
            'banned_by' => null
        ]);
    }
    
    /**
     * Get the admin who banned this user
     */
    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
    
    /**
     * Get role display name
     */
    public function getRoleAttribute(): string
    {
        if ($this->is_admin) {
            return 'Admin';
        }
        
        if ($this->teacher) {
            return 'Teacher';
        }
        
        return 'Student';
    }
    
    /**
     * Get role badge color
     */
    public function getRoleBadgeColorAttribute(): string
    {
        if ($this->is_admin) {
            return 'bg-red-100 text-red-800';
        }
        
        if ($this->teacher) {
            return 'bg-purple-100 text-purple-800';
        }
        
        return 'bg-blue-100 text-blue-800';
    }
    
    /**
     * Get user's role relationship
     */
    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Admin has all permissions
        if ($this->is_admin) {
            return true;
        }
        
        // Check through role
        if ($this->userRole) {
            return $this->userRole->hasPermission($permissionSlug);
        }
        
        return false;
    }
    
    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Get all user permissions
     */
    public function getAllPermissions()
    {
        if ($this->is_admin) {
            return Permission::all();
        }
        
        if ($this->userRole) {
            return $this->userRole->permissions;
        }
        
        return collect([]);
    }
    
    /**
     * Assign role to user
     */
    public function assignRole(Role $role): void
    {
        $this->update(['role_id' => $role->id]);
    }
    
    /**
     * Remove role from user
     */
    public function removeRole(): void
    {
        $this->update(['role_id' => null]);
    }
}