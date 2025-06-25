<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGoal extends Model
{
    protected $fillable = [
        'user_id',
        'target_band_score',
        'target_date',
        'exam_type',
        'study_reason',
        'weekly_study_hours',
        'section_targets',
        'is_active'
    ];

    protected $casts = [
        'target_band_score' => 'float',
        'target_date' => 'date',
        'section_targets' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->target_date) {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->target_date, false));
    }

    public function getProgressPercentageAttribute(): float
    {
        if (!$this->target_band_score || !$this->user->average_band_score) {
            return 0;
        }
        
        $startScore = 4.0; // Assuming starting score
        $currentScore = $this->user->average_band_score;
        $targetScore = $this->target_band_score;
        
        $progress = (($currentScore - $startScore) / ($targetScore - $startScore)) * 100;
        
        return max(0, min(100, $progress));
    }
}