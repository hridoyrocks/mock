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
        if (!$this->target_band_score) {
            return 0;
        }
        
        // Get user's recent average band score
        $recentAttempts = $this->user->attempts()
            ->where('status', 'completed')
            ->whereNotNull('band_score')
            ->latest()
            ->take(5)
            ->get();
        
        if ($recentAttempts->isEmpty()) {
            return 0;
        }
        
        $currentScore = $recentAttempts->avg('band_score');
        $startScore = 4.0; // Assuming starting score
        $targetScore = $this->target_band_score;
        
        // If already achieved target
        if ($currentScore >= $targetScore) {
            return 100;
        }
        
        // Calculate progress percentage
        $progress = (($currentScore - $startScore) / ($targetScore - $startScore)) * 100;
        
        return max(0, min(100, round($progress, 1)));
    }
    
    public function getCurrentBandScoreAttribute(): ?float
    {
        $recentAttempts = $this->user->attempts()
            ->where('status', 'completed')
            ->whereNotNull('band_score')
            ->latest()
            ->take(5)
            ->get();
        
        if ($recentAttempts->isEmpty()) {
            return null;
        }
        
        $averageScore = $recentAttempts->avg('band_score');
        
        // Round to nearest 0.5 (IELTS official format)
        return round($averageScore * 2) / 2;
    }
}