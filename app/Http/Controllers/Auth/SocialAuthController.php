<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SocialAuthController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user exists
            $user = User::where('email', $socialUser->email)
                ->orWhere($provider . '_id', $socialUser->id)
                ->first();

            if ($user) {
                // Existing user - just login
                Auth::login($user);
                
                // Track device
                $this->trackDevice($request, $user);
                
                return redirect()->route('student.dashboard');
            }

            // New user - need additional info
            session([
                'social_auth' => [
                    'provider' => $provider,
                    'id' => $socialUser->id,
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'avatar' => $socialUser->avatar,
                ]
            ]);

            return redirect()->route('auth.social.complete');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Social login failed. Please try again.');
        }
    }

    public function showCompleteForm(Request $request)
    {
        if (!session('social_auth')) {
            return redirect()->route('register');
        }

        $locationData = $this->locationService->getLocation($request->ip());
        $countries = collect([
            'BD' => 'Bangladesh',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'IN' => 'India',
            'PK' => 'Pakistan',
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'MY' => 'Malaysia',
            'SG' => 'Singapore',
            'QA' => 'Qatar',
            'KW' => 'Kuwait',
            'OM' => 'Oman'
        ]);

        return view('auth.social-complete', [
            'socialData' => session('social_auth'),
            'locationData' => $locationData,
            'countries' => $countries,
        ]);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|phone:' . $request->country_code . ',mobile',
            'country_code' => 'required|string|size:2',
        ]);

        $socialData = session('social_auth');
        if (!$socialData) {
            return redirect()->route('register');
        }

        DB::transaction(function () use ($request, $socialData) {
            $user = User::create([
                'name' => $socialData['name'],
                'email' => $socialData['email'],
                'password' => bcrypt(\Str::random(16)),
                'phone_number' => $request->phone_number,
                'country_code' => $request->country_code,
                'country_name' => $request->country_name,
                $socialData['provider'] . '_id' => $socialData['id'],
                'avatar_url' => $socialData['avatar'],
                'login_method' => $socialData['provider'],
                'is_social_signup' => true,
                'email_verified_at' => now(), // Auto verify for social
            ]);

            // Send OTP for phone verification
            $otp = OtpVerification::createForEmail($user->email);
            
            // Send OTP email
            $user->notify(new \App\Notifications\OtpNotification($otp));
        });

        session()->forget('social_auth');
        
        return redirect()->route('auth.verify.otp', ['email' => $socialData['email']]);
    }

    private function trackDevice($request, $user)
    {
        $locationData = $this->locationService->getLocation($request->ip());
        $device = \App\Models\UserDevice::createFromRequest($request, $user->id, $locationData);
        
        if (!$device->is_trusted) {
            $user->notify(new \App\Notifications\NewDeviceNotification($device));
        }
    }
}