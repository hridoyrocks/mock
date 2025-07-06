<?php

namespace App\Notifications;

use App\Models\UserDevice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDeviceNotification extends Notification
{
    use Queueable;

    protected $device;

    public function __construct(UserDevice $device)
    {
        $this->device = $device;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Device Login Alert')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We detected a login from a new device:')
            ->line('**Device:** ' . $this->device->device_name . ' (' . $this->device->browser . ')')
            ->line('**Location:** ' . $this->device->city . ', ' . $this->device->country_name)
            ->line('**IP Address:** ' . $this->device->ip_address)
            ->line('**Time:** ' . $this->device->created_at->format('M d, Y H:i'))
            ->line('If this was you, you can safely ignore this email.')
            ->line('If this wasn\'t you, please secure your account immediately.')
            ->action('Check Account Activity', url('/profile'))
            ->salutation('Stay safe, IELTS Mock Test Team');
    }
}