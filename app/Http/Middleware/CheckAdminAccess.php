<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $user = auth()->user();

        // Super Admin (is_admin = true) always has access
        if ($user->is_admin) {
            return $next($request);
        }

        // Role-based admin (role_id is set) has access
        if ($user->role_id !== null) {
            return $next($request);
        }

        // Student/Teacher (no role_id) - no admin access
        abort(403, 'You do not have permission to access the admin panel.');
    }
}
