<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'feature' => \App\Http\Middleware\CheckFeatureAccess::class,
            'usage.limit' => \App\Http\Middleware\TrackUsageLimit::class,
            'verify.webhook' => \App\Http\Middleware\VerifyWebhookSignature::class,
            'maintenance.check' => \App\Http\Middleware\CheckMaintenanceMode::class,
            'teacher' => \App\Http\Middleware\IsTeacher::class,
        ]);
        
        // Set priority - CheckBanned should run early
        $middleware->priority([
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\CheckBanned::class, // Run early after session
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        
        // Web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\CheckBanned::class,
        ]);
        
        // API middleware group
        $middleware->api(append: [
            // Add any custom API middleware here if needed
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Process expired subscriptions daily at 2 AM
        $schedule->command('subscriptions:process-expired')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/subscriptions.log'));
        
        // Reset monthly usage counters on the 1st of each month at 12:01 AM
        $schedule->command('subscriptions:reset-monthly')
            ->monthlyOn(1, '00:01')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/monthly-reset.log'));
        
        // Grant monthly tokens to subscribers on the 1st of each month at 12:05 AM
        $schedule->command('tokens:grant-monthly')
            ->monthlyOn(1, '00:05')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/monthly-tokens.log'));
        
        // Send expiry reminders daily at 9 AM
        $schedule->command('subscriptions:send-expiry-reminders')
            ->dailyAt('09:00')
            ->withoutOverlapping();
        
        // Clean up old payment transactions (older than 90 days)
        $schedule->command('payments:cleanup')
            ->weekly()
            ->sundays()
            ->at('03:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
