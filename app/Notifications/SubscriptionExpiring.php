<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UserSubscription;

class SubscriptionExpiring extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;
    protected $daysRemaining;

    public function __construct(UserSubscription $subscription, int $daysRemaining)
    {
        $this->subscription = $subscription;
        $this->daysRemaining = $daysRemaining;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your subscription expires in ' . $this->daysRemaining . ' days')
            ->view('emails.subscription-expiring', [
                'subscription' => $this->subscription->load('plan'),
                'daysRemaining' => $this->daysRemaining,
                'totalAchievements' => $notifiable->achievements()->count(),
                'recentStreak' => $notifiable->activeGoal->current_streak ?? 0,
                'leaderboardPosition' => $this->getLeaderboardPosition($notifiable)
            ]);
    }
    
    private function getLeaderboardPosition($user)
    {
        // Simple implementation - you can enhance this
        return \App\Models\LeaderboardEntry::where('score', '>', $user->leaderboard_score ?? 0)->count() + 1;
    }
}