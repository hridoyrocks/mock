<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next, ?string $requiredPlan = null): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has active subscription
        $activeSubscription = $user->activeSubscription();
        
        if (!$activeSubscription) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Please subscribe to access this feature.');
        }

        // If specific plan is required
        if ($requiredPlan) {
            $userPlan = $activeSubscription->plan->slug;
            
            // Plan hierarchy: free < premium < pro
            $planHierarchy = ['free' => 1, 'premium' => 2, 'pro' => 3];
            
            if (($planHierarchy[$userPlan] ?? 0) < ($planHierarchy[$requiredPlan] ?? 999)) {
                return redirect()->route('subscription.plans')
                    ->with('error', 'Please upgrade to ' . ucfirst($requiredPlan) . ' plan to access this feature.');
            }
        }

        return $next($request);
    }
}