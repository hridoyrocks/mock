<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeakingRecording extends Model
{
    protected $fillable = ['answer_id', 'file_path'];
    
    public function answer(): BelongsTo
    {
        return $this->belongsTo(StudentAnswer::class, 'answer_id');
    }
}