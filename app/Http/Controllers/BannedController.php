<?php

namespace App\Http\Controllers;

use App\Models\BanAppeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedController extends Controller
{
    /**
     * Display the banned page
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isBanned()) {
            return redirect()->route('dashboard');
        }
        
        $latestAppeal = $user->latestBanAppeal;
        
        return view('banned.index', compact('user', 'latestAppeal'));
    }
    
    /**
     * Submit an appeal
     */
    public function appeal(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isBanned()) {
            return redirect()->route('dashboard');
        }
        
        // Check if user already has a pending appeal
        if ($user->hasPendingAppeal()) {
            return back()->with('error', 'You already have a pending appeal. Please wait for the admin to review it.');
        }
        
        $request->validate([
            'appeal_reason' => 'required|string|min:50|max:1000'
        ], [
            'appeal_reason.required' => 'Please provide a reason for your appeal.',
            'appeal_reason.min' => 'Your appeal reason must be at least 50 characters long.',
            'appeal_reason.max' => 'Your appeal reason cannot exceed 1000 characters.'
        ]);
        
        BanAppeal::create([
            'user_id' => $user->id,
            'appeal_reason' => $request->appeal_reason,
            'status' => 'pending'
        ]);
        
        return back()->with('success', 'Your appeal has been submitted successfully. An admin will review it soon.');
    }
}
