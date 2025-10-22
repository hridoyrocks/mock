<?php

namespace App\Http\Controllers;

use App\Models\LeaderboardEntry;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get top 3 performers from this week
        $topPerformers = LeaderboardEntry::where('period', 'weekly')
            ->where('category', 'overall')
            ->with('user')
            ->orderBy('rank')
            ->take(3)
            ->get();

        return view('welcome', compact('topPerformers'));
    }
}
