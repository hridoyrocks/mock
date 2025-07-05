<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Password Has Been Changed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your password was successfully changed.')
            ->line('**Details:**')
            ->line('Time: ' . $this->details['time']->format('M d, Y H:i'))
            ->line('Location: ' . $this->details['location'])
            ->line('IP Address: ' . $this->details['ip'])
            ->line('Device: ' . substr($this->details['browser'], 0, 50) . '...')
            ->line('If you did not make this change, please contact us immediately.')
            ->action('Secure Your Account', route('profile.edit'))
            ->line('For security reasons, all your devices have been logged out.')
            ->salutation('Stay safe, IELTS Mock Test Team');
    }
}