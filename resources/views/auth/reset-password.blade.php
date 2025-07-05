<x-layout>
    <x-slot:title>Reset Password - IELTS Mock Test</x-slot>
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Reset your password</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Enter your new password below
                    </p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email (readonly) --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ $email ?? old('email') }}" 
                               required 
                               readonly
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none sm:text-sm">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <div class="mt-1 relative">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   required 
                                   autofocus
                                   onkeyup="checkPasswordStrength()"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror"
                                   placeholder="Enter new password">
                            <button type="button" 
                                    onclick="togglePassword('password')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        
                        {{-- Password Strength Indicator --}}
                        <div class="mt-2">
                            <div class="flex items-center space-x-1">
                                <div class="flex-1 h-1 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="password-strength" class="h-full bg-gray-300 transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <span id="strength-text" class="text-xs text-gray-500">Weak</span>
                            </div>
                        </div>
                        
                        {{-- Password Requirements --}}
                        <div class="mt-2 text-xs text-gray-500">
                            <p>Password must contain:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li id="length-check" class="text-gray-400">At least 8 characters</li>
                                <li id="uppercase-check" class="text-gray-400">Upper & lowercase letters</li>
                                <li id="number-check" class="text-gray-400">At least one number</li>
                                <li id="special-check" class="text-gray-400">At least one special character</li>
                            </ul>
                        </div>
                        
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <div class="mt-1 relative">
                            <input id="password_confirmation" 
                                   name="password_confirmation" 
                                   type="password" 
                                   required 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Confirm new password">
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            field.type = field.type === 'password' ? 'text' : 'password';
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength');
            const strengthText = document.getElementById('strength-text');
            
            // Check individual requirements
            const hasLength = password.length >= 8;
            const hasUpperLower = password.match(/[a-z]/) && password.match(/[A-Z]/);
            const hasNumber = password.match(/[0-9]/);
            const hasSpecial = password.match(/[^a-zA-Z0-9]/);
            
            // Update requirement indicators
            document.getElementById('length-check').className = hasLength ? 'text-green-600' : 'text-gray-400';
            document.getElementById('uppercase-check').className = hasUpperLower ? 'text-green-600' : 'text-gray-400';
            document.getElementById('number-check').className = hasNumber ? 'text-green-600' : 'text-gray-400';
            document.getElementById('special-check').className = hasSpecial ? 'text-green-600' : 'text-gray-400';
            
            // Calculate strength
            let strength = 0;
            if (hasLength) strength++;
            if (hasUpperLower) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;
            
            const percentage = (strength / 4) * 100;
            strengthBar.style.width = percentage + '%';
            
            if (strength === 0) {
                strengthBar.className = 'h-full bg-gray-300 transition-all duration-300';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-xs text-gray-500';
            } else if (strength <= 2) {
                strengthBar.className = 'h-full bg-yellow-400 transition-all duration-300';
                strengthText.textContent = 'Fair';
                strengthText.className = 'text-xs text-yellow-600';
            } else if (strength === 3) {
                strengthBar.className = 'h-full bg-blue-500 transition-all duration-300';
                strengthText.textContent = 'Good';
                strengthText.className = 'text-xs text-blue-600';
            } else {
                strengthBar.className = 'h-full bg-green-500 transition-all duration-300';
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-xs text-green-600';
            }
        }
    </script>
    @endpush
</x-layout>