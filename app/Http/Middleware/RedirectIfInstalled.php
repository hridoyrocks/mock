<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->alreadyInstalled()) {
            return redirect()->route('login');
        }

        return $next($request);
    }

    private function alreadyInstalled()
    {
        return file_exists(storage_path('installed')) || env('APP_INSTALLED', false);
    }
}