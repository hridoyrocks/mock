<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function removeDevice(Request $request, $deviceId)
{
    $device = $request->user()->devices()->findOrFail($deviceId);
    
    // Don't allow removing current device
    if ($device->device_fingerprint === $request->header('User-Agent')) {
        return response()->json(['error' => 'Cannot remove current device'], 400);
    }
    
    $device->delete();
    
    return response()->json(['success' => true]);
}

/**
 * Logout from all devices except current
 */
public function logoutAllDevices(Request $request)
{
    $currentFingerprint = UserDevice::generateFingerprint($request);
    
    $request->user()->devices()
        ->where('device_fingerprint', '!=', $currentFingerprint)
        ->delete();
    
    return response()->json(['success' => true]);
}

/**
 * Update notification preferences
 */
public function updateNotifications(Request $request)
{
    $preferences = [
        'test_reminders' => $request->boolean('test_reminders'),
        'score_updates' => $request->boolean('score_updates'),
        'achievement_alerts' => $request->boolean('achievement_alerts'),
        'marketing_emails' => $request->boolean('marketing_emails'),
    ];
    
    $request->user()->update([
        'notification_preferences' => $preferences
    ]);
    
    return redirect()->route('profile.edit')
        ->with('success', 'Notification preferences updated!');
}

public function updateAvatar(Request $request): RedirectResponse
{
    try {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Max 2MB
        ]);

        $user = $request->user();

        // Delete old avatar if exists (but not if it's a social avatar URL)
        if ($user->avatar_url && !filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
            $oldPath = str_replace('/storage/', '', $user->avatar_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->update([
            'avatar_url' => '/storage/' . $path
        ]);

        return back()->with('status', 'profile-updated');
        
    } catch (\Exception $e) {
        return back()->withErrors(['avatar' => 'Failed to upload avatar. Please try again.']);
    }
}
}