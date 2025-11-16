<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TestSet extends Model
{
    protected $fillable = ['title', 'section_id', 'active', 'is_premium'];

    protected $casts = [
        'active' => 'boolean',
        'is_premium' => 'boolean',
    ];

    /**
     * Scope for free test sets
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Scope for premium test sets
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }
    
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
    // First check if full audio exists (part_number = 0)
    $fullAudio = $this->partAudios()->where('part_number', 0)->first();
    
    // If full audio exists, return it for any part
    if ($fullAudio) {
        return $fullAudio;
    }
    
    // Otherwise return specific part audio
    return $this->partAudios()->where('part_number', $partNumber)->first();
}

public function hasPartAudio($partNumber): bool
{
    // Check if full audio exists (part_number = 0)
    $hasFullAudio = $this->partAudios()->where('part_number', 0)->exists();
    
    // If full audio exists, all parts have audio
    if ($hasFullAudio) {
        return true;
    }
    
    // Otherwise check for specific part audio
    return $this->partAudios()->where('part_number', $partNumber)->exists();
}

    /**
     * Categories that this test set belongs to
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TestCategory::class, 'test_category_test_set')
            ->withTimestamps();
    }

    /**
     * Check if test set belongs to a specific category
     */
    public function belongsToCategory(string $categorySlug): bool
    {
        return $this->categories()->where('slug', $categorySlug)->exists();
    }

    /**
     * Get primary category (first one)
     */
    public function getPrimaryCategoryAttribute()
    {
        return $this->categories()->orderBy('sort_order')->first();
    }

    /**
     * Full tests that this test set belongs to
     */
    public function fullTests(): BelongsToMany
    {
        return $this->belongsToMany(FullTest::class, 'full_test_sets')
            ->withPivot('section_type', 'order_number')
            ->withTimestamps();
    }
}