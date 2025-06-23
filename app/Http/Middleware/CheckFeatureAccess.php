<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user's plan has this feature
        if (!$user->hasFeature($feature)) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Your current plan does not include this feature.');
        }

        return $next($request);
    }
}