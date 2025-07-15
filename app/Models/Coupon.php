<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'plan_id',
        'duration_days',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'created_by',
        'metadata'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
        'discount_value' => 'decimal:2',
    ];

    // Relations
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->active()
            ->where(function ($q) use ($now) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', $now);
            });
    }

    // Methods
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function hasBeenUsedByUser(User $user): bool
    {
        return $this->redemptions()->where('user_id', $user->id)->exists();
    }

    public function canBeUsedByUser(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->hasBeenUsedByUser($user)) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $originalPrice): array
    {
        $discountAmount = 0;
        $finalPrice = $originalPrice;

        switch ($this->discount_type) {
            case 'percentage':
                $discountAmount = ($originalPrice * $this->discount_value) / 100;
                $finalPrice = $originalPrice - $discountAmount;
                break;
                
            case 'fixed':
                $discountAmount = min($this->discount_value, $originalPrice);
                $finalPrice = $originalPrice - $discountAmount;
                break;
                
            case 'full_access':
            case 'trial':
                $discountAmount = $originalPrice;
                $finalPrice = 0;
                break;
        }

        return [
            'original_price' => $originalPrice,
            'discount_amount' => round($discountAmount, 2),
            'final_price' => round(max(0, $finalPrice), 2),
            'discount_percentage' => $originalPrice > 0 ? round(($discountAmount / $originalPrice) * 100, 2) : 0
        ];
    }

    public function getFormattedDiscountAttribute(): string
    {
        switch ($this->discount_type) {
            case 'percentage':
                return $this->discount_value . '% OFF';
            case 'fixed':
                return '৳' . number_format($this->discount_value, 0) . ' OFF';
            case 'full_access':
                return '100% FREE';
            case 'trial':
                return $this->duration_days . ' Days Free Trial';
            default:
                return 'Special Offer';
        }
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    // Static method to generate unique code
    public static function generateUniqueCode(string $prefix = 'CD', int $length = 8): string
    {
        do {
            $code = $prefix . strtoupper(bin2hex(random_bytes($length / 2)));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}