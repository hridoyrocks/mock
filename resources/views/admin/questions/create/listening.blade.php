<x-admin-layout>
    <x-slot:title>Add Question - Listening</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold">Add Listening Question</h1>
                        <p class="text-purple-100 text-sm mt-1">{{ $testSet->title }}</p>
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
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                                        <textarea id="instructions" name="instructions" class="tinymce-editor-simple">{{ old('instructions') }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Question <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-3 flex flex-wrap gap-2" id="blank-buttons" style="display: none;">
                                    <button type="button" onclick="insertListeningBlank()" class="px-3 py-1 bg-amber-600 text-white text-xs font-medium rounded hover:bg-amber-700 transition-colors">
                                    Insert Blank
                                    </button>
                                    <span class="text-xs text-gray-500 flex items-center">
                                    <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Alt+B</kbd>
                                    </span>
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-2" id="dropdown-buttons" style="display: none;">
                                    <button type="button" onclick="insertListeningDropdown()" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                    Insert Dropdown
                                    </button>
                                    <span class="text-xs text-gray-500 flex items-center">
                                    <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Alt+D</kbd>
                                    </span>
                                    </div>
                                    <div class="mb-3 flex flex-wrap gap-2" id="drag-zone-buttons" style="display: none;">
                                    <button type="button" onclick="insertDragZone()" class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700 transition-colors">
                                    Insert Drag Zone
                                    </button>
                                    <span class="text-xs text-gray-500 flex items-center">
                                    <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Alt+G</kbd>
                                    </span>
                                    </div>
                                    <textarea id="content" name="content" class="tinymce-editor">{{ old('content') }}</textarea>
                                    </div>
                    
                    <!-- Blanks Manager -->
                                    <div id="blanks-manager-listening" class="hidden mt-4">
                                    <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                                    <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                    <h4 class="text-sm font-medium text-gray-900">Fill in the Blanks Configuration</h4>
                                    <span id="blank-counter-listening" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">0</span>
                                    </div>
                                    </div>
                                    <div id="blanks-list-listening" class="space-y-2 max-h-64 overflow-y-auto">
                                    <!-- Dynamically populated -->
                                    </div>
                                    </div>
                                    </div>
                                    
                                    <!-- Dropdown Manager -->
                                    <div id="dropdown-manager-listening" class="hidden mt-4">
                                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    <h4 class="text-sm font-medium text-gray-900">Dropdown Configuration</h4>
                                                    <span id="dropdown-counter-listening" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">0</span>
                                                </div>
                                            </div>
                                            <div id="dropdown-list-listening" class="space-y-3">
                                                <!-- Dynamically populated -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Drag Zones Manager -->
                                    <div id="drag-zones-manager" class="hidden mt-4">
                                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    <h4 class="text-sm font-medium text-gray-900">Drag Zones Configuration</h4>
                                                    <span id="drag-zone-counter" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">0</span>
                                                </div>
                                            </div>
                                            <div id="drag-zones-list" class="space-y-3 max-h-96 overflow-y-auto">
                                                <!-- Dynamically populated -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    @include('admin.questions.partials.question-settings', [
                                    'questionTypes' => [
                                    'fill_blanks' => 'Fill in the Blanks',
                                    'single_choice' => 'Single Choice (Radio)',
                                    'multiple_choice' => 'Multiple Choice (Checkbox)',
                                    'dropdown_selection' => 'Dropdown Selection',
                                        'drag_drop' => 'Drag & Drop'
                                        ]
                    ])
                                    
                                    <!-- Audio Transcript -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Audio Transcript
                                        </label>
                                        <textarea name="audio_transcript" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 text-sm"
                                                  placeholder="Enter the transcript of the audio...">{{ old('audio_transcript') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Include listening question type panels --}}
                    @include('admin.questions.partials.listening-question-types')
                    
                    {{-- Type-specific panels --}}
                    <div id="type-specific-panels">
                        {{-- Existing panels will be handled by respective handlers --}}
                    </div>
                    
                    <!-- Audio Management -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-purple-50">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                Audio Settings
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            {{-- Part Audio Status Check --}}
                            <div id="part-audio-status">
                                {{-- This will be updated dynamically via JavaScript --}}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons - Sticky on Mobile -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 sticky bottom-0 z-10 border-t sm:border-t-0 sm:relative">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" name="action" value="save" class="flex-1 py-2.5 sm:py-3 bg-purple-600 text-white font-medium rounded-md hover:bg-purple-700 transition-colors text-sm sm:text-base">
                                Save Question
                            </button>
                            <button type="submit" name="action" value="save_and_new" class="flex-1 py-2.5 sm:py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors text-sm sm:text-base">
                                Save & Add Another
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
        /* Professional styles */
        .drag-over {
            border-color: #9333EA !important;
            background-color: #FAF5FF !important;
        }
        
        /* Type specific panel styles */
        .type-specific-panel {
            transition: all 0.3s ease;
        }
        
        /* Notification styles */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #7C3AED;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            max-width: 90%;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification.success {
            background: #059669;
        }
        
        .notification.error {
            background: #DC2626;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/listening-question-types.js') }}"></script>
    <script src="{{ asset('js/student/listening-drag-drop.js') }}"></script>

    <script>
    // Global variables
    let contentEditor = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TinyMCE
        tinymce.init({
            selector: '.tinymce-editor-simple',
            height: 150,
            menubar: false,
            plugins: ['lists', 'link', 'charmap', 'code', 'table'],
            toolbar: 'bold italic underline | fontsize | bullist numlist | alignleft aligncenter alignright | link | table | removeformat code',
            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
            content_css: '//www.tiny.cloud/css/codepen.min.css',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
        
        tinymce.init({
            selector: '.tinymce-editor',
            height: 350,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'preview', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | fontsize | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | link image | removeformat code',
            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
            content_css: '//www.tiny.cloud/css/codepen.min.css',
            table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
            table_default_styles: {
                'border-collapse': 'collapse',
                'width': '100%'
            },
            table_default_attributes: {
                border: '1'
            },
            setup: function(editor) {
                contentEditor = editor;
                editor.on('change', function() {
                    editor.save();
                    
                    // Update blanks/dropdowns/dragzones based on question type
                    const questionType = document.getElementById('question_type')?.value;
                    if (['fill_blanks', 'note_completion', 'sentence_completion'].includes(questionType)) {
                        if (window.ListeningQuestionTypes) {
                            window.ListeningQuestionTypes.updateBlanks();
                        }
                    } else if (['dropdown_selection', 'form_completion'].includes(questionType)) {
                        if (window.ListeningQuestionTypes) {
                            window.ListeningQuestionTypes.updateDropdowns();
                        }
                    } else if (questionType === 'drag_drop') {
                        if (window.ListeningQuestionTypes) {
                            window.ListeningQuestionTypes.updateDragZones();
                        }
                    }
                });
            }
        });
        
        // Question type change handler
        const questionTypeSelect = document.getElementById('question_type');
        if (questionTypeSelect) {
            questionTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                console.log('Question type changed to:', selectedType);
                
                // Hide all type-specific panels first
                document.querySelectorAll('.type-specific-panel').forEach(panel => {
                    panel.style.display = 'none';
                });
                
                // Initialize ListeningQuestionTypes handler
                if (window.ListeningQuestionTypes) {
                    window.ListeningQuestionTypes.init(selectedType);
                }
                
                // Show/hide buttons based on type
                const blankButtons = document.getElementById('blank-buttons');
                const dropdownButtons = document.getElementById('dropdown-buttons');
                const dragZoneButtons = document.getElementById('drag-zone-buttons');
                
                if (blankButtons) blankButtons.style.display = 'none';
                if (dropdownButtons) dropdownButtons.style.display = 'none';
                if (dragZoneButtons) dragZoneButtons.style.display = 'none';
                
                if (selectedType === 'fill_blanks' && blankButtons) {
                    blankButtons.style.display = 'flex';
                } else if (selectedType === 'dropdown_selection' && dropdownButtons) {
                    dropdownButtons.style.display = 'flex';
                } else if (selectedType === 'drag_drop' && dragZoneButtons) {
                    dragZoneButtons.style.display = 'flex';
                }
            });
            
            // Initialize on load if type is already selected
            if (questionTypeSelect.value) {
                questionTypeSelect.dispatchEvent(new Event('change'));
            }
        }
        
        // Form submission handler
        const questionForm = document.getElementById('questionForm');
        if (questionForm) {
            questionForm.addEventListener('submit', function(e) {
                console.log('=== FORM SUBMISSION STARTED ===');
                
                // Save TinyMCE content
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
                
                // Get question type
                const questionType = document.getElementById('question_type').value;
                console.log('Question Type:', questionType);
                
                // Prepare submission data for listening question types
                if (window.ListeningQuestionTypes) {
                    window.ListeningQuestionTypes.prepareSubmissionData();
                }
                
                // Handle fill_blanks submission
                if (questionType === 'fill_blanks') {
                    handleFillBlanksSubmission();
                }
                
                console.log('=== FORM SUBMISSION COMPLETED ===');
            });
        }
        
        // Part audio check functionality
        function checkPartAudio(partNumber) {
            fetch(`/admin/test-sets/{{ $testSet->id }}/check-part-audio/${partNumber}`)
                .then(response => response.json())
                .then(data => {
                    const statusDiv = document.getElementById('part-audio-status');
                    
                    if (data.hasAudio) {
                        const audioType = data.isFullAudio ? 'Full audio' : `Part ${partNumber} audio`;
                        const audioTypeColor = data.isFullAudio ? 'purple' : 'green';
                        
                        statusDiv.innerHTML = `
                            <div class="bg-${audioTypeColor}-50 border border-${audioTypeColor}-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-${audioTypeColor}-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-${audioTypeColor}-800 font-medium">
                                            ✅ ${audioType} is available and ready to use!
                                        </p>
                                        <p class="text-xs text-${audioTypeColor}-700 mt-1">
                                            ${data.isFullAudio 
                                                ? 'This question will automatically use the full audio that covers all parts.' 
                                                : `This question will automatically use the Part ${partNumber} audio.`}
                                        </p>
                                        ${data.isFullAudio ? `
                                            <div class="mt-2 inline-flex items-center px-2.5 py-1 bg-purple-100 rounded-md text-xs font-semibold text-purple-800">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Using Full Audio for All Parts
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        statusDiv.innerHTML = `
                            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-red-800 font-medium">
                                            ❌ No audio uploaded for Part ${partNumber}
                                        </p>
                                        <p class="text-xs text-red-700 mt-1">
                                            You must upload a Full Audio or Part ${partNumber} audio before creating questions.
                                        </p>
                                        <a href="{{ route('admin.test-sets.part-audios', $testSet) }}" 
                                           target="_blank"
                                           class="inline-flex items-center mt-3 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Upload Audio Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        uploadSection.style.opacity = '1';
                        uploadSection.style.pointerEvents = 'auto';
                        mediaInput.setAttribute('required', 'required');
                        useCustomAudio.value = '1';
                    }
                })
                .catch(error => {
                    console.error('Error checking part audio:', error);
                });
        }
        
        // Check initial part audio status
        const partSelect = document.querySelector('[name="part_number"]');
        if (partSelect) {
            const initialPart = partSelect.value || 1;
            checkPartAudio(initialPart);
            
            partSelect.addEventListener('change', function() {
                checkPartAudio(this.value);
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.altKey) {
                const questionType = document.getElementById('question_type')?.value;
                
                if (questionType === 'fill_blanks' && (e.key === 'b' || e.key === 'B')) {
                    e.preventDefault();
                    insertListeningBlank();
                }
                
                if (questionType === 'dropdown_selection' && (e.key === 'd' || e.key === 'D')) {
                    e.preventDefault();
                    insertListeningDropdown();
                }
                
                if (questionType === 'drag_drop' && (e.key === 'g' || e.key === 'G')) {
                    e.preventDefault();
                    insertDragZone();
                }
            }
        });
    });
    
    // Global functions
    window.insertListeningBlank = function() {
        if (window.ListeningQuestionTypes) {
            // Call updateBlanks directly on ListeningQuestionTypes
            const editor = window.contentEditor || tinymce.activeEditor;
            if (!editor) {
                console.error('No editor found');
                return;
            }
            
            if (!window.listeningBlankCounter) {
                window.listeningBlankCounter = 0;
            }
            
            window.listeningBlankCounter++;
            const blankText = `[____${window.listeningBlankCounter}____]`;
            editor.insertContent(blankText);
            
            console.log('Inserted blank:', blankText);
            setTimeout(() => window.ListeningQuestionTypes.updateBlanks(), 100);
        }
    }
    
    window.insertListeningDropdown = function() {
        if (window.ListeningQuestionTypes && window.contentEditor) {
            window.ListeningQuestionTypes.setupDropdownInsertion();
            if (window.insertListeningDropdown) {
                window.insertListeningDropdown();
            }
        }
    }
    
    window.insertDragZone = function() {
        if (window.ListeningQuestionTypes) {
            const editor = window.contentEditor || tinymce.activeEditor;
            if (!editor) {
                console.error('No editor found');
                return;
            }
            
            if (!window.dragZoneCounter) {
                window.dragZoneCounter = 0;
            }
            
            window.dragZoneCounter++;
            const dragZoneText = `[DRAG_${window.dragZoneCounter}]`;
            editor.insertContent(dragZoneText);
            
            console.log('Inserted drag zone:', dragZoneText);
            setTimeout(() => window.ListeningQuestionTypes.updateDragZones(), 100);
        }
    }
    
    function handleFillBlanksSubmission() {
        // Extract and validate blank answers
        const blankInputs = document.querySelectorAll('input[name="blank_answers[]"]');
        console.log('Found blank inputs:', blankInputs.length);
        
        // Check if all blanks have answers
        let hasEmptyBlanks = false;
        blankInputs.forEach((input, index) => {
            if (!input.value || input.value.trim() === '') {
                console.error(`Blank ${index + 1} is empty`);
                hasEmptyBlanks = true;
            } else {
                console.log(`Blank ${index + 1} answer:`, input.value);
            }
        });
        
        if (hasEmptyBlanks) {
            console.error('Some blanks have no answers');
        }
    }
    
    function showBulkOptions() {
        const modal = document.getElementById('bulk-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }
    </script>
    @endpush
</x-admin-layout>