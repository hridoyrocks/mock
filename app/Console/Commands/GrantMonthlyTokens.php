<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Subscription\SubscriptionManager;
use App\Models\UserSubscription;
use App\Models\UserEvaluationToken;

class GrantMonthlyTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:grant-monthly {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant monthly evaluation tokens to active subscribers';

    protected $subscriptionManager;

    public function __construct(SubscriptionManager $subscriptionManager)
    {
        parent::__construct();
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly token grant process...');

        if ($userId = $this->option('user')) {
            // Grant tokens to specific user
            $subscription = UserSubscription::active()
                ->where('user_id', $userId)
                ->with(['user', 'plan'])
                ->first();

            if (!$subscription) {
                $this->error('No active subscription found for user ID: ' . $userId);
                return 1;
            }

            $this->subscriptionManager->grantMonthlyTokens($subscription->user, $subscription->plan);
            $this->info('Tokens granted to user: ' . $subscription->user->name);
        } else {
            // Grant tokens to all active subscribers
            $activeSubscriptions = UserSubscription::active()
                ->with(['user', 'plan.features'])
                ->get();

            $grantedCount = 0;
            $skippedCount = 0;

            foreach ($activeSubscriptions as $subscription) {
                $tokenFeature = $subscription->plan->getFeatureValue('evaluation_tokens_per_month');
                
                if (!$tokenFeature || $tokenFeature == 0) {
                    $skippedCount++;
                    continue;
                }

                $tokenRecord = UserEvaluationToken::getOrCreateForUser($subscription->user);
                
                // Check if already granted this month
                if ($tokenRecord->last_monthly_grant_at && 
                    $tokenRecord->last_monthly_grant_at->isCurrentMonth()) {
                    $this->line('Tokens already granted this month for: ' . $subscription->user->name);
                    $skippedCount++;
                    continue;
                }

                $this->subscriptionManager->grantMonthlyTokens($subscription->user, $subscription->plan);
                $grantedCount++;
                $this->line('Granted ' . $tokenFeature . ' tokens to: ' . $subscription->user->name);
            }

            $this->info("\nMonthly token grant completed!");
            $this->info("Granted: {$grantedCount} users");
            $this->info("Skipped: {$skippedCount} users");
        }

        return 0;
    }
}