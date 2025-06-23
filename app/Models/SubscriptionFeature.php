<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubscriptionFeature extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'icon'
    ];

    /**
     * Get the plans that have this feature.
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'plan_feature', 'feature_id', 'plan_id')
            ->withPivot('value', 'limit')
            ->withTimestamps();
    }

    /**
     * Check if feature is available for a plan.
     */
    public function isAvailableForPlan(SubscriptionPlan $plan): bool
    {
        return $this->plans()->where('plan_id', $plan->id)->exists();
    }

    /**
     * Get feature value for a plan.
     */
    public function getValueForPlan(SubscriptionPlan $plan)
    {
        $pivot = $this->plans()->where('plan_id', $plan->id)->first();
        
        if (!$pivot) {
            return null;
        }

        return $pivot->pivot->value ?? $pivot->pivot->limit;
    }
}