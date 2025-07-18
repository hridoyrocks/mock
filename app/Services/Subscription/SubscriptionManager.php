<?php

namespace App\Services\Subscription;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\PaymentTransaction;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class SubscriptionManager
{
    /**
     * Subscribe user to a plan
     */
    public function subscribe(User $user, SubscriptionPlan $plan, array $paymentDetails = []): UserSubscription
{
    return DB::transaction(function () use ($user, $plan, $paymentDetails) {
        // Cancel existing active subscriptions
        $this->cancelExistingSubscriptions($user);

        // Check for coupon
        $coupon = null;
        $finalPrice = $plan->current_price;
        $discountAmount = 0;
        
        if (isset($paymentDetails['coupon_code'])) {
            $coupon = Coupon::where('code', $paymentDetails['coupon_code'])->first();
            
            if ($coupon && $coupon->canBeUsedByUser($user) && $coupon->plan_id === $plan->id) {
                $discount = $coupon->calculateDiscount($plan->current_price);
                $finalPrice = $discount['final_price'];
                $discountAmount = $discount['discount_amount'];
            }
        }

        // Determine subscription duration
        $durationDays = $plan->duration_days;
        if ($coupon && $coupon->discount_type === 'trial' && $coupon->duration_days) {
            $durationDays = $coupon->duration_days;
        }

        // Create subscription
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'auto_renew' => $finalPrice > 0, // Auto-renew only for paid subscriptions
            'payment_method' => $paymentDetails['payment_method'] ?? null,
            'payment_reference' => $paymentDetails['payment_reference'] ?? null,
        ]);

        // Create coupon redemption record
        if ($coupon) {
            CouponRedemption::create([
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'subscription_id' => $subscription->id,
                'original_price' => $plan->current_price,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'redeemed_at' => now(),
                'expires_at' => $subscription->ends_at,
            ]);
            
            // Increment coupon usage
            $coupon->incrementUsage();
        }

        // Update user status
        $user->update([
            'subscription_status' => $plan->slug,
            'subscription_ends_at' => $subscription->ends_at,
        ]);

        // Reset usage counters
        $user->resetMonthlyCounters();

        return $subscription;
    });
}

    /**
     * Cancel user's active subscriptions
     */
    public function cancelExistingSubscriptions(User $user): void
    {
        $user->subscriptions()
            ->active()
            ->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'auto_renew' => false,
            ]);
    }

    /**
     * Renew subscription
     */
    public function renew(UserSubscription $subscription): UserSubscription
    {
        if (!$subscription->isActive()) {
            throw new Exception('Cannot renew inactive subscription');
        }

        return DB::transaction(function () use ($subscription) {
            // Create new subscription period
            $newSubscription = UserSubscription::create([
                'user_id' => $subscription->user_id,
                'plan_id' => $subscription->plan_id,
                'status' => 'active',
                'starts_at' => $subscription->ends_at,
                'ends_at' => $subscription->ends_at->copy()->addDays($subscription->plan->duration_days),
                'auto_renew' => true,
                'payment_method' => $subscription->payment_method,
            ]);

            // Update user
            $subscription->user->update([
                'subscription_ends_at' => $newSubscription->ends_at,
            ]);

            // Reset monthly counters
            $subscription->user->resetMonthlyCounters();

            return $newSubscription;
        });
    }

    /**
     * Check and process expired subscriptions
     */
    public function processExpiredSubscriptions(): int
    {
        $expired = UserSubscription::where('status', 'active')
            ->where('ends_at', '<=', now())
            ->get();

        $processed = 0;

        foreach ($expired as $subscription) {
            if ($subscription->auto_renew && $this->canAutoRenew($subscription)) {
                try {
                    $this->autoRenew($subscription);
                } catch (\Exception $e) {
                    $this->handleExpiredSubscription($subscription);
                }
            } else {
                $this->handleExpiredSubscription($subscription);
            }
            $processed++;
        }

        return $processed;
    }

    /**
     * Handle expired subscription
     */
    private function handleExpiredSubscription(UserSubscription $subscription): void
    {
        $subscription->update(['status' => 'expired']);

        // Downgrade to free plan
        $subscription->user->update([
            'subscription_status' => 'free',
            'subscription_ends_at' => null,
        ]);

        // Send expiration notification
        $subscription->user->notify(new \App\Notifications\SubscriptionExpired($subscription));
    }

    /**
     * Check if subscription can be auto-renewed
     */
    private function canAutoRenew(UserSubscription $subscription): bool
    {
        // Check if user has valid payment method
        // Check if last payment was successful
        // etc.
        return false; // Implement based on your business logic
    }

    /**
     * Auto-renew subscription
     */
    private function autoRenew(UserSubscription $subscription): void
    {
        // Process payment
        // Create new subscription period
        // Send renewal notification
        throw new Exception('Auto-renewal not implemented');
    }

    /**
     * Get subscription statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_active' => UserSubscription::active()->count(),
            'by_plan' => UserSubscription::active()
                ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
                ->select('subscription_plans.name', DB::raw('count(*) as count'))
                ->groupBy('subscription_plans.name')
                ->pluck('count', 'name')
                ->toArray(),
            'expiring_soon' => UserSubscription::expiringSoon(7)->count(),
            'revenue_this_month' => PaymentTransaction::successful()
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
    }

    /**
     * Reset monthly usage for all users
     */
    public function resetMonthlyUsage(): int
    {
        return User::where('tests_taken_this_month', '>', 0)
            ->orWhere('ai_evaluations_used', '>', 0)
            ->update([
                'tests_taken_this_month' => 0,
                'ai_evaluations_used' => 0,
            ]);
    }
}