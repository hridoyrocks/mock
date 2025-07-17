<x-layout>
    <x-slot:title>Edit Reading Question</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">📖 Edit Reading Question #{{ $question->order_number }}</h1>
                        <p class="text-green-100 text-sm mt-1">{{ $testSet->title }}</p>
                    </div>
                    <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Question Content -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Question Content</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-6">
                                    <!-- Instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Instructions / Passage Title
                                        </label>
                                        <textarea id="instructions" name="instructions" rows="2" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="e.g., 'The History of Aviation' OR 'Questions 1-5: Choose the correct letter'">{{ old('instructions', $question->instructions) }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $question->question_type === 'passage' ? 'Passage Content' : 'Question' }} <span class="text-red-500">*</span>
                                        </label>
                                        @if($question->question_type === 'fill_blanks')
                                        <div class="mb-3 flex space-x-2">
                                            <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                                Insert Blank ____
                                            </button>
                                            <button type="button" onclick="insertDropdown()" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">
                                                Insert Dropdown ▼
                                            </button>
                                        </div>
                                        @endif
                    
                    <!-- Blank Answers Display (for fill-in-the-blank questions) -->
                    @if(in_array($question->question_type, ['sentence_completion', 'note_completion', 'summary_completion', 'form_completion']))
                    <div class="bg-white rounded-lg shadow-sm" id="blank-answers-section">
                        <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                            <h3 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-fill-drip mr-2"></i>Fill in the Blank Answers
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div id="blank-answers-container" class="space-y-3">
                                @php
                                    // Extract blanks from content
                                    preg_match_all('/\[____(\d+)____\]/', $question->content, $matches);
                                    $blankNumbers = array_unique($matches[1]);
                                    sort($blankNumbers);
                                @endphp
                                
                                @if(count($blankNumbers) > 0)
                                    @foreach($blankNumbers as $index => $blankNum)
                                        @php
                                            $blank = $question->blanks()->where('blank_number', $blankNum)->first();
                                            $answer = '';
                                            
                                            if ($blank) {
                                                $answer = $blank->correct_answer;
                                                if ($blank->alternate_answers) {
                                                    $answer .= '|' . implode('|', $blank->alternate_answers);
                                                }
                                            }
                                        @endphp
                                        
                                        <div class="flex items-center gap-3 bg-white p-3 rounded border border-gray-200">
                                            <label class="text-sm font-medium text-gray-700 w-24">
                                                Blank {{ $blankNum }}:
                                            </label>
                                            <input type="text" 
                                                   name="blank_answers[]" 
                                                   value="{{ $answer }}"
                                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                                   placeholder="Answer (use | for alternatives: answer1|answer2)"
                                                   required>
                                            <span class="text-xs text-gray-500">[____{{ $blankNum }}____]</span>
                                            
                                            @if($blank)
                                                <span class="text-green-600" title="Saved in database">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @else
                                                <span class="text-red-600" title="Not saved">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    <div class="mt-3 p-3 bg-blue-50 rounded">
                                        <p class="text-sm text-blue-800">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            <strong>Tips:</strong> Use pipe (|) to separate alternative correct answers. 
                                            Example: <code class="bg-white px-1 rounded">color|colour</code>
                                        </p>
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">
                                        No blanks found. Use [____1____] format in content to create blanks.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                                        <textarea id="content" name="content" class="tinymce" required>{{ old('content', $question->content) }}</textarea>
                                        @if($question->question_type === 'passage')
                                        <input type="hidden" name="passage_text" value="{{ old('passage_text', $question->passage_text ?? $question->content) }}">
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <!-- Question Type (Read-only) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                        <input type="text" value="{{ $question->question_type }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                                        <input type="hidden" name="question_type" value="{{ $question->question_type }}">
                                    </div>
                                    
                                    <!-- Question Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Number <span class="text-red-500">*</span></label>
                                        <input type="number" name="order_number" value="{{ old('order_number', $question->order_number) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" min="0" required>
                                    </div>
                                    
                                    <!-- Part Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Passage <span class="text-red-500">*</span></label>
                                        <select name="part_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                                            <option value="1" {{ $question->part_number == 1 ? 'selected' : '' }}>Passage 1</option>
                                            <option value="2" {{ $question->part_number == 2 ? 'selected' : '' }}>Passage 2</option>
                                            <option value="3" {{ $question->part_number == 3 ? 'selected' : '' }}>Passage 3</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Marks -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Marks</label>
                                        <input type="number" name="marks" value="{{ old('marks', $question->marks) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" min="0" max="40">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Options Manager (if applicable) -->
                    @if(in_array($question->question_type, ['multiple_choice', 'true_false', 'yes_no', 'matching_headings', 'matching_information', 'matching_features']))
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Answer Options</h3>
                        </div>
                        
                        <div class="p-6">
                            <div id="options-container" class="space-y-3">
                                @foreach($question->options as $index => $option)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <input type="radio" name="correct_option" value="{{ $index }}" 
                                           class="h-4 w-4 text-blue-600" {{ $option->is_correct ? 'checked' : '' }}>
                                    <span class="font-medium text-gray-700">{{ chr(65 + $index) }}.</span>
                                    <input type="text" name="options[{{ $index }}][content]" value="{{ $option->content }}" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                                    <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="add-option-btn" onclick="addOption()"
                                    class="mt-4 w-full px-4 py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-md hover:border-gray-400 hover:text-gray-600 transition-all">
                                + Add Option
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Fill in the Blanks Configuration (if applicable) -->
                    @if($question->question_type === 'fill_blanks' && $question->section_specific_data)
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                            <h3 class="text-lg font-medium text-gray-900">Fill in the Blanks Configuration</h3>
                        </div>
                        
                        <div class="p-6">
                            <div id="blanks-manager">
                                <div id="blanks-list" class="space-y-2">
                                    @if(isset($question->section_specific_data['blank_answers']))
                                        @foreach($question->section_specific_data['blank_answers'] as $num => $answer)
                                        <div class="flex items-center space-x-2 p-2 bg-white rounded border border-gray-200">
                                            <span class="text-sm font-medium text-gray-700 w-20">Blank {{ $num }}:</span>
                                            <input type="text" 
                                                   name="blank_answers[{{ $num }}]" 
                                                   class="flex-1 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" 
                                                   value="{{ $answer }}"
                                                   required>
                                        </div>
                                        @endforeach
                                    @endif
                                    
                                    @if(isset($question->section_specific_data['dropdown_options']))
                                        @foreach($question->section_specific_data['dropdown_options'] as $num => $options)
                                        <div class="flex items-center space-x-2 p-2 bg-white rounded border border-gray-200">
                                            <span class="text-sm font-medium text-gray-700 w-20">Dropdown {{ $num }}:</span>
                                            <input type="text" 
                                                   value="{{ $options }}" 
                                                   name="dropdown_options[{{ $num }}]" 
                                                   class="flex-1 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                            <select name="dropdown_correct[{{ $num }}]" 
                                                    class="px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                                @foreach(explode(',', $options) as $idx => $opt)
                                                <option value="{{ $idx }}" {{ ($question->section_specific_data['dropdown_correct'][$num] ?? 0) == $idx ? 'selected' : '' }}>
                                                    {{ trim($opt) }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                                    Update Question
                                </button>
                                <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                                   class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 text-center transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script>
        // Initialize TinyMCE
        document.addEventListener('DOMContentLoaded', function() {
            initializeTinyMCE('.tinymce');
            
            @if($question->question_type === 'fill_blanks')
            // Re-scan blanks after TinyMCE loads
            setTimeout(function() {
                if (typeof updateBlanks === 'function') {
                    updateBlanks();
                }
            }, 1000);
            @endif
        });
        
        @if($question->question_type === 'fill_blanks')
        // Basic fill blanks functionality
        let blankCounter = {{ $question->blank_count ?? 0 }};
        let dropdownCounter = 0;
        
        window.insertBlank = function() {
            const editor = tinymce.get('content');
            if (editor) {
                blankCounter++;
                const blankHtml = `<span class="blank-placeholder" data-blank="${blankCounter}" contenteditable="false">[____${blankCounter}____]</span>&nbsp;`;
                editor.insertContent(blankHtml);
            }
        };
        
        window.insertDropdown = function() {
            const editor = tinymce.get('content');
            if (editor) {
                const options = prompt('Enter dropdown options separated by comma:');
                if (options) {
                    dropdownCounter++;
                    const dropdownHtml = `<span class="dropdown-placeholder" data-dropdown="${dropdownCounter}" data-options="${options}" contenteditable="false">[DROPDOWN_${dropdownCounter}]</span>&nbsp;`;
                    editor.insertContent(dropdownHtml);
                }
            }
        };
        @endif
    </script>
    @endpush
</x-layout>