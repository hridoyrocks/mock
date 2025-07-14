{{-- resources/views/student/dashboard.blade.php --}}
<x-student-layout>
    <x-slot:title>Dashboard</x-slot>

    <!-- Hero Section with Journey Progress -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-7xl mx-auto">
                <!-- Welcome Banner -->
                <div class="glass rounded-2xl p-8 lg:p-12 mb-8">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                        <div class="flex-1 text-center lg:text-left">
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                                Welcome back, {{ auth()->user()->name }}! 🎯
                            </h1>
                            <p class="text-gray-300 text-lg mb-6">
                                You're on fire! Keep up the great work.
                            </p>
                            
                            <!-- Quick Stats -->
                            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                                <div class="glass rounded-xl px-6 py-3 border-purple-500/30">
                                    <p class="text-gray-400 text-sm">Current Streak</p>
                                    <p class="text-2xl font-bold text-white">
                                        <i class="fas fa-fire text-orange-500 mr-1"></i>
                                        {{ auth()->user()->study_streak_days ?? 0 }} days
                                    </p>
                                </div>
                                <div class="glass rounded-xl px-6 py-3 border-blue-500/30">
                                    <p class="text-gray-400 text-sm">Average Score</p>
                                    <p class="text-2xl font-bold text-white">
                                        {{ $stats['average_band_score'] ? number_format($stats['average_band_score'], 1) : 'N/A' }}
                                    </p>
                                </div>
                                <div class="glass rounded-xl px-6 py-3 border-pink-500/30">
                                    <p class="text-gray-400 text-sm">Tests Completed</p>
                                    <p class="text-2xl font-bold text-white">
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
                                            stroke="rgba(255,255,255,0.1)" 
                                            stroke-width="8" 
                                            fill="none" />
                                    <circle cx="50%" cy="50%" r="45%" 
                                            stroke="url(#gradient)" 
                                            stroke-width="8" 
                                            fill="none"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ 2 * 3.14159 * 45 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 45 * (1 - ($userGoal ? $userGoal->progress_percentage / 100 : 0)) }}" />
                                    <defs>
                                        <linearGradient id="gradient">
                                            <stop offset="0%" stop-color="#a855f7" />
                                            <stop offset="100%" stop-color="#ec4899" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                                
                                <!-- Center Content -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    @if($userGoal)
                                        <p class="text-gray-400 text-sm">Target Band</p>
                                        <p class="text-4xl font-bold text-white">{{ $userGoal->target_band_score }}</p>
                                        <p class="text-sm text-purple-400">{{ round($userGoal->progress_percentage) }}% Complete</p>
                                    @else
                                        <button onclick="openGoalModal()" class="glass px-4 py-2 rounded-lg text-white hover:border-purple-500/50 transition-all">
                                            <i class="fas fa-plus mr-2"></i>Set Goal
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Challenge / Focus Area -->
                <div class="glass rounded-2xl p-6 mb-8 border-purple-500/30 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/20 rounded-full blur-3xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">
                                <i class="fas fa-bullseye text-purple-400 mr-2"></i>
                                Today's Focus
                            </h3>
                            <p class="text-gray-300">Based on your recent performance, we recommend focusing on:</p>
                        </div>
                        <div class="text-right">
                            @php
                                $weakestSection = $sectionPerformance->where('attempts_count', '>', 0)->sortBy('average_score')->first();
                            @endphp
                            @if($weakestSection)
                                <p class="text-2xl font-bold text-white capitalize">{{ $weakestSection['name'] }}</p>
                                <a href="{{ route('student.' . $weakestSection['name'] . '.index') }}" 
                                   class="inline-flex items-center text-purple-400 hover:text-purple-300 mt-2">
                                    Practice Now <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            @else
                                <p class="text-lg text-gray-400">Complete a test to get recommendations</p>
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
                    <div class="glass rounded-2xl p-6">
                        <h2 class="text-xl font-bold text-white mb-6">
                            <i class="fas fa-gamepad text-purple-400 mr-2"></i>
                            Start Your Practice
                        </h2>
                        
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Listening Card -->
                            <div class="group relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-violet-600 to-purple-600 rounded-xl blur opacity-50 group-hover:opacity-100 transition-opacity"></div>
                                <a href="{{ route('student.listening.index') }}" 
                                   class="relative glass rounded-xl p-6 flex flex-col items-center justify-center hover:border-violet-500/50 transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-headphones text-white text-2xl"></i>
                                    </div>
                                    <p class="text-white font-semibold">Listening</p>
                                    <p class="text-xs text-gray-400 mt-1">30 min</p>
                                </a>
                            </div>

                            <!-- Reading Card -->
                            <div class="group relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-green-600 rounded-xl blur opacity-50 group-hover:opacity-100 transition-opacity"></div>
                                <a href="{{ route('student.reading.index') }}" 
                                   class="relative glass rounded-xl p-6 flex flex-col items-center justify-center hover:border-emerald-500/50 transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-book-open text-white text-2xl"></i>
                                    </div>
                                    <p class="text-white font-semibold">Reading</p>
                                    <p class="text-xs text-gray-400 mt-1">60 min</p>
                                </a>
                            </div>

                            <!-- Writing Card -->
                            <div class="group relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-amber-600 to-orange-600 rounded-xl blur opacity-50 group-hover:opacity-100 transition-opacity"></div>
                                <a href="{{ route('student.writing.index') }}" 
                                   class="relative glass rounded-xl p-6 flex flex-col items-center justify-center hover:border-amber-500/50 transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-pen-fancy text-white text-2xl"></i>
                                    </div>
                                    <p class="text-white font-semibold">Writing</p>
                                    <p class="text-xs text-gray-400 mt-1">60 min</p>
                                </a>
                            </div>

                            <!-- Speaking Card -->
                            <div class="group relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-rose-600 to-pink-600 rounded-xl blur opacity-50 group-hover:opacity-100 transition-opacity"></div>
                                <a href="{{ route('student.speaking.index') }}" 
                                   class="relative glass rounded-xl p-6 flex flex-col items-center justify-center hover:border-rose-500/50 transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-microphone text-white text-2xl"></i>
                                    </div>
                                    <p class="text-white font-semibold">Speaking</p>
                                    <p class="text-xs text-gray-400 mt-1">15 min</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Chart -->
                    <div class="glass rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-white">
                                <i class="fas fa-chart-line text-blue-400 mr-2"></i>
                                Your Progress
                            </h2>
                            <select class="glass bg-transparent text-white text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-purple-500">
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
                                                    $sectionColors = [
                                                        'listening' => 'purple',
                                                        'reading' => 'blue',
                                                        'writing' => 'green',
                                                        'speaking' => 'pink'
                                                    ];
                                                @endphp
                                                <i class="fas {{ $sectionIcons[$section['name']] ?? 'fa-question' }} text-{{ $sectionColors[$section['name']] ?? 'gray' }}-400"></i>
                                                <span class="text-white font-medium capitalize">{{ $section['name'] }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-bold text-white">{{ number_format($section['average_score'], 1) }}</span>
                                                <span class="text-xs text-gray-400 ml-1">/ 9.0</span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-{{ $sectionColors[$section['name']] }}-500 to-{{ $sectionColors[$section['name']] }}-400 rounded-full transition-all duration-1000 hover:shadow-lg hover:shadow-{{ $sectionColors[$section['name']] }}-500/50"
                                                     style="width: {{ ($section['average_score'] / 9) * 100 }}%"></div>
                                            </div>
                                            <div class="flex justify-between mt-1">
                                                <span class="text-xs text-gray-500">{{ $section['attempts_count'] }} attempts</span>
                                                <span class="text-xs text-gray-500">Best: {{ number_format($section['best_score'], 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if($sectionPerformance->where('attempts_count', '>', 0)->isEmpty())
                                <div class="text-center py-12">
                                    <i class="fas fa-chart-area text-6xl text-gray-600 mb-4"></i>
                                    <p class="text-gray-400">No data available yet</p>
                                    <p class="text-sm text-gray-500 mt-2">Complete your first test to see your progress</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Activity Timeline -->
                    <div class="glass rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-white">
                                <i class="fas fa-history text-purple-400 mr-2"></i>
                                Recent Activity
                            </h2>
                            <a href="{{ route('student.results') }}" class="text-purple-400 hover:text-purple-300 text-sm">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        
                        @if($recentAttempts->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($recentAttempts as $attempt)
                                    <a href="{{ route('student.results.show', $attempt) }}" 
                                       class="block group">
                                        <div class="glass rounded-xl p-4 hover:border-purple-500/50 transition-all duration-300">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    @php
                                                        $sectionColors = [
                                                            'listening' => 'from-violet-500 to-purple-500',
                                                            'reading' => 'from-blue-500 to-cyan-500',
                                                            'writing' => 'from-green-500 to-emerald-500',
                                                            'speaking' => 'from-rose-500 to-pink-500'
                                                        ];
                                                        $gradientClass = $sectionColors[$attempt->testSet->section->name] ?? 'from-gray-500 to-gray-600';
                                                    @endphp
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $gradientClass }} flex items-center justify-center">
                                                        <i class="fas {{ $icons[$attempt->testSet->section->name] ?? 'fa-question' }} text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-white font-medium group-hover:text-purple-400 transition-colors">
                                                            {{ $attempt->testSet->title }}
                                                        </h4>
                                                        <p class="text-sm text-gray-400">
                                                            {{ $attempt->created_at->diffForHumans() }} • {{ $attempt->created_at->format('M d, Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    @if($attempt->band_score)
                                                        <p class="text-2xl font-bold text-white">{{ number_format($attempt->band_score, 1) }}</p>
                                                        <p class="text-xs text-gray-400">Band Score</p>
                                                    @else
                                                        <span class="glass px-3 py-1 rounded-full text-xs text-yellow-400 border-yellow-500/30">
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
                                <i class="fas fa-inbox text-6xl text-gray-600 mb-4"></i>
                                <p class="text-gray-400">No recent activity</p>
                                <a href="{{ route('student.index') }}" class="inline-flex items-center mt-4 text-purple-400 hover:text-purple-300">
                                    Start your first test <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Sidebar Content -->
                <div class="space-y-6">
                    <!-- Achievement Showcase -->
                    <div class="glass rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-white">
                                <i class="fas fa-trophy text-yellow-400 mr-2"></i>
                                Achievements
                            </h3>
                            <button onclick="openAchievementsModal()" class="text-purple-400 hover:text-purple-300">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        
                        @if($recentAchievements->isNotEmpty())
                            <div class="grid grid-cols-3 gap-3">
                                @foreach($recentAchievements->take(6) as $achievement)
                                    <div class="group relative">
                                        <div class="w-full aspect-square rounded-xl bg-gradient-to-br from-{{ $achievement->badge->color }}-500/20 to-{{ $achievement->badge->color }}-600/20 flex items-center justify-center hover:from-{{ $achievement->badge->color }}-500/30 hover:to-{{ $achievement->badge->color }}-600/30 transition-all cursor-pointer"
                                             onclick="showAchievementDetails({{ $achievement->badge->id }})">
                                            <i class="{{ $achievement->badge->icon }} text-{{ $achievement->badge->color }}-400 text-2xl"></i>
                                        </div>
                                        @if(!$achievement->is_seen)
                                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-400">
                                    <span class="text-white font-bold">{{ auth()->user()->achievement_points ?? 0 }}</span> Total Points
                                </p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-medal text-4xl text-gray-600 mb-3"></i>
                                <p class="text-gray-400 text-sm">Complete tests to earn achievements!</p>
                            </div>
                        @endif
                    </div>

                    <!-- Leaderboard -->
                    <div class="glass rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-white">
                                <i class="fas fa-crown text-yellow-400 mr-2"></i>
                                Leaderboard
                            </h3>
                            <select onchange="changeLeaderboardPeriod(this.value)" 
                                    class="glass bg-transparent text-white text-xs rounded px-2 py-1 focus:outline-none">
                                <option value="weekly">This Week</option>
                                <option value="monthly">This Month</option>
                                <option value="all_time">All Time</option>
                            </select>
                        </div>
                        
                        <div id="leaderboard-content">
                            @if($leaderboard->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach($leaderboard->take(5) as $entry)
                                        <div class="flex items-center space-x-3 {{ $entry->user_id === auth()->id() ? 'glass rounded-lg p-2 border-purple-500/50' : '' }}">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br 
                                                {{ $loop->iteration === 1 ? 'from-yellow-500 to-amber-500' : 
                                                   ($loop->iteration === 2 ? 'from-gray-400 to-gray-500' : 
                                                   ($loop->iteration === 3 ? 'from-orange-500 to-amber-600' : 'from-purple-500 to-pink-500')) }} 
                                                flex items-center justify-center text-white font-bold text-sm">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-white font-medium text-sm">
                                                    {{ $entry->user_id === auth()->id() ? 'You' : Str::limit($entry->user->name, 15) }}
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $entry->total_points }} pts</p>
                                            </div>
                                            @if($loop->iteration <= 3)
                                                <i class="fas fa-trophy text-{{ $loop->iteration === 1 ? 'yellow' : ($loop->iteration === 2 ? 'gray' : 'orange') }}-400"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if(!$userInLeaderboard)
                                        <div class="pt-3 border-t border-white/10">
                                            <p class="text-xs text-gray-400 text-center">
                                                You're not in top 5. Keep practicing!
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <i class="fas fa-users text-4xl text-gray-600 mb-3"></i>
                                    <p class="text-gray-400 text-sm">No leaderboard data yet</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Study Tips -->
                    <div class="glass rounded-2xl p-6 border-purple-500/30 bg-gradient-to-br from-purple-600/10 to-pink-600/10">
                        <h3 class="text-lg font-bold text-white mb-3">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                            Pro Tip
                        </h3>
                        <p class="text-sm text-gray-300 leading-relaxed">
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
                        <button class="mt-4 text-xs text-purple-400 hover:text-purple-300">
                            Get more tips <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>

                    <!-- Upcoming Features -->
                    <div class="glass rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">
                            <i class="fas fa-rocket text-purple-400 mr-2"></i>
                            Coming Soon
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center">
                                    <i class="fas fa-users text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium text-sm">Study Groups</p>
                                    <p class="text-xs text-gray-400">Connect with peers</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center">
                                    <i class="fas fa-robot text-green-400"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium text-sm">AI Tutor</p>
                                    <p class="text-xs text-gray-400">Personalized guidance</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-purple-400"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium text-sm">Study Planner</p>
                                    <p class="text-xs text-gray-400">Smart scheduling</p>
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
            
            <div class="relative glass rounded-2xl w-full max-w-md p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white">
                        <i class="fas fa-bullseye text-purple-400 mr-2"></i>
                        Set Your IELTS Goal
                    </h3>
                    <button onclick="closeGoalModal()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form action="{{ route('student.goals.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Target Band Score</label>
                            <select name="target_band_score" class="w-full glass bg-transparent text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                <option value="">Select your target</option>
                                @for($i = 4.0; $i <= 9.0; $i += 0.5)
                                    <option value="{{ $i }}" class="bg-gray-900">Band {{ number_format($i, 1) }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Target Date</label>
                            <input type="date" 
                                   name="target_date" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full glass bg-transparent text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Your Motivation</label>
                            <textarea name="study_reason" 
                                      rows="3" 
                                      class="w-full glass bg-transparent text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"
                                      placeholder="Why are you taking IELTS? (e.g., Study abroad, Immigration, Career)"></textarea>
                        </div>
                        
                        <div class="flex gap-3 pt-4">
                            <button type="button" 
                                    onclick="closeGoalModal()" 
                                    class="flex-1 glass rounded-lg py-3 text-white hover:border-gray-500/50 transition-all">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 rounded-lg py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
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
            
            <div class="relative glass rounded-2xl w-full max-w-4xl p-6 lg:p-8 max-h-[80vh] overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white">
                        <i class="fas fa-trophy text-yellow-400 mr-2"></i>
                        Your Achievements
                    </h3>
                    <button onclick="closeAchievementsModal()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="overflow-y-auto max-h-[60vh] pr-2">
                    @if($allBadges)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($allBadges as $badge)
                                @php
                                    $earned = $userAchievements->contains('badge_id', $badge->id);
                                @endphp
                                <div class="glass rounded-xl p-4 text-center cursor-pointer transition-all duration-300 
                                     {{ $earned ? 'border-' . $badge->color . '-500/50 hover:border-' . $badge->color . '-400/70' : 'opacity-50 hover:opacity-70' }}"
                                     onclick="showAchievementDetails({{ $badge->id }})">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-{{ $badge->color }}-500/20 to-{{ $badge->color }}-600/20 flex items-center justify-center mx-auto mb-3
                                         {{ $earned ? 'neon-' . $badge->color : '' }}">
                                        <i class="{{ $badge->icon }} text-{{ $badge->color }}-400 text-3xl"></i>
                                    </div>
                                    <h4 class="text-white font-semibold text-sm">{{ $badge->name }}</h4>
                                    <p class="text-xs text-gray-400 mt-1">{{ $badge->points }} pts</p>
                                    <p class="text-xs mt-2">
                                        @if($earned)
                                            <span class="text-{{ $badge->color }}-400">
                                                <i class="fas fa-check-circle"></i> Earned
                                            </span>
                                        @else
                                            <span class="text-gray-500">
                                                <i class="fas fa-lock"></i> Locked
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
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
        
        // Auto refresh leaderboard every 5 minutes
        setInterval(() => {
            const select = document.querySelector('select[onchange*="changeLeaderboardPeriod"]');
            if (select) {
                changeLeaderboardPeriod(select.value);
            }
        }, 300000);
        
        // Daily tips array
        const dailyTips = [
            "Focus on your weakest section for maximum improvement!",
            "Practice speaking with yourself in the mirror to build confidence.",
            "Read diverse topics to expand your vocabulary naturally.",
            "Time management is key - practice with a timer always.",
            "Listen to various English accents to prepare for the test.",
            "Write at least 250 words daily to improve your writing skills.",
            "Record yourself speaking and analyze your pronunciation.",
            "Learn 5 new words every day and use them in sentences.",
            "Take mock tests regularly to track your progress.",
            "Stay consistent - even 30 minutes daily makes a difference!"
        ];
    </script>
    @endpush
</x-student-layout>