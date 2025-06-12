<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\TestSection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get recent attempts for this student
        $recentAttempts = $user->attempts()
            ->with(['testSet.section'])
            ->latest()
            ->take(3)
            ->get();

        // Get student statistics
        $stats = [
            'total_attempts' => $user->attempts()->count(),
            'completed_attempts' => $user->attempts()->where('status', 'completed')->count(),
            'in_progress_attempts' => $user->attempts()->where('status', 'in_progress')->count(),
            'average_band_score' => $user->attempts()
                ->whereNotNull('band_score')
                ->avg('band_score'),
        ];

        // Get section-wise performance
        $sectionPerformance = TestSection::with(['testSets.attempts' => function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'completed')
                  ->whereNotNull('band_score');
        }])->get()->map(function($section) {
            $attempts = $section->testSets->flatMap->attempts;
            return [
                'name' => $section->name,
                'attempts_count' => $attempts->count(),
                'average_score' => $attempts->avg('band_score'),
                'best_score' => $attempts->max('band_score'),
            ];
        });

        // Get available test sections with active test sets
        $testSections = TestSection::with(['testSets' => function($query) {
            $query->where('active', 1);
        }])->get();

        return view('student.dashboard', compact(
            'recentAttempts',
            'stats',
            'sectionPerformance',
            'testSections'
        ));
    }

    /**
     * Get student progress data for charts
     */
    public function progressData()
    {
        $user = auth()->user();
        
        // Monthly progress
        $monthlyProgress = $user->attempts()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as attempts, AVG(band_score) as avg_score')
            ->whereYear('created_at', date('Y'))
            ->whereNotNull('band_score')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('avg_score', 'month')
            ->toArray();

        // Fill missing months with null
        $progressData = [];
        for ($i = 1; $i <= 12; $i++) {
            $progressData[] = $monthlyProgress[$i] ?? null;
        }

        return response()->json([
            'monthly_progress' => $progressData,
            'latest_attempts' => $user->attempts()
                ->with('testSet.section')
                ->latest()
                ->take(5)
                ->get()
        ]);
    }
}