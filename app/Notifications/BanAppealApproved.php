<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BanAppealApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $adminResponse;

    /**
     * Create a new notification instance.
     */
    public function __construct($adminResponse)
    {
        $this->adminResponse = $adminResponse;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Ban Appeal Has Been Approved')
            ->greeting('Good news, ' . $notifiable->name . '!')
            ->line('Your ban appeal has been reviewed and approved. Your account has been unbanned.')
            ->line('**Admin Response:** ' . $this->adminResponse)
            ->line('You can now log in to your account and continue using our platform.')
            ->action('Login Now', url('/login'))
            ->line('We appreciate your patience and understanding. Please ensure you follow our community guidelines to avoid future issues.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'admin_response' => $this->adminResponse,
            'status' => 'approved'
        ];
    }
}
