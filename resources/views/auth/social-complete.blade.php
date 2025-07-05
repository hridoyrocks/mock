<x-layout>
    <x-slot:title>Complete Registration - IELTS Mock Test</x-slot>
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Almost Done!</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        We just need a few more details to complete your registration
                    </p>
                </div>

                {{-- User Info from Social --}}
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $socialData['avatar'] }}" 
                             alt="{{ $socialData['name'] }}" 
                             class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-medium text-gray-900">{{ $socialData['name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $socialData['email'] }}</p>
                        </div>
                        <div class="ml-auto">
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
                <form method="POST" action="{{ route('auth.social.complete') }}" class="space-y-5">
                    @csrf

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                        <div class="mt-1 flex rounded-lg shadow-sm">
                            <select name="country_phone_code" 
                                    class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <option value="+880" {{ $locationData['countryCode'] === 'BD' ? 'selected' : '' }}>🇧🇩 +880</option>
                                <option value="+91" {{ $locationData['countryCode'] === 'IN' ? 'selected' : '' }}>🇮🇳 +91</option>
                                <option value="+1" {{ $locationData['countryCode'] === 'US' ? 'selected' : '' }}>🇺🇸 +1</option>
                                <option value="+44" {{ $locationData['countryCode'] === 'GB' ? 'selected' : '' }}>🇬🇧 +44</option>
                                <option value="+61" {{ $locationData['countryCode'] === 'AU' ? 'selected' : '' }}>🇦🇺 +61</option>
                            </select>
                            <input type="tel" 
                                   name="phone_number" 
                                   id="phone_number" 
                                   required
                                   class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-r-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone_number') border-red-300 @enderror"
                                   placeholder="1234567890">
                        </div>
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Country --}}
                    <div>
                        <label for="country_code" class="block text-sm font-medium text-gray-700">Country *</label>
                        <select id="country_code" 
                                name="country_code" 
                                required
                                onchange="updateCountryName()"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}" 
                                        data-name="{{ $name }}"
                                        {{ $locationData['countryCode'] === $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="country_name" name="country_name" value="{{ $locationData['countryName'] ?? '' }}">
                        
                        @if($locationData)
                            <p class="mt-1 text-sm text-gray-500">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Detected: {{ $locationData['cityName'] }}, {{ $locationData['countryName'] }}
                            </p>
                        @endif
                    </div>

                    {{-- Terms --}}
                    <div class="border-t pt-5">
                        <div class="flex items-start">
                            <input id="terms" 
                                   name="terms" 
                                   type="checkbox" 
                                   required
                                   class="h-4 w-4 mt-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms and Conditions</a> 
                                and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Complete Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateCountryName() {
            const select = document.getElementById('country_code');
            const selectedOption = select.options[select.selectedIndex];
            const countryName = selectedOption.getAttribute('data-name');
            document.getElementById('country_name').value = countryName;
        }

        // Initialize country name
        updateCountryName();
    </script>
    @endpush
</x-layout>