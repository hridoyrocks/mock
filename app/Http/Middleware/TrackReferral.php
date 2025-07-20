<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackReferral
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            // Store referral code in session
            session(['referral_code' => $request->get('ref')]);
        }

        return $next($request);
    }
}
