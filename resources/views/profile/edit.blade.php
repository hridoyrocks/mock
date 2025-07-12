{{-- resources/views/profile/edit.blade.php --}}
<x-student-layout>
    <x-slot:title>My Profile</x-slot>
    
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Profile Header --}}
            <div class="bg-gradient-to-r from-red-500 via-orange-500 to-red-600 rounded-3xl shadow-xl p-8 mb-8 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0">
                    <div class="absolute w-96 h-96 -top-48 -left-48 bg-red-400 rounded-full opacity-20"></div>
                    <div class="absolute w-64 h-64 -bottom-32 -right-32 bg-orange-400 rounded-full opacity-20"></div>
                </div>
                
                <div class="relative flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                    {{-- Profile Image --}}
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-full bg-white p-1 shadow-xl">
                            <img class="w-full h-full rounded-full object-cover" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=256&background=ef4444&color=fff&bold=true" 
                                 alt="{{ $user->name }}">
                        </div>
                        <button class="absolute bottom-0 right-0 bg-white rounded-full p-3 shadow-lg hover:bg-gray-100 transition group-hover:scale-110">
                            <i class="fas fa-camera text-gray-600"></i>
                        </button>
                    </div>
                    
                    {{-- Profile Info --}}
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-red-100 mt-1">{{ $user->email }}</p>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur text-white">
                                <i class="fas fa-clock mr-2"></i>
                                Member since {{ $user->created_at->format('M Y') }}
                            </span>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                @if($user->subscription_status === 'pro') bg-purple-500 text-white
                                @elseif($user->subscription_status === 'premium') bg-blue-500 text-white
                                @else bg-white/20 backdrop-blur text-white
                                @endif">
                                <i class="fas fa-crown mr-2"></i>
                                {{ ucfirst($user->subscription_status) }} Plan
                            </span>
                            @if($user->country_name)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur text-white">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $user->country_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Profile Completion --}}
                    <div class="mt-4 md:mt-0">
                        <div class="bg-white/20 backdrop-blur rounded-xl p-4">
                            <p class="text-sm text-white mb-2">Profile Completion</p>
                            @php
                                $completion = 0;
                                if($user->name) $completion += 25;
                                if($user->email) $completion += 25;
                                if($user->phone_number) $completion += 25;
                                if($user->country_name) $completion += 25;
                            @endphp
                            <div class="relative w-24 h-24">
                                <svg class="w-24 h-24 transform -rotate-90">
                                    <circle cx="48" cy="48" r="36" stroke="rgba(255,255,255,0.2)" stroke-width="8" fill="none"/>
                                    <circle cx="48" cy="48" r="36" stroke="white" stroke-width="8" fill="none"
                                            stroke-dasharray="{{ $completion * 2.26 }} 226"
                                            stroke-linecap="round"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">{{ $completion }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column - Stats & Actions --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Stats Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-line text-red-500 mr-2"></i>
                            Your Statistics
                        </h3>
                        <div class="space-y-4">
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tests Completed</span>
                                    <span class="text-2xl font-bold text-gray-900">{{ $user->attempts()->where('status', 'completed')->count() }}</span>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Average Score</span>
                                    <span class="text-2xl font-bold text-gray-900">
                                        {{ number_format($user->attempts()->whereNotNull('band_score')->avg('band_score') ?? 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Study Streak</span>
                                    <span class="text-2xl font-bold text-orange-600">
                                        <i class="fas fa-fire mr-1"></i>
                                        {{ $user->study_streak_days ?? 0 }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Achievement Points</span>
                                    <span class="text-2xl font-bold text-purple-600">
                                        <i class="fas fa-star mr-1"></i>
                                        {{ $user->achievement_points ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-rocket text-red-500 mr-2"></i>
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('subscription.index') }}" 
                               class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-200 transition">
                                        <i class="fas fa-credit-card text-red-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">Manage Subscription</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </a>
                            
                            <a href="{{ route('student.results') }}" 
                               class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition">
                                        <i class="fas fa-chart-bar text-blue-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">View Results</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </a>
                            
                            <a href="{{ route('student.dashboard') }}" 
                               class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition">
                                        <i class="fas fa-home text-green-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">Dashboard</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Badges Preview --}}
                    <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl p-6 text-white">
                        <h3 class="text-lg font-semibold mb-4">Recent Achievements</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-white/20 backdrop-blur rounded-lg p-3 text-center">
                                <i class="fas fa-fire text-2xl mb-1"></i>
                                <p class="text-xs">Streak</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur rounded-lg p-3 text-center">
                                <i class="fas fa-star text-2xl mb-1"></i>
                                <p class="text-xs">Points</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur rounded-lg p-3 text-center">
                                <i class="fas fa-trophy text-2xl mb-1"></i>
                                <p class="text-xs">Top 10</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Profile Forms --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Profile Information Form --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Profile Information</h3>
                                <p class="text-sm text-gray-500 mt-1">Update your account's profile information</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-red-600"></i>
                            </div>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                        placeholder="+880 1XXX-XXXXXX">
                                    @error('phone_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                    <select name="country_code" id="country" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                                        <option value="">Select Country</option>
                                        <option value="BD" {{ $user->country_code == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                                        <option value="IN" {{ $user->country_code == 'IN' ? 'selected' : '' }}>India</option>
                                        <option value="PK" {{ $user->country_code == 'PK' ? 'selected' : '' }}>Pakistan</option>
                                        <option value="GB" {{ $user->country_code == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="US" {{ $user->country_code == 'US' ? 'selected' : '' }}>United States</option>
                                        <option value="CA" {{ $user->country_code == 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="AU" {{ $user->country_code == 'AU' ? 'selected' : '' }}>Australia</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl font-medium hover:from-red-600 hover:to-orange-600 transition shadow-lg hover:shadow-xl">
                                    Save Changes
                                </button>
                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm text-green-600 flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Saved successfully!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Update Password Form --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Update Password</h3>
                                <p class="text-sm text-gray-500 mt-1">Ensure your account is using a secure password</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-lock text-yellow-600"></i>
                            </div>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition pr-12">
                                    <button type="button" onclick="togglePassword('current_password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition pr-12">
                                    <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition pr-12">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl font-medium hover:from-red-600 hover:to-orange-600 transition shadow-lg hover:shadow-xl">
                                    Update Password
                                </button>
                                @if (session('status') === 'password-updated')
                                    <p class="text-sm text-green-600 flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Password updated successfully!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Security Settings --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Security Settings</h3>
                                <p class="text-sm text-gray-500 mt-1">Manage your account security preferences</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-alt text-green-600"></i>
                            </div>
                        </div>

                        <div class="space-y-4">
                            {{-- Two Factor Authentication --}}
                            <div class="p-4 border border-gray-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-mobile-alt text-gray-400 mr-3"></i>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Two-Factor Authentication</h4>
                                            <p class="text-sm text-gray-500">Add an extra layer of security</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                    </label>
                                </div>
                            </div>

                            {{-- Login Notifications --}}
                            <div class="p-4 border border-gray-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-bell text-gray-400 mr-3"></i>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Login Notifications</h4>
                                            <p class="text-sm text-gray-500">Get notified of new device logins</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Delete Account --}}
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-red-900">Danger Zone</h3>
                                <p class="text-sm text-red-700 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                        </div>
                        
                        <button onclick="confirmDeleteAccount()" class="px-6 py-3 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition shadow-lg hover:shadow-xl">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Delete Account?</h3>
                <p class="text-gray-600">This action cannot be undone. All your data will be permanently deleted.</p>
            </div>
            
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="mb-6">
                    <label for="password_delete" class="block text-sm font-medium text-gray-700 mb-2">Enter your password to confirm</label>
                    <input type="password" name="password" id="password_delete" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition shadow-lg hover:shadow-xl">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        function confirmDeleteAccount() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    @endpush
</x-student-layout>