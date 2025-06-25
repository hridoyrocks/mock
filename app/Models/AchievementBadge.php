<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AchievementBadge extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'category',
        'criteria',
        'points',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_active' => 'boolean',
    ];

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class, 'badge_id');
    }

    public function checkCriteria(User $user): bool
    {
        $criteria = $this->criteria;
        
        switch ($criteria['type']) {
            case 'tests_completed':
                return $user->attempts()->where('status', 'completed')->count() >= $criteria['value'];
                
            case 'band_score_achieved':
                return $user->attempts()->where('band_score', '>=', $criteria['value'])->exists();
                
            case 'study_streak':
                return $user->study_streak_days >= $criteria['value'];
                
            case 'perfect_score':
                return $user->attempts()
                    ->where('band_score', '=', 9.0)
                    ->where('test_set_id', $criteria['test_set_id'] ?? null)
                    ->exists();
                
            case 'all_sections_completed':
                $sections = ['listening', 'reading', 'writing', 'speaking'];
                foreach ($sections as $section) {
                    if (!$user->attempts()->whereHas('testSet.section', function($q) use ($section) {
                        $q->where('name', $section);
                    })->exists()) {
                        return false;
                    }
                }
                return true;
                
            case 'improvement':
                $firstScore = $user->attempts()->where('band_score', '>', 0)->orderBy('created_at')->first()?->band_score;
                $latestScore = $user->attempts()->where('band_score', '>', 0)->latest()->first()?->band_score;
                return $firstScore && $latestScore && ($latestScore - $firstScore) >= $criteria['value'];
                
            default:
                return false;
        }
    }

    public static function getDefaultBadges(): array
    {
        return [
            // Milestone Badges
            [
                'name' => 'First Step',
                'slug' => 'first-step',
                'description' => 'Complete your first test',
                'icon' => 'fas fa-shoe-prints',
                'color' => 'green',
                'category' => 'milestone',
                'criteria' => ['type' => 'tests_completed', 'value' => 1],
                'points' => 10,
            ],
            [
                'name' => 'Dedicated Learner',
                'slug' => 'dedicated-learner',
                'description' => 'Complete 10 tests',
                'icon' => 'fas fa-book-reader',
                'color' => 'blue',
                'category' => 'milestone',
                'criteria' => ['type' => 'tests_completed', 'value' => 10],
                'points' => 50,
            ],
            [
                'name' => 'Test Master',
                'slug' => 'test-master',
                'description' => 'Complete 50 tests',
                'icon' => 'fas fa-graduation-cap',
                'color' => 'purple',
                'category' => 'milestone',
                'criteria' => ['type' => 'tests_completed', 'value' => 50],
                'points' => 200,
            ],
            
            // Streak Badges
            [
                'name' => 'Week Warrior',
                'slug' => 'week-warrior',
                'description' => 'Study for 7 days in a row',
                'icon' => 'fas fa-fire',
                'color' => 'orange',
                'category' => 'streak',
                'criteria' => ['type' => 'study_streak', 'value' => 7],
                'points' => 30,
            ],
            [
                'name' => 'Monthly Champion',
                'slug' => 'monthly-champion',
                'description' => 'Study for 30 days in a row',
                'icon' => 'fas fa-fire-flame-curved',
                'color' => 'red',
                'category' => 'streak',
                'criteria' => ['type' => 'study_streak', 'value' => 30],
                'points' => 100,
            ],
            
            // Performance Badges
            [
                'name' => 'Rising Star',
                'slug' => 'rising-star',
                'description' => 'Achieve Band 6.0 or higher',
                'icon' => 'fas fa-star',
                'color' => 'yellow',
                'category' => 'performance',
                'criteria' => ['type' => 'band_score_achieved', 'value' => 6.0],
                'points' => 40,
            ],
            [
                'name' => 'High Achiever',
                'slug' => 'high-achiever',
                'description' => 'Achieve Band 7.0 or higher',
                'icon' => 'fas fa-trophy',
                'color' => 'gold',
                'category' => 'performance',
                'criteria' => ['type' => 'band_score_achieved', 'value' => 7.0],
                'points' => 80,
            ],
            [
                'name' => 'IELTS Expert',
                'slug' => 'ielts-expert',
                'description' => 'Achieve Band 8.0 or higher',
                'icon' => 'fas fa-crown',
                'color' => 'purple',
                'category' => 'performance',
                'criteria' => ['type' => 'band_score_achieved', 'value' => 8.0],
                'points' => 150,
            ],
            
            // Special Badges
            [
                'name' => 'All-Rounder',
                'slug' => 'all-rounder',
                'description' => 'Complete tests in all four sections',
                'icon' => 'fas fa-circle-notch',
                'color' => 'indigo',
                'category' => 'special',
                'criteria' => ['type' => 'all_sections_completed'],
                'points' => 60,
            ],
            [
                'name' => 'Improvement Master',
                'slug' => 'improvement-master',
                'description' => 'Improve your score by 2.0 bands',
                'icon' => 'fas fa-chart-line',
                'color' => 'teal',
                'category' => 'special',
                'criteria' => ['type' => 'improvement', 'value' => 2.0],
                'points' => 100,
            ],
        ];
    }
}
