<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGatewayFactory $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Display user's subscription dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $activeSubscription = $user->activeSubscription();
        $subscriptionHistory = $user->subscriptions()
            ->with('plan')
            ->latest()
            ->paginate(10);
        
        $transactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get();

        return view('subscription.index', compact(
            'user',
            'activeSubscription',
            'subscriptionHistory',
            'transactions'
        ));
    }

    /**
     * Display subscription plans.
     */
    public function plans()
    {
        $plans = SubscriptionPlan::active()
            ->with('features')
            ->get();
        
        $currentPlan = auth()->user()->activeSubscription()?->plan;

        return view('subscription.plans', compact('plans', 'currentPlan'));
    }

    /**
     * Subscribe to a plan.
     */
    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $user = auth()->user();

        // Check if user already has this plan
        if ($user->hasPlan($plan->slug)) {
            return redirect()->route('subscription.index')
                ->with('info', 'You already have this plan.');
        }

        // Free plan doesn't need payment
        if ($plan->is_free) {
            $user->subscribeTo($plan);
            return redirect()->route('subscription.index')
                ->with('success', 'Successfully subscribed to the Free plan!');
        }

        // For paid plans, redirect to payment
        session([
            'subscription_plan_id' => $plan->id,
            'subscription_amount' => $plan->current_price,
        ]);

        return view('subscription.checkout', compact('plan'));
    }

    /**
     * Cancel subscription.
     */
    public function cancel(Request $request)
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return redirect()->route('subscription.index')
                ->with('error', 'No active subscription found.');
        }

        // Don't allow cancelling free plan
        if ($subscription->plan->is_free) {
            return redirect()->route('subscription.index')
                ->with('error', 'Cannot cancel free plan.');
        }

        $subscription->cancel();

        return redirect()->route('subscription.index')
            ->with('success', 'Your subscription has been cancelled. You can continue using it until ' . $subscription->ends_at->format('d M Y'));
    }

    /**
     * Download invoice.
     */
    public function invoice(PaymentTransaction $transaction)
    {
        // Check if transaction belongs to user
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $user = auth()->user();
        $subscription = $transaction->subscription;
        $plan = $subscription->plan;

        return view('subscription.invoice', compact(
            'transaction',
            'user',
            'subscription',
            'plan'
        ));
    }
}