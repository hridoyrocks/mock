<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UserSubscription;

class SubscriptionCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    public function __construct(UserSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to ' . $this->subscription->plan->name . ' Plan!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for subscribing to our ' . $this->subscription->plan->name . ' plan.')
            ->line('Your subscription is now active and will be valid until ' . $this->subscription->ends_at->format('F j, Y') . '.')
            ->line('Here\'s what you get with your plan:')
            ->lines($this->getPlanFeatures())
            ->action('Start Practicing', url('/student/dashboard'))
            ->line('If you have any questions, feel free to contact our support team.')
            ->salutation('Best regards, The IELTS Practice Team');
    }

    private function getPlanFeatures()
    {
        $features = [];
        foreach ($this->subscription->plan->features as $feature) {
            $features[] = '✓ ' . $feature->name;
        }
        return $features;
    }
}