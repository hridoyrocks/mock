<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Models\UserDevice;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class OtpVerificationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function show(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email || !session('otp_session')) {
            return redirect()->route('login');
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'resend_after' => 60, // seconds
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $otp = OtpVerification::where('identifier', $request->email)
            ->where('otp_code', $request->otp)
            ->where('type', 'email')
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        if ($otp->isExpired()) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($otp->attempts >= 3) {
            return back()->withErrors(['otp' => 'Too many failed attempts. Please request a new OTP.']);
        }

        // Mark OTP as verified
        $otp->markAsVerified();

        // Mark email as verified
        $user = User::where('email', $request->email)->first();
        $user->markEmailAsVerified();

        // Login user
        Auth::login($user);

        // Track device
        $this->trackLoginDevice($request, $user);

        // Clear session
        session()->forget(['otp_session', 'registration_data']);

        return redirect()->intended(route('student.dashboard'))
            ->with('success', 'Email verified successfully!');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Rate limiting
        $key = 'otp-resend:' . $request->email;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many requests. Please try again in {$seconds} seconds."
            ]);
        }

        RateLimiter::hit($key, 60); // 1 minute decay

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Create new OTP
        $otp = OtpVerification::createForEmail($user->email);
        
        // Send OTP
        $user->notify(new \App\Notifications\OtpNotification($otp));

        return back()->with('success', 'New OTP sent to your email.');
    }

    private function trackLoginDevice(Request $request, User $user)
    {
        $locationData = $this->locationService->getLocation($request->ip());
        $device = UserDevice::createFromRequest($request, $user->id, $locationData);

        // Check if this is a new device
        if ($device->wasRecentlyCreated) {
            // Send new device notification
            $user->notify(new \App\Notifications\NewDeviceNotification($device));
        } else {
            // Update last activity
            $device->updateActivity();
        }

        // Store device fingerprint in session
        session(['device_fingerprint' => $device->device_fingerprint]);
    }
}