<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the admin's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Password updated successfully!');
    }

    /**
     * Update admin avatar
     */
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

            return redirect()->route('admin.profile.edit')->with('success', 'Avatar updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('admin.profile.edit')->withErrors(['avatar' => 'Failed to upload avatar. Please try again.']);
        }
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Delete avatar file if exists
        if ($user->avatar_url && !filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
            $oldPath = str_replace('/storage/', '', $user->avatar_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $user->update(['avatar_url' => null]);

        return redirect()->route('admin.profile.edit')->with('success', 'Avatar removed successfully!');
    }
}
