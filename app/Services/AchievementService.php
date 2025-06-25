<?php

namespace App\Services;

use App\Models\User;
use App\Models\AchievementBadge;
use App\Models\UserAchievement;
use App\Notifications\AchievementEarned;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    /**
     * Check all achievements for a user
     */
    public function checkAllAchievements(User $user): array
    {
        $earnedBadges = [];
        
        $badges = AchievementBadge::where('is_active', true)->get();
        
        foreach ($badges as $badge) {
            if ($this->checkAndAwardBadge($user, $badge)) {
                $earnedBadges[] = $badge;
            }
        }
        
        return $earnedBadges;
    }

    /**
     * Check and award a specific badge
     */
    public function checkAndAwardBadge(User $user, AchievementBadge $badge): bool
    {
        // Check if already earned
        if ($user->achievements()->where('badge_id', $badge->id)->exists()) {
            return false;
        }

        // Check criteria
        if (!$badge->checkCriteria($user)) {
            return false;
        }

        // Award badge
        DB::transaction(function () use ($user, $badge) {
            UserAchievement::create([
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'earned_at' => now(),
                'metadata' => [
                    'user_stats' => [
                        'total_tests' => $user->attempts()->count(),
                        'average_score' => $user->attempts()->avg('band_score'),
                        'study_streak' => $user->study_streak_days,
                    ]
                ]
            ]);

            // Add points to user
            $user->increment('achievement_points', $badge->points);

            // Send notification
            $user->notify(new AchievementEarned($badge));
        });

        return true;
    }

    /**
     * Update user's study streak
     */
    public function updateStudyStreak(User $user): void
    {
        $today = now()->toDateString();
        $lastStudyDate = $user->last_study_date;

        if (!$lastStudyDate) {
            // First time studying
            $user->update([
                'study_streak_days' => 1,
                'last_study_date' => $today,
            ]);
        } elseif ($lastStudyDate->toDateString() === $today) {
            // Already studied today
            return;
        } elseif ($lastStudyDate->addDay()->toDateString() === $today) {
            // Consecutive day
            $user->increment('study_streak_days');
            $user->update(['last_study_date' => $today]);
        } else {
            // Streak broken
            $user->update([
                'study_streak_days' => 1,
                'last_study_date' => $today,
            ]);
        }

        // Check streak achievements
        $this->checkStreakAchievements($user);
    }

    /**
     * Check achievements after completing a test
     */
    public function checkTestCompletionAchievements(User $user): array
    {
        $earnedBadges = [];
        
        // Check milestone achievements
        $testCounts = [1, 5, 10, 25, 50, 100];
        $completedTests = $user->attempts()->where('status', 'completed')->count();
        
        foreach ($testCounts as $count) {
            if ($completedTests === $count) {
                $badge = AchievementBadge::where('criteria->type', 'tests_completed')
                    ->where('criteria->value', $count)
                    ->first();
                    
                if ($badge && $this->checkAndAwardBadge($user, $badge)) {
                    $earnedBadges[] = $badge;
                }
            }
        }

        // Check performance achievements
        $latestScore = $user->attempts()->latest()->first()->band_score ?? 0;
        $performanceBadges = AchievementBadge::where('criteria->type', 'band_score_achieved')
            ->where('is_active', true)
            ->get();
            
        foreach ($performanceBadges as $badge) {
            if ($this->checkAndAwardBadge($user, $badge)) {
                $earnedBadges[] = $badge;
            }
        }

        // Check special achievements
        $specialBadges = AchievementBadge::where('category', 'special')
            ->where('is_active', true)
            ->get();
            
        foreach ($specialBadges as $badge) {
            if ($this->checkAndAwardBadge($user, $badge)) {
                $earnedBadges[] = $badge;
            }
        }

        return $earnedBadges;
    }

    /**
     * Check streak-related achievements
     */
    private function checkStreakAchievements(User $user): array
    {
        $earnedBadges = [];
        
        $streakBadges = AchievementBadge::where('criteria->type', 'study_streak')
            ->where('is_active', true)
            ->get();
            
        foreach ($streakBadges as $badge) {
            if ($this->checkAndAwardBadge($user, $badge)) {
                $earnedBadges[] = $badge;
            }
        }

        return $earnedBadges;
    }

    /**
     * Get user's progress towards next achievements
     */
    public function getProgressToNextAchievements(User $user): array
    {
        $progress = [];
        
        // Get unearned badges
        $earnedBadgeIds = $user->achievements()->pluck('badge_id');
        $unearnedBadges = AchievementBadge::whereNotIn('id', $earnedBadgeIds)
            ->where('is_active', true)
            ->get();
            
        foreach ($unearnedBadges as $badge) {
            $criteria = $badge->criteria;
            $currentProgress = 0;
            $targetValue = $criteria['value'] ?? 0;
            
            switch ($criteria['type']) {
                case 'tests_completed':
                    $currentProgress = $user->attempts()->where('status', 'completed')->count();
                    break;
                    
                case 'study_streak':
                    $currentProgress = $user->study_streak_days;
                    break;
                    
                case 'band_score_achieved':
                    $currentProgress = $user->attempts()->max('band_score') ?? 0;
                    break;
            }
            
            if ($targetValue > 0) {
                $percentage = min(100, ($currentProgress / $targetValue) * 100);
                
                if ($percentage > 0) {
                    $progress[] = [
                        'badge' => $badge,
                        'current' => $currentProgress,
                        'target' => $targetValue,
                        'percentage' => round($percentage, 1),
                    ];
                }
            }
        }

        return collect($progress)->sortByDesc('percentage')->take(3)->values()->all();
    }
}