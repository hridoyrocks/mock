<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FullTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_premium',
        'active',
        'order_number'
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'active' => 'boolean',
        'order_number' => 'integer'
    ];

    /**
     * Get the test sets for this full test.
     */
    public function testSets(): BelongsToMany
    {
        return $this->belongsToMany(TestSet::class, 'full_test_sets')
            ->withPivot('section_type', 'order_number')
            ->withTimestamps()
            ->orderBy('full_test_sets.order_number');
    }

    /**
     * Get the listening test set.
     */
    public function listeningTestSet()
    {
        return $this->testSets()->wherePivot('section_type', 'listening')->first();
    }

    /**
     * Get the reading test set.
     */
    public function readingTestSet()
    {
        return $this->testSets()->wherePivot('section_type', 'reading')->first();
    }

    /**
     * Get the writing test set.
     */
    public function writingTestSet()
    {
        return $this->testSets()->wherePivot('section_type', 'writing')->first();
    }

    /**
     * Get the speaking test set.
     */
    public function speakingTestSet()
    {
        return $this->testSets()->wherePivot('section_type', 'speaking')->first();
    }

    /**
     * Get all attempts for this full test.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(FullTestAttempt::class);
    }

    /**
     * Check if all sections are assigned.
     */
    public function hasAllSections(): bool
    {
        $sections = $this->testSets()->pluck('full_test_sets.section_type')->toArray();
        $requiredSections = ['listening', 'reading', 'writing', 'speaking'];
        
        return count(array_intersect($requiredSections, $sections)) === 4;
    }
    
    /**
     * Get available sections for this test.
     */
    public function getAvailableSections(): array
    {
        $sections = [];
        
        if ($this->listeningTestSet()) {
            $sections[] = 'listening';
        }
        if ($this->readingTestSet()) {
            $sections[] = 'reading';
        }
        if ($this->writingTestSet()) {
            $sections[] = 'writing';
        }
        if ($this->speakingTestSet()) {
            $sections[] = 'speaking';
        }
        
        return $sections;
    }
    
    /**
     * Check if test has a specific section.
     */
    public function hasSection(string $section): bool
    {
        return in_array($section, $this->getAvailableSections());
    }

    /**
     * Get minimum required sections count.
     */
    public function getMinimumSectionsCount(): int
    {
        return 3; // Minimum 3 sections required
    }

    /**
     * Check if test has minimum required sections.
     */
    public function hasMinimumSections(): bool
    {
        return count($this->getAvailableSections()) >= $this->getMinimumSectionsCount();
    }

    /**
     * Get active full tests.
     */
    public static function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get free full tests.
     */
    public static function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Get premium full tests.
     */
    public static function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }
}
