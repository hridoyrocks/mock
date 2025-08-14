{{-- resources/views/profile/edit.blade.php --}}
<x-student-layout>
    <x-slot:title>My Profile</x-slot>
    
    <div x-data="{ otpModalOpen: false }">
        <!-- Profile Header Section -->
    <section class="relative">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <!-- Profile Header Card -->
                <div class="relative rounded-2xl p-8 mb-8 glass border" :class="darkMode ? 'border-white/10' : 'border-[#C8102E]/20'">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                        <!-- Profile Image -->
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] p-[2px] shadow-lg">
                                <div class="w-full h-full rounded-2xl" :class="darkMode ? 'bg-gray-900' : 'bg-white'">
                                    @if($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                             class="w-full h-full rounded-2xl object-cover p-1">
                                    @else
                                        <div class="w-full h-full rounded-2xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center text-white text-4xl font-bold">
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
                                    class="absolute bottom-0 right-0 bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full p-2.5 shadow-lg hover:from-[#A00E27] hover:to-[#8A0C20] transition transform group-hover:scale-110">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="text-center md:text-left flex-1">
                            <h1 class="text-3xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $user->name }}</h1>
                            <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="mb-4">{{ $user->email }}</p>
                            
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium glass" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                    <svg class="w-4 h-4 mr-2" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Member since {{ $user->created_at->format('M Y') }}</span>
                                </span>
                                
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium 
                                    @if($user->subscription_status === 'pro') 
                                        bg-gradient-to-r from-[#C8102E]/20 to-[#A00E27]/20 border border-[#C8102E]/50 text-[#C8102E]
                                    @elseif($user->subscription_status === 'premium') 
                                        bg-gradient-to-r from-[#C8102E]/10 to-[#A00E27]/10 border border-[#C8102E]/30 text-[#C8102E]
                                    @else 
                                        glass
                                    @endif" :class="!darkMode && '{{ $user->subscription_status }}' === 'free' ? 'border-gray-200' : 'border-white/10'">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    {{ ucfirst($user->subscription_status) }} Plan
                                </span>
                                
                                @if($user->country_name)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium glass" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                        <svg class="w-4 h-4 mr-2" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{{ $user->country_name }}</span>
                                    </span>
                                @endif
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Stats & Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Stats Card -->
                    <div class="glass rounded-xl p-6 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                        <h3 class="text-lg font-semibold mb-6 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <svg class="w-5 h-5 mr-2 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Your Statistics
                        </h3>
                        <div class="space-y-4">
                            <div class="glass rounded-lg p-4" :class="darkMode ? 'hover:border-[#C8102E]/30' : 'hover:bg-gray-50'" style="transition: all 0.3s;">
                                <div class="flex justify-between items-center">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="text-sm">Tests Completed</span>
                                    <span class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $user->attempts()->where('status', 'completed')->count() }}</span>
                                </div>
                            </div>
                            
                            <div class="glass rounded-lg p-4" :class="darkMode ? 'hover:border-[#C8102E]/30' : 'hover:bg-gray-50'" style="transition: all 0.3s;">
                                <div class="flex justify-between items-center">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="text-sm">Average Score</span>
                                    <span class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        {{ number_format($user->attempts()->whereNotNull('band_score')->avg('band_score') ?? 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="glass rounded-lg p-4" :class="darkMode ? 'hover:border-[#C8102E]/30' : 'hover:bg-gray-50'" style="transition: all 0.3s;">
                                <div class="flex justify-between items-center">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="text-sm">Study Streak</span>
                                    <span class="text-2xl font-bold text-[#C8102E] flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $user->study_streak_days ?? 0 }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="glass rounded-lg p-4" :class="darkMode ? 'hover:border-[#C8102E]/30' : 'hover:bg-gray-50'" style="transition: all 0.3s;">
                                <div class="flex justify-between items-center">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="text-sm">Achievement Points</span>
                                    <span class="text-2xl font-bold text-[#C8102E] flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        {{ $user->achievement_points ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="glass rounded-xl p-6 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                        <h3 class="text-lg font-semibold mb-6 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <svg class="w-5 h-5 mr-2 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('subscription.index') }}" 
                               class="flex items-center justify-between p-3 rounded-lg glass hover:border-[#C8102E]/30 transition-all group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">Manage Subscription</span>
                                </div>
                                <svg class="w-5 h-5 transition-colors" :class="darkMode ? 'text-gray-400 group-hover:text-white' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            
                            <a href="{{ route('student.results') }}" 
                               class="flex items-center justify-between p-3 rounded-lg glass hover:border-[#C8102E]/30 transition-all group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-white shadow-md'" style="display: flex; align-items: center; justify-content: center;">
                                        <svg class="w-5 h-5 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">View Results</span>
                                </div>
                                <svg class="w-5 h-5 transition-colors" :class="darkMode ? 'text-gray-400 group-hover:text-white' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            
                            <a href="{{ route('student.dashboard') }}" 
                               class="flex items-center justify-between p-3 rounded-lg glass hover:border-[#C8102E]/30 transition-all group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-white shadow-md'" style="display: flex; align-items: center; justify-content: center;">
                                        <svg class="w-5 h-5 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">Dashboard</span>
                                </div>
                                <svg class="w-5 h-5 transition-colors" :class="darkMode ? 'text-gray-400 group-hover:text-white' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Achievements -->
                    <div class="bg-gradient-to-br from-[#C8102E] to-[#A00E27] rounded-xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-white mb-4">Recent Achievements</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-white/20 backdrop-blur rounded-lg p-3 text-center hover:bg-white/30 transition-colors cursor-pointer">
                                <svg class="w-8 h-8 mx-auto mb-2 text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-xs text-white/90">7 Day Streak</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur rounded-lg p-3 text-center hover:bg-white/30 transition-colors cursor-pointer">
                                <svg class="w-8 h-8 mx-auto mb-2 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <p class="text-xs text-white/90">100 Points</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur rounded-lg p-3 text-center hover:bg-white/30 transition-colors cursor-pointer">
                                <svg class="w-8 h-8 mx-auto mb-2 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd"></path>
                                    <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"></path>
                                </svg>
                                <p class="text-xs text-white/90">Top 10</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Profile Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Information Form -->
                    <div class="glass rounded-xl p-6 lg:p-8 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Profile Information</h3>
                                <p class="text-sm mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Update your account's profile information</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors"
                                        :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Email Address</label>
                                    <div class="relative">
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                            class="w-full px-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors"
                                            :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                        @if(!$user->hasVerifiedEmail())
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs px-2 py-1 rounded bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                                Unverified
                                            </span>
                                        @elseif(session('pending_email'))
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs px-2 py-1 rounded bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                Pending Change
                                            </span>
                                        @endif
                                    </div>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    
                                    @if(session('pending_email'))
                                        <div class="mt-3 p-3 rounded-lg border" :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-amber-50 border-amber-200'">
                                            <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                Verification email sent to: <strong>{{ session('pending_email') }}</strong>
                                            </p>
                                            <div class="flex gap-2 mt-2">
                                                <button type="button" 
                                                        @click="otpModalOpen = true; console.log('Modal opened:', otpModalOpen)" 
                                                        onclick="window.otpModalOpen = true"
                                                        class="text-sm px-3 py-1 bg-[#C8102E] text-white rounded hover:bg-[#A00E27] transition">
                                                    Enter Code
                                                </button>
                                                <form method="POST" action="{{ route('profile.resend-email-otp') }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-sm px-3 py-1 border rounded transition"
                                                            :class="darkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'">
                                                        Resend Code
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('profile.cancel-email-change') }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-sm px-3 py-1 text-red-600 hover:text-red-700">
                                                        Cancel
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @php
                                    $countries = \App\Helpers\CountryHelper::getAllCountries();
                                @endphp
                                
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Phone Number</label>
                                    <div class="flex gap-2">
                                        <select id="phone_country_code" name="phone_country_code" 
                                            class="w-32 px-3 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors"
                                            :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                            @foreach($countries as $code => $country)
                                                <option value="{{ $country['code'] }}">{{ $country['flag'] }} {{ $country['code'] }}</option>
                                            @endforeach
                                        </select>
                                        <input type="tel" name="phone_number" id="phone_number" 
                                            value="{{ old('phone_number', $user->phone_number) }}"
                                            class="flex-1 px-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors"
                                            :class="darkMode ? 'bg-gray-800 border-gray-700 text-white placeholder-gray-500' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-400'"
                                            placeholder="Enter phone number">
                                    </div>
                                    @error('phone_number')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="country" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Country</label>
                                    <select name="country_code" id="country" 
                                        class="w-full px-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors"
                                        :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'"
                                        onchange="updatePhoneCountryCode(this.value)">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $code => $country)
                                            <option value="{{ $code }}" data-phone="{{ $country['code'] }}" {{ $user->country_code == $code ? 'selected' : '' }}>
                                                {{ $country['flag'] }} {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white rounded-lg font-medium hover:from-[#A00E27] hover:to-[#8A0C20] transition-all shadow-md hover:shadow-lg">
                                    Save Changes
                                </button>
                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm text-green-500 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Saved successfully!
                                    </p>
                                @elseif (session('status') === 'email-verification-sent')
                                    <p class="text-sm text-blue-500 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Verification email sent!
                                    </p>
                                @elseif (session('status') === 'email-updated')
                                    <p class="text-sm text-green-500 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Email updated successfully!
                                    </p>
                                @elseif (session('status') === 'otp-resent')
                                    <p class="text-sm text-blue-500 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                        </svg>
                                        Verification code resent!
                                    </p>
                                @elseif (session('status') === 'email-change-cancelled')
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Email change cancelled
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Update Password Form -->
                    <div class="glass rounded-xl p-6 lg:p-8 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Update Password</h3>
                                <p class="text-sm mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Ensure your account is using a secure password</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="current_password" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Current Password</label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password" required
                                        class="w-full px-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors pr-12"
                                        :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                    <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 transition-colors"
                                            :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-700'">
                                        <svg class="w-5 h-5" id="current_password_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('current_password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">New Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors pr-12"
                                        :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                    <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 transition-colors"
                                            :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-700'">
                                        <svg class="w-5 h-5" id="password_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full px-4 py-2.5 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors pr-12"
                                        :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 transition-colors"
                                            :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-700'">
                                        <svg class="w-5 h-5" id="password_confirmation_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white rounded-lg font-medium hover:from-[#A00E27] hover:to-[#8A0C20] transition-all shadow-md hover:shadow-lg">
                                    Update Password
                                </button>
                                @if (session('status') === 'password-updated')
                                    <p class="text-sm text-green-500 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Password updated successfully!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Security Settings -->
                    <div class="glass rounded-xl p-6 lg:p-8 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Security Settings</h3>
                                <p class="text-sm mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Manage your account security preferences</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Login Notifications -->
                            <div class="p-4 glass rounded-lg hover:border-[#C8102E]/30 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">Login Notifications</h4>
                                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Get notified of new device logins</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#C8102E]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C8102E]"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Email Notifications -->
                            <div class="p-4 glass rounded-lg hover:border-[#C8102E]/30 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">Email Notifications</h4>
                                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Receive study reminders and updates</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#C8102E]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C8102E]"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Privacy Settings -->
                            <div class="p-4 glass rounded-lg hover:border-[#C8102E]/30 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-[#C8102E] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">Show on Leaderboard</h4>
                                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Display your name in public rankings</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" {{ $user->show_on_leaderboard ?? true ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#C8102E]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C8102E]"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Email Verification OTP Modal -->
    <div x-show="otpModalOpen" 
         x-cloak
         @keydown.escape.window="otpModalOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="otpModalOpen = false" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
            
            <div x-show="otpModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative glass rounded-2xl w-full max-w-md p-6 border" :class="darkMode ? 'border-white/10' : 'border-gray-200 bg-white'">
                
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#C8102E] to-[#A00E27] mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Verify Email Change</h3>
                    <p class="text-sm mt-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Enter the 6-digit code sent to {{ session('pending_email') }}</p>
                </div>
                
                <form method="POST" action="{{ route('profile.verify-email-change') }}" id="otpForm">
                    @csrf
                    <div class="mb-6">
                        <label for="otp" class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Verification Code</label>
                        <input type="text" 
                               name="otp" 
                               id="otp" 
                               maxlength="6" 
                               pattern="[0-9]{6}" 
                               required
                               class="w-full px-4 py-3 text-center text-2xl font-mono tracking-widest rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-colors"
                               :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-300 text-gray-900'"
                               placeholder="000000"
                               autocomplete="off">
                        @error('otp')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white rounded-lg font-medium hover:from-[#A00E27] hover:to-[#8A0C20] transition-all">
                            Verify Email
                        </button>
                        <button type="button" @click="otpModalOpen = false" 
                                class="px-4 py-2.5 border rounded-lg font-medium transition"
                                :class="darkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'">
                            Cancel
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <form method="POST" action="{{ route('profile.resend-email-otp') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm" :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-gray-900'">
                            Didn't receive the code? <span class="underline">Resend</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Phone number formatting and country code management
        function updatePhoneCountryCode(countryCode) {
            const countrySelect = document.getElementById('country');
            const phoneCodeSelect = document.getElementById('phone_country_code');
            const selectedOption = countrySelect.options[countrySelect.selectedIndex];
            const phoneCode = selectedOption.getAttribute('data-phone');
            
            if (phoneCode && phoneCodeSelect) {
                // Find and select the matching phone code
                for (let i = 0; i < phoneCodeSelect.options.length; i++) {
                    if (phoneCodeSelect.options[i].value === phoneCode) {
                        phoneCodeSelect.selectedIndex = i;
                        updatePhoneFormat();
                        break;
                    }
                }
            }
        }
        
        function updatePhoneFormat() {
            const phoneCodeSelect = document.getElementById('phone_country_code');
            const phoneInput = document.getElementById('phone_number');
            const selectedOption = phoneCodeSelect.options[phoneCodeSelect.selectedIndex];
            const format = selectedOption.getAttribute('data-format');
            
            if (format && phoneInput) {
                phoneInput.placeholder = format.replace(/X/g, '0');
            }
        }
        
        function formatPhoneNumber(input) {
            // Remove all non-digit characters
            let value = input.value.replace(/\D/g, '');
            const phoneCodeSelect = document.getElementById('phone_country_code');
            const selectedOption = phoneCodeSelect.options[phoneCodeSelect.selectedIndex];
            const format = selectedOption.getAttribute('data-format');
            
            if (!format) return;
            
            let formatted = '';
            let digitIndex = 0;
            
            for (let i = 0; i < format.length && digitIndex < value.length; i++) {
                if (format[i] === 'X') {
                    formatted += value[digitIndex];
                    digitIndex++;
                } else {
                    formatted += format[i];
                }
            }
            
            input.value = formatted;
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial phone country code based on user's country
            const userCountryCode = '{{ $user->country_code }}';
            if (userCountryCode) {
                updatePhoneCountryCode(userCountryCode);
            }
            
            // Parse existing phone number if present
            const phoneNumber = '{{ $user->phone_number }}';
            if (phoneNumber) {
                // Extract country code and number
                const match = phoneNumber.match(/^(\+\d+)\s*(.*)$/);
                if (match) {
                    const phoneCode = match[1];
                    const number = match[2];
                    
                    // Set phone country code
                    const phoneCodeSelect = document.getElementById('phone_country_code');
                    for (let i = 0; i < phoneCodeSelect.options.length; i++) {
                        if (phoneCodeSelect.options[i].value === phoneCode) {
                            phoneCodeSelect.selectedIndex = i;
                            break;
                        }
                    }
                    
                    // Set phone number
                    document.getElementById('phone_number').value = number;
                }
            }
        });
        
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                field.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
        
        // Avatar upload handler
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                // Show loading state
                const button = document.querySelector('button[onclick*="avatar-input"]');
                const originalContent = button.innerHTML;
                button.innerHTML = '<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                // Submit the form
                document.getElementById('avatar-form').submit();
            }
        });
    </script>
    @endpush
    </div>
</x-student-layout>