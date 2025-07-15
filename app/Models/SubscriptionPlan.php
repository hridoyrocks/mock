<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'discount_price',
        'duration_days',
        'description',
        'features',
        'sort_order',
        'is_active',
        'is_free',
        'is_featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_free' => 'boolean', 
        'is_featured' => 'boolean',
    ];

    /**
     * Get the subscriptions for this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }

    /**
     * Get the features for this plan.
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionFeature::class, 'plan_feature', 'plan_id', 'feature_id')
            ->withPivot('value', 'limit')
            ->withTimestamps();
    }

    /**
     * Check if plan has a specific feature.
     */
    public function hasFeature(string $featureKey): bool
    {
        return $this->features()->where('key', $featureKey)->exists();
    }

    /**
     * Get feature value or limit.
     */
    public function getFeatureValue(string $featureKey)
    {
        $feature = $this->features()->where('key', $featureKey)->first();
        
        if (!$feature) {
            return null;
        }

        return $feature->pivot->value ?? $feature->pivot->limit;
    }

    /**
     * Get active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Get the current price (considering discount).
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check if plan is free.
     */
    public function getIsFreeAttribute(): bool
    {
        return $this->price == 0;
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->discount_price || $this->discount_price >= $this->price) {
            return null;
        }

        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }
}