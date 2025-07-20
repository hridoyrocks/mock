<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenPackage extends Model
{
    protected $fillable = [
        'name',
        'tokens_count',
        'price',
        'bonus_tokens',
        'is_active',
        'sort_order'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];
    
    /**
     * Get total tokens (including bonus)
     */
    public function getTotalTokensAttribute(): int
    {
        return $this->tokens_count + $this->bonus_tokens;
    }
    
    /**
     * Get price per token
     */
    public function getPricePerTokenAttribute(): float
    {
        return $this->total_tokens > 0 ? round($this->price / $this->total_tokens, 4) : 0;
    }
    
    /**
     * Get active packages
     */
    public static function getActivePackages()
    {
        return self::where('is_active', true)
                   ->orderBy('sort_order')
                   ->orderBy('tokens_count')
                   ->get();
    }
}
