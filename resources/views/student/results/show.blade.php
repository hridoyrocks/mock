{{-- resources/views/student/results/show.blade.php --}}
<x-student-layout>
    <x-slot:title>Test Result Details</x-slot>

    <style>
        /* Dark Mode Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Smooth transitions */
        body.dark {
            background: linear-gradient(to bottom, #1a1a1a, #0a0a0a);
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>

    <div x-data="{
        darkMode: localStorage.getItem('darkMode') === 'false' ? false : true,
        init() {
            // Listen for storage changes from other tabs/components
            window.addEventListener('storage', (e) => {
                if (e.key === 'darkMode') {
                    this.darkMode = e.newValue === 'false' ? false : true;
                }
            });

            // Listen for custom darkMode event from header
            window.addEventListener('darkModeChanged', (e) => {
                this.darkMode = e.detail;
            });

            // Watch for darkMode changes and update body class
            this.$watch('darkMode', value => {
                document.body.classList.toggle('dark', value);
            });
        }
    }">

    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0" :class="darkMode ? 'bg-black/20' : 'bg-gradient-to-br from-[#C8102E]/5 via-transparent to-[#C8102E]/10'"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <!-- Test Info Header -->
                <div class="rounded-2xl shadow-xl p-6 lg:p-8"
                     :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-[#C8102E]/10'">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            @php
                                $sectionIcons = [
                                    'listening' => 'fa-headphones',
                                    'reading' => 'fa-book-open',
                                    'writing' => 'fa-pen-fancy',
                                    'speaking' => 'fa-microphone'
                                ];
                                $icon = $sectionIcons[$attempt->testSet->section->name] ?? 'fa-question';
                            @endphp

                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center shadow-lg relative">
                                <i class="fas {{ $icon }} text-white text-2xl"></i>
                                @if($attempt->testSet->is_premium)
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-full flex items-center justify-center shadow-lg border-2" :class="darkMode ? 'border-gray-900' : 'border-white'">
                                        <i class="fas fa-crown text-white text-xs"></i>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <h1 class="text-2xl lg:text-3xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                    {{ $attempt->testSet->title }}
                                </h1>
                                <p class="capitalize" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ $attempt->testSet->section->name }} Section</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <!-- Band Score Display -->
                            @if($attempt->band_score)
                                <div class="rounded-xl px-8 py-6 text-center shadow-lg"
                                     :class="darkMode ? 'glass border border-[#C8102E]/30' : 'bg-gradient-to-br from-[#C8102E]/10 to-[#C8102E]/5 border border-[#C8102E]/20'">
                                    <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score</p>
                                    <p class="text-4xl font-bold text-[#C8102E]">
                                        {{ number_format($attempt->band_score, 1) }}
                                    </p>
                                    @if(isset($answeredQuestions) && isset($totalQuestions) && $answeredQuestions < $totalQuestions)
                                        <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Based on {{ $answeredQuestions }}/{{ $totalQuestions }} questions</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-4">
        <div class="max-w-7xl mx-auto">
            {{-- No Answers Alert --}}
            @if(isset($answeredQuestions) && $answeredQuestions === 0)
                <div class="rounded-xl p-6 mb-6 shadow-lg"
                     :class="darkMode ? 'glass border border-red-500/30' : 'bg-red-50 border border-red-200'">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 :class="darkMode ? 'bg-red-500/20' : 'bg-red-100'">
                                <i class="fas fa-exclamation-triangle text-2xl" :class="darkMode ? 'text-red-400' : 'text-red-600'"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-lg mb-2" :class="darkMode ? 'text-red-400' : 'text-red-800'">No Questions Answered</h4>
                            <p class="text-sm mb-3" :class="darkMode ? 'text-gray-300' : 'text-red-700'">
                                You did not answer any questions in this test. Your band score is 0.0 as no answers were submitted.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('student.' . $attempt->testSet->section->name . '.onboarding.confirm-details', $attempt->testSet->id) }}"
                                   class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition-all duration-200"
                                   :class="darkMode ? 'bg-[#C8102E] text-white hover:bg-[#A00E27]' : 'bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white hover:from-[#A00E27] hover:to-[#8A0C20]'">
                                    <i class="fas fa-redo mr-2"></i>
                                    Retake Test
                                </a>
                                <a href="{{ route('student.' . $attempt->testSet->section->name . '.index') }}"
                                   class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition-all duration-200"
                                   :class="darkMode ? 'bg-gray-700 text-white hover:bg-gray-600' : 'bg-gray-200 text-gray-800 hover:bg-gray-300'">
                                    <i class="fas fa-list mr-2"></i>
                                    View All Tests
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Score Details Alert --}}
            @if(isset($correctAnswers) && isset($totalQuestions) && isset($answeredQuestions) && $answeredQuestions > 0 && $answeredQuestions < $totalQuestions)
                <div class="rounded-xl p-4 mb-6 shadow-sm"
                     :class="darkMode ? 'glass border border-yellow-500/30' : 'bg-yellow-50 border border-yellow-200'">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-2xl mt-1" :class="darkMode ? 'text-yellow-400' : 'text-yellow-600'"></i>
                        <div class="flex-1">
                            <h4 class="font-semibold mb-1" :class="darkMode ? 'text-yellow-400' : 'text-yellow-800'">Test Completion Status</h4>
                            <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-yellow-700'">
                                You answered <strong :class="darkMode ? 'text-white' : 'text-yellow-900'">{{ $answeredQuestions }}/{{ $totalQuestions }}</strong> questions
                                ({{ round(($answeredQuestions / $totalQuestions) * 100, 1) }}% completion)
                            </p>
                            <p class="text-sm mt-1" :class="darkMode ? 'text-gray-300' : 'text-yellow-700'">
                                Band Score: <strong :class="darkMode ? 'text-white' : 'text-yellow-900'">{{ $attempt->band_score }}</strong>
                                (Based on <strong :class="darkMode ? 'text-white' : 'text-yellow-900'">{{ $correctAnswers }}/{{ $totalQuestions }}</strong> correct answers)
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="rounded-xl p-6 text-center shadow-sm"
                     :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                    <i class="fas fa-calendar-alt text-2xl mb-3 text-[#C8102E]"></i>
                    <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Date Taken</p>
                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">{{ $attempt->created_at->format('M d, Y') }}</p>
                    <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">{{ $attempt->created_at->format('g:i A') }}</p>
                </div>

                <div class="rounded-xl p-6 text-center shadow-sm"
                     :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                    <i class="fas fa-stopwatch text-2xl mb-3 text-[#C8102E]"></i>
                    <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Time Spent</p>
                    @php
                        $startTime = $attempt->start_time;
                        $endTime = $attempt->end_time ?? $attempt->updated_at;
                        $totalSeconds = $startTime->diffInSeconds($endTime);
                        $minutes = floor($totalSeconds / 60);
                        $seconds = $totalSeconds % 60;
                    @endphp
                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">{{ $minutes }}m {{ $seconds }}s</p>
                </div>

                <div class="rounded-xl p-6 text-center shadow-sm"
                     :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                    <i class="fas fa-tasks text-2xl mb-3 text-[#C8102E]"></i>
                    <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Completion</p>
                    @if(isset($answeredQuestions) && isset($totalQuestions) && $totalQuestions > 0)
                        <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">
                            {{ $answeredQuestions }}/{{ $totalQuestions }}
                            <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">({{ round(($answeredQuestions / $totalQuestions) * 100) }}%)</span>
                        </p>
                        <div class="w-full h-2 rounded-full mt-2 overflow-hidden" :class="darkMode ? 'bg-white/10' : 'bg-gray-200'">
                            <div class="h-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full"
                                 style="width: {{ ($answeredQuestions / $totalQuestions) * 100 }}%"></div>
                        </div>
                    @else
                        <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">{{ $attempt->completion_rate }}%</p>
                        <div class="w-full h-2 rounded-full mt-2 overflow-hidden" :class="darkMode ? 'bg-white/10' : 'bg-gray-200'">
                            <div class="h-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full"
                                 style="width: {{ $attempt->completion_rate }}%"></div>
                        </div>
                    @endif
                </div>

                <div class="rounded-xl p-6 text-center shadow-sm"
                     :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                    <i class="fas fa-trophy text-2xl mb-3 text-[#C8102E]"></i>
                    <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Level</p>
                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-800'">{{ \App\Helpers\ScoreCalculator::getBandDescription($attempt->band_score ?? 0) }}</p>
                </div>
            </div>
            
            {{-- Retake Test Button (Alternative Position) --}}
            @php
                $canRetake = $attempt->status === 'completed';
                $latestAttempt = \App\Models\StudentAttempt::getLatestAttempt($attempt->user_id, $attempt->test_set_id);
                $isLatestAttempt = $latestAttempt && $attempt->id === $latestAttempt->id;
            @endphp
            
            @if($canRetake && $isLatestAttempt)
                <div class="rounded-xl p-6 mb-8 text-center shadow-sm"
                     :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                    <p class="mb-4" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Want to improve your score? You can retake this test.</p>
                    <form action="{{ route('student.results.retake', $attempt) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                                class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium hover:from-[#A00E27] hover:to-[#8B0C24] transition-all transform hover:scale-105 inline-flex items-center shadow-lg">
                            <i class="fas fa-redo mr-2"></i>
                            Retake This Test
                        </button>
                    </form>
                </div>
            @endif

            {{-- Evaluation Section for Writing/Speaking --}}
            @if(isset($attempt->testSet) && isset($attempt->testSet->section) && in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                <!-- Evaluation Tabs -->
                <div class="rounded-2xl p-6 mb-8 shadow-xl"
                     :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-[#C8102E]/10'">
                    <div class="flex mb-6" :class="darkMode ? 'border-b border-white/10' : 'border-b border-gray-200'">
                        <button onclick="switchTab('ai')" id="ai-tab" class="px-6 py-3 font-medium border-b-2 border-[#C8102E] transition-all"
                                :class="darkMode ? 'text-white' : 'text-gray-800'">
                            <i class="fas fa-robot mr-2"></i> AI Evaluation
                        </button>
                        <button onclick="switchTab('human')" id="human-tab" class="px-6 py-3 font-medium border-b-2 border-transparent transition-all"
                                :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-800'">
                            <i class="fas fa-user-tie mr-2"></i> Human Evaluation
                        </button>
                    </div>

                    <!-- AI Evaluation Tab Content -->
                    <div id="ai-content" class="">
                        <h3 class="text-xl font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-800'">
                            <i class="fas fa-robot text-[#C8102E] mr-2"></i>
                            AI Evaluation
                        </h3>
                        
                        @if(auth()->user()->hasFeature('ai_' . $attempt->testSet->section->name . '_evaluation'))
                            @if($attempt->completion_rate == 0)
                                <div class="rounded-xl p-4 shadow-sm"
                                     :class="darkMode ? 'bg-yellow-500/10 border border-yellow-500/30' : 'bg-yellow-50 border border-yellow-200'">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-exclamation-circle text-xl mt-1" :class="darkMode ? 'text-yellow-400' : 'text-yellow-600'"></i>
                                        <div class="flex-1">
                                            <h4 class="font-semibold" :class="darkMode ? 'text-yellow-400' : 'text-yellow-800'">Test Not Completed</h4>
                                            <p class="text-sm mt-1" :class="darkMode ? 'text-gray-300' : 'text-yellow-700'">
                                                You need to complete the {{ $attempt->testSet->section->name }} test before requesting AI evaluation.
                                            </p>
                                            <a href="{{ route('student.' . $attempt->testSet->section->name . '.start', $attempt->testSet) }}"
                                               class="inline-flex items-center mt-3 px-4 py-2 rounded-lg transition-all text-sm shadow-sm"
                                               :class="darkMode ? 'glass border border-yellow-500/50 text-yellow-400 hover:border-yellow-500' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                Go Back to Test
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @elseif(!$attempt->ai_evaluated_at)
                                <div class="text-center py-6">
                                    <i class="fas fa-robot text-6xl text-[#C8102E] mb-4"></i>
                                    <p class="mb-4" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Get instant feedback and band score prediction with our advanced AI evaluator.</p>

                                    @if($attempt->completion_rate > 0)
                                        <div class="inline-flex items-center px-4 py-2 rounded-lg mb-4 shadow-sm"
                                             :class="darkMode ? 'glass border border-green-500/30 text-green-400' : 'bg-green-50 border border-green-200 text-green-700'">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Test completed with {{ $attempt->completion_rate }}% completion rate
                                        </div>
                                    @endif

                                    <button onclick="startAIEvaluation({{ $attempt->id }}, '{{ $attempt->testSet->section->name }}')"
                                            id="ai-eval-btn"
                                            class="block w-full sm:w-auto mx-auto px-6 py-3 rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium hover:from-[#A00E27] hover:to-[#8B0C24] transition-all transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-magic mr-2"></i>
                                        Get Instant Evaluation
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center justify-between rounded-xl p-4 shadow-sm"
                                     :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <div>
                                        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score</p>
                                        <p class="text-3xl font-bold text-[#C8102E]">{{ $attempt->ai_band_score ?? 'N/A' }}</p>
                                    </div>
                                    <a href="{{ route('ai.evaluation.get', $attempt->id) }}"
                                       class="px-6 py-3 rounded-xl transition-all shadow-sm"
                                       :class="darkMode ? 'glass border border-[#C8102E]/30 text-white hover:border-[#C8102E]' : 'bg-white border border-gray-200 text-gray-800 hover:border-[#C8102E] hover:text-[#C8102E]'">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        View Detailed Analysis
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-6">
                                <i class="fas fa-crown text-6xl text-[#C8102E] mb-4"></i>
                                <p class="mb-4" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Upgrade to Premium to unlock instant evaluation for instant feedback.</p>
                                <a href="{{ route('subscription.plans') }}"
                                   class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 text-white font-medium hover:from-amber-600 hover:to-yellow-600 transition-all shadow-lg">
                                    <i class="fas fa-rocket mr-2"></i>
                                    Upgrade to Premium
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Human Evaluation Tab Content -->
                    <div id="human-content" class="hidden">
                        <h3 class="text-xl font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <i class="fas fa-user-tie text-[#C8102E] mr-2"></i>
                            Human Evaluation
                        </h3>

                        @if(isset($humanEvaluationRequest) && $humanEvaluationRequest)
                            @if($humanEvaluationRequest->status === 'completed')
                                <!-- Completed Evaluation -->
                                <div class="rounded-xl p-6 shadow-sm" :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Evaluated by</p>
                                            <p class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $humanEvaluationRequest->teacher->user->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score</p>
                                            <p class="text-3xl font-bold text-[#C8102E]">{{ $humanEvaluationRequest->humanEvaluation->overall_band_score ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.evaluation.result', $attempt->id) }}"
                                       class="block w-full text-center py-3 rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium hover:from-[#A00E27] hover:to-[#8B0C24] transition-all shadow-lg">
                                        <i class="fas fa-eye mr-2"></i> View Detailed Evaluation
                                    </a>
                                </div>
                            @else
                                <!-- Pending Evaluation -->
                                <div class="rounded-xl p-6 border shadow-sm" :class="darkMode ? 'glass bg-yellow-500/10 border-yellow-500/30' : 'bg-yellow-50 border-yellow-200'">
                                    <div class="flex items-start gap-4">
                                        <i class="fas fa-clock text-yellow-400 text-2xl"></i>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-yellow-400 mb-2">Evaluation In Progress</h4>
                                            <p class="mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Your evaluation request has been assigned to <strong>{{ $humanEvaluationRequest->teacher->user->name }}</strong>.</p>
                                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Status: <span class="text-yellow-400">{{ ucfirst($humanEvaluationRequest->status) }}</span></p>
                                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Deadline: {{ $humanEvaluationRequest->deadline_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.evaluation.status', $attempt->id) }}"
                                       class="block w-full text-center mt-4 py-3 rounded-xl text-white transition-all" :class="darkMode ? 'glass hover:border-[#C8102E]/50' : 'bg-gray-100 hover:bg-gray-200'">
                                        <i class="fas fa-info-circle mr-2"></i> View Status Details
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- No Evaluation Yet -->
                            <div class="text-center py-8">
                                <i class="fas fa-user-tie text-6xl text-[#C8102E] mb-4"></i>
                                <p class="mb-4" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Get your {{ $attempt->testSet->section->name }} evaluated by certified IELTS teachers for detailed feedback and accurate band score.</p>

                                <div class="rounded-xl p-4 inline-block mb-6 shadow-sm" :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <p class="text-sm mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Starting from</p>
                                    <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">10 <span class="text-[#C8102E]">tokens</span></p>
                                </div>

                                <a href="{{ route('student.evaluation.teachers', $attempt->id) }}"
                                   class="inline-flex items-center px-8 py-3 rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium hover:from-[#A00E27] hover:to-[#8B0C24] transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-search mr-2"></i>
                                    Choose Teacher
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Score Breakdown for Listening/Reading --}}
            @if(in_array($attempt->testSet->section->name, ['listening', 'reading']))
                <div class="rounded-2xl p-6 mb-8 shadow-xl" :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-[#C8102E]/10'">
                    <h3 class="text-xl font-bold mb-6" :class="darkMode ? 'text-white' : 'text-gray-900'">
                        <i class="fas fa-chart-pie text-[#C8102E] mr-2"></i>
                        Score Breakdown
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-sm mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Questions Attempted</p>
                            <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                {{ $answeredQuestions }}
                                <span class="text-lg" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">/ {{ $totalQuestions }}</span>
                            </p>
                        </div>

                        <div class="text-center">
                            <p class="text-sm mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Correct Answers</p>
                            <p class="text-2xl font-bold text-green-500">
                                {{ $correctAnswers }}
                            </p>
                        </div>

                        <div class="text-center">
                            <p class="text-sm mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Accuracy</p>
                            <p class="text-2xl font-bold text-blue-500">
                                {{ number_format($accuracy, 1) }}%
                            </p>
                        </div>

                        <div class="text-center">
                            <p class="text-sm mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score</p>
                            <p class="text-2xl font-bold text-[#C8102E]">
                                {{ $attempt->band_score }}
                            </p>
                        </div>
                    </div>

                    {{-- Show calculation method info --}}
                    @if($answeredQuestions < $totalQuestions)
                        <div class="rounded-xl p-4 mb-4 border shadow-sm" :class="darkMode ? 'glass border-[#C8102E]/30' : 'bg-[#C8102E]/5 border-[#C8102E]/20'">
                            <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                <i class="fas fa-calculator text-[#C8102E] mr-2"></i>
                                Band score calculated based on actual correct answers ({{ $correctAnswers }}/{{ $totalQuestions }}) according to IELTS scoring table.
                            </p>
                        </div>
                    @endif

                    {{-- Band Score Visual --}}
                    <div class="mt-6">
                        <p class="text-sm mb-3" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score Progress</p>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-2">
                                @foreach([1, 2, 3, 4, 5, 6, 7, 8, 9] as $band)
                                    <span class="text-xs {{ $attempt->band_score >= $band ? 'text-[#C8102E]' : '' }}" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">{{ $band }}</span>
                                @endforeach
                            </div>
                            <div class="w-full h-3 rounded-full overflow-hidden" :class="darkMode ? 'bg-white/10' : 'bg-gray-200'">
                                <div class="h-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full transition-all duration-1000"
                                     style="width: {{ ($attempt->band_score / 9) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Question Analysis --}}
                <div id="question-analysis" class="rounded-2xl p-6 relative shadow-xl" :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-[#C8102E]/10'">
                    <h3 class="text-xl font-bold mb-6" :class="darkMode ? 'text-white' : 'text-gray-900'">
                        <i class="fas fa-microscope text-[#C8102E] mr-2"></i>
                        Question Analysis
                    </h3>

                    @php
                        $isPremium = auth()->user()->hasActiveSubscription() && !auth()->user()->hasPlan('free');
                    @endphp

                    @if(!$isPremium)
                        <div class="absolute inset-0 z-10 backdrop-blur-sm rounded-2xl flex items-center justify-center" :class="darkMode ? 'glass' : 'bg-white/90'">
                            <div class="text-center max-w-md p-6">
                                <i class="fas fa-lock text-[#C8102E] text-6xl mb-4"></i>
                                <h4 class="text-xl font-bold mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">Premium Feature</h4>
                                <p class="mb-4" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                    Unlock detailed question analysis with correct answers and explanations.
                                </p>
                                <a href="{{ route('subscription.plans') }}"
                                   class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium hover:from-[#A00E27] hover:to-[#8B0C24] transition-all shadow-lg">
                                    <i class="fas fa-crown mr-2"></i>
                                    Upgrade to Premium
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <div class="{{ !$isPremium ? 'blur-sm pointer-events-none' : '' }}">
                        <div class="space-y-3">
                            @php
                                $allQuestions = $attempt->testSet->questions()
                                    ->where('question_type', '!=', 'passage')
                                    ->orderBy('part_number')
                                    ->orderBy('order_number')
                                    ->get();
                                
                                // Build display questions with proper numbering
                                $displayQuestions = [];
                                $currentNumber = 1;
                                $masterQuestionIds = []; // Track master questions already processed
                                
                                foreach ($allQuestions as $question) {
                                    if ($question->isMasterMatchingHeading()) {
                                        if (!in_array($question->id, $masterQuestionIds)) {
                                            $masterQuestionIds[] = $question->id;
                                            $mappings = $question->section_specific_data['mappings'] ?? [];
                                            $headings = $question->section_specific_data['headings'] ?? [];

                                            // Get all answers for this master question
                                            $masterAnswers = $attempt->answers->filter(function($answer) use ($question) {
                                                return $answer->question_id == $question->id;
                                            });

                                            // Create display for each sub-question
                                            foreach ($mappings as $mapping) {
                                                $subQuestionNum = $mapping['question'] ?? $mapping['number'] ?? $currentNumber;
                                                $paragraphLabel = $mapping['paragraph'] ?? chr(65 + array_search($mapping, $mappings));
                                                $correctLetter = $mapping['correct'] ?? null;

                                                // Find correct heading text
                                                $correctHeadingText = null;
                                                if ($correctLetter) {
                                                    foreach ($headings as $heading) {
                                                        if ($heading['id'] === $correctLetter) {
                                                            $correctHeadingText = $heading['text'] ?? null;
                                                            break;
                                                        }
                                                    }
                                                }

                                                // Find the specific answer for this sub-question
                                                $specificAnswer = $masterAnswers->first(function($answer) use ($subQuestionNum) {
                                                    if ($answer->answer) {
                                                        $decoded = json_decode($answer->answer, true);
                                                        return isset($decoded['sub_question']) && $decoded['sub_question'] == $subQuestionNum;
                                                    }
                                                    return false;
                                                });

                                                $displayQuestions[] = [
                                                    'number' => $currentNumber,
                                                    'question' => $question,
                                                    'content' => "Choose the correct heading for Paragraph {$paragraphLabel}",
                                                    'answer' => $specificAnswer,
                                                    'is_master_sub' => true,
                                                    'sub_question' => $subQuestionNum,
                                                    'correct_letter' => $correctLetter,
                                                    'correct_heading_text' => $correctHeadingText,
                                                    'all_headings' => $headings
                                                ];
                                                $currentNumber++;
                                            }
                                        }
                                    } elseif ($question->question_type === 'sentence_completion' && isset($question->section_specific_data['sentence_completion'])) {
                                        // Handle sentence completion questions
                                        $scData = $question->section_specific_data['sentence_completion'];
                                        $sentences = $scData['sentences'] ?? [];
                                        
                                        foreach ($sentences as $sentenceIndex => $sentence) {
                                            // Find answer for this specific sentence based on questionNumber
                                            $questionNumber = $sentence['questionNumber'] ?? ($sentenceIndex + 1);
                                            
                                            $specificAnswer = $attempt->answers->first(function($ans) use ($question, $questionNumber) {
                                                if ($ans->question_id != $question->id) return false;
                                                
                                                $answerData = json_decode($ans->answer, true);
                                                if (is_array($answerData) && isset($answerData['sub_question'])) {
                                                    return (int)$answerData['sub_question'] === $questionNumber;
                                                }
                                                return false;
                                            });
                                            
                                            $displayQuestions[] = [
                                                'number' => $currentNumber,
                                                'question' => $question,
                                                'content' => $sentence['text'] ?? "Sentence " . ($sentenceIndex + 1),
                                                'answer' => $specificAnswer,
                                                'is_sentence_completion' => true,
                                                'sentence_index' => $sentenceIndex,
                                                'question_number' => $questionNumber,
                                                'correct_answer' => $sentence['correctAnswer'] ?? $sentence['correct_answer'] ?? null
                                            ];
                                            $currentNumber++;
                                        }
                                    } elseif ($question->question_type === 'drag_drop') {
                                        // Handle drag & drop questions
                                        $dragDropData = $question->section_specific_data ?? [];
                                        $dropZones = $dragDropData['drop_zones'] ?? [];
                                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                                        
                                        foreach ($dropZones as $zoneIndex => $zone) {
                                            $displayQuestions[] = [
                                                'number' => $currentNumber,
                                                'question' => $question,
                                                'content' => $zone['text'] ?? "Drop Zone " . ($zoneIndex + 1),
                                                'answer' => $answer,
                                                'is_drag_drop' => true,
                                                'zone_index' => $zoneIndex,
                                                'correct_answer' => $zone['correct_answer'] ?? null
                                            ];
                                            $currentNumber++;
                                        }
                                    } elseif ($question->question_type === 'fill_blanks') {
                                        // Handle fill in the blanks questions
                                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                                        
                                        // Count blanks in content
                                        preg_match_all('/\[____\d+____\]/', $question->content, $matches);
                                        $blankCount = count($matches[0]);
                                        
                                        if ($blankCount > 1) {
                                            // Multiple blanks - create separate display for each
                                            foreach ($matches[0] as $match) {
                                                preg_match('/\d+/', $match, $numberMatch);
                                                $blankNum = $numberMatch[0] ?? $currentNumber;
                                                
                                                $cleanContent = str_replace($match, '[blank]', $question->content);
                                                $cleanContent = strip_tags($cleanContent);
                                                
                                                $displayQuestions[] = [
                                                    'number' => $currentNumber,
                                                    'question' => $question,
                                                    'content' => $cleanContent,
                                                    'answer' => $answer,
                                                    'is_fill_blank' => true,
                                                    'blank_number' => $blankNum
                                                ];
                                                $currentNumber++;
                                            }
                                        } else {
                                            // Single blank
                                            $displayQuestions[] = [
                                                'number' => $currentNumber,
                                                'question' => $question,
                                                'content' => strip_tags($question->content),
                                                'answer' => $answer,
                                                'is_fill_blank' => true,
                                                'blank_number' => 1
                                            ];
                                            $currentNumber++;
                                        }
                                    } elseif ($question->question_type === 'dropdown_selection') {
                                        // Handle dropdown selection questions
                                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                                        
                                        // Count dropdowns in content
                                        preg_match_all('/\[DROPDOWN_\d+\]/', $question->content, $matches);
                                        $dropdownCount = count($matches[0]);
                                        
                                        if ($dropdownCount > 1) {
                                            // Multiple dropdowns - create separate display for each
                                            foreach ($matches[0] as $match) {
                                                preg_match('/\d+/', $match, $numberMatch);
                                                $dropdownNum = $numberMatch[0] ?? $currentNumber;
                                                
                                                $cleanContent = str_replace($match, '[dropdown]', $question->content);
                                                $cleanContent = strip_tags($cleanContent);
                                                
                                                $displayQuestions[] = [
                                                    'number' => $currentNumber,
                                                    'question' => $question,
                                                    'content' => $cleanContent,
                                                    'answer' => $answer,
                                                    'is_dropdown' => true,
                                                    'dropdown_index' => $dropdownNum
                                                ];
                                                $currentNumber++;
                                            }
                                        } else {
                                            // Single dropdown
                                            $displayQuestions[] = [
                                                'number' => $currentNumber,
                                                'question' => $question,
                                                'content' => strip_tags(preg_replace('/\[DROPDOWN_\d+\]/', '[dropdown]', $question->content)),
                                                'answer' => $answer,
                                                'is_dropdown' => true,
                                                'dropdown_index' => 1
                                            ];
                                            $currentNumber++;
                                        }
                                    } elseif ($question->question_type === 'multiple_choice') {
                                        // Handle multiple choice with potentially multiple correct answers
                                        $correctCount = $question->options->where('is_correct', true)->count();
                                        $answers = $attempt->answers->where('question_id', $question->id);
                                        
                                        if ($correctCount > 1) {
                                            // Multiple correct answers - each gets its own number
                                            for ($i = 1; $i <= $correctCount; $i++) {
                                                $displayQuestions[] = [
                                                    'number' => $currentNumber,
                                                    'question' => $question,
                                                    'content' => strip_tags($question->content),
                                                    'answer' => $answers->skip($i-1)->first(),
                                                    'is_multiple_choice' => true,
                                                    'choice_index' => $i
                                                ];
                                                $currentNumber++;
                                            }
                                        } else {
                                            // Single correct answer
                                            $displayQuestions[] = [
                                                'number' => $currentNumber,
                                                'question' => $question,
                                                'content' => strip_tags($question->content),
                                                'answer' => $answers->first(),
                                                'is_multiple_choice' => true,
                                                'choice_index' => 1
                                            ];
                                            $currentNumber++;
                                        }
                                    } else {
                                        // Regular question
                                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                                        $displayQuestions[] = [
                                            'number' => $currentNumber,
                                            'question' => $question,
                                            'content' => strip_tags($question->content),
                                            'answer' => $answer,
                                            'is_regular' => true
                                        ];
                                        $currentNumber++;
                                    }
                                }
                            @endphp
                            
                            @php
                                $startIndex = ($currentPage - 1) * $perPage;
                                $questionsToShow = collect($displayQuestions)->slice($startIndex, $perPage);
                            @endphp
                            
                            @foreach($questionsToShow as $item)
                                @php
                                    $question = $item['question'];
                                    $answer = $item['answer'];
                                    $isAnswered = !empty($answer);
                                    
                                    // Check if answer is correct
                                    $isCorrect = false;
                                    $displayAnswer = 'No answer';
                                    
                                    // Handle dropdown-specific display
                                    if (isset($item['is_drag_drop']) && $item['is_drag_drop'] && $isAnswered && $answer) {
                                        // Handle drag & drop questions
                                        $answerData = @json_decode($answer->answer, true);
                                        if (is_array($answerData)) {
                                            $zoneIndex = $item['zone_index'];
                                            $zoneKey = 'zone_' . $zoneIndex;
                                            $studentAnswer = $answerData[$zoneKey] ?? null;
                                            
                                            if ($studentAnswer !== null) {
                                                $displayAnswer = $studentAnswer;
                                                $correctAnswer = $item['correct_answer'];
                                                $isCorrect = ($correctAnswer && $studentAnswer === $correctAnswer);
                                            }
                                        }
                                    } elseif (isset($item['is_fill_blank']) && $item['is_fill_blank'] && $isAnswered && $answer) {
                                        // Handle fill in the blanks
                                        $answerData = @json_decode($answer->answer, true);
                                        if (is_array($answerData)) {
                                            $blankNum = $item['blank_number'];
                                            $studentAnswer = $answerData['blank_' . $blankNum] ?? null;
                                            
                                            if ($studentAnswer !== null) {
                                                $displayAnswer = $studentAnswer;
                                                $isCorrect = $question->checkBlankAnswer($blankNum, $studentAnswer);
                                            }
                                        }
                                    } elseif (isset($item['dropdown_index']) && isset($item['is_dropdown']) && $item['is_dropdown'] && $isAnswered && $answer) {
                                        // Handle dropdown selection
                                        $answerData = @json_decode($answer->answer, true);
                                        if (is_array($answerData)) {
                                            $dropdownNum = $item['dropdown_index'];
                                            $studentDropdownAnswer = $answerData['dropdown_' . $dropdownNum] ?? null;
                                            
                                            if ($studentDropdownAnswer !== null) {
                                                $displayAnswer = $studentDropdownAnswer;
                                                
                                                // Check if correct
                                                if ($question->section_specific_data && isset($question->section_specific_data['dropdown_correct'][$dropdownNum])) {
                                                    $correctIndex = $question->section_specific_data['dropdown_correct'][$dropdownNum];
                                                    $dropdownOptions = $question->section_specific_data['dropdown_options'][$dropdownNum] ?? '';
                                                    
                                                    if ($dropdownOptions) {
                                                        $options = array_map('trim', explode(',', $dropdownOptions));
                                                        $correctOption = $options[$correctIndex] ?? '';
                                                        $isCorrect = (strtolower(trim($studentDropdownAnswer)) === strtolower(trim($correctOption)));
                                                    }
                                                }
                                            }
                                        }
                                    } else if ($isAnswered && $answer) {
                                        if (isset($item['is_master_sub']) && $item['is_master_sub']) {
                                            // Master matching heading sub-question
                                            $decoded = json_decode($answer->answer, true);
                                            $selectedLetter = $decoded['selected_letter'] ?? null;

                                            // Find selected heading text
                                            $selectedHeadingText = null;
                                            if ($selectedLetter && isset($item['all_headings'])) {
                                                foreach ($item['all_headings'] as $heading) {
                                                    if ($heading['id'] === $selectedLetter) {
                                                        $selectedHeadingText = $heading['text'] ?? null;
                                                        break;
                                                    }
                                                }
                                            }

                                            $displayAnswer = $selectedHeadingText ? $selectedHeadingText : ($selectedLetter ? "Option {$selectedLetter}" : 'No answer');
                                            $isCorrect = $selectedLetter && $selectedLetter === $item['correct_letter'];
                                        } elseif ($answer->selectedOption) {
                                            $displayAnswer = $answer->selectedOption->content;
                                            $isCorrect = $answer->selectedOption->is_correct;
                                        } elseif (isset($item['is_sentence_completion']) && $item['is_sentence_completion']) {
                                            // Sentence completion answer
                                            if ($answer && $answer->answer) {
                                                $answerData = json_decode($answer->answer, true);
                                                if (is_array($answerData) && isset($answerData['sub_question']) && isset($answerData['selected_answer'])) {
                                                    $questionNum = (int)$answerData['sub_question'];
                                                    $questionNumber = $item['question_number'] ?? $item['number'];
                                                    if ($questionNum == $questionNumber) {
                                                        $displayAnswer = $answerData['selected_answer'] ? "Option {$answerData['selected_answer']}" : 'No answer';
                                                        $isCorrect = $answerData['selected_answer'] && $answerData['selected_answer'] === $item['correct_answer'];
                                                    }
                                                }
                                            }
                                        } elseif ($answer->answer) {
                                            // Check if it's JSON (fill-in-the-blank or dropdown)
                                            $answerData = @json_decode($answer->answer, true);
                                            if (is_array($answerData)) {
                                                $displayParts = [];
                                                foreach ($answerData as $key => $value) {
                                                    if (!empty($value)) {
                                                        $displayParts[] = $value;
                                                    }
                                                }
                                                $displayAnswer = implode(', ', $displayParts);
                                                
                                                // Check blanks
                                                $allCorrect = true;
                                                if ($question->section_specific_data && isset($question->section_specific_data['blank_answers'])) {
                                                    foreach ($question->section_specific_data['blank_answers'] as $num => $correctAnswer) {
                                                        $studentAnswer = $answerData['blank_' . $num] ?? '';
                                                        if (strtolower(trim($studentAnswer)) !== strtolower(trim($correctAnswer))) {
                                                            $allCorrect = false;
                                                            break;
                                                        }
                                                    }
                                                }
                                                
                                                // Check dropdowns
                                                if ($question->section_specific_data && isset($question->section_specific_data['dropdown_correct'])) {
                                                    // If only dropdowns exist (no blanks), reset allCorrect
                                                    $hasDropdowns = count($question->section_specific_data['dropdown_correct']) > 0;
                                                    $hasBlanks = isset($question->section_specific_data['blank_answers']) && count($question->section_specific_data['blank_answers']) > 0;
                                                    
                                                    if ($hasDropdowns && !$hasBlanks) {
                                                        $allCorrect = true;
                                                    }
                                                    
                                                    foreach ($question->section_specific_data['dropdown_correct'] as $num => $correctIndex) {
                                                        $studentDropdownAnswer = $answerData['dropdown_' . $num] ?? null;
                                                        
                                                        if ($studentDropdownAnswer !== null) {
                                                            $dropdownOptions = $question->section_specific_data['dropdown_options'][$num] ?? '';
                                                            if ($dropdownOptions) {
                                                                $options = array_map('trim', explode(',', $dropdownOptions));
                                                                $correctOption = $options[$correctIndex] ?? '';
                                                                
                                                                if (strtolower(trim($studentDropdownAnswer)) !== strtolower(trim($correctOption))) {
                                                                    $allCorrect = false;
                                                                    break;
                                                                }
                                                            }
                                                        } else if ($hasDropdowns && !$hasBlanks) {
                                                            // For dropdown-only questions, if answer is missing, it's incorrect
                                                            $allCorrect = false;
                                                            break;
                                                        }
                                                    }
                                                }
                                                
                                                $isCorrect = $allCorrect;
                                            } else {
                                                $displayAnswer = $answer->answer;
                                            }
                                        }
                                    }
                                @endphp

                                <div class="rounded-lg p-4 shadow-sm {{ !$isAnswered ? 'opacity-60' : '' }}" :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-start gap-3 mb-2">
                                                <span class="px-3 py-1 rounded-lg text-sm font-medium min-w-[3rem] text-center shadow-sm" :class="darkMode ? 'glass text-white' : 'bg-white text-gray-900 border border-gray-200'">
                                                    Q{{ $item['number'] }}
                                                </span>
                                                <div class="flex-1">
                                                    <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                        @if(isset($item['is_dropdown']) && $item['is_dropdown'])
                                                            @php
                                                                // For dropdown questions, show full content with dropdown highlighted
                                                                $content = $item['content'];
                                                                $dropdownNum = $item['dropdown_index'];
                                                                $counter = 0;
                                                                // Replace the specific dropdown placeholder
                                                                $formattedContent = preg_replace_callback('/________/', function($matches) use ($dropdownNum, &$counter) {
                                                                    $counter++;
                                                                    if ($counter == $dropdownNum) {
                                                                        return '<span class="inline-block px-2 py-0.5 bg-[#C8102E]/20 text-[#C8102E] rounded font-medium">[blank]</span>';
                                                                    }
                                                                    return '________';
                                                                }, $content);
                                                            @endphp
                                                            {!! $formattedContent !!}
                                                        @else
                                                            {!! Str::limit(strip_tags($item['content']), 100) !!}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="text-sm">
                                                <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                    Your answer:
                                                    <span :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                        @if(!$isAnswered)
                                                            <span class="text-orange-500">Not attempted</span>
                                                        @else
                                                            {{ $displayAnswer }}
                                                        @endif
                                                    </span>
                                                </p>
                                                
                                                {{-- Show correct answer for premium users --}}
                                                @if($isPremium && $isAnswered && !$isCorrect)
                                                    <p class="mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        Correct answer:
                                                        <span class="text-green-500">
                                                            @if(isset($item['is_drag_drop']) && $item['is_drag_drop'])
                                                                {{ $item['correct_answer'] ?? 'N/A' }}
                                                            @elseif(isset($item['is_fill_blank']) && $item['is_fill_blank'])
                                                                @php
                                                                    $blankNum = $item['blank_number'];
                                                                    $blankAnswers = $question->getBlankAnswersArray();
                                                                    $correctAnswer = $blankAnswers[$blankNum] ?? 'N/A';
                                                                @endphp
                                                                {{ $correctAnswer }}
                                                            @elseif(isset($item['dropdown_index']) && isset($item['is_dropdown']) && $item['is_dropdown'])
                                                                @php
                                                                    $dropdownNum = $item['dropdown_index'];
                                                                    $correctIndex = $question->section_specific_data['dropdown_correct'][$dropdownNum] ?? null;
                                                                    $dropdownOptions = $question->section_specific_data['dropdown_options'][$dropdownNum] ?? '';
                                                                    $correctAnswer = '';
                                                                    if ($dropdownOptions && $correctIndex !== null) {
                                                                        $options = array_map('trim', explode(',', $dropdownOptions));
                                                                        $correctAnswer = $options[$correctIndex] ?? '';
                                                                    }
                                                                @endphp
                                                                {{ $correctAnswer }}
                                                            @elseif(isset($item['is_master_sub']) && $item['is_master_sub'])
                                                                {{ $item['correct_heading_text'] ?? 'Option ' . $item['correct_letter'] }}
                                                            @elseif(isset($item['is_sentence_completion']) && $item['is_sentence_completion'])
                                                                Option {{ $item['correct_answer'] }}
                                                            @else
                                                                {{ $question->getCorrectAnswerForDisplay() }}
                                                            @endif
                                                        </span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="ml-4">
                                            @if(!$isAnswered)
                                                <span class="text-orange-500">
                                                    <i class="fas fa-minus-circle text-xl"></i>
                                                </span>
                                            @elseif($isCorrect)
                                                <span class="text-green-500">
                                                    <i class="fas fa-check-circle text-xl"></i>
                                                </span>
                                            @else
                                                <span class="text-red-500">
                                                    <i class="fas fa-times-circle text-xl"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        {{-- Pagination Controls --}}
                        @if(count($displayQuestions) > $perPage)
                            @php
                                $totalPages = ceil(count($displayQuestions) / $perPage);
                                $startIndex = ($currentPage - 1) * $perPage;
                                $endIndex = min($startIndex + $perPage, count($displayQuestions));
                            @endphp
                            
                            <div class="mt-6 pt-6" :class="darkMode ? 'border-t border-white/10' : 'border-t border-gray-200'">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <p class="text-sm text-center sm:text-left" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        Showing {{ $startIndex + 1 }} to {{ $endIndex }} of {{ count($displayQuestions) }} questions
                                    </p>

                                    <div class="flex items-center gap-2">
                                        {{-- Previous Button --}}
                                        @if($currentPage > 1)
                                            <a href="?page={{ $currentPage - 1 }}"
                                               class="px-4 py-2 rounded-lg transition-all flex items-center gap-2 shadow-sm" :class="darkMode ? 'glass text-white hover:border-[#C8102E]/50' : 'bg-white text-gray-700 border border-gray-200 hover:border-[#C8102E]/50'">
                                                <i class="fas fa-chevron-left"></i>
                                                Previous
                                            </a>
                                        @else
                                            <button disabled
                                                    class="px-4 py-2 rounded-lg text-gray-500 opacity-50 cursor-not-allowed flex items-center gap-2 shadow-sm" :class="darkMode ? 'glass' : 'bg-gray-100 border border-gray-200'">
                                                <i class="fas fa-chevron-left"></i>
                                                Previous
                                            </button>
                                        @endif
                                        
                                        {{-- Page Numbers (Limited for mobile) --}}
                                        <div class="hidden sm:flex items-center gap-1">
                                            @php
                                                $showPages = [];
                                                // Always show first page
                                                $showPages[] = 1;
                                                
                                                // Show current page and nearby pages
                                                for($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++) {
                                                    $showPages[] = $i;
                                                }
                                                
                                                // Always show last page if more than 1 page
                                                if($totalPages > 1) {
                                                    $showPages[] = $totalPages;
                                                }
                                                
                                                $showPages = array_unique($showPages);
                                                sort($showPages);
                                            @endphp
                                            
                                            @foreach($showPages as $index => $pageNum)
                                                @if($index > 0 && $showPages[$index] - $showPages[$index-1] > 1)
                                                    <span class="text-gray-500">...</span>
                                                @endif
                                                
                                                @if($pageNum == $currentPage)
                                                    <span class="px-3 py-2 rounded-lg font-medium shadow-sm" :class="darkMode ? 'glass bg-[#C8102E]/30 text-white' : 'bg-[#C8102E] text-white'">
                                                        {{ $pageNum }}
                                                    </span>
                                                @else
                                                    <a href="?page={{ $pageNum }}"
                                                       class="px-3 py-2 rounded-lg transition-all shadow-sm" :class="darkMode ? 'glass text-gray-400 hover:text-white hover:border-[#C8102E]/50' : 'bg-white text-gray-700 border border-gray-200 hover:border-[#C8102E]/50'">
                                                        {{ $pageNum }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        {{-- Mobile Page Indicator --}}
                                        <div class="flex sm:hidden items-center gap-2">
                                            <span class="px-3 py-2 rounded-lg shadow-sm" :class="darkMode ? 'glass text-white' : 'bg-white text-gray-900 border border-gray-200'">
                                                {{ $currentPage }} / {{ $totalPages }}
                                            </span>
                                        </div>

                                        {{-- Next Button --}}
                                        @if($currentPage < $totalPages)
                                            <a href="?page={{ $currentPage + 1 }}"
                                               class="px-4 py-2 rounded-lg transition-all flex items-center gap-2 shadow-sm" :class="darkMode ? 'glass text-white hover:border-[#C8102E]/50' : 'bg-white text-gray-700 border border-gray-200 hover:border-[#C8102E]/50'">
                                                Next
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        @else
                                            <button disabled
                                                    class="px-4 py-2 rounded-lg text-gray-500 opacity-50 cursor-not-allowed flex items-center gap-2 shadow-sm" :class="darkMode ? 'glass' : 'bg-gray-100 border border-gray-200'">
                                                Next
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Writing/Speaking Submission --}}
            @if(in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                <div class="rounded-2xl p-6 shadow-xl" :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-[#C8102E]/10'">
                    <h3 class="text-xl font-bold mb-6" :class="darkMode ? 'text-white' : 'text-gray-900'">
                        <i class="fas fa-file-alt text-[#C8102E] mr-2"></i>
                        Your Submission
                    </h3>

                    @if($attempt->testSet->section->name === 'writing')
                        <div class="space-y-6">
                            @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                <div class="rounded-xl p-6 shadow-sm" :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <h4 class="font-semibold mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        <i class="fas fa-tasks text-[#C8102E] mr-2"></i>
                                        Task {{ $answer->question->order_number }}
                                    </h4>
                                    @if(!empty($answer->answer))
                                        <div class="prose max-w-none" :class="darkMode ? 'prose-invert' : ''">
                                            <p class="whitespace-pre-wrap" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{!! nl2br(e($answer->answer)) !!}</p>
                                        </div>
                                        <div class="mt-4 flex items-center gap-4">
                                            <span class="px-3 py-1 rounded-lg text-sm shadow-sm" :class="darkMode ? 'glass text-gray-400' : 'bg-white text-gray-600 border border-gray-200'">
                                                <i class="fas fa-file-word mr-1"></i>
                                                {{ str_word_count($answer->answer) }} words
                                            </span>
                                        </div>
                                    @else
                                        <p class="italic" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">No answer provided for this task.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @elseif($attempt->testSet->section->name === 'speaking')
                        <div class="space-y-4">
                            @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                <div class="rounded-xl p-6 shadow-sm" :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <h4 class="font-semibold mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        <i class="fas fa-microphone text-[#C8102E] mr-2"></i>
                                        Part {{ $answer->question->order_number }}
                                    </h4>
                                    
                                    @if($answer->speakingRecording)
                                        @php
                                            // Get proper audio URL
                                            $audioUrl = route('audio.stream', $answer->speakingRecording->id);
                                        @endphp

                                        <div class="mb-3">
                                            <audio controls class="w-full rounded-lg" preload="metadata" id="audio-{{ $answer->id }}">
                                                <source src="{{ $audioUrl }}" type="{{ $answer->speakingRecording->mime_type ?? 'audio/webm' }}">
                                                <source src="{{ $audioUrl }}" type="audio/webm">
                                                <source src="{{ $audioUrl }}" type="audio/mpeg">
                                                <source src="{{ $audioUrl }}" type="audio/ogg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                        
                                        {{-- Add JavaScript to handle errors --}}
                                        <script>
                                            document.getElementById('audio-{{ $answer->id }}').addEventListener('error', function(e) {
                                                console.error('Audio load error for answer {{ $answer->id }}:', e);
                                                console.log('Audio URL:', '{{ $audioUrl }}');
                                                console.log('Error details:', e.target.error);
                                            });
                                        </script>
                                    @else
                                        <p class="italic" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">No recording available.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    {{-- AI Evaluation Modal --}}
    <div id="aiEvalModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="rounded-2xl max-w-md w-full p-8 shadow-2xl" :class="darkMode ? 'glass-dark border border-white/10' : 'bg-white border border-gray-200'">
            <h3 class="text-2xl font-bold mb-6 text-center" :class="darkMode ? 'text-white' : 'text-gray-900'">Starting Instant Evaluation</h3>
            <div class="flex items-center justify-center py-8">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full border-4 border-[#C8102E]/20 border-t-[#C8102E] animate-spin"></div>
                    <i class="fas fa-robot text-[#C8102E] text-2xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
                </div>
            </div>
            <p class="text-center" :class="darkMode ? 'text-gray-300' : 'text-gray-700'" id="eval-status">Analyzing your response...</p>
            <p class="text-center text-sm mt-2" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">This may take 15-30 seconds</p>
        </div>
    </div>

    @push('scripts')
    <script>
    // Scroll to question analysis section on page change
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page');
        
        if (page && page !== '1') {
            // Find the question analysis section
            const analysisSection = document.getElementById('question-analysis');
            if (analysisSection) {
                // Scroll to the section with some offset
                const offsetTop = analysisSection.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }
    });
    
    function switchTab(tab) {
        // Update tab buttons
        document.getElementById('ai-tab').classList.toggle('border-[#C8102E]', tab === 'ai');
        document.getElementById('ai-tab').classList.toggle('text-[#C8102E]', tab === 'ai');
        document.getElementById('ai-tab').classList.toggle('text-gray-400', tab !== 'ai');
        document.getElementById('ai-tab').classList.toggle('border-transparent', tab !== 'ai');

        document.getElementById('human-tab').classList.toggle('border-[#C8102E]', tab === 'human');
        document.getElementById('human-tab').classList.toggle('text-[#C8102E]', tab === 'human');
        document.getElementById('human-tab').classList.toggle('text-gray-400', tab !== 'human');
        document.getElementById('human-tab').classList.toggle('border-transparent', tab !== 'human');

        // Toggle content
        document.getElementById('ai-content').classList.toggle('hidden', tab !== 'ai');
        document.getElementById('human-content').classList.toggle('hidden', tab !== 'human');
    }
    
    function startAIEvaluation(attemptId, type) {
        document.getElementById('aiEvalModal').classList.remove('hidden');
        
        const button = document.getElementById('ai-eval-btn');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        
        const endpoint = `/ai/evaluate/${type}`;
        
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                attempt_id: attemptId
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || `HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('eval-status').innerHTML = '<i class="fas fa-check-circle text-green-500 mr-2"></i>Evaluation completed! Redirecting...';
                
                setTimeout(() => {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                }, 1500);
            } else {
                throw new Error(data.error || 'Failed to evaluate');
            }
        })
        .catch(error => {
            document.getElementById('aiEvalModal').classList.add('hidden');
            alert(error.message || 'An error occurred. Please try again.');
            
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-magic mr-2"></i> Get Instant Evaluation';
        });
    }
    </script>
    @endpush
    </div>
</x-student-layout>
