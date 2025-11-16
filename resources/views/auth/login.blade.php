{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $settings = \App\Models\WebsiteSetting::first();
        $siteName = $settings ? $settings->site_title : 'CD IELTS';
        $favicon = $settings && $settings->favicon_path ? Storage::url($settings->favicon_path) : null;
        $logo = $settings && $settings->logo_path ? Storage::url($settings->logo_path) : null;
    @endphp
    
    <title>Sign In - {{ $siteName }}</title>
    
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        {{-- Left Side - Branding (Desktop Only) --}}
        <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-red-500 to-rose-600 items-center justify-center px-12">
            <div class="text-white text-center">
                @if($logo)
                    <img src="{{ $logo }}" alt="{{ $siteName }}" class="h-16 w-auto mx-auto mb-6 brightness-0 invert">
                @else
                    <div class="flex items-center justify-center space-x-3 mb-6">
                        <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center">
                            <span class="text-red-500 font-bold text-3xl">CD</span>
                        </div>
                    </div>
                @endif
                <h1 class="text-4xl font-bold mb-4">Welcome Back!</h1>
                <p class="text-lg opacity-90">Continue your IELTS preparation journey</p>
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md">
                {{-- Logo for Mobile and Desktop Form --}}
                <div class="text-center mb-6">
                    <a href="{{ url('/') }}" class="inline-block">
                        @if($logo)
                            <img src="{{ $logo }}" alt="{{ $siteName }}" class="h-12 w-auto mx-auto">
                        @else
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xl">CD</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">{{ $siteName }}</span>
                            </div>
                        @endif
                    </a>
                </div>
                
                {{-- Mobile Header --}}
                <div class="lg:hidden text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Sign In</h1>
                    <p class="text-sm text-gray-600 mt-1">Welcome back to {{ $siteName }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 lg:p-8">
                    {{-- Desktop Header --}}
                    <div class="hidden lg:block text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Sign In</h2>
                        <p class="text-sm text-gray-600 mt-1">Enter your credentials to continue</p>
                    </div>

                    {{-- Error Alert (Redirected from Admin Login) --}}
                    @if(session('error'))
                        <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                    @if(session('info'))
                                        <p class="text-xs text-red-600 mt-1">{{ session('info') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Social Login --}}
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <a href="{{ route('auth.social.redirect', 'google') }}" 
                           class="flex items-center justify-center py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Google
                        </a>

                        <a href="{{ route('auth.social.redirect', 'facebook') }}" 
                           class="flex items-center justify-center py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                            <svg class="w-4 h-4 mr-2" fill="#1877F2" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </a>
                    </div>

                    <div class="relative mb-5">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-2 bg-white text-gray-500">or</span>
                        </div>
                    </div>

                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        {{-- Email/Phone --}}
                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email or Phone</label>
                            <input id="email" 
                                   name="email" 
                                   type="text" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('email') border-red-300 @enderror"
                                   placeholder="you@example.com or phone number">
                            @error('email')
                                <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       required 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent pr-10 @error('password') border-red-300 @enderror"
                                       placeholder="Enter your password">
                                <button type="button" 
                                        onclick="togglePasswordVisibility()" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eye-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember & Forgot --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="remember" 
                                       class="w-4 h-4 text-red-500 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-2 text-xs text-gray-700">Remember me for 30 days</span>
                            </label>
                            <a href="{{ route('password.request') }}" 
                               class="text-xs text-red-600 hover:text-red-500 font-medium">
                                Forgot password?
                            </a>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" 
                                class="w-full py-2.5 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition">
                            Sign In
                        </button>

                        {{-- Register Link --}}
                        <p class="text-center text-xs text-gray-600 pt-2">
                            Don't have an account? 
                            <a href="{{ route('register') }}" 
                               class="text-red-600 hover:text-red-500 font-medium">
                                Create free account
                            </a>
                        </p>
                    </form>

                    {{-- Divider --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-3">Having trouble signing in?</p>
                            <div class="flex justify-center space-x-4 text-xs">
                                <a href="{{ route('password.request') }}" class="text-red-600 hover:text-red-500">Reset Password</a>
                                <span class="text-gray-300">•</span>
                                <a href="{{ route('contact') }}" class="text-red-600 hover:text-red-500">Contact Support</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Links --}}
                <div class="mt-6 text-center text-xs text-gray-500">
                    <a href="{{ route('terms-of-service') }}" class="hover:text-gray-700 transition">Terms</a>
                    <span class="mx-2">•</span>
                    <a href="{{ route('privacy-policy') }}" class="hover:text-gray-700 transition">Privacy</a>
                    <span class="mx-2">•</span>
                    <a href="{{ route('contact') }}" class="hover:text-gray-700 transition">Support</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                passwordField.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>
</html>