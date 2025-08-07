<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BanAppeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BanAppealController extends Controller
{
    /**
     * Display a listing of ban appeals
     */
    public function index(Request $request)
    {
        $query = BanAppeal::with(['user', 'reviewer']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by user
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $appeals = $query->latest()->paginate(20);
        
        return view('admin.ban-appeals.index', compact('appeals'));
    }
    
    /**
     * Show the form for reviewing an appeal
     */
    public function show(BanAppeal $banAppeal)
    {
        $banAppeal->load(['user.bannedBy', 'reviewer']);
        
        return view('admin.ban-appeals.show', compact('banAppeal'));
    }
    
    /**
     * Approve the appeal
     */
    public function approve(Request $request, BanAppeal $banAppeal)
    {
        $request->validate([
            'admin_response' => 'required|string|max:500'
        ]);
        
        // Update appeal
        $banAppeal->update([
            'status' => 'approved',
            'admin_response' => $request->admin_response,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);
        
        // Unban the user
        $banAppeal->user->unban();
        
        // Send notification email to user
        $banAppeal->user->notify(new \App\Notifications\BanAppealApproved($request->admin_response));
        
        return redirect()->route('admin.ban-appeals.index')
            ->with('success', 'Ban appeal approved and user has been unbanned.');
    }
    
    /**
     * Reject the appeal
     */
    public function reject(Request $request, BanAppeal $banAppeal)
    {
        $request->validate([
            'admin_response' => 'required|string|max:500'
        ]);
        
        // Update appeal
        $banAppeal->update([
            'status' => 'rejected',
            'admin_response' => $request->admin_response,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);
        
        // Send notification email to user
        $banAppeal->user->notify(new \App\Notifications\BanAppealRejected($request->admin_response));
        
        return redirect()->route('admin.ban-appeals.index')
            ->with('success', 'Ban appeal has been rejected.');
    }
}
