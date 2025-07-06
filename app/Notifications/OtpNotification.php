<?php

namespace App\Notifications;

use App\Models\OtpVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    protected $otp;

    public function __construct(OtpVerification $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your IELTS Mock Test Verification Code')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your verification code is:')
            ->line('# ' . $this->otp->otp_code)
            ->line('This code will expire in 5 minutes.')
            ->line('If you didn\'t request this code, please ignore this email.')
            ->action('Open IELTS Mock Test', url('/'))
            ->salutation('Best regards, IELTS Mock Test Team');
    }
}