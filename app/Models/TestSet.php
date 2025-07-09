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

    public function partAudios()
{
    return $this->hasMany(TestPartAudio::class)->orderBy('part_number');
}

public function getPartAudio($partNumber)
{
    return $this->partAudios()->where('part_number', $partNumber)->first();
}

public function hasPartAudio($partNumber): bool
{
    return $this->partAudios()->where('part_number', $partNumber)->exists();
}
}