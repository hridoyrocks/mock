<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UserSubscription;

class SubscriptionExpired extends Notification implements ShouldQueue
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
            ->subject('Your subscription has expired')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your ' . $this->subscription->plan->name . ' subscription has expired.')
            ->line('You have been downgraded to the Free plan with limited features.')
            ->line('To regain access to all premium features, please renew your subscription.')
            ->action('Renew Subscription', url('/subscription/plans'))
            ->line('We hope to see you back soon!')
            ->salutation('Best regards, The IELTS Practice Team');
    }
}