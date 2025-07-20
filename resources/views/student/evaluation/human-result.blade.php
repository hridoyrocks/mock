{{-- resources/views/student/evaluation/human-result.blade.php --}}
<x-student-layout>
    <x-slot:title>Human Evaluation Result</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-transparent to-cyan-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-6">
            <div class="max-w-7xl mx-auto">
                <!-- Test Info Header -->
                <div class="glass rounded-2xl p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-white mb-2">
                                <i class="fas fa-user-tie text-blue-400 mr-2"></i>
                                Human Evaluation Result
                            </h1>
                            <p class="text-sm text-gray-300">
                                {{ $attempt->testSet->title }} • {{ ucfirst($attempt->testSet->section->name) }} Section
                            </p>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <!-- Band Score -->
                            <div class="glass rounded-xl px-6 py-4 text-center border-blue-500/30">
                                <p class="text-xs text-gray-400 mb-1">Overall Band</p>
                                <p class="text-4xl font-bold text-white">
                                    {{ number_format($evaluation->overall_band_score, 1) }}
                                </p>
                                <div class="mt-1 text-xs text-blue-400">Human Expert</div>
                            </div>
                            
                            <!-- Teacher Info -->
                            <div class="glass rounded-xl px-6 py-4">
                                <p class="text-xs text-gray-400 mb-1">Evaluated by</p>
                                <div class="flex items-center gap-3">
                                    @if($evaluation->evaluator->avatar_url)
                                        <img src="{{ $evaluation->evaluator->avatar_url }}" 
                                             alt="{{ $evaluation->evaluator->name }}" 
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($evaluation->evaluator->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm text-white font-medium">{{ $evaluation->evaluator->name }}</p>
                                        <p class="text-xs text-gray-400">IELTS Expert</p>
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
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-4">
        <div class="max-w-7xl mx-auto">
            <!-- Evaluation Details -->
            <div class="glass rounded-2xl p-6 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-chart-radar text-blue-400 mr-2"></i>
                    Score Breakdown
                </h2>
                
                <!-- Criteria Scores -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                        $criteriaScores = is_string($evaluation->criteria_scores) ? json_decode($evaluation->criteria_scores, true) : $evaluation->criteria_scores;
                    @endphp
                    @if($criteriaScores && is_array($criteriaScores))
                        @foreach($criteriaScores as $criterion => $score)
                            <div class="glass rounded-lg p-4 text-center hover:border-blue-500/50 transition-all">
                                <p class="text-xs text-gray-400 mb-1">{{ $criterion }}</p>
                                <p class="text-2xl font-bold 
                                   {{ $score >= 7 ? 'text-green-400' : ($score >= 5 ? 'text-yellow-400' : 'text-red-400') }}">
                                    {{ number_format($score, 1) }}
                                </p>
                                <div class="mt-2 w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full transition-all duration-1000"
                                         style="width: {{ ($score/9)*100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <!-- Band Score Comparison -->
                @if($attempt->ai_band_score)
                    <div class="glass rounded-lg p-4 bg-blue-500/10 border-blue-500/30 mt-4">
                        <div class="flex items-center justify-between">
                            <div class="text-center">
                                <p class="text-xs text-gray-400">AI Score</p>
                                <p class="text-xl font-bold text-purple-400">{{ number_format($attempt->ai_band_score, 1) }}</p>
                            </div>
                            <div class="text-center px-4">
                                <i class="fas fa-arrows-alt-h text-blue-400 text-lg"></i>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-400">Human Score</p>
                                <p class="text-xl font-bold text-blue-400">{{ number_format($evaluation->overall_band_score, 1) }}</p>
                            </div>
                        </div>
                        @php
                            $difference = abs($evaluation->overall_band_score - $attempt->ai_band_score);
                        @endphp
                        @if($difference <= 0.5)
                            <p class="text-center text-green-400 text-xs mt-3">
                                <i class="fas fa-check-circle mr-1"></i>
                                AI and Human evaluations are closely aligned
                            </p>
                        @else
                            <p class="text-center text-yellow-400 text-xs mt-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ $difference }} band difference between evaluations
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Detailed Feedback -->
            @if($attempt->testSet->section->name === 'writing')
                <div class="space-y-6">
                    @php
                        $taskEvaluations = is_string($evaluation->task_evaluations) ? json_decode($evaluation->task_evaluations, true) : $evaluation->task_evaluations;
                    @endphp
                    @if($taskEvaluations && is_array($taskEvaluations))
                        @foreach($taskEvaluations as $task)
                        <div class="glass rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 mr-3 text-sm">
                                    {{ $task['task_number'] }}
                                </span>
                                Task {{ $task['task_number'] }} Evaluation
                                <span class="ml-auto glass rounded-lg px-3 py-1 text-sm border-blue-500/30">
                                    Band {{ number_format($task['band_score'], 1) }}
                                </span>
                            </h3>
                            
                            <!-- Criteria Feedback -->
                            <div class="grid md:grid-cols-2 gap-3 mb-4">
                                @php
                                    $criteriaFeedback = isset($task['criteria_feedback']) && is_string($task['criteria_feedback']) 
                                        ? json_decode($task['criteria_feedback'], true) 
                                        : ($task['criteria_feedback'] ?? []);
                                    $taskCriteriaScores = isset($task['criteria_scores']) && is_string($task['criteria_scores']) 
                                        ? json_decode($task['criteria_scores'], true) 
                                        : ($task['criteria_scores'] ?? []);
                                @endphp
                                @if($criteriaFeedback && is_array($criteriaFeedback))
                                    @foreach($criteriaFeedback as $criterion => $feedback)
                                        <div class="glass rounded-lg p-4 border-l-3 
                                            @if($criterion == 'Task Achievement') border-blue-500
                                            @elseif($criterion == 'Coherence and Cohesion') border-green-500
                                            @elseif($criterion == 'Lexical Resource') border-yellow-500
                                            @else border-red-500
                                            @endif">
                                            <h4 class="text-sm font-semibold text-white mb-2 flex items-center">
                                                @if($criterion == 'Task Achievement')
                                                    <i class="fas fa-bullseye text-blue-400 mr-2 text-xs"></i>
                                                @elseif($criterion == 'Coherence and Cohesion')
                                                    <i class="fas fa-link text-green-400 mr-2 text-xs"></i>
                                                @elseif($criterion == 'Lexical Resource')
                                                    <i class="fas fa-book text-yellow-400 mr-2 text-xs"></i>
                                                @else
                                                    <i class="fas fa-spell-check text-red-400 mr-2 text-xs"></i>
                                                @endif
                                                {{ $criterion }}
                                                <span class="ml-auto text-base font-bold">{{ $taskCriteriaScores[$criterion] ?? 'N/A' }}</span>
                                            </h4>
                                            <p class="text-gray-300 text-xs leading-relaxed">{{ $feedback }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            <!-- Specific Comments -->
                            @if(!empty($task['specific_comments']))
                                <div class="glass rounded-lg p-4 bg-blue-500/10 border-blue-500/30">
                                    <h4 class="text-sm font-semibold text-white mb-2 flex items-center">
                                        <i class="fas fa-comment-dots text-blue-400 mr-2 text-xs"></i>
                                        Additional Comments
                                    </h4>
                                    <p class="text-gray-300 text-xs leading-relaxed">{{ $task['specific_comments'] }}</p>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            @elseif($attempt->testSet->section->name === 'speaking')
                <div class="space-y-6">
                    @php
                        $partEvaluations = is_string($evaluation->part_evaluations) ? json_decode($evaluation->part_evaluations, true) : $evaluation->part_evaluations;
                    @endphp
                    @if($partEvaluations && is_array($partEvaluations))
                        @foreach($partEvaluations as $part)
                        <div class="glass rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 mr-3 text-sm">
                                    {{ $part['part_number'] }}
                                </span>
                                Part {{ $part['part_number'] }} Evaluation
                                <span class="ml-auto glass rounded-lg px-3 py-1 text-sm border-blue-500/30">
                                    Band {{ number_format($part['band_score'], 1) }}
                                </span>
                            </h3>
                            
                            <!-- Criteria Feedback -->
                            <div class="grid md:grid-cols-2 gap-3 mb-4">
                                @php
                                    $partCriteriaFeedback = isset($part['criteria_feedback']) && is_string($part['criteria_feedback']) 
                                        ? json_decode($part['criteria_feedback'], true) 
                                        : ($part['criteria_feedback'] ?? []);
                                    $partCriteriaScores = isset($part['criteria_scores']) && is_string($part['criteria_scores']) 
                                        ? json_decode($part['criteria_scores'], true) 
                                        : ($part['criteria_scores'] ?? []);
                                @endphp
                                @if($partCriteriaFeedback && is_array($partCriteriaFeedback))
                                    @foreach($partCriteriaFeedback as $criterion => $feedback)
                                        <div class="glass rounded-lg p-4 border-l-3 
                                            @if($criterion == 'Fluency and Coherence') border-blue-500
                                            @elseif($criterion == 'Lexical Resource') border-green-500
                                            @elseif($criterion == 'Grammatical Range and Accuracy') border-yellow-500
                                            @else border-purple-500
                                            @endif">
                                            <h4 class="text-sm font-semibold text-white mb-2 flex items-center">
                                                @if($criterion == 'Fluency and Coherence')
                                                    <i class="fas fa-comment-dots text-blue-400 mr-2 text-xs"></i>
                                                @elseif($criterion == 'Lexical Resource')
                                                    <i class="fas fa-book text-green-400 mr-2 text-xs"></i>
                                                @elseif($criterion == 'Grammatical Range and Accuracy')
                                                    <i class="fas fa-spell-check text-yellow-400 mr-2 text-xs"></i>
                                                @else
                                                    <i class="fas fa-volume-up text-purple-400 mr-2 text-xs"></i>
                                                @endif
                                                {{ $criterion }}
                                                <span class="ml-auto text-base font-bold">{{ $partCriteriaScores[$criterion] ?? 'N/A' }}</span>
                                            </h4>
                                            <p class="text-gray-300 text-xs leading-relaxed">{{ $feedback }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            <!-- Specific Comments -->
                            @if(!empty($part['specific_comments']))
                                <div class="glass rounded-lg p-4 bg-blue-500/10 border-blue-500/30">
                                    <h4 class="text-sm font-semibold text-white mb-2 flex items-center">
                                        <i class="fas fa-comment-dots text-blue-400 mr-2 text-xs"></i>
                                        Additional Comments
                                    </h4>
                                    <p class="text-gray-300 text-xs leading-relaxed">{{ $part['specific_comments'] }}</p>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            @endif

            <!-- Overall Comments & Recommendations -->
            <div class="glass rounded-2xl p-6 bg-gradient-to-br from-blue-600/10 to-cyan-600/10 border-blue-500/30 mb-6 mt-6">
                <h2 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                    Overall Assessment
                </h2>
                
                <!-- Overall Comments -->
                @if(!empty($evaluation->overall_comments))
                    <div class="glass rounded-lg p-4 mb-4">
                        <h3 class="text-sm font-semibold text-white mb-2 flex items-center">
                            <i class="fas fa-comment text-blue-400 mr-2 text-xs"></i>
                            Teacher's Overall Feedback
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $evaluation->overall_comments }}</p>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Strengths -->
                    @php
                        $strengths = is_string($evaluation->strengths) ? json_decode($evaluation->strengths, true) : $evaluation->strengths;
                    @endphp
                    @if(!empty($strengths) && is_array($strengths))
                        <div class="glass rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-white mb-3 flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-2 text-xs"></i>
                                Your Strengths
                            </h3>
                            <ul class="space-y-2">
                                @foreach($strengths as $strength)
                                    <li class="flex items-start text-gray-300 text-xs">
                                        <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5 text-xs"></i>
                                        {{ $strength }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Areas for Improvement -->
                    @php
                        $areasForImprovement = is_string($evaluation->areas_for_improvement) ? json_decode($evaluation->areas_for_improvement, true) : $evaluation->areas_for_improvement;
                    @endphp
                    @if(!empty($areasForImprovement) && is_array($areasForImprovement))
                        <div class="glass rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-white mb-3 flex items-center">
                                <i class="fas fa-chart-line text-blue-400 mr-2 text-xs"></i>
                                Areas for Improvement
                            </h3>
                            <ul class="space-y-2">
                                @foreach($areasForImprovement as $improvement)
                                    <li class="flex items-start text-gray-300 text-xs">
                                        <i class="fas fa-arrow-up text-blue-400 mr-2 mt-0.5 text-xs"></i>
                                        {{ $improvement }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                
                <!-- Study Recommendations -->
                @php
                    $studyRecommendations = is_string($evaluation->study_recommendations) ? json_decode($evaluation->study_recommendations, true) : $evaluation->study_recommendations;
                @endphp
                @if(!empty($studyRecommendations) && is_array($studyRecommendations))
                    <div class="glass rounded-lg p-4 mt-4">
                        <h3 class="text-sm font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-graduation-cap text-purple-400 mr-2 text-xs"></i>
                            Study Recommendations
                        </h3>
                        <div class="grid md:grid-cols-2 gap-2">
                            @foreach($studyRecommendations as $index => $recommendation)
                                <div class="flex items-start">
                                    <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-xs mr-2 flex-shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <p class="text-gray-300 text-xs">{{ $recommendation }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Evaluation Info -->
            <div class="glass rounded-lg p-4 text-center mb-6">
                <p class="text-gray-400 text-xs">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Evaluated on {{ $evaluation->created_at->format('M d, Y h:i A') }}
                    @if($evaluationRequest->priority === 'urgent')
                        <span class="inline-block ml-2 px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-400 text-xs border border-orange-500/30">
                            <i class="fas fa-fire mr-1"></i>Urgent
                        </span>
                    @endif
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-3">
                <a href="{{ route('student.results.show', $attempt) }}" 
                   class="px-4 py-2 rounded-lg glass text-white text-sm hover:border-blue-500/50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Results
                </a>
                <button onclick="downloadPDF()" 
                        class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm hover:from-blue-700 hover:to-cyan-700 transition-all">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </button>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        @media print {
            .glass {
                background: white !important;
                color: black !important;
                border: 1px solid #ddd !important;
            }
            
            .text-white, .text-gray-300, .text-gray-400 {
                color: black !important;
            }
            
            body {
                background: white !important;
            }
            
            /* Hide navigation and buttons */
            nav, header, footer, button, a[href] {
                display: none !important;
            }
        }
        
        /* Custom styles for better readability */
        .border-l-3 {
            border-left-width: 3px;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
        function downloadPDF() {
            // Simple implementation - you can enhance this with a proper PDF library
            window.print();
        }
    </script>
    @endpush
</x-student-layout>
