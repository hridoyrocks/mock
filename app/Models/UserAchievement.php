<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'badge_id',
        'earned_at',
        'metadata',
        'is_seen'
    ];

    protected $casts = [
        'earned_at' => 'datetime',
        'metadata' => 'array',
        'is_seen' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(AchievementBadge::class, 'badge_id');
    }
}