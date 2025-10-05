<x-teacher-layout>
    <x-slot:title>Evaluate - {{ ucfirst($evaluationRequest->studentAttempt->testSet->section->name) }}</x-slot>
    
    <x-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-white">
                Evaluate {{ ucfirst($evaluationRequest->studentAttempt->testSet->section->name) }} Test
            </h1>
            <div class="flex items-center gap-4">
                <div class="text-sm">
                    <span class="text-gray-400">Status:</span>
                    <span class="text-white font-medium ml-1">{{ ucfirst($evaluationRequest->status) }}</span>
                </div>
                <div class="text-sm">
                    <span class="text-gray-400">Deadline:</span>
                    <span class="text-white font-medium ml-1">{{ $evaluationRequest->deadline_at->format('M d, h:i A') }}</span>
                </div>
            </div>
        </div>
    </x-slot>
    
    <!-- Error Type Selection Modal -->
    <div id="errorTypeModal" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none; z-index: 9999 !important;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-20 backdrop-blur-sm transition-opacity" onclick="closeErrorModal()"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-xl shadow-2xl transform transition-all sm:max-w-md sm:w-full border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        Mark Error Type
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        <span id="selectedTextDisplay" class="font-medium text-gray-900 bg-amber-100 px-2 py-1 rounded"></span>
                    </p>
                    <div class="space-y-2">
                        <button type="button" onclick="markError('task_achievement')" class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg border-2 border-transparent hover:border-blue-300 transition-all duration-200 group">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900">Task Achievement</span>
                                    <p class="text-xs text-gray-600 mt-0.5">Content & addressing the prompt</p>
                                </div>
                            </div>
                        </button>
                        <button type="button" onclick="markError('coherence_cohesion')" class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg border-2 border-transparent hover:border-purple-300 transition-all duration-200 group">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900">Coherence & Cohesion</span>
                                    <p class="text-xs text-gray-600 mt-0.5">Organization & flow of ideas</p>
                                </div>
                            </div>
                        </button>
                        <button type="button" onclick="markError('lexical_resource')" class="w-full text-left px-4 py-3 bg-amber-50 hover:bg-amber-100 rounded-lg border-2 border-transparent hover:border-amber-300 transition-all duration-200 group">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-amber-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900">Lexical Resource</span>
                                    <p class="text-xs text-gray-600 mt-0.5">Vocabulary & word choice</p>
                                </div>
                            </div>
                        </button>
                        <button type="button" onclick="markError('grammar')" class="w-full text-left px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg border-2 border-transparent hover:border-red-300 transition-all duration-200 group">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900">Grammar</span>
                                    <p class="text-xs text-gray-600 mt-0.5">Grammar & sentence structure</p>
                                </div>
                            </div>
                        </button>
                    </div>
                    <button type="button" onclick="closeErrorModal()" class="w-full mt-4 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mx-auto px-6 lg:px-8 py-6">
        @if($evaluationRequest->status === 'completed' && $evaluationRequest->humanEvaluation)
            <!-- Completed Evaluation View -->
            <div class="bg-green-50 rounded-xl p-6 mb-6 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-green-900">Evaluation Completed</h3>
                        <p class="text-green-700 text-sm mt-1">
                            Completed on {{ $evaluationRequest->completed_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-green-700">Overall Band</p>
                        <p class="text-3xl font-bold text-green-900">{{ $evaluationRequest->humanEvaluation->overall_band_score }}</p>
                    </div>
                </div>
            </div>
        @else
            <!-- Active Evaluation Form -->
            <form action="{{ route('teacher.evaluations.submit', $evaluationRequest) }}" method="POST" id="evaluationForm">
                @csrf
                
                <!-- Hidden field for error markings -->
                <input type="hidden" name="error_markings" id="errorMarkingsInput" value="[]">
                
                <!-- Progress Bar -->
                <div class="bg-white rounded-xl shadow-sm mb-6 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Evaluation Progress</span>
                        <span class="text-sm font-medium text-gray-900" id="progressText">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%" id="progressBar"></div>
                    </div>
                </div>
                
                <!-- Student Info Card -->
                <div class="bg-white rounded-xl shadow-sm mb-6 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Student</p>
                            <p class="font-medium text-gray-900">{{ $evaluationRequest->student->name }}</p>
                            <p class="text-sm text-gray-600">{{ $evaluationRequest->student->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Test</p>
                            <p class="font-medium text-gray-900">{{ $evaluationRequest->studentAttempt->testSet->title }}</p>
                            <p class="text-sm text-gray-600">{{ ucfirst($evaluationRequest->studentAttempt->testSet->section->name) }} Section</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Submitted</p>
                            <p class="font-medium text-gray-900">{{ $evaluationRequest->studentAttempt->created_at->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $evaluationRequest->studentAttempt->created_at->format('h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Priority</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($evaluationRequest->priority) }}</p>
                            <p class="text-sm text-gray-600">{{ $evaluationRequest->tokens_used }} tokens</p>
                        </div>
                    </div>
                </div>
                
                @php
                    $sectionName = $evaluationRequest->studentAttempt->testSet->section->name;
                @endphp
                
                @if($sectionName === 'writing')
                    <!-- Writing Tasks -->
                    @foreach($evaluationRequest->studentAttempt->answers as $index => $answer)
                        <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                            <!-- Task Header -->
                            <div class="bg-gray-50 px-6 py-4 border-b">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    <span class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    Task {{ $index + 1 }}: {{ $answer->question->title }}
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <!-- Question -->
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Question</p>
                                    <div class="bg-gray-50 rounded-lg p-4 text-gray-700 text-sm">
                                        {!! $answer->question->content !!}
                                    </div>
                                </div>
                                
                                <!-- Student Response -->
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-sm font-medium text-gray-700">Student's Response</p>
                                        <div class="flex items-center gap-4 text-xs">
                                            <span class="text-gray-500">
                                                <i class="fas fa-file-word mr-1"></i>
                                                {{ str_word_count($answer->answer) }} words
                                            </span>
                                            <span class="text-blue-600">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Select text to mark errors
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Error Marking Legend -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="inline-flex items-center text-xs">
                                            <span class="w-3 h-3 bg-blue-200 rounded mr-1"></span>
                                            Task Achievement
                                        </span>
                                        <span class="inline-flex items-center text-xs">
                                            <span class="w-3 h-3 bg-purple-200 rounded mr-1"></span>
                                            Coherence
                                        </span>
                                        <span class="inline-flex items-center text-xs">
                                            <span class="w-3 h-3 bg-amber-200 rounded mr-1"></span>
                                            Vocabulary
                                        </span>
                                        <span class="inline-flex items-center text-xs">
                                            <span class="w-3 h-3 bg-red-200 rounded mr-1"></span>
                                            Grammar
                                        </span>
                                    </div>
                                    
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <div id="studentResponse_{{ $index }}" 
                                             class="text-gray-800 whitespace-pre-wrap leading-relaxed text-marking-container"
                                             data-task-number="{{ $index + 1 }}"
                                             data-answer-id="{{ $answer->id }}">{{ $answer->answer }}</div>
                                    </div>
                                    
                                    <!-- Error Summary -->
                                    <div id="errorSummary_{{ $index }}" class="mt-3 hidden">
                                        <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                                            <p class="text-sm font-medium text-amber-900 mb-2">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Marked Errors
                                            </p>
                                            <div id="errorList_{{ $index }}" class="flex flex-wrap gap-2"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Scoring Grid -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-700 mb-3">Band Score Criteria</p>
                                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Overall</label>
                                            <select name="task_scores[{{ $index }}][score]" 
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Task Achievement</label>
                                            <select name="task_scores[{{ $index }}][task_achievement]" 
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Coherence</label>
                                            <select name="task_scores[{{ $index }}][coherence_cohesion]" 
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Vocabulary</label>
                                            <select name="task_scores[{{ $index }}][lexical_resource]" 
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Grammar</label>
                                            <select name="task_scores[{{ $index }}][grammar]" 
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Feedback -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Detailed Feedback
                                    </label>
                                    <textarea name="task_scores[{{ $index }}][feedback]" 
                                              rows="3"
                                              class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Provide specific feedback for this task..."
                                              required></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                
                <!-- Overall Assessment -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clipboard-check text-blue-600 mr-2"></i>
                        Overall Assessment
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Overall Band -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Overall Band Score
                            </label>
                            <select name="overall_band_score" 
                                    class="w-full rounded-lg border-gray-300 text-lg font-semibold focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <option value="">Select Score</option>
                                @for($i = 0; $i <= 9; $i += 0.5)
                                    <option value="{{ $i }}">Band {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <!-- Strengths -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Key Strengths
                            </label>
                            <div id="strengths-container" class="space-y-2">
                                <div class="strength-input">
                                    <input type="text" 
                                           name="strengths[]" 
                                           class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="e.g., Good vocabulary range"
                                           required>
                                </div>
                            </div>
                            <button type="button" 
                                    onclick="addStrength()"
                                    class="text-sm text-blue-600 hover:text-blue-700 mt-2">
                                <i class="fas fa-plus-circle mr-1"></i>Add strength
                            </button>
                        </div>
                        
                        <!-- Improvements -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Areas for Improvement
                            </label>
                            <div id="improvements-container" class="space-y-2">
                                <div class="improvement-input">
                                    <input type="text" 
                                           name="improvements[]" 
                                           class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="e.g., Work on paragraph structure"
                                           required>
                                </div>
                            </div>
                            <button type="button" 
                                    onclick="addImprovement()"
                                    class="text-sm text-blue-600 hover:text-blue-700 mt-2">
                                <i class="fas fa-plus-circle mr-1"></i>Add improvement
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Section -->
                <div class="sticky bottom-0 bg-white border-t mt-6 px-6 py-4 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <button type="button" 
                                    onclick="saveDraft()"
                                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-save mr-2"></i>Save Draft
                            </button>
                            <span id="saveStatus" class="text-sm text-gray-500 hidden">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Draft saved
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('teacher.evaluations.pending') }}" 
                               class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Submit Evaluation
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
    
    @push('styles')
    <style>
        /* Container adjustments */
        .container {
            max-width: 1400px !important;
        }
        
        /* Clean minimal styles */
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Modal styles */
        #errorTypeModal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            z-index: 99999 !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        #errorTypeModal .relative {
            position: relative !important;
            z-index: 100000 !important;
            margin: auto;
            animation: modalFadeIn 0.2s ease-out;
            max-width: 450px !important;
            width: 90% !important;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        /* Error marking styles */
        .error-mark {
            padding: 2px 6px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            display: inline-block;
            margin: 0 1px;
        }
        
        .error-mark.task_achievement {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        
        .error-mark.coherence_cohesion {
            background-color: #E9D5FF;
            color: #6B21A8;
        }
        
        .error-mark.lexical_resource {
            background-color: #FED7AA;
            color: #92400E;
        }
        
        .error-mark.grammar {
            background-color: #FECACA;
            color: #991B1B;
        }
        
        .error-mark:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Text selection */
        .text-marking-container {
            user-select: text;
            line-height: 1.8;
            cursor: text;
        }
        
        ::selection {
            background-color: #FEF3C7;
            color: #1F2937;
        }
        
        /* Form inputs */
        select, input, textarea {
            transition: all 0.2s;
        }
        
        select:focus, input:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Sticky footer shadow */
        .sticky {
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
        // Error marking functionality
        let errorMarkings = [];
        let currentSelection = null;
        let markingIdCounter = 0;
        
        // Make functions available globally
        window.markError = markError;
        window.closeErrorModal = closeErrorModal;
        window.removeMarking = removeMarking;
        window.addStrength = addStrength;
        window.addImprovement = addImprovement;
        window.removeField = removeField;
        window.saveDraft = saveDraft;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Hide modal on load
            const modal = document.getElementById('errorTypeModal');
            if (modal) modal.style.display = 'none';
            
            // Text selection handlers
            document.querySelectorAll('.text-marking-container').forEach(container => {
                container.addEventListener('mouseup', handleTextSelection);
            });
            
            // Progress tracking
            updateProgress();
            document.querySelectorAll('select, input, textarea').forEach(field => {
                field.addEventListener('change', updateProgress);
            });
            
            // Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeErrorModal();
            });
            
            // Auto-save
            let autoSaveTimer;
            document.getElementById('evaluationForm')?.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(saveDraft, 2000);
            });
        });
        
        function handleTextSelection(event) {
            if (event.target.classList.contains('error-mark')) return;
            
            const selection = window.getSelection();
            const selectedText = selection.toString().trim();
            
            if (selectedText.length > 0) {
                const container = event.target.closest('.text-marking-container');
                if (!container) return;
                
                const range = selection.getRangeAt(0);
                const startOffset = getTextOffset(container, range.startContainer, range.startOffset);
                const endOffset = getTextOffset(container, range.endContainer, range.endOffset);
                
                currentSelection = {
                    text: selectedText,
                    taskNumber: container.dataset.taskNumber,
                    answerId: container.dataset.answerId,
                    startOffset: startOffset,
                    endOffset: endOffset,
                    container: container
                };
                
                document.getElementById('selectedTextDisplay').textContent = selectedText;
                const modal = document.getElementById('errorTypeModal');
                modal.style.display = 'flex';
            }
        }
        
        function getTextOffset(container, node, offset) {
            let textOffset = 0;
            let walker = document.createTreeWalker(
                container,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );
            
            let currentNode;
            while (currentNode = walker.nextNode()) {
                if (currentNode === node) {
                    return textOffset + offset;
                }
                textOffset += currentNode.textContent.length;
            }
            return textOffset;
        }
        
        function markError(errorType) {
            if (!currentSelection) return;
            
            const marking = {
                id: ++markingIdCounter,
                text: currentSelection.text,
                taskNumber: currentSelection.taskNumber,
                answerId: currentSelection.answerId,
                startOffset: currentSelection.startOffset,
                endOffset: currentSelection.endOffset,
                errorType: errorType
            };
            
            errorMarkings.push(marking);
            updateErrorMarkingsInput();
            renderErrorMarkings();
            closeErrorModal();
            window.getSelection().removeAllRanges();
        }
        
        function renderErrorMarkings() {
            document.querySelectorAll('.text-marking-container').forEach(container => {
                const taskNumber = container.dataset.taskNumber;
                const relevantMarkings = errorMarkings
                    .filter(m => m.taskNumber === taskNumber)
                    .sort((a, b) => b.startOffset - a.startOffset);
                
                if (relevantMarkings.length === 0) {
                    const originalText = container.getAttribute('data-original-text') || container.textContent;
                    container.innerHTML = originalText;
                } else {
                    if (!container.hasAttribute('data-original-text')) {
                        container.setAttribute('data-original-text', container.textContent);
                    }
                    
                    const originalText = container.getAttribute('data-original-text');
                    let markedText = originalText;
                    
                    relevantMarkings.forEach(marking => {
                        const before = markedText.substring(0, marking.startOffset);
                        const marked = markedText.substring(marking.startOffset, marking.endOffset);
                        const after = markedText.substring(marking.endOffset);
                        
                        markedText = before + 
                            `<span class="error-mark ${marking.errorType}" data-marking-id="${marking.id}" onclick="window.removeMarking(${marking.id})" title="Click to remove">${marked}</span>` + 
                            after;
                    });
                    
                    container.innerHTML = markedText;
                }
                
                updateErrorSummary(taskNumber - 1);
            });
        }
        
        function removeMarking(markingId) {
            errorMarkings = errorMarkings.filter(m => m.id !== markingId);
            updateErrorMarkingsInput();
            renderErrorMarkings();
        }
        
        function updateErrorSummary(index) {
            const taskNumber = index + 1;
            const taskMarkings = errorMarkings.filter(m => m.taskNumber == taskNumber);
            const summaryContainer = document.getElementById(`errorSummary_${index}`);
            const errorList = document.getElementById(`errorList_${index}`);
            
            if (taskMarkings.length > 0) {
                summaryContainer.classList.remove('hidden');
                const grouped = taskMarkings.reduce((acc, marking) => {
                    if (!acc[marking.errorType]) acc[marking.errorType] = 0;
                    acc[marking.errorType]++;
                    return acc;
                }, {});
                
                errorList.innerHTML = Object.entries(grouped).map(([type, count]) => {
                    const labels = {
                        'task_achievement': 'Task Achievement',
                        'coherence_cohesion': 'Coherence',
                        'lexical_resource': 'Vocabulary',
                        'grammar': 'Grammar'
                    };
                    const colors = {
                        'task_achievement': 'bg-blue-100 text-blue-700',
                        'coherence_cohesion': 'bg-purple-100 text-purple-700',
                        'lexical_resource': 'bg-amber-100 text-amber-700',
                        'grammar': 'bg-red-100 text-red-700'
                    };
                    return `<span class="text-xs px-2 py-1 rounded-full ${colors[type]}">${labels[type]}: ${count}</span>`;
                }).join('');
            } else {
                summaryContainer.classList.add('hidden');
            }
        }
        
        function updateErrorMarkingsInput() {
            document.getElementById('errorMarkingsInput').value = JSON.stringify(errorMarkings);
        }
        
        function closeErrorModal() {
            const modal = document.getElementById('errorTypeModal');
            modal.style.display = 'none';
            currentSelection = null;
            window.getSelection().removeAllRanges();
        }
        
        function addStrength() {
            const container = document.getElementById('strengths-container');
            const div = document.createElement('div');
            div.className = 'strength-input flex items-center gap-2';
            div.innerHTML = `
                <input type="text" 
                       name="strengths[]" 
                       class="flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Enter a strength..."
                       required>
                <button type="button" onclick="removeField(this)" class="text-red-500 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }
        
        function addImprovement() {
            const container = document.getElementById('improvements-container');
            const div = document.createElement('div');
            div.className = 'improvement-input flex items-center gap-2';
            div.innerHTML = `
                <input type="text" 
                       name="improvements[]" 
                       class="flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Enter an improvement..."
                       required>
                <button type="button" onclick="removeField(this)" class="text-red-500 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }
        
        function removeField(button) {
            button.closest('.strength-input, .improvement-input').remove();
        }
        
        function updateProgress() {
            const form = document.getElementById('evaluationForm');
            if (!form) return;
            
            const allFields = form.querySelectorAll('select[required], input[required], textarea[required]');
            const filledFields = Array.from(allFields).filter(field => field.value).length;
            const progress = Math.round((filledFields / allFields.length) * 100);
            
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressText').textContent = progress + '%';
        }
        
        function saveDraft() {
            const form = document.getElementById('evaluationForm');
            if (!form) return;
            
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }
            
            localStorage.setItem('evaluation_draft_{{ $evaluationRequest->id }}', JSON.stringify(data));
            
            const saveStatus = document.getElementById('saveStatus');
            saveStatus.classList.remove('hidden');
            setTimeout(() => saveStatus.classList.add('hidden'), 3000);
        }
    </script>
    @endpush
</x-teacher-layout>
