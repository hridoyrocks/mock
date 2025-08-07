<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBanned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reason;
    protected $banType;
    protected $expiresAt;

    /**
     * Create a new notification instance.
     */
    public function __construct($reason, $banType, $expiresAt = null)
    {
        $this->reason = $reason;
        $this->banType = $banType;
        $this->expiresAt = $expiresAt;
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
        $mail = (new MailMessage)
            ->subject('Your Account Has Been ' . ($this->banType === 'permanent' ? 'Permanently' : 'Temporarily') . ' Banned')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your account on ' . config('app.name') . ' has been ' . ($this->banType === 'permanent' ? 'permanently' : 'temporarily') . ' banned.')
            ->line('**Reason:** ' . $this->reason);
        
        if ($this->banType === 'temporary' && $this->expiresAt) {
            $mail->line('**Ban expires on:** ' . $this->expiresAt->format('F j, Y g:i A'));
        }
        
        $mail->line('If you believe this is a mistake, you can submit an appeal by logging into your account.')
            ->action('Submit Appeal', url('/banned'))
            ->line('Please note that repeated violations may result in permanent suspension.');
        
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reason' => $this->reason,
            'ban_type' => $this->banType,
            'expires_at' => $this->expiresAt?->toISOString(),
        ];
    }
}
