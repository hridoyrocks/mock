<x-layout>
    <x-slot:title>Add Reading Question - {{ $testSet->title }}</x-slot:title>
    
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">➕ Add Reading Question</h1>
                        <p class="text-blue-100 text-sm mt-1">{{ $testSet->title }} - Reading Section</p>
                    </div>
                    <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Test Set
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-green-600">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-sm font-medium">Passage Added</span>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="h-1 bg-blue-600 rounded"></div>
                    </div>
                    <div class="flex items-center text-blue-600">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-medium">2</div>
                        <span class="ml-2 text-sm font-medium">Add Questions</span>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Passage Preview -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-4">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-sm font-medium text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $passage->instructions ?? 'Reading Passage' }}
                            </h3>
                        </div>
                        <div class="p-4 max-h-96 overflow-y-auto">
                            <div id="passage-content" class="prose prose-sm max-w-none text-sm leading-relaxed">
                                {!! $processedPassage !!}
                            </div>
                        </div>
                        
                        <!-- Available Markers -->
                        @if(count($availableMarkers) > 0)
                        <div class="px-4 py-3 border-t border-gray-200 bg-blue-50">
                            <h4 class="text-xs font-medium text-blue-900 mb-2">📍 Available Answer Locations:</h4>
                            <div class="flex flex-wrap gap-1">
                                @foreach($availableMarkers as $marker)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                    {{ $marker }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Question Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('admin.questions.store') }}" method="POST" id="questionForm">
                        @csrf
                        <input type="hidden" name="test_set_id" value="{{ $testSet->id }}">
                        <input type="hidden" name="part_number" value="{{ $passage->part_number }}">
                        
                        <!-- Question Details Card -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Question Details
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <!-- Question Settings Row -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question Type <span class="text-red-500">*</span>
                                        </label>
                                        <select id="question_type" name="question_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">Select type...</option>
                                            <option value="multiple_choice">Multiple Choice</option>
                                            <option value="true_false">True/False/Not Given</option>
                                            <option value="yes_no">Yes/No/Not Given</option>
                                            <option value="matching_headings">Matching Headings</option>
                                            <option value="matching_information">Matching Information</option>
                                            <option value="sentence_completion">Sentence Completion</option>
                                            <option value="summary_completion">Summary Completion</option>
                                            <option value="short_answer">Short Answer</option>
                                            <option value="fill_blanks">Fill in the Blanks</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="order_number" value="{{ old('order_number', $nextQuestionNumber) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Marks
                                        </label>
                                        <input type="number" name="marks" value="{{ old('marks', 1) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" max="10">
                                    </div>
                                </div>

                                <!-- Answer Location -->
                                @if(count($availableMarkers) > 0)
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        🔗 Answer Location in Passage
                                        <span class="text-gray-500 text-xs">(Optional - Links to marked text)</span>
                                    </label>
                                    <select id="marker_id" name="marker_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- No specific location --</option>
                                        @foreach($availableMarkers as $marker)
                                        <option value="{{ $marker }}" data-text="{{ $markerTexts[$marker] ?? '' }}">
                                            {{ $marker }} - "{{ Str::limit($markerTexts[$marker] ?? '', 50) }}"
                                        </option>
                                        @endforeach
                                    </select>
                                    <div id="marker-preview" class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md hidden">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Selected location:</strong> <span id="marker-text"></span>
                                        </p>
                                    </div>
                                </div>
                                @endif

                                <!-- Instructions -->
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Instructions for Students
                                        </label>
                                        <button type="button" onclick="showInstructionTemplates()" class="text-sm text-blue-600 hover:text-blue-700">
                                            Templates ▼
                                        </button>
                                    </div>
                                    <textarea id="instructions" name="instructions" rows="2" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="e.g., Choose the correct letter, A, B, C or D.">{{ old('instructions') }}</textarea>
                                </div>

                                <!-- Question Content -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Question Content <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mb-3 flex space-x-2">
                                        <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                            Insert Blank ____
                                        </button>
                                        <button type="button" onclick="insertDropdown()" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">
                                            Insert Dropdown ▼
                                        </button>
                                    </div>
                                    <textarea id="content" name="content" class="tinymce" required>{{ old('content') }}</textarea>
                                </div>

                                <!-- Fill in the Blanks Manager -->
                                <div id="blanks-manager" class="hidden mb-6">
                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                        <h4 class="text-sm font-medium text-blue-900 mb-3">📝 Fill in the Blanks Configuration</h4>
                                        <div id="blanks-list" class="space-y-2">
                                            <!-- Blanks will be dynamically added here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Answer Options Card -->
                        <div id="options-card" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-medium text-gray-900">Answer Options</h3>
                                    <button type="button" onclick="showBulkOptions()" class="text-sm text-blue-600 hover:text-blue-700">
                                        Add Bulk Options
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div id="options-container" class="space-y-3">
                                    <!-- Options will be dynamically added here -->
                                </div>
                                
                                <button type="button" id="add-option-btn" 
                                        class="mt-4 w-full px-4 py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-md hover:border-gray-400 hover:text-gray-600 transition-all">
                                    + Add Option
                                </button>
                            </div>
                        </div>

                        <!-- Explanation & Learning Card -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                            <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    Explanation & Learning Resources
                                </h3>
                            </div>
                            
                            <div class="p-6 space-y-6">
                                <!-- Difficulty Level -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Difficulty Level
                                    </label>
                                    <select name="difficulty_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select difficulty...</option>
                                        <option value="easy">🟢 Easy</option>
                                        <option value="medium">🟡 Medium</option>
                                        <option value="hard">🔴 Hard</option>
                                    </select>
                                </div>
                                
                                <!-- Main Explanation -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Explanation <span class="text-gray-500">(Why is this the correct answer?)</span>
                                        </label>
                                        <button type="button" onclick="showExplanationTemplates()" class="text-sm text-purple-600 hover:text-purple-700">
                                            Templates ▼
                                        </button>
                                    </div>
                                    <div class="mb-2 flex flex-wrap gap-2">
                                        @if(count($availableMarkers) > 0)
                                            @foreach($availableMarkers as $marker)
                                            <button type="button" onclick="insertMarkerReference('{{ $marker }}')" 
                                                    class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded hover:bg-yellow-200 transition-colors">
                                                Insert {{ $marker }}
                                            </button>
                                            @endforeach
                                        @endif
                                        <button type="button" onclick="insertCorrectAnswer()" class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded hover:bg-green-200">
                                            ✓ Correct
                                        </button>
                                        <button type="button" onclick="insertIncorrectAnswer()" class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded hover:bg-red-200">
                                            ✗ Incorrect
                                        </button>
                                    </div>
                                    <textarea id="explanation" name="explanation" class="tinymce-explanation">{{ old('explanation') }}</textarea>
                                </div>
                                
                                <!-- Passage Reference -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        📍 Passage Reference <span class="text-gray-500">(Where to find the answer)</span>
                                    </label>
                                    <input type="text" name="passage_reference" 
                                           value="{{ old('passage_reference') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                           placeholder="e.g., Paragraph 2, Lines 15-20 OR Third paragraph, second sentence">
                                </div>
                                
                                <!-- Tips & Common Mistakes -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            💡 Tips & Strategies
                                        </label>
                                        <textarea name="tips" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="e.g., For True/False/Not Given questions, look for exact matches...">{{ old('tips') }}</textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            ⚠️ Common Mistakes
                                        </label>
                                        <textarea name="common_mistakes" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="e.g., Students often choose option A because it contains keywords...">{{ old('common_mistakes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="submit" name="action" value="save" class="flex-1 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Save Question
                                    </button>
                                    <button type="submit" name="action" value="save_and_new" class="flex-1 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Save & Add Another
                                    </button>
                                    <button type="button" onclick="previewQuestion()" class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals and Scripts go here -->
    <p class="text-center text-gray-500 text-sm mt-8">Reading Question Creation Form</p>

    @push('scripts')
    <script>
        console.log('Reading question form loaded');
    </script>
    @endpush
</x-layout>