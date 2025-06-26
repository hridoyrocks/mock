<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AchievementBadge;

class AchievementBadgesSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
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
                'sort_order' => 1,
            ],
            [
                'name' => 'Getting Started',
                'slug' => 'getting-started',
                'description' => 'Complete 5 tests',
                'icon' => 'fas fa-walking',
                'color' => 'blue',
                'category' => 'milestone',
                'criteria' => ['type' => 'tests_completed', 'value' => 5],
                'points' => 25,
                'sort_order' => 2,
            ],
            [
                'name' => 'Dedicated Learner',
                'slug' => 'dedicated-learner',
                'description' => 'Complete 10 tests',
                'icon' => 'fas fa-book-reader',
                'color' => 'indigo',
                'category' => 'milestone',
                'criteria' => ['type' => 'tests_completed', 'value' => 10],
                'points' => 50,
                'sort_order' => 3,
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
                'sort_order' => 4,
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
                'sort_order' => 1,
            ],
            [
                'name' => 'Consistent Learner',
                'slug' => 'consistent-learner',
                'description' => 'Study for 14 days in a row',
                'icon' => 'fas fa-fire-flame-simple',
                'color' => 'red',
                'category' => 'streak',
                'criteria' => ['type' => 'study_streak', 'value' => 14],
                'points' => 60,
                'sort_order' => 2,
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
                'sort_order' => 3,
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
                'sort_order' => 1,
            ],
            [
                'name' => 'High Achiever',
                'slug' => 'high-achiever',
                'description' => 'Achieve Band 7.0 or higher',
                'icon' => 'fas fa-trophy',
                'color' => 'yellow',
                'category' => 'performance',
                'criteria' => ['type' => 'band_score_achieved', 'value' => 7.0],
                'points' => 80,
                'sort_order' => 2,
            ],
            [
                'name' => 'IELTS Expert',
                'slug' => 'ielts-expert',
                'description' => 'Achieve Band 8.0 or higher',
                'icon' => 'fas fa-crown',
                'color' => 'yellow',
                'category' => 'performance',
                'criteria' => ['type' => 'band_score_achieved', 'value' => 8.0],
                'points' => 150,
                'sort_order' => 3,
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
                'sort_order' => 1,
            ],
            [
                'name' => 'Perfectionist',
                'slug' => 'perfectionist',
                'description' => 'Score Band 9.0 in any test',
                'icon' => 'fas fa-gem',
                'color' => 'purple',
                'category' => 'special',
                'criteria' => ['type' => 'perfect_score'],
                'points' => 200,
                'sort_order' => 2,
            ],
        ];

        foreach ($badges as $badge) {
            AchievementBadge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}