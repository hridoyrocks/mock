{{-- resources/views/student/results/show.blade.php --}}
<x-student-layout>
    <x-slot:title>Test Result Details</x-slot>

    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
               

                <!-- Test Info Header -->
                <div class="glass rounded-2xl p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            @php
                                $sectionIcons = [
                                    'listening' => 'fa-headphones',
                                    'reading' => 'fa-book-open',
                                    'writing' => 'fa-pen-fancy',
                                    'speaking' => 'fa-microphone'
                                ];
                                $sectionColors = [
                                    'listening' => 'from-violet-500 to-purple-500',
                                    'reading' => 'from-blue-500 to-cyan-500',
                                    'writing' => 'from-green-500 to-emerald-500',
                                    'speaking' => 'from-rose-500 to-pink-500'
                                ];
                                $icon = $sectionIcons[$attempt->testSet->section->name] ?? 'fa-question';
                                $gradient = $sectionColors[$attempt->testSet->section->name] ?? 'from-gray-500 to-gray-600';
                            @endphp
                            
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center">
                                <i class="fas {{ $icon }} text-white text-2xl"></i>
                            </div>
                            
                            <div>
                                <h1 class="text-2xl lg:text-3xl font-bold text-white">
                                    {{ $attempt->testSet->title }}
                                </h1>
                                <p class="text-gray-400 capitalize">{{ $attempt->testSet->section->name }} Section</p>
                            </div>
                        </div>

                        <!-- Band Score Display -->
                        @if($attempt->band_score)
                            <div class="glass rounded-xl px-8 py-6 text-center border-purple-500/30">
                                <p class="text-gray-400 text-sm mb-1">Band Score</p>
                                <p class="text-4xl font-bold text-white">
                                    {{ number_format($attempt->band_score, 1) }}
                                    @if(!$attempt->is_complete_attempt)
                                        <span class="text-lg text-purple-400">*</span>
                                    @endif
                                </p>
                                @if(!$attempt->is_complete_attempt)
                                    <p class="text-xs text-purple-400 mt-1">Projected</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-4">
        <div class="max-w-7xl mx-auto">
            {{-- Score Details Alert --}}
            @if(session('score_details') && !session('score_details')['is_reliable'])
                <div class="glass rounded-xl p-4 mb-6 border-yellow-500/30">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mt-1"></i>
                        <div class="flex-1">
                            <h4 class="text-yellow-400 font-semibold mb-1">Partial Test Completion</h4>
                            <p class="text-gray-300 text-sm">
                                You answered only <strong class="text-white">{{ session('score_details')['answered'] }}/{{ session('score_details')['total'] }}</strong> questions 
                                ({{ session('score_details')['completion_percentage'] }}%)
                            </p>
                            <p class="text-gray-400 text-sm mt-1">{{ session('score_details')['message'] }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="glass rounded-xl p-6 text-center">
                    <i class="fas fa-calendar-alt text-purple-400 text-2xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Date Taken</p>
                    <p class="text-white font-semibold">{{ $attempt->created_at->format('M d, Y') }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $attempt->created_at->format('g:i A') }}</p>
                </div>

                <div class="glass rounded-xl p-6 text-center">
                    <i class="fas fa-stopwatch text-blue-400 text-2xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Time Spent</p>
                    @php
                        $startTime = $attempt->start_time;
                        $endTime = $attempt->end_time ?? $attempt->updated_at;
                        $totalSeconds = $startTime->diffInSeconds($endTime);
                        $minutes = floor($totalSeconds / 60);
                        $seconds = $totalSeconds % 60;
                    @endphp
                    <p class="text-white font-semibold">{{ $minutes }}m {{ $seconds }}s</p>
                </div>

                <div class="glass rounded-xl p-6 text-center">
                    <i class="fas fa-tasks text-green-400 text-2xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Completion</p>
                    <p class="text-white font-semibold">{{ $attempt->completion_rate }}%</p>
                    <div class="w-full h-2 bg-white/10 rounded-full mt-2 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" 
                             style="width: {{ $attempt->completion_rate }}%"></div>
                    </div>
                </div>

                <div class="glass rounded-xl p-6 text-center">
                    <i class="fas fa-shield-alt text-pink-400 text-2xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Confidence</p>
                    <p class="text-white font-semibold">{{ $attempt->confidence_level ?? 'N/A' }}</p>
                    @if($attempt->confidence_level)
                        <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full
                            @if($attempt->confidence_level == 'Very High') bg-green-500/20 text-green-400
                            @elseif($attempt->confidence_level == 'High') bg-blue-500/20 text-blue-400
                            @elseif($attempt->confidence_level == 'Medium') bg-yellow-500/20 text-yellow-400
                            @else bg-red-500/20 text-red-400
                            @endif">
                            {{ $attempt->confidence_level }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- AI Evaluation Section for Writing/Speaking --}}
            @if(in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                <div class="glass rounded-2xl p-6 mb-8 border-purple-500/30">
                    <h3 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-robot text-purple-400 mr-2"></i>
                        AI Evaluation
                    </h3>
                    
                    @if(auth()->user()->hasFeature('ai_' . $attempt->testSet->section->name . '_evaluation'))
                        @if($attempt->completion_rate == 0)
                            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-exclamation-circle text-yellow-400 text-xl mt-1"></i>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-yellow-400">Test Not Completed</h4>
                                        <p class="text-gray-300 text-sm mt-1">
                                            You need to complete the {{ $attempt->testSet->section->name }} test before requesting AI evaluation.
                                        </p>
                                        <a href="{{ route('student.' . $attempt->testSet->section->name . '.start', $attempt->testSet) }}" 
                                           class="inline-flex items-center mt-3 glass px-4 py-2 rounded-lg text-yellow-400 hover:border-yellow-500/50 transition-all text-sm">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Go Back to Test
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif(!$attempt->ai_evaluated_at)
                            <div class="text-center py-6">
                                <i class="fas fa-robot text-6xl text-purple-400 mb-4"></i>
                                <p class="text-gray-300 mb-4">Get instant feedback and band score prediction with our advanced AI evaluator.</p>
                                
                                @if($attempt->completion_rate > 0)
                                    <div class="inline-flex items-center glass px-4 py-2 rounded-lg text-green-400 border-green-500/30 mb-4">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Test completed with {{ $attempt->completion_rate }}% completion rate
                                    </div>
                                @endif
                                
                                <button onclick="startAIEvaluation({{ $attempt->id }}, '{{ $attempt->testSet->section->name }}')" 
                                        id="ai-eval-btn"
                                        class="block w-full sm:w-auto mx-auto px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105">
                                    <i class="fas fa-magic mr-2"></i>
                                    Get Instant Evaluation
                                </button>
                            </div>
                        @else
                            <div class="flex items-center justify-between glass rounded-xl p-4">
                                <div>
                                    <p class="text-gray-400 text-sm">Band Score</p>
                                    <p class="text-3xl font-bold text-white">{{ $attempt->ai_band_score ?? 'N/A' }}</p>
                                </div>
                                <a href="{{ route('ai.evaluation.get', $attempt->id) }}" 
                                   class="glass px-6 py-3 rounded-xl text-white hover:border-purple-500/50 transition-all">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    View Detailed Analysis
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-crown text-6xl text-purple-400 mb-4"></i>
                            <p class="text-gray-300 mb-4">Upgrade to Premium to unlock Instant evaluation for instant feedback.</p>
                            <a href="{{ route('subscription.plans') }}" 
                               class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
                                <i class="fas fa-rocket mr-2"></i>
                                Upgrade to Premium
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Score Breakdown for Listening/Reading --}}
            @if(in_array($attempt->testSet->section->name, ['listening', 'reading']))
                <div class="glass rounded-2xl p-6 mb-8">
                    <h3 class="text-xl font-bold text-white mb-6">
                        <i class="fas fa-chart-pie text-blue-400 mr-2"></i>
                        Score Breakdown
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-gray-400 text-sm mb-2">Questions Attempted</p>
                            <p class="text-2xl font-bold text-white">
                                {{ $attempt->answered_questions ?? count($attempt->answers) }}
                                <span class="text-lg text-gray-400">/ {{ $totalQuestions ?? $attempt->testSet->questions()->where('question_type', '!=', 'passage')->count() }}</span>
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-gray-400 text-sm mb-2">Correct Answers</p>
                            <p class="text-2xl font-bold text-green-400">
                                {{ $correctAnswers ?? $attempt->correct_answers }}
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-gray-400 text-sm mb-2">Accuracy</p>
                            <p class="text-2xl font-bold text-blue-400">
                                @if($attempt->answered_questions > 0)
                                    {{ number_format(($attempt->correct_answers / $attempt->answered_questions) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-gray-400 text-sm mb-2">Band Score</p>
                            <p class="text-2xl font-bold text-purple-400">
                                {{ $attempt->band_score }}
                                @if(!$attempt->is_complete_attempt)
                                    <span class="text-sm">*</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Band Score Visual --}}
                    <div class="mt-6">
                        <p class="text-gray-400 text-sm mb-3">Band Score Progress</p>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-2">
                                @foreach([1, 2, 3, 4, 5, 6, 7, 8, 9] as $band)
                                    <span class="text-xs {{ $attempt->band_score >= $band ? 'text-purple-400' : 'text-gray-600' }}">{{ $band }}</span>
                                @endforeach
                            </div>
                            <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-1000"
                                     style="width: {{ ($attempt->band_score / 9) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Question Analysis --}}
                <div class="glass rounded-2xl p-6 relative">
                    <h3 class="text-xl font-bold text-white mb-6">
                        <i class="fas fa-microscope text-purple-400 mr-2"></i>
                        Question Analysis
                    </h3>

                    @php
                        $isPremium = auth()->user()->hasActiveSubscription() && !auth()->user()->hasPlan('free');
                    @endphp
                    
                    @if(!$isPremium)
                        <div class="absolute inset-0 z-10 glass backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <div class="text-center max-w-md p-6">
                                <i class="fas fa-lock text-purple-400 text-6xl mb-4"></i>
                                <h4 class="text-xl font-bold text-white mb-2">Premium Feature</h4>
                                <p class="text-gray-300 mb-4">
                                    Unlock detailed question analysis with correct answers and explanations.
                                </p>
                                <a href="{{ route('subscription.plans') }}" 
                                   class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
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
                                    ->orderBy('order_number')
                                    ->get();
                                $answeredQuestions = $attempt->answers->pluck('question_id')->toArray();
                            @endphp
                            
                            @foreach($allQuestions->take(10) as $question)
                                @php
                                    $answer = $attempt->answers->where('question_id', $question->id)->first();
                                    $isAnswered = in_array($question->id, $answeredQuestions);
                                @endphp
                                
                                <div class="glass rounded-lg p-4 {{ !$isAnswered ? 'opacity-60' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="glass px-3 py-1 rounded-lg text-sm font-medium text-white">
                                                    Q{{ $question->order_number }}
                                                </span>
                                                <p class="text-gray-300 text-sm flex-1">
                                                    {!! Str::limit(strip_tags($question->content), 100) !!}
                                                </p>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 text-sm">
                                                <span class="text-gray-400">
                                                    Your answer: 
                                                    <span class="text-white">
                                                        @if($isAnswered && $answer)
                                                            {{ $answer->selectedOption ? $answer->selectedOption->content : ($answer->answer ?: 'No answer') }}
                                                        @else
                                                            <span class="text-orange-400">Not attempted</span>
                                                        @endif
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-4">
                                            @if(!$isAnswered)
                                                <span class="text-orange-400">
                                                    <i class="fas fa-minus-circle text-xl"></i>
                                                </span>
                                            @elseif($answer && $answer->selectedOption && $answer->selectedOption->is_correct)
                                                <span class="text-green-400">
                                                    <i class="fas fa-check-circle text-xl"></i>
                                                </span>
                                            @else
                                                <span class="text-red-400">
                                                    <i class="fas fa-times-circle text-xl"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($allQuestions->count() > 10)
                            <p class="text-center text-gray-400 text-sm mt-4">
                                Showing 10 of {{ $allQuestions->count() }} questions
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Writing/Speaking Submission --}}
            @if(in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                <div class="glass rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6">
                        <i class="fas fa-file-alt text-green-400 mr-2"></i>
                        Your Submission
                    </h3>
                    
                    @if($attempt->testSet->section->name === 'writing')
                        <div class="space-y-6">
                            @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                <div class="glass rounded-xl p-6">
                                    <h4 class="font-semibold text-white mb-3">
                                        <i class="fas fa-tasks text-purple-400 mr-2"></i>
                                        Task {{ $answer->question->order_number }}
                                    </h4>
                                    @if(!empty($answer->answer))
                                        <div class="prose prose-invert max-w-none">
                                            <p class="text-gray-300 whitespace-pre-wrap">{!! nl2br(e($answer->answer)) !!}</p>
                                        </div>
                                        <div class="mt-4 flex items-center gap-4">
                                            <span class="glass px-3 py-1 rounded-lg text-sm text-gray-400">
                                                <i class="fas fa-file-word mr-1"></i>
                                                {{ str_word_count($answer->answer) }} words
                                            </span>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">No answer provided for this task.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @elseif($attempt->testSet->section->name === 'speaking')
                        <div class="space-y-4">
                            @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                <div class="glass rounded-xl p-6">
                                    <h4 class="font-semibold text-white mb-3">
                                        <i class="fas fa-microphone text-purple-400 mr-2"></i>
                                        Part {{ $answer->question->order_number }}
                                    </h4>
                                    
                                    @if($answer->speakingRecording)
                                        <audio controls class="w-full">
                                            <source src="{{ asset('storage/' . $answer->speakingRecording->file_path) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    @else
                                        <p class="text-gray-500 italic">No recording available.</p>
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
        <div class="glass rounded-2xl max-w-md w-full p-8">
            <h3 class="text-2xl font-bold text-white mb-6 text-center">Starting Insant Evaluation</h3>
            <div class="flex items-center justify-center py-8">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full border-4 border-purple-500/20 border-t-purple-500 animate-spin"></div>
                    <i class="fas fa-robot text-purple-400 text-2xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
                </div>
            </div>
            <p class="text-center text-gray-300" id="eval-status">Analyzing your response...</p>
            <p class="text-center text-sm text-gray-500 mt-2">This may take 15-30 seconds</p>
        </div>
    </div>

    @push('scripts')
    <script>
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
                document.getElementById('eval-status').innerHTML = '<i class="fas fa-check-circle text-green-400 mr-2"></i>Evaluation completed! Redirecting...';
                
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
</x-student-layout>