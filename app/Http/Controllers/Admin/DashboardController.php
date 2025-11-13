<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSection;
use App\Models\TestSet;
use App\Models\Question;
use App\Models\StudentAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Check permission
        if (!auth()->user()->hasPermission('dashboard.view')) {
            abort(403, 'You do not have permission to access the dashboard.');
        }
        // Get all statistics
        $stats = [
            'total_sections' => TestSection::count(),
            'total_test_sets' => TestSet::count(),
            'total_questions' => Question::count(),
            'total_students' => User::where('is_admin', false)->count(),
            'total_attempts' => StudentAttempt::count(),
            'completed_attempts' => StudentAttempt::where('status', 'completed')->count(),
            'pending_evaluations' => StudentAttempt::where('status', 'completed')
                ->whereNull('band_score')
                ->whereHas('testSet.section', function($q) {
                    $q->whereIn('name', ['writing', 'speaking']);
                })
                ->count(),
        ];

// Add coupon stats
    $stats['active_coupons'] = \App\Models\Coupon::active()->valid()->count();
    $stats['total_coupon_redemptions'] = \App\Models\CouponRedemption::count();
    $stats['coupon_discount_given'] = \App\Models\CouponRedemption::sum('discount_amount');

        // Recent activities
        $recent_attempts = StudentAttempt::with(['user', 'testSet.section'])
            ->latest()
            ->take(10)
            ->get();

        // Section wise statistics
        $section_stats = TestSection::withCount([
            'testSets',
            'testSets as total_questions' => function ($query) {
                $query->join('questions', 'test_sets.id', '=', 'questions.test_set_id');
            }
        ])->get();

        // Monthly attempts data for chart
        $monthly_attempts = StudentAttempt::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $chart_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $chart_data[] = $monthly_attempts[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'stats',
            'recent_attempts',
            'section_stats',
            'chart_data'
        ));
    }

    /**
     * Get quick stats for AJAX calls
     */
    public function quickStats()
    {
        // Check permission
        if (!auth()->user()->hasPermission('dashboard.stats')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json([
            'total_students' => User::where('is_admin', false)->count(),
            'online_students' => User::where('is_admin', false)
                ->where('last_seen_at', '>=', now()->subMinutes(5))
                ->count(),
            'today_attempts' => StudentAttempt::whereDate('created_at', today())->count(),
            'pending_evaluations' => StudentAttempt::where('status', 'completed')
                ->whereNull('band_score')
                ->whereHas('testSet.section', function($q) {
                    $q->whereIn('name', ['writing', 'speaking']);
                })
                ->count(),
        ]);
    }
}