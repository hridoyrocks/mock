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

    // Debug - Add this temporarily
    \Log::info('TrackUsageLimit Check', [
        'user_id' => $user->id,
        'limit_type' => $limitType,
        'can_take_tests' => $user->canTakeMoreTests(),
        'tests_taken' => $user->tests_taken_this_month,
        'feature_limit' => $user->getFeatureLimit('mock_tests_per_month'),
    ]);

    // Check usage limits based on type
    switch ($limitType) {
        case 'mock_test':
            if (!$user->canTakeMoreTests()) {
                return redirect()->route('subscription.plans')
                    ->with('error', 'You have reached your monthly test limit. Please upgrade your plan.');
            }
            break;
    }

    return $next($request);
}
}