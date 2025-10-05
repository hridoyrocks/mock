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
            <!-- Task Scores -->
            <div class="space-y-6 mb-6">
                @foreach($evaluation->task_scores as $index => $taskScore)
                    <div class="glass rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 mr-3 text-sm">
                                {{ $index + 1 }}
                            </span>
                            Task {{ $index + 1 }} Evaluation
                            <span class="ml-auto glass rounded-lg px-3 py-1 text-sm border-blue-500/30">
                                Band {{ number_format($taskScore['score'], 1) }}
                            </span>
                        </h3>
                        
                        <!-- Score Breakdown -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <div class="glass rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-400 mb-1">Task Achievement</p>
                                <p class="text-lg font-bold text-blue-400">{{ $taskScore['task_achievement'] }}</p>
                            </div>
                            <div class="glass rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-400 mb-1">Coherence & Cohesion</p>
                                <p class="text-lg font-bold text-purple-400">{{ $taskScore['coherence_cohesion'] }}</p>
                            </div>
                            <div class="glass rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-400 mb-1">Lexical Resource</p>
                                <p class="text-lg font-bold text-yellow-400">{{ $taskScore['lexical_resource'] }}</p>
                            </div>
                            <div class="glass rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-400 mb-1">Grammar</p>
                                <p class="text-lg font-bold text-red-400">{{ $taskScore['grammar'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Teacher's Feedback -->
                        <div class="glass rounded-lg p-4 bg-blue-500/10 border-blue-500/30 mb-4">
                            <h4 class="text-sm font-semibold text-white mb-2">
                                <i class="fas fa-comment-dots text-blue-400 mr-2"></i>
                                Teacher's Feedback
                            </h4>
                            <p class="text-gray-300 text-sm">{{ $taskScore['feedback'] }}</p>
                        </div>
                        
                        <!-- Student's Response with Error Markings -->
                        @php
                            $answer = $attempt->answers->where('question.part_number', $index + 1)->first();
                            if (!$answer) {
                                // Try to find by index
                                $answer = $attempt->answers->get($index);
                            }
                            $errorMarkings = $evaluation->errorMarkings->where('task_number', $index + 1);
                        @endphp
                        
                        @if($answer && $errorMarkings->count() > 0)
                            <div class="glass rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-semibold text-white mb-3">
                                    <i class="fas fa-edit text-yellow-400 mr-2"></i>
                                    Your Response with Marked Errors
                                </h4>
                                
                                <!-- Error Legend -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">Task Achievement</span>
                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded">Coherence & Cohesion</span>
                                    <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">Lexical Resource</span>
                                    <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">Grammar</span>
                                </div>
                                
                                <div id="markedResponse_{{ $index }}" class="text-gray-300 text-sm leading-relaxed whitespace-pre-wrap">{{ $answer->answer }}</div>
                            </div>
                            
                            <!-- Error Summary -->
                            <div class="glass rounded-lg p-4 bg-red-500/10 border-red-500/30">
                                <h4 class="text-sm font-semibold text-white mb-2">
                                    <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                                    Error Summary
                                </h4>
                                @php
                                    $errorsByType = $errorMarkings->groupBy('error_type');
                                @endphp
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    @foreach(['task_achievement', 'coherence_cohesion', 'lexical_resource', 'grammar'] as $errorType)
                                        @php
                                            $count = $errorsByType->get($errorType, collect())->count();
                                            $model = new \App\Models\EvaluationErrorMarking(['error_type' => $errorType]);
                                        @endphp
                                        <div class="text-center">
                                            <p class="text-xs text-gray-400">{{ $model->getErrorTypeLabel() }}</p>
                                            <p class="text-lg font-bold {{ $count > 0 ? 'text-red-400' : 'text-green-400' }}">
                                                {{ $count }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @push('scripts')
                    <script>
                        // Apply error markings for task {{ $index + 1 }}
                        (function() {
                            const container = document.getElementById('markedResponse_{{ $index }}');
                            if (!container) return;
                            
                            const originalText = container.textContent;
                            const markings = @json($errorMarkings->values());
                            
                            // Sort markings by position (reverse order)
                            markings.sort((a, b) => b.end_position - a.start_position);
                            
                            let markedText = originalText;
                            markings.forEach(marking => {
                                const before = markedText.substring(0, marking.start_position);
                                const marked = markedText.substring(marking.start_position, marking.end_position);
                                const after = markedText.substring(marking.end_position);
                                
                                const colorClass = {
                                    'task_achievement': 'bg-blue-200 text-blue-900 border-blue-400',
                                    'coherence_cohesion': 'bg-purple-200 text-purple-900 border-purple-400',
                                    'lexical_resource': 'bg-yellow-200 text-yellow-900 border-yellow-400',
                                    'grammar': 'bg-red-200 text-red-900 border-red-400'
                                }[marking.error_type] || 'bg-gray-200 text-gray-900 border-gray-400';
                                
                                markedText = before + 
                                    `<span class="inline-block px-1 rounded border-2 ${colorClass}" title="${marking.error_type.replace('_', ' ')}">${marked}</span>` + 
                                    after;
                            });
                            
                            container.innerHTML = markedText;
                        })();
                    </script>
                    @endpush
                @endforeach
            </div>
            
            <!-- Overall Assessment -->
            <div class="glass rounded-2xl p-6 bg-gradient-to-br from-blue-600/10 to-cyan-600/10 border-blue-500/30 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                    Overall Assessment
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Strengths -->
                    @if(!empty($evaluation->strengths))
                        <div class="glass rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-white mb-3 flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-2"></i>
                                Your Strengths
                            </h3>
                            <ul class="space-y-2">
                                @foreach($evaluation->strengths as $strength)
                                    <li class="flex items-start text-gray-300 text-xs">
                                        <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5 text-xs"></i>
                                        {{ $strength }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Areas for Improvement -->
                    @if(!empty($evaluation->improvements))
                        <div class="glass rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-white mb-3 flex items-center">
                                <i class="fas fa-chart-line text-blue-400 mr-2"></i>
                                Areas for Improvement
                            </h3>
                            <ul class="space-y-2">
                                @foreach($evaluation->improvements as $improvement)
                                    <li class="flex items-start text-gray-300 text-xs">
                                        <i class="fas fa-arrow-up text-blue-400 mr-2 mt-0.5 text-xs"></i>
                                        {{ $improvement }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-center gap-3">
                <a href="{{ route('student.results.show', $attempt) }}" 
                   class="px-4 py-2 rounded-lg glass text-white text-sm hover:border-blue-500/50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Results
                </a>
                <button onclick="window.print()" 
                        class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm hover:from-blue-700 hover:to-cyan-700 transition-all">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </button>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        /* Custom styles for error markings */
        .marked-error {
            padding: 2px 4px;
            border-radius: 3px;
            cursor: help;
            position: relative;
            border-width: 2px;
        }
        
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
    </style>
    @endpush
</x-student-layout>
