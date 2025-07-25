<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role === 'teacher') {
                $query->whereHas('teacher');
            } elseif ($request->role === 'student') {
                $query->where('is_admin', false)->doesntHave('teacher');
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

        $users = $query->with(['teacher', 'currentSubscription.plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
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
            'role' => 'required|in:student,teacher,admin',
            'email_verified' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['role'] === 'admin',
            'email_verified_at' => $validated['email_verified'] ?? false ? now() : null,
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
        return view('admin.users.edit', compact('user'));
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
            'email_verified' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_admin' => $validated['role'] === 'admin',
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
     * Ban or unban a user.
     */
    public function toggleBan(User $user)
    {
        if ($user->banned_at) {
            $user->update([
                'banned_at' => null,
                'ban_reason' => null,
            ]);
            $message = 'User has been unbanned successfully.';
        } else {
            $user->update([
                'banned_at' => now(),
                'ban_reason' => request('reason', 'Banned by administrator'),
            ]);
            $message = 'User has been banned successfully.';
        }

        return back()->with('success', $message);
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
            // Store user name before deletion
            $userName = $user->name;
            
            // Delete the user
            $user->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "User '{$userName}' has been deleted successfully."
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Failed to delete user. Please try again.'], 500);
            }
            return back()->with('error', 'Failed to delete user. Please try again.');
        }
    }
}
