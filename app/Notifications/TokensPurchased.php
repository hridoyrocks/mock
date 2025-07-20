<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TokenPackage;
use App\Models\PaymentTransaction;

class TokensPurchased extends Notification implements ShouldQueue
{
    use Queueable;

    protected $package;
    protected $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(TokenPackage $package, PaymentTransaction $transaction)
    {
        $this->package = $package;
        $this->transaction = $transaction;
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
            ->subject('Token Purchase Successful')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your token purchase has been completed successfully.')
            ->line('Package: ' . $this->package->name)
            ->line('Tokens Added: ' . $this->package->total_tokens)
            ->line('Amount Paid: ৳' . number_format($this->transaction->amount, 2))
            ->line('Transaction ID: ' . $this->transaction->transaction_id)
            ->action('View Token Balance', url('/student/tokens/purchase'))
            ->line('Thank you for your purchase!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'tokens_purchased',
            'package_id' => $this->package->id,
            'package_name' => $this->package->name,
            'tokens' => $this->package->total_tokens,
            'amount' => $this->transaction->amount,
            'transaction_id' => $this->transaction->transaction_id,
            'message' => 'Successfully purchased ' . $this->package->total_tokens . ' tokens'
        ];
    }
}