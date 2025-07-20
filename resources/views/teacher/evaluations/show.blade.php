<x-teacher-layout>
    <x-slot:title>Evaluate - {{ ucfirst($evaluationRequest->studentAttempt->testSet->section->name) }}</x-slot>
    
    <x-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-white">
                Evaluate {{ ucfirst($evaluationRequest->studentAttempt->testSet->section->name) }} Test
            </h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-400">
                    Status: 
                    <span class="font-semibold text-white">{{ ucfirst($evaluationRequest->status) }}</span>
                </span>
                <span class="text-sm text-gray-400">
                    Deadline: 
                    <span class="font-semibold text-white">{{ $evaluationRequest->deadline_at->format('M d, Y h:i A') }}</span>
                </span>
            </div>
        </div>
    </x-slot>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Student Information -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-medium text-gray-900">{{ $evaluationRequest->student->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium text-gray-900">{{ $evaluationRequest->student->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Test Taken</p>
                    <p class="font-medium text-gray-900">{{ $evaluationRequest->studentAttempt->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Test Details -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Test Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Test Set</p>
                    <p class="font-medium text-gray-900">{{ $evaluationRequest->studentAttempt->testSet->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Section</p>
                    <p class="font-medium text-gray-900">{{ ucfirst($evaluationRequest->studentAttempt->testSet->section->name) }}</p>
                </div>
            </div>
        </div>
        
        @if($evaluationRequest->status === 'completed' && $evaluationRequest->humanEvaluation)
            <!-- Completed Evaluation -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-green-900 mb-2">Evaluation Completed</h3>
                <p class="text-green-700">This evaluation was completed on {{ $evaluationRequest->completed_at->format('M d, Y h:i A') }}</p>
                <p class="text-2xl font-bold text-green-900 mt-2">
                    Overall Band Score: {{ $evaluationRequest->humanEvaluation->overall_band_score }}
                </p>
            </div>
            
            <!-- View Submitted Evaluation -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Submitted Evaluation</h2>
                
                @php
                    $evaluation = $evaluationRequest->humanEvaluation;
                    $sectionName = $evaluationRequest->studentAttempt->testSet->section->name;
                @endphp
                
                <!-- Task Scores -->
                <div class="space-y-6 mb-6">
                    @foreach($evaluation->task_scores as $index => $taskScore)
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Task {{ $index + 1 }}</h4>
                            
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600">Overall Score</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $taskScore['score'] }}</p>
                                </div>
                                
                                @if($sectionName === 'writing')
                                    <div>
                                        <p class="text-sm text-gray-600">Task Achievement</p>
                                        <p class="font-medium">{{ $taskScore['task_achievement'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Coherence & Cohesion</p>
                                        <p class="font-medium">{{ $taskScore['coherence_cohesion'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Lexical Resource</p>
                                        <p class="font-medium">{{ $taskScore['lexical_resource'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Grammar</p>
                                        <p class="font-medium">{{ $taskScore['grammar'] }}</p>
                                    </div>
                                @else
                                    <div>
                                        <p class="text-sm text-gray-600">Fluency & Coherence</p>
                                        <p class="font-medium">{{ $taskScore['fluency_coherence'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Lexical Resource</p>
                                        <p class="font-medium">{{ $taskScore['lexical_resource'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Grammar</p>
                                        <p class="font-medium">{{ $taskScore['grammar'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Pronunciation</p>
                                        <p class="font-medium">{{ $taskScore['pronunciation'] }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Feedback</p>
                                <p class="text-gray-900">{{ $taskScore['feedback'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Strengths & Improvements -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Strengths</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($evaluation->strengths as $strength)
                                <li class="text-gray-700">{{ $strength }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Areas for Improvement</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($evaluation->improvements as $improvement)
                                <li class="text-gray-700">{{ $improvement }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <!-- Evaluation Form -->
            <form action="{{ route('teacher.evaluations.submit', $evaluationRequest) }}" method="POST" id="evaluationForm">
                @csrf
                
                <!-- Student Responses -->
                <div class="bg-white rounded-lg shadow mb-6 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Student Responses</h2>
                    
                    @php
                        $sectionName = $evaluationRequest->studentAttempt->testSet->section->name;
                    @endphp
                    
                    @if($sectionName === 'writing')
                        <!-- Writing Responses -->
                        @foreach($evaluationRequest->studentAttempt->answers as $index => $answer)
                            <div class="mb-6 pb-6 {{ !$loop->last ? 'border-b' : '' }}">
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    Task {{ $index + 1 }}: {{ $answer->question->title }}
                                </h3>
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Question:</p>
                                    <div class="text-gray-900">{!! $answer->question->content !!}</div>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 mb-2">Student's Response:</p>
                                    <div class="text-gray-900 whitespace-pre-wrap">{{ $answer->answer_text }}</div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Word count: {{ str_word_count($answer->answer_text) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Speaking Responses -->
                        @foreach($evaluationRequest->studentAttempt->answers as $index => $answer)
                            <div class="mb-6 pb-6 {{ !$loop->last ? 'border-b' : '' }}">
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    Part {{ $answer->question->part_number }}: {{ $answer->question->title }}
                                </h3>
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Question:</p>
                                    <div class="text-gray-900">{!! $answer->question->content !!}</div>
                                </div>
                                @if($answer->speakingRecording)
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 mb-2">Student's Recording:</p>
                                        <audio controls class="w-full">
                                            <source src="{{ Storage::url($answer->speakingRecording->file_path) }}" type="audio/webm">
                                            Your browser does not support the audio element.
                                        </audio>
                                        <p class="text-xs text-gray-500 mt-2">
                                            Duration: {{ gmdate('i:s', $answer->speakingRecording->duration_seconds) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <!-- Evaluation Criteria -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Evaluation</h2>
                    
                    <!-- Task Scores -->
                    <div class="space-y-6 mb-6">
                        @foreach($evaluationRequest->studentAttempt->answers as $index => $answer)
                            <div class="border rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">
                                    @if($sectionName === 'writing')
                                        Task {{ $index + 1 }}
                                    @else
                                        Part {{ $answer->question->part_number }}
                                    @endif
                                    Evaluation
                                </h4>
                                
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Overall Score
                                        </label>
                                        <select name="task_scores[{{ $index }}][score]" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                required>
                                            <option value="">Select</option>
                                            @for($i = 0; $i <= 9; $i += 0.5)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    @if($sectionName === 'writing')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Task Achievement
                                            </label>
                                            <select name="task_scores[{{ $index }}][task_achievement]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Coherence & Cohesion
                                            </label>
                                            <select name="task_scores[{{ $index }}][coherence_cohesion]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Lexical Resource
                                            </label>
                                            <select name="task_scores[{{ $index }}][lexical_resource]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Grammar
                                            </label>
                                            <select name="task_scores[{{ $index }}][grammar]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    @else
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Fluency & Coherence
                                            </label>
                                            <select name="task_scores[{{ $index }}][fluency_coherence]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Lexical Resource
                                            </label>
                                            <select name="task_scores[{{ $index }}][lexical_resource]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Grammar
                                            </label>
                                            <select name="task_scores[{{ $index }}][grammar]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Pronunciation
                                            </label>
                                            <select name="task_scores[{{ $index }}][pronunciation]" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                <option value="">Select</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Detailed Feedback
                                    </label>
                                    <textarea name="task_scores[{{ $index }}][feedback]" 
                                              rows="4"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                              placeholder="Provide specific feedback on this task..."
                                              required></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Overall Assessment -->
                    <div class="border-t pt-6">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Overall Band Score
                            </label>
                            <select name="overall_band_score" 
                                    class="w-full max-w-xs rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    required>
                                <option value="">Select Overall Band Score</option>
                                @for($i = 0; $i <= 9; $i += 0.5)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Key Strengths
                                </label>
                                <div id="strengths-container">
                                    <div class="strength-input mb-2">
                                        <input type="text" 
                                               name="strengths[]" 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                               placeholder="Enter a strength..."
                                               required>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="addStrength()"
                                        class="text-sm text-emerald-600 hover:text-emerald-700">
                                    <i class="fas fa-plus mr-1"></i>Add another strength
                                </button>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Areas for Improvement
                                </label>
                                <div id="improvements-container">
                                    <div class="improvement-input mb-2">
                                        <input type="text" 
                                               name="improvements[]" 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                               placeholder="Enter an area for improvement..."
                                               required>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="addImprovement()"
                                        class="text-sm text-emerald-600 hover:text-emerald-700">
                                    <i class="fas fa-plus mr-1"></i>Add another improvement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('teacher.evaluations.pending') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 border border-transparent rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Submit Evaluation
                    </button>
                </div>
            </form>
        @endif
    </div>
    
    @push('scripts')
    <script>
        function addStrength() {
            const container = document.getElementById('strengths-container');
            const div = document.createElement('div');
            div.className = 'strength-input mb-2 flex items-center';
            div.innerHTML = `
                <input type="text" 
                       name="strengths[]" 
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                       placeholder="Enter a strength..."
                       required>
                <button type="button" onclick="removeField(this)" class="ml-2 text-red-600 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }
        
        function addImprovement() {
            const container = document.getElementById('improvements-container');
            const div = document.createElement('div');
            div.className = 'improvement-input mb-2 flex items-center';
            div.innerHTML = `
                <input type="text" 
                       name="improvements[]" 
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                       placeholder="Enter an area for improvement..."
                       required>
                <button type="button" onclick="removeField(this)" class="ml-2 text-red-600 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }
        
        function removeField(button) {
            button.parentElement.remove();
        }
        
        // Auto-save form data
        let autoSaveTimer;
        const form = document.getElementById('evaluationForm');
        
        if (form) {
            form.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    // Save form data to localStorage
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
                    
                    // Show saved indicator
                    const savedIndicator = document.createElement('div');
                    savedIndicator.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg';
                    savedIndicator.textContent = 'Draft saved';
                    document.body.appendChild(savedIndicator);
                    
                    setTimeout(function() {
                        savedIndicator.remove();
                    }, 2000);
                }, 1000);
            });
            
            // Restore saved data on page load
            const savedData = localStorage.getItem('evaluation_draft_{{ $evaluationRequest->id }}');
            if (savedData) {
                const data = JSON.parse(savedData);
                // Restore form fields
                // Implementation depends on your specific needs
            }
        }
    </script>
    @endpush
</x-teacher-layout>