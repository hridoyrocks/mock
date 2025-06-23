<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\Subscription\SubscriptionManager;

class ResetMonthlyUsage extends Command
{
    protected $signature = 'subscriptions:reset-monthly';
    protected $description = 'Reset monthly usage counters for all users';

    protected $subscriptionManager;

    public function __construct(SubscriptionManager $subscriptionManager)
    {
        parent::__construct();
        $this->subscriptionManager = $subscriptionManager;
    }

    public function handle()
    {
        $this->info('Resetting monthly usage counters...');
        
        $resetCount = $this->subscriptionManager->resetMonthlyUsage();
        
        $this->info("Reset usage counters for {$resetCount} users.");
        
        // Send monthly summary emails to users
        $this->sendMonthlySummaries();
        
        return Command::SUCCESS;
    }

    private function sendMonthlySummaries()
    {
        User::where('is_admin', false)
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    // Dispatch job to send monthly summary email
                    \App\Jobs\SendMonthlySummaryEmail::dispatch($user);
                }
            });
    }
}