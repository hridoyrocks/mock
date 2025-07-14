<?php
// app/Http/Middleware/CheckMaintenanceMode.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MaintenanceMode;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Skip for admin users
        if ($user && $user->is_admin) {
            return $next($request);
        }

        // Check if maintenance mode is active
        if (MaintenanceMode::isActive()) {
            // Allow access to these routes during maintenance
            $allowedRoutes = [
                'maintenance',
                'logout',
                'login',
                'register',
                'password.request',
                'password.email',
                'password.reset',
                'password.update',
                'auth.verify.otp',
                'auth.otp.verify',
                'auth.otp.resend',
                'auth.social.redirect',
                'auth.social.callback',
                'auth.social.complete'
            ];

            // Allow all guest routes
            if (!$user) {
                return $next($request);
            }

            // For logged-in non-admin users, redirect to maintenance
            if ($user && !$user->is_admin && !in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('maintenance');
            }
        }

        return $next($request);
    }
}