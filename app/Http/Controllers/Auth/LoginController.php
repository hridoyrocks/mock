<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Rate limiting
        $this->checkRateLimit($request);

        // Attempt login with email or phone
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        
        $credentials = [
            $loginField => $request->email,
            'password' => $request->password,
        ];

        // Remember me functionality - will keep user logged in for 30 days if checked
        $remember = $request->filled('remember');
        
        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($this->throttleKey($request));
            
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // Clear rate limiter
        RateLimiter::clear($this->throttleKey($request));

        $user = Auth::user();

        // Check email verification
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            
            // Send new OTP
            $otp = \App\Models\OtpVerification::createForEmail($user->email);
            $user->notify(new \App\Notifications\OtpNotification($otp));
            
            session(['otp_session' => ['email' => $user->email]]);
            
            return redirect()->route('auth.verify.otp', ['email' => $user->email])
                ->with('warning', 'Please verify your email first.');
        }

        // Device tracking (without trust device feature)
        try {
            $this->handleDeviceTracking($request, $user);
        } catch (\Exception $e) {
            // Log error but don't fail login
            \Log::error('Device tracking failed: ' . $e->getMessage());
        }

        // Regenerate session for security
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    private function handleDeviceTracking(Request $request, $user)
    {
        try {
            $locationData = $this->locationService->getLocation($request->ip());
            $device = UserDevice::createFromRequest($request, $user->id, $locationData);

            // Check if new device
            if ($device->wasRecentlyCreated) {
                // Send notification for new device (only if class exists)
                if (class_exists('\App\Notifications\NewDeviceNotification')) {
                    $user->notify(new \App\Notifications\NewDeviceNotification($device));
                }
            } else {
                $device->updateActivity();
            }

            // Store device info in session
            session(['device_id' => $device->id]);
        } catch (\Exception $e) {
            // Don't fail login if device tracking fails
            \Log::error('Device tracking error: ' . $e->getMessage());
        }
    }

    private function checkRateLimit(Request $request)
    {
        $key = $this->throttleKey($request);
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
            ]);
        }
    }

    private function throttleKey(Request $request): string
    {
        return 'login:' . $request->ip();
    }

    private function redirectPath(): string
    {
        return auth()->user()->is_admin 
            ? route('admin.dashboard') 
            : route('student.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}