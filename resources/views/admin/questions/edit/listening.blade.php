<x-layout>
    <x-slot:title>Edit Question - Listening</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold">Edit Listening Question</h1>
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
            
            <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                @method('PUT')
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
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Instructions</label>
                                            <button type="button" onclick="showTemplates()" class="text-sm text-purple-600 hover:text-purple-700">
                                                Use Template
                                            </button>
                                        </div>
                                        <textarea id="instructions" name="instructions" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 text-sm"
                                                  placeholder="e.g., Questions 1-5: Complete the form below. Write NO MORE THAN TWO WORDS AND/OR A NUMBER for each answer.">{{ old('instructions', $question->instructions) }}</textarea>
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
                                        <div class="border border-gray-300 rounded-md overflow-hidden" style="height: 350px;">
                                            <textarea id="content" name="content" class="tinymce">{{ old('content', $question->content) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Question Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                                            <select id="question_type" name="question_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                                                <option value="">Select type...</option>
                                                @foreach([
                                                    'fill_blanks' => 'Fill in the Blanks',
                                                    'single_choice' => 'Single Choice (Radio)',
                                                    'multiple_choice' => 'Multiple Choice (Checkbox)',
                                                    'dropdown_selection' => 'Dropdown Selection'
                                                ] as $key => $type)
                                                    <option value="{{ $key }}" {{ old('question_type', $question->question_type) == $key ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- Question Number -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Number <span class="text-red-500">*</span></label>
                                            <input type="number" name="order_number" value="{{ old('order_number', $question->order_number) }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500" min="0" required>
                                        </div>
                                        
                                        <!-- Part Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Part <span class="text-red-500">*</span></label>
                                            <select name="part_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                                                @for($i = 1; $i <= 4; $i++)
                                                    <option value="{{ $i }}" {{ old('part_number', $question->part_number) == $i ? 'selected' : '' }}>
                                                        Part {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <!-- Marks -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Marks</label>
                                            <input type="number" name="marks" value="{{ old('marks', $question->marks) }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                                                   min="0" max="40">
                                        </div>
                                    </div>
                                    
                                    <!-- Audio Transcript -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Audio Transcript
                                        </label>
                                        <textarea name="audio_transcript" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 text-sm"
                                                  placeholder="Enter the transcript of the audio...">{{ old('audio_transcript', $question->audio_transcript) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Include listening question type panels --}}
                    @include('admin.questions.partials.listening-question-types')
                    

                    
                    {{-- Type-specific panels --}}
                    <div id="type-specific-panels">
                        {{-- Listening question types will be handled by listening-question-types.js --}}
                    </div>
                    
                    <!-- Audio Upload -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-purple-50">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                Audio Upload
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            @if($question->media_path && !in_array($question->question_type, ['plan_map_diagram']))
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Audio:</label>
                                    <audio controls class="w-full">
                                        <source src="{{ asset('storage/' . $question->media_path) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <div class="mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="remove_media" value="1" class="form-checkbox">
                                            <span class="ml-2 text-sm text-red-600">Remove current audio</span>
                                        </label>
                                    </div>
                                </div>
                            @endif
                            
                            <div id="drop-zone" class="border-2 border-dashed border-purple-300 rounded-lg p-6 sm:p-8 text-center hover:border-purple-400 transition-colors cursor-pointer bg-purple-50/30">
                                <svg class="mx-auto h-10 sm:h-12 w-10 sm:w-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <label for="media" class="cursor-pointer text-purple-600 hover:text-purple-700 font-medium">
                                        Click to upload new audio
                                    </label>
                                    <span class="hidden sm:inline"> or drag and drop</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">MP3, WAV, OGG up to 50MB</p>
                                <input id="media" name="media" type="file" class="hidden" accept=".mp3,.wav,.ogg">
                            </div>
                            
                            <div id="media-preview" class="mt-4 hidden">
                                <!-- Preview will be shown here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" class="flex-1 py-2.5 sm:py-3 bg-purple-600 text-white font-medium rounded-md hover:bg-purple-700 transition-colors text-sm sm:text-base">
                                Update Question
                            </button>
                            <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                               class="flex-1 py-2.5 sm:py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors text-sm sm:text-base text-center">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('styles')
    <style>
        /* Same styles as create view */
        .drag-over {
            border-color: #9333EA !important;
            background-color: #FAF5FF !important;
        }
        
        .blank-placeholder {
            background-color: #E9D5FF;
            padding: 2px 8px;
            margin: 0 4px;
            border-bottom: 2px solid #9333EA;
            border-radius: 2px;
            font-weight: 500;
            color: #581C87;
            cursor: not-allowed;
            user-select: none;
            display: inline-block;
            min-width: 60px;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/listening-question-types.js') }}"></script>
    
    <script>
    let contentEditor = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TinyMCE
        tinymce.init({
            selector: '.tinymce',
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
                    
                    // Update blanks/dropdowns based on question type
                    const questionType = document.getElementById('question_type')?.value;
                    if (questionType === 'fill_blanks') {
                        if (window.ListeningQuestionTypes) {
                            window.ListeningQuestionTypes.updateBlanks();
                        }
                    } else if (questionType === 'dropdown_selection') {
                        if (window.ListeningQuestionTypes) {
                            window.ListeningQuestionTypes.updateDropdowns();
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
                
                // Show/hide blank and dropdown buttons based on type
                if (selectedType === 'fill_blanks') {
                    document.getElementById('blank-buttons').style.display = 'flex';
                    document.getElementById('dropdown-buttons').style.display = 'none';
                } else if (selectedType === 'dropdown_selection') {
                    document.getElementById('blank-buttons').style.display = 'none';
                    document.getElementById('dropdown-buttons').style.display = 'flex';
                } else {
                    document.getElementById('blank-buttons').style.display = 'none';
                    document.getElementById('dropdown-buttons').style.display = 'none';
                }
            });
            
            // Initialize on load if type is already selected
            if (questionTypeSelect.value) {
                questionTypeSelect.dispatchEvent(new Event('change'));
            }
        }
        
        // File upload handling
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('media');
        
        dropZone.addEventListener('click', () => fileInput.click());
        
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
        
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-over');
        });
        
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });
        
        function handleFileSelect(file) {
            const preview = document.getElementById('media-preview');
            preview.innerHTML = `
                <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">${file.name}</p>
                        <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    </div>
                </div>
            `;
            preview.classList.remove('hidden');
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
            }
        });
    });
    
    // Global functions
    window.insertListeningBlank = function() {
        if (window.ListeningQuestionTypes) {
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
    </script>
    @endpush
</x-layout>