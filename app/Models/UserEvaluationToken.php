<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEvaluationToken extends Model
{
    protected $fillable = [
        'user_id',
        'available_tokens',
        'used_tokens',
        'last_purchased_at'
    ];
    
    protected $casts = [
        'last_purchased_at' => 'datetime'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Add tokens to user account
     */
    public function addTokens(int $amount, string $source = 'purchase'): void
    {
        $this->available_tokens += $amount;
        if ($source === 'purchase') {
            $this->last_purchased_at = now();
        }
        $this->save();
        
        // Log transaction (without using activity log)
        \Illuminate\Support\Facades\Log::info('Tokens added', [
            'user_id' => $this->user_id,
            'amount' => $amount,
            'source' => $source,
            'new_balance' => $this->available_tokens
        ]);
    }
    
    /**
     * Use tokens for evaluation
     */
    public function useTokens(int $amount): bool
    {
        if ($this->available_tokens < $amount) {
            return false;
        }
        
        $this->available_tokens -= $amount;
        $this->used_tokens += $amount;
        $this->save();
        
        return true;
    }
    
    /**
     * Check if user has enough tokens
     */
    public function hasTokens(int $amount): bool
    {
        return $this->available_tokens >= $amount;
    }
    
    /**
     * Get or create token record for user
     */
    public static function getOrCreateForUser(User $user): self
    {
        return self::firstOrCreate(
            ['user_id' => $user->id],
            ['available_tokens' => 0, 'used_tokens' => 0]
        );
    }
}
