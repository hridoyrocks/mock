<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MaintenanceMode;

class MaintenanceNotification extends Notification
{
    use Queueable;

    protected $maintenance;
    protected $type;

    public function __construct(MaintenanceMode $maintenance, string $type)
    {
        $this->maintenance = $maintenance;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        // Only use mail for now, remove database to avoid errors
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->type === 'started') {
            return (new MailMessage)
                ->subject('Platform Maintenance Scheduled')
                ->greeting('Hello ' . $notifiable->name . ',')
                ->line('We are performing scheduled maintenance on our platform.')
                ->line('Maintenance Title: ' . $this->maintenance->title)
                ->line($this->maintenance->message)
                ->line('Expected completion: ' . ($this->maintenance->expected_end_at 
                    ? $this->maintenance->expected_end_at->format('M d, Y h:i A') 
                    : 'To be announced'))
                ->line('We apologize for any inconvenience this may cause.')
                ->line('Thank you for your patience!');
        } else {
            return (new MailMessage)
                ->subject('Maintenance Completed - Platform is Back Online!')
                ->greeting('Hello ' . $notifiable->name . ',')
                ->line('Good news! Our platform maintenance has been completed.')
                ->line('You can now access all features and continue your IELTS preparation.')
                ->action('Go to Dashboard', url('/student/dashboard'))
                ->line('Thank you for your patience during the maintenance period!');
        }
    }
}