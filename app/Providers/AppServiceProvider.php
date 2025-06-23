<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Subscription\SubscriptionManager;
use App\Services\Payment\PaymentGatewayFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register SubscriptionManager as singleton
        $this->app->singleton(SubscriptionManager::class, function ($app) {
            return new SubscriptionManager();
        });
        
        // Register PaymentGatewayFactory as singleton
        $this->app->singleton(PaymentGatewayFactory::class, function ($app) {
            return new PaymentGatewayFactory();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom validation rules if needed
        \Illuminate\Support\Facades\Validator::extend('valid_subscription', function ($attribute, $value, $parameters, $validator) {
            return \App\Models\SubscriptionPlan::where('slug', $value)->where('is_active', true)->exists();
        });
    }
}