<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'specialization',
        'rating',
        'experience_years',
        'qualifications',
        'evaluation_price_tokens',
        'total_evaluations_done',
        'average_turnaround_hours',
        'is_available',
        'profile_description',
        'languages'
    ];
    
    protected $casts = [
        'specialization' => 'array',
        'qualifications' => 'array',
        'languages' => 'array',
        'rating' => 'float',
        'average_turnaround_hours' => 'float',
        'is_available' => 'boolean'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function evaluationRequests(): HasMany
    {
        return $this->hasMany(HumanEvaluationRequest::class);
    }
    
    public function completedEvaluations(): HasMany
    {
        return $this->hasMany(HumanEvaluationRequest::class)
                    ->where('status', 'completed');
    }
    
    /**
     * Calculate token price based on section and priority
     */
    public function calculateTokenPrice(string $section, bool $isPriority = false): int
    {
        $basePrice = $this->evaluation_price_tokens;
        
        // Section multiplier (case-insensitive)
        $sectionLower = strtolower($section);
        $sectionMultipliers = [
            'writing' => 1.2,
            'speaking' => 1.1,
            'reading' => 1.0,
            'listening' => 1.0
        ];
        
        $sectionMultiplier = $sectionMultipliers[$sectionLower] ?? 1.0;
        $price = round($basePrice * $sectionMultiplier);
        
        // Add priority fee
        if ($isPriority) {
            $price += 5;
        }
        
        return $price;
    }
    
    /**
     * Update teacher statistics after evaluation
     */
    public function updateStatistics()
    {
        $completedEvaluations = $this->evaluationRequests()
                                     ->where('status', 'completed')
                                     ->get();
        
        $this->total_evaluations_done = $completedEvaluations->count();
        
        // Calculate average turnaround time
        if ($this->total_evaluations_done > 0) {
            $totalHours = 0;
            foreach ($completedEvaluations as $evaluation) {
                $hours = $evaluation->requested_at->diffInHours($evaluation->completed_at);
                $totalHours += $hours;
            }
            $this->average_turnaround_hours = round($totalHours / $this->total_evaluations_done, 2);
        }
        
        $this->save();
    }
    
    /**
     * Check if teacher can handle a specific section
     */
    public function canEvaluateSection(string $section): bool
    {
        return in_array($section, $this->specialization ?? []);
    }
}
