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
            ->view('emails.subscription-created', [
                'user' => $notifiable,
                'subscription' => $this->subscription->load('plan.features')
            ]);
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