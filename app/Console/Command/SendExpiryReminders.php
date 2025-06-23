<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;
use App\Notifications\SubscriptionExpiring;
use Carbon\Carbon;

class SendExpiryReminders extends Command
{
    protected $signature = 'subscriptions:send-expiry-reminders';
    protected $description = 'Send expiry reminder emails to users';

    public function handle()
    {
        $this->info('Sending subscription expiry reminders...');
        
        $reminderDays = config('subscription.notifications.expiry_reminder_days', [7, 3, 1]);
        $sentCount = 0;
        
        foreach ($reminderDays as $days) {
            $targetDate = Carbon::now()->addDays($days)->startOfDay();
            $endDate = $targetDate->copy()->endOfDay();
            
            $expiringSubscriptions = UserSubscription::active()
                ->whereBetween('ends_at', [$targetDate, $endDate])
                ->with('user', 'plan')
                ->get();
            
            foreach ($expiringSubscriptions as $subscription) {
                // Check if reminder already sent
                $reminderKey = "expiry_reminder_{$days}_{$subscription->id}";
                
                if (!cache()->has($reminderKey)) {
                    $subscription->user->notify(new SubscriptionExpiring($subscription, $days));
                    cache()->put($reminderKey, true, now()->addDays($days + 1));
                    $sentCount++;
                }
            }
        }
        
        $this->info("Sent {$sentCount} expiry reminders.");
        
        return Command::SUCCESS;
    }
}