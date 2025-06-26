<?php

namespace App\Services;

use App\Models\User;
use App\Models\AchievementBadge;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AchievementService
{
    /**
     * Update user's study streak
     */
    public function updateStudyStreak(User $user): void
    {
        try {
            $today = now()->format('Y-m-d');
            $lastStudyDateString = $user->last_study_date;

            // If no last study date, start streak
            if (!$lastStudyDateString) {
                $user->update([
                    'study_streak_days' => 1,
                    'last_study_date' => $today,
                ]);
                return;
            }

            // Parse the date string
            $lastStudyDate = \Carbon\Carbon::parse($lastStudyDateString);
            $todayCarbon = \Carbon\Carbon::parse($today);

            // If already studied today, do nothing
            if ($lastStudyDate->format('Y-m-d') === $today) {
                return;
            }

            // Check if it's the next day
            $daysDiff = $lastStudyDate->diffInDays($todayCarbon);
            
            if ($daysDiff === 1) {
                // Consecutive day - increment streak
                $user->increment('study_streak_days');
                $user->update(['last_study_date' => $today]);
            } else {
                // Streak broken - reset to 1
                $user->update([
                    'study_streak_days' => 1,
                    'last_study_date' => $today,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Study streak update failed: ' . $e->getMessage());
        }
    }

    /**
     * Get user's progress towards next achievements
     */
    public function getProgressToNextAchievements(User $user): array
    {
        try {
            $progress = [];
            
            // Get unearned badges
            $earnedBadgeIds = $user->achievements()->pluck('badge_id')->toArray();
            $unearnedBadges = AchievementBadge::whereNotIn('id', $earnedBadgeIds)
                ->where('is_active', true)
                ->get();
                
            foreach ($unearnedBadges as $badge) {
                $criteria = $badge->criteria;
                if (!$criteria || !isset($criteria['type'])) {
                    continue;
                }
                
                $currentProgress = 0;
                $targetValue = $criteria['value'] ?? 0;
                
                switch ($criteria['type']) {
                    case 'tests_completed':
                        $currentProgress = $user->attempts()->where('status', 'completed')->count();
                        break;
                        
                    case 'study_streak':
                        $currentProgress = $user->study_streak_days ?? 0;
                        break;
                        
                    case 'band_score_achieved':
                        $currentProgress = $user->attempts()->max('band_score') ?? 0;
                        break;
                }
                
                if ($targetValue > 0 && $currentProgress > 0) {
                    $percentage = min(100, ($currentProgress / $targetValue) * 100);
                    
                    $progress[] = [
                        'badge' => $badge,
                        'current' => $currentProgress,
                        'target' => $targetValue,
                        'percentage' => round($percentage, 1),
                    ];
                }
            }

            return collect($progress)->sortByDesc('percentage')->take(3)->values()->all();
            
        } catch (\Exception $e) {
            Log::error('Error getting achievement progress: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Check and award a specific badge
     */
    public function checkAndAwardBadge(User $user, AchievementBadge $badge): bool
    {
        try {
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
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Error awarding badge: ' . $e->getMessage());
            return false;
        }
    }
}