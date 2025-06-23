<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your middleware aliases here
        $middleware->alias([
            'role' => CheckRole::class,
            
            // Subscription related middleware
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'feature' => \App\Http\Middleware\CheckFeatureAccess::class,
            'usage.limit' => \App\Http\Middleware\TrackUsageLimit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();