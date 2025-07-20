<?php

namespace App\Services\Referral;

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralReward;
use App\Models\ReferralRedemption;
use App\Models\ReferralSetting;
use App\Models\UserEvaluationToken;
use App\Models\SubscriptionPlan;
use App\Models\TokenTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class ReferralService
{
    /**
     * Process referral registration
     */
    public function processReferralRegistration(User $newUser, string $referralCode): void
    {
        $referrer = User::where('referral_code', $referralCode)->first();
        
        if (!$referrer || $referrer->id === $newUser->id) {
            return;
        }

        DB::transaction(function () use ($newUser, $referrer) {
            // Link the referral
            $newUser->referred_by = $referrer->id;
            $newUser->save();

            // Create referral record
            $referral = Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $newUser->id,
                'status' => 'pending',
                'reward_amount' => ReferralSetting::getValue('referral_reward_amount', 100),
                'reward_currency' => 'BDT',
                'completion_condition' => ReferralSetting::getValue('referral_completion_condition', 'first_test'),
            ]);

            // Increment referrer's total referrals
            $referrer->increment('total_referrals');
        });
    }

    /**
     * Check and complete referral
     */
    public function checkAndCompleteReferral(User $user): void
    {
        $referral = Referral::where('referred_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$referral || !$referral->isEligibleForReward()) {
            return;
        }

        DB::transaction(function () use ($referral) {
            // Mark referral as completed
            $referral->markAsCompleted();

            // Create reward
            $reward = $referral->createReward();

            // Update referrer's balance and successful referrals count
            $referrer = $referral->referrer;
            $referrer->addReferralBalance($reward->amount);
            $referrer->increment('successful_referrals');

            // Send notification
            $referrer->notify(new \App\Notifications\ReferralCompleted($referral, $reward));
        });
    }

    /**
     * Redeem referral balance for tokens
     */
    public function redeemForTokens(User $user, float $amount): array
    {
        if (!$user->canRedeemBalance() || $amount > $user->referral_balance) {
            throw new Exception('Insufficient balance for redemption');
        }

        $tokensPerTaka = (int) ReferralSetting::getValue('tokens_per_taka', 10);
        $tokensToAdd = (int) ($amount * $tokensPerTaka);

        DB::transaction(function () use ($user, $amount, $tokensToAdd) {
            // Deduct balance
            $user->deductReferralBalance($amount);

            // Add tokens
            $tokenBalance = $user->evaluationTokens;
            if (!$tokenBalance) {
                $tokenBalance = UserEvaluationToken::create([
                    'user_id' => $user->id,
                    'available_tokens' => 0,
                    'used_tokens' => 0,
                    'total_purchased' => 0,
                ]);
            }
            
            $tokenBalance->increment('available_tokens', $tokensToAdd);
            $tokenBalance->increment('total_purchased', $tokensToAdd);

            // Create redemption record
            ReferralRedemption::create([
                'user_id' => $user->id,
                'redemption_type' => 'tokens',
                'amount_spent' => $amount,
                'currency' => 'BDT',
                'tokens_received' => $tokensToAdd,
            ]);

            // Create token transaction
            TokenTransaction::create([
                'user_id' => $user->id,
                'type' => 'referral_redemption',
                'amount' => $tokensToAdd,
                'balance_after' => $tokenBalance->available_tokens,
                'description' => "Redeemed {$tokensToAdd} tokens from referral balance",
                'metadata' => [
                    'amount_spent' => $amount,
                    'currency' => 'BDT',
                ],
            ]);
        });

        return [
            'success' => true,
            'tokens_received' => $tokensToAdd,
            'amount_spent' => $amount,
        ];
    }

    /**
     * Redeem referral balance for subscription
     */
    public function redeemForSubscription(User $user, int $planId, int $days): array
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        
        // Calculate cost based on plan price and days
        $dailyCost = $plan->price / 30; // Assuming monthly pricing
        $totalCost = $dailyCost * $days;

        if (!$user->canRedeemBalance() || $totalCost > $user->referral_balance) {
            throw new Exception('Insufficient balance for redemption');
        }

        DB::transaction(function () use ($user, $plan, $days, $totalCost) {
            // Deduct balance
            $user->deductReferralBalance($totalCost);

            // Create or extend subscription
            $currentSubscription = $user->activeSubscription()->first();
            
            if ($currentSubscription && $currentSubscription->plan_id === $plan->id) {
                // Extend existing subscription
                $currentSubscription->ends_at = $currentSubscription->ends_at->addDays($days);
                $currentSubscription->save();
            } else {
                // Create new subscription or upgrade
                $subscriptionManager = app(\App\Services\Subscription\SubscriptionManager::class);
                $subscription = $subscriptionManager->subscribe($user, $plan, [
                    'payment_method' => 'referral_balance',
                    'ends_at' => now()->addDays($days),
                ]);
            }

            // Create redemption record
            ReferralRedemption::create([
                'user_id' => $user->id,
                'redemption_type' => 'subscription',
                'amount_spent' => $totalCost,
                'currency' => 'BDT',
                'subscription_plan_id' => $plan->id,
                'subscription_days' => $days,
            ]);
        });

        return [
            'success' => true,
            'plan' => $plan->name,
            'days' => $days,
            'amount_spent' => $totalCost,
        ];
    }

    /**
     * Get referral statistics for user
     */
    public function getUserReferralStats(User $user): array
    {
        $totalEarned = $user->referralRewards()
            ->where('status', 'credited')
            ->sum('amount');

        $totalRedeemed = $user->referralRedemptions()
            ->sum('amount_spent');

        $pendingReferrals = $user->referrals()
            ->where('status', 'pending')
            ->count();

        return [
            'referral_code' => $user->referral_code,
            'referral_link' => $user->referral_link,
            'total_referrals' => $user->total_referrals,
            'successful_referrals' => $user->successful_referrals,
            'pending_referrals' => $pendingReferrals,
            'current_balance' => $user->referral_balance,
            'total_earned' => $totalEarned,
            'total_redeemed' => $totalRedeemed,
            'can_redeem' => $user->canRedeemBalance(),
            'min_redemption_amount' => (float) ReferralSetting::getValue('min_redemption_amount', 50),
            'tokens_per_taka' => (int) ReferralSetting::getValue('tokens_per_taka', 10),
        ];
    }

    /**
     * Get referral history
     */
    public function getReferralHistory(User $user, int $limit = 10): array
    {
        $referrals = $user->referrals()
            ->with('referred:id,name,email,created_at')
            ->latest()
            ->paginate($limit);

        return [
            'referrals' => $referrals->map(function ($referral) {
                return [
                    'id' => $referral->id,
                    'referred_user' => [
                        'name' => $referral->referred->name,
                        'email' => substr($referral->referred->email, 0, 3) . '***@***',
                        'joined_at' => $referral->referred->created_at->format('Y-m-d'),
                    ],
                    'status' => $referral->status,
                    'reward_amount' => $referral->reward_amount,
                    'completed_at' => $referral->completed_at?->format('Y-m-d'),
                    'created_at' => $referral->created_at->format('Y-m-d'),
                ];
            }),
            'pagination' => [
                'current_page' => $referrals->currentPage(),
                'last_page' => $referrals->lastPage(),
                'per_page' => $referrals->perPage(),
                'total' => $referrals->total(),
            ],
        ];
    }

    /**
     * Get redemption history
     */
    public function getRedemptionHistory(User $user, int $limit = 10): array
    {
        $redemptions = $user->referralRedemptions()
            ->with('subscriptionPlan:id,name')
            ->latest()
            ->paginate($limit);

        return [
            'redemptions' => $redemptions->map(function ($redemption) {
                return [
                    'id' => $redemption->id,
                    'type' => $redemption->redemption_type,
                    'details' => $redemption->details,
                    'amount_spent' => $redemption->amount_spent,
                    'formatted_amount' => $redemption->formatted_amount,
                    'created_at' => $redemption->created_at->format('Y-m-d H:i'),
                ];
            }),
            'pagination' => [
                'current_page' => $redemptions->currentPage(),
                'last_page' => $redemptions->lastPage(),
                'per_page' => $redemptions->perPage(),
                'total' => $redemptions->total(),
            ],
        ];
    }
}
