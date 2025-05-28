<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['test_set_id', 'question_type', 'content', 'media_path', 'order_number'];
    
    public function testSet(): BelongsTo
    {
        return $this->belongsTo(TestSet::class);
    }
    
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }
    
    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}