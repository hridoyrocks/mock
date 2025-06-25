<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardEntry extends Model
{
    protected $fillable = [
        'user_id',
        'period',
        'category',
        'average_score',
        'tests_taken',
        'total_points',
        'rank',
        'period_start',
        'period_end'
    ];

    protected $casts = [
        'average_score' => 'float',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function updateLeaderboard(string $period = 'weekly', string $category = 'overall')
    {
        $startDate = match($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'all_time' => null,
        };

        $endDate = match($period) {
            'daily' => now()->endOfDay(),
            'weekly' => now()->endOfWeek(),
            'monthly' => now()->endOfMonth(),
            'all_time' => null,
        };

        // Get users with their scores
        $query = User::where('is_admin', false)
            ->where('show_on_leaderboard', true)
            ->with(['attempts' => function($q) use ($startDate, $endDate, $category) {
                $q->where('status', 'completed')
                  ->whereNotNull('band_score');
                
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $q->where('created_at', '<=', $endDate);
                }
                
                if ($category !== 'overall') {
                    $q->whereHas('testSet.section', function($sq) use ($category) {
                        $sq->where('name', $category);
                    });
                }
            }]);

        $users = $query->get()->map(function($user) {
            $attempts = $user->attempts;
            return [
                'user_id' => $user->id,
                'average_score' => $attempts->avg('band_score') ?: 0,
                'tests_taken' => $attempts->count(),
                'total_points' => $user->achievement_points,
            ];
        })->filter(function($entry) {
            return $entry['tests_taken'] > 0;
        })->sortByDesc(function($entry) {
            // Sort by average score, then by total points
            return $entry['average_score'] * 1000 + $entry['total_points'];
        })->values();

        // Clear existing entries
        self::where('period', $period)
            ->where('category', $category)
            ->where('period_start', $startDate ?: '1900-01-01')
            ->delete();

        // Insert new entries
        $rank = 1;
        foreach ($users as $userData) {
            self::create([
                'user_id' => $userData['user_id'],
                'period' => $period,
                'category' => $category,
                'average_score' => round($userData['average_score'], 1),
                'tests_taken' => $userData['tests_taken'],
                'total_points' => $userData['total_points'],
                'rank' => $rank++,
                'period_start' => $startDate ?: '1900-01-01',
                'period_end' => $endDate,
            ]);
        }
    }
}