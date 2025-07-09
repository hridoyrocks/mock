<x-layout>
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
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Instructions</label>
                                            <button type="button" onclick="showTemplates()" class="text-sm text-purple-600 hover:text-purple-700">
                                                Use Template
                                            </button>
                                        </div>
                                        <textarea id="instructions" name="instructions" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 text-sm"
                                                  placeholder="e.g., Questions 1-5: Complete the form below. Write NO MORE THAN TWO WORDS AND/OR A NUMBER for each answer.">{{ old('instructions') }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mb-3 flex flex-wrap gap-2">
                                            <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700 transition-colors">
                                                Insert Blank
                                            </button>
                                        </div>
                                        <div class="border border-gray-300 rounded-md overflow-hidden" style="height: 350px;">
                                            <textarea id="content" name="content" class="tinymce">{{ old('content') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    @include('admin.questions.partials.question-settings', [
                                        'questionTypes' => [
                                            'multiple_choice' => 'Multiple Choice',
                                            'form_completion' => 'Form Completion',
                                            'note_completion' => 'Note Completion',
                                            'sentence_completion' => 'Sentence Completion',
                                            'short_answer' => 'Short Answer',
                                            'matching' => 'Matching',
                                            'plan_map_diagram' => 'Plan/Map/Diagram Labeling'
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
                    
                    {{-- Regular Options Manager (hidden for special types) --}}
                    <div id="options-card" class="bg-white rounded-lg shadow-sm hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Answer Options</h3>
                            <button type="button" onclick="showBulkOptions()" class="text-sm text-blue-600 hover:text-blue-700">
                                Add Bulk Options
                            </button>
                        </div>
                        
                        <div class="p-6">
                            <div id="options-container" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Options will be dynamically added here -->
                            </div>
                            
                            <button type="button" id="add-option-btn" 
                                    class="mt-4 w-full md:w-auto px-4 py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-md hover:border-gray-400 hover:text-gray-600 transition-all">
                                + Add Option
                            </button>
                        </div>
                    </div>
                    
                    {{-- Type-specific panels --}}
                    <div id="type-specific-panels">
                        {{-- Matching Questions Panel --}}
                        <div id="matching-panel" class="type-specific-panel bg-white rounded-lg shadow-sm overflow-hidden" style="display: none;">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-yellow-50">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                    Matching Pairs Setup
                                </h3>
                            </div>
                            
                            <div class="p-4 sm:p-6">
                                <p class="text-sm text-gray-600 mb-4">
                                    Create matching pairs. Students will need to match items from the left with options on the right.
                                </p>
                                
                                <div id="matching-pairs-container">
                                    {{-- Pairs will be added here dynamically --}}
                                </div>
                                
                                <button type="button" onclick="QuestionTypeHandlers.addMatchingPair()" 
                                        class="mt-3 px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 text-sm">
                                    + Add Matching Pair
                                </button>
                            </div>
                        </div>
                        
                        {{-- Form Completion Panel --}}
                        <div id="form-completion-panel" class="type-specific-panel bg-white rounded-lg shadow-sm overflow-hidden" style="display: none;">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-green-50">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                    Form Structure Setup
                                </h3>
                            </div>
                            
                            <div class="p-4 sm:p-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Form Title</label>
                                    <input type="text" name="form_structure[title]" 
                                           placeholder="e.g., Student Registration Form"
                                           class="w-full px-3 py-2 border rounded-md text-sm">
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-4">
                                    Add form fields that students need to complete:
                                </p>
                                
                                <div id="form-fields-container">
                                    {{-- Fields will be added here dynamically --}}
                                </div>
                                
                                <button type="button" onclick="QuestionTypeHandlers.addFormField()" 
                                        class="mt-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                    + Add Form Field
                                </button>
                            </div>
                        </div>
                        
                        {{-- Diagram Labeling Panel --}}
                        <div id="diagram-panel" class="type-specific-panel bg-white rounded-lg shadow-sm overflow-hidden" style="display: none;">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-blue-50">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                    Plan/Map/Diagram Setup
                                </h3>
                            </div>
                            
                            <div class="p-4 sm:p-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Diagram Image</label>
                                    <input type="file" id="diagram-image" name="diagram_image" 
                                           accept="image/*"
                                           class="w-full px-3 py-2 border rounded-md text-sm">
                                </div>
                                
                                <div id="diagram-preview" class="mb-4 relative">
                                    {{-- Image preview and hotspot markers --}}
                                </div>
                                
                                <div id="hotspots-container">
                                    {{-- Hotspot fields will be added here --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Find the Audio Upload section and REPLACE it with this --}}
<!-- Audio Management -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-purple-50">
        <h3 class="text-base sm:text-lg font-medium text-gray-900">
            Audio Settings
        </h3>
    </div>
    
    <div class="p-4 sm:p-6">
        {{-- Part Audio Status Check --}}
        <div id="part-audio-status" class="mb-4">
            {{-- This will be updated dynamically via JavaScript --}}
        </div>
        
        {{-- Audio Upload Zone --}}
        <div id="audio-upload-section">
            <div id="drop-zone" class="border-2 border-dashed border-purple-300 rounded-lg p-6 sm:p-8 text-center hover:border-purple-400 transition-colors cursor-pointer bg-purple-50/30">
                <svg class="mx-auto h-10 sm:h-12 w-10 sm:w-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-600">
                    <label for="media" class="cursor-pointer text-purple-600 hover:text-purple-700 font-medium">
                        Click to upload custom audio
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
        
        {{-- Hidden field to track if using custom audio --}}
        <input type="hidden" name="use_custom_audio" id="use_custom_audio" value="0">
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
        /* Professional styles */
        .drag-over {
            border-color: #9333EA !important;
            background-color: #FAF5FF !important;
        }
        
        /* Hide emoji button in TinyMCE */
        .tox-tbtn[aria-label="Emoticons"] {
            display: none !important;
        }
        
        /* Responsive editor */
        @media (max-width: 640px) {
            .tox-tinymce {
                height: 300px !important;
            }
        }
        
        /* Professional audio preview */
        #media-preview audio {
            width: 100%;
            height: 40px;
        }
        
        /* Clean notification */
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
        
        /* Success notification */
        .notification.success {
            background: #059669;
        }
        
        /* Error notification */
        .notification.error {
            background: #DC2626;
        }
        
        /* Blank placeholder */
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
        
        /* Sticky header on mobile */
        @media (max-width: 640px) {
            .sticky {
                box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            }
        }
        
        /* Hotspot marker styles */
        .hotspot-marker {
            position: absolute;
            width: 30px;
            height: 30px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transform: translate(-50%, -50%);
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        /* Type specific panel styles */
        .type-specific-panel {
            transition: all 0.3s ease;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-listening.js') }}"></script>
    <script src="{{ asset('js/admin/question-types.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TinyMCE
        tinymce.init({
            selector: '.tinymce',
            plugins: 'lists link table code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link | code',
            height: 350,
            menubar: false,
            branding: false
        });
        
        // Question type change handler
        const questionTypeSelect = document.getElementById('question_type');
        questionTypeSelect.addEventListener('change', function() {
            QuestionTypeHandlers.init(this.value);
            
            // Show/hide regular options card
            const optionsCard = document.getElementById('options-card');
            const specialTypes = ['matching', 'form_completion', 'plan_map_diagram'];
            
            if (specialTypes.includes(this.value)) {
                optionsCard.classList.add('hidden');
            } else if (this.value && !['short_answer', 'sentence_completion', 'note_completion', 'form_completion'].includes(this.value)) {
                optionsCard.classList.remove('hidden');
            } else {
                optionsCard.classList.add('hidden');
            }
        });
        
        // Initialize on load if type is already selected
        if (questionTypeSelect.value) {
            questionTypeSelect.dispatchEvent(new Event('change'));
        }
        
        // ========== PART AUDIO CHECK FUNCTIONALITY ==========
        function checkPartAudio(partNumber) {
            fetch(`/admin/test-sets/{{ $testSet->id }}/check-part-audio/${partNumber}`)
                .then(response => response.json())
                .then(data => {
                    const statusDiv = document.getElementById('part-audio-status');
                    const uploadSection = document.getElementById('audio-upload-section');
                    const mediaInput = document.getElementById('media');
                    const useCustomAudio = document.getElementById('use_custom_audio');
                    
                    if (data.hasAudio) {
                        // Part audio exists
                        statusDiv.innerHTML = `
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-green-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-green-800 font-medium">
                                            Part ${partNumber} audio is available!
                                        </p>
                                        <p class="text-xs text-green-700 mt-1">
                                            This question will automatically use the Part ${partNumber} audio unless you upload a custom audio file.
                                        </p>
                                        <label class="inline-flex items-center mt-2">
                                            <input type="checkbox" id="custom-audio-checkbox" class="form-checkbox text-purple-600" onchange="toggleCustomAudioUpload()">
                                            <span class="ml-2 text-sm text-gray-700">Upload custom audio for this question only</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Make upload optional by default
                        uploadSection.style.opacity = '0.5';
                        uploadSection.style.pointerEvents = 'none';
                        mediaInput.removeAttribute('required');
                        useCustomAudio.value = '0';
                        
                    } else {
                        // No part audio
                        statusDiv.innerHTML = `
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-yellow-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-yellow-800 font-medium">
                                            No audio uploaded for Part ${partNumber}
                                        </p>
                                        <p class="text-xs text-yellow-700 mt-1">
                                            You must upload audio for this question, or upload Part ${partNumber} audio first for all questions to share.
                                        </p>
                                        <a href="{{ route('admin.test-sets.part-audios', $testSet) }}" 
                                           target="_blank"
                                           class="inline-flex items-center mt-2 text-xs text-yellow-600 hover:text-yellow-700 font-medium">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Upload Part ${partNumber} Audio (opens in new tab)
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Make upload required
                        uploadSection.style.opacity = '1';
                        uploadSection.style.pointerEvents = 'auto';
                        mediaInput.setAttribute('required', 'required');
                        useCustomAudio.value = '1';
                    }
                })
                .catch(error => {
                    console.error('Error checking part audio:', error);
                    // Show error message
                    const statusDiv = document.getElementById('part-audio-status');
                    statusDiv.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-800">Error checking part audio status. Please refresh the page.</p>
                        </div>
                    `;
                });
        }
        
        // Toggle custom audio upload
        window.toggleCustomAudioUpload = function() {
            const checkbox = document.getElementById('custom-audio-checkbox');
            const uploadSection = document.getElementById('audio-upload-section');
            const mediaInput = document.getElementById('media');
            const useCustomAudio = document.getElementById('use_custom_audio');
            
            if (checkbox && checkbox.checked) {
                uploadSection.style.opacity = '1';
                uploadSection.style.pointerEvents = 'auto';
                mediaInput.setAttribute('required', 'required');
                useCustomAudio.value = '1';
            } else {
                uploadSection.style.opacity = '0.5';
                uploadSection.style.pointerEvents = 'none';
                mediaInput.removeAttribute('required');
                useCustomAudio.value = '0';
                // Clear any selected file
                mediaInput.value = '';
                document.getElementById('media-preview').classList.add('hidden');
            }
        }
        
        // Check initial part audio status
        const partSelect = document.querySelector('[name="part_number"]');
        if (partSelect) {
            const initialPart = partSelect.value || 1;
            checkPartAudio(initialPart);
            
            // Listen for part number changes
            partSelect.addEventListener('change', function() {
                checkPartAudio(this.value);
                // Reset custom audio checkbox when part changes
                const customCheckbox = document.getElementById('custom-audio-checkbox');
                if (customCheckbox) {
                    customCheckbox.checked = false;
                }
            });
        }
        
        // ========== END PART AUDIO CHECK ==========
        
        // File upload handling
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('media');
        const mediaPreview = document.getElementById('media-preview');
        
        if (dropZone && fileInput) {
            dropZone.addEventListener('click', () => {
                // Only allow click if not disabled
                if (dropZone.parentElement.parentElement.style.pointerEvents !== 'none') {
                    fileInput.click();
                }
            });
            
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                if (dropZone.parentElement.parentElement.style.pointerEvents !== 'none') {
                    dropZone.classList.add('drag-over');
                }
            });
            
            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('drag-over');
            });
            
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                
                if (dropZone.parentElement.parentElement.style.pointerEvents !== 'none') {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        handleFileSelect(files[0]);
                    }
                }
            });
            
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFileSelect(e.target.files[0]);
                }
            });
        }
        
        function handleFileSelect(file) {
            const mediaPreview = document.getElementById('media-preview');
            if (mediaPreview) {
                mediaPreview.innerHTML = `
                    <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${file.name}</p>
                            <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        </div>
                        <button type="button" onclick="clearAudioUpload()" class="ml-3 text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
                mediaPreview.classList.remove('hidden');
            }
        }
        
        // Clear audio upload
        window.clearAudioUpload = function() {
            const fileInput = document.getElementById('media');
            const mediaPreview = document.getElementById('media-preview');
            
            if (fileInput) fileInput.value = '';
            if (mediaPreview) {
                mediaPreview.innerHTML = '';
                mediaPreview.classList.add('hidden');
            }
        }
        
        // Options handling
        const addOptionBtn = document.getElementById('add-option-btn');
        const optionsContainer = document.getElementById('options-container');
        let optionCount = 0;
        
        if (addOptionBtn) {
            addOptionBtn.addEventListener('click', function() {
                addOption();
            });
        }
        
        function addOption() {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'option-item flex items-center gap-3';
            optionDiv.innerHTML = `
                <input type="radio" name="correct_option" value="${optionCount}" 
                       class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                <input type="text" name="options[${optionCount}][content]" 
                       placeholder="Option ${String.fromCharCode(65 + optionCount)}"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                       required>
                <button type="button" onclick="removeOption(this)" 
                        class="text-red-500 hover:text-red-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            optionsContainer.appendChild(optionDiv);
            optionCount++;
        }
        
        window.removeOption = function(button) {
            button.closest('.option-item').remove();
            reindexOptions();
        }
        
        function reindexOptions() {
            const options = optionsContainer.querySelectorAll('.option-item');
            optionCount = 0;
            options.forEach((option, index) => {
                const radio = option.querySelector('input[type="radio"]');
                const textInput = option.querySelector('input[type="text"]');
                
                radio.value = index;
                textInput.name = `options[${index}][content]`;
                textInput.placeholder = `Option ${String.fromCharCode(65 + index)}`;
                optionCount++;
            });
        }
    });
    
    // Preview function
    function previewQuestion() {
        // Implementation for preview
        alert('Preview functionality to be implemented');
    }
    
    // Template function
    function showTemplates() {
        // Implementation for templates
        alert('Template functionality to be implemented');
    }
    
    // Insert blank function
    function insertBlank() {
        // Get TinyMCE instance
        const editor = tinymce.get('content');
        if (editor) {
            // Insert blank at cursor position
            editor.insertContent('[____' + (Date.now() % 1000) + '____]');
        }
    }
    
    // Bulk options function
    function showBulkOptions() {
        // Implementation for bulk options
        const modal = document.getElementById('bulk-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }
    </script>
@endpush
</x-layout>