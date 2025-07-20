<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HumanEvaluationRequest extends Model
{
    protected $fillable = [
        'student_attempt_id',
        'student_id',
        'teacher_id',
        'tokens_used',
        'status',
        'priority',
        'requested_at',
        'assigned_at',
        'completed_at',
        'deadline_at'
    ];
    
    protected $casts = [
        'requested_at' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'deadline_at' => 'datetime'
    ];
    
    public function studentAttempt(): BelongsTo
    {
        return $this->belongsTo(StudentAttempt::class);
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    
    public function humanEvaluation(): HasOne
    {
        return $this->hasOne(HumanEvaluation::class, 'evaluation_request_id');
    }
    
    /**
     * Assign teacher to evaluation request
     */
    public function assignTeacher(Teacher $teacher): void
    {
        $this->teacher_id = $teacher->id;
        $this->status = 'assigned';
        $this->assigned_at = now();
        
        // Set deadline based on priority
        $hours = $this->priority === 'urgent' ? 12 : 48;
        $this->deadline_at = now()->addHours($hours);
        
        $this->save();
    }
    
    /**
     * Mark as completed
     */
    public function markCompleted(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
        
        // Update teacher statistics
        if ($this->teacher) {
            $this->teacher->updateStatistics();
        }
    }
    
    /**
     * Check if request is overdue
     */
    public function isOverdue(): bool
    {
        return $this->deadline_at && 
               $this->deadline_at->isPast() && 
               $this->status !== 'completed';
    }
    
    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'assigned' => 'blue',
            'in_progress' => 'purple',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }
}
