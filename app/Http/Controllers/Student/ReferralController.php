<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Referral\ReferralService;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Show referral dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $stats = $this->referralService->getUserReferralStats($user);
        $history = $this->referralService->getReferralHistory($user);
        $redemptionHistory = $this->referralService->getRedemptionHistory($user);
        
        // Get available subscription plans for redemption (exclude institute-only plans)
        $subscriptionPlans = SubscriptionPlan::where('is_active', true)
            ->where('is_institute_only', false)
            ->where('slug', '!=', 'free')
            ->select('id', 'name', 'price')
            ->get()
            ->map(function ($plan) {
                $dailyPrice = $plan->price / 30;
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'daily_price' => round($dailyPrice, 2),
                ];
            });

        return view('student.referrals.index', [
            'stats' => $stats,
            'referralHistory' => $history,
            'redemptionHistory' => $redemptionHistory,
            'subscriptionPlans' => $subscriptionPlans,
        ]);
    }

    /**
     * Redeem balance for tokens
     */
    public function redeemTokens(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        
        try {
            $result = $this->referralService->redeemForTokens($user, $request->amount);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully redeemed {$result['tokens_received']} tokens!",
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Redeem balance for subscription
     */
    public function redeemSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'days' => 'required|integer|min:1|max:365',
        ]);

        $user = Auth::user();
        
        try {
            $result = $this->referralService->redeemForSubscription(
                $user,
                $request->plan_id,
                $request->days
            );
            
            return response()->json([
                'success' => true,
                'message' => "Successfully redeemed {$result['days']} days of {$result['plan']} subscription!",
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get referral history (AJAX)
     */
    public function getReferralHistory(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        $history = $this->referralService->getReferralHistory($user, $limit);
        
        return response()->json($history);
    }

    /**
     * Get redemption history (AJAX)
     */
    public function getRedemptionHistory(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        $history = $this->referralService->getRedemptionHistory($user, $limit);
        
        return response()->json($history);
    }

    /**
     * Get current referral stats (AJAX)
     */
    public function getStats()
    {
        $user = Auth::user();
        $stats = $this->referralService->getUserReferralStats($user);
        
        return response()->json($stats);
    }
}
