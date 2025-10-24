<?php

namespace App\Http\Controllers;

use App\Models\LeaderboardEntry;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Update leaderboard data first
        LeaderboardEntry::updateLeaderboard('weekly', 'overall');
        
        // Get current week's start date
        $startDate = now()->startOfWeek();
        
        // Get top 3 performers from this week (unique users)
        $topPerformers = LeaderboardEntry::where('period', 'weekly')
            ->where('category', 'overall')
            ->where('period_start', $startDate)
            ->with('user')
            ->orderBy('rank')
            ->take(3)
            ->get();

        return view('welcome', compact('topPerformers'));
    }
}
