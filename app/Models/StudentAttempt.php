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
        'feedback'
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'band_score' => 'decimal:1',
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