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

                        <div class="flex items-center gap-4">
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
            
            {{-- Retake Test Button (Alternative Position) --}}
            @php
                $canRetake = $attempt->status === 'completed';
                $latestAttempt = \App\Models\StudentAttempt::getLatestAttempt($attempt->user_id, $attempt->test_set_id);
                $isLatestAttempt = $latestAttempt && $attempt->id === $latestAttempt->id;
            @endphp
            
            @if($canRetake && $isLatestAttempt)
                <div class="glass rounded-xl p-6 mb-8 text-center">
                    <p class="text-gray-300 mb-4">Want to improve your score? You can retake this test.</p>
                    <form action="{{ route('student.results.retake', $attempt) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" 
                                class="px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105 flex items-center">
                            <i class="fas fa-redo mr-2"></i>
                            Retake This Test
                        </button>
                    </form>
                </div>
            @endif

            {{-- Evaluation Section for Writing/Speaking --}}
            @if(isset($attempt->testSet) && isset($attempt->testSet->section) && in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                <!-- Evaluation Tabs -->
                <div class="glass rounded-2xl p-6 mb-8">
                    <div class="flex border-b border-white/10 mb-6">
                        <button onclick="switchTab('ai')" id="ai-tab" class="px-6 py-3 text-white font-medium border-b-2 border-purple-500 transition-all">
                            <i class="fas fa-robot mr-2"></i> AI Evaluation
                        </button>
                        <button onclick="switchTab('human')" id="human-tab" class="px-6 py-3 text-gray-400 font-medium border-b-2 border-transparent hover:text-white transition-all">
                            <i class="fas fa-user-tie mr-2"></i> Human Evaluation
                        </button>
                    </div>
                    
                    <!-- AI Evaluation Tab Content -->
                    <div id="ai-content" class="">
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
                    
                    <!-- Human Evaluation Tab Content -->
                    <div id="human-content" class="hidden">
                        <h3 class="text-xl font-bold text-white mb-4">
                            <i class="fas fa-user-tie text-purple-400 mr-2"></i>
                            Human Evaluation
                        </h3>
                        
                        @if(isset($humanEvaluationRequest) && $humanEvaluationRequest)
                            @if($humanEvaluationRequest->status === 'completed')
                                <!-- Completed Evaluation -->
                                <div class="glass rounded-xl p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="text-gray-400 text-sm">Evaluated by</p>
                                            <p class="text-xl font-bold text-white">{{ $humanEvaluationRequest->teacher->user->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-gray-400 text-sm">Band Score</p>
                                            <p class="text-3xl font-bold text-white">{{ $humanEvaluationRequest->humanEvaluation->overall_band_score ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.evaluation.result', $attempt->id) }}" 
                                       class="block w-full text-center py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
                                        <i class="fas fa-eye mr-2"></i> View Detailed Evaluation
                                    </a>
                                </div>
                            @else
                                <!-- Pending Evaluation -->
                                <div class="glass rounded-xl p-6 bg-yellow-500/10 border-yellow-500/30">
                                    <div class="flex items-start gap-4">
                                        <i class="fas fa-clock text-yellow-400 text-2xl"></i>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-yellow-400 mb-2">Evaluation In Progress</h4>
                                            <p class="text-gray-300 mb-2">Your evaluation request has been assigned to <strong>{{ $humanEvaluationRequest->teacher->user->name }}</strong>.</p>
                                            <p class="text-gray-400 text-sm">Status: <span class="text-yellow-400">{{ ucfirst($humanEvaluationRequest->status) }}</span></p>
                                            <p class="text-gray-400 text-sm">Deadline: {{ $humanEvaluationRequest->deadline_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.evaluation.status', $attempt->id) }}" 
                                       class="block w-full text-center mt-4 py-3 rounded-xl glass text-white hover:border-purple-500/50 transition-all">
                                        <i class="fas fa-info-circle mr-2"></i> View Status Details
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- No Evaluation Yet -->
                            <div class="text-center py-8">
                                <i class="fas fa-user-tie text-6xl text-purple-400 mb-4"></i>
                                <p class="text-gray-300 mb-4">Get your {{ $attempt->testSet->section->name }} evaluated by certified IELTS teachers for detailed feedback and accurate band score.</p>
                                
                                <div class="glass rounded-xl p-4 inline-block mb-6">
                                    <p class="text-gray-400 text-sm mb-1">Starting from</p>
                                    <p class="text-2xl font-bold text-white">10 <span class="text-purple-400">tokens</span></p>
                                </div>
                                
                                <a href="{{ route('student.evaluation.teachers', $attempt->id) }}" 
                                   class="block w-full sm:w-auto mx-auto px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105">
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
                <div class="glass rounded-2xl p-6 mb-8">
                    <h3 class="text-xl font-bold text-white mb-6">
                        <i class="fas fa-chart-pie text-blue-400 mr-2"></i>
                        Score Breakdown
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-gray-400 text-sm mb-2">Questions Attempted</p>
                            <p class="text-2xl font-bold text-white">
                                {{ $correctAnswers + ($totalQuestions - $correctAnswers) }}
                                <span class="text-lg text-gray-400">/ {{ $totalQuestions }}</span>
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-gray-400 text-sm mb-2">Correct Answers</p>
                            <p class="text-2xl font-bold text-green-400">
                                {{ $correctAnswers }}
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-gray-400 text-sm mb-2">Accuracy</p>
                            <p class="text-2xl font-bold text-blue-400">
                                {{ number_format($accuracy, 1) }}%
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
                <div id="question-analysis" class="glass rounded-2xl p-6 relative">
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
                                            
                                            // Get all answers for this master question
                                            $masterAnswers = $attempt->answers->filter(function($answer) use ($question) {
                                                return $answer->question_id == $question->id;
                                            });
                                            
                                            // Create display for each sub-question
                                            foreach ($mappings as $mapping) {
                                                $subQuestionNum = $mapping['question'] ?? $mapping['number'] ?? $currentNumber;
                                                $paragraphLabel = $mapping['paragraph'] ?? chr(65 + array_search($mapping, $mappings));
                                                
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
                                                    'correct_letter' => $mapping['correct'] ?? null
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
                                    } else {
                                        // Regular question
                                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                                        $displayQuestions[] = [
                                            'number' => $currentNumber,
                                            'question' => $question,
                                            'content' => $question->content,
                                            'answer' => $answer,
                                            'is_master_sub' => false
                                        ];
                                        
                                        // Count blanks for numbering
                                        $blankCount = $question->countBlanks();
                                        $currentNumber += $blankCount > 0 ? $blankCount : 1;
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
                                    
                                    if ($isAnswered && $answer) {
                                        if (isset($item['is_master_sub']) && $item['is_master_sub']) {
                                            // Master matching heading sub-question
                                            $decoded = json_decode($answer->answer, true);
                                            $selectedLetter = $decoded['selected_letter'] ?? null;
                                            $displayAnswer = $selectedLetter ? "Option {$selectedLetter}" : 'No answer';
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
                                            // Check if it's JSON (fill-in-the-blank)
                                            $answerData = @json_decode($answer->answer, true);
                                            if (is_array($answerData)) {
                                                $displayParts = [];
                                                foreach ($answerData as $key => $value) {
                                                    if (!empty($value)) {
                                                        $displayParts[] = $value;
                                                    }
                                                }
                                                $displayAnswer = implode(', ', $displayParts);
                                                
                                                // For fill-in-the-blank, we need to check against section_specific_data
                                                if ($question->section_specific_data && isset($question->section_specific_data['blank_answers'])) {
                                                    $allBlanksCorrect = true;
                                                    foreach ($question->section_specific_data['blank_answers'] as $num => $correctAnswer) {
                                                        $studentAnswer = $answerData['blank_' . $num] ?? '';
                                                        if (strtolower(trim($studentAnswer)) !== strtolower(trim($correctAnswer))) {
                                                            $allBlanksCorrect = false;
                                                            break;
                                                        }
                                                    }
                                                    $isCorrect = $allBlanksCorrect;
                                                }
                                            } else {
                                                $displayAnswer = $answer->answer;
                                            }
                                        }
                                    }
                                @endphp
                                
                                <div class="glass rounded-lg p-4 {{ !$isAnswered ? 'opacity-60' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="glass px-3 py-1 rounded-lg text-sm font-medium text-white">
                                                    Q{{ $item['number'] }}
                                                </span>
                                                <p class="text-gray-300 text-sm flex-1">
                                                    {!! Str::limit(strip_tags($item['content']), 100) !!}
                                                </p>
                                            </div>
                                            
                                            <div class="text-sm">
                                                <p class="text-gray-400">
                                                    Your answer: 
                                                    <span class="text-white">
                                                        @if(!$isAnswered)
                                                            <span class="text-orange-400">Not attempted</span>
                                                        @else
                                                            {{ $displayAnswer }}
                                                        @endif
                                                    </span>
                                                </p>
                                                
                                                {{-- Show correct answer for premium users --}}
                                                @if($isPremium && $isAnswered && !$isCorrect)
                                                    <p class="text-gray-400 mt-1">
                                                        Correct answer: 
                                                        <span class="text-green-400">
                                                            @if(isset($item['is_master_sub']) && $item['is_master_sub'])
                                                            Option {{ $item['correct_letter'] }}
                                                            @elseif(isset($item['is_sentence_completion']) && $item['is_sentence_completion'])
                                                            Option {{ $item['correct_answer'] }}
                                                            @elseif($question->correctOption())
                                                                {{ $question->correctOption()->content }}
                                                            @elseif($question->section_specific_data && isset($question->section_specific_data['blank_answers']))
                                                                @php
                                                                    $blankAnswers = $question->getBlankAnswersArray();
                                                                @endphp
                                                                @if(!empty($blankAnswers))
                                                                    {{ implode(', ', $blankAnswers) }}
                                                                @else
                                                                    {{ implode(', ', $question->section_specific_data['blank_answers']) }}
                                                                @endif
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
                                                <span class="text-orange-400">
                                                    <i class="fas fa-minus-circle text-xl"></i>
                                                </span>
                                            @elseif($isCorrect)
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
                        
                        {{-- Pagination Controls --}}
                        @if(count($displayQuestions) > $perPage)
                            @php
                                $totalPages = ceil(count($displayQuestions) / $perPage);
                                $startIndex = ($currentPage - 1) * $perPage;
                                $endIndex = min($startIndex + $perPage, count($displayQuestions));
                            @endphp
                            
                            <div class="mt-6 border-t border-white/10 pt-6">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <p class="text-gray-400 text-sm text-center sm:text-left">
                                        Showing {{ $startIndex + 1 }} to {{ $endIndex }} of {{ count($displayQuestions) }} questions
                                    </p>
                                    
                                    <div class="flex items-center gap-2">
                                        {{-- Previous Button --}}
                                        @if($currentPage > 1)
                                            <a href="?page={{ $currentPage - 1 }}" 
                                               class="glass px-4 py-2 rounded-lg text-white hover:border-purple-500/50 transition-all flex items-center gap-2">
                                                <i class="fas fa-chevron-left"></i>
                                                Previous
                                            </a>
                                        @else
                                            <button disabled 
                                                    class="glass px-4 py-2 rounded-lg text-gray-500 opacity-50 cursor-not-allowed flex items-center gap-2">
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
                                                    <span class="glass px-3 py-2 rounded-lg bg-purple-600/30 text-white font-medium">
                                                        {{ $pageNum }}
                                                    </span>
                                                @else
                                                    <a href="?page={{ $pageNum }}" 
                                                       class="glass px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:border-purple-500/50 transition-all">
                                                        {{ $pageNum }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        {{-- Mobile Page Indicator --}}
                                        <div class="flex sm:hidden items-center gap-2">
                                            <span class="glass px-3 py-2 rounded-lg text-white">
                                                {{ $currentPage }} / {{ $totalPages }}
                                            </span>
                                        </div>
                                        
                                        {{-- Next Button --}}
                                        @if($currentPage < $totalPages)
                                            <a href="?page={{ $currentPage + 1 }}" 
                                               class="glass px-4 py-2 rounded-lg text-white hover:border-purple-500/50 transition-all flex items-center gap-2">
                                                Next
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        @else
                                            <button disabled 
                                                    class="glass px-4 py-2 rounded-lg text-gray-500 opacity-50 cursor-not-allowed flex items-center gap-2">
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
                                        @php
                                            // Get proper audio URL
                                            // Use proxy route for better compatibility
                                            $audioUrl = route('audio.stream', $answer->speakingRecording->id);
                                            $directUrl = $answer->speakingRecording->file_url;
                                            
                                            // Debug info
                                            $debugInfo = [
                                                'file_path' => $answer->speakingRecording->file_path,
                                                'direct_url' => $directUrl,
                                                'proxy_url' => $audioUrl,
                                                'storage_disk' => $answer->speakingRecording->storage_disk,
                                                'mime_type' => $answer->speakingRecording->mime_type,
                                            ];
                                        @endphp
                                        
                                        <div class="mb-3">
                                            <audio controls class="w-full" preload="metadata" id="audio-{{ $answer->id }}">
                                                <source src="{{ $audioUrl }}" type="{{ $answer->speakingRecording->mime_type ?? 'audio/webm' }}">
                                                <source src="{{ $audioUrl }}" type="audio/webm">
                                                <source src="{{ $audioUrl }}" type="audio/mpeg">
                                                <source src="{{ $audioUrl }}" type="audio/ogg">
                                                Your browser does not support the audio element.
                                            </audio>
                                            
                                            <div class="mt-2 text-xs text-gray-500">
                                                <p>Storage: {{ strtoupper($answer->speakingRecording->storage_disk) }}</p>
                                                <p>Size: {{ $answer->speakingRecording->formatted_size }}</p>
                                                @if(config('app.debug'))
                                                    <details class="mt-1">
                                                        <summary class="cursor-pointer text-purple-400">Debug Info</summary>
                                                        <pre class="text-xs bg-black/20 p-2 rounded mt-1">{{ json_encode($debugInfo, JSON_PRETTY_PRINT) }}</pre>
                                                    </details>
                                                @endif
                                            </div>
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
            <h3 class="text-2xl font-bold text-white mb-6 text-center">Starting Instant Evaluation</h3>
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
        document.getElementById('ai-tab').classList.toggle('border-purple-500', tab === 'ai');
        document.getElementById('ai-tab').classList.toggle('text-white', tab === 'ai');
        document.getElementById('ai-tab').classList.toggle('text-gray-400', tab !== 'ai');
        document.getElementById('ai-tab').classList.toggle('border-transparent', tab !== 'ai');
        
        document.getElementById('human-tab').classList.toggle('border-purple-500', tab === 'human');
        document.getElementById('human-tab').classList.toggle('text-white', tab === 'human');
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
