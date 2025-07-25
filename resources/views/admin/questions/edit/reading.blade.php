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
                                        <textarea id="instructions" name="instructions" class="tinymce-editor-simple">{{ old('instructions', $question->instructions) }}</textarea>
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
                                        <textarea id="content" name="content" class="tinymce-editor" required>{{ old('content', $question->content) }}</textarea>
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
                    @if(in_array($question->question_type, ['multiple_choice', 'true_false', 'yes_no', 'matching_information', 'matching_features']))
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
                    
                    @if($question->question_type === 'matching_headings')
                    <div class="bg-white rounded-lg shadow-sm" id="matching-headings-card">
                        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                            <h3 class="text-lg font-medium text-gray-900">
                                <svg class="w-5 h-5 inline mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                Matching Headings Configuration
                            </h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Step 1: Headings List -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-xs rounded-full mr-2">1</span>
                                        List of Headings
                                    </h4>
                                    <span class="text-sm text-gray-500" id="heading-count">{{ $question->options->count() }} headings</span>
                                </div>
                                <div id="matching-headings-container" class="space-y-2">
                                    @foreach($question->options->sortBy('order') as $index => $option)
                                        <div class="flex items-center gap-2 p-3 bg-white rounded border border-gray-200" data-heading-index="{{ $index }}">
                                            <span class="font-semibold text-gray-700 min-w-[30px]">{{ chr(65 + $index) }}.</span>
                                            <input type="text" 
                                                   data-heading-id="{{ chr(65 + $index) }}"
                                                   name="options[{{ $index }}][content]" 
                                                   value="{{ $option->content }}" 
                                                   class="heading-input flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                   placeholder="Enter heading text..." 
                                                   onkeyup="MatchingHeadingsManager.updateDropdowns()"
                                                   required>
                                            <button type="button" onclick="MatchingHeadingsManager.removeHeading({{ $index }})" 
                                                    class="text-red-500 hover:text-red-700 p-1">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add-heading-btn" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                    + Add Heading
                                </button>
                            </div>
                            
                            <!-- Step 2: Question Mappings -->
                            <div class="border-t pt-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-600 text-white text-xs rounded-full mr-2">2</span>
                                        Question Mappings
                                    </h4>
                                    <span class="text-sm text-gray-500" id="question-count">
                                        {{ isset($question->section_specific_data['mappings']) ? count($question->section_specific_data['mappings']) : 0 }} questions
                                    </span>
                                </div>
                                <div id="question-mappings-container" class="space-y-2">
                                    @if(isset($question->section_specific_data['mappings']))
                                        @foreach($question->section_specific_data['mappings'] as $index => $mapping)
                                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200" data-question-index="{{ $index }}">
                                                <span class="font-medium text-gray-700 min-w-[140px]">
                                                    Question {{ $mapping['question'] }} - Paragraph {{ $mapping['paragraph'] }}:
                                                </span>
                                                <select class="question-select flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                        data-question-index="{{ $index }}"
                                                        onchange="MatchingHeadingsManager.updateMappingData()">
                                                    <option value="">Select correct heading</option>
                                                    @foreach($question->options as $optionIndex => $option)
                                                        <option value="{{ chr(65 + $optionIndex) }}" 
                                                                {{ $mapping['correct'] == chr(65 + $optionIndex) ? 'selected' : '' }}>
                                                            {{ chr(65 + $optionIndex) }}. {{ $option->content }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button" onclick="MatchingHeadingsManager.removeQuestionMapping({{ $index }})" 
                                                        class="text-red-500 hover:text-red-700 p-1">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" id="add-question-mapping-btn" class="mt-3 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium" {{ $question->options->count() >= 2 ? '' : 'disabled' }}>
                                    + Add Question
                                </button>
                            </div>
                            
                            <!-- Hidden input for storing JSON data -->
                            <input type="hidden" id="matching_headings_data" name="matching_headings_data" 
                                   value='{{ json_encode($question->section_specific_data ?? ["headings" => [], "mappings" => []]) }}'>
                            
                            <!-- Hidden JSON input for submission -->
                            <input type="hidden" id="matching_headings_json" name="matching_headings_json">
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
            // Initialize TinyMCE for instructions (simple editor)
            tinymce.init({
                selector: '.tinymce-editor-simple',
                height: 150,
                menubar: false,
                plugins: [
                    'lists', 'link', 'charmap', 'code'
                ],
                toolbar: 'bold italic underline | fontsize | bullist numlist | alignleft aligncenter alignright | link | removeformat code',
                font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
                content_css: '//www.tiny.cloud/css/codepen.min.css',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
            
            // Initialize TinyMCE for main content
            tinymce.init({
                selector: '.tinymce-editor',
                height: 350,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                    'preview', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | formatselect | fontsize | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat code',
                font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
                content_css: '//www.tiny.cloud/css/codepen.min.css',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
            
            @if($question->question_type === 'fill_blanks')
            // Re-scan blanks after TinyMCE loads
            setTimeout(function() {
                if (typeof updateBlanks === 'function') {
                    updateBlanks();
                }
            }, 1000);
            @endif
            
            @if($question->question_type === 'matching_headings')
            // Initialize matching headings manager
            setTimeout(function() {
                if (window.MatchingHeadingsManager) {
                    window.MatchingHeadingsManager.init();
                }
            }, 100);
            
            // Add form submission handler for matching headings
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if ('{{ $question->question_type }}' === 'matching_headings') {
                        // Ensure the JSON data is updated
                        if (window.MatchingHeadingsManager) {
                            window.MatchingHeadingsManager.updateMappingData();
                            
                            // Double-check data is in form
                            const jsonInput = document.getElementById('matching_headings_json');
                            const dataInput = document.getElementById('matching_headings_data');
                            
                            if (jsonInput && dataInput) {
                                const data = JSON.parse(dataInput.value || '{}');
                                console.log('Submitting matching headings data:', data);
                                
                                // Validate before submission
                                if (!data.headings || data.headings.length < 2) {
                                    e.preventDefault();
                                    alert('Please add at least 2 headings');
                                    return false;
                                }
                            }
                        }
                    }
                });
            }
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
        
        // Enhanced Matching Headings Manager for Edit Page
        const MatchingHeadingsManager = {
            headingCount: {{ $question->question_type === 'matching_headings' ? $question->options->count() : 0 }},
            questionCount: {{ $question->question_type === 'matching_headings' && isset($question->section_specific_data['mappings']) ? count($question->section_specific_data['mappings']) : 0 }},
            headings: [],
            mappings: [],
            
            init() {
                const addHeadingBtn = document.getElementById('add-heading-btn');
                const addQuestionBtn = document.getElementById('add-question-mapping-btn');
                
                if (addHeadingBtn) {
                    addHeadingBtn.addEventListener('click', () => this.addHeading());
                }
                
                if (addQuestionBtn) {
                    addQuestionBtn.addEventListener('click', () => this.addQuestionMapping());
                }
                
                // Load existing data
                const dataInput = document.getElementById('matching_headings_data');
                if (dataInput && dataInput.value) {
                    try {
                        const data = JSON.parse(dataInput.value);
                        this.headings = data.headings || [];
                        this.mappings = data.mappings || [];
                    } catch (e) {
                        console.error('Error parsing existing data:', e);
                    }
                }
                
                // Update initial state
                this.updateMappingData();
            },
            
            addHeading(content = '') {
                const container = document.getElementById('matching-headings-container');
                if (!container) return;
                
                const index = this.headingCount;
                const letter = String.fromCharCode(65 + index);
                
                const headingDiv = document.createElement('div');
                headingDiv.className = 'flex items-center gap-2 p-3 bg-white rounded border border-gray-200';
                headingDiv.setAttribute('data-heading-index', index);
                headingDiv.innerHTML = `
                    <span class="font-semibold text-gray-700 min-w-[30px]">${letter}.</span>
                    <input type="text" 
                           data-heading-id="${letter}"
                           name="options[${index}][content]" 
                           value="${content}" 
                           class="heading-input flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter heading text..." 
                           onkeyup="MatchingHeadingsManager.updateDropdowns()"
                           required>
                    <button type="button" onclick="MatchingHeadingsManager.removeHeading(${index})" 
                            class="text-red-500 hover:text-red-700 p-1">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(headingDiv);
                this.headingCount++;
                
                // Update counter
                document.getElementById('heading-count').textContent = `${this.headingCount} headings`;
                
                // Enable question mapping button
                const addQuestionBtn = document.getElementById('add-question-mapping-btn');
                if (addQuestionBtn && this.headingCount >= 2) {
                    addQuestionBtn.disabled = false;
                }
                
                // Update all dropdowns
                this.updateDropdowns();
            },
            
            removeHeading(index) {
                if (this.headingCount <= 2) {
                    alert('You must have at least 2 headings.');
                    return;
                }
                
                const container = document.getElementById('matching-headings-container');
                const headingDiv = container.querySelector(`[data-heading-index="${index}"]`);
                if (headingDiv) {
                    headingDiv.remove();
                    this.reindexHeadings();
                }
            },
            
            reindexHeadings() {
                const container = document.getElementById('matching-headings-container');
                const headings = container.querySelectorAll('div');
                this.headingCount = 0;
                
                headings.forEach((heading, index) => {
                    const letter = String.fromCharCode(65 + index);
                    heading.setAttribute('data-heading-index', index);
                    heading.querySelector('span').textContent = letter + '.';
                    heading.querySelector('.heading-input').setAttribute('data-heading-id', letter);
                    heading.querySelector('.heading-input').name = `options[${index}][content]`;
                    
                    const btn = heading.querySelector('button');
                    btn.setAttribute('onclick', `MatchingHeadingsManager.removeHeading(${index})`);
                    
                    this.headingCount++;
                });
                
                // Update counter
                document.getElementById('heading-count').textContent = `${this.headingCount} headings`;
                
                // Update all dropdowns
                this.updateDropdowns();
                
                // Disable add question button if less than 2 headings
                const addQuestionBtn = document.getElementById('add-question-mapping-btn');
                if (addQuestionBtn && this.headingCount < 2) {
                    addQuestionBtn.disabled = true;
                }
            },
            
            addQuestionMapping() {
                const container = document.getElementById('question-mappings-container');
                if (!container) return;
                
                const index = this.questionCount;
                const paragraphLetter = String.fromCharCode(65 + index);
                
                const mappingDiv = document.createElement('div');
                mappingDiv.className = 'flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
                mappingDiv.setAttribute('data-question-index', index);
                mappingDiv.innerHTML = `
                    <span class="font-medium text-gray-700 min-w-[140px]">
                        Question ${index + 1} - Paragraph ${paragraphLetter}:
                    </span>
                    <select class="question-select flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                            data-question-index="${index}"
                            onchange="MatchingHeadingsManager.updateMappingData()">
                        <option value="">Select correct heading</option>
                        ${this.getHeadingOptions()}
                    </select>
                    <button type="button" onclick="MatchingHeadingsManager.removeQuestionMapping(${index})" 
                            class="text-red-500 hover:text-red-700 p-1">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(mappingDiv);
                this.questionCount++;
                
                // Update counter
                document.getElementById('question-count').textContent = `${this.questionCount} questions`;
                
                // Update mapping data
                this.updateMappingData();
            },
            
            removeQuestionMapping(index) {
                const container = document.getElementById('question-mappings-container');
                const mappingDiv = container.querySelector(`[data-question-index="${index}"]`);
                if (mappingDiv) {
                    mappingDiv.remove();
                    this.reindexQuestions();
                }
            },
            
            reindexQuestions() {
                const container = document.getElementById('question-mappings-container');
                const questions = container.querySelectorAll('div');
                this.questionCount = 0;
                
                questions.forEach((question, index) => {
                    const paragraphLetter = String.fromCharCode(65 + index);
                    question.setAttribute('data-question-index', index);
                    question.querySelector('span').textContent = `Question ${index + 1} - Paragraph ${paragraphLetter}:`;
                    question.querySelector('.question-select').setAttribute('data-question-index', index);
                    
                    const btn = question.querySelector('button');
                    btn.setAttribute('onclick', `MatchingHeadingsManager.removeQuestionMapping(${index})`);
                    
                    this.questionCount++;
                });
                
                // Update counter
                document.getElementById('question-count').textContent = `${this.questionCount} questions`;
                
                // Update mapping data
                this.updateMappingData();
            },
            
            getHeadingOptions() {
                const headings = document.querySelectorAll('.heading-input');
                let options = '';
                
                headings.forEach((heading, index) => {
                    const letter = String.fromCharCode(65 + index);
                    const text = heading.value || `Heading ${letter}`;
                    options += `<option value="${letter}">${letter}. ${text}</option>`;
                });
                
                return options;
            },
            
            updateDropdowns() {
                const selects = document.querySelectorAll('.question-select');
                const newOptions = '<option value="">Select correct heading</option>' + this.getHeadingOptions();
                
                selects.forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = newOptions;
                    select.value = currentValue; // Restore previous selection
                });
                
                // Update mapping data
                this.updateMappingData();
            },
            
            updateMappingData() {
                // Collect all headings
                this.headings = [];
                document.querySelectorAll('.heading-input').forEach((input, index) => {
                    const letter = String.fromCharCode(65 + index);
                    this.headings.push({
                        id: letter,
                        text: input.value || ''
                    });
                });
                
                // Collect all mappings
                this.mappings = [];
                document.querySelectorAll('.question-select').forEach((select, index) => {
                    const paragraphLetter = String.fromCharCode(65 + index);
                    if (select.value) {
                        this.mappings.push({
                            question: index + 1,
                            paragraph: paragraphLetter,
                            correct: select.value
                        });
                    }
                });
                
                // Update hidden inputs with JSON data
                const dataInput = document.getElementById('matching_headings_data');
                const jsonInput = document.getElementById('matching_headings_json');
                
                const data = {
                    headings: this.headings,
                    mappings: this.mappings
                };
                
                if (dataInput) {
                    dataInput.value = JSON.stringify(data);
                }
                
                if (jsonInput) {
                    jsonInput.value = JSON.stringify(data);
                }
                
                console.log('Updated matching headings data:', data);
            }
        };
        
        // Make it globally available
        window.MatchingHeadingsManager = MatchingHeadingsManager;
    </script>
    @endpush
</x-layout>