<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Referral;
use App\Models\ReferralReward;

class ReferralCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $referral;
    protected $reward;

    /**
     * Create a new notification instance.
     */
    public function __construct(Referral $referral, ReferralReward $reward)
    {
        $this->referral = $referral;
        $this->reward = $reward;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Congratulations! Your referral earned you a reward')
            ->view('emails.referral-completed', [
                'referral' => $this->referral->load('referrer', 'referee'),
                'totalReferrals' => $notifiable->total_referrals,
                'successfulReferrals' => $notifiable->successful_referrals,
                'totalBalance' => $notifiable->referral_balance
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'referral_completed',
            'referral_id' => $this->referral->id,
            'reward_id' => $this->reward->id,
            'amount' => $this->reward->amount,
            'currency' => $this->reward->currency,
            'message' => 'Your referral earned you ' . $this->reward->formatted_amount,
        ];
    }
}
