{{-- resources/views/student/evaluation/human-result.blade.php --}}
<x-student-layout>
    <x-slot:title>Human Evaluation Result</x-slot>
    
    <!-- Header Section with Glass Effect -->
    <section class="relative">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Card -->
                <div class="glass rounded-2xl p-6 lg:p-8 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <!-- Left: Title -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 rounded-xl bg-[#C8102E] flex items-center justify-center shadow-lg">
                                    <i class="fas fa-user-tie text-white text-xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl lg:text-3xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        Expert Evaluation Result
                                    </h1>
                                    <p class="text-sm mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        Professional IELTS Assessment
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                <div class="flex items-center gap-2 glass px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-book-open" :class="darkMode ? 'text-[#C8102E]' : 'text-[#C8102E]'"></i>
                                    <span>{{ $attempt->testSet->title }}</span>
                                </div>
                                <div class="flex items-center gap-2 glass px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-graduation-cap" :class="darkMode ? 'text-[#C8102E]' : 'text-[#C8102E]'"></i>
                                    <span>{{ ucfirst($attempt->testSet->section->name) }} Section</span>
                                </div>
                                <div class="flex items-center gap-2 glass px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-calendar" :class="darkMode ? 'text-[#C8102E]' : 'text-[#C8102E]'"></i>
                                    <span>{{ $evaluation->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right: Score & Teacher -->
                        <div class="flex items-start gap-4">
                            <!-- Overall Band Score -->
                            <div class="glass rounded-2xl px-8 py-6 text-center border-2 border-[#C8102E]/30">
                                <p class="text-xs font-semibold uppercase tracking-wide mb-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Overall Band</p>
                                <p class="text-5xl font-black text-[#C8102E]">
                                    {{ number_format($evaluation->overall_band_score, 1) }}
                                </p>
                                <div class="mt-2 flex items-center justify-center gap-1">
                                    @for($i = 1; $i <= 9; $i++)
                                        <div class="w-1.5 h-3 rounded-full {{ $i <= floor($evaluation->overall_band_score) ? 'bg-[#C8102E]' : 'bg-gray-600' }}"></div>
                                    @endfor
                                </div>
                            </div>
                            
                            <!-- Teacher Info -->
                            <div class="glass rounded-2xl p-4 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'" style="min-width: 180px;">
                                <p class="text-xs uppercase tracking-wide mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Evaluated by</p>
                                <div class="flex items-center gap-3">
                                    @if($evaluation->evaluator->avatar_url)
                                        <img src="{{ $evaluation->evaluator->avatar_url }}" 
                                             alt="{{ $evaluation->evaluator->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2" :class="darkMode ? 'border-white/20' : 'border-gray-300'">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-[#C8102E] flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                            {{ substr($evaluation->evaluator->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $evaluation->evaluator->name }}</p>
                                        <p class="text-xs flex items-center gap-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                            <i class="fas fa-certificate text-[#C8102E]"></i>
                                            IELTS Expert
                                        </p>
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
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Task Evaluations -->
            @foreach($evaluation->task_scores as $index => $taskScore)
                <div class="glass rounded-2xl border overflow-hidden" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                    <!-- Task Header -->
                    <div class="px-6 py-5 border-b" :class="darkMode ? 'border-white/10 bg-white/5' : 'border-gray-200 bg-gray-50'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#C8102E] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-[#C8102E]/30">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Task {{ $index + 1 }} Evaluation</h3>
                                    <p class="text-xs mt-0.5" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Detailed Assessment & Feedback</p>
                                </div>
                            </div>
                            <div class="glass rounded-xl px-4 py-2 border" :class="darkMode ? 'border-[#C8102E]/30' : 'border-gray-300'">
                                <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score</p>
                                <p class="text-2xl font-bold text-[#C8102E]">{{ number_format($taskScore['score'], 1) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Criteria Scores -->
                        <div>
                            <h4 class="text-base font-bold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                <i class="fas fa-chart-bar text-[#C8102E] mr-2"></i>
                                Assessment Criteria
                            </h4>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                <div class="glass rounded-xl p-4 border border-blue-500/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <i class="fas fa-bullseye text-blue-500"></i>
                                        <span class="text-2xl font-bold text-blue-500">{{ $taskScore['task_achievement'] }}</span>
                                    </div>
                                    <p class="text-sm font-semibold" :class="darkMode ? 'text-gray-200' : 'text-gray-700'">Task Response</p>
                                </div>
                                
                                <div class="glass rounded-xl p-4 border border-purple-500/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <i class="fas fa-link text-purple-500"></i>
                                        <span class="text-2xl font-bold text-purple-500">{{ $taskScore['coherence_cohesion'] }}</span>
                                    </div>
                                    <p class="text-sm font-semibold" :class="darkMode ? 'text-gray-200' : 'text-gray-700'">Coherence & Cohesion</p>
                                </div>
                                
                                <div class="glass rounded-xl p-4 border border-amber-500/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <i class="fas fa-book text-amber-500"></i>
                                        <span class="text-2xl font-bold text-amber-500">{{ $taskScore['lexical_resource'] }}</span>
                                    </div>
                                    <p class="text-sm font-semibold" :class="darkMode ? 'text-gray-200' : 'text-gray-700'">Lexical Resource</p>
                                </div>
                                
                                <div class="glass rounded-xl p-4 border border-red-500/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <i class="fas fa-spell-check text-red-500"></i>
                                        <span class="text-2xl font-bold text-red-500">{{ $taskScore['grammar'] }}</span>
                                    </div>
                                    <p class="text-sm font-semibold" :class="darkMode ? 'text-gray-200' : 'text-gray-700'">Grammar</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Teacher's Feedback -->
                        <div class="glass rounded-xl p-5 border border-[#C8102E]/20">
                            <h4 class="text-base font-bold mb-3 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                <i class="fas fa-comment-dots text-[#C8102E] mr-2"></i>
                                Expert Feedback
                            </h4>
                            <p class="text-base leading-relaxed" :class="darkMode ? 'text-gray-100' : 'text-gray-800'">{{ $taskScore['feedback'] }}</p>
                        </div>
                        
                        <!-- Student's Response with Errors -->
                        @php
                            $answer = $attempt->answers->where('question.part_number', $index + 1)->first();
                            if (!$answer) {
                                $answer = $attempt->answers->get($index);
                            }
                            $errorMarkings = $evaluation->errorMarkings->where('task_number', $index + 1);
                        @endphp
                        
                        @if($answer && $errorMarkings->count() > 0)
                            <div>
                                <h4 class="text-base font-bold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                    <i class="fas fa-edit text-[#C8102E] mr-2"></i>
                                    Your Response with Marked Errors
                                </h4>
                                
                                <!-- Error Legend -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <div class="flex items-center gap-2 glass px-4 py-2 rounded-lg text-sm font-semibold border border-blue-500/30">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <span :class="darkMode ? 'text-gray-100' : 'text-gray-800'">Task Response</span>
                                    </div>
                                    <div class="flex items-center gap-2 glass px-4 py-2 rounded-lg text-sm font-semibold border border-purple-500/30">
                                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                        <span :class="darkMode ? 'text-gray-100' : 'text-gray-800'">Coherence & Cohesion</span>
                                    </div>
                                    <div class="flex items-center gap-2 glass px-4 py-2 rounded-lg text-sm font-semibold border border-amber-500/30">
                                        <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                                        <span :class="darkMode ? 'text-gray-100' : 'text-gray-800'">Lexical Resource</span>
                                    </div>
                                    <div class="flex items-center gap-2 glass px-4 py-2 rounded-lg text-sm font-semibold border border-red-500/30">
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                        <span :class="darkMode ? 'text-gray-100' : 'text-gray-800'">Grammar</span>
                                    </div>
                                </div>
                                
                                <div class="glass rounded-xl p-5 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                    <div id="markedResponse_{{ $index }}" class="text-base leading-relaxed whitespace-pre-wrap break-words" :class="darkMode ? 'text-gray-100' : 'text-gray-800'">{{ $answer->answer }}</div>
                                </div>
                            </div>
                            
                            <!-- Error Details -->
                            @if($errorMarkings->count() > 0)
                                <div class="glass rounded-xl p-5 border border-amber-500/20">
                                    <h4 class="text-base font-bold mb-4 flex items-center justify-between" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        <span>
                                            <i class="fas fa-exclamation-circle text-amber-500 mr-2"></i>
                                            Marked Errors & Teacher Notes
                                        </span>
                                        <span class="text-sm glass px-3 py-1.5 rounded-lg border font-semibold" :class="darkMode ? 'border-white/20 text-gray-200' : 'border-gray-300 text-gray-700'">
                                            {{ $errorMarkings->count() }} errors found
                                        </span>
                                    </h4>
                                    
                                    <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                                        @foreach($errorMarkings->sortBy('start_position') as $marking)
                                            <div class="glass rounded-xl p-4 border hover-lift" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 mt-1">
                                                        @php
                                                            $iconColor = match($marking->error_type) {
                                                                'task_achievement' => 'text-blue-500',
                                                                'coherence_cohesion' => 'text-purple-500',
                                                                'lexical_resource' => 'text-amber-500',
                                                                'grammar' => 'text-red-500',
                                                                default => 'text-gray-500'
                                                            };
                                                        @endphp
                                                        <i class="fas fa-exclamation-triangle {{ $iconColor }}"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg text-white
                                                                {{ match($marking->error_type) {
                                                                    'task_achievement' => 'bg-blue-600',
                                                                    'coherence_cohesion' => 'bg-purple-600',
                                                                    'lexical_resource' => 'bg-amber-600',
                                                                    'grammar' => 'bg-red-600',
                                                                    default => 'bg-gray-600'
                                                                } }}
                                                            ">
                                                                {{ $marking->getErrorTypeLabel() }}
                                                            </span>
                                                        </div>
                                                        <div class="mb-2">
                                                            <span class="text-sm font-semibold" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">Marked text:</span>
                                                            <span class="text-base font-semibold glass px-2 py-1 rounded ml-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                                "{{ Str::limit($marking->marked_text, 60) }}"
                                                            </span>
                                                        </div>
                                                        @if($marking->comment && trim($marking->comment) !== '')
                                                            <div class="glass border-l-4 border-blue-500 pl-3 py-2 rounded" :class="darkMode ? 'bg-blue-500/10' : 'bg-blue-50'">
                                                                <p class="text-base" :class="darkMode ? 'text-gray-100' : 'text-gray-800'">
                                                                    <i class="fas fa-sticky-note text-blue-500 mr-2"></i>
                                                                    {{ $marking->comment }}
                                                                </p>
                                                            </div>
                                                        @elseif($marking->note && trim($marking->note) !== '')
                                                            <div class="glass border-l-4 border-blue-500 pl-3 py-2 rounded" :class="darkMode ? '' : 'bg-blue-50'">
                                                                <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                                    <i class="fas fa-sticky-note text-blue-500 mr-2"></i>
                                                                    {{ $marking->note }}
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div class="glass border-l-4 border-gray-500 pl-3 py-2 rounded">
                                                                <p class="text-xs italic" :class="darkMode ? 'text-gray-500' : 'text-gray-600'">
                                                                    No teacher note provided
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Error Summary -->
                                <div class="glass rounded-xl p-5 border" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                    <h4 class="text-base font-bold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        <i class="fas fa-chart-pie text-[#C8102E] mr-2"></i>
                                        Error Summary Statistics
                                    </h4>
                                    @php
                                        $errorsByType = $errorMarkings->groupBy('error_type');
                                    @endphp
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                        @foreach(['task_achievement', 'coherence_cohesion', 'lexical_resource', 'grammar'] as $errorType)
                                            @php
                                                $typeErrors = $errorsByType->get($errorType, collect());
                                                $count = $typeErrors->count();
                                                $withComments = $typeErrors->filter(function($e) { 
                                                    return ($e->comment && trim($e->comment) !== '') || ($e->note && trim($e->note) !== ''); 
                                                })->count();
                                                $model = new \App\Models\EvaluationErrorMarking(['error_type' => $errorType]);
                                            @endphp
                                            <div class="glass rounded-xl p-4 border {{ match($errorType) {
                                                'task_achievement' => 'border-blue-500/20',
                                                'coherence_cohesion' => 'border-purple-500/20',
                                                'lexical_resource' => 'border-amber-500/20',
                                                'grammar' => 'border-red-500/20',
                                            } }} text-center">
                                                <p class="text-sm font-semibold mb-1" :class="darkMode ? 'text-gray-200' : 'text-gray-700'">{{ $model->getErrorTypeLabel() }}</p>
                                                <p class="text-3xl font-bold {{ $count > 0 ? 'text-red-500' : 'text-green-500' }}">
                                                    {{ $count }}
                                                </p>
                                                @if($count > 0)
                                                    <p class="text-sm mt-1 font-medium" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                        {{ $withComments }} with notes
                                                    </p>
                                                @else
                                                    <p class="text-sm mt-1 font-semibold text-green-500">
                                                        <i class="fas fa-check-circle"></i> Perfect
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                
                @push('scripts')
                <script>
                    (function() {
                        const container = document.getElementById('markedResponse_{{ $index }}');
                        if (!container) return;
                        
                        const originalText = container.textContent;
                        const markings = @json($errorMarkings->values());
                        
                        markings.sort((a, b) => b.start_position - a.start_position);
                        
                        let markedText = originalText;
                        markings.forEach((marking) => {
                            const before = markedText.substring(0, marking.start_position);
                            const marked = markedText.substring(marking.start_position, marking.end_position);
                            const after = markedText.substring(marking.end_position);
                            
                            const colorClass = {
                                'task_achievement': 'bg-blue-500/20 text-blue-300 border-blue-500/40',
                                'coherence_cohesion': 'bg-purple-500/20 text-purple-300 border-purple-500/40',
                                'lexical_resource': 'bg-amber-500/20 text-amber-300 border-amber-500/40',
                                'grammar': 'bg-red-500/20 text-red-300 border-red-500/40'
                            }[marking.error_type] || 'bg-gray-500/20 text-gray-300 border-gray-500/40';
                            
                            const hasNote = (marking.comment || marking.note) && (marking.comment || marking.note).trim() !== '';
                            const noteIcon = hasNote ? '<i class="fas fa-comment-dots text-xs ml-1"></i>' : '';
                            
                            markedText = before + 
                                `<span class="marked-error border-2 ${colorClass} cursor-pointer hover:opacity-80 transition-opacity rounded px-1" 
                                       data-error-id="${marking.id}"
                                       style="font-weight: 500;">${marked}${noteIcon}</span>` + 
                                after;
                        });
                        
                        container.innerHTML = markedText;
                        
                        container.addEventListener('click', function(e) {
                            const markedError = e.target.closest('.marked-error');
                            if (markedError) {
                                e.preventDefault();
                                const errorId = markedError.dataset.errorId;
                                const marking = markings.find(m => m.id == errorId);
                                if (marking) {
                                    showErrorPopup(marking, markedError);
                                }
                            }
                        });
                    })();
                    
                    function showErrorPopup(marking, element) {
                        document.querySelectorAll('.error-popup').forEach(popup => popup.remove());
                        
                        const errorLabels = {
                            'task_achievement': 'Task Response',
                            'coherence_cohesion': 'Coherence & Cohesion',
                            'lexical_resource': 'Lexical Resource',
                            'grammar': 'Grammatical Range & Accuracy'
                        };
                        
                        const popup = document.createElement('div');
                        popup.className = 'error-popup fixed z-50 glass rounded-2xl shadow-2xl border border-white/20 p-5 max-w-md';
                        
                        const noteContent = marking.comment || marking.note || '';
                        const isDark = !document.documentElement.classList.contains('light-mode');
                        
                        popup.innerHTML = `
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-${
                                        marking.error_type === 'task_achievement' ? 'blue' :
                                        marking.error_type === 'coherence_cohesion' ? 'purple' :
                                        marking.error_type === 'lexical_resource' ? 'amber' :
                                        marking.error_type === 'grammar' ? 'red' : 'gray'
                                    }-500"></div>
                                    <span class="text-sm font-bold ${isDark ? 'text-white' : 'text-gray-800'}">${errorLabels[marking.error_type]}</span>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()" class="${isDark ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-gray-800'}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="text-xs ${isDark ? 'text-gray-400' : 'text-gray-600'} mb-3 glass px-3 py-2 rounded-lg">
                                <strong>Marked:</strong> "${marking.marked_text.substring(0, 50)}${marking.marked_text.length > 50 ? '...' : ''}"
                            </div>
                            <div class="text-sm ${noteContent ? 'glass border-l-4 border-blue-500 pl-3 py-2 rounded' : 'glass px-3 py-2 rounded-lg'} ${isDark ? 'text-gray-300' : 'text-gray-700'}">
                                ${noteContent ? 
                                    `<i class="fas fa-sticky-note text-blue-500 mr-2"></i>${noteContent}` :
                                    `<i class="fas fa-info-circle ${isDark ? 'text-gray-400' : 'text-gray-500'} mr-2"></i><span class="${isDark ? 'text-gray-500' : 'text-gray-600'} italic">No teacher note provided</span>`
                                }
                            </div>
                        `;
                        
                        document.body.appendChild(popup);
                        
                        const rect = element.getBoundingClientRect();
                        popup.style.left = Math.max(20, Math.min(rect.left, window.innerWidth - popup.offsetWidth - 20)) + 'px';
                        popup.style.top = (rect.bottom + 10) + 'px';
                        
                        setTimeout(() => popup.remove(), 8000);
                    }
                </script>
                @endpush
            @endforeach
            
            <!-- Overall Assessment -->
            <div class="glass rounded-2xl p-6 border" :class="darkMode ? 'border-[#C8102E]/30' : 'border-gray-200'">
                <h2 class="text-xl font-bold mb-6 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    <i class="fas fa-lightbulb text-[#C8102E] mr-3"></i>
                    Overall Assessment
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if(!empty($evaluation->strengths))
                        <div class="glass rounded-xl p-5 border border-green-500/20">
                            <h3 class="text-lg font-semibold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                Your Strengths
                            </h3>
                            <ul class="space-y-3">
                                @foreach($evaluation->strengths as $strength)
                                    <li class="flex items-start" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                        <span class="text-sm">{{ $strength }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(!empty($evaluation->improvements))
                        <div class="glass rounded-xl p-5 border border-blue-500/20">
                            <h3 class="text-lg font-semibold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                                Areas for Improvement
                            </h3>
                            <ul class="space-y-3">
                                @foreach($evaluation->improvements as $improvement)
                                    <li class="flex items-start" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        <i class="fas fa-arrow-up text-blue-500 mr-3 mt-1 flex-shrink-0"></i>
                                        <span class="text-sm">{{ $improvement }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('student.results.show', $attempt) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl glass border font-semibold transition-all hover-lift"
                   :class="darkMode ? 'border-white/20 text-white hover:border-white/40' : 'border-gray-300 text-gray-700 hover:border-gray-400'">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Results
                </a>
                <button onclick="window.print()" 
                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#C8102E] text-white font-semibold hover:bg-[#A00E27] transition-all shadow-lg shadow-[#C8102E]/30 hover-lift">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </button>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .marked-error {
            transition: all 0.2s ease;
            display: inline;
        }
        
        .marked-error:hover {
            transform: translateY(-1px);
        }
        
        .error-popup {
            animation: slideUp 0.2s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media print {
            .error-popup, button, nav { display: none !important; }
        }
    </style>
    @endpush
</x-student-layout>
