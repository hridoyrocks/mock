<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class FullTestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_test_id',
        'start_time',
        'end_time',
        'status',
        'current_section',
        'overall_band_score',
        'listening_score',
        'reading_score',
        'writing_score',
        'speaking_score',
        'feedback'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'overall_band_score' => 'float',
        'listening_score' => 'float',
        'reading_score' => 'float',
        'writing_score' => 'float',
        'speaking_score' => 'float'
    ];

    /**
     * Get the user who took this test.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full test.
     */
    public function fullTest(): BelongsTo
    {
        return $this->belongsTo(FullTest::class);
    }

    /**
     * Get the section attempts.
     */
    public function sectionAttempts(): HasMany
    {
        return $this->hasMany(FullTestSectionAttempt::class);
    }

    /**
     * Get the student attempts through section attempts.
     */
    public function studentAttempts(): HasManyThrough
    {
        return $this->hasManyThrough(
            StudentAttempt::class,
            FullTestSectionAttempt::class,
            'full_test_attempt_id',
            'id',
            'id',
            'student_attempt_id'
        );
    }

    /**
     * Get listening attempt.
     */
    public function listeningAttempt()
    {
        return $this->sectionAttempts()
            ->where('section_type', 'listening')
            ->with('studentAttempt')
            ->first()?->studentAttempt;
    }

    /**
     * Get reading attempt.
     */
    public function readingAttempt()
    {
        return $this->sectionAttempts()
            ->where('section_type', 'reading')
            ->with('studentAttempt')
            ->first()?->studentAttempt;
    }

    /**
     * Get writing attempt.
     */
    public function writingAttempt()
    {
        return $this->sectionAttempts()
            ->where('section_type', 'writing')
            ->with('studentAttempt')
            ->first()?->studentAttempt;
    }

    /**
     * Get speaking attempt.
     */
    public function speakingAttempt()
    {
        return $this->sectionAttempts()
            ->where('section_type', 'speaking')
            ->with('studentAttempt')
            ->first()?->studentAttempt;
    }

    /**
     * Calculate overall band score.
     * Only calculates based on available sections.
     */
    public function calculateOverallScore(): float
    {
        $scores = [];
        
        // Only include scores for sections that exist in the test
        if ($this->fullTest->hasSection('listening') && $this->listening_score !== null) {
            $scores[] = $this->listening_score;
        }
        if ($this->fullTest->hasSection('reading') && $this->reading_score !== null) {
            $scores[] = $this->reading_score;
        }
        if ($this->fullTest->hasSection('writing') && $this->writing_score !== null) {
            $scores[] = $this->writing_score;
        }
        if ($this->fullTest->hasSection('speaking') && $this->speaking_score !== null) {
            $scores[] = $this->speaking_score;
        }

        if (count($scores) === 0) {
            return 0;
        }

        $average = array_sum($scores) / count($scores);
        
        // Round to nearest 0.5
        return round($average * 2) / 2;
    }

    /**
     * Update section score.
     */
    public function updateSectionScore(string $section, float $score): void
    {
        $this->update([
            "{$section}_score" => $score
        ]);

        // Recalculate overall score if all sections completed
        if ($this->hasAllSectionScores()) {
            $this->update([
                'overall_band_score' => $this->calculateOverallScore()
            ]);
        }
    }

    /**
     * Check if all available sections have scores.
     */
    public function hasAllSectionScores(): bool
    {
        $availableSections = $this->fullTest->getAvailableSections();
        $scoredSections = 0;
        
        foreach ($availableSections as $section) {
            $scoreField = $section . '_score';
            if ($this->$scoreField !== null) {
                $scoredSections++;
            }
        }
        
        // All available sections must have scores
        return $scoredSections === count($availableSections);
    }

    /**
     * Get next section to complete.
     */
    public function getNextSection(): ?string
    {
        $availableSections = $this->fullTest->getAvailableSections();
        $completedSections = $this->sectionAttempts()->pluck('section_type')->toArray();
        
        foreach ($availableSections as $section) {
            if (!in_array($section, $completedSections)) {
                return $section;
            }
        }
        
        return null;
    }

    /**
     * Check if test is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark test as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'end_time' => now(),
            'overall_band_score' => $this->calculateOverallScore()
        ]);
    }
}
