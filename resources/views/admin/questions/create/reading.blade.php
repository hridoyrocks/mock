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
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Instructions</label>
                                            <button type="button" onclick="showTemplates()" class="text-sm text-blue-600 hover:text-blue-700">
                                                Use Template ▼
                                            </button>
                                        </div>
                                        <textarea id="instructions" name="instructions" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="e.g., Questions 1-5: Choose the correct letter, A, B, C or D.">{{ old('instructions') }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div id="question-content-field">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mb-3 flex space-x-2">
                                            <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                                Insert Blank
                                            </button>
                                            <button type="button" onclick="insertDropdown()" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">
                                                Insert Dropdown
                                            </button>
                                        </div>
                                        <textarea id="content" name="content" class="tinymce">{{ old('content') }}</textarea>
                                    </div>
                                    
                                    <!-- Passage Content (Hidden by default) -->
                                    <div id="passage-content-field" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Passage Content <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mb-3">
                                            <div class="bg-blue-50 border border-blue-200 rounded p-3 text-sm">
                                                <p class="text-blue-800">💡 Tip: Use markers like to mark answer locations in the passage.</p>
                                            </div>
                                        </div>
                                        <textarea id="passage-text" name="passage_text" class="tinymce-passage">{{ old('passage_text') }}</textarea>
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
                                            'fill_blanks' => 'Fill in the Blanks',
                                            'flow_chart' => 'Flow Chart Completion',
                                            'table_completion' => 'Table Completion'
                                        ]
                                    ])
                                    
                                    <!-- Marker Selection (for questions) -->
                                    <div id="marker-select-field" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Answer Location in Passage
                                        </label>
                                        <select name="marker_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                            <option value="">-- No specific location --</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Link to marked location in passage</p>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Options Manager -->
                    @include('admin.questions.partials.options-manager')
                    
                    <!-- Explanation & Tips -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Explanation & Learning</h3>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Explanation
                                </label>
                                <textarea name="explanation" rows="4" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                          placeholder="Explain why this is the correct answer...">{{ old('explanation') }}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Passage Reference
                                    </label>
                                    <input type="text" name="passage_reference" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                           placeholder="e.g., Paragraph 2, Lines 10-15">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Difficulty Level
                                    </label>
                                    <select name="difficulty_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tips & Strategies
                                    </label>
                                    <textarea name="tips" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                              placeholder="Tips for solving this type of question...">{{ old('tips') }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Common Mistakes
                                    </label>
                                    <textarea name="common_mistakes" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                              placeholder="Common mistakes students make...">{{ old('common_mistakes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @include('admin.questions.partials.action-buttons')
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-reading.js') }}"></script>
    @endpush
</x-layout>