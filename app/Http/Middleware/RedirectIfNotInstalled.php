<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (!$this->alreadyInstalled() && !$request->is('install*')) {
            return redirect()->route('installer.welcome');
        }

        return $next($request);
    }

    private function alreadyInstalled()
    {
        return file_exists(storage_path('installed')) || env('APP_INSTALLED', false);
    }
}