<x-layout>
    <x-slot:title>Add Question - Reading</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">📖 Add Reading Question</h1>
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
            
            @include('admin.questions.partials.question-header')
            
            <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                <input type="hidden" name="test_set_id" value="{{ $testSet->id }}">
                
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
                                                  placeholder="e.g., 'The History of Aviation' OR 'Questions 1-5: Choose the correct letter'">{{ old('instructions') }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div id="question-content-field">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mb-3 flex space-x-2" id="blank-buttons" style="display: none;">
                                            <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                                Insert Blank ____
                                            </button>
                                            <button type="button" onclick="insertDropdown()" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">
                                                Insert Dropdown ▼
                                            </button>
                                        </div>
                                        <textarea id="content" name="content" class="tinymce">{{ old('content') }}</textarea>
                                    </div>
                                    
                                    <!-- Blanks Manager -->
                                    <div id="blanks-manager" class="hidden">
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                            <h4 class="text-sm font-medium text-yellow-800 mb-2">Fill in the Blanks Configuration</h4>
                                            <div id="blanks-list" class="space-y-2">
                                                <!-- Dynamically populated -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Passage Content (Hidden by default) -->
                                    <div id="passage-content-field" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Passage Content <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="passage_text" name="passage_text" class="tinymce-passage">{{ old('passage_text') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    @include('admin.questions.partials.question-settings', [
                                        'questionTypes' => [
                                            'passage' => '📄 Reading Passage',
                                            'multiple_choice' => 'Multiple Choice',
                                            'true_false' => 'True/False/Not Given',
                                            'yes_no' => 'Yes/No/Not Given',
                                            'matching_headings' => 'Matching Headings',
                                            'matching_information' => 'Matching Information',
                                            'matching_features' => 'Matching Features',
                                            'sentence_completion' => 'Sentence Completion',
                                            'summary_completion' => 'Summary Completion',
                                            'short_answer' => 'Short Answer',
                                            'fill_blanks' => 'Fill in the Blanks'
                                        ]
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Options Manager -->
                    @include('admin.questions.partials.options-manager')
                    
                    @include('admin.questions.partials.action-buttons')
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script>
    // Simple Reading specific functionality
    let passageEditor = null;
    let contentEditor = null;

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize TinyMCE for regular content
        initializeTinyMCE('.tinymce');

        // Setup question type handler
        const questionType = document.getElementById('question_type');
        if (questionType) {
            questionType.addEventListener('change', handleReadingQuestionTypeChange);
        }
    });

    // Handle question type changes
    function handleReadingQuestionTypeChange() {
        const type = this.value;
        const questionContentField = document.getElementById('question-content-field');
        const passageContentField = document.getElementById('passage-content-field');
        const blanksManager = document.getElementById('blanks-manager');
        const blankButtons = document.getElementById('blank-buttons');

        // Reset displays
        if (questionContentField) questionContentField.classList.remove('hidden');
        if (passageContentField) passageContentField.classList.add('hidden');
        if (blanksManager) blanksManager.classList.add('hidden');
        if (blankButtons) blankButtons.style.display = 'none';

        if (type === 'passage') {
            // Show passage content field, hide question field
            if (questionContentField) questionContentField.classList.add('hidden');
            if (passageContentField) passageContentField.classList.remove('hidden');

            // Initialize passage editor
            if (!passageEditor && typeof tinymce !== 'undefined') {
                setTimeout(() => {
                    tinymce.init({
                        selector: '.tinymce-passage',
                        height: 500,
                        menubar: true,
                        plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
                        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | help',
                        content_style: 'body { font-family: Georgia, Times New Roman, serif; font-size: 16px; line-height: 1.8; color: #333; }',
                        setup: function (editor) {
                            passageEditor = editor;
                        }
                    });
                }, 100);
            }

            // Set default values for passage
            const orderInput = document.querySelector('input[name="order_number"]');
            const marksInput = document.querySelector('input[name="marks"]');
            if (orderInput && orderInput.value === '') orderInput.value = '0';
            if (marksInput) marksInput.value = '0';

        } else if (type === 'fill_blanks') {
            // Show blank buttons and manager for fill in the blanks
            if (blankButtons) blankButtons.style.display = 'flex';
            if (blanksManager) blanksManager.classList.remove('hidden');
            
            // Get the content editor
            if (typeof tinymce !== 'undefined') {
                contentEditor = tinymce.get('content');
            }
        } else {
            // Regular questions - reset marks if it was 0
            const marksInput = document.querySelector('input[name="marks"]');
            if (marksInput && marksInput.value === '0') {
                marksInput.value = '1';
            }
        }
    }

    // Insert blank function
    window.insertBlank = function() {
        if (!contentEditor && typeof tinymce !== 'undefined') {
            contentEditor = tinymce.get('content');
        }
        
        if (contentEditor) {
            const blankCounter = (document.querySelectorAll('[data-blank]').length || 0) + 1;
            const blankHtml = '<span class="blank-placeholder" data-blank="' + blankCounter + '" contenteditable="false" style="background:#fef3c7;padding:2px 8px;margin:0 4px;border-bottom:2px solid #f59e0b;">[____' + blankCounter + '____]</span>';
            contentEditor.insertContent(blankHtml);
            updateBlanks();
        }
    };

    // Insert dropdown function
    window.insertDropdown = function() {
        if (!contentEditor && typeof tinymce !== 'undefined') {
            contentEditor = tinymce.get('content');
        }
        
        if (contentEditor) {
            const options = prompt('Enter dropdown options separated by comma:\n(e.g., option1, option2, option3)');
            if (options) {
                const dropdownCounter = (document.querySelectorAll('[data-dropdown]').length || 0) + 1;
                const dropdownHtml = '<span class="dropdown-placeholder" data-dropdown="' + dropdownCounter + '" data-options="' + options + '" contenteditable="false" style="background:#d1fae5;border:1px solid #10b981;padding:2px 8px;margin:0 4px;border-radius:4px;">[DROPDOWN_' + dropdownCounter + ']</span>';
                contentEditor.insertContent(dropdownHtml);
                updateBlanks();
            }
        }
    };

    // Update blanks display
    function updateBlanks() {
        if (!contentEditor) return;

        const content = contentEditor.getContent();
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;

        const blanks = tempDiv.querySelectorAll('[data-blank], [data-dropdown]');
        const blanksManager = document.getElementById('blanks-manager');
        const blanksList = document.getElementById('blanks-list');

        if (!blanksManager || !blanksList) return;

        if (blanks.length > 0) {
            blanksManager.classList.remove('hidden');
            blanksList.innerHTML = '';

            blanks.forEach((blank) => {
                const isDropdown = blank.hasAttribute('data-dropdown');
                const num = blank.getAttribute(isDropdown ? 'data-dropdown' : 'data-blank');

                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center space-x-2';

                if (isDropdown) {
                    const options = blank.getAttribute('data-options');
                    const optionsArray = options.split(',').map(opt => opt.trim());

                    itemDiv.innerHTML = `
                        <span class="text-sm font-medium">Dropdown ${num}:</span>
                        <input type="text" value="${options}" name="dropdown_options[${num}]" 
                               class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded">
                        <select name="dropdown_correct[${num}]" class="px-2 py-1 text-sm border border-gray-300 rounded">
                            ${optionsArray.map((opt, idx) => `<option value="${idx}">${opt}</option>`).join('')}
                        </select>
                    `;
                } else {
                    itemDiv.innerHTML = `
                        <span class="text-sm font-medium">Blank ${num}:</span>
                        <input type="text" name="blank_answers[${num}]" 
                               class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded" 
                               placeholder="Correct answer">
                    `;
                }

                blanksList.appendChild(itemDiv);
            });
        } else {
            blanksManager.classList.add('hidden');
        }
    }

    // Override form submission
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('questionForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                const questionType = document.getElementById('question_type').value;

                // Save all TinyMCE editors
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }

                if (questionType === 'passage' && passageEditor) {
                    // For passage type, ensure content is saved
                    const passageContent = passageEditor.getContent();
                    
                    const passageTextField = document.getElementById('passage_text');
                    if (passageTextField) {
                        passageTextField.value = passageContent;
                    }
                    
                    const contentField = document.getElementById('content');
                    if (contentField) {
                        contentField.value = passageContent;
                    }
                }

                return true;
            });
        }
    });

    // Section specific handler
    function handleSectionSpecificChange(type) {
        handleReadingQuestionTypeChange();
    }
    </script>
    @endpush
</x-layout>