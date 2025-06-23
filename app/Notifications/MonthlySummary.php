<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlySummary extends Notification implements ShouldQueue
{
    use Queueable;

    protected $stats;

    public function __construct(array $stats)
    {
        $this->stats = $stats;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your IELTS Practice Summary for ' . $this->stats['month'])
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Here\'s your monthly practice summary:');
        
        if ($this->stats['tests_completed'] > 0) {
            $mail->line('📊 **Tests Completed:** ' . $this->stats['tests_completed'])
                 ->line('📈 **Average Score:** ' . number_format($this->stats['average_score'], 1));
            
            if ($this->stats['improvement'] > 0) {
                $mail->line('🎉 **Improvement:** +' . number_format($this->stats['improvement'], 1) . ' points from last month!');
            } elseif ($this->stats['improvement'] < 0) {
                $mail->line('📉 **Score Change:** ' . number_format($this->stats['improvement'], 1) . ' points from last month.');
            }
            
            $mail->line('Keep practicing regularly to maintain and improve your scores!');
        } else {
            $mail->line('We noticed you haven\'t taken any practice tests last month.')
                 ->line('Remember, consistent practice is key to improving your IELTS score!');
        }
        
        return $mail->action('Start Practicing', url('/student/dashboard'))
                   ->line('Thank you for choosing our platform for your IELTS preparation!')
                   ->salutation('Best regards, The IELTS Practice Team');
    }
}