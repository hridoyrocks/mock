<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestSet extends Model
{
    protected $fillable = ['title', 'section_id', 'active'];
    
    protected $casts = [
        'active' => 'boolean',
    ];
    
    public function section(): BelongsTo
    {
        return $this->belongsTo(TestSection::class, 'section_id');
    }
    
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order_number');
    }
    
    public function attempts(): HasMany
    {
        return $this->hasMany(StudentAttempt::class);
    }
}