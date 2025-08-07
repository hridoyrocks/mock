<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BanAppealRejected extends Notification implements ShouldQueue
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
            ->subject('Your Ban Appeal Has Been Reviewed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your ban appeal has been reviewed by our admin team.')
            ->line('Unfortunately, we cannot approve your appeal at this time.')
            ->line('**Admin Response:** ' . $this->adminResponse)
            ->line('Your account will remain banned as per the original decision.')
            ->line('You may submit another appeal after 7 days from now.')
            ->line('If you have any questions, please review our community guidelines and terms of service.');
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
            'status' => 'rejected'
        ];
    }
}
