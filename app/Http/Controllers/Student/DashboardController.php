<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\TestSection;
use App\Models\UserGoal;
use App\Models\UserAchievement;
use App\Models\AchievementBadge;
use App\Models\LeaderboardEntry;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $achievementService;

    public function __construct()
    {
        $this->achievementService = app(AchievementService::class);
    }

    /**
     * Display the student dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Update study streak if user has activity today
        $this->achievementService->updateStudyStreak($user);
        
        // Get recent attempts for this student
        $recentAttempts = $user->attempts()
            ->with(['testSet.section'])
            ->latest()
            ->take(3)
            ->get();

        // Get student statistics
        $averageBandScore = $user->attempts()
            ->whereNotNull('band_score')
            ->avg('band_score');
        
        // Round to nearest 0.5 (IELTS official format)
        $averageBandScore = $averageBandScore ? round($averageBandScore * 2) / 2 : null;
        
        $stats = [
            'total_attempts' => $user->attempts()->count(),
            'completed_attempts' => $user->attempts()->where('status', 'completed')->count(),
            'in_progress_attempts' => $user->attempts()->where('status', 'in_progress')->count(),
            'average_band_score' => $averageBandScore,
        ];

        // Get section-wise performance
        $sectionPerformance = TestSection::with(['testSets.attempts' => function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'completed')
                  ->whereNotNull('band_score');
        }])->get()->map(function($section) {
            $attempts = $section->testSets->flatMap->attempts;
            $averageScore = $attempts->avg('band_score');
            $bestScore = $attempts->max('band_score');
            
            // Round to nearest 0.5 (IELTS official format)
            $averageScore = $averageScore ? round($averageScore * 2) / 2 : null;
            $bestScore = $bestScore ? round($bestScore * 2) / 2 : null;
            
            return [
                'name' => $section->name,
                'attempts_count' => $attempts->count(),
                'average_score' => $averageScore,
                'best_score' => $bestScore,
            ];
        });

        // Get available test sections with active test sets
        $testSections = TestSection::with(['testSets' => function($query) {
            $query->where('active', 1);
        }])->get();

        // Get user's goal
        $userGoal = UserGoal::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        // Get recent achievements
        $recentAchievements = UserAchievement::where('user_id', $user->id)
            ->with('badge')
            ->latest('earned_at')
            ->take(6)
            ->get();

        // Get all badges for modal
        $allBadges = AchievementBadge::where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        // Get user's earned badges
        $userAchievements = $user->achievements;

        // Get progress to next achievements
        $progressToNext = collect($this->achievementService->getProgressToNextAchievements($user));

        // Get leaderboard data
        $leaderboard = LeaderboardEntry::where('period', 'weekly')
            ->where('category', 'overall')
            ->where('period_start', now()->startOfWeek())
            ->with('user')
            ->orderBy('rank')
            ->take(10)
            ->get();

        // Check if user is in top 10
        $userInLeaderboard = $leaderboard->where('user_id', $user->id)->isNotEmpty();

        // Section icons for display
        $icons = [
            'listening' => 'fa-headphones',
            'reading' => 'fa-book-open',
            'writing' => 'fa-pen-fancy',
            'speaking' => 'fa-microphone',
        ];

        return view('student.dashboard', compact(
            'recentAttempts',
            'stats',
            'sectionPerformance',
            'testSections',
            'userGoal',
            'recentAchievements',
            'allBadges',
            'userAchievements',
            'progressToNext',
            'leaderboard',
            'userInLeaderboard',
            'icons'
        ));
    }

    /**
     * Get leaderboard data for AJAX requests
     */
    public function getLeaderboard(Request $request, $period = 'weekly')
    {
        $validPeriods = ['daily', 'weekly', 'monthly', 'all_time'];
        $period = in_array($period, $validPeriods) ? $period : 'weekly';

        // Update leaderboard data
        LeaderboardEntry::updateLeaderboard($period, 'overall');

        // Get leaderboard entries
        $startDate = match($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'all_time' => null,
        };

        $query = LeaderboardEntry::where('period', $period)
            ->where('category', 'overall');
            
        if ($startDate) {
            $query->where('period_start', $startDate);
        }

        $leaderboard = $query->with('user')
            ->orderBy('rank')
            ->take(10)
            ->get();

        $userInLeaderboard = $leaderboard->where('user_id', auth()->id())->isNotEmpty();

        return view('partials.leaderboard-content', compact('leaderboard', 'userInLeaderboard'));
    }

    /**
     * Get top 100 leaderboard data for modal
     */
    public function getTop100Leaderboard($period = 'weekly')
    {
        $validPeriods = ['daily', 'weekly', 'monthly', 'all_time'];
        $period = in_array($period, $validPeriods) ? $period : 'weekly';

        // Update leaderboard data
        LeaderboardEntry::updateLeaderboard($period, 'overall');

        // Get leaderboard entries
        $startDate = match($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'all_time' => null,
        };

        $query = LeaderboardEntry::where('period', $period)
            ->where('category', 'overall');
            
        if ($startDate) {
            $query->where('period_start', $startDate);
        }

        $leaderboard = $query->with('user')
            ->orderBy('rank')
            ->take(100)
            ->get();

        return response()->json([
            'leaderboard' => $leaderboard,
            'currentUser' => auth()->id(),
            'period' => $period,
            'total' => $leaderboard->count()
        ]);
    }

    /**
     * Store user's goal
     */
    public function storeGoal(Request $request)
    {
        $request->validate([
            'target_band_score' => 'required|numeric|min:1|max:9|regex:/^\d+(\.[05])?$/',
            'target_date' => 'required|date|after:today',
            'study_reason' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Deactivate existing goals
        UserGoal::where('user_id', $user->id)->update(['is_active' => false]);

        // Create new goal
        UserGoal::create([
            'user_id' => $user->id,
            'target_band_score' => $request->target_band_score,
            'target_date' => $request->target_date,
            'study_reason' => $request->study_reason,
            'is_active' => true,
        ]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Your IELTS goal has been set successfully!');
    }

    /**
     * Mark achievements as seen
     */
    public function markAchievementsSeen(Request $request)
    {
        $user = auth()->user();
        
        UserAchievement::where('user_id', $user->id)
            ->where('is_seen', false)
            ->update(['is_seen' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get achievement details
     */
    public function getAchievementDetails($badgeId)
    {
        $badge = AchievementBadge::findOrFail($badgeId);
        $userHasEarned = auth()->user()->achievements()
            ->where('badge_id', $badgeId)
            ->exists();

        return response()->json([
            'badge' => $badge,
            'earned' => $userHasEarned,
            'earned_at' => $userHasEarned ? auth()->user()->achievements()
                ->where('badge_id', $badgeId)
                ->first()
                ->earned_at
                ->format('M d, Y') : null,
        ]);
    }
}