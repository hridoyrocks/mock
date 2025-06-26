{{-- resources/views/student/dashboard.blade.php --}}
<x-student-layout>
    <x-slot:title>Student Dashboard - IELTS Practice Platform</x-slot>
    
    <x-slot:header>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h3>
               
            </div>
            <!-- Study Goal Badge -->
            @if($userGoal)
                <div class="hidden lg:flex items-center bg-indigo-50 px-4 py-2 rounded-lg">
                    <i class="fas fa-bullseye text-indigo-600 mr-2"></i>
                    <div>
                        <p class="text-xs text-gray-600">Target Band Score</p>
                        <p class="font-bold text-indigo-700">{{ $userGoal->target_band_score }}</p>
                    </div>
                </div>
            @endif
        </div>
    </x-slot>
    
    <!-- Dashboard Content -->
    <div class="p-6 space-y-6">
        <!-- Achievement Alert -->
        @if($recentAchievements && $recentAchievements->where('is_seen', false)->count() > 0)
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-trophy text-yellow-600 text-2xl mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">New Achievements Unlocked!</h4>
                            <p class="text-sm text-gray-600">You've earned {{ $recentAchievements->where('is_seen', false)->count() }} new badges</p>
                        </div>
                    </div>
                    <button onclick="viewAchievements()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        View All
                    </button>
                </div>
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Tests This Month -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="text-right">
                        @php
                            $testLimit = auth()->user()->getFeatureLimit('mock_tests_per_month');
                        @endphp
                        <p class="text-xs text-gray-500">
                            {{ $testLimit === 'unlimited' ? 'Unlimited' : 'of ' . $testLimit }}
                        </p>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->tests_taken_this_month }}</h3>
                <p class="text-sm text-gray-600 mt-1">Tests This Month</p>
            </div>

         

              <!-- AI Evaluations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-robot text-purple-600 text-lg"></i>
                    </div>
                </div>
                <h3 class="text-xl md:text-2xl font-bold text-gray-900">{{ auth()->user()->ai_evaluations_used }}</h3>
                <p class="text-xs md:text-sm text-gray-600 mt-1">AI Evaluations</p>
                <p class="text-xs text-gray-500 mt-1">
                    @if(auth()->user()->hasFeature('ai_writing_evaluation'))
                        Available
                    @else
                        <a href="{{ route('subscription.plans') }}" class="text-indigo-600 hover:text-indigo-800">Upgrade</a>
                    @endif
                </p>
            </div>

               <!-- Average Band Score -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                    @if($stats['average_band_score'] && $stats['average_band_score'] >= 7)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Excellent</span>
                    @endif
                </div>
                <h3 class="text-2xl font-bold text-gray-900">
                    {{ $stats['average_band_score'] ? number_format($stats['average_band_score'], 1) : '-' }}
                </h3>
                <p class="text-sm text-gray-600 mt-1">Average Band Score</p>
            </div>

            <!-- Achievement Points -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->achievement_points ?? 0 }}</h3>
                <p class="text-sm text-gray-600 mt-1">Achievement Points</p>
            </div>

            <!-- Subscription Status -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-sm p-6 text-white hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold">{{ ucfirst(auth()->user()->subscription_status) }}</h3>
                @if(auth()->user()->activeSubscription())
                    <p class="text-sm text-indigo-100 mt-1">{{ auth()->user()->activeSubscription()->days_remaining }} days left</p>
                @else
                    <p class="text-sm text-indigo-100 mt-1">Forever Free</p>
                @endif
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Recent Activity & Performance -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Performance Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Performance by Section</h2>
                        
                    </div>
                    
                    @if($sectionPerformance->where('attempts_count', '>', 0)->isNotEmpty())
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($sectionPerformance as $performance)
                                @if($performance['attempts_count'] > 0)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                @switch($performance['name'])
                                                    @case('listening')
                                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-headphones text-blue-600"></i>
                                                        </div>
                                                        @break
                                                    @case('reading')
                                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-book-open text-green-600"></i>
                                                        </div>
                                                        @break
                                                    @case('writing')
                                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-pen text-yellow-600"></i>
                                                        </div>
                                                        @break
                                                    @case('speaking')
                                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-microphone text-purple-600"></i>
                                                        </div>
                                                        @break
                                                @endswitch
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 capitalize">{{ $performance['name'] }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $performance['attempts_count'] }} tests</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold text-gray-900">{{ number_format($performance['average_score'], 1) }}</p>
                                                <p class="text-xs text-gray-500">Best: {{ number_format($performance['best_score'], 1) }}</p>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-500
                                                @switch($performance['name'])
                                                    @case('listening') bg-blue-500 @break
                                                    @case('reading') bg-green-500 @break
                                                    @case('writing') bg-yellow-500 @break
                                                    @case('speaking') bg-purple-500 @break
                                                @endswitch"
                                                style="width: {{ min(($performance['average_score'] / 9) * 100, 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Complete tests to see your performance</p>
                            <a href="{{ route('student.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                Start Your First Test
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Recent Activity</h2>
                        <a href="{{ route('student.results') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            View All →
                        </a>
                    </div>

                    @if($recentAttempts->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($recentAttempts as $attempt)
                                <div class="group bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-all duration-300 cursor-pointer"
                                     onclick="window.location='{{ route('student.results.show', $attempt) }}'">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-lg flex items-center justify-center
                                                @switch($attempt->testSet->section->name)
                                                    @case('listening') bg-blue-100 @break
                                                    @case('reading') bg-green-100 @break
                                                    @case('writing') bg-yellow-100 @break
                                                    @case('speaking') bg-purple-100 @break
                                                @endswitch">
                                                @switch($attempt->testSet->section->name)
                                                    @case('listening')
                                                        <i class="fas fa-headphones text-blue-600"></i>
                                                        @break
                                                    @case('reading')
                                                        <i class="fas fa-book-open text-green-600"></i>
                                                        @break
                                                    @case('writing')
                                                        <i class="fas fa-pen text-yellow-600"></i>
                                                        @break
                                                    @case('speaking')
                                                        <i class="fas fa-microphone text-purple-600"></i>
                                                        @break
                                                @endswitch
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                    {{ $attempt->testSet->title }}
                                                </h4>
                                                <p class="text-sm text-gray-600">{{ $attempt->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($attempt->band_score)
                                                <div class="text-2xl font-bold text-gray-900">{{ number_format($attempt->band_score, 1) }}</div>
                                                <p class="text-xs text-gray-600">Band Score</p>
                                            @else
                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No test attempts yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Leaderboard & Achievements -->
            <div class="space-y-6">
                <!-- Leaderboard -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i>Leaderboard
                        </h2>
                        <select onchange="changeLeaderboardPeriod(this.value)" 
                                class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
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
                        <h2 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-medal text-indigo-600 mr-2"></i>Next Achievements
                        </h2>
                        <button onclick="viewAchievements()" class="text-sm text-indigo-600 hover:text-indigo-800">
                            View All
                        </button>
                    </div>
                    
                    @if($progressToNext->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($progressToNext as $progress)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-{{ $progress['badge']->color }}-100 flex items-center justify-center mr-2">
                                                <i class="{{ $progress['badge']->icon }} text-{{ $progress['badge']->color }}-600 text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">{{ $progress['badge']->name }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $progress['current'] }}/{{ $progress['target'] }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                             style="width: {{ $progress['percentage'] }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $progress['badge']->description }}</p>
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

                <!-- Study Goal -->
                @if($userGoal)
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">
                            <i class="fas fa-bullseye text-indigo-600 mr-2"></i>Your IELTS Goal
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Target Band</span>
                                <span class="font-bold text-indigo-700 text-lg">{{ $userGoal->target_band_score }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Days Left</span>
                                <span class="font-bold text-gray-900">{{ $userGoal->days_remaining }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Progress</span>
                                <span class="font-bold text-gray-900">{{ round($userGoal->progress_percentage) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full"
                                     style="width: {{ $userGoal->progress_percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6 text-center">
                        <i class="fas fa-target text-4xl text-gray-400 mb-3"></i>
                        <h3 class="font-bold text-gray-900 mb-2">Set Your IELTS Goal</h3>
                        <p class="text-sm text-gray-600 mb-4">Define your target to track progress</p>
                        <button onclick="setGoalModal()" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Set Goal Now
                        </button>
                    </div>
                @endif
            </div>
        </div>

       

    <!-- Achievements Modal -->
    <div id="achievementsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-trophy text-yellow-500 mr-2"></i>Your Achievements
                    </h3>
                    <button onclick="closeAchievementsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-h-96 overflow-y-auto">
                    @foreach($allBadges as $badge)
                        @php
                            $earned = $userAchievements->contains('badge_id', $badge->id);
                            $achievement = $earned ? $userAchievements->firstWhere('badge_id', $badge->id) : null;
                        @endphp
                        <div class="text-center p-4 rounded-lg border-2 transition-all duration-300 cursor-pointer
                            {{ $earned ? 'border-' . $badge->color . '-500 bg-' . $badge->color . '-50' : 'border-gray-200 bg-gray-50 opacity-50' }}"
                            onclick="showBadgeDetails({{ $badge->id }})">
                            <div class="w-16 h-16 mx-auto mb-2 rounded-full bg-{{ $badge->color }}-100 flex items-center justify-center
                                {{ $earned ? 'ring-4 ring-' . $badge->color . '-500 ring-opacity-50' : '' }}">
                                <i class="{{ $badge->icon }} text-{{ $badge->color }}-600 text-2xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">{{ $badge->name }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $badge->points }} pts</p>
                            @if($earned)
                                <p class="text-xs text-{{ $badge->color }}-600 mt-1">
                                    <i class="fas fa-check-circle"></i> Earned
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Goal Setting Modal -->
    <div id="goalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <form action="{{ route('student.goals.store') }}" method="POST">
                @csrf
                <div class="mt-3">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Set Your IELTS Goal</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Band Score</label>
                        <select name="target_band_score" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select target score</option>
                            @for($i = 4.0; $i <= 9.0; $i += 0.5)
                                <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Date</label>
                        <input type="date" name="target_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Why are you taking IELTS?</label>
                        <textarea name="study_reason" rows="3" 
                                  class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="e.g., Study abroad, Immigration, Career..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeGoalModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Set Goal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
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
                    // Show badge details in a tooltip or modal
                    alert(data.badge.description + (data.earned ? '\n\nEarned on: ' + data.earned_at : '\n\nNot earned yet'));
                });
        }

        // Goal functionality
        function setGoalModal() {
            document.getElementById('goalModal').classList.remove('hidden');
        }

        function closeGoalModal() {
            document.getElementById('goalModal').classList.add('hidden');
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
    </script>
    @endpush
</x-student-layout>