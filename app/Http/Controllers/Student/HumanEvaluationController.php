<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\StudentAttempt;
use App\Models\HumanEvaluationRequest;
use App\Models\UserEvaluationToken;
use App\Models\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HumanEvaluationController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGatewayFactory $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Show available teachers for evaluation
     */
    public function showTeachers(StudentAttempt $attempt)
    {
        try {
            // Check if attempt belongs to user
            if ($attempt->user_id !== auth()->id()) {
                if (request()->ajax()) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                abort(403);
            }
            
            // Check if human evaluation already requested
            $existingRequest = HumanEvaluationRequest::where('student_attempt_id', $attempt->id)->first();
            if ($existingRequest) {
                if (request()->ajax()) {
                    return response()->json(['error' => 'Evaluation already requested', 'redirect' => route('student.evaluation.status', $attempt->id)], 400);
                }
                return redirect()->route('student.evaluation.status', $attempt->id);
            }
            
            // Get section name
            $section = $attempt->testSet->section->name;
            
            // Get available teachers for this section
            $teachers = Teacher::with('user')
                ->where('is_available', true)
                ->get()
                ->filter(function ($teacher) use ($section) {
                    // Case-insensitive check for specialization
                    $specializations = $teacher->specialization ?? [];
                    return collect($specializations)->contains(function ($spec) use ($section) {
                        return strcasecmp($spec, $section) === 0;
                    });
                })
                ->map(function ($teacher) use ($section) {
                    $teacher->token_price = $teacher->calculateTokenPrice($section);
                    $teacher->urgent_price = $teacher->calculateTokenPrice($section, true);
                    return $teacher;
                })
                ->values();
            
            // Get user's token balance
            $tokenBalance = UserEvaluationToken::getOrCreateForUser(auth()->user());
            
            // Log for debugging
            Log::info('Teachers loaded for evaluation', [
                'attempt_id' => $attempt->id,
                'section' => $section,
                'teachers_count' => $teachers->count(),
                'ajax_request' => request()->ajax()
            ]);
            
            // If AJAX request, return only the teacher cards
            if (request()->ajax()) {
                return view('student.evaluation.partials.teacher-cards', compact(
                    'teachers',
                    'tokenBalance',
                    'section'
                ));
            }
            
            return view('student.evaluation.select-teacher', compact(
                'attempt',
                'teachers',
                'tokenBalance',
                'section'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading teachers', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attempt_id' => $attempt->id ?? null
            ]);
            
            if (request()->ajax()) {
                return response()->json(['error' => 'Failed to load teachers'], 500);
            }
            
            return back()->with('error', 'Failed to load teachers. Please try again.');
        }
    }
    
    /**
     * Request human evaluation
     */
    public function requestEvaluation(Request $request, StudentAttempt $attempt)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'priority' => 'required|in:normal,urgent'
        ]);
        
        // Check ownership
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Check if already requested
        if (HumanEvaluationRequest::where('student_attempt_id', $attempt->id)->exists()) {
            return redirect()->back()->with('error', 'Evaluation already requested for this attempt.');
        }
        
        $teacher = Teacher::findOrFail($request->teacher_id);
        $section = $attempt->testSet->section->name;
        $isPriority = $request->priority === 'urgent';
        $tokenCost = $teacher->calculateTokenPrice($section, $isPriority);
        
        // Check token balance
        $tokenBalance = UserEvaluationToken::getOrCreateForUser(auth()->user());
        if (!$tokenBalance->hasTokens($tokenCost)) {
            return redirect()->route('student.tokens.purchase')
                ->with('error', "You need {$tokenCost} tokens for this evaluation. Your balance: {$tokenBalance->available_tokens}");
        }
        
        DB::transaction(function () use ($attempt, $teacher, $tokenCost, $isPriority, $tokenBalance, $section) {
            // Deduct tokens
            $tokenBalance->useTokens($tokenCost);
            
            // Create evaluation request
            $evaluationRequest = HumanEvaluationRequest::create([
                'student_attempt_id' => $attempt->id,
                'student_id' => auth()->id(),
                'teacher_id' => $teacher->id,
                'tokens_used' => $tokenCost,
                'status' => 'assigned',
                'priority' => $isPriority ? 'urgent' : 'normal',
                'requested_at' => now(),
                'assigned_at' => now(),
                'deadline_at' => now()->addHours($isPriority ? 12 : 48)
            ]);
            
            // Log token transaction
            DB::table('token_transactions')->insert([
                'user_id' => auth()->id(),
                'type' => 'usage',
                'amount' => -$tokenCost,
                'balance_after' => $tokenBalance->available_tokens,
                'reason' => "Human evaluation request for {$section} test",
                'evaluation_request_id' => $evaluationRequest->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Send notification to teacher
            try {
                $teacher->user->notify(new \App\Notifications\NewEvaluationRequest($evaluationRequest));
            } catch (\Exception $e) {
                Log::error('Failed to send notification to teacher', [
                    'teacher_id' => $teacher->id,
                    'error' => $e->getMessage()
                ]);
            }
        });
        
        return redirect()->route('student.evaluation.status', $attempt->id)
            ->with('success', 'Human evaluation requested successfully!');
    }
    
    /**
     * Show evaluation status
     */
    public function status(StudentAttempt $attempt)
    {
        // Check ownership
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $evaluationRequest = HumanEvaluationRequest::with(['teacher.user', 'humanEvaluation'])
            ->where('student_attempt_id', $attempt->id)
            ->firstOrFail();
        
        return view('student.evaluation.status', compact('attempt', 'evaluationRequest'));
    }
    
    /**
     * Show token purchase page
     */
    public function showTokenPurchase()
    {
        $packages = TokenPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        $tokenBalance = UserEvaluationToken::getOrCreateForUser(auth()->user());
        
        // Get recent token transactions
        $recentTransactions = DB::table('token_transactions')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('student.tokens.purchase', compact('packages', 'tokenBalance', 'recentTransactions'));
    }
    
    /**
     * Process token purchase
     */
    public function purchaseTokens(Request $request)
    {
        // Handle both JSON and form requests
        if ($request->wantsJson()) {
            return $this->processJsonTokenPurchase($request);
        }
        
        $request->validate([
            'package_id' => 'required|exists:token_packages,id',
            'payment_method' => 'required|in:stripe,bkash,nagad'
        ]);
        
        $package = TokenPackage::findOrFail($request->package_id);
        
        if (!$package->is_active) {
            return back()->with('error', 'This package is not available.');
        }
        
        // Store package info in session
        session([
            'token_package_id' => $package->id,
            'token_package_amount' => $package->price
        ]);
        
        // Redirect to payment gateway
        return redirect()->route('payment.process', [
            'type' => 'token_package',
            'package_id' => $package->id,
            'payment_method' => $request->payment_method
        ]);
    }
    
    /**
     * Process JSON token purchase (for AJAX requests)
     */
    protected function processJsonTokenPurchase(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:token_packages,id',
            'payment_method' => 'required|in:stripe,bkash,nagad',
            'payment_method_id' => 'required_if:payment_method,stripe'
        ]);

        $package = TokenPackage::findOrFail($request->package_id);
        
        if (!$package->is_active) {
            return response()->json(['error' => 'This package is not available.'], 400);
        }

        try {
            // Process based on payment method
            if ($request->payment_method === 'stripe') {
                // Process Stripe payment directly
                $gateway = $this->paymentGateway->make('stripe');
                
                $paymentData = [
                    'amount' => $package->price,
                    'currency' => auth()->user()->currency ?? 'BDT',
                    'payment_method_id' => $request->payment_method_id,
                    'customer' => [
                        'email' => auth()->user()->email,
                        'name' => auth()->user()->name,
                    ],
                    'metadata' => [
                        'user_id' => auth()->id(),
                        'package_id' => $package->id,
                        'type' => 'token_package',
                        'tokens' => $package->total_tokens,
                    ],
                    'description' => "Token Package: {$package->name} ({$package->total_tokens} tokens)",
                ];
                
                $result = $gateway->createPayment($paymentData);
                
                if ($result['status'] === 'requires_action' && isset($result['client_secret'])) {
                    // 3D Secure authentication required
                    return response()->json([
                        'requires_action' => true,
                        'client_secret' => $result['client_secret']
                    ]);
                }
                
                if ($result['status'] === 'succeeded') {
                    // Payment successful, add tokens
                    $this->completeTokenPurchase($package, $result, 'stripe');
                    
                    return response()->json([
                        'success' => true,
                        'message' => "Successfully purchased {$package->total_tokens} tokens!",
                        'redirect' => route('student.tokens.purchase')
                    ]);
                }
                
                return response()->json(['error' => 'Payment failed.'], 400);
                
            } else {
                // For bKash/Nagad, return redirect URL
                session([
                    'token_package_id' => $package->id,
                    'token_package_amount' => $package->price
                ]);
                
                return response()->json([
                    'redirect' => route('payment.process', [
                        'type' => 'token_package',
                        'package_id' => $package->id,
                        'payment_method' => $request->payment_method
                    ])
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Token purchase failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'package_id' => $package->id
            ]);
            
            return response()->json(['error' => 'Payment processing failed.'], 500);
        }
    }
    
    /**
     * Complete token purchase after successful payment
     */
    protected function completeTokenPurchase($package, $paymentResult, $paymentMethod)
    {
        DB::transaction(function () use ($package, $paymentResult, $paymentMethod) {
            $user = auth()->user();
            
            // Create payment transaction
            $transaction = \App\Models\PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => null,
                'transaction_id' => $paymentResult['transaction_id'],
                'amount' => $paymentResult['amount'],
                'discount_amount' => 0,
                'currency' => $paymentResult['currency'] ?? 'BDT',
                'payment_method' => $paymentMethod,
                'status' => 'completed',
                'gateway_response' => $paymentResult,
                'metadata' => [
                    'type' => 'token_package',
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'tokens' => $package->total_tokens,
                ],
            ]);

            // Add tokens to user account
            $tokenBalance = UserEvaluationToken::getOrCreateForUser($user);
            $tokenBalance->addTokens($package->total_tokens, 'purchase');
            
            // Log token transaction
            DB::table('token_transactions')->insert([
                'user_id' => $user->id,
                'type' => 'purchase',
                'amount' => $package->total_tokens,
                'balance_after' => $tokenBalance->available_tokens,
                'reason' => "Purchased {$package->name}",
                'package_id' => $package->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Send notification
            try {
                $user->notify(new \App\Notifications\TokensPurchased($package, $transaction));
            } catch (\Exception $e) {
                Log::error('Failed to send token purchase notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }
    
    /**
     * View human evaluation result
     */
    public function viewResult(StudentAttempt $attempt)
    {
        // Check ownership
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $evaluationRequest = HumanEvaluationRequest::with(['humanEvaluation.evaluator', 'humanEvaluation.errorMarkings'])
            ->where('student_attempt_id', $attempt->id)
            ->where('status', 'completed')
            ->firstOrFail();
        
        if (!$evaluationRequest->humanEvaluation) {
            return redirect()->route('student.evaluation.status', $attempt->id)
                ->with('error', 'Evaluation not yet completed.');
        }
        
        $evaluation = $evaluationRequest->humanEvaluation;
        
        // Load attempt details
        $attempt->load(['testSet.section', 'answers.question']);
        
        return view('student.evaluation.human-result', compact('attempt', 'evaluation', 'evaluationRequest'));
    }
}