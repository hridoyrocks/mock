<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $gatewayFactory;

    public function __construct(PaymentGatewayFactory $gatewayFactory)
    {
        $this->gatewayFactory = $gatewayFactory;
    }

    /**
     * Process payment.
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:stripe,bkash,nagad',
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Create pending transaction
        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'transaction_id' => PaymentTransaction::generateTransactionId(),
            'payment_method' => $request->payment_method,
            'amount' => $plan->current_price,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        try {
            $gateway = $this->gatewayFactory->make($request->payment_method);
            
            // Process payment based on gateway
            $response = $gateway->processPayment([
                'amount' => $plan->current_price,
                'currency' => 'BDT',
                'transaction_id' => $transaction->transaction_id,
                'success_url' => route('payment.success', ['transaction' => $transaction->id]),
                'cancel_url' => route('payment.failed', ['transaction' => $transaction->id]),
                'customer_email' => $user->email,
                'customer_name' => $user->name,
                'description' => "Subscription to {$plan->name} plan",
            ]);

            // Store gateway response
            $transaction->update([
                'gateway_response' => $response,
            ]);

            // Redirect to payment gateway
            if (isset($response['redirect_url'])) {
                return redirect($response['redirect_url']);
            }

            // For API-based gateways
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'transaction' => $transaction->id,
            ]);

            $transaction->markAsFailed(['error' => $e->getMessage()]);

            return redirect()->route('subscription.plans')
                ->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Handle successful payment.
     */
    public function success(Request $request)
    {
        $transaction = PaymentTransaction::findOrFail($request->transaction);

        // Verify transaction belongs to user
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if already processed
        if ($transaction->isSuccessful()) {
            return redirect()->route('subscription.index')
                ->with('info', 'This payment has already been processed.');
        }

        try {
            DB::beginTransaction();

            // Mark transaction as completed
            $transaction->markAsCompleted([
                'payment_id' => $request->payment_id,
                'completed_at' => now(),
            ]);

            // Get plan from session or transaction
            $planId = session('subscription_plan_id');
            $plan = SubscriptionPlan::findOrFail($planId);

            // Subscribe user to plan
            $subscription = $transaction->user->subscribeTo($plan, [
                'payment_method' => $transaction->payment_method,
                'payment_reference' => $transaction->transaction_id,
            ]);

            // Link transaction to subscription
            $transaction->update(['subscription_id' => $subscription->id]);

            DB::commit();

            // Clear session
            session()->forget(['subscription_plan_id', 'subscription_amount']);

            return redirect()->route('subscription.index')
                ->with('success', 'Payment successful! You are now subscribed to the ' . $plan->name . ' plan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to activate subscription after payment', [
                'error' => $e->getMessage(),
                'transaction' => $transaction->id,
            ]);

            return redirect()->route('subscription.index')
                ->with('error', 'Payment received but failed to activate subscription. Please contact support.');
        }
    }

    /**
     * Handle failed payment.
     */
    public function failed(Request $request)
    {
        $transaction = PaymentTransaction::findOrFail($request->transaction);

        // Verify transaction belongs to user
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->markAsFailed([
            'failed_at' => now(),
            'reason' => $request->reason ?? 'Payment cancelled by user',
        ]);

        return redirect()->route('subscription.plans')
            ->with('error', 'Payment failed or cancelled. Please try again.');
    }

    /**
     * Handle payment gateway webhooks.
     */
    public function webhook(Request $request, $provider)
    {
        try {
            $gateway = $this->gatewayFactory->make($provider);
            $response = $gateway->handleWebhook($request);

            if ($response['status'] === 'success') {
                $transaction = PaymentTransaction::where('transaction_id', $response['transaction_id'])->first();

                if ($transaction && $transaction->isPending()) {
                    DB::beginTransaction();

                    $transaction->markAsCompleted($response['data'] ?? []);

                    // Get plan and subscribe user
                    $plan = SubscriptionPlan::find($response['plan_id'] ?? session('subscription_plan_id'));
                    if ($plan) {
                        $subscription = $transaction->user->subscribeTo($plan, [
                            'payment_method' => $transaction->payment_method,
                            'payment_reference' => $transaction->transaction_id,
                        ]);

                        $transaction->update(['subscription_id' => $subscription->id]);
                    }

                    DB::commit();
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }
}