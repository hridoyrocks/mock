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
        $mail = (new MailMessage)
            ->subject('Your subscription expires in ' . $this->daysRemaining . ' days')
            ->greeting('Hello ' . $notifiable->name . '!');

        if ($this->daysRemaining === 1) {
            $mail->line('Your ' . $this->subscription->plan->name . ' subscription expires tomorrow!')
                 ->line('Don\'t lose access to premium features. Renew now to continue your IELTS preparation without interruption.');
        } else {
            $mail->line('Your ' . $this->subscription->plan->name . ' subscription will expire in ' . $this->daysRemaining . ' days.')
                 ->line('Renew your subscription to continue enjoying all premium features.');
        }

        return $mail->action('Renew Subscription', url('/subscription'))
                   ->line('If you have any questions, please contact our support team.')
                   ->salutation('Best regards, The IELTS Practice Team');
    }
}