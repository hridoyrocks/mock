<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $gatewayFactory;

    public function __construct(PaymentGatewayFactory $gatewayFactory)
    {
        $this->gatewayFactory = $gatewayFactory;
    }

    public function process(Request $request)
{
    try {
        // Validate request
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_method' => 'required|in:stripe,bkash,nagad',
            'payment_method_id' => 'required_if:payment_method,stripe',
            'terms' => 'required|accepted',
        ]);

        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        
        // Get coupon details from session
        $couponDetails = session('applied_coupon_details');
        $amount = session('subscription_amount', $plan->current_price);
        
        // Verify coupon again if present
        $coupon = null;
        $discountAmount = 0;
        $couponCode = null;
        
        if ($couponDetails && isset($couponDetails['coupon_id'])) {
            $coupon = Coupon::find($couponDetails['coupon_id']);
            
            if (!$coupon || !$coupon->canBeUsedByUser($user) || $coupon->plan_id !== $plan->id) {
                // Invalid coupon, reset to original price
                $amount = $plan->current_price;
                $couponDetails = null;
                session()->forget(['applied_coupon_details', 'coupon_code', 'subscription_amount']);
                
                Log::warning('Invalid coupon attempted during payment', [
                    'user_id' => $user->id,
                    'coupon_id' => $couponDetails['coupon_id'] ?? null
                ]);
            } else {
                // Use the already calculated discount from session
                $couponCode = $coupon->code;
                $discountAmount = $couponDetails['discount_amount'];
                $amount = $couponDetails['final_price'];
            }
        }

        // If amount is 0 (free or 100% discount), process without payment gateway
        if ($amount == 0) {
            DB::beginTransaction();
            
            try {
                // Create payment transaction record
                $transaction = PaymentTransaction::create([
                    'user_id' => $user->id,
                    'subscription_id' => null, // Will be updated after subscription creation
                    'transaction_id' => 'FREE-' . strtoupper(Str::random(10)),
                    'amount' => $plan->current_price,
                    'discount_amount' => $discountAmount,
                    'currency' => $user->currency ?? 'BDT',
                    'payment_method' => $coupon ? 'coupon' : 'free',
                    'status' => 'completed',
                    'gateway_response' => [
                        'coupon_code' => $couponCode,
                        'message' => $coupon ? 'Redeemed with coupon' : 'Free plan'
                    ],
                    'coupon_id' => $coupon?->id,
                ]);

                // Subscribe user
                $paymentDetails = [
                    'payment_method' => $coupon ? 'coupon' : 'free',
                    'payment_reference' => $transaction->transaction_id,
                    'coupon_code' => $couponCode
                ];
                
                $subscription = $user->subscribeTo($plan, $paymentDetails);
                
                // Update transaction with subscription ID
                $transaction->update(['subscription_id' => $subscription->id]);

                DB::commit();

                // Clear sessions
                $this->clearPaymentSessions();

                return redirect()->route('subscription.welcome')
                    ->with('success', 'Successfully subscribed to ' . $plan->name . ' plan!');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        // Process payment through gateway
        $gateway = $this->gatewayFactory->make($request->payment_method);
        
        // Prepare payment data
        $paymentData = [
            'amount' => $amount,
            'currency' => $user->currency ?? 'BDT',
            'customer' => [
                'email' => $user->email,
                'name' => $user->name,
                'phone' => $user->phone_number,
            ],
            'metadata' => [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'coupon_code' => $couponCode,
                'coupon_id' => $coupon?->id,
                'discount_amount' => $discountAmount,
                'original_amount' => $plan->current_price,
            ],
            'success_url' => route('payment.success'),
            'cancel_url' => route('subscription.plans'),
        ];

        // Handle different payment methods
        switch ($request->payment_method) {
            case 'stripe':
                return $this->processStripePayment($request, $gateway, $paymentData, $plan, $coupon);
                
            case 'bkash':
                return $this->processBkashPayment($gateway, $paymentData, $plan, $coupon);
                
            case 'nagad':
                return $this->processNagadPayment($gateway, $paymentData, $plan, $coupon);
                
            default:
                throw new \Exception('Invalid payment method');
        }

    } catch (\Exception $e) {
        Log::error('Payment processing failed', [
            'error' => $e->getMessage(),
            'user_id' => auth()->id(),
            'plan_id' => $request->plan_id ?? null,
            'coupon_details' => session('applied_coupon_details'),
        ]);

        return back()->with('error', 'Payment processing failed. Please try again.');
    }
}

    protected function processStripePayment($request, $gateway, $paymentData, $plan, $coupon)
    {
        try {
            // Add Stripe specific data
            $paymentData['payment_method_id'] = $request->payment_method_id;
            
            // Create payment intent
            $result = $gateway->createPayment($paymentData);
            
            if ($result['status'] === 'requires_action' && isset($result['client_secret'])) {
                // 3D Secure authentication required
                return response()->json([
                    'requires_action' => true,
                    'client_secret' => $result['client_secret']
                ]);
            }
            
            if ($result['status'] === 'succeeded') {
                // Payment successful, create subscription
                return $this->handleSuccessfulPayment($result, $plan, $coupon, 'stripe');
            }
            
            // Payment failed
            return back()->with('error', 'Payment failed. Please try again.');
            
        } catch (\Exception $e) {
            Log::error('Stripe payment failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return back()->with('error', 'Payment processing failed.');
        }
    }

    protected function processBkashPayment($gateway, $paymentData, $plan, $coupon)
    {
        try {
            // Create bKash payment
            $result = $gateway->createPayment($paymentData);
            
            if (isset($result['payment_url'])) {
                // Store payment info in session for callback
                session([
                    'bkash_payment' => [
                        'payment_id' => $result['payment_id'],
                        'plan_id' => $plan->id,
                        'coupon_id' => $coupon?->id,
                        'amount' => $paymentData['amount'],
                        'discount_amount' => $paymentData['metadata']['discount_amount'],
                    ]
                ]);
                
                // Redirect to bKash payment page
                return redirect($result['payment_url']);
            }
            
            return back()->with('error', 'Failed to initiate bKash payment.');
            
        } catch (\Exception $e) {
            Log::error('bKash payment failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return back()->with('error', 'Payment processing failed.');
        }
    }

    protected function processNagadPayment($gateway, $paymentData, $plan, $coupon)
    {
        try {
            // Create Nagad payment
            $result = $gateway->createPayment($paymentData);
            
            if (isset($result['payment_url'])) {
                // Store payment info in session for callback
                session([
                    'nagad_payment' => [
                        'payment_id' => $result['payment_id'],
                        'plan_id' => $plan->id,
                        'coupon_id' => $coupon?->id,
                        'amount' => $paymentData['amount'],
                        'discount_amount' => $paymentData['metadata']['discount_amount'],
                    ]
                ]);
                
                // Redirect to Nagad payment page
                return redirect($result['payment_url']);
            }
            
            return back()->with('error', 'Failed to initiate Nagad payment.');
            
        } catch (\Exception $e) {
            Log::error('Nagad payment failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return back()->with('error', 'Payment processing failed.');
        }
    }

    protected function handleSuccessfulPayment($paymentResult, $plan, $coupon, $paymentMethod)
{
    DB::beginTransaction();
    
    try {
        $user = auth()->user();
        $couponDetails = session('applied_coupon_details');
        
        // Create payment transaction
        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'subscription_id' => null, // Will be updated
            'transaction_id' => $paymentResult['transaction_id'],
            'amount' => $paymentResult['amount'],
            'discount_amount' => $couponDetails['discount_amount'] ?? 0,
            'currency' => $paymentResult['currency'] ?? 'BDT',
            'payment_method' => $paymentMethod,
            'status' => 'completed',
            'gateway_response' => $paymentResult,
            'coupon_id' => $coupon?->id,
        ]);

        // Subscribe user
        $paymentDetails = [
            'payment_method' => $paymentMethod,
            'payment_reference' => $transaction->transaction_id,
            'coupon_code' => $coupon?->code
        ];
        
        $subscription = $user->subscribeTo($plan, $paymentDetails);
        
        // Update transaction with subscription ID
        $transaction->update(['subscription_id' => $subscription->id]);

        DB::commit();

        // Clear sessions
        $this->clearPaymentSessions();

        // Send notifications
        $user->notify(new \App\Notifications\PaymentSuccessful($transaction));
        
        return redirect()->route('subscription.welcome')
            ->with('success', 'Payment successful! Welcome to ' . $plan->name . ' plan.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Failed to process successful payment', [
            'error' => $e->getMessage(),
            'payment_result' => $paymentResult
        ]);
        
        return redirect()->route('payment.failed')
            ->with('error', 'Payment was successful but subscription activation failed. Please contact support.');
    }
}

    public function success(Request $request)
    {
        // Handle payment success callback (for payment gateways that use callbacks)
        $paymentMethod = $request->input('payment_method');
        
        try {
            switch ($paymentMethod) {
                case 'bkash':
                    return $this->handleBkashCallback($request);
                    
                case 'nagad':
                    return $this->handleNagadCallback($request);
                    
                default:
                    return redirect()->route('subscription.welcome');
            }
        } catch (\Exception $e) {
            Log::error('Payment callback failed', [
                'error' => $e->getMessage(),
                'method' => $paymentMethod
            ]);
            
            return redirect()->route('payment.failed');
        }
    }

    public function failed(Request $request)
    {
        $this->clearPaymentSessions();
        
        return view('payment.failed');
    }

    protected function handleBkashCallback(Request $request)
    {
        $paymentInfo = session('bkash_payment');
        
        if (!$paymentInfo) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Payment session expired.');
        }
        
        // Verify payment with bKash
        $gateway = $this->gatewayFactory->make('bkash');
        $result = $gateway->verifyPayment($paymentInfo['payment_id']);
        
        if ($result['status'] === 'completed') {
            $plan = SubscriptionPlan::find($paymentInfo['plan_id']);
            $coupon = $paymentInfo['coupon_id'] ? Coupon::find($paymentInfo['coupon_id']) : null;
            
            return $this->handleSuccessfulPayment($result, $plan, $coupon, 'bkash');
        }
        
        return redirect()->route('payment.failed');
    }

    protected function handleNagadCallback(Request $request)
    {
        $paymentInfo = session('nagad_payment');
        
        if (!$paymentInfo) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Payment session expired.');
        }
        
        // Verify payment with Nagad
        $gateway = $this->gatewayFactory->make('nagad');
        $result = $gateway->verifyPayment($paymentInfo['payment_id']);
        
        if ($result['status'] === 'completed') {
            $plan = SubscriptionPlan::find($paymentInfo['plan_id']);
            $coupon = $paymentInfo['coupon_id'] ? Coupon::find($paymentInfo['coupon_id']) : null;
            
            return $this->handleSuccessfulPayment($result, $plan, $coupon, 'nagad');
        }
        
        return redirect()->route('payment.failed');
    }

    protected function clearPaymentSessions()
    {
        session()->forget([
            'subscription_plan_id',
            'subscription_amount',
            'coupon_code',
            'applied_coupon_details',
            'bkash_payment',
            'nagad_payment'
        ]);
    }

    // Webhook handler
    public function webhook(Request $request, $provider)
    {
        Log::info('Webhook received', [
            'provider' => $provider,
            'data' => $request->all()
        ]);
        
        try {
            $gateway = $this->gatewayFactory->make($provider);
            $result = $gateway->handleWebhook($request);
            
            if ($result['status'] === 'success') {
                // Process based on webhook type
                if ($result['type'] === 'payment.succeeded') {
                    $this->processWebhookPaymentSuccess($result['data'], $provider);
                } elseif ($result['type'] === 'payment.failed') {
                    $this->processWebhookPaymentFailed($result['data'], $provider);
                }
            }
            
            return response()->json(['status' => 'ok']);
            
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Webhook processing failed'], 400);
        }
    }

    protected function processWebhookPaymentSuccess($data, $provider)
    {
        // Find transaction by gateway reference
        $transaction = PaymentTransaction::where('transaction_id', $data['transaction_id'])
            ->where('payment_method', $provider)
            ->first();
            
        if ($transaction && $transaction->status !== 'completed') {
            $transaction->update([
                'status' => 'completed',
                'gateway_response' => array_merge($transaction->gateway_response ?? [], $data)
            ]);
            
            // Activate subscription if not already active
            if ($transaction->subscription && !$transaction->subscription->isActive()) {
                $transaction->subscription->update(['status' => 'active']);
            }
        }
    }

    protected function processWebhookPaymentFailed($data, $provider)
    {
        $transaction = PaymentTransaction::where('transaction_id', $data['transaction_id'])
            ->where('payment_method', $provider)
            ->first();
            
        if ($transaction) {
            $transaction->update([
                'status' => 'failed',
                'gateway_response' => array_merge($transaction->gateway_response ?? [], $data)
            ]);
        }
    }
}