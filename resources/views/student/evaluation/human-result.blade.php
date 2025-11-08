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
        <div class="max-w-7xl mx-auto space-y-6">
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
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3 mb-4">
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
                                
                                <div id="markedResponse_{{ $index }}" class="text-gray-300 text-sm leading-relaxed whitespace-pre-wrap break-words overflow-hidden">{{ $answer->answer }}</div>
                            </div>
                            
                            <!-- Error Details with Comments -->
                            @if($errorMarkings->count() > 0)
                                <div class="glass rounded-lg p-4 bg-red-500/10 border-red-500/30 mb-4">
                                    <h4 class="text-sm font-semibold text-white mb-3">
                                        <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                                        Marked Errors & Teacher Notes
                                    </h4>
                                    
                                    <div class="space-y-3 error-details-section">
                                        @foreach($errorMarkings->sortBy('start_position') as $marking)
                                            <div class="glass rounded-lg p-3 bg-gray-900/50">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0">
                                                        @php
                                                            $colorIcon = match($marking->error_type) {
                                                                'task_achievement' => 'text-blue-400',
                                                                'coherence_cohesion' => 'text-purple-400',
                                                                'lexical_resource' => 'text-yellow-400',
                                                                'grammar' => 'text-red-400',
                                                                default => 'text-gray-400'
                                                            };
                                                        @endphp
                                                        <i class="fas fa-exclamation-triangle {{ $colorIcon }}"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="text-xs px-2 py-1 rounded text-white
                                                                {{ match($marking->error_type) {
                                                                    'task_achievement' => 'bg-blue-600/80',
                                                                    'coherence_cohesion' => 'bg-purple-600/80',
                                                                    'lexical_resource' => 'bg-yellow-600/80',
                                                                    'grammar' => 'bg-red-600/80',
                                                                    default => 'bg-gray-600/80'
                                                                } }}
                                                            ">
                                                                {{ $marking->getErrorTypeLabel() }}
                                                            </span>
                                                        </div>
                                                        <div class="mb-2">
                                                            <span class="text-sm text-gray-400">Marked text:</span>
                                                            <span class="text-sm text-white font-medium bg-gray-800 px-2 py-1 rounded ml-2">
                                                                "{{ Str::limit($marking->marked_text, 50) }}"
                                                            </span>
                                                        </div>
                                                        @if($marking->comment && trim($marking->comment) !== '')
                                                        <div class="bg-blue-900/30 border-l-4 border-blue-400 pl-3 py-2">
                                                        <p class="text-sm text-gray-300">
                                                        <i class="fas fa-sticky-note text-blue-400 mr-2"></i>
                                                        {{ $marking->comment }}
                                                        </p>
                                                        </div>
                                                        @elseif($marking->note && trim($marking->note) !== '')
                                                        <div class="bg-blue-900/30 border-l-4 border-blue-400 pl-3 py-2">
                                                        <p class="text-sm text-gray-300">
                                                        <i class="fas fa-sticky-note text-blue-400 mr-2"></i>
                                                        {{ $marking->note }}
                                                        </p>
                                                        </div>
                                                        @else
                                            <div class="bg-gray-800/50 border-l-4 border-gray-600 pl-3 py-2">
                                                <p class="text-xs text-gray-500 italic">
                                                    <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                                                    No teacher note provided for this error
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
                                <div class="glass rounded-lg p-4 bg-amber-500/10 border-amber-500/30">
                                    <h4 class="text-sm font-semibold text-white mb-2">
                                        <i class="fas fa-chart-bar text-amber-400 mr-2"></i>
                                        Error Summary
                                    </h4>
                                    @php
                                        $errorsByType = $errorMarkings->groupBy('error_type');
                                    @endphp
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3">
                                        @foreach(['task_achievement', 'coherence_cohesion', 'lexical_resource', 'grammar'] as $errorType)
                                            @php
                                                $typeErrors = $errorsByType->get($errorType, collect());
                                                $count = $typeErrors->count();
                                                $withComments = $typeErrors->whereNotNull('comment')->count();
                                                $model = new \App\Models\EvaluationErrorMarking(['error_type' => $errorType]);
                                            @endphp
                                            <div class="glass rounded-lg p-3 text-center">
                                                <p class="text-xs text-gray-400 mb-1">{{ $model->getErrorTypeLabel() }}</p>
                                                <p class="text-lg font-bold {{ $count > 0 ? 'text-red-400' : 'text-green-400' }}">
                                                    {{ $count }}
                                                </p>
                                                @if($count > 0)
                                                    <p class="text-xs text-gray-500">
                                                        {{ $withComments }} with notes
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
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
                            
                            // Sort markings by position (reverse order for proper text replacement)
                            markings.sort((a, b) => b.start_position - a.start_position);
                            
                            let markedText = originalText;
                            markings.forEach((marking, index) => {
                                const before = markedText.substring(0, marking.start_position);
                                const marked = markedText.substring(marking.start_position, marking.end_position);
                                const after = markedText.substring(marking.end_position);
                                
                                const colorClass = {
                                    'task_achievement': 'bg-blue-100 text-blue-800 border-blue-300',
                                    'coherence_cohesion': 'bg-purple-100 text-purple-800 border-purple-300',
                                    'lexical_resource': 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'grammar': 'bg-red-100 text-red-800 border-red-300'
                                }[marking.error_type] || 'bg-gray-100 text-gray-800 border-gray-300';
                                
                                // Very simple tooltip - no special characters
                                const simpleLabel = {
                                    'task_achievement': 'Task Achievement Error',
                                    'coherence_cohesion': 'Coherence Error', 
                                    'lexical_resource': 'Vocabulary Error',
                                    'grammar': 'Grammar Error'
                                }[marking.error_type] || 'Error';
                                
                                const tooltipContent = (marking.comment || marking.note) && (marking.comment || marking.note).trim() !== '' ? 
                                    `${simpleLabel} - Click for note` : 
                                    `${simpleLabel} - Click for details`;
                                
                                // Create simple marked span without block elements
                                const commentIcon = ((marking.comment || marking.note) && (marking.comment || marking.note).trim() !== '') ? 
                                    `<i class="fas fa-sticky-note text-xs ml-1 opacity-60" style="font-size: 8px; color: #10b981;"></i>` : 
                                    '';
                                
                                markedText = before + 
                                    `<span class="marked-error border ${colorClass} cursor-help" 
                                           title="${tooltipContent}" 
                                           data-error-id="${marking.id}" 
                                           data-error-type="${marking.error_type}"
                                           style="padding: 1px 2px; border-radius: 2px; display: inline;">${marked}${commentIcon}</span>` + 
                                    after;
                            });
                            
                            container.innerHTML = markedText;
                            
                            // Add click handler for marked errors to show detailed popup
                            container.addEventListener('click', function(e) {
                                const markedError = e.target.closest('.marked-error');
                                if (markedError) {
                                    e.preventDefault();
                                    const errorId = markedError.dataset.errorId;
                                    const marking = markings.find(m => m.id == errorId);
                                    
                                    if (marking) {
                                        // Show comment popup even if no comment (will show "no comment" message)
                                        showErrorComment(marking, markedError);
                                    }
                                }
                            });
                        })();
                        
                        // Function to show error comment popup
                        function showErrorComment(marking, element) {
                            // Remove any existing popups
                            document.querySelectorAll('.error-comment-popup').forEach(popup => popup.remove());
                            
                            const errorTypeLabels = {
                                'task_achievement': 'Task Achievement',
                                'coherence_cohesion': 'Coherence & Cohesion',
                                'lexical_resource': 'Lexical Resource',
                                'grammar': 'Grammar'
                            };
                            
                            const popup = document.createElement('div');
                            popup.className = 'error-comment-popup fixed z-50 bg-gray-900 border border-gray-600 rounded-lg p-4 shadow-2xl';
                            
                            // Truncate long comments for display
                            const maxCommentLength = window.innerWidth < 768 ? 150 : 200;
                            const noteContent = marking.comment || marking.note || '';
                            const displayComment = noteContent && noteContent.length > maxCommentLength ? 
                                noteContent.substring(0, maxCommentLength) + '...' : 
                                noteContent;
                            
                            popup.innerHTML = `
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-3 h-3 rounded-full bg-${
                                        marking.error_type === 'task_achievement' ? 'blue' :
                                        marking.error_type === 'coherence_cohesion' ? 'purple' :
                                        marking.error_type === 'lexical_resource' ? 'yellow' :
                                        marking.error_type === 'grammar' ? 'red' : 'gray'
                                    }-400"></div>
                                    <span class="text-sm font-semibold text-white">${errorTypeLabels[marking.error_type] || marking.error_type}</span>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-white text-lg leading-none">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="text-xs text-gray-400 mb-2">
                                    "${marking.marked_text.length > 30 ? marking.marked_text.substring(0, 30) + '...' : marking.marked_text}"
                                </div>
                                <div class="text-sm text-gray-300">
                                    ${noteContent && noteContent.trim() !== '' ? 
                                        `<i class="fas fa-sticky-note text-blue-400 mr-2"></i>${displayComment}` :
                                        `<i class="fas fa-info-circle text-gray-400 mr-2"></i><span class="text-gray-500 italic">No teacher note provided for this error</span>`
                                    }
                                </div>
                            `;
                            
                            document.body.appendChild(popup);
                            
                            // Position popup based on screen size
                            if (window.innerWidth <= 768) {
                                // Mobile: Center on screen
                                popup.style.position = 'fixed';
                                popup.style.top = '50%';
                                popup.style.left = '50%';
                                popup.style.transform = 'translate(-50%, -50%)';
                                popup.style.maxWidth = 'calc(100vw - 1rem)';
                                popup.style.width = 'calc(100vw - 1rem)';
                                popup.style.maxHeight = '70vh';
                                popup.style.overflowY = 'auto';
                            } else {
                                // Desktop/Tablet: Position near clicked element
                                const rect = element.getBoundingClientRect();
                                const popupRect = popup.getBoundingClientRect();
                                
                                // Calculate best position
                                let left = Math.min(rect.left, window.innerWidth - popupRect.width - 20);
                                let top = rect.bottom + 10;
                                
                                // If popup would go off bottom of screen, show above element
                                if (top + popupRect.height > window.innerHeight) {
                                    top = rect.top - popupRect.height - 10;
                                }
                                
                                // Ensure popup doesn't go off left edge
                                left = Math.max(10, left);
                                
                                popup.style.left = left + 'px';
                                popup.style.top = top + 'px';
                            }
                            
                            // Auto-close popup after 12 seconds on mobile, 8 seconds on desktop
                            const autoCloseTime = window.innerWidth <= 768 ? 12000 : 8000;
                            setTimeout(() => {
                                if (popup.parentElement) {
                                    popup.remove();
                                }
                            }, autoCloseTime);
                            
                            // Close popup when clicking outside (desktop only)
                            if (window.innerWidth > 768) {
                                document.addEventListener('click', function closeOnClickOutside(e) {
                                    if (!popup.contains(e.target) && !element.contains(e.target)) {
                                        popup.remove();
                                        document.removeEventListener('click', closeOnClickOutside);
                                    }
                                });
                            }
                        }
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
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
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
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('student.results.show', $attempt) }}" 
                   class="px-4 py-2 rounded-lg glass text-white text-sm text-center hover:border-blue-500/50 transition-all">
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
        /* Custom styles for error markings - Minimal inline approach */
        .marked-error {
            cursor: help;
            transition: box-shadow 0.2s ease;
        }
        
        .marked-error:hover {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        /* Note indicator styling (fas fa-sticky-note for notes, fas fa-comment-dots for general) */
        .marked-error .fa-comment-dots {
            opacity: 0.7;
            font-size: 10px;
            margin-left: 2px;
            animation: pulse 3s infinite;
            vertical-align: super;
            color: #3b82f6;
        }
        
        .marked-error .fa-sticky-note {
            opacity: 0.8;
            font-size: 9px;
            margin-left: 2px;
            animation: pulse 3s infinite;
            vertical-align: super;
            color: #10b981;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }
        
        /* Error comment popup */
        .error-comment-popup {
            animation: fadeInUp 0.2s ease-out;
            backdrop-filter: blur(10px);
            background: rgba(17, 24, 39, 0.98) !important;
            border: 1px solid rgba(75, 85, 99, 0.5);
            max-width: 90vw;
            width: auto;
            min-width: 250px;
            z-index: 9999;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Enhanced visual hierarchy for error details */
        .error-details-section {
            max-height: 60vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #4B5563 #1F2937;
        }
        
        .error-details-section::-webkit-scrollbar {
            width: 6px;
        }
        
        .error-details-section::-webkit-scrollbar-track {
            background: #1F2937;
            border-radius: 3px;
        }
        
        .error-details-section::-webkit-scrollbar-thumb {
            background: #4B5563;
            border-radius: 3px;
        }
        
        .error-details-section::-webkit-scrollbar-thumb:hover {
            background: #6B7280;
        }
        
        /* Responsive text container */
        #markedResponse_0, #markedResponse_1 {
            word-wrap: break-word;
            word-break: break-word;
            line-height: 1.6;
            font-size: 14px;
        }
        
        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .error-comment-popup {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                max-width: calc(100vw - 1rem);
                width: calc(100vw - 1rem);
                max-height: 70vh;
                overflow-y: auto;
            }
            
            .marked-error {
                /* Mobile: Even more minimal styling */
            }
            
            .marked-error .fa-comment-dots {
                font-size: 8px;
                margin-left: 1px;
            }
            
            .marked-error .fa-sticky-note {
                font-size: 7px;
                margin-left: 1px;
            }
            
            /* Improve readability on mobile */
            #markedResponse_0, #markedResponse_1 {
                font-size: 13px;
                line-height: 1.5;
            }
            
            /* Error details cards responsive */
            .error-details-section .glass {
                padding: 12px;
            }
            
            .error-details-section .flex {
                flex-direction: column;
                gap: 8px;
            }
            
            .error-details-section .flex-shrink-0 {
                flex-shrink: 1;
            }
        }
        
        /* Extra small screens */
        @media (max-width: 480px) {
            .marked-error {
                /* Very small screens: Keep text natural */
            }
            
            .marked-error .fa-comment-dots,
            .marked-error .fa-sticky-note {
                display: none; /* Hide all icons on very small screens */
            }
            
            #markedResponse_0, #markedResponse_1 {
                font-size: 12px;
                line-height: 1.4;
            }
            
            .error-comment-popup {
                padding: 12px;
                font-size: 13px;
            }
        }
        
        /* Tablet styles */
        @media (min-width: 769px) and (max-width: 1024px) {
            .error-comment-popup {
                max-width: 400px;
            }
        }
        
        /* Desktop styles */
        @media (min-width: 1025px) {
            .error-comment-popup {
                max-width: 450px;
            }
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
            
            /* Hide interactive elements during print */
            nav, header, footer, button, a[href], .error-comment-popup {
                display: none !important;
            }
            
            /* Show only essential error marking info */
            .marked-error {
                border: 1px solid #000 !important;
                background: #f0f0f0 !important;
                color: #000 !important;
            }
            
            .fa-comment-dots, .fa-sticky-note {
                display: none !important;
            }
            
            /* Ensure proper text flow in print */
            #markedResponse_0, #markedResponse_1 {
                font-size: 12pt !important;
                line-height: 1.4 !important;
            }
        }
    </style>
    @endpush
</x-student-layout>
