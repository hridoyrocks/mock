<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PaymentTransaction;

class PaymentSuccessful extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(PaymentTransaction $transaction)
    {
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
        $isTokenPurchase = isset($this->transaction->metadata['type']) && 
                          $this->transaction->metadata['type'] === 'token_package';
        
        if ($isTokenPurchase) {
            return (new MailMessage)
                ->subject('Payment Successful - Token Purchase')
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('Your payment has been processed successfully.')
                ->line('Amount: ৳' . number_format($this->transaction->amount, 2))
                ->line('Payment Method: ' . ucfirst($this->transaction->payment_method))
                ->line('Transaction ID: ' . $this->transaction->transaction_id)
                ->action('View Details', url('/student/tokens/purchase'))
                ->line('Thank you for your purchase!');
        } else {
            return (new MailMessage)
                ->subject('Payment Successful - Subscription')
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('Your subscription payment has been processed successfully.')
                ->line('Amount: ৳' . number_format($this->transaction->amount, 2))
                ->line('Payment Method: ' . ucfirst($this->transaction->payment_method))
                ->line('Transaction ID: ' . $this->transaction->transaction_id)
                ->action('View Subscription', url('/subscription'))
                ->line('Thank you for subscribing!');
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_successful',
            'transaction_id' => $this->transaction->transaction_id,
            'amount' => $this->transaction->amount,
            'payment_method' => $this->transaction->payment_method,
            'message' => 'Payment of ৳' . number_format($this->transaction->amount, 2) . ' was successful'
        ];
    }
}