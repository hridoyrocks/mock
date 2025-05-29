<x-layout>
    <x-slot:title>Create Question - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Question') }}
            </h2>
            <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                Back to Questions
            </a>
        </div>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Progress Steps -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-medium">1</div>
                                <div class="ml-2 text-sm font-medium text-blue-600">Basic Info</div>
                            </div>
                            <div class="flex-1 mx-4 h-1 bg-gray-200"></div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-medium">2</div>
                                <div class="ml-2 text-sm font-medium text-gray-600">Content</div>
                            </div>
                            <div class="flex-1 mx-4 h-1 bg-gray-200"></div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-medium">3</div>
                                <div class="ml-2 text-sm font-medium text-gray-600">Options</div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="question-form">
                        @csrf
                        
                        <!-- Step 1: Basic Information -->
                        <div class="step-content" id="step-1">
                            <h3 class="text-lg font-medium mb-6">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="test_set_id" class="block text-sm font-medium text-gray-700 mb-2">Test Set *</label>
                                    <select id="test_set_id" name="test_set_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select a test set</option>
                                        @foreach($testSets->groupBy('section.name') as $sectionName => $sets)
                                            <optgroup label="{{ ucfirst($sectionName) }}">
                                                @foreach($sets as $testSet)
                                                    <option value="{{ $testSet->id }}" {{ old('test_set_id', $preselectedTestSet) == $testSet->id ? 'selected' : '' }}>
                                                        {{ $testSet->title }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('test_set_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Question Number *</label>
                                    <input type="number" id="order_number" name="order_number" min="1" value="{{ old('order_number', 1) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @error('order_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="question_type" class="block text-sm font-medium text-gray-700 mb-2">Question Type *</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="passage" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">📄</div>
                                            <div class="text-sm font-medium">Passage</div>
                                            <div class="text-xs text-gray-500">Reading text</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="multiple_choice" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">☑️</div>
                                            <div class="text-sm font-medium">Multiple Choice</div>
                                            <div class="text-xs text-gray-500">Select one answer</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="true_false" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">✅</div>
                                            <div class="text-sm font-medium">True/False</div>
                                            <div class="text-xs text-gray-500">T/F/Not Given</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="matching" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">🔗</div>
                                            <div class="text-sm font-medium">Matching</div>
                                            <div class="text-xs text-gray-500">Connect pairs</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="fill_blank" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">📝</div>
                                            <div class="text-sm font-medium">Fill Blank</div>
                                            <div class="text-xs text-gray-500">Complete text</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="short_answer" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">💬</div>
                                            <div class="text-sm font-medium">Short Answer</div>
                                            <div class="text-xs text-gray-500">Brief response</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="essay" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">📋</div>
                                            <div class="text-sm font-medium">Essay</div>
                                            <div class="text-xs text-gray-500">Long writing</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="question_type" value="cue_card" class="sr-only question-type-radio">
                                        <div class="question-type-card w-full text-center">
                                            <div class="text-2xl mb-1">🎤</div>
                                            <div class="text-sm font-medium">Cue Card</div>
                                            <div class="text-xs text-gray-500">Speaking topic</div>
                                        </div>
                                    </label>
                                </div>
                                @error('question_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" id="next-to-step-2" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50" disabled>
                                    Next: Add Content
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Content -->
                        <div class="step-content hidden" id="step-2">
                            <h3 class="text-lg font-medium mb-6">Question Content</h3>
                            
                            <div id="question-description" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md text-sm text-blue-800 hidden"></div>
                            
                            <div class="mb-6">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                                <textarea id="content" name="content" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your question content here..." required>{{ old('content') }}</textarea>
                                <div class="mt-1 text-xs text-gray-500">
                                    <span id="content-char-count">0</span> characters
                                </div>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-6">
                                <label for="media" class="block text-sm font-medium text-gray-700 mb-2">Media File (Optional)</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="media" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> media file</p>
                                            <p class="text-xs text-gray-500">Images: JPG, PNG, GIF | Audio: MP3, WAV, OGG (Max: 10MB)</p>
                                        </div>
                                        <input id="media" name="media" type="file" accept="image/*,audio/*" class="hidden" />
                                    </label>
                                </div>
                                <div id="media-preview" class="mt-3 hidden"></div>
                                @error('media')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-between">
                                <button type="button" id="back-to-step-1" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    Back
                                </button>
                                <button type="button" id="next-to-step-3" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Next: Configure Options
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Options -->
                        <div class="step-content hidden" id="step-3">
                            <h3 class="text-lg font-medium mb-6">Answer Options</h3>
                            
                            <div id="options-section" class="hidden">
                                <div id="options-container" class="space-y-4 mb-6">
                                    <!-- Options will be dynamically added here -->
                                </div>
                                
                                <div class="mb-6">
                                    <button type="button" id="add-option" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                        + Add Option
                                    </button>
                                </div>
                            </div>
                            
                            <div id="no-options-message" class="p-4 bg-green-50 border border-green-200 rounded-md text-green-800 text-sm">
                                This question type doesn't require answer options. Students will provide their own responses.
                            </div>

                            <div class="flex justify-between">
                                <button type="button" id="back-to-step-2" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    Back
                                </button>
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    Create Question
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            let selectedQuestionType = null;
            let optionCount = 0;
            
            // Elements
            const steps = {
                1: document.getElementById('step-1'),
                2: document.getElementById('step-2'),
                3: document.getElementById('step-3')
            };
            
            const questionTypeRadios = document.querySelectorAll('.question-type-radio');
            const contentTextarea = document.getElementById('content');
            const contentCharCount = document.getElementById('content-char-count');
            const mediaInput = document.getElementById('media');
            const mediaPreview = document.getElementById('media-preview');
            
            // Navigation buttons
            const nextToStep2 = document.getElementById('next-to-step-2');
            const nextToStep3 = document.getElementById('next-to-step-3');
            const backToStep1 = document.getElementById('back-to-step-1');
            const backToStep2 = document.getElementById('back-to-step-2');
            
            // Question type selection
            questionTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedQuestionType = this.value;
                    updateQuestionTypeUI();
                    nextToStep2.disabled = false;
                    
                    // Update all visual cards
                    document.querySelectorAll('label[for*="question_type"]').forEach(label => {
                        label.classList.remove('border-blue-500', 'bg-blue-50');
                    });
                    this.closest('label').classList.add('border-blue-500', 'bg-blue-50');
                });
            });
            
            // Content character count
            contentTextarea.addEventListener('input', function() {
                contentCharCount.textContent = this.value.length;
            });
            
            // Media upload preview
            mediaInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const isImage = file.type.startsWith('image/');
                        const isAudio = file.type.startsWith('audio/');
                        
                        let previewHTML = '';
                        if (isImage) {
                            previewHTML = `<img src="${e.target.result}" class="max-h-32 rounded border">`;
                        } else if (isAudio) {
                            previewHTML = `<audio controls class="w-full"><source src="${e.target.result}" type="${file.type}"></audio>`;
                        }
                        
                        previewHTML += `<div class="mt-2 text-sm text-gray-600">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</div>`;
                        
                        mediaPreview.innerHTML = previewHTML;
                        mediaPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    mediaPreview.classList.add('hidden');
                }
            });
            
            // Navigation
            nextToStep2.addEventListener('click', () => showStep(2));
            nextToStep3.addEventListener('click', () => showStep(3));
            backToStep1.addEventListener('click', () => showStep(1));
            backToStep2.addEventListener('click', () => showStep(2));
            
            function showStep(stepNumber) {
                // Hide all steps
                Object.values(steps).forEach(step => step.classList.add('hidden'));
                
                // Show current step
                steps[stepNumber].classList.remove('hidden');
                
                // Update progress
                updateProgress(stepNumber);
                
                // Handle step-specific logic
                if (stepNumber === 2) {
                    updateContentPlaceholder();
                } else if (stepNumber === 3) {
                    setupOptionsStep();
                }
                
                currentStep = stepNumber;
            }
            
            function updateProgress(activeStep) {
                for (let i = 1; i <= 3; i++) {
                    const circle = document.querySelector(`div:nth-child(${i * 2 - 1}) .w-8`);
                    const text = document.querySelector(`div:nth-child(${i * 2 - 1}) .ml-2`);
                    
                    if (i <= activeStep) {
                        circle.classList.remove('bg-gray-300', 'text-gray-600');
                        circle.classList.add('bg-blue-600', 'text-white');
                        text.classList.remove('text-gray-600');
                        text.classList.add('text-blue-600');
                    } else {
                        circle.classList.remove('bg-blue-600', 'text-white');
                        circle.classList.add('bg-gray-300', 'text-gray-600');
                        text.classList.remove('text-blue-600');
                        text.classList.add('text-gray-600');
                    }
                }
            }
            
            function updateQuestionTypeUI() {
                const descriptions = {
                    'passage': 'This will be displayed as reference material for students to read.',
                    'multiple_choice': 'Students will select one correct answer from multiple options.',
                    'true_false': 'Students will choose True, False, or Not Given.',
                    'matching': 'Students will match items from two lists or categories.',
                    'fill_blank': 'Students will fill in missing words in the text.',
                    'short_answer': 'Students will provide brief written responses.',
                    'essay': 'Students will write detailed responses (for Writing section).',
                    'cue_card': 'Students will speak on this topic (for Speaking section).'
                };
                
                const descElement = document.getElementById('question-description');
                if (descriptions[selectedQuestionType]) {
                    descElement.textContent = descriptions[selectedQuestionType];
                    descElement.classList.remove('hidden');
                }
            }
            
            function updateContentPlaceholder() {
                const placeholders = {
                    'passage': 'Enter the reading passage or text content here...',
                    'multiple_choice': 'Enter your question here...',
                    'true_false': 'Enter a statement for students to evaluate...',
                    'matching': 'Enter matching instructions...',
                    'fill_blank': 'Enter text with blanks using _____ for each blank...',
                    'short_answer': 'Enter question requiring a brief written response...',
                    'essay': 'Enter essay prompt or task description...',
                    'cue_card': 'Enter speaking topic with bullet points for guidance...'
                };
                
                contentTextarea.placeholder = placeholders[selectedQuestionType] || 'Enter your content here...';
            }
            
            function setupOptionsStep() {
                const needsOptions = ['multiple_choice', 'true_false', 'matching'].includes(selectedQuestionType);
                const optionsSection = document.getElementById('options-section');
                const noOptionsMessage = document.getElementById('no-options-message');
                
                if (needsOptions) {
                    optionsSection.classList.remove('hidden');
                    noOptionsMessage.classList.add('hidden');
                    
                    if (selectedQuestionType === 'true_false') {
                        setupTrueFalseOptions();
                    } else {
                        setupDynamicOptions();
                    }
                } else {
                    optionsSection.classList.add('hidden');
                    noOptionsMessage.classList.remove('hidden');
                }
            }
            
            function setupTrueFalseOptions() {
                const optionsContainer = document.getElementById('options-container');
                const addButton = document.getElementById('add-option');
                
                optionsContainer.innerHTML = '';
                addButton.style.display = 'none';
                
                const tfOptions = ['True', 'False', 'Not Given'];
                tfOptions.forEach((option, index) => {
                    addOption(option, index, true);
                });
            }
            
            function setupDynamicOptions() {
                const optionsContainer = document.getElementById('options-container');
                const addButton = document.getElementById('add-option');
                
                optionsContainer.innerHTML = '';
                addButton.style.display = 'inline-block';
                optionCount = 0;
                
                // Add initial options
                const initialCount = selectedQuestionType === 'multiple_choice' ? 4 : 3;
                for (let i = 0; i < initialCount; i++) {
                    addOption();
                }
                
                // Add option button handler
                addButton.onclick = () => addOption();
            }
            
            function addOption(predefinedText = '', index = null, isFixed = false) {
                const optionsContainer = document.getElementById('options-container');
                const currentIndex = index !== null ? index : optionCount;
                
                const optionDiv = document.createElement('div');
                optionDiv.className = 'flex items-center gap-3 p-3 border border-gray-200 rounded-md bg-gray-50';
                optionDiv.innerHTML = `
                    <input type="radio" name="correct_option" value="${currentIndex}" class="w-4 h-4 text-blue-600" ${currentIndex === 0 ? 'checked' : ''}>
                    <input type="text" name="options[${currentIndex}][content]" value="${predefinedText}" placeholder="Enter option text..." class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required ${isFixed ? 'readonly' : ''}>
                    ${!isFixed ? `<button type="button" class="px-3 py-2 text-red-600 hover:text-red-800 text-sm" onclick="this.parentElement.remove()">Remove</button>` : ''}
                `;
                
                optionsContainer.appendChild(optionDiv);
                
                if (index === null) {
                    optionCount++;
                }
            }
            
            // Initialize
            updateProgress(1);
        });
    </script>
    @endpush
</x-layout>