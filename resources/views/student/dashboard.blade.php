{{-- resources/views/student/dashboard.blade.php --}}
<x-student-layout>
    <x-slot:title>Dashboard</x-slot>

    <!-- Hero Section with Journey Progress -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0" :class="darkMode ? 'bg-black/20' : 'bg-gradient-to-br from-[#C8102E]/5 via-transparent to-[#C8102E]/10'"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-7xl mx-auto">
                <!-- Welcome Banner -->
                <div class="rounded-2xl shadow-xl p-8 lg:p-12 mb-8" 
                     :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-[#C8102E]/10'">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                        <div class="flex-1 text-center lg:text-left">
                            <h1 class="text-3xl lg:text-4xl font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                Welcome back, {{ auth()->user()->name }}! 🎯
                            </h1>
                            <p class="text-lg mb-6" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                                You're on fire! Keep up the great work.
                            </p>
                            
                            <!-- Quick Stats -->
                            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                                <div class="rounded-xl px-6 py-3" 
                                     :class="darkMode ? 'glass border border-[#C8102E]/30' : 'bg-gradient-to-br from-[#C8102E]/10 to-[#C8102E]/5 border border-[#C8102E]/20'">
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Current Streak</p>
                                    <p class="text-2xl font-bold text-[#C8102E]">
                                        <i class="fas fa-fire text-[#C8102E] mr-1"></i>
                                        {{ auth()->user()->study_streak_days ?? 0 }} days
                                    </p>
                                </div>
                                <div class="rounded-xl px-6 py-3" 
                                     :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200 shadow-sm'">
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Average Score</p>
                                    <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                        {{ $stats['average_band_score'] ? number_format($stats['average_band_score'], 1) : 'N/A' }}
                                    </p>
                                </div>
                                <div class="rounded-xl px-6 py-3" 
                                     :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200 shadow-sm'">
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Tests Completed</p>
                                    <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                        {{ $stats['completed_attempts'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Journey Visualization -->
                        <div class="relative">
                            <div class="w-48 h-48 lg:w-64 lg:h-64 relative">
                                <!-- Circular Progress -->
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="50%" cy="50%" r="45%" 
                                            stroke="rgba(200, 16, 46, 0.1)" 
                                            stroke-width="8" 
                                            fill="none" />
                                    <circle cx="50%" cy="50%" r="45%" 
                                            stroke="#C8102E" 
                                            stroke-width="8" 
                                            fill="none"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ 2 * 3.14159 * 45 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 45 * (1 - ($userGoal ? $userGoal->progress_percentage / 100 : 0)) }}" />
                                </svg>
                                
                                <!-- Center Content -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    @if($userGoal)
                                        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Target Band</p>
                                        <p class="text-4xl font-bold text-[#C8102E]">{{ $userGoal->target_band_score }}</p>
                                        <p class="text-sm text-[#C8102E]">{{ round($userGoal->progress_percentage) }}% Complete</p>
                                    @else
                                        <button onclick="openGoalModal()" 
                                                class="px-4 py-2 rounded-lg text-[#C8102E] transition-all shadow-md"
                                                :class="darkMode ? 'glass border border-[#C8102E]/30 hover:bg-[#C8102E]/10' : 'bg-white border border-[#C8102E]/30 hover:bg-[#C8102E] hover:text-white'">
                                            <i class="fas fa-plus mr-2"></i>Set Goal
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Challenge / Focus Area -->
                <div class="bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">
                                <i class="fas fa-bullseye mr-2"></i>
                                Today's Focus
                            </h3>
                            <p class="text-white/90">Based on your recent performance, we recommend focusing on:</p>
                        </div>
                        <div class="text-right">
                            @php
                                $weakestSection = $sectionPerformance->where('attempts_count', '>', 0)->sortBy('average_score')->first();
                            @endphp
                            @if($weakestSection)
                                <p class="text-2xl font-bold text-white capitalize">{{ $weakestSection['name'] }}</p>
                                <a href="{{ route('student.' . $weakestSection['name'] . '.index') }}" 
                                   class="inline-flex items-center text-white hover:text-white/80 mt-2">
                                    Practice Now <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            @else
                                <p class="text-lg text-white/80">Complete a test to get recommendations</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Dashboard Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Interactive Test Selection -->
                    <div class="rounded-2xl shadow-lg p-6" 
                         :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-100'">
                        <h2 class="text-xl font-bold mb-6" :class="darkMode ? 'text-white' : 'text-gray-800'">
                            <i class="fas fa-gamepad text-[#C8102E] mr-2"></i>
                            Start Your Practice
                        </h2>
                        
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Listening Card -->
                            <div class="group relative">
                                <a href="{{ route('student.listening.index') }}" 
                                   class="block rounded-xl p-6 transition-all duration-300 hover:-translate-y-1"
                                   :class="darkMode ? 'glass border border-[#C8102E]/20 hover:border-[#C8102E]/40' : 'bg-gradient-to-br from-[#C8102E]/5 to-[#C8102E]/10 border border-[#C8102E]/20 hover:border-[#C8102E]/40 hover:shadow-lg'">
                                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-3 shadow-md group-hover:shadow-lg transition-shadow">
                                        <i class="fas fa-headphones text-[#C8102E] text-2xl"></i>
                                    </div>
                                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">Listening</p>
                                    <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">30 min</p>
                                </a>
                            </div>

                            <!-- Reading Card -->
                            <div class="group relative">
                                <a href="{{ route('student.reading.index') }}" 
                                   class="block rounded-xl p-6 transition-all duration-300 hover:-translate-y-1"
                                   :class="darkMode ? 'glass border border-[#C8102E]/20 hover:border-[#C8102E]/40' : 'bg-gradient-to-br from-[#C8102E]/5 to-[#C8102E]/10 border border-[#C8102E]/20 hover:border-[#C8102E]/40 hover:shadow-lg'">
                                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-3 shadow-md group-hover:shadow-lg transition-shadow">
                                        <i class="fas fa-book-open text-[#C8102E] text-2xl"></i>
                                    </div>
                                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">Reading</p>
                                    <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">60 min</p>
                                </a>
                            </div>

                            <!-- Writing Card -->
                            <div class="group relative">
                                <a href="{{ route('student.writing.index') }}" 
                                   class="block rounded-xl p-6 transition-all duration-300 hover:-translate-y-1"
                                   :class="darkMode ? 'glass border border-[#C8102E]/20 hover:border-[#C8102E]/40' : 'bg-gradient-to-br from-[#C8102E]/5 to-[#C8102E]/10 border border-[#C8102E]/20 hover:border-[#C8102E]/40 hover:shadow-lg'">
                                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-3 shadow-md group-hover:shadow-lg transition-shadow">
                                        <i class="fas fa-pen-fancy text-[#C8102E] text-2xl"></i>
                                    </div>
                                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">Writing</p>
                                    <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">60 min</p>
                                </a>
                            </div>

                            <!-- Speaking Card -->
                            <div class="group relative">
                                <a href="{{ route('student.speaking.index') }}" 
                                   class="block rounded-xl p-6 transition-all duration-300 hover:-translate-y-1"
                                   :class="darkMode ? 'glass border border-[#C8102E]/20 hover:border-[#C8102E]/40' : 'bg-gradient-to-br from-[#C8102E]/5 to-[#C8102E]/10 border border-[#C8102E]/20 hover:border-[#C8102E]/40 hover:shadow-lg'">
                                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-3 shadow-md group-hover:shadow-lg transition-shadow">
                                        <i class="fas fa-microphone text-[#C8102E] text-2xl"></i>
                                    </div>
                                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">Speaking</p>
                                    <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">15 min</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Chart -->
                    <div class="rounded-2xl shadow-lg p-6" 
                         :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-100'">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                <i class="fas fa-chart-line text-[#C8102E] mr-2"></i>
                                Your Progress
                            </h2>
                            <select class="text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#C8102E]"
                                    :class="darkMode ? 'glass bg-transparent text-white' : 'bg-gray-50 border border-gray-200 text-gray-800'">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>All time</option>
                            </select>
                        </div>
                        
                        <!-- Progress Bars for Each Section -->
                        <div class="space-y-4">
                            @foreach($sectionPerformance as $section)
                                @if($section['attempts_count'] > 0)
                                    <div class="group">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $sectionIcons = [
                                                        'listening' => 'fa-headphones',
                                                        'reading' => 'fa-book-open',
                                                        'writing' => 'fa-pen-fancy',
                                                        'speaking' => 'fa-microphone'
                                                    ];
                                                @endphp
                                                <i class="fas {{ $sectionIcons[$section['name']] ?? 'fa-question' }} text-[#C8102E]"></i>
                                                <span class="font-medium capitalize" :class="darkMode ? 'text-white' : 'text-gray-800'">{{ $section['name'] }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-bold text-[#C8102E]">{{ number_format($section['average_score'], 1) }}</span>
                                                <span class="text-xs ml-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">/ 9.0</span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <div class="w-full h-3 rounded-full overflow-hidden" :class="darkMode ? 'bg-gray-700' : 'bg-gray-100'">
                                                <div class="h-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full transition-all duration-1000"
                                                     style="width: {{ ($section['average_score'] / 9) * 100 }}%"></div>
                                            </div>
                                            <div class="flex justify-between mt-1">
                                                <span class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">{{ $section['attempts_count'] }} attempts</span>
                                                <span class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">Best: {{ number_format($section['best_score'], 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if($sectionPerformance->where('attempts_count', '>', 0)->isEmpty())
                                <div class="text-center py-12">
                                    <i class="fas fa-chart-area text-6xl mb-4" :class="darkMode ? 'text-gray-600' : 'text-gray-300'"></i>
                                    <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No data available yet</p>
                                    <p class="text-sm mt-2" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">Complete your first test to see your progress</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Activity Timeline -->
                    <div class="rounded-2xl shadow-lg p-6" 
                         :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-100'">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                <i class="fas fa-history text-[#C8102E] mr-2"></i>
                                Recent Activity
                            </h2>
                            <a href="{{ route('student.results') }}" class="text-[#C8102E] hover:text-[#A00E27] text-sm">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        
                        @if($recentAttempts->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($recentAttempts as $attempt)
                                    <a href="{{ route('student.results.show', $attempt) }}" 
                                       class="block group">
                                        <div class="rounded-xl p-4 transition-all duration-300"
                                             :class="darkMode ? 'glass hover:bg-white/5 hover:border-[#C8102E]/30 border border-transparent' : 'bg-gray-50 hover:bg-[#C8102E]/5 hover:border-[#C8102E]/30 border border-transparent'">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 rounded-xl bg-[#C8102E] flex items-center justify-center shadow-md">
                                                        <i class="fas {{ $icons[$attempt->testSet->section->name] ?? 'fa-question' }} text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium group-hover:text-[#C8102E] transition-colors"
                                                            :class="darkMode ? 'text-white' : 'text-gray-800'">
                                                            {{ $attempt->testSet->title }}
                                                        </h4>
                                                        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                            {{ $attempt->created_at->diffForHumans() }} • {{ $attempt->created_at->format('M d, Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    @if($attempt->band_score)
                                                        <p class="text-2xl font-bold text-[#C8102E]">{{ number_format($attempt->band_score, 1) }}</p>
                                                        <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score</p>
                                                    @else
                                                        <span class="px-3 py-1 rounded-full text-xs"
                                                              :class="darkMode ? 'glass border border-yellow-500/30 text-yellow-400' : 'bg-yellow-100 text-yellow-800 border border-yellow-200'">
                                                            <i class="fas fa-clock mr-1"></i>Pending
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-inbox text-6xl mb-4" :class="darkMode ? 'text-gray-600' : 'text-gray-300'"></i>
                                <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No recent activity</p>
                                <a href="{{ route('student.index') }}" class="inline-flex items-center mt-4 text-[#C8102E] hover:text-[#A00E27]">
                                    Start your first test <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Sidebar Content -->
                <div class="space-y-6">
                    <!-- Achievement Showcase -->
                    <div class="rounded-2xl shadow-lg p-6" 
                         :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-100'">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                <i class="fas fa-trophy text-[#C8102E] mr-2"></i>
                                Achievements
                            </h3>
                            <button onclick="openAchievementsModal()" class="text-[#C8102E] hover:text-[#A00E27]">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        
                        @if($recentAchievements->isNotEmpty())
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($recentAchievements->take(6) as $achievement)
                                    <div class="group relative">
                                        <div class="w-full aspect-square rounded-xl flex items-center justify-center transition-all cursor-pointer"
                                             :class="darkMode ? 'glass border border-[#C8102E]/20 hover:bg-[#C8102E]/10' : 'bg-gradient-to-br from-[#C8102E]/10 to-[#C8102E]/5 border border-[#C8102E]/20 hover:from-[#C8102E]/20 hover:to-[#C8102E]/10'"
                                             onclick="showAchievementDetails({{ $achievement->badge->id }})">
                                            <i class="{{ $achievement->badge->icon }} text-[#C8102E] text-2xl"></i>
                                        </div>
                                        @if(!$achievement->is_seen)
                                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-[#C8102E] rounded-full animate-pulse"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                    <span class="text-[#C8102E] font-bold">{{ auth()->user()->achievement_points ?? 0 }}</span> Total Points
                                </p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-medal text-4xl mb-3" :class="darkMode ? 'text-gray-600' : 'text-gray-300'"></i>
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Complete tests to earn achievements!</p>
                            </div>
                        @endif
                    </div>

                    <!-- Leaderboard -->
                    <div class="rounded-2xl shadow-lg p-6" 
                         :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-100'">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                <i class="fas fa-crown text-[#C8102E] mr-2"></i>
                                Leaderboard
                            </h3>
                            <select onchange="changeLeaderboardPeriod(this.value)" 
                                    class="text-xs rounded px-2 py-1 focus:outline-none"
                                    :class="darkMode ? 'glass bg-transparent text-white' : 'bg-gray-50 border border-gray-200 text-gray-800'">
                                <option value="weekly">This Week</option>
                                <option value="monthly">This Month</option>
                                <option value="all_time">All Time</option>
                            </select>
                        </div>
                        
                        <div id="leaderboard-content">
                            @if($leaderboard->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach($leaderboard->take(5) as $entry)
                                        <div class="flex items-center space-x-3 {{ $entry->user_id === auth()->id() ? 'rounded-lg p-2' : '' }}"
                                             :class="darkMode ? '{{ $entry->user_id === auth()->id() ? 'glass border border-[#C8102E]/30' : '' }}' : '{{ $entry->user_id === auth()->id() ? 'bg-[#C8102E]/5 border border-[#C8102E]/20' : '' }}'">
                                            <div class="w-8 h-8 rounded-lg {{ $loop->iteration === 1 ? 'bg-[#C8102E]' : '' }} 
                                                flex items-center justify-center text-white font-bold text-sm"
                                                :class="darkMode ? '{{ $loop->iteration > 1 ? 'bg-gray-700' : '' }}' : '{{ $loop->iteration > 1 ? 'bg-gray-200 text-gray-700' : '' }}'">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-sm" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                                    {{ $entry->user_id === auth()->id() ? 'You' : Str::limit($entry->user->name, 15) }}
                                                </p>
                                                <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ $entry->total_points }} pts</p>
                                            </div>
                                            @if($loop->iteration <= 3)
                                                <i class="fas fa-trophy text-{{ $loop->iteration === 1 ? '[#C8102E]' : 'gray-400' }}"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if(!$userInLeaderboard)
                                        <div class="pt-3 border-t" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                            <p class="text-xs text-center" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                You're not in top 5. Keep practicing!
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <i class="fas fa-users text-4xl mb-3" :class="darkMode ? 'text-gray-600' : 'text-gray-300'"></i>
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No leaderboard data yet</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Referral Rewards Widget -->
                    <div class="bg-gradient-to-br from-[#C8102E] to-[#A00E27] rounded-2xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-white">
                                <i class="fas fa-gift text-white mr-2"></i>
                                Earn Rewards
                            </h3>
                            <a href="{{ route('student.referrals.index') }}" class="text-white hover:text-white/80">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        @if(auth()->user()->referral_balance > 0)
                            <div class="text-center mb-4">
                                <p class="text-3xl font-bold text-white">৳{{ number_format(auth()->user()->referral_balance, 0) }}</p>
                                <p class="text-xs text-white/80">Available Balance</p>
                            </div>
                        @endif
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-white/90">Total Referrals</span>
                                <span class="text-white font-bold">{{ auth()->user()->total_referrals }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-white/90">Successful</span>
                                <span class="text-white font-bold">{{ auth()->user()->successful_referrals }}</span>
                            </div>
                        </div>
                        
                        <button onclick="copyReferralCode()" class="w-full mt-4 px-4 py-2 bg-white text-[#C8102E] rounded-lg hover:bg-gray-100 transition-all text-sm font-medium">
                            <i class="fas fa-copy mr-2"></i>Copy Referral Code
                        </button>
                        
                        <input type="hidden" id="referral-code" value="{{ auth()->user()->referral_code }}">
                    </div>

                    <!-- Study Tips -->
                    <div class="rounded-2xl shadow-lg p-6" 
                         :class="darkMode ? 'glass-dark border border-white/10' : 'bg-gradient-to-br from-gray-50 to-white border border-gray-100'">
                        <h3 class="text-lg font-bold mb-3" :class="darkMode ? 'text-white' : 'text-gray-800'">
                            <i class="fas fa-lightbulb text-[#C8102E] mr-2"></i>
                            Pro Tip
                        </h3>
                        <p class="text-sm leading-relaxed" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                            @php
                                $tips = [
                                    'Focus on your weakest section for maximum improvement!',
                                    'Practice speaking with yourself in the mirror to build confidence.',
                                    'Read diverse topics to expand your vocabulary naturally.',
                                    'Time management is key - practice with a timer always.',
                                    'Listen to various English accents to prepare for the test.',
                                    'Write at least 250 words daily to improve your writing skills.',
                                    'Record yourself speaking and analyze your pronunciation.',
                                    'Learn 5 new words every day and use them in sentences.',
                                    'Take mock tests regularly to track your progress.',
                                    'Stay consistent - even 30 minutes daily makes a difference!'
                                ];
                            @endphp
                            {{ $tips[array_rand($tips)] }}
                        </p>
                        <button class="mt-4 text-xs text-[#C8102E] hover:text-[#A00E27]">
                            Get more tips <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>

                    <!-- Coming Soon -->
                    <div class="rounded-2xl shadow-lg p-6" 
                         :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-100'">
                        <h3 class="text-lg font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-800'">
                            <i class="fas fa-rocket text-[#C8102E] mr-2"></i>
                            Coming Soon
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     :class="darkMode ? 'bg-gray-700' : 'bg-gray-100'">
                                    <i class="fas fa-users" :class="darkMode ? 'text-gray-400' : 'text-gray-600'"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm" :class="darkMode ? 'text-white' : 'text-gray-800'">Study Groups</p>
                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Connect with peers</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     :class="darkMode ? 'bg-gray-700' : 'bg-gray-100'">
                                    <i class="fas fa-robot" :class="darkMode ? 'text-gray-400' : 'text-gray-600'"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm" :class="darkMode ? 'text-white' : 'text-gray-800'">AI Tutor</p>
                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Personalized guidance</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     :class="darkMode ? 'bg-gray-700' : 'bg-gray-100'">
                                    <i class="fas fa-calendar-check" :class="darkMode ? 'text-gray-400' : 'text-gray-600'"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm" :class="darkMode ? 'text-white' : 'text-gray-800'">Study Planner</p>
                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Smart scheduling</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modals -->
    <!-- Goal Setting Modal -->
    <div id="goalModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-data="{ open: false }">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeGoalModal()"></div>
            
            <div class="relative rounded-2xl shadow-2xl w-full max-w-md p-6 lg:p-8"
                 :class="darkMode ? 'glass-dark' : 'bg-white'">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                        <i class="fas fa-bullseye text-[#C8102E] mr-2"></i>
                        Set Your IELTS Goal
                    </h3>
                    <button onclick="closeGoalModal()" class="hover:text-gray-600" :class="darkMode ? 'text-gray-400' : 'text-gray-400'">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form action="{{ route('student.goals.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Target Band Score</label>
                            <select name="target_band_score" class="w-full rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C8102E]"
                                    :class="darkMode ? 'glass bg-transparent text-white' : 'bg-gray-50 border border-gray-200 text-gray-800'" required>
                                <option value="">Select your target</option>
                                @for($i = 4.0; $i <= 9.0; $i += 0.5)
                                    <option value="{{ $i }}">Band {{ number_format($i, 1) }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Target Date</label>
                            <input type="date" 
                                   name="target_date" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C8102E]"
                                   :class="darkMode ? 'glass bg-transparent text-white' : 'bg-gray-50 border border-gray-200 text-gray-800'" 
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Your Motivation</label>
                            <textarea name="study_reason" 
                                      rows="3" 
                                      class="w-full rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C8102E] resize-none"
                                      :class="darkMode ? 'glass bg-transparent text-white' : 'bg-gray-50 border border-gray-200 text-gray-800'"
                                      placeholder="Why are you taking IELTS? (e.g., Study abroad, Immigration, Career)"></textarea>
                        </div>
                        
                        <div class="flex gap-3 pt-4">
                            <button type="button" 
                                    onclick="closeGoalModal()" 
                                    class="flex-1 rounded-lg py-3 transition-all"
                                    :class="darkMode ? 'glass text-white hover:bg-white/10' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 rounded-lg py-3 bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all">
                                Set Goal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Achievements Modal -->
    <div id="achievementsModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeAchievementsModal()"></div>
            
            <div class="relative rounded-2xl w-full max-w-4xl p-6 lg:p-8 max-h-[80vh] overflow-hidden"
                 :class="darkMode ? 'glass-dark' : 'bg-white shadow-2xl'">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                        <i class="fas fa-trophy text-[#C8102E] mr-2"></i>
                        Your Achievements
                    </h3>
                    <button onclick="closeAchievementsModal()" :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-gray-800'">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="overflow-y-auto max-h-[60vh] pr-2">
                    @if($allBadges ?? false)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($allBadges as $badge)
                                @php
                                    $earned = ($userAchievements ?? collect())->contains('badge_id', $badge->id);
                                @endphp
                                <div class="rounded-xl p-4 text-center cursor-pointer transition-all duration-300" 
                                     :class="darkMode ? 'glass {{ $earned ? 'border-[#C8102E]/50 hover:border-[#C8102E]/70' : 'opacity-50 hover:opacity-70' }}' : 'border {{ $earned ? 'border-[#C8102E]/50 hover:border-[#C8102E]/70 bg-white' : 'border-gray-200 bg-gray-50 opacity-50 hover:opacity-70' }}'" 
                                     onclick="showAchievementDetails({{ $badge->id }})">
                                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3"
                                         :class="darkMode ? 'bg-gray-700/50' : 'bg-gray-100'"
                                         style="{{ $earned ? 'box-shadow: 0 0 20px rgba(200, 16, 46, 0.3)' : '' }}">
                                        <i class="{{ $badge->icon }} {{ $earned ? 'text-[#C8102E]' : '' }} text-3xl"
                                           :class="!{{ $earned ? 'true' : 'false' }} && (darkMode ? 'text-gray-500' : 'text-gray-400')"></i>
                                    </div>
                                    <h4 class="font-semibold text-sm" :class="darkMode ? 'text-white' : 'text-gray-800'">{{ $badge->name }}</h4>
                                    <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ $badge->points }} pts</p>
                                    <p class="text-xs mt-2">
                                        @if($earned)
                                            <span class="text-[#C8102E]">
                                                <i class="fas fa-check-circle"></i> Earned
                                            </span>
                                        @else
                                            <span :class="darkMode ? 'text-gray-500' : 'text-gray-400'">
                                                <i class="fas fa-lock"></i> Locked
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-trophy text-6xl mb-4" :class="darkMode ? 'text-gray-600' : 'text-gray-300'"></i>
                            <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No achievements available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Copy Referral Code Function
        function copyReferralCode() {
            const code = document.getElementById('referral-code').value;
            const button = document.querySelector('button[onclick="copyReferralCode()"]');
            const originalHTML = button.innerHTML;
            
            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(code).then(() => {
                    // Success
                    button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                    button.classList.add('bg-green-600', 'text-white');
                    button.classList.remove('bg-white', 'text-[#C8102E]');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.classList.remove('bg-green-600', 'text-white');
                        button.classList.add('bg-white', 'text-[#C8102E]');
                    }, 2000);
                }).catch(() => {
                    // Fallback
                    copyUsingFallback();
                });
            } else {
                // Use fallback for older browsers
                copyUsingFallback();
            }
            
            function copyUsingFallback() {
                const textArea = document.createElement('textarea');
                textArea.value = code;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                
                try {
                    document.execCommand('copy');
                    button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                    alert('Referral code: ' + code);
                }
                
                document.body.removeChild(textArea);
            }
        }
        
        // Modal Functions
        function openGoalModal() {
            document.getElementById('goalModal').classList.remove('hidden');
        }
        
        function closeGoalModal() {
            document.getElementById('goalModal').classList.add('hidden');
        }
        
        function openAchievementsModal() {
            document.getElementById('achievementsModal').classList.remove('hidden');
            // Mark achievements as seen
            fetch('{{ route('student.achievements.mark-seen') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });
        }
        
        function closeAchievementsModal() {
            document.getElementById('achievementsModal').classList.add('hidden');
        }
        
        function showAchievementDetails(badgeId) {
            fetch(`/student/achievements/${badgeId}`)
                .then(response => response.json())
                .then(data => {
                    // You can create a nice tooltip or modal here
                    const message = data.earned 
                        ? `${data.badge.description}\n\nEarned on: ${data.earned_at}` 
                        : `${data.badge.description}\n\nKeep working to unlock this achievement!`;
                    
                    // For now, using alert, but you can make a nice modal
                    alert(message);
                });
        }
        
        function changeLeaderboardPeriod(period) {
            fetch(`{{ route('student.leaderboard') }}/${period}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('leaderboard-content').innerHTML = html;
                });
        }
        
        // Close modals on outside click
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
                closeGoalModal();
                closeAchievementsModal();
            }
        }
    </script>
    @endpush
    <!-- Announcement Popup Component -->
    @include('components.announcement-popup')
</x-student-layout>
