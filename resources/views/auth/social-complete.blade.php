<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Registration - CD IELTS</title>
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
                <div class="mb-8">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto backdrop-blur-sm">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl font-bold mb-4">Almost There!</h1>
                <p class="text-lg opacity-90">Just a few more details to complete your registration</p>
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md">
                {{-- Mobile Header --}}
                <div class="lg:hidden text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Complete Your Profile</h1>
                    <p class="text-sm text-gray-600 mt-1">We need a few more details</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 lg:p-8">
                    {{-- Desktop Header --}}
                    <div class="hidden lg:block text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Complete Your Registration</h2>
                        <p class="text-sm text-gray-600 mt-1">We just need a few more details</p>
                    </div>

                    {{-- User Info from Social --}}
                    <div class="bg-red-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $socialData['avatar'] }}" 
                                 alt="{{ $socialData['name'] }}" 
                                 class="w-12 h-12 rounded-full">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $socialData['name'] }}</p>
                                <p class="text-sm text-gray-600">{{ $socialData['email'] }}</p>
                            </div>
                            <div>
                                @if($socialData['provider'] === 'google')
                                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Additional Info Form --}}
                    <form method="POST" action="{{ route('auth.social.complete') }}" class="space-y-4">
                        @csrf

                        {{-- Phone Number --}}
                        <div>
                            <label for="phone_number" class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                            <div class="flex rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-red-500 focus-within:border-transparent">
                                <select name="country_phone_code" 
                                        class="px-2 text-sm bg-gray-50 border-r border-gray-300 focus:outline-none">
                                    <option value="+880" {{ $locationData['countryCode'] === 'BD' ? 'selected' : '' }}>🇧🇩 +880</option>
                                    <option value="+91" {{ $locationData['countryCode'] === 'IN' ? 'selected' : '' }}>🇮🇳 +91</option>
                                    <option value="+1" {{ $locationData['countryCode'] === 'US' ? 'selected' : '' }}>🇺🇸 +1</option>
                                    <option value="+44" {{ $locationData['countryCode'] === 'GB' ? 'selected' : '' }}>🇬🇧 +44</option>
                                    <option value="+61" {{ $locationData['countryCode'] === 'AU' ? 'selected' : '' }}>🇦🇺 +61</option>
                                    <option value="+971" {{ $locationData['countryCode'] === 'AE' ? 'selected' : '' }}>🇦🇪 +971</option>
                                    <option value="+966" {{ $locationData['countryCode'] === 'SA' ? 'selected' : '' }}>🇸🇦 +966</option>
                                    <option value="+86" {{ $locationData['countryCode'] === 'CN' ? 'selected' : '' }}>🇨🇳 +86</option>
                                </select>
                                <input type="tel" 
                                       name="phone_number" 
                                       id="phone_number" 
                                       required
                                       pattern="[0-9]{10,15}"
                                       class="flex-1 px-3 py-2 text-sm focus:outline-none @error('phone_number') border-red-300 @enderror"
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
                                <option value="">Select your country</option>
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

                        {{-- Terms --}}
                        <div class="flex items-start pt-2">
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
                                class="w-full py-2.5 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition mt-6">
                            Complete Registration
                        </button>

                        {{-- Alternative --}}
                        <p class="text-center text-xs text-gray-600 pt-2">
                            Wrong account? 
                            <a href="{{ route('login') }}" class="text-red-600 hover:text-red-500 font-medium">
                                Use different method
                            </a>
                        </p>
                    </form>

                    {{-- Divider --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-3">Why do we need this?</p>
                            <div class="space-y-2 text-xs text-gray-500">
                                <div class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Personalized study plans</span>
                                </div>
                                <div class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Important notifications</span>
                                </div>
                                <div class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Account security</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Links --}}
                <div class="mt-6 text-center text-xs text-gray-500">
                    <a href="#" class="hover:text-gray-700 transition">Help</a>
                    <span class="mx-2">•</span>
                    <a href="#" class="hover:text-gray-700 transition">Privacy</a>
                    <span class="mx-2">•</span>
                    <a href="#" class="hover:text-gray-700 transition">Terms</a>
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
            
            // Auto-update phone code based on country
            const countryPhoneCodes = {
                'BD': '+880', 'IN': '+91', 'US': '+1', 'GB': '+44',
                'AU': '+61', 'CA': '+1', 'AE': '+971', 'SA': '+966',
                'CN': '+86', 'JP': '+81', 'KR': '+82', 'MY': '+60',
                'SG': '+65', 'PK': '+92', 'LK': '+94', 'NP': '+977'
            };
            
            const phoneSelect = document.querySelector('select[name="country_phone_code"]');
            const phoneCode = countryPhoneCodes[select.value];
            if (phoneCode && phoneSelect) {
                // Try to find and select the matching phone code
                for (let option of phoneSelect.options) {
                    if (option.value === phoneCode) {
                        phoneSelect.value = phoneCode;
                        break;
                    }
                }
            }
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', function() {
            updateCountryName();
        });
    </script>
</body>
</html>