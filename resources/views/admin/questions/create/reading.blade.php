<x-layout>
    <x-slot:title>Add Question - Reading</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold">Add Reading Question</h1>
                        <p class="text-green-100 text-sm mt-1">{{ $testSet->title }}</p>
                    </div>
                    <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                       class="inline-flex items-center px-3 sm:px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
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
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
            
            @include('admin.questions.partials.question-header')
            
            <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                <input type="hidden" name="test_set_id" value="{{ $testSet->id }}">
                
                <div class="space-y-4 sm:space-y-6">
                    <!-- Question Content -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Question Content</h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
                                <div class="space-y-4 sm:space-y-6">
                                    <!-- Instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Instructions / Passage Title
                                        </label>
                                        <textarea id="instructions" name="instructions" rows="2" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                  placeholder="e.g., 'The History of Aviation' OR 'Questions 1-5: Choose the correct letter'">{{ old('instructions') }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div id="question-content-field">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mb-3 flex flex-wrap gap-2" id="blank-buttons" style="display: none;">
                                            <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                                Insert Blank
                                            </button>
                                            <button type="button" onclick="insertDropdown()" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                                Insert Dropdown
                                            </button>
                                            <span class="text-xs text-gray-500 flex items-center">
                                                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Alt+B</kbd>
                                                <span class="mx-1">or</span>
                                                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Alt+D</kbd>
                                            </span>
                                        </div>
                                        <div class="border border-gray-300 rounded-md overflow-hidden" style="height: 400px;">
                                            <textarea id="content" name="content" class="tinymce">{{ old('content') }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Blanks Manager -->
                                    <div id="blanks-manager" class="hidden">
                                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    <h4 class="text-sm font-medium text-gray-900">Fill in the Blanks Configuration</h4>
                                                    <span id="blank-counter" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">0</span>
                                                </div>
                                                <button type="button" onclick="refreshBlanks()" class="text-xs text-blue-600 hover:text-blue-800">
                                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Refresh
                                                </button>
                                            </div>
                                            <div id="blanks-list" class="space-y-2 max-h-64 overflow-y-auto">
                                                <!-- Dynamically populated -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Passage Content (Hidden by default) -->
                                    <div id="passage-content-field" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Passage Content <span class="text-red-500">*</span>
                                        </label>
                                        <div class="border border-gray-300 rounded-md overflow-hidden" style="height: 500px;">
                                            <textarea id="passage_text" name="passage_text" class="tinymce-passage">{{ old('passage_text') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    @include('admin.questions.partials.question-settings', [
                                        'questionTypes' => [
                                            'passage' => 'Reading Passage',
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
                    
                    <!-- Action Buttons - Made Sticky on Mobile -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 sticky bottom-0 z-10 border-t sm:border-t-0 sm:relative">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" name="action" value="save" class="flex-1 py-2.5 sm:py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors text-sm sm:text-base">
                                Save Question
                            </button>
                            <button type="submit" name="action" value="save_and_new" class="flex-1 py-2.5 sm:py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors text-sm sm:text-base">
                                Save & Add Another
                            </button>
                            <button type="button" onclick="previewQuestion()" class="flex-1 py-2.5 sm:py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors text-sm sm:text-base">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('styles')
    <style>
        /* Professional styles without emojis */
        .blank-placeholder {
            background-color: #FEF3C7;
            padding: 2px 8px;
            margin: 0 4px;
            border-bottom: 2px solid #F59E0B;
            border-radius: 2px;
            font-weight: 500;
            color: #92400E;
            cursor: not-allowed;
            user-select: none;
            display: inline-block;
            min-width: 60px;
            transition: background-color 0.2s ease;
        }
        
        .blank-placeholder:hover {
            background-color: #FDE68A;
        }
        
        .dropdown-placeholder {
            background-color: #D1FAE5;
            border: 1px solid #10B981;
            padding: 2px 8px;
            margin: 0 4px;
            border-radius: 4px;
            font-weight: 500;
            color: #064E3B;
            cursor: not-allowed;
            user-select: none;
            display: inline-block;
            min-width: 80px;
            transition: background-color 0.2s ease;
        }
        
        .dropdown-placeholder:hover {
            background-color: #A7F3D0;
        }
        
        /* Professional success indicator */
        .blank-answer-input {
            transition: all 0.2s ease;
        }
        
        .blank-answer-input.validated {
            border-color: #10B981;
            background-color: #F0FDF4;
        }
        
        /* Responsive improvements */
        @media (max-width: 640px) {
            .tox-tinymce {
                height: 300px !important;
            }
            
            /* Make modals full screen on mobile */
            .modal-content {
                margin: 0;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
            }
        }
        
        /* Hide TinyMCE emoji button */
        .tox-tbtn[aria-label="Emoticons"] {
            display: none !important;
        }
        
        /* Full page editor styles */
        .tox-editor-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
            border-bottom: 1px solid #e5e7eb;
        }
        
        /* Clean notification style */
        .success-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #10B981;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .success-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        /* Sticky header on scroll */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
    @endpush
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script>
    // All Reading specific functionality
    let passageEditor = null;
    let contentEditor = null;
    let blankCounter = 0;
    let dropdownCounter = 0;

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize TinyMCE with full page configuration
        initializeTinyMCE();

        // Setup question type handler
        const questionType = document.getElementById('question_type');
        if (questionType) {
            questionType.addEventListener('change', handleReadingQuestionTypeChange);
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

                return true;
            });
        }
    });

    // Initialize TinyMCE with professional settings
    function initializeTinyMCE() {
        const commonConfig = {
            height: '100%',
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | table | code | fullscreen',
            content_style: `
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6; 
                    color: #374151;
                    padding: 12px;
                }
                p { margin: 0 0 10px 0; }
            `,
            toolbar_mode: 'sliding',
            contextmenu: false,
            branding: false,
            resize: false,
            elementpath: false,
            setup: function(editor) {
                // Remove emoji plugin if loaded
                editor.on('init', function() {
                    const emojiButton = editor.ui.registry.getAll().buttons.emoticons;
                    if (emojiButton) {
                        editor.ui.registry.remove('button', 'emoticons');
                    }
                });
            }
        };

        // Initialize content editor
        tinymce.init({
            ...commonConfig,
            selector: '#content',
            setup: function(editor) {
                contentEditor = editor;
                commonConfig.setup(editor);
                
                editor.on('NodeChange KeyUp', function() {
                    if (document.getElementById('question_type')?.value === 'fill_blanks') {
                        updateBlanks();
                    }
                });
            }
        });
    }

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
                        height: '100%',
                        menubar: true,
                        plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
                        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | fullscreen',
                        content_style: `
                            body { 
                                font-family: Georgia, 'Times New Roman', serif; 
                                font-size: 16px; 
                                line-height: 1.8; 
                                color: #1F2937;
                                padding: 20px;
                            }
                        `,
                        branding: false,
                        resize: false,
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
            
            // Initial update
            setTimeout(updateBlanks, 500);
        }
    }

    // Insert blank function with professional feedback
    window.insertBlank = function() {
        if (!contentEditor && typeof tinymce !== 'undefined') {
            contentEditor = tinymce.get('content');
        }
        
        if (contentEditor) {
            blankCounter++;
            const blankHtml = `<span class="blank-placeholder" data-blank="${blankCounter}" contenteditable="false">[____${blankCounter}____]</span>&nbsp;`;
            contentEditor.insertContent(blankHtml);
            
            // Professional notification
            showNotification(`Blank ${blankCounter} added`, 'success');
            
            setTimeout(updateBlanks, 100);
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
                dropdownCounter++;
                const dropdownHtml = `<span class="dropdown-placeholder" data-dropdown="${dropdownCounter}" data-options="${options}" contenteditable="false">[DROPDOWN_${dropdownCounter}]</span>&nbsp;`;
                contentEditor.insertContent(dropdownHtml);
                
                showNotification(`Dropdown ${dropdownCounter} added`, 'success');
                
                setTimeout(updateBlanks, 100);
            }
        }
    };
    
    // Professional notification function
    function showNotification(message, type = 'info') {
        // Remove existing notification
        const existing = document.querySelector('.success-notification');
        if (existing) existing.remove();
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = 'success-notification';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Hide after 2 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    // Store blank answers
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

        // Save current values
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
                itemDiv.className = 'flex items-center space-x-2 p-2 bg-white rounded border border-gray-200';
                
                const storedValue = blankAnswersStore[num] || '';
                
                itemDiv.innerHTML = `
                    <span class="text-sm font-medium text-gray-700 min-w-[80px]">Blank ${num}:</span>
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
                
                // Add input event listener
                const input = itemDiv.querySelector(`#blank_answer_${num}`);
                if (input) {
                    input.addEventListener('input', function() {
                        blankAnswersStore[num] = this.value;
                        
                        // Professional validation feedback
                        if (this.value.trim()) {
                            this.classList.add('validated');
                        } else {
                            this.classList.remove('validated');
                        }
                    });
                    
                    // Check if already has value
                    if (input.value.trim()) {
                        input.classList.add('validated');
                    }
                }
            });

            // Add dropdowns
            dropdowns.forEach((dropdown) => {
                const num = dropdown.getAttribute('data-dropdown');
                const options = dropdown.getAttribute('data-options');

                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center space-x-2 p-2 bg-white rounded border border-gray-200';
                
                const storedOptions = dropdownStore.options[num] || options;
                const storedCorrect = dropdownStore.correct[num] || '0';
                
                itemDiv.innerHTML = `
                    <span class="text-sm font-medium text-gray-700 min-w-[80px]">Dropdown ${num}:</span>
                    <input type="text" 
                           id="dropdown_options_${num}"
                           value="${storedOptions}" 
                           name="dropdown_options[${num}]" 
                           class="flex-1 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Options (comma separated)"
                           data-dropdown-num="${num}">
                    <select id="dropdown_correct_${num}" name="dropdown_correct[${num}]" 
                            class="px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
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

            // Update counter badge
            const counterBadge = document.getElementById('blank-counter');
            if (counterBadge) {
                const total = blanks.length + dropdowns.length;
                counterBadge.textContent = total;
                counterBadge.style.display = total > 0 ? 'inline-flex' : 'none';
            }

        } else {
            blanksManager.classList.add('hidden');
            const counterBadge = document.getElementById('blank-counter');
            if (counterBadge) {
                counterBadge.style.display = 'none';
            }
        }
    }
    
    // Save current blank values
    function saveCurrentBlankValues() {
        document.querySelectorAll('.blank-answer-input').forEach(input => {
            const num = input.getAttribute('data-blank-num');
            if (num) {
                blankAnswersStore[num] = input.value;
            }
        });
        
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

    // Remove blank
    window.removeBlank = function(num) {
        if (contentEditor) {
            delete blankAnswersStore[num];
            
            let content = contentEditor.getContent();
            const regex = new RegExp(`<span[^>]*data-blank="${num}"[^>]*>\\[____${num}____\\]</span>`, 'g');
            content = content.replace(regex, '');
            contentEditor.setContent(content);
            
            renumberBlanks();
            showNotification('Blank removed', 'info');
        }
    };

    // Remove dropdown
    window.removeDropdown = function(num) {
        if (contentEditor) {
            delete dropdownStore.options[num];
            delete dropdownStore.correct[num];
            
            let content = contentEditor.getContent();
            const regex = new RegExp(`<span[^>]*data-dropdown="${num}"[^>]*>\\[DROPDOWN_${num}\\]</span>`, 'g');
            content = content.replace(regex, '');
            contentEditor.setContent(content);
            
            renumberDropdowns();
            showNotification('Dropdown removed', 'info');
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
        
        blanks.forEach((blank, index) => {
            const oldNum = blank.getAttribute('data-blank');
            const newNum = index + 1;
            
            blank.setAttribute('data-blank', newNum);
            blank.innerHTML = `[____${newNum}____]`;
            
            if (blankAnswersStore[oldNum]) {
                newStore[newNum] = blankAnswersStore[oldNum];
            }
        });
        
        Object.keys(blankAnswersStore).forEach(key => delete blankAnswersStore[key]);
        Object.assign(blankAnswersStore, newStore);
        
        blankCounter = blanks.length;
        
        contentEditor.setContent(tempDiv.innerHTML);
        
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
        
        dropdowns.forEach((dropdown, index) => {
            const oldNum = dropdown.getAttribute('data-dropdown');
            const newNum = index + 1;
            
            dropdown.setAttribute('data-dropdown', newNum);
            dropdown.innerHTML = `[DROPDOWN_${newNum}]`;
            
            if (dropdownStore.options[oldNum]) {
                newOptionsStore[newNum] = dropdownStore.options[oldNum];
            }
            if (dropdownStore.correct[oldNum]) {
                newCorrectStore[newNum] = dropdownStore.correct[oldNum];
            }
        });
        
        dropdownStore.options = newOptionsStore;
        dropdownStore.correct = newCorrectStore;
        
        dropdownCounter = dropdowns.length;
        
        contentEditor.setContent(tempDiv.innerHTML);
        
        setTimeout(updateBlanks, 100);
    }
    
    // Refresh blanks
    window.refreshBlanks = function() {
        updateBlanks();
        showNotification('Configuration refreshed', 'info');
    };
    </script>
    @endpush
</x-layout>