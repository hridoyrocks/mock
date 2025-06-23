<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUsageLimit
{
    public function handle(Request $request, Closure $next, string $limitType): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check usage limits based on type
        switch ($limitType) {
            case 'mock_test':
                if (!$user->canTakeMoreTests()) {
                    return redirect()->route('subscription.plans')
                        ->with('error', 'You have reached your monthly test limit. Please upgrade your plan.');
                }
                break;
                
            case 'ai_evaluation':
                if (!$user->canUseAIEvaluation()) {
                    return redirect()->route('subscription.plans')
                        ->with('error', 'AI evaluation is not available in your current plan.');
                }
                break;
        }

        return $next($request);
    }
}