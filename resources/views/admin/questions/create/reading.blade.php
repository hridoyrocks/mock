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
                                            <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                                Insert Blank ____
                                            </button>
                                            <button type="button" onclick="insertDropdown()" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                                Insert Dropdown ▼
                                            </button>
                                            <span class="text-xs text-gray-500 flex items-center ml-3">
                                                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Alt+B</kbd>
                                                <span class="mx-1">or</span>
                                                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Alt+D</kbd>
                                            </span>
                                        </div>
                                        <textarea id="content" name="content" class="tinymce">{{ old('content') }}</textarea>
                                    </div>
                                    
                                    <!-- Blanks Manager -->
                                    <div id="blanks-manager" class="hidden">
                                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    <h4 class="text-sm font-medium text-blue-900">Fill in the Blanks Configuration</h4>
                                                    <span id="blank-counter" class="blank-counter-badge">0</span>
                                                </div>
                                                <button type="button" onclick="refreshBlanks()" class="text-xs text-blue-600 hover:text-blue-800">
                                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Refresh
                                                </button>
                                            </div>
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
    
    @push('styles')
    <style>
        .blank-placeholder {
            background-color: #fef3c7 !important;
            padding: 2px 8px !important;
            margin: 0 4px !important;
            border-bottom: 2px solid #f59e0b !important;
            border-radius: 2px !important;
            font-weight: 500 !important;
            color: #92400e !important;
            cursor: not-allowed !important;
            user-select: none !important;
            display: inline-block !important;
            min-width: 60px !important;
            transition: all 0.3s ease !important;
        }
        
        .blank-placeholder:hover {
            background-color: #fde68a !important;
            transform: scale(1.05);
        }
        
        .dropdown-placeholder {
            background-color: #d1fae5 !important;
            border: 1px solid #10b981 !important;
            padding: 2px 8px !important;
            margin: 0 4px !important;
            border-radius: 4px !important;
            font-weight: 500 !important;
            color: #064e3b !important;
            cursor: not-allowed !important;
            user-select: none !important;
            display: inline-block !important;
            min-width: 80px !important;
            transition: all 0.3s ease !important;
        }
        
        .dropdown-placeholder:hover {
            background-color: #a7f3d0 !important;
            transform: scale(1.05);
        }
        
        /* Better visibility in TinyMCE */
        .mce-content-body .blank-placeholder,
        .mce-content-body .dropdown-placeholder {
            display: inline-block !important;
        }
        
        /* Pulse animation for new blanks */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(251, 191, 36, 0);
                transform: scale(1.05);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(251, 191, 36, 0);
                transform: scale(1);
            }
        }
        
        /* Notification animation */
        .blank-notification {
            opacity: 0;
            transform: translateX(100%);
        }
        
        /* Input field animations */
        .blank-answer-input {
            transition: all 0.3s ease;
        }
        
        .blank-answer-input:focus {
            transform: scale(1.02);
        }
        
        .blank-answer-input.ring-2 {
            animation: successPulse 0.5s ease;
        }
        
        @keyframes successPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.5);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }
        
        /* Answer indicator animation */
        .answer-indicator {
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
    @endpush
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script>
    // All Reading specific functionality in one place
    let passageEditor = null;
    let contentEditor = null;
    let blankCounter = 0;
    let dropdownCounter = 0;

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize TinyMCE
        initializeTinyMCE('.tinymce');

        // Setup question type handler
        const questionType = document.getElementById('question_type');
        if (questionType) {
            questionType.addEventListener('change', handleReadingQuestionTypeChange);
            // Trigger on load if value exists
            if (questionType.value) {
                handleReadingQuestionTypeChange.call(questionType);
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.altKey && document.getElementById('question_type')?.value === 'fill_blanks') {
                if (e.key === 'b' || e.key === 'B') {
                    e.preventDefault();
                    insertBlank();
                } else if (e.key === 'd' || e.key === 'D') {
                    e.preventDefault();
                    insertDropdown();
                }
            }
        });

        // Form submission handler
        const form = document.getElementById('questionForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                const questionType = document.getElementById('question_type').value;

                // Save all TinyMCE editors
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }

                if (questionType === 'passage' && passageEditor) {
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

                // Debug log for fill_blanks
                if (questionType === 'fill_blanks') {
                    console.log('Submitting fill_blanks question');
                    const formData = new FormData(form);
                    console.log('Form data:');
                    for (let [key, value] of formData.entries()) {
                        if (key.startsWith('blank_answers') || key.startsWith('dropdown_')) {
                            console.log(key + ': ' + value);
                        }
                    }
                }

                return true;
            });
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
        questionContentField?.classList.remove('hidden');
        passageContentField?.classList.add('hidden');
        blanksManager?.classList.add('hidden');
        if (blankButtons) blankButtons.style.display = 'none';

        // Reset counters when changing type
        blankCounter = 0;
        dropdownCounter = 0;

        if (type === 'passage') {
            questionContentField?.classList.add('hidden');
            passageContentField?.classList.remove('hidden');

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

            // Set defaults for passage
            const orderInput = document.querySelector('input[name="order_number"]');
            const marksInput = document.querySelector('input[name="marks"]');
            if (orderInput && !orderInput.value) orderInput.value = '0';
            if (marksInput) marksInput.value = '0';

        } else if (type === 'fill_blanks') {
            // Show blank buttons and manager
            if (blankButtons) blankButtons.style.display = 'flex';
            blanksManager?.classList.remove('hidden');
            
            // Get content editor
            if (!contentEditor && typeof tinymce !== 'undefined') {
                contentEditor = tinymce.get('content');
            }
            
            // Initial update
            setTimeout(updateBlanks, 500);
        } else {
            // Regular questions
            const marksInput = document.querySelector('input[name="marks"]');
            if (marksInput && marksInput.value === '0') {
                marksInput.value = '1';
            }
        }
    }

    // Insert blank function with visual feedback
    window.insertBlank = function() {
        if (!contentEditor && typeof tinymce !== 'undefined') {
            contentEditor = tinymce.get('content');
        }
        
        if (contentEditor) {
            blankCounter++;
            const blankHtml = `<span class="blank-placeholder" data-blank="${blankCounter}" contenteditable="false">[____${blankCounter}____]</span>&nbsp;`;
            contentEditor.insertContent(blankHtml);
            
            // Visual feedback
            showNotification(`Blank ${blankCounter} added successfully!`, 'success');
            
            // Highlight the new blank briefly
            setTimeout(() => {
                const newBlank = contentEditor.getBody().querySelector(`[data-blank="${blankCounter}"]`);
                if (newBlank) {
                    newBlank.style.animation = 'pulse 1s ease-in-out';
                }
            }, 100);
            
            setTimeout(updateBlanks, 100);
        }
    };

    // Insert dropdown function with visual feedback
    window.insertDropdown = function() {
        if (!contentEditor && typeof tinymce !== 'undefined') {
            contentEditor = tinymce.get('content');
        }
        
        if (contentEditor) {
            const options = prompt('Enter dropdown options separated by comma:\n(e.g., option1, option2, option3)');
            if (options) {
                dropdownCounter++;
                const dropdownHtml = `<span class="dropdown-placeholder" data-dropdown="${dropdownCounter}" data-options="${options}" contenteditable="false">[DROPDOWN_${dropdownCounter}]</span>&nbsp;`;
                contentEditor.insertContent(dropdownHtml);
                
                // Visual feedback
                showNotification(`Dropdown ${dropdownCounter} added successfully!`, 'success');
                
                setTimeout(updateBlanks, 100);
            }
        }
    };
    
    // Notification function
    function showNotification(message, type = 'info') {
        // Remove existing notification
        const existing = document.querySelector('.blank-notification');
        if (existing) existing.remove();
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `blank-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    }
                </svg>
                <span class="font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Store blank answers to preserve them
    const blankAnswersStore = {};
    const dropdownStore = {
        options: {},
        correct: {}
    };

    // Update blanks display
    function updateBlanks() {
        if (!contentEditor) {
            contentEditor = tinymce.get('content');
        }
        
        if (!contentEditor) return;

        // First, save current values before updating
        saveCurrentBlankValues();

        const content = contentEditor.getContent();
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;

        const blanks = tempDiv.querySelectorAll('[data-blank]');
        const dropdowns = tempDiv.querySelectorAll('[data-dropdown]');
        
        const blanksManager = document.getElementById('blanks-manager');
        const blanksList = document.getElementById('blanks-list');

        if (!blanksManager || !blanksList) return;

        // Update counters
        if (blanks.length > 0) {
            blankCounter = Math.max(...Array.from(blanks).map(b => parseInt(b.getAttribute('data-blank'))));
        }
        if (dropdowns.length > 0) {
            dropdownCounter = Math.max(...Array.from(dropdowns).map(d => parseInt(d.getAttribute('data-dropdown'))));
        }

        if (blanks.length > 0 || dropdowns.length > 0) {
            blanksManager.classList.remove('hidden');
            blanksList.innerHTML = '';

            // Add blanks
            blanks.forEach((blank) => {
                const num = blank.getAttribute('data-blank');
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center space-x-2 mb-2 p-2 bg-white rounded border border-gray-200';
                
                // Get stored value or empty
                const storedValue = blankAnswersStore[num] || '';
                
                itemDiv.innerHTML = `
                    <span class="text-sm font-medium text-gray-700 w-20">Blank ${num}:</span>
                    <input type="text" 
                           id="blank_answer_${num}"
                           name="blank_answers[${num}]" 
                           class="blank-answer-input flex-1 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Enter correct answer"
                           value="${storedValue}"
                           data-blank-num="${num}"
                           required>
                    <button type="button" onclick="removeBlank(${num})" class="text-red-500 hover:text-red-700 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;

                blanksList.appendChild(itemDiv);
                
                // Add input event listener to save value
                const input = itemDiv.querySelector(`#blank_answer_${num}`);
                if (input) {
                    input.addEventListener('input', function() {
                        blankAnswersStore[num] = this.value;
                        
                        // Visual feedback when answer is added
                        if (this.value.trim()) {
                            this.classList.add('ring-2', 'ring-green-500', 'border-green-500');
                            showAnswerFeedback(num, 'added');
                        } else {
                            this.classList.remove('ring-2', 'ring-green-500', 'border-green-500');
                        }
                    });
                    
                    // Check if already has value
                    if (input.value.trim()) {
                        input.classList.add('ring-2', 'ring-green-500', 'border-green-500');
                    }
                }
            });

            // Add dropdowns
            dropdowns.forEach((dropdown) => {
                const num = dropdown.getAttribute('data-dropdown');
                const options = dropdown.getAttribute('data-options');
                const optionsArray = options.split(',').map(opt => opt.trim());

                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center space-x-2 mb-2 p-2 bg-white rounded border border-gray-200';
                
                // Get stored values
                const storedOptions = dropdownStore.options[num] || options;
                const storedCorrect = dropdownStore.correct[num] || '0';
                
                itemDiv.innerHTML = `
                    <span class="text-sm font-medium text-gray-700 w-20">Dropdown ${num}:</span>
                    <input type="text" 
                           id="dropdown_options_${num}"
                           value="${storedOptions}" 
                           name="dropdown_options[${num}]" 
                           class="dropdown-options-input flex-1 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Options (comma separated)"
                           data-dropdown-num="${num}">
                    <select id="dropdown_correct_${num}" name="dropdown_correct[${num}]" 
                            class="dropdown-correct-select px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                            data-dropdown-num="${num}">
                        ${storedOptions.split(',').map((opt, idx) => `<option value="${idx}" ${idx == storedCorrect ? 'selected' : ''}>${opt.trim()}</option>`).join('')}
                    </select>
                    <button type="button" onclick="removeDropdown(${num})" class="text-red-500 hover:text-red-700 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;

                blanksList.appendChild(itemDiv);
                
                // Add event listeners
                const optionsInput = itemDiv.querySelector(`#dropdown_options_${num}`);
                const correctSelect = itemDiv.querySelector(`#dropdown_correct_${num}`);
                
                if (optionsInput) {
                    optionsInput.addEventListener('input', function() {
                        dropdownStore.options[num] = this.value;
                        updateDropdownSelect(num, this.value);
                    });
                }
                
                if (correctSelect) {
                    correctSelect.addEventListener('change', function() {
                        dropdownStore.correct[num] = this.value;
                    });
                }
            });

            // Add summary
            const summaryDiv = document.createElement('div');
            summaryDiv.className = 'mt-3 pt-3 border-t border-gray-200 text-sm text-gray-600';
            summaryDiv.innerHTML = `<strong>Total:</strong> ${blanks.length} blank(s), ${dropdowns.length} dropdown(s)`;
            blanksList.appendChild(summaryDiv);
            
            // Update counter badge
            const counterBadge = document.getElementById('blank-counter');
            if (counterBadge) {
                const total = blanks.length + dropdowns.length;
                counterBadge.textContent = total;
                counterBadge.style.display = total > 0 ? 'inline-flex' : 'none';
            }

        } else {
            blanksManager.classList.add('hidden');
            // Hide counter when no blanks
            const counterBadge = document.getElementById('blank-counter');
            if (counterBadge) {
                counterBadge.style.display = 'none';
            }
        }
    }
    
    // Save current blank values before updating
    function saveCurrentBlankValues() {
        // Save blank answers
        document.querySelectorAll('.blank-answer-input').forEach(input => {
            const num = input.getAttribute('data-blank-num');
            if (num) {
                blankAnswersStore[num] = input.value;
            }
        });
        
        // Save dropdown values
        document.querySelectorAll('.dropdown-options-input').forEach(input => {
            const num = input.getAttribute('data-dropdown-num');
            if (num) {
                dropdownStore.options[num] = input.value;
            }
        });
        
        document.querySelectorAll('.dropdown-correct-select').forEach(select => {
            const num = select.getAttribute('data-dropdown-num');
            if (num) {
                dropdownStore.correct[num] = select.value;
            }
        });
    }
    
    // Update dropdown select options
    function updateDropdownSelect(num, optionsString) {
        const select = document.querySelector(`#dropdown_correct_${num}`);
        if (select) {
            const currentValue = select.value;
            const options = optionsString.split(',').map(opt => opt.trim());
            
            select.innerHTML = options.map((opt, idx) => 
                `<option value="${idx}" ${idx == currentValue ? 'selected' : ''}>${opt}</option>`
            ).join('');
        }
    }

    // Update dropdown options in editor
    window.updateDropdownOptions = function(num, newOptions) {
        if (contentEditor) {
            let content = contentEditor.getContent();
            const regex = new RegExp(`data-options="[^"]*"`, 'g');
            const newContent = content.replace(
                `data-dropdown="${num}"`,
                `data-dropdown="${num}" data-options="${newOptions}"`
            );
            contentEditor.setContent(newContent);
        }
    };

    // Remove blank
    window.removeBlank = function(num) {
        if (contentEditor) {
            // Remove from store
            delete blankAnswersStore[num];
            
            // Remove from editor
            let content = contentEditor.getContent();
            const regex = new RegExp(`<span[^>]*data-blank="${num}"[^>]*>\\[____${num}____\\]</span>`, 'g');
            content = content.replace(regex, '');
            contentEditor.setContent(content);
            
            // Renumber remaining blanks
            renumberBlanks();
        }
    };

    // Remove dropdown
    window.removeDropdown = function(num) {
        if (contentEditor) {
            // Remove from store
            delete dropdownStore.options[num];
            delete dropdownStore.correct[num];
            
            // Remove from editor
            let content = contentEditor.getContent();
            const regex = new RegExp(`<span[^>]*data-dropdown="${num}"[^>]*>\\[DROPDOWN_${num}\\]</span>`, 'g');
            content = content.replace(regex, '');
            contentEditor.setContent(content);
            
            // Renumber dropdowns
            renumberDropdowns();
        }
    };
    
    // Renumber blanks after deletion
    function renumberBlanks() {
        if (!contentEditor) return;
        
        let content = contentEditor.getContent();
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;
        
        const blanks = tempDiv.querySelectorAll('[data-blank]');
        const newStore = {};
        
        // Renumber each blank
        blanks.forEach((blank, index) => {
            const oldNum = blank.getAttribute('data-blank');
            const newNum = index + 1;
            
            // Update the element
            blank.setAttribute('data-blank', newNum);
            blank.innerHTML = `[____${newNum}____]`;
            
            // Transfer stored value to new number
            if (blankAnswersStore[oldNum]) {
                newStore[newNum] = blankAnswersStore[oldNum];
            }
        });
        
        // Update store
        blankAnswersStore = newStore;
        
        // Update counter
        blankCounter = blanks.length;
        
        // Set the updated content
        contentEditor.setContent(tempDiv.innerHTML);
        
        // Update display
        setTimeout(updateBlanks, 100);
    }
    
    // Renumber dropdowns after deletion
    function renumberDropdowns() {
        if (!contentEditor) return;
        
        let content = contentEditor.getContent();
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;
        
        const dropdowns = tempDiv.querySelectorAll('[data-dropdown]');
        const newOptionsStore = {};
        const newCorrectStore = {};
        
        // Renumber each dropdown
        dropdowns.forEach((dropdown, index) => {
            const oldNum = dropdown.getAttribute('data-dropdown');
            const newNum = index + 1;
            
            // Update the element
            dropdown.setAttribute('data-dropdown', newNum);
            dropdown.innerHTML = `[DROPDOWN_${newNum}]`;
            
            // Transfer stored values
            if (dropdownStore.options[oldNum]) {
                newOptionsStore[newNum] = dropdownStore.options[oldNum];
            }
            if (dropdownStore.correct[oldNum]) {
                newCorrectStore[newNum] = dropdownStore.correct[oldNum];
            }
        });
        
        // Update store
        dropdownStore.options = newOptionsStore;
        dropdownStore.correct = newCorrectStore;
        
        // Update counter
        dropdownCounter = dropdowns.length;
        
        // Set the updated content
        contentEditor.setContent(tempDiv.innerHTML);
        
        // Update display
        setTimeout(updateBlanks, 100);
    }

    // Show answer feedback
    function showAnswerFeedback(num, action) {
        // Visual indicator in editor
        if (contentEditor) {
            const editorBody = contentEditor.getBody();
            const blank = editorBody.querySelector(`[data-blank="${num}"]`);
            
            if (blank) {
                // Add checkmark or indicator
                if (action === 'added') {
                    blank.style.backgroundColor = '#86efac';
                    blank.style.transition = 'background-color 0.5s ease';
                    
                    // Create a small checkmark
                    const checkmark = document.createElement('span');
                    checkmark.innerHTML = ' ✓';
                    checkmark.style.color = '#059669';
                    checkmark.style.fontWeight = 'bold';
                    checkmark.className = 'answer-checkmark';
                    
                    // Remove existing checkmark if any
                    const existingCheck = blank.querySelector('.answer-checkmark');
                    if (existingCheck) existingCheck.remove();
                    
                    blank.appendChild(checkmark);
                    
                    // Revert color after 2 seconds
                    setTimeout(() => {
                        blank.style.backgroundColor = '#fef3c7';
                    }, 2000);
                }
            }
        }
        
        // Update the blank item visual
        const blankItem = document.querySelector(`#blank_answer_${num}`).closest('.flex');
        if (blankItem) {
            const indicator = blankItem.querySelector('.answer-indicator') || document.createElement('span');
            indicator.className = 'answer-indicator ml-2 text-green-600 font-medium text-sm';
            indicator.innerHTML = '✓ Saved';
            
            if (!blankItem.querySelector('.answer-indicator')) {
                blankItem.appendChild(indicator);
            }
            
            // Fade out after 2 seconds
            setTimeout(() => {
                indicator.style.opacity = '0';
                indicator.style.transition = 'opacity 0.5s ease';
                setTimeout(() => indicator.remove(), 500);
            }, 2000);
        }
    }
    
    // Refresh blanks
    window.refreshBlanks = function() {
        updateBlanks();
        showNotification('Blanks configuration refreshed!', 'info');
    };

    // Section specific handler
    function handleSectionSpecificChange(type) {
        const questionType = document.getElementById('question_type');
        if (questionType) {
            handleReadingQuestionTypeChange.call(questionType);
        }
    }

    // Listen for editor changes
    setTimeout(() => {
        if (typeof tinymce !== 'undefined') {
            tinymce.on('AddEditor', function(e) {
                e.editor.on('NodeChange KeyUp', function() {
                    if (document.getElementById('question_type')?.value === 'fill_blanks') {
                        updateBlanks();
                    }
                });
            });
        }
    }, 1000);
    </script>
    @endpush
</x-layout>