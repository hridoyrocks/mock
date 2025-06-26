<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\AchievementBadge;
use App\Models\LeaderboardEntry;

class UserDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Update User 4 if exists
        $user = User::find(4);
        if ($user) {
            $user->update([
                'achievement_points' => 135,
                'study_streak_days' => 5,
                'last_study_date' => now()->toDateString(),
            ]);

            // Create achievements for user
            $badges = [
                'first-step' => now()->subDays(10),
                'getting-started' => now()->subDays(5),
                'rising-star' => now()->subDays(2),
            ];

            foreach ($badges as $slug => $earnedAt) {
                $badge = AchievementBadge::where('slug', $slug)->first();
                if ($badge) {
                    UserAchievement::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'badge_id' => $badge->id,
                        ],
                        [
                            'earned_at' => $earnedAt,
                            'is_seen' => $slug !== 'rising-star', // Keep one unseen
                        ]
                    );
                }
            }

            // Update leaderboard entries
            LeaderboardEntry::updateOrCreate([
                'user_id' => $user->id,
                'period' => 'weekly',
                'category' => 'overall',
                'period_start' => now()->startOfWeek()
            ], [
                'average_score' => 7.0,
                'tests_taken' => 4,
                'total_points' => 135,
                'rank' => 1,
                'period_end' => now()->endOfWeek()
            ]);
        }

        // Create other dummy users for leaderboard
        $dummyUsers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'achievement_points' => 120,
                'rank' => 2,
                'average_score' => 6.5,
                'tests_taken' => 3,
            ],
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'achievement_points' => 95,
                'rank' => 3,
                'average_score' => 6.0,
                'tests_taken' => 2,
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@example.com',
                'achievement_points' => 80,
                'rank' => 4,
                'average_score' => 5.5,
                'tests_taken' => 2,
            ],
        ];

        foreach ($dummyUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                    'achievement_points' => $userData['achievement_points'],
                ]
            );

            LeaderboardEntry::updateOrCreate([
                'user_id' => $user->id,
                'period' => 'weekly',
                'category' => 'overall',
                'period_start' => now()->startOfWeek()
            ], [
                'average_score' => $userData['average_score'],
                'tests_taken' => $userData['tests_taken'],
                'total_points' => $userData['achievement_points'],
                'rank' => $userData['rank'],
                'period_end' => now()->endOfWeek()
            ]);
        }
    }
}