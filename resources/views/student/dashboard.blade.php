{{-- resources/views/student/dashboard.blade.php --}}
<x-student-layout>
    <x-slot:title>Dashboard - IELTS Master Pro</x-slot>
    
    <x-slot:header>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold gradient-text">
                    Welcome back, {{ auth()->user()->name }}! 👋
                </h3>
                <p class="text-gray-600 mt-1">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <!-- Daily Motivation -->
            <div class="hidden lg:flex items-center bg-gradient-to-r from-red-50 to-orange-50 px-6 py-3 rounded-xl border border-red-200">
                <i class="fas fa-quote-left text-red-400 mr-3"></i>
                <div>
                    <p class="text-sm font-medium text-gray-700">"Success is the sum of small efforts repeated daily"</p>
                    <p class="text-xs text-gray-500 mt-0.5">Keep pushing forward! 🎯</p>
                </div>
            </div>
        </div>
    </x-slot>
    
    <!-- Dashboard Content -->
    <div class="p-6">
        <!-- Achievement Alert (if new achievements) -->
        @if($recentAchievements && $recentAchievements->where('is_seen', false)->count() > 0)
            <div class="mb-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-1">
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center animate-bounce">
                                <i class="fas fa-trophy text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900">Congratulations! 🎉</h4>
                                <p class="text-sm text-gray-600">You've unlocked {{ $recentAchievements->where('is_seen', false)->count() }} new achievements!</p>
                            </div>
                        </div>
                        <button onclick="viewAchievements()" class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg font-medium hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            View Achievements
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Goal Setting Card (if no goal) -->
        @if(!$userGoal)
            <div class="mb-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-1">
                <div class="bg-white rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bullseye text-purple-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Set Your IELTS Goal</h4>
                                <p class="text-sm text-gray-600 mt-1">Define your target score and track your progress</p>
                            </div>
                        </div>
                        <button onclick="setGoalModal()" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg font-medium hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Set Goal Now
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Study Streak -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-100 to-red-100 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center shadow-lg pulse-glow">
                            <i class="fas fa-fire text-white text-xl"></i>
                        </div>
                        @if(auth()->user()->study_streak_days >= 7)
                            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full font-medium animate-pulse">On Fire!</span>
                        @endif
                    </div>
                    <h3 class="text-3xl font-bold gradient-text">{{ auth()->user()->study_streak_days ?? 0 }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Day Streak</p>
                </div>
            </div>

            <!-- Tests This Month -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-alt text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ auth()->user()->tests_taken_this_month }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Tests This Month</p>
                    <div class="mt-2">
                        @php
                            $testLimit = auth()->user()->getFeatureLimit('mock_tests_per_month');
                            $percentage = $testLimit === 'unlimited' ? 0 : (auth()->user()->tests_taken_this_month / $testLimit) * 100;
                        @endphp
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-gray-500">Usage</span>
                            <span class="font-medium">{{ $testLimit === 'unlimited' ? 'Unlimited' : auth()->user()->tests_taken_this_month . '/' . $testLimit }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-1.5 rounded-full transition-all duration-500" 
                                 style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        @if($stats['average_band_score'] && $stats['average_band_score'] >= 7)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Excellent!</span>
                        @endif
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900">
                        {{ $stats['average_band_score'] ? number_format($stats['average_band_score'], 1) : '-' }}
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Average Band</p>
                </div>
            </div>

            <!-- Achievement Points -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ auth()->user()->achievement_points ?? 0 }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Points Earned</p>
                </div>
            </div>

            <!-- Subscription Status -->
            <div class="bg-gradient-to-br from-red-500 to-orange-500 rounded-xl shadow-sm p-6 text-white hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full opacity-10 -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                            <i class="fas fa-crown text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold">{{ ucfirst(auth()->user()->subscription_status) }}</h3>
                    @if(auth()->user()->activeSubscription())
                        <p class="text-sm text-red-100 mt-1">{{ auth()->user()->activeSubscription()->days_remaining }} days left</p>
                    @else
                        <p class="text-sm text-red-100 mt-1">Upgrade for more</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-rocket text-red-500 mr-2"></i>
                        Quick Actions
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button onclick="window.location='{{ route('student.listening.index') }}'" 
                                class="group p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-headphones text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Listening Test</p>
                        </button>
                        
                        <button onclick="window.location='{{ route('student.reading.index') }}'" 
                                class="group p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-book-open text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Reading Test</p>
                        </button>
                        
                        <button onclick="window.location='{{ route('student.writing.index') }}'" 
                                class="group p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl hover:from-yellow-100 hover:to-yellow-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-pen text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Writing Test</p>
                        </button>
                        
                        <button onclick="window.location='{{ route('student.speaking.index') }}'" 
                                class="group p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-microphone text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700">Speaking Test</p>
                        </button>
                    </div>
                </div>

                <!-- Performance Overview -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-chart-bar text-red-500 mr-2"></i>
                            Performance Overview
                        </h2>
                        <select class="text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                            <option>Last 30 Days</option>
                            <option>Last 7 Days</option>
                            <option>All Time</option>
                        </select>
                    </div>
                    
                    @if($sectionPerformance->where('attempts_count', '>', 0)->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($sectionPerformance as $performance)
                                @if($performance['attempts_count'] > 0)
                                    <div class="group hover:bg-gray-50 p-4 rounded-xl transition-all duration-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                @php
                                                    $sectionIcons = [
                                                        'listening' => ['icon' => 'fa-headphones', 'color' => 'blue'],
                                                        'reading' => ['icon' => 'fa-book-open', 'color' => 'green'],
                                                        'writing' => ['icon' => 'fa-pen', 'color' => 'yellow'],
                                                        'speaking' => ['icon' => 'fa-microphone', 'color' => 'purple']
                                                    ];
                                                    $section = $sectionIcons[$performance['name']] ?? ['icon' => 'fa-question', 'color' => 'gray'];
                                                @endphp
                                                <div class="w-12 h-12 bg-{{ $section['color'] }}-100 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas {{ $section['icon'] }} text-{{ $section['color'] }}-600 text-lg"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 capitalize">{{ $performance['name'] }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $performance['attempts_count'] }} tests completed</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-3xl font-bold gradient-text">{{ number_format($performance['average_score'], 1) }}</p>
                                                <p class="text-xs text-gray-500">Best: {{ number_format($performance['best_score'], 1) }}</p>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <div class="w-full bg-gray-200 rounded-full h-3">
                                                <div class="h-3 rounded-full transition-all duration-1000 bg-gradient-to-r from-{{ $section['color'] }}-500 to-{{ $section['color'] }}-600"
                                                     style="width: {{ min(($performance['average_score'] / 9) * 100, 100) }}%">
                                                </div>
                                            </div>
                                            <span class="absolute -top-1 text-xs font-medium text-gray-700"
                                                  style="left: {{ min(($performance['average_score'] / 9) * 100, 95) }}%">
                                                {{ number_format($performance['average_score'], 1) }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-bar text-4xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 mb-4">No test data available yet</p>
                            <a href="{{ route('student.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-lg font-medium hover:from-red-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-play-circle mr-2"></i>
                                Start Your First Test
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-history text-red-500 mr-2"></i>
                            Recent Activity
                        </h2>
                        <a href="{{ route('student.results') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                            View All →
                        </a>
                    </div>

                    @if($recentAttempts->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($recentAttempts as $attempt)
                                <div class="group bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 hover:from-red-50 hover:to-orange-50 transition-all duration-300 cursor-pointer border border-gray-200 hover:border-red-200"
                                     onclick="window.location='{{ route('student.results.show', $attempt) }}'">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            @php
                                                $sectionInfo = [
                                                    'listening' => ['icon' => 'fa-headphones', 'gradient' => 'from-blue-500 to-indigo-500'],
                                                    'reading' => ['icon' => 'fa-book-open', 'gradient' => 'from-green-500 to-emerald-500'],
                                                    'writing' => ['icon' => 'fa-pen', 'gradient' => 'from-yellow-500 to-orange-500'],
                                                    'speaking' => ['icon' => 'fa-microphone', 'gradient' => 'from-purple-500 to-pink-500']
                                                ];
                                                $section = $sectionInfo[$attempt->testSet->section->name] ?? ['icon' => 'fa-question', 'gradient' => 'from-gray-500 to-gray-600'];
                                            @endphp
                                            <div class="w-14 h-14 bg-gradient-to-br {{ $section['gradient'] }} rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                                                <i class="fas {{ $section['icon'] }} text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors">
                                                    {{ $attempt->testSet->title }}
                                                </h4>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-sm text-gray-600">
                                                        <i class="far fa-clock mr-1"></i>
                                                        {{ $attempt->created_at->diffForHumans() }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <i class="far fa-calendar mr-1"></i>
                                                        {{ $attempt->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($attempt->band_score)
                                                <div class="text-3xl font-bold gradient-text">{{ number_format($attempt->band_score, 1) }}</div>
                                                <p class="text-xs text-gray-600">Band Score</p>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Study Goal -->
                @if($userGoal)
                    <div class="bg-gradient-to-br from-red-500 to-orange-500 rounded-xl p-6 text-white shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg flex items-center">
                                <i class="fas fa-bullseye mr-2"></i>
                                Your IELTS Goal
                            </h3>
                            <button onclick="editGoalModal()" class="text-white/80 hover:text-white">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-sm text-red-100">Target Band Score</span>
                                    <span class="text-3xl font-bold">{{ $userGoal->target_band_score }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-red-100">Days Remaining</span>
                                    <span class="font-bold">{{ $userGoal->days_remaining }} days</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-red-100">Progress</span>
                                    <span class="font-bold">{{ round($userGoal->progress_percentage) }}%</span>
                                </div>
                                <div class="w-full bg-red-700 rounded-full h-3">
                                    <div class="bg-white h-3 rounded-full transition-all duration-1000"
                                         style="width: {{ $userGoal->progress_percentage }}%"></div>
                                </div>
                            </div>
                            @if($userGoal->study_reason)
                                <div class="pt-2 border-t border-red-400">
                                    <p class="text-sm text-red-100">Motivation:</p>
                                    <p class="text-sm font-medium mt-1">{{ $userGoal->study_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Leaderboard -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                            Leaderboard
                        </h2>
                        <select onchange="changeLeaderboardPeriod(this.value)" 
                                class="text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                            <option value="weekly">This Week</option>
                            <option value="monthly">This Month</option>
                            <option value="all_time">All Time</option>
                        </select>
                    </div>
                    
                    <div id="leaderboard-content">
                        @include('partials.leaderboard-content', ['leaderboard' => $leaderboard, 'userInLeaderboard' => $userInLeaderboard])
                    </div>
                </div>

                <!-- Achievement Progress -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-medal text-red-500 mr-2"></i>
                            Next Achievements
                        </h2>
                        <button onclick="viewAchievements()" class="text-sm text-red-600 hover:text-red-700">
                            View All
                        </button>
                    </div>
                    
                    @if($progressToNext->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($progressToNext->take(3) as $progress)
                                <div class="group hover:bg-gray-50 p-3 rounded-lg transition-all duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-lg bg-{{ $progress['badge']->color }}-100 flex items-center justify-center mr-3">
                                                <i class="{{ $progress['badge']->icon }} text-{{ $progress['badge']->color }}-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $progress['badge']->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $progress['badge']->points }} pts</p>
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $progress['current'] }}/{{ $progress['target'] }}</span>
                                    </div>
                                    <div class="relative">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-{{ $progress['badge']->color }}-500 to-{{ $progress['badge']->color }}-600 h-2 rounded-full transition-all duration-500"
                                                 style="width: {{ $progress['percentage'] }}%"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">{{ $progress['badge']->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-trophy text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Keep practicing to unlock achievements!</p>
                        </div>
                    @endif
                </div>

                <!-- Study Tips -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl p-6 text-white shadow-xl">
                    <h3 class="font-bold text-lg mb-4 flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Daily Tip
                    </h3>
                    <p class="text-sm opacity-90 leading-relaxed">
                        Focus on your weakest section today. Consistent practice in challenging areas leads to the biggest improvements!
                    </p>
                    <button class="mt-4 text-sm font-medium text-white/80 hover:text-white transition-colors">
                        Get more tips →
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Achievements Modal -->
    <div id="achievementsModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-xl rounded-xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold gradient-text">
                        <i class="fas fa-trophy text-yellow-500 mr-2"></i>Your Achievements
                    </h3>
                    <button onclick="closeAchievementsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-h-96 overflow-y-auto p-2">
                    @foreach($allBadges as $badge)
                        @php
                            $earned = $userAchievements->contains('badge_id', $badge->id);
                            $achievement = $earned ? $userAchievements->firstWhere('badge_id', $badge->id) : null;
                        @endphp
                        <div class="text-center p-4 rounded-xl border-2 transition-all duration-300 cursor-pointer transform hover:scale-105
                            {{ $earned ? 'border-' . $badge->color . '-500 bg-' . $badge->color . '-50 shadow-lg' : 'border-gray-200 bg-gray-50 opacity-60' }}"
                            onclick="showBadgeDetails({{ $badge->id }})">
                            <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-{{ $badge->color }}-100 flex items-center justify-center
                                {{ $earned ? 'ring-4 ring-' . $badge->color . '-500 ring-opacity-50' : '' }}">
                                <i class="{{ $badge->icon }} text-{{ $badge->color }}-600 text-3xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">{{ $badge->name }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $badge->points }} pts</p>
                            @if($earned)
                                <p class="text-xs text-{{ $badge->color }}-600 mt-2">
                                    <i class="fas fa-check-circle"></i> Earned
                                </p>
                            @else
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-lock"></i> Locked
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Goal Setting Modal -->
    <div id="goalModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-xl rounded-xl bg-white">
            <form action="{{ route('student.goals.store') }}" method="POST">
                @csrf
                <div class="mt-3">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold gradient-text">Set Your IELTS Goal</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target Band Score</label>
                            <select name="target_band_score" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
                                <option value="">Select target score</option>
                                @for($i = 4.0; $i <= 9.0; $i += 0.5)
                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target Date</label>
                            <input type="date" name="target_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                   class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Why are you taking IELTS?</label>
                            <textarea name="study_reason" rows="3" 
                                      class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                                      placeholder="e.g., Study abroad, Immigration, Career advancement..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeGoalModal()" 
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-lg font-medium hover:from-red-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Set Goal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Confetti animation for achievements
        function celebrateAchievement() {
            // Add confetti library and trigger
        }

        // Leaderboard functionality
        function changeLeaderboardPeriod(period) {
            fetch(`{{ route('student.leaderboard') }}/${period}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('leaderboard-content').innerHTML = html;
                });
        }

        // Achievement functionality
        function viewAchievements() {
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

        function showBadgeDetails(badgeId) {
            fetch(`/student/achievements/${badgeId}`)
                .then(response => response.json())
                .then(data => {
                    // Create a nice modal or tooltip with badge details
                    const details = data.earned 
                        ? `${data.badge.description}\n\nEarned on: ${data.earned_at}` 
                        : `${data.badge.description}\n\nKeep working to unlock this achievement!`;
                    alert(details);
                });
        }

        // Goal functionality
        function setGoalModal() {
            document.getElementById('goalModal').classList.remove('hidden');
        }

        function closeGoalModal() {
            document.getElementById('goalModal').classList.add('hidden');
        }

        function editGoalModal() {
            // Implement edit goal functionality
            setGoalModal();
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const achievementsModal = document.getElementById('achievementsModal');
            const goalModal = document.getElementById('goalModal');
            
            if (event.target == achievementsModal) {
                achievementsModal.classList.add('hidden');
            }
            if (event.target == goalModal) {
                goalModal.classList.add('hidden');
            }
        }

        // Auto-refresh leaderboard every 5 minutes
        setInterval(() => {
            const select = document.querySelector('select[onchange*="changeLeaderboardPeriod"]');
            if (select) {
                changeLeaderboardPeriod(select.value);
            }
        }, 300000);

        // Add floating animation to achievement cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.hover\\:-translate-y-1');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
    @endpush
</x-student-layout>