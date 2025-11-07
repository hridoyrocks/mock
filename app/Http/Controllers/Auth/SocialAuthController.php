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
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function redirect($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            Log::error('Social redirect error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to connect to ' . ucfirst($provider) . '. Please try again.');
        }
    }

    public function callback(Request $request, $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            if (!$socialUser || !$socialUser->email) {
                Log::warning('Social login: No email provided from ' . $provider);
                return redirect()->route('login')
                    ->with('error', 'Could not get your email from ' . ucfirst($provider) . '. Please try another method.');
            }
            
            // CRITICAL: Check if user exists by email FIRST (prevents duplicate)
            $user = User::where('email', $socialUser->email)->first();

            if ($user) {
                Log::info('Social login: Existing user found - ' . $user->email);
                
                // Update social ID and avatar if not already set
                try {
                    $updateData = [];
                    
                    if (!$user->{$provider . '_id'}) {
                        $updateData[$provider . '_id'] = $socialUser->id;
                    }
                    
                    if (!$user->avatar_url && $socialUser->avatar) {
                        $updateData['avatar_url'] = $socialUser->avatar;
                    }
                    
                    if (!empty($updateData)) {
                        $user->update($updateData);
                    }
                    
                    // Update last login
                    $user->update(['last_login_at' => now()]);
                    
                } catch (\Exception $e) {
                    Log::error('Error updating user social data: ' . $e->getMessage());
                    // Continue anyway, login is more important
                }
                
                // Login the user
                Auth::login($user);
                
                // Track device (don't fail login if this fails)
                try {
                    $this->trackDevice($request, $user);
                } catch (\Exception $e) {
                    Log::error('Error tracking device: ' . $e->getMessage());
                }
                
                return redirect()->route('student.dashboard')
                    ->with('success', 'Welcome back, ' . $user->name . '!');
            }

            // Check if user exists by social ID only (edge case - different email)
            $user = User::where($provider . '_id', $socialUser->id)->first();
            
            if ($user) {
                Log::info('Social login: User found by social ID - ' . $user->email);
                
                Auth::login($user);
                $user->update(['last_login_at' => now()]);
                
                try {
                    $this->trackDevice($request, $user);
                } catch (\Exception $e) {
                    Log::error('Error tracking device: ' . $e->getMessage());
                }
                
                return redirect()->route('student.dashboard')
                    ->with('success', 'Welcome back, ' . $user->name . '!');
            }

            // New user - store data in session for phone number collection
            Log::info('Social login: New user - ' . $socialUser->email);
            
            session([
                'social_auth' => [
                    'provider' => $provider,
                    'id' => $socialUser->id,
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'avatar' => $socialUser->avatar,
                    'timestamp' => now()->timestamp, // Add timestamp for expiry check
                ]
            ]);

            return redirect()->route('auth.social.complete');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Social login invalid state: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Authentication session expired. Please try again.');
                
        } catch (\Exception $e) {
            Log::error('Social login callback error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->route('login')
                ->with('error', 'Social login failed. Please try again or use email login.');
        }
    }

    public function showCompleteForm(Request $request)
    {
        $socialData = session('social_auth');
        
        if (!$socialData) {
            return redirect()->route('register')
                ->with('error', 'Session expired. Please try again.');
        }
        
        // Check session age (expire after 10 minutes)
        if (isset($socialData['timestamp']) && (now()->timestamp - $socialData['timestamp']) > 600) {
            session()->forget('social_auth');
            return redirect()->route('register')
                ->with('error', 'Session expired. Please try again.');
        }
        
        // CRITICAL: Before showing form, check if user already exists
        $existingUser = User::where('email', $socialData['email'])->first();
        if ($existingUser) {
            Log::info('Social complete form: User already exists - ' . $existingUser->email);
            session()->forget('social_auth');
            Auth::login($existingUser);
            return redirect()->route('student.dashboard')
                ->with('success', 'Welcome back!');
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
            'socialData' => $socialData,
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
            Log::warning('Social complete: No session data');
            return redirect()->route('register')
                ->with('error', 'Session expired. Please try again.');
        }
        
        // Check session age
        if (isset($socialData['timestamp']) && (now()->timestamp - $socialData['timestamp']) > 600) {
            session()->forget('social_auth');
            return redirect()->route('register')
                ->with('error', 'Session expired. Please try again.');
        }

        try {
            // CRITICAL: Triple check if user already exists before creating
            $existingUser = User::where('email', $socialData['email'])
                ->orWhere($socialData['provider'] . '_id', $socialData['id'])
                ->first();
                
            if ($existingUser) {
                Log::info('Social complete: User already exists - ' . $existingUser->email);
                session()->forget('social_auth');
                Auth::login($existingUser);
                return redirect()->route('student.dashboard')
                    ->with('success', 'Welcome back, ' . $existingUser->name . '!');
            }

            // Use DB transaction with lock to prevent race condition
            $user = DB::transaction(function () use ($request, $socialData) {
                // One more check inside transaction with lock
                $checkUser = User::where('email', $socialData['email'])
                    ->lockForUpdate()
                    ->first();
                    
                if ($checkUser) {
                    return $checkUser;
                }
                
                // Create new user
                $user = User::create([
                    'name' => $socialData['name'],
                    'email' => $socialData['email'],
                    'password' => bcrypt(\Illuminate\Support\Str::random(32)),
                    'phone_number' => $request->phone_number,
                    'country_code' => $request->country_code,
                    'country_name' => $request->country_name ?? 'Unknown',
                    $socialData['provider'] . '_id' => $socialData['id'],
                    'avatar_url' => $socialData['avatar'],
                    'login_method' => $socialData['provider'],
                    'is_social_signup' => true,
                    'email_verified_at' => now(), // Auto verify for social login
                ]);
                
                Log::info('Social complete: New user created - ' . $user->email);
                
                return $user;
            }, 3); // 3 attempts if deadlock

            // Login the user
            Auth::login($user);
            
            // Track device (don't fail if this errors)
            try {
                $this->trackDevice($request, $user);
            } catch (\Exception $e) {
                Log::error('Error tracking device on registration: ' . $e->getMessage());
            }

            session()->forget('social_auth');
            
            return redirect()->route('student.dashboard')
                ->with('success', 'Welcome to IELTS Mock Platform, ' . $user->name . '!');
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Duplicate entry error
            if ($e->getCode() == 23000) {
                Log::error('Duplicate entry error: ' . $e->getMessage());
                
                // User was created by another request, just login
                $user = User::where('email', $socialData['email'])->first();
                if ($user) {
                    session()->forget('social_auth');
                    Auth::login($user);
                    return redirect()->route('student.dashboard')
                        ->with('success', 'Welcome back!');
                }
            }
            
            Log::error('Database error in social complete: ' . $e->getMessage());
            return redirect()->route('register')
                ->with('error', 'An error occurred. Please try again or use email registration.');
                
        } catch (\Exception $e) {
            Log::error('Social complete error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->route('register')
                ->with('error', 'An error occurred. Please try again or use email registration.');
        }
    }

    private function trackDevice($request, $user)
    {
        try {
            $locationData = $this->locationService->getLocation($request->ip());
            $device = \App\Models\UserDevice::createFromRequest($request, $user->id, $locationData);
            
            if ($device && !$device->is_trusted) {
                $user->notify(new \App\Notifications\NewDeviceNotification($device));
            }
        } catch (\Exception $e) {
            Log::error('Device tracking error: ' . $e->getMessage());
            // Don't throw, just log
        }
    }
}
