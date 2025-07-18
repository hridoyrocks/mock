<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Subscription\SubscriptionManager;

class ProcessExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:process-expired';
    protected $description = 'Process expired subscriptions and downgrade users';

    protected $subscriptionManager;

    public function __construct(SubscriptionManager $subscriptionManager)
    {
        parent::__construct();
        $this->subscriptionManager = $subscriptionManager;
    }

    public function handle()
    {
        $this->info('Processing expired subscriptions...');
        
        $processed = $this->subscriptionManager->processExpiredSubscriptions();
        
        $this->info("Processed {$processed} expired subscriptions.");
        
        return Command::SUCCESS;
    }
}