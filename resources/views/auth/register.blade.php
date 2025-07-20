{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register -  CD IELTS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        {{-- Left Side - Branding (Desktop Only) --}}
        <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-red-500 to-rose-600 items-center justify-center px-12">
            <div class="text-white text-center">
                <h1 class="text-4xl font-bold mb-4">Join IELTS Mock Test</h1>
                <p class="text-lg opacity-90">Start your journey to success with 50,000+ students</p>
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md">
                {{-- Mobile Header --}}
                <div class="lg:hidden text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
                    <p class="text-sm text-gray-600 mt-1">Join thousands of successful students</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 lg:p-8">
                    {{-- Social Registration --}}
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

                    {{-- Registration Form - Compact Version --}}
                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf

                        {{-- Name & Email in one row --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       value="{{ old('name') }}" 
                                       required 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('name') border-red-300 @enderror"
                                       placeholder="John Doe">
                                @error('name')
                                    <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('email') border-red-300 @enderror"
                                       placeholder="you@example.com">
                                @error('email')
                                    <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone_number" class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                            <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-red-500 focus-within:border-transparent">
                                <select name="country_phone_code" class="px-2 text-sm bg-gray-50 border-r border-gray-300 focus:outline-none">
                                    <option value="+880">🇧🇩 +880</option>
                                    <option value="+91">🇮🇳 +91</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                </select>
                                <input type="tel" 
                                       name="phone_number" 
                                       value="{{ old('phone_number') }}"
                                       required
                                       pattern="[0-9]{10,15}"
                                       class="flex-1 px-3 py-2 text-sm focus:outline-none"
                                       placeholder="1234567890">
                            </div>
                            @error('phone_number')
                                <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Country --}}
                        <div>
                            <label for="country_code" class="block text-xs font-medium text-gray-700 mb-1">Country</label>
                            <select id="country_code" 
                                    name="country_code" 
                                    required
                                    onchange="updateCountryName()"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">Select country</option>
                                @foreach($countries as $code => $name)
                                    <option value="{{ $code }}" 
                                            data-name="{{ $name }}"
                                            {{ ($locationData['countryCode'] ?? '') === $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="country_name" name="country_name" value="{{ $locationData['countryName'] ?? '' }}">
                            @if($locationData)
                                
                            @endif
                        </div>
                        
                        {{-- Referral Code (if exists) --}}
                        @if($referralCode)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Referral Code</label>
                            <div class="relative">
                                <input type="text" 
                                       name="referral_code" 
                                       value="{{ $referralCode }}" 
                                       readonly
                                       class="w-full px-3 py-2 pr-10 text-sm border border-green-300 bg-green-50 rounded-lg">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-0.5 text-xs text-green-600">You'll get bonus rewards after registration!</p>
                        </div>
                        @endif

                        {{-- Password & Confirm in one row --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       required 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('password') border-red-300 @enderror"
                                       placeholder="Min 8 characters">
                                @error('password')
                                    <p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">Confirm</label>
                                <input id="password_confirmation" 
                                       name="password_confirmation" 
                                       type="password" 
                                       required 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                       placeholder="Confirm password">
                            </div>
                        </div>

                        {{-- Terms --}}
                        <div class="flex items-start">
                            <input id="terms" 
                                   name="terms" 
                                   type="checkbox" 
                                   required
                                   class="w-4 h-4 mt-0.5 text-red-500 border-gray-300 rounded focus:ring-red-500">
                            <label for="terms" class="ml-2 text-xs text-gray-700">
                                I agree to the <a href="#" class="text-red-600 hover:underline">Terms</a> 
                                and <a href="#" class="text-red-600 hover:underline">Privacy Policy</a>
                            </label>
                        </div>
                        @error('terms')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Submit Button --}}
                        <button type="submit" 
                                class="w-full py-2.5 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition">
                            Create Account
                        </button>

                        {{-- Login Link --}}
                        <p class="text-center text-xs text-gray-600 pt-2">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-red-600 hover:text-red-500 font-medium">Sign in</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateCountryName() {
            const select = document.getElementById('country_code');
            const selectedOption = select.options[select.selectedIndex];
            const countryName = selectedOption.getAttribute('data-name');
            document.getElementById('country_name').value = countryName || '';
            
            // Auto-update phone code
            const countryPhoneCodes = {
                'BD': '+880', 'IN': '+91', 'US': '+1', 'GB': '+44',
                'AU': '+61', 'CA': '+1', 'AE': '+971', 'SA': '+966'
            };
            
            const phoneSelect = document.querySelector('select[name="country_phone_code"]');
            const phoneCode = countryPhoneCodes[select.value];
            if (phoneCode && phoneSelect) {
                for (let option of phoneSelect.options) {
                    if (option.value === phoneCode) {
                        phoneSelect.value = phoneCode;
                        break;
                    }
                }
            }
        }

        // Initialize on load
        updateCountryName();
    </script>
</body>
</html>