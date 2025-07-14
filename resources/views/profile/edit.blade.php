{{-- resources/views/profile/edit.blade.php --}}
<x-student-layout>
    <x-slot:title>My Profile</x-slot>
    
    <!-- Profile Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-7xl mx-auto">
                <!-- Profile Header Card -->
                <div class="relative rounded-3xl p-8 lg:p-12 mb-8 overflow-hidden border border-purple-500/30 bg-gradient-to-br from-purple-900/30 via-purple-800/20 to-pink-900/30 backdrop-blur-xl">
                    <!-- Background Effects -->
                    <div class="absolute inset-0">
                        <div class="absolute w-96 h-96 -top-48 -left-48 bg-purple-600 rounded-full opacity-20 blur-3xl"></div>
                        <div class="absolute w-64 h-64 -bottom-32 -right-32 bg-pink-600 rounded-full opacity-20 blur-3xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 via-transparent to-pink-600/10"></div>
                    </div>
                    
                    <div class="relative flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                        <!-- Profile Image -->
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 p-[2px] shadow-xl shadow-purple-500/30">
                                <div class="w-full h-full rounded-2xl bg-slate-900 p-1">
                                    @if($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                             class="w-full h-full rounded-xl object-cover">
                                    @else
                                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white text-4xl font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <form id="avatar-form" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" style="display: none;">
                                @csrf
                                <input type="file" id="avatar-input" name="avatar" accept="image/*">
                            </form>
                            <button type="button" onclick="document.getElementById('avatar-input').click()" 
                                    class="absolute bottom-0 right-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full p-3 shadow-lg hover:from-purple-700 hover:to-pink-700 transition transform group-hover:scale-110">
                                <i class="fas fa-camera text-white"></i>
                            </button>
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="text-center md:text-left flex-1">
                            <h1 class="text-3xl font-bold text-white mb-2">{{ $user->name }}</h1>
                            <p class="text-gray-400 mb-4">{{ $user->email }}</p>
                            
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium glass border border-white/10">
                                    <i class="fas fa-calendar text-purple-400 mr-2"></i>
                                    <span class="text-gray-300">Member since {{ $user->created_at->format('M Y') }}</span>
                                </span>
                                
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium 
                                    @if($user->subscription_status === 'pro') 
                                        bg-gradient-to-r from-purple-600/20 to-pink-600/20 border border-purple-500/50
                                    @elseif($user->subscription_status === 'premium') 
                                        bg-gradient-to-r from-blue-600/20 to-cyan-600/20 border border-blue-500/50
                                    @else 
                                        glass border border-white/10
                                    @endif">
                                    <i class="fas fa-crown text-{{ $user->subscription_status === 'pro' ? 'purple' : ($user->subscription_status === 'premium' ? 'blue' : 'gray') }}-400 mr-2"></i>
                                    <span class="text-white">{{ ucfirst($user->subscription_status) }} Plan</span>
                                </span>
                                
                                @if($user->country_name)
                                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium glass border border-white/10">
                                        <i class="fas fa-map-marker-alt text-pink-400 mr-2"></i>
                                        <span class="text-gray-300">{{ $user->country_name }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Profile Completion -->
                        <div class="mt-4 md:mt-0">
                            <div class="glass rounded-2xl p-6 border border-white/10">
                                <p class="text-sm text-gray-400 mb-3">Profile Completion</p>
                                @php
                                    $completion = 0;
                                    if($user->name) $completion += 25;
                                    if($user->email) $completion += 25;
                                    if($user->phone_number) $completion += 25;
                                    if($user->country_name) $completion += 25;
                                @endphp
                                <div class="relative w-28 h-28 mx-auto">
                                    <svg class="w-28 h-28 transform -rotate-90">
                                        <circle cx="56" cy="56" r="45" stroke="rgba(255,255,255,0.1)" stroke-width="8" fill="none"/>
                                        <circle cx="56" cy="56" r="45" stroke="url(#gradient)" stroke-width="8" fill="none"
                                                stroke-dasharray="{{ $completion * 2.83 }} 283"
                                                stroke-linecap="round"/>
                                        <defs>
                                            <linearGradient id="gradient">
                                                <stop offset="0%" stop-color="#a855f7" />
                                                <stop offset="100%" stop-color="#ec4899" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-3xl font-bold text-white">{{ $completion }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Stats & Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Stats Card -->
                    <div class="glass rounded-2xl p-6 border border-white/10">
                        <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                            <i class="fas fa-chart-line text-purple-400 mr-2"></i>
                            Your Statistics
                        </h3>
                        <div class="space-y-4">
                            <div class="glass rounded-xl p-4 border border-white/5 hover:border-purple-500/30 transition-all">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Tests Completed</span>
                                    <span class="text-2xl font-bold text-white">{{ $user->attempts()->where('status', 'completed')->count() }}</span>
                                </div>
                            </div>
                            
                            <div class="glass rounded-xl p-4 border border-white/5 hover:border-blue-500/30 transition-all">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Average Score</span>
                                    <span class="text-2xl font-bold text-white">
                                        {{ number_format($user->attempts()->whereNotNull('band_score')->avg('band_score') ?? 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="glass rounded-xl p-4 border border-white/5 hover:border-orange-500/30 transition-all">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Study Streak</span>
                                    <span class="text-2xl font-bold text-orange-400 flex items-center">
                                        <i class="fas fa-fire mr-2"></i>
                                        {{ $user->study_streak_days ?? 0 }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="glass rounded-xl p-4 border border-white/5 hover:border-purple-500/30 transition-all">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Achievement Points</span>
                                    <span class="text-2xl font-bold text-purple-400 flex items-center">
                                        <i class="fas fa-star mr-2"></i>
                                        {{ $user->achievement_points ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="glass rounded-2xl p-6 border border-white/10">
                        <h3 class="text-lg font-semibold text-white mb-6 flex items-center">
                            <i class="fas fa-rocket text-pink-400 mr-2"></i>
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('subscription.index') }}" 
                               class="flex items-center justify-between glass rounded-xl p-4 hover:border-purple-500/50 transition-all group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mr-3 group-hover:from-purple-500/30 group-hover:to-pink-500/30 transition">
                                        <i class="fas fa-credit-card text-purple-400"></i>
                                    </div>
                                    <span class="font-medium text-white">Manage Subscription</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400 group-hover:text-white transition"></i>
                            </a>
                            
                            <a href="{{ route('student.results') }}" 
                               class="flex items-center justify-between glass rounded-xl p-4 hover:border-blue-500/50 transition-all group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mr-3 group-hover:from-blue-500/30 group-hover:to-cyan-500/30 transition">
                                        <i class="fas fa-chart-bar text-blue-400"></i>
                                    </div>
                                    <span class="font-medium text-white">View Results</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400 group-hover:text-white transition"></i>
                            </a>
                            
                            <a href="{{ route('student.dashboard') }}" 
                               class="flex items-center justify-between glass rounded-xl p-4 hover:border-green-500/50 transition-all group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center mr-3 group-hover:from-green-500/30 group-hover:to-emerald-500/30 transition">
                                        <i class="fas fa-home text-green-400"></i>
                                    </div>
                                    <span class="font-medium text-white">Dashboard</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400 group-hover:text-white transition"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Achievements -->
                    <div class="glass rounded-2xl p-6 border border-purple-500/30 bg-gradient-to-br from-purple-600/10 to-pink-600/10">
                        <h3 class="text-lg font-semibold text-white mb-4">Recent Achievements</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="glass rounded-xl p-4 text-center hover:border-purple-500/50 transition-all cursor-pointer">
                                <i class="fas fa-fire text-orange-400 text-2xl mb-2"></i>
                                <p class="text-xs text-gray-400">7 Day Streak</p>
                            </div>
                            <div class="glass rounded-xl p-4 text-center hover:border-purple-500/50 transition-all cursor-pointer">
                                <i class="fas fa-star text-yellow-400 text-2xl mb-2"></i>
                                <p class="text-xs text-gray-400">100 Points</p>
                            </div>
                            <div class="glass rounded-xl p-4 text-center hover:border-purple-500/50 transition-all cursor-pointer">
                                <i class="fas fa-trophy text-purple-400 text-2xl mb-2"></i>
                                <p class="text-xs text-gray-400">Top 10</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Profile Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Information Form -->
                    <div class="glass rounded-2xl p-8 border border-white/10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-white">Profile Information</h3>
                                <p class="text-sm text-gray-400 mt-1">Update your account's profile information</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                                <i class="fas fa-user text-purple-400"></i>
                            </div>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full glass bg-transparent text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition placeholder-gray-500">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full glass bg-transparent text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition placeholder-gray-500">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                        class="w-full glass bg-transparent text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition placeholder-gray-500"
                                        placeholder="+880 1XXX-XXXXXX">
                                    @error('phone_number')
                                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-300 mb-2">Country</label>
                                    <select name="country_code" id="country" 
                                        class="w-full glass bg-transparent text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                                        <option value="" class="bg-slate-900">Select Country</option>
                                        <option value="BD" {{ $user->country_code == 'BD' ? 'selected' : '' }} class="bg-slate-900">Bangladesh</option>
                                        <option value="IN" {{ $user->country_code == 'IN' ? 'selected' : '' }} class="bg-slate-900">India</option>
                                        <option value="PK" {{ $user->country_code == 'PK' ? 'selected' : '' }} class="bg-slate-900">Pakistan</option>
                                        <option value="GB" {{ $user->country_code == 'GB' ? 'selected' : '' }} class="bg-slate-900">United Kingdom</option>
                                        <option value="US" {{ $user->country_code == 'US' ? 'selected' : '' }} class="bg-slate-900">United States</option>
                                        <option value="CA" {{ $user->country_code == 'CA' ? 'selected' : '' }} class="bg-slate-900">Canada</option>
                                        <option value="AU" {{ $user->country_code == 'AU' ? 'selected' : '' }} class="bg-slate-900">Australia</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-medium hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl neon-purple">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm text-green-400 flex items-center animate-pulse">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Saved successfully!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Update Password Form -->
                    <div class="glass rounded-2xl p-8 border border-white/10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-white">Update Password</h3>
                                <p class="text-sm text-gray-400 mt-1">Ensure your account is using a secure password</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                                <i class="fas fa-lock text-amber-400"></i>
                            </div>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password" required
                                        class="w-full glass bg-transparent text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition pr-12 placeholder-gray-500">
                                    <button type="button" onclick="togglePassword('current_password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                        <i class="fas fa-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                                @error('current_password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required
                                        class="w-full glass bg-transparent text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition pr-12 placeholder-gray-500">
                                    <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                        <i class="fas fa-eye" id="password_icon"></i>
                                    </button>
                                </div>
                                @error('password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full glass bg-transparent text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition pr-12 placeholder-gray-500">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition">
                                        <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-medium hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl neon-purple">
                                    <i class="fas fa-key mr-2"></i>Update Password
                                </button>
                                @if (session('status') === 'password-updated')
                                    <p class="text-sm text-green-400 flex items-center animate-pulse">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Password updated successfully!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Security Settings -->
                    <div class="glass rounded-2xl p-8 border border-white/10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-white">Security Settings</h3>
                                <p class="text-sm text-gray-400 mt-1">Manage your account security preferences</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-green-400"></i>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Login Notifications -->
                            <div class="glass rounded-xl p-4 border border-white/5 hover:border-green-500/30 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-bell text-green-400 mr-3"></i>
                                        <div>
                                            <h4 class="font-medium text-white">Login Notifications</h4>
                                            <p class="text-sm text-gray-400">Get notified of new device logins</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-purple-600 peer-checked:to-pink-600"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Email Notifications -->
                            <div class="glass rounded-xl p-4 border border-white/5 hover:border-blue-500/30 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-blue-400 mr-3"></i>
                                        <div>
                                            <h4 class="font-medium text-white">Email Notifications</h4>
                                            <p class="text-sm text-gray-400">Receive study reminders and updates</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-purple-600 peer-checked:to-pink-600"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Privacy Settings -->
                            <div class="glass rounded-xl p-4 border border-white/5 hover:border-purple-500/30 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-shield text-purple-400 mr-3"></i>
                                        <div>
                                            <h4 class="font-medium text-white">Show on Leaderboard</h4>
                                            <p class="text-sm text-gray-400">Display your name in public rankings</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" {{ $user->show_on_leaderboard ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-purple-600 peer-checked:to-pink-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
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
        
        // Avatar upload handler
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                // Show loading state (optional)
                const button = document.querySelector('.fa-camera').parentElement;
                button.innerHTML = '<i class="fas fa-spinner fa-spin text-white"></i>';
                
                // Submit the form
                document.getElementById('avatar-form').submit();
            }
        });
    </script>
    @endpush
</x-student-layout>