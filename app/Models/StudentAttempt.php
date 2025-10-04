<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'ai_evaluated_at',     // Added
        'attempt_number',      // Added for retake
        'is_retake',          // Added for retake
        'original_attempt_id' // Added for retake
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
        'attempt_number' => 'integer',        // Added for retake
        'is_retake' => 'boolean',            // Added for retake
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
    
    /**
     * Get the original attempt (for retakes)
     */
    public function originalAttempt(): BelongsTo
    {
        return $this->belongsTo(StudentAttempt::class, 'original_attempt_id');
    }
    
    /**
     * Get all retakes for this attempt
     */
    public function retakes(): HasMany
    {
        return $this->hasMany(StudentAttempt::class, 'original_attempt_id');
    }
    
    /**
     * Get all attempts for a user and test set (including retakes)
     */
    public static function getAllAttemptsForUserAndTest($userId, $testSetId)
    {
        return self::where('user_id', $userId)
            ->where('test_set_id', $testSetId)
            ->orderBy('attempt_number', 'desc')
            ->get();
    }
    
    /**
     * Get the latest attempt for a user and test set
     */
    public static function getLatestAttempt($userId, $testSetId)
    {
        return self::where('user_id', $userId)
            ->where('test_set_id', $testSetId)
            ->orderBy('attempt_number', 'desc')
            ->orderBy('id', 'desc')  // Fallback for old records without attempt_number
            ->first();
    }
    
    /**
     * Check if user can retake this test
     */
    public function canRetake(): bool
    {
        // Only completed tests can be retaken
        if ($this->status !== 'completed') {
            return false;
        }
        
        // Check if this is already the latest attempt
        $latestAttempt = self::getLatestAttempt($this->user_id, $this->test_set_id);
        return $this->id === $latestAttempt->id;
    }
    
    /**
     * Get AI evaluation jobs for this attempt
     */
    public function aiEvaluationJobs(): HasMany
    {
        return $this->hasMany(AIEvaluationJob::class, 'attempt_id');
    }
    
    /**
     * Get human evaluation request for this attempt
     */
    public function humanEvaluationRequest(): HasOne
    {
        return $this->hasOne(HumanEvaluationRequest::class, 'student_attempt_id');
    }
}