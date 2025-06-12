<x-layout>
    <x-slot:title>Create IELTS Question - Professional Wizard</x-slot>
    
    <x-slot:header>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    IELTS Question Creator
                </h2>
                <p class="text-gray-600 mt-1">Create professional IELTS questions with our easy-to-use wizard</p>
            </div>
            <a href="{{ route('admin.questions.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                ← Back to Questions
            </a>
        </div>
    </x-slot:header>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-8">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-medium step-indicator" data-step="1">1</div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Section & Type</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 font-medium step-indicator" data-step="2">2</div>
                        <span class="ml-3 text-sm font-medium text-gray-500">Question Details</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 font-medium step-indicator" data-step="3">3</div>
                        <span class="ml-3 text-sm font-medium text-gray-500">Media & Options</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 font-medium step-indicator" data-step="4">4</div>
                        <span class="ml-3 text-sm font-medium text-gray-500">Review & Save</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="question-wizard">
                @csrf
                
                <!-- Step 1: Section & Type Selection -->
                <div class="step-content bg-white rounded-xl shadow-lg p-8 mb-6" data-step="1">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Choose IELTS Section & Question Type</h3>
                        <p class="text-gray-600">Select the section and specific question type you want to create</p>
                    </div>

                    <!-- Section Selection Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="section-card cursor-pointer border-2 border-gray-200 rounded-xl p-6 text-center transition-all hover:border-blue-500 hover:shadow-lg" data-section="listening">
                            <div class="text-4xl mb-4">🎧</div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Listening</h4>
                            <p class="text-sm text-gray-600">Audio-based questions with various formats</p>
                        </div>
                        
                        <div class="section-card cursor-pointer border-2 border-gray-200 rounded-xl p-6 text-center transition-all hover:border-blue-500 hover:shadow-lg" data-section="reading">
                            <div class="text-4xl mb-4">📖</div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Reading</h4>
                            <p class="text-sm text-gray-600">Text comprehension and analysis questions</p>
                        </div>
                        
                        <div class="section-card cursor-pointer border-2 border-gray-200 rounded-xl p-6 text-center transition-all hover:border-blue-500 hover:shadow-lg" data-section="writing">
                            <div class="text-4xl mb-4">✍️</div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Writing</h4>
                            <p class="text-sm text-gray-600">Task 1 & Task 2 writing prompts</p>
                        </div>
                        
                        <div class="section-card cursor-pointer border-2 border-gray-200 rounded-xl p-6 text-center transition-all hover:border-blue-500 hover:shadow-lg" data-section="speaking">
                            <div class="text-4xl mb-4">🎤</div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Speaking</h4>
                            <p class="text-sm text-gray-600">Conversation and presentation topics</p>
                        </div>
                    </div>

                    <!-- Test Set Selection -->
                    <div id="test-set-selection" class="mb-8 hidden">
                        <label class="block text-lg font-medium text-gray-900 mb-4">Select Test Set</label>
                        <select id="test_set_id" name="test_set_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                            <option value="">Choose a test set...</option>
                            @foreach($testSets as $testSet)
                                <option value="{{ $testSet->id }}" data-section="{{ $testSet->section->name }}">
                                    {{ $testSet->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Question Type Selection -->
                    <div id="question-type-selection" class="hidden">
                        <label class="block text-lg font-medium text-gray-900 mb-4">Select Question Type</label>
                        <div id="question-types-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Question type cards will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" id="selected_section" name="section" value="">
                    <input type="hidden" id="selected_question_type" name="question_type" value="">
                </div>

                <!-- Step 2: Question Details -->
                <div class="step-content bg-white rounded-xl shadow-lg p-8 mb-6 hidden" data-step="2">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Question Details</h3>
                        <p class="text-gray-600">Enter the main content and settings for your question</p>
                    </div>

                    <div class="max-w-4xl mx-auto space-y-6">
                        <!-- Question Number -->
                        <div>
                            <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Question Number</label>
                            <input type="number" id="order_number" name="order_number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question number (e.g., 1, 2, 3...)">
                        </div>

                        <!-- Question Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Question Content</label>
                            <div id="content-template" class="mb-3 p-4 bg-blue-50 rounded-lg hidden">
                                <h4 class="font-medium text-blue-900 mb-2">Template for this question type:</h4>
                                <p class="text-blue-800 text-sm"></p>
                            </div>
                            <textarea id="content" name="content" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your question content here..."></textarea>
                            <p class="mt-2 text-sm text-gray-500">Be clear and specific. Use proper IELTS question formatting.</p>
                        </div>

                        <!-- Dynamic Fields Container -->
                        <div id="dynamic-fields">
                            <!-- Word Limit -->
                            <div id="word-limit-field" class="hidden">
                                <label for="word_limit" class="block text-sm font-medium text-gray-700 mb-2">Word Limit</label>
                                <div class="flex items-center space-x-4">
                                    <input type="number" id="word_limit" name="word_limit" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-500">words</span>
                                </div>
                            </div>

                            <!-- Time Limit -->
                            <div id="time-limit-field" class="hidden">
                                <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">Time Limit</label>
                                <div class="flex items-center space-x-4">
                                    <input type="number" id="time_limit" name="time_limit" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-500">minutes</span>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div id="instructions-field" class="hidden">
                                <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">Special Instructions</label>
                                <textarea id="instructions" name="instructions" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter any special instructions for students..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Media & Options -->
                <div class="step-content bg-white rounded-xl shadow-lg p-8 mb-6 hidden" data-step="3">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Media & Answer Options</h3>
                        <p class="text-gray-600">Upload media files and set up answer options</p>
                    </div>

                    <div class="max-w-4xl mx-auto space-y-8">
                        <!-- Media Upload -->
                        <div>
                            <label class="block text-lg font-medium text-gray-900 mb-4">Media File</label>
                            <div id="media-upload-area" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                <div id="media-upload-content">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-2">Upload Media File</p>
                                    <p class="text-gray-500" id="media-help-text">Click to upload or drag and drop</p>
                                    <p class="text-sm text-gray-400 mt-2" id="media-formats">Supported formats will be shown here</p>
                                </div>
                                <input type="file" id="media" name="media" class="hidden" accept="">
                            </div>
                            <div id="media-preview" class="mt-4 hidden">
                                <!-- Media preview will be shown here -->
                            </div>
                        </div>

                        <!-- Answer Options -->
                        <div id="answer-options-section" class="hidden">
                            <label class="block text-lg font-medium text-gray-900 mb-4">Answer Options</label>
                            <div id="options-container">
                                <div id="options-list" class="space-y-4">
                                    <!-- Options will be populated by JavaScript -->
                                </div>
                                <button type="button" id="add-option-btn" class="mt-4 flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Another Option
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Review & Save -->
                <div class="step-content bg-white rounded-xl shadow-lg p-8 mb-6 hidden" data-step="4">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Review Your Question</h3>
                        <p class="text-gray-600">Please review all details before saving</p>
                    </div>

                    <div class="max-w-4xl mx-auto">
                        <div id="question-preview" class="bg-gray-50 rounded-xl p-6 mb-6">
                            <!-- Question preview will be populated by JavaScript -->
                        </div>

                        <div class="flex items-center justify-center space-x-4">
                            <button type="button" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors prev-btn">
                                ← Previous
                            </button>
                            <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                ✓ Create Question
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between">
                    <button type="button" id="prev-btn" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors hidden">
                        ← Previous
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" id="next-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Next →
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        class QuestionWizard {
            constructor() {
                this.currentStep = 1;
                this.maxSteps = 4;
                this.selectedSection = '';
                this.selectedQuestionType = '';
                
                this.questionTypes = {
                    listening: [
                        { id: 'multiple_choice', title: 'Multiple Choice', icon: '☑️', desc: 'Select the correct answer from options' },
                        { id: 'form_completion', title: 'Form Completion', icon: '📝', desc: 'Fill in missing information in a form' },
                        { id: 'note_completion', title: 'Note Completion', icon: '📋', desc: 'Complete notes with missing words' },
                        { id: 'sentence_completion', title: 'Sentence Completion', icon: '✏️', desc: 'Complete sentences with missing words' },
                        { id: 'short_answer', title: 'Short Answer', icon: '💬', desc: 'Provide brief answers to questions' },
                        { id: 'matching', title: 'Matching', icon: '🔗', desc: 'Match items from two lists' }
                    ],
                    reading: [
                        { id: 'passage', title: 'Reading Passage', icon: '📄', desc: 'Main text for reading comprehension' },
                        { id: 'multiple_choice', title: 'Multiple Choice', icon: '☑️', desc: 'Select the correct answer from options' },
                        { id: 'true_false', title: 'True/False/Not Given', icon: '✅', desc: 'Evaluate statement accuracy' },
                        { id: 'yes_no', title: 'Yes/No/Not Given', icon: '❓', desc: 'Agree/disagree with writer\'s views' },
                        { id: 'matching_headings', title: 'Matching Headings', icon: '🏷️', desc: 'Match paragraphs with headings' },
                        { id: 'sentence_completion', title: 'Sentence Completion', icon: '✏️', desc: 'Complete sentences with passage words' },
                        { id: 'short_answer', title: 'Short Answer', icon: '💬', desc: 'Answer questions with passage words' }
                    ],
                    writing: [
                        { id: 'task1_line_graph', title: 'Task 1: Line Graph', icon: '📈', desc: 'Describe trends in line graphs' },
                        { id: 'task1_bar_chart', title: 'Task 1: Bar Chart', icon: '📊', desc: 'Compare data in bar charts' },
                        { id: 'task1_pie_chart', title: 'Task 1: Pie Chart', icon: '🥧', desc: 'Analyze proportions in pie charts' },
                        { id: 'task1_table', title: 'Task 1: Table', icon: '📋', desc: 'Summarize data from tables' },
                        { id: 'task1_process', title: 'Task 1: Process', icon: '🔄', desc: 'Describe processes or cycles' },
                        { id: 'task2_opinion', title: 'Task 2: Opinion', icon: '💭', desc: 'Express and support opinions' },
                        { id: 'task2_discussion', title: 'Task 2: Discussion', icon: '🗣️', desc: 'Discuss different viewpoints' },
                        { id: 'task2_problem_solution', title: 'Task 2: Problem/Solution', icon: '🔧', desc: 'Identify problems and solutions' }
                    ],
                    speaking: [
                        { id: 'part1_personal', title: 'Part 1: Personal', icon: '👤', desc: 'Personal questions and topics' },
                        { id: 'part2_cue_card', title: 'Part 2: Cue Card', icon: '🎤', desc: 'Individual presentation topics' },
                        { id: 'part3_discussion', title: 'Part 3: Discussion', icon: '🗣️', desc: 'Abstract discussion questions' }
                    ]
                };

                this.templates = {
                    multiple_choice: "Listen to the audio and choose the correct answer.\n\nWhat is the main topic of the conversation?\nA) Travel plans\nB) Work schedule\nC) Weekend activities\nD) Restaurant booking",
                    passage: "Write a comprehensive reading passage (minimum 300 words) that will be used for comprehension questions. Include varied vocabulary and clear structure with introduction, body paragraphs, and conclusion.",
                    task1_line_graph: "The line graph below shows [describe what the graph shows].\n\nSummarize the information by selecting and reporting the main features, and make comparisons where relevant.\n\nWrite at least 150 words.",
                    task2_opinion: "Some people believe that [topic statement].\n\nTo what extent do you agree or disagree with this statement?\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\n\nWrite at least 250 words.",
                    part2_cue_card: "Describe a place you would like to visit in the future.\n\nYou should say:\n• Where this place is\n• Why you want to visit it\n• What you would do there\n• And explain how you think you would feel about visiting this place\n\nYou will have 1-2 minutes to talk about this topic."
                };

                this.init();
            }

            init() {
                this.bindEvents();
                this.updateStepDisplay();
            }

            bindEvents() {
                // Section cards
                document.querySelectorAll('.section-card').forEach(card => {
                    card.addEventListener('click', () => this.selectSection(card.dataset.section));
                });

                // Navigation buttons
                document.getElementById('next-btn').addEventListener('click', () => this.nextStep());
                document.getElementById('prev-btn').addEventListener('click', () => this.prevStep());
                
                // Test set selection
                document.getElementById('test_set_id').addEventListener('change', (e) => {
                    if (e.target.value) {
                        this.showQuestionTypes();
                    }
                });

                // Media upload
                document.getElementById('media-upload-area').addEventListener('click', () => {
                    document.getElementById('media').click();
                });

                document.getElementById('media').addEventListener('change', (e) => {
                    this.handleMediaUpload(e.target.files[0]);
                });

                // Add option button
                document.getElementById('add-option-btn').addEventListener('click', () => {
                    this.addOption();
                });
            }

            selectSection(section) {
                this.selectedSection = section;
                document.getElementById('selected_section').value = section;
                
                // Update UI
                document.querySelectorAll('.section-card').forEach(card => {
                    card.classList.remove('border-blue-500', 'bg-blue-50');
                    card.classList.add('border-gray-200');
                });
                
                document.querySelector(`[data-section="${section}"]`).classList.remove('border-gray-200');
                document.querySelector(`[data-section="${section}"]`).classList.add('border-blue-500', 'bg-blue-50');
                
                // Show test set selection
                this.showTestSets(section);
                document.getElementById('test-set-selection').classList.remove('hidden');
                
                // Enable next button
                this.updateNextButton();
            }

            showTestSets(section) {
                const select = document.getElementById('test_set_id');
                const options = select.querySelectorAll('option[data-section]');
                
                options.forEach(option => {
                    option.style.display = option.dataset.section === section ? 'block' : 'none';
                });
                
                select.value = '';
            }

            showQuestionTypes() {
                const container = document.getElementById('question-types-grid');
                const types = this.questionTypes[this.selectedSection] || [];
                
                container.innerHTML = '';
                
                types.forEach(type => {
                    const card = document.createElement('div');
                    card.className = 'question-type-card cursor-pointer border-2 border-gray-200 rounded-lg p-4 text-center transition-all hover:border-blue-500 hover:shadow-md';
                    card.dataset.type = type.id;
                    
                    card.innerHTML = `
                        <div class="text-2xl mb-2">${type.icon}</div>
                        <h5 class="font-medium text-gray-900 mb-1">${type.title}</h5>
                        <p class="text-xs text-gray-600">${type.desc}</p>
                    `;
                    
                    card.addEventListener('click', () => this.selectQuestionType(type.id, card));
                    container.appendChild(card);
                });
                
                document.getElementById('question-type-selection').classList.remove('hidden');
            }

            selectQuestionType(type, card) {
                this.selectedQuestionType = type;
                document.getElementById('selected_question_type').value = type;
                
                // Update UI
                document.querySelectorAll('.question-type-card').forEach(c => {
                    c.classList.remove('border-blue-500', 'bg-blue-50');
                    c.classList.add('border-gray-200');
                });
                
                card.classList.remove('border-gray-200');
                card.classList.add('border-blue-500', 'bg-blue-50');
                
                this.updateNextButton();
            }

            nextStep() {
                if (this.currentStep < this.maxSteps) {
                    if (this.validateStep()) {
                        this.currentStep++;
                        this.updateStepDisplay();
                        this.setupStepContent();
                    }
                }
            }

            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                    this.updateStepDisplay();
                }
            }

            validateStep() {
                switch (this.currentStep) {
                    case 1:
                        return this.selectedSection && this.selectedQuestionType && document.getElementById('test_set_id').value;
                    case 2:
                        return document.getElementById('content').value && document.getElementById('order_number').value;
                    case 3:
                        return true; // Media and options are optional
                    default:
                        return true;
                }
            }

            setupStepContent() {
                switch (this.currentStep) {
                    case 2:
                        this.setupQuestionDetails();
                        break;
                    case 3:
                        this.setupMediaAndOptions();
                        break;
                    case 4:
                        this.setupReview();
                        break;
                }
            }

            setupQuestionDetails() {
                // Show template if available
                const template = this.templates[this.selectedQuestionType];
                if (template) {
                    const templateDiv = document.getElementById('content-template');
                    templateDiv.querySelector('p').textContent = template;
                    templateDiv.classList.remove('hidden');
                    
                    // Auto-fill if content is empty
                    if (!document.getElementById('content').value) {
                        document.getElementById('content').value = template;
                    }
                }

                // Show relevant fields
                this.showRelevantFields();
            }

            showRelevantFields() {
                const section = this.selectedSection;
                const type = this.selectedQuestionType;

                // Hide all dynamic fields first
                document.querySelectorAll('#dynamic-fields > div').forEach(field => {
                    field.classList.add('hidden');
                });

                // Show word limit for writing
                if (section === 'writing') {
                    document.getElementById('word-limit-field').classList.remove('hidden');
                    const wordLimit = type.startsWith('task1_') ? 150 : 250;
                    document.getElementById('word_limit').value = wordLimit;
                }

                // Show time limit for writing and speaking
                if (section === 'writing' || section === 'speaking') {
                    document.getElementById('time-limit-field').classList.remove('hidden');
                    let timeLimit = 20;
                    if (section === 'writing') {
                        timeLimit = type.startsWith('task1_') ? 20 : 40;
                    } else if (section === 'speaking') {
                        timeLimit = type === 'part2_cue_card' ? 2 : 5;
                    }
                    document.getElementById('time_limit').value = timeLimit;
                }

                // Show instructions for specific types
                if (['part2_cue_card', 'form_completion', 'note_completion'].includes(type)) {
                    document.getElementById('instructions-field').classList.remove('hidden');
                }
            }

            setupMediaAndOptions() {
                this.setupMediaUpload();
                this.setupAnswerOptions();
            }

            setupMediaUpload() {
                const section = this.selectedSection;
                const type = this.selectedQuestionType;
                
                let helpText = 'Optional: Upload supporting media';
                let formats = 'Images: JPG, PNG, GIF | Audio: MP3, WAV, OGG';
                let accept = '.jpg,.jpeg,.png,.gif,.mp3,.wav,.ogg';
                let required = false;

                if (section === 'listening') {
                    helpText = 'Required: Upload audio file for listening question';
                    formats = 'Audio files: MP3, WAV, OGG (max 50MB)';
                    accept = '.mp3,.wav,.ogg';
                    required = true;
                } else if (section === 'writing' && type.startsWith('task1_')) {
                    helpText = 'Required: Upload chart/graph/diagram image';
                    formats = 'Images: JPG, PNG, GIF (max 5MB)';
                    accept = '.jpg,.jpeg,.png,.gif';
                    required = true;
                }

                document.getElementById('media-help-text').textContent = helpText;
                document.getElementById('media-formats').textContent = formats;
                document.getElementById('media').setAttribute('accept', accept);

                if (required) {
                    document.getElementById('media-upload-area').classList.add('border-blue-400', 'bg-blue-50');
                }
            }

            setupAnswerOptions() {
                const type = this.selectedQuestionType;
                const requiresOptions = [
                    'multiple_choice', 'true_false', 'yes_no', 'matching',
                    'matching_headings', 'matching_information'
                ];

                if (requiresOptions.includes(type)) {
                    document.getElementById('answer-options-section').classList.remove('hidden');
                    this.createDefaultOptions(type);
                } else {
                    document.getElementById('answer-options-section').classList.add('hidden');
                }
            }

            createDefaultOptions(type) {
                const container = document.getElementById('options-list');
                container.innerHTML = '';

                let options = [];
                if (type === 'true_false') {
                    options = ['True', 'False', 'Not Given'];
                } else if (type === 'yes_no') {
                    options = ['Yes', 'No', 'Not Given'];
                } else if (type === 'multiple_choice') {
                    options = ['Option A', 'Option B', 'Option C', 'Option D'];
                }

                options.forEach((text, index) => {
                    this.addOption(text, index === 0);
                });

                // Hide add button for fixed option types
                const addBtn = document.getElementById('add-option-btn');
                if (['true_false', 'yes_no'].includes(type)) {
                    addBtn.style.display = 'none';
                } else {
                    addBtn.style.display = 'flex';
                }
            }

            addOption(text = '', isFirst = false) {
                const container = document.getElementById('options-list');
                const index = container.children.length;
                
                const optionDiv = document.createElement('div');
                optionDiv.className = 'option-item bg-gray-50 p-4 rounded-lg border border-gray-200';
                
                optionDiv.innerHTML = `
                    <div class="flex items-start space-x-4">
                        <div class="flex items-center">
                            <input type="radio" name="correct_option" value="${index}" class="w-4 h-4 text-blue-600" ${isFirst ? 'checked' : ''}>
                            <label class="ml-2 text-sm font-medium text-gray-700">Correct</label>
                        </div>
                        <div class="flex-1">
                            <input type="text" name="options[${index}][content]" value="${text}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter option text...">
                        </div>
                        ${!text ? '<button type="button" class="text-red-500 hover:text-red-700 remove-option">Remove</button>' : ''}
                    </div>
                `;
                
                container.appendChild(optionDiv);

                // Add remove functionality
                const removeBtn = optionDiv.querySelector('.remove-option');
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        optionDiv.remove();
                        this.updateOptionIndices();
                    });
                }
            }

            updateOptionIndices() {
                const options = document.querySelectorAll('.option-item');
                options.forEach((option, index) => {
                    const radio = option.querySelector('input[type="radio"]');
                    const input = option.querySelector('input[type="text"]');
                    
                    radio.value = index;
                    input.name = `options[${index}][content]`;
                });
            }

            setupReview() {
                const preview = document.getElementById('question-preview');
                const section = this.selectedSection;
                const type = this.selectedQuestionType;
                const content = document.getElementById('content').value;
                const orderNumber = document.getElementById('order_number').value;
                
                preview.innerHTML = `
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            ${section.charAt(0).toUpperCase() + section.slice(1)} - Question ${orderNumber}
                        </span>
                    </div>
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-900 mb-2">Question Type:</h4>
                        <p class="text-gray-700">${this.getQuestionTypeTitle(type)}</p>
                    </div>
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-900 mb-2">Content:</h4>
                        <div class="bg-white p-4 rounded border text-gray-700 whitespace-pre-line">${content}</div>
                    </div>
                    ${this.getAdditionalReviewFields()}
                `;
            }

            getQuestionTypeTitle(type) {
                for (const section in this.questionTypes) {
                    const found = this.questionTypes[section].find(t => t.id === type);
                    if (found) return found.title;
                }
                return type;
            }

            getAdditionalReviewFields() {
                let html = '';
                
                const wordLimit = document.getElementById('word_limit')?.value;
                if (wordLimit) {
                    html += `
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Word Limit:</h4>
                            <p class="text-gray-700">${wordLimit} words</p>
                        </div>
                    `;
                }

                const timeLimit = document.getElementById('time_limit')?.value;
                if (timeLimit) {
                    html += `
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Time Limit:</h4>
                            <p class="text-gray-700">${timeLimit} minutes</p>
                        </div>
                    `;
                }

                const options = document.querySelectorAll('.option-item input[type="text"]');
                if (options.length > 0) {
                    html += `
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Answer Options:</h4>
                            <ul class="space-y-2">
                    `;
                    options.forEach((option, index) => {
                        const isCorrect = document.querySelector(`input[name="correct_option"]:checked`)?.value == index;
                        html += `
                            <li class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-medium mr-3 ${isCorrect ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'}">
                                    ${String.fromCharCode(65 + index)}
                                </span>
                                <span class="text-gray-700">${option.value}</span>
                                ${isCorrect ? '<span class="ml-2 text-green-600 font-medium">✓ Correct</span>' : ''}
                            </li>
                        `;
                    });
                    html += '</ul></div>';
                }

                return html;
            }

            handleMediaUpload(file) {
                if (!file) return;

                const preview = document.getElementById('media-preview');
                const uploadContent = document.getElementById('media-upload-content');
                
                preview.innerHTML = '';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'max-h-48 rounded-lg';
                    preview.appendChild(img);
                } else if (file.type.startsWith('audio/')) {
                    const audio = document.createElement('audio');
                    audio.src = URL.createObjectURL(file);
                    audio.controls = true;
                    audio.className = 'w-full';
                    preview.appendChild(audio);
                }
                
                const fileInfo = document.createElement('p');
                fileInfo.className = 'text-sm text-gray-600 mt-2';
                fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                preview.appendChild(fileInfo);
                
                preview.classList.remove('hidden');
                uploadContent.innerHTML = `
                    <svg class="mx-auto h-8 w-8 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-600 font-medium">File uploaded successfully</p>
                    <p class="text-sm text-gray-500">Click to change file</p>
                `;
            }

            updateStepDisplay() {
                // Update progress indicators
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNum = index + 1;
                    if (stepNum < this.currentStep) {
                        indicator.className = 'flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white font-medium step-indicator';
                        indicator.innerHTML = '✓';
                    } else if (stepNum === this.currentStep) {
                        indicator.className = 'flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-medium step-indicator';
                        indicator.textContent = stepNum;
                    } else {
                        indicator.className = 'flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600 font-medium step-indicator';
                        indicator.textContent = stepNum;
                    }
                });

                // Update step labels
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNum = index + 1;
                    const label = indicator.nextElementSibling;
                    if (stepNum <= this.currentStep) {
                        label.className = 'ml-3 text-sm font-medium text-gray-900';
                    } else {
                        label.className = 'ml-3 text-sm font-medium text-gray-500';
                    }
                });

                // Show/hide step content
                document.querySelectorAll('.step-content').forEach((content, index) => {
                    if (index + 1 === this.currentStep) {
                        content.classList.remove('hidden');
                    } else {
                        content.classList.add('hidden');
                    }
                });

                // Update navigation buttons
                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');

                if (this.currentStep === 1) {
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                }

                if (this.currentStep === this.maxSteps) {
                    nextBtn.classList.add('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                }

                this.updateNextButton();
            }

            updateNextButton() {
                const nextBtn = document.getElementById('next-btn');
                const isValid = this.validateStep();
                
                if (isValid) {
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    nextBtn.disabled = true;
                    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // Initialize wizard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            new QuestionWizard();
        });
    </script>
    @endpush
</x-layout>