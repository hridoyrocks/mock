<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'notes'
    ];

    protected $casts = {
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    /**
     * Get the user for this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription for this transaction.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

    /**
     * Check if transaction is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark transaction as completed.
     */
    public function markAsCompleted(array $gatewayResponse = []): void
    {
        $this->status = 'completed';
        $this->gateway_response = array_merge($this->gateway_response ?? [], $gatewayResponse);
        $this->save();
    }

    /**
     * Mark transaction as failed.
     */
    public function markAsFailed(array $gatewayResponse = []): void
    {
        $this->status = 'failed';
        $this->gateway_response = array_merge($this->gateway_response ?? [], $gatewayResponse);
        $this->save();
    }

    /**
     * Get successful transactions.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Generate unique transaction ID.
     */
    public static function generateTransactionId(): string
    {
        do {
            $transactionId = 'TXN_' . strtoupper(uniqid() . bin2hex(random_bytes(4)));
        } while (self::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }
}