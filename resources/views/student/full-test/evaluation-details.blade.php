{{-- resources/views/student/full-test/evaluation-details.blade.php --}}
<x-student-layout>
    <x-slot:title>Full Test Evaluation Details</x-slot>

    <section class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('student.full-test.results', $fullTestAttempt) }}"
                   class="inline-flex items-center text-sm text-gray-400 hover:text-white mb-4 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Results
                </a>

                <h1 class="text-2xl font-bold text-white mb-2">Detailed Evaluation Results</h1>
                <p class="text-gray-400">{{ $fullTestAttempt->fullTest->title }}</p>
                <p class="text-gray-500 text-sm mt-1">
                    {{ $fullTestAttempt->end_time ? $fullTestAttempt->end_time->format('M d, Y h:i A') : 'In Progress' }}
                </p>
            </div>

            <!-- Overall Score Summary -->
            <div class="glass-dark border border-white/10 rounded-xl p-6 mb-6">
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-400 uppercase tracking-wide mb-3">Overall Band Score</p>
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-[#C8102E]/10 border-2 border-[#C8102E]/30">
                        @if($fullTestAttempt->overall_band_score !== null)
                            <span class="text-3xl font-bold text-[#C8102E]">
                                {{ number_format($fullTestAttempt->overall_band_score, 1) }}
                            </span>
                        @else
                            <span class="text-xs font-medium text-yellow-400 px-3 text-center">
                                Pending<br>Evaluation
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Section Scores Grid -->
                @php
                    $sections = [
                        'listening' => ['icon' => 'fa-headphones', 'label' => 'Listening'],
                        'reading' => ['icon' => 'fa-book-open', 'label' => 'Reading'],
                        'writing' => ['icon' => 'fa-pen-fancy', 'label' => 'Writing'],
                        'speaking' => ['icon' => 'fa-microphone', 'label' => 'Speaking']
                    ];
                    $availableSections = $fullTestAttempt->fullTest->getAvailableSections();
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($sections as $sectionKey => $sectionData)
                        @if(in_array($sectionKey, $availableSections))
                            @php
                                $scoreField = $sectionKey . '_score';
                                $score = $fullTestAttempt->$scoreField;
                            @endphp
                            <div class="glass border border-white/5 rounded-lg p-4 text-center">
                                <i class="fas {{ $sectionData['icon'] }} text-[#C8102E] text-lg mb-2"></i>
                                <p class="text-xs text-gray-400 mb-1">{{ $sectionData['label'] }}</p>
                                @if($score !== null)
                                    <p class="text-2xl font-bold text-white">{{ number_format($score, 1) }}</p>
                                @else
                                    <p class="text-xs font-medium text-yellow-400">Pending</p>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Section Details -->
            @foreach($fullTestAttempt->sectionAttempts as $sectionAttempt)
                @php
                    $studentAttempt = $sectionAttempt->studentAttempt;
                    $testSet = $studentAttempt->testSet;
                    $section = $testSet->section;
                    $sectionType = $sectionAttempt->section_type;
                    $isWritingOrSpeaking = in_array($sectionType, ['writing', 'speaking']);
                    $humanEvaluation = $studentAttempt->humanEvaluationRequest?->humanEvaluation;
                @endphp

                <div class="glass-dark border border-white/10 rounded-xl p-6 mb-6">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-[#C8102E]/10 flex items-center justify-center mr-4">
                                <i class="fas {{ $sections[$sectionType]['icon'] ?? 'fa-file-alt' }} text-[#C8102E] text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white capitalize">{{ $sectionType }}</h2>
                                <p class="text-sm text-gray-400">{{ $testSet->title }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-400">Band Score</p>
                            @if($studentAttempt->band_score !== null)
                                <p class="text-2xl font-bold text-[#C8102E]">{{ number_format($studentAttempt->band_score, 1) }}</p>
                            @else
                                <p class="text-sm font-medium text-yellow-400">Pending</p>
                            @endif
                        </div>
                    </div>

                    @if($isWritingOrSpeaking && $humanEvaluation)
                        <!-- Writing/Speaking Human Evaluation -->
                        <div class="space-y-6">

                            <!-- Overall Feedback -->
                            <div class="glass border border-white/5 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-white mb-4">Overall Assessment</h3>

                                <!-- Band Score Breakdown -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                    @if($sectionType === 'writing')
                                        @php
                                            $firstTask = collect($humanEvaluation->task_scores)->first();
                                        @endphp
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Task Achievement</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['task_achievement'] ?? 0, 1) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Coherence/Cohesion</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['coherence_cohesion'] ?? 0, 1) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Lexical Resource</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['lexical_resource'] ?? 0, 1) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Grammar</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['grammar'] ?? 0, 1) }}</p>
                                        </div>
                                    @else
                                        @php
                                            $firstTask = collect($humanEvaluation->task_scores)->first();
                                        @endphp
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Fluency/Coherence</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['fluency_coherence'] ?? 0, 1) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Lexical Resource</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['lexical_resource'] ?? 0, 1) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Grammar</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['grammar'] ?? 0, 1) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">Pronunciation</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($firstTask['pronunciation'] ?? 0, 1) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Task-by-Task Feedback -->
                            @foreach($humanEvaluation->task_scores as $taskIndex => $taskScore)
                                <div class="glass border border-white/5 rounded-lg p-4">
                                    <h4 class="text-md font-semibold text-white mb-3">
                                        {{ $sectionType === 'writing' ? 'Task ' . ($taskIndex + 1) : 'Part ' . ($taskIndex + 1) }}
                                        <span class="text-[#C8102E] ml-2">Band {{ number_format($taskScore['score'] ?? 0, 1) }}</span>
                                    </h4>

                                    <!-- Your Response -->
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-400 mb-2">Your Response:</p>
                                        <div class="glass bg-black/20 rounded-lg p-4">
                                            @php
                                                $answer = $studentAttempt->answers->where('question.task_number', $taskIndex + 1)->first();
                                            @endphp
                                            @if($answer)
                                                @if($sectionType === 'writing')
                                                    <p class="text-sm text-gray-300 whitespace-pre-wrap">{{ $answer->answer_text ?? 'No answer provided' }}</p>
                                                @else
                                                    @if($answer->speakingRecording)
                                                        <audio controls class="w-full">
                                                            <source src="{{ $answer->speakingRecording->audio_url }}" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                        @if($answer->speakingRecording->transcription)
                                                            <div class="mt-3 pt-3 border-t border-white/10">
                                                                <p class="text-xs text-gray-400 mb-1">Transcription:</p>
                                                                <p class="text-sm text-gray-300">{{ $answer->speakingRecording->transcription }}</p>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <p class="text-sm text-gray-400">No recording available</p>
                                                    @endif
                                                @endif
                                            @else
                                                <p class="text-sm text-gray-400">No answer provided</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Teacher's Feedback -->
                                    @if(isset($taskScore['feedback']))
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-400 mb-2">Teacher's Feedback:</p>
                                            <div class="glass bg-blue-500/5 border border-blue-500/20 rounded-lg p-4">
                                                <p class="text-sm text-gray-300 whitespace-pre-wrap">{{ $taskScore['feedback'] }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Error Markings (Writing only) -->
                                    @if($sectionType === 'writing' && $humanEvaluation->errorMarkings->isNotEmpty())
                                        @php
                                            $taskErrors = $humanEvaluation->errorMarkings->where('task_number', $taskIndex + 1);
                                        @endphp
                                        @if($taskErrors->isNotEmpty())
                                            <div>
                                                <p class="text-sm text-gray-400 mb-2">Error Markings:</p>
                                                <div class="space-y-2">
                                                    @foreach($taskErrors as $error)
                                                        <div class="glass bg-red-500/5 border border-red-500/20 rounded-lg p-3">
                                                            <div class="flex items-start justify-between">
                                                                <div class="flex-1">
                                                                    <p class="text-sm font-medium text-red-300">"{{ $error->marked_text }}"</p>
                                                                    <p class="text-xs text-gray-400 mt-1 capitalize">{{ str_replace('_', ' ', $error->error_type) }}</p>
                                                                    @if($error->comment)
                                                                        <p class="text-sm text-gray-300 mt-2">{{ $error->comment }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach

                            <!-- Strengths and Improvements -->
                            <div class="grid md:grid-cols-2 gap-4">
                                <!-- Strengths -->
                                <div class="glass border border-green-500/20 rounded-lg p-4">
                                    <h4 class="text-md font-semibold text-white mb-3 flex items-center">
                                        <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Strengths
                                    </h4>
                                    <ul class="space-y-2">
                                        @foreach($humanEvaluation->strengths as $strength)
                                            <li class="text-sm text-gray-300 flex items-start">
                                                <span class="text-green-400 mr-2">•</span>
                                                <span>{{ $strength }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Areas for Improvement -->
                                <div class="glass border border-yellow-500/20 rounded-lg p-4">
                                    <h4 class="text-md font-semibold text-white mb-3 flex items-center">
                                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Areas for Improvement
                                    </h4>
                                    <ul class="space-y-2">
                                        @foreach($humanEvaluation->improvements as $improvement)
                                            <li class="text-sm text-gray-300 flex items-start">
                                                <span class="text-yellow-400 mr-2">•</span>
                                                <span>{{ $improvement }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Evaluated By -->
                            <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                <div class="flex items-center">
                                    <img src="{{ $humanEvaluation->evaluator->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($humanEvaluation->evaluator->name) }}"
                                         alt="{{ $humanEvaluation->evaluator->name }}"
                                         class="w-10 h-10 rounded-full mr-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Evaluated by</p>
                                        <p class="text-sm font-medium text-white">{{ $humanEvaluation->evaluator->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400">Evaluated on</p>
                                    <p class="text-sm text-gray-300">{{ $humanEvaluation->evaluated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                    @elseif($isWritingOrSpeaking)
                        <!-- Pending Evaluation -->
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-white mb-2">Evaluation Pending</h3>
                            <p class="text-sm text-gray-400">This section is being evaluated by a teacher</p>
                        </div>

                    @else
                        <!-- Listening/Reading Auto-graded Results -->
                        <div class="space-y-4">
                            @php
                                $questions = $testSet->questions->where('question_type', '!=', 'passage')->sortBy('order_number');
                                $groupedQuestions = $questions->groupBy('task_number');
                            @endphp

                            @foreach($groupedQuestions as $taskNumber => $taskQuestions)
                                <div class="glass border border-white/5 rounded-lg p-4">
                                    <h4 class="text-md font-semibold text-white mb-3">Task {{ $taskNumber }}</h4>

                                    <div class="space-y-3">
                                        @foreach($taskQuestions as $question)
                                            @php
                                                $answer = $studentAttempt->answers->where('question_id', $question->id)->first();
                                                $isCorrect = $answer && $answer->is_correct;
                                            @endphp

                                            <div class="glass bg-black/20 rounded-lg p-3 {{ $isCorrect ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <p class="text-sm text-gray-400 mb-1">Question {{ $question->order_number }}</p>
                                                        <p class="text-sm text-white mb-2">{{ $question->question_text }}</p>

                                                        <div class="flex items-center gap-4 text-sm">
                                                            <div>
                                                                <span class="text-gray-400">Your answer:</span>
                                                                <span class="ml-1 {{ $isCorrect ? 'text-green-400' : 'text-red-400' }} font-medium">
                                                                    {{ $answer->answer_text ?? $answer->selectedOption?->option_text ?? 'No answer' }}
                                                                </span>
                                                            </div>
                                                            @if(!$isCorrect && $question->correct_answer)
                                                                <div>
                                                                    <span class="text-gray-400">Correct answer:</span>
                                                                    <span class="ml-1 text-green-400 font-medium">{{ $question->correct_answer }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="ml-4">
                                                        @if($isCorrect)
                                                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <!-- Statistics -->
                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div class="text-center glass border border-white/5 rounded-lg p-3">
                                    <p class="text-xs text-gray-400">Correct</p>
                                    <p class="text-xl font-bold text-green-400">{{ $studentAttempt->answers->where('is_correct', true)->count() }}</p>
                                </div>
                                <div class="text-center glass border border-white/5 rounded-lg p-3">
                                    <p class="text-xs text-gray-400">Incorrect</p>
                                    <p class="text-xl font-bold text-red-400">{{ $studentAttempt->answers->where('is_correct', false)->count() }}</p>
                                </div>
                                <div class="text-center glass border border-white/5 rounded-lg p-3">
                                    <p class="text-xs text-gray-400">Accuracy</p>
                                    <p class="text-xl font-bold text-white">
                                        @php
                                            $total = $studentAttempt->answers->count();
                                            $correct = $studentAttempt->answers->where('is_correct', true)->count();
                                            $accuracy = $total > 0 ? ($correct / $total) * 100 : 0;
                                        @endphp
                                        {{ number_format($accuracy, 0) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Check for unrequested sections -->
            @php
                $availableSections = $fullTestAttempt->fullTest->getAvailableSections();
                $hasWriting = in_array('writing', $availableSections);
                $hasSpeaking = in_array('speaking', $availableSections);

                $writingRequested = false;
                $speakingRequested = false;
                $sectionsNotRequested = [];

                foreach ($fullTestAttempt->sectionAttempts as $sectionAttempt) {
                    if ($sectionAttempt->section_type === 'writing' && $hasWriting) {
                        if ($sectionAttempt->studentAttempt->humanEvaluationRequest) {
                            $writingRequested = true;
                        } else {
                            $sectionsNotRequested[] = 'Writing';
                        }
                    }

                    if ($sectionAttempt->section_type === 'speaking' && $hasSpeaking) {
                        if ($sectionAttempt->studentAttempt->humanEvaluationRequest) {
                            $speakingRequested = true;
                        } else {
                            $sectionsNotRequested[] = 'Speaking';
                        }
                    }
                }

                $hasUnrequestedSections = !empty($sectionsNotRequested);
            @endphp

            @if($hasUnrequestedSections)
                <!-- Request remaining sections -->
                <div class="max-w-4xl mx-auto mt-6">
                    <div class="glass-dark border border-blue-500/30 rounded-xl p-6 text-center">
                        <div class="flex items-center justify-center text-blue-400 mb-3">
                            <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold">Additional Sections Available</h3>
                        </div>
                        <p class="text-gray-400 mb-4">
                            You can also request evaluation for your {{ implode(' and ', $sectionsNotRequested) }}
                            {{ count($sectionsNotRequested) > 1 ? 'sections' : 'section' }} to get complete feedback
                        </p>
                        <a href="{{ route('student.full-test.request-evaluation', $fullTestAttempt) }}"
                           class="inline-flex items-center px-6 py-3 bg-[#C8102E] hover:bg-[#A00D24] text-white font-semibold rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Request {{ implode(' & ', $sectionsNotRequested) }} Evaluation
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>
</x-student-layout>
