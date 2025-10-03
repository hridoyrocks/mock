<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionManagementController extends Controller
{
    /**
     * Display subscription analytics.
     */
    public function index()
    {
        // Get subscription stats
        $stats = [
            'total_subscribers' => UserSubscription::active()->count(),
            'revenue_this_month' => PaymentTransaction::successful()
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'new_subscribers_this_week' => UserSubscription::active()
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
            'churn_rate' => $this->calculateChurnRate(),
        ];

        // Get plan distribution
        $planDistribution = UserSubscription::active()
            ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
            ->select('subscription_plans.name', DB::raw('count(*) as count'))
            ->groupBy('subscription_plans.name')
            ->get();

        // Get recent subscriptions
        $recentSubscriptions = UserSubscription::with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        // Get expiring soon
        $expiringSoon = UserSubscription::expiringSoon()
            ->with(['user', 'plan'])
            ->get();

        return view('admin.subscriptions.index', compact(
            'stats',
            'planDistribution',
            'recentSubscriptions',
            'expiringSoon'
        ));
    }

    /**
     * Display users with subscriptions.
     */
    public function users(Request $request)
    {
        $query = User::with(['subscriptions' => function ($q) {
            $q->latest();
        }]);

        // Filter by subscription status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('subscriptions', function ($q) {
                    $q->active();
                });
            } elseif ($request->status === 'expired') {
                $query->whereHas('subscriptions', function ($q) {
                    $q->where('status', 'expired');
                });
            } elseif ($request->status === 'free') {
                $query->whereDoesntHave('subscriptions', function ($q) {
                    $q->active();
                });
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(20);

        return view('admin.subscriptions.users', compact('users'));
    }

    /**
     * Display transactions.
     */
    public function transactions(Request $request)
    {
        $query = PaymentTransaction::with(['user', 'subscription.plan']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->latest()->paginate(20);

        // Calculate totals
        $totals = [
            'total_amount' => $query->where('status', 'completed')->sum('amount'),
            'total_transactions' => $query->count(),
            'successful_transactions' => $query->where('status', 'completed')->count(),
        ];

        return view('admin.subscriptions.transactions', compact('transactions', 'totals'));
    }

    /**
     * Grant subscription to a user.
     */
    public function grantSubscription(Request $request, User $user)
    {
        \Log::info('Grant subscription request received', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'admin_id' => auth()->id()
        ]);

        try {
            $validated = $request->validate([
                'plan_id' => 'required|exists:subscription_plans,id',
                'duration_days' => 'required|integer|min:1|max:365',
                'reason' => 'required|string|max:255',
            ]);

            $plan = SubscriptionPlan::findOrFail($validated['plan_id']);
            \Log::info('Plan found', ['plan_id' => $plan->id, 'plan_name' => $plan->name]);

            DB::beginTransaction();

            // Cancel any existing active subscriptions
            $cancelledCount = UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'auto_renew' => false
                ]);
            
            \Log::info('Cancelled existing subscriptions', ['count' => $cancelledCount]);

            // Create new subscription directly
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays((int)$validated['duration_days']),
                'auto_renew' => false,
                'payment_method' => 'admin_granted',
                'payment_reference' => 'ADMIN_GRANT_' . auth()->id(),
            ]);
            
            \Log::info('Subscription created', ['subscription_id' => $subscription->id]);

            // Update user status
            // Map plan slug to allowed subscription status values
            $subscriptionStatus = 'free';
            if (in_array($plan->slug, ['premium', 'pro'])) {
                $subscriptionStatus = $plan->slug;
            } elseif ($plan->price > 0) {
                // If it's a paid plan but not premium/pro, default to premium
                $subscriptionStatus = 'premium';
            }
            
            $user->update([
                'subscription_status' => $subscriptionStatus,
                'subscription_ends_at' => $subscription->ends_at,
                'tests_taken_this_month' => 0,
                'ai_evaluations_used' => 0,
            ]);
            
            \Log::info('User updated', ['user_id' => $user->id]);

            // Create a transaction record
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'transaction_id' => PaymentTransaction::generateTransactionId(),
                'payment_method' => 'admin_granted',
                'amount' => 0,
                'currency' => 'BDT',
                'status' => 'completed',
                'notes' => 'Admin granted: ' . $validated['reason'],
                'metadata' => [
                    'granted_by' => auth()->id(),
                    'reason' => $validated['reason'],
                    'duration_days' => $validated['duration_days']
                ]
            ]);
            
            \Log::info('Transaction created', ['transaction_id' => $transaction->id]);

            // Grant monthly tokens if plan includes them
            try {
                $tokenFeatureValue = $plan->getFeatureValue('evaluation_tokens_per_month');
                if ($tokenFeatureValue && $tokenFeatureValue > 0) {
                    $tokenRecord = \App\Models\UserEvaluationToken::getOrCreateForUser($user);
                    $tokenRecord->addTokens($tokenFeatureValue, 'subscription_monthly');
                    
                    // Log token grant
                    DB::table('token_transactions')->insert([
                        'user_id' => $user->id,
                        'type' => 'admin_grant',
                        'amount' => $tokenFeatureValue,
                        'balance_after' => $tokenRecord->available_tokens,
                        'reason' => 'Monthly tokens from subscription grant',
                        'admin_id' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    \Log::info('Tokens granted', ['amount' => $tokenFeatureValue]);
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to grant tokens', ['error' => $e->getMessage()]);
            }

            DB::commit();
            
            \Log::info('Subscription grant completed successfully');

            return redirect()->back()
                ->with('success', "Subscription granted to {$user->name} successfully.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to grant subscription', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to grant subscription. Please try again.');
        }
    }

    /**
     * Revoke subscription.
     */
    public function revokeSubscription(Request $request, UserSubscription $subscription)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            // Cancel subscription immediately
            $subscription->cancel(true);

            // Update user status
            $subscription->user->update([
                'subscription_status' => 'free',
                'subscription_ends_at' => null,
            ]);

            // Log the action
            PaymentTransaction::create([
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'transaction_id' => PaymentTransaction::generateTransactionId(),
                'payment_method' => 'admin_revoked',
                'amount' => 0,
                'currency' => 'BDT',
                'status' => 'completed',
                'notes' => 'Admin revoked: ' . $request->reason,
            ]);

            return redirect()->back()
                ->with('success', 'Subscription revoked successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to revoke subscription: ' . $e->getMessage());
        }
    }

    /**
     * Calculate churn rate.
     */
    private function calculateChurnRate(): float
    {
        $lastMonth = now()->subMonth();
        
        $activeLastMonth = UserSubscription::where('status', 'active')
            ->where('created_at', '<=', $lastMonth)
            ->count();
            
        $cancelledThisMonth = UserSubscription::where('status', 'cancelled')
            ->whereMonth('cancelled_at', now()->month)
            ->count();

        if ($activeLastMonth === 0) {
            return 0;
        }

        return round(($cancelledThisMonth / $activeLastMonth) * 100, 2);
    }
}