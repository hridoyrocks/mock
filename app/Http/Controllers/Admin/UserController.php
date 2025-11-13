<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality - trim and lowercase for better matching
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role - Fixed the logic
        if ($request->filled('role')) {
            switch ($request->role) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'teacher':
                    $query->whereHas('teacher')
                          ->where('is_admin', false); // Teachers are not admins
                    break;
                case 'student':
                    $query->where('is_admin', false)
                          ->whereDoesntHave('teacher');
                    break;
            }
        }

        // Filter by status - Fixed to handle ban expiry
        if ($request->filled('status')) {
            if ($request->status === 'banned') {
                $query->whereNotNull('banned_at')
                      ->where(function ($q) {
                          $q->where('ban_type', 'permanent')
                            ->orWhere(function ($q2) {
                                $q2->where('ban_type', 'temporary')
                                   ->where('ban_expires_at', '>', now());
                            });
                      });
            } elseif ($request->status === 'active') {
                $query->where(function ($q) {
                    $q->whereNull('banned_at')
                      ->orWhere(function ($q2) {
                          $q2->where('ban_type', 'temporary')
                             ->where('ban_expires_at', '<=', now());
                      });
                });
            }
        }

        $users = $query->with(['teacher', 'currentSubscription.plan'])
            ->withCount([
                'studentAttempts as human_evaluations_count' => function ($query) {
                    $query->whereHas('humanEvaluationRequest', function ($q) {
                        $q->where('status', 'completed');
                    });
                },
                'studentAttempts as ai_evaluations_count' => function ($query) {
                    $query->whereNotNull('ai_evaluated_at');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Display system users (all admins and teachers, no students).
     */
    public function systemUsers(Request $request)
    {
        $query = User::query();

        // Filter for system users - only admins and teachers (no students)
        $query->where(function($q) {
            $q->where('is_admin', true)
              ->orWhereHas('teacher');
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role === 'teacher') {
                $query->whereHas('teacher');
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'banned') {
                $query->whereNotNull('banned_at');
            } elseif ($request->status === 'active') {
                $query->whereNull('banned_at');
            }
        }

        $users = $query->with(['teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.system', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = \App\Models\Role::with('permissions')->get();
        
        // Debug: Check if roles exist
        if ($roles->isEmpty()) {
            \Log::warning('No roles found in database');
        }
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher,admin,custom',
            'custom_role_id' => 'required_if:role,custom|nullable|exists:roles,id',
            'email_verified' => 'boolean',
        ]);

        // Determine is_admin and role_id based on selection
        $isAdmin = false;
        $roleId = null;
        
        if ($validated['role'] === 'custom') {
            $roleId = $validated['custom_role_id'];
        } elseif ($validated['role'] === 'admin') {
            $isAdmin = true;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $isAdmin,
            'role_id' => $roleId,
            'email_verified_at' => $validated['email_verified'] ?? false ? now() : null,
            'created_by' => 'system',
        ]);

        // If teacher role, create teacher record
        if ($validated['role'] === 'teacher') {
            $user->teacher()->create([
                'bio' => '',
                'specializations' => json_encode([]),
                'hourly_rate' => 0,
                'is_available' => false,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load([
            'teacher',
            'subscriptions.plan',
            'studentAttempts' => function ($query) {
                $query->latest()->take(10);
            },
            'evaluationTokens',
            'referrals',
            'referredBy',
        ]);
        
        // Load authentication logs separately if table exists
        if (\Schema::hasTable('authentication_log')) {
            $user->load(['authenticationLogs' => function ($query) {
                $query->latest()->take(10);
            }]);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = \App\Models\Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:student,teacher,admin',
            'custom_role_id' => 'nullable|exists:roles,id',
            'email_verified' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_admin' => $validated['role'] === 'admin',
            'role_id' => $validated['custom_role_id'] ?? null,
            'email_verified_at' => $validated['email_verified'] ?? false ? ($user->email_verified_at ?? now()) : null,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Handle role changes
        if ($validated['role'] === 'teacher' && !$user->teacher) {
            $user->teacher()->create([
                'bio' => '',
                'specializations' => json_encode([]),
                'hourly_rate' => 0,
                'is_available' => false,
            ]);
        } elseif ($validated['role'] !== 'teacher' && $user->teacher) {
            $user->teacher()->delete();
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Show ban form
     */
    public function showBanForm(User $user)
    {
        if ($user->isBanned()) {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'User is already banned.');
        }
        
        return view('admin.users.ban', compact('user'));
    }
    
    /**
     * Ban a user
     */
    public function ban(Request $request, User $user)
    {
        $validated = $request->validate([
            'ban_reason' => 'required|string|max:500',
            'ban_type' => 'required|in:temporary,permanent',
            'ban_duration' => 'required_if:ban_type,temporary|nullable|integer|min:1|max:365',
        ]);
        
        $expiresAt = null;
        if ($validated['ban_type'] === 'temporary' && isset($validated['ban_duration'])) {
            $expiresAt = now()->addDays((int)$validated['ban_duration']);
        }
        
        $user->ban(
            $validated['ban_reason'],
            $validated['ban_type'],
            $expiresAt,
            auth()->user()
        );
        
        // Send ban notification
        $user->notify(new \App\Notifications\UserBanned(
            $validated['ban_reason'],
            $validated['ban_type'],
            $expiresAt
        ));
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User has been banned successfully.');
    }
    
    /**
     * Unban a user
     */
    public function unban(User $user)
    {
        if (!$user->isBanned()) {
            return back()->with('error', 'User is not banned.');
        }
        
        $user->unban();
        
        return back()->with('success', 'User has been unbanned successfully.');
    }

    /**
     * Verify a user's email.
     */
    public function verifyEmail(User $user)
    {
        if (!$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
            return back()->with('success', 'Email verified successfully.');
        }

        return back()->with('info', 'Email is already verified.');
    }

    /**
     * Export selected users to CSV
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        $users = User::whereIn('id', $validated['user_ids'])
            ->with(['teacher', 'currentSubscription.plan'])
            ->get();
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $columns = ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Subscription', 'Email Verified', 'Referral Code', 'Referral Balance', 'AI Evaluations', 'Tests Taken', 'Created At', 'Last Login'];
        
        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($users as $user) {
                $row = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? 'N/A',
                    $user->is_admin ? 'Admin' : ($user->teacher ? 'Teacher' : 'Student'),
                    $user->isBanned() ? 'Banned' : 'Active',
                    $user->currentSubscription ? $user->currentSubscription->plan->name : 'Free',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->referral_code,
                    $user->referral_balance,
                    $user->ai_evaluations_used,
                    $user->tests_taken_this_month,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Bulk delete users
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        // Remove current user from the list if present
        $userIds = array_filter($validated['user_ids'], function($id) {
            return $id != auth()->id();
        });
        
        if (empty($userIds)) {
            return back()->with('error', 'No valid users selected for deletion.');
        }
        
        $deletedCount = 0;
        $failedCount = 0;
        $errors = [];
        
        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                if ($user && $user->id !== auth()->id()) {
                    $this->deleteUserAndRelatedData($user);
                    $deletedCount++;
                }
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Failed to delete user ID {$userId}: " . $e->getMessage();
                \Log::error('Bulk delete failed for user: ' . $userId, ['error' => $e->getMessage()]);
            }
        }
        
        $message = "Successfully deleted {$deletedCount} user(s).";
        if ($failedCount > 0) {
            $message .= " Failed to delete {$failedCount} user(s).";
            if (!empty($errors)) {
                session()->flash('errors', $errors);
            }
            return back()->with('warning', $message);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
    
    /**
     * Delete user and all related data (extracted for reuse)
     */
    private function deleteUserAndRelatedData(User $user)
    {
        \DB::beginTransaction();
        
        try {
            // Store user name before deletion
            $userName = $user->name;
            
            // Delete authentication logs if table exists
            if (\Schema::hasTable('authentication_log')) {
                \DB::table('authentication_log')->where('authenticatable_id', $user->id)->delete();
            }
            
            // Delete student attempts and their answers
            if ($user->studentAttempts) {
                foreach ($user->studentAttempts as $attempt) {
                    // Delete attempt answers
                    $attempt->answers()->delete();
                    
                    // Delete AI evaluation jobs
                    if ($attempt->aiEvaluationJobs) {
                        $attempt->aiEvaluationJobs()->delete();
                    }
                    
                    // Delete human evaluation requests
                    if ($attempt->humanEvaluationRequest) {
                        $attempt->humanEvaluationRequest->delete();
                    }
                }
                $user->studentAttempts()->delete();
            }
            
            // Delete user achievements
            if ($user->achievements) {
                $user->achievements()->delete();
            }
            
            // Delete user goals
            if ($user->goals) {
                $user->goals()->delete();
            }
            
            // Delete subscriptions
            if ($user->subscriptions) {
                $user->subscriptions()->delete();
            }
            
            // Delete payment transactions
            if ($user->transactions) {
                $user->transactions()->delete();
            }
            
            // Delete evaluation tokens
            if ($user->evaluationTokens) {
                $user->evaluationTokens()->delete();
            }
            
            // Delete devices
            if ($user->devices) {
                $user->devices()->delete();
            }
            
            // Delete OTP verifications
            if ($user->otpVerifications) {
                $user->otpVerifications()->delete();
            }
            
            // Delete referrals where user is referrer
            if ($user->referrals) {
                $user->referrals()->delete();
            }
            
            // Delete referral rewards
            if ($user->referralRewards) {
                $user->referralRewards()->delete();
            }
            
            // Delete referral redemptions
            if ($user->referralRedemptions) {
                $user->referralRedemptions()->delete();
            }
            
            // Delete ban appeals
            if ($user->banAppeals) {
                $user->banAppeals()->delete();
            }
            
            // If user is a teacher, delete teacher record
            if ($user->teacher) {
                // Delete evaluation requests where this teacher is assigned
                \DB::table('human_evaluation_requests')
                    ->where('teacher_id', $user->teacher->id)
                    ->update(['teacher_id' => null]);
                    
                $user->teacher()->delete();
            }
            
            // Update referred_by for users who were referred by this user
            User::where('referred_by', $user->id)->update(['referred_by' => null]);
            
            // Update banned_by for users who were banned by this user
            User::where('banned_by', $user->id)->update(['banned_by' => null]);
            
            // Delete any other relations that might exist
            \DB::table('full_test_attempts')->where('user_id', $user->id)->delete();
            
            // Delete announcements_dismissed if table exists
            if (\Schema::hasTable('announcements_dismissed')) {
                \DB::table('announcements_dismissed')->where('user_id', $user->id)->delete();
            }
            
            \DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            \DB::table('sessions')->where('user_id', $user->id)->delete();
            
            // Finally delete the user
            $user->delete();
            
            \DB::commit();
            
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'You cannot delete your own account.'], 403);
            }
            return back()->with('error', 'You cannot delete your own account.');
        }

        try {
            $userName = $user->name; // Store name before deletion
            $this->deleteUserAndRelatedData($user);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "User '{$userName}' has been deleted successfully."
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
                
        } catch (\Exception $e) {
            // Rollback transaction
            \DB::rollback();
            
            // Log the error
            \Log::error('Failed to delete user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'error' => 'Failed to delete user. ' . ($e->getMessage() ?: 'Please try again.')
                ], 500);
            }
            return back()->with('error', 'Failed to delete user. ' . ($e->getMessage() ?: 'Please try again.'));
        }
    }
}
