<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentAttempt extends Model
{
    protected $fillable = [
        'user_id', 
        'test_set_id', 
        'start_time', 
        'end_time', 
        'status', 
        'band_score', 
        'feedback',
        'completion_rate',
        'confidence_level',
        'is_complete_attempt',
        'total_questions',
        'answered_questions',
        'correct_answers',
        'ai_band_score',      // Added
        'ai_evaluated_at'     // Added
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'band_score' => 'float',
        'completion_rate' => 'float',
        'is_complete_attempt' => 'boolean',
        'total_questions' => 'integer',
        'answered_questions' => 'integer',
        'correct_answers' => 'integer',
        'ai_band_score' => 'float',           // Added
        'ai_evaluated_at' => 'datetime',      // Added
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function testSet(): BelongsTo
    {
        return $this->belongsTo(TestSet::class);
    }
    
    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'attempt_id');
    }
}