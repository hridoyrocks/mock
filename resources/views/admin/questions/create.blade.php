<x-layout>
    <x-slot:title>Add Question - {{ ucfirst($testSet->section->name) }}</x-slot>
    
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">Add Question to {{ $testSet->title }}</h1>
                        <p class="text-blue-100 text-sm mt-1">{{ ucfirst($testSet->section->name) }} Section</p>
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
            
            <!-- Question Status Card -->
            <div class="bg-white rounded-lg shadow-sm mb-6 border-l-4 border-blue-500">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Creating Question</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Total questions in this test: <span class="font-semibold">{{ $existingQuestions->count() }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-blue-600" id="question-number-display">
                                #{{ $nextQuestionNumber }}
                            </div>
                            <p class="text-xs text-gray-500">Question Number</p>
                        </div>
                    </div>
                    
                    <!-- Quick question list -->
                    @if($existingQuestions->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-2">Existing questions:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($existingQuestions->sortBy('order_number') as $q)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                #{{ $q->order_number }}
                            </span>
                            @endforeach
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700 border border-blue-300">
                                #{{ $nextQuestionNumber }} (new)
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm" novalidate>
                @csrf
                <input type="hidden" name="test_set_id" value="{{ $testSet->id }}">
                
                <!-- Single Column Layout -->
                <div class="space-y-6">
                    
                    <!-- Question Content Section -->
                    <div class="bg-white rounded-lg shadow-sm" id="main-content-section">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Question Content</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
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
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Enter instructions for this question...">{{ old('instructions') }}</textarea>
                                    </div>
                                    
                                    <!-- Question Editor -->
                                    <div>
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
                                        
                                        <!-- Word Count -->
                                        <div class="flex justify-between items-center text-xs text-gray-500 mt-2">
                                            <span>Words: <span id="word-count">0</span></span>
                                            <span>Characters: <span id="char-count">0</span></span>
                                        </div>
                                        
                                        <!-- Blanks Manager -->
                                        <div id="blanks-manager" class="hidden mt-4">
                                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                                <h4 class="text-sm font-medium text-blue-900 mb-3">Fill in the Blanks Configuration</h4>
                                                <div id="blanks-list" class="space-y-2">
                                                    <!-- Blanks will be dynamically added here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Settings Grid -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Part Selection -->
                                        @if(in_array($testSet->section->name, ['listening', 'reading', 'speaking', 'writing']))
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $testSet->section->name === 'writing' ? 'Task' : 'Part' }} <span class="text-red-500">*</span>
                                            </label>
                                            <select name="part_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                @if($testSet->section->name === 'listening')
                                                    <option value="1">Part 1 (Social)</option>
                                                    <option value="2">Part 2 (Monologue)</option>
                                                    <option value="3">Part 3 (Discussion)</option>
                                                    <option value="4">Part 4 (Lecture)</option>
                                                @elseif($testSet->section->name === 'reading')
                                                    <option value="1">Passage 1</option>
                                                    <option value="2">Passage 2</option>
                                                    <option value="3">Passage 3</option>
                                                @elseif($testSet->section->name === 'speaking')
                                                    <option value="1">Part 1</option>
                                                    <option value="2">Part 2</option>
                                                    <option value="3">Part 3</option>
                                                @elseif($testSet->section->name === 'writing')
                                                    <option value="1">Task 1</option>
                                                    <option value="2">Task 2</option>
                                                @endif
                                            </select>
                                        </div>
                                        @endif
                                        
                                        <!-- Question Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                                            <select id="question_type" name="question_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                <option value="">Select type...</option>
                                                @php
                                                    $types = [
                                                        'listening' => [
                                                            'multiple_choice' => 'Multiple Choice',
                                                            'form_completion' => 'Form Completion',
                                                            'note_completion' => 'Note Completion',
                                                            'sentence_completion' => 'Sentence Completion',
                                                            'short_answer' => 'Short Answer',
                                                            'matching' => 'Matching'
                                                        ],
                                                        'reading' => [
                                                            'passage' => '📄 Reading Passage',
                                                            'multiple_choice' => 'Multiple Choice',
                                                            'true_false' => 'True/False/Not Given',
                                                            'yes_no' => 'Yes/No/Not Given',
                                                            'matching_headings' => 'Matching Headings',
                                                            'sentence_completion' => 'Sentence Completion',
                                                            'summary_completion' => 'Summary Completion',
                                                            'fill_blanks' => 'Fill in the Blanks',
                                                            'short_answer' => 'Short Answer'
                                                        ],
                                                        'writing' => [
                                                            'task1_graph' => 'Task 1: Graph/Chart',
                                                            'task1_process' => 'Task 1: Process',
                                                            'task1_map' => 'Task 1: Map',
                                                            'task2_opinion' => 'Task 2: Opinion',
                                                            'task2_discussion' => 'Task 2: Discussion'
                                                        ],
                                                        'speaking' => [
                                                            'part1_personal' => 'Part 1: Personal',
                                                            'part2_cue_card' => 'Part 2: Cue Card',
                                                            'part3_discussion' => 'Part 3: Discussion'
                                                        ]
                                                    ];
                                                @endphp
                                                @foreach($types[$testSet->section->name] ?? [] as $key => $type)
                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- Question Number -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Number <span class="text-red-500">*</span></label>
                                            <input type="number" name="order_number" value="{{ old('order_number', $nextQuestionNumber) }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="1" required>
                                        </div>
                                        
                                        <!-- Marks -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Marks</label>
                                            <input type="number" id="marks-input" name="marks" value="1" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                   min="0" max="40">
                                        </div>
                                        
                                        <!-- Question Group -->
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                                            <input type="text" name="question_group" placeholder="e.g., Questions 1-5"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        
                                        @if($testSet->section->name === 'writing')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Word Limit</label>
                                            <input type="number" name="word_limit" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Time (min)</label>
                                            <input type="number" name="time_limit" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reading Passage Special Section -->
                    <div id="passage-section" class="bg-white rounded-lg shadow-sm hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Reading Passage Content</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Passage Title / Heading
                                </label>
                                <input type="text" name="passage_title" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                       placeholder="e.g., The History of Navigation">
                            </div>
                            
                            <!-- Quick Reference Guide -->
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-blue-900 mb-2">How to Mark Answer Locations:</h4>
                                        <ul class="text-sm text-blue-800 space-y-1">
                                            <li>• Use <code class="bg-white px-2 py-0.5 rounded text-xs">[Q1]</code> where Question 1's answer is located</li>
                                            <li>• Use <code class="bg-white px-2 py-0.5 rounded text-xs">[Q2]</code> where Question 2's answer is located</li>
                                            <li>• Example: The scientist discovered <code class="bg-white px-2 py-0.5 rounded text-xs">[Q1]</code> a new species in 2023.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- TinyMCE Editor for Passage -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Passage Text <span class="text-red-500">*</span>
                                </label>
                                <textarea name="passage_text" id="passage-editor" class="tinymce-passage">{{ old('passage_text') }}</textarea>
                                
                                <div class="flex justify-between items-center mt-3">
                                    <div class="flex space-x-4">
                                        <button type="button" onclick="insertAnswerMarker()" 
                                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Insert Answer Marker
                                        </button>
                                        <button type="button" onclick="previewMarkers()" 
                                                class="inline-flex items-center text-sm text-green-600 hover:text-green-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Preview Markers
                                        </button>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        <span id="passage-word-count">0</span> words
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Answer Options Section -->
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
                    
                   {{-- Explanation Section - Replace the existing complex one --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">
            Question Explanation
        </h3>
    </div>
    
    <div class="p-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Explanation <span class="text-gray-500">(Optional)</span>
            </label>
            <textarea name="explanation" 
                      id="explanation-editor"
                      rows="6" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                      placeholder="Explain why this is the correct answer...">{{ old('explanation') }}</textarea>
        </div>
    </div>
</div>
                    
                    <!-- Media Files Section -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Media Files</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="border-2 border-dashed border-gray-300 rounded-md p-8 text-center hover:border-gray-400 transition-colors cursor-pointer"
                                 id="drop-zone" onclick="document.getElementById('media').click()">
                                <input type="file" id="media" name="media" class="hidden" 
                                       accept="{{ $testSet->section->name === 'listening' ? '.mp3,.wav,.ogg' : 'image/*,.mp3,.wav,.ogg' }}">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-blue-600 hover:text-blue-500">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($testSet->section->name === 'listening')
                                        Audio files only: MP3, WAV, OGG (max 50MB)
                                    @else
                                        Images: PNG, JPG, GIF (max 10MB) or Audio files
                                    @endif
                                </p>
                            </div>
                            <div id="media-preview" class="mt-4 hidden">
                                <!-- Preview will be shown here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions Section -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit" name="action" value="save" class="flex-1 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors save-btn">
                                    Save Question
                                </button>
                                <button type="submit" name="action" value="save_and_new" class="flex-1 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors save-btn">
                                    Save & Add Another
                                </button>
                                <button type="button" onclick="previewQuestion()" class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors">
                                    Preview
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Template Modal -->
    <div id="template-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Instruction Templates</h3>
            <div class="space-y-2">
                <button onclick="useTemplate('Choose the correct letter, A, B, C or D.')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">Multiple Choice</button>
                <button onclick="useTemplate('Write TRUE if the statement agrees with the information, FALSE if it contradicts, or NOT GIVEN.')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">True/False/NG</button>
                <button onclick="useTemplate('Complete the sentences below. Write NO MORE THAN TWO WORDS from the passage.')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">Sentence Completion</button>
                <button onclick="useTemplate('Write NO MORE THAN THREE WORDS AND/OR A NUMBER for each answer.')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">Short Answer</button>
            </div>
            <button onclick="closeTemplates()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Close</button>
        </div>
    </div>
    
    <!-- Preview Modal -->
    <div id="preview-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Question Preview</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="preview-content" class="border rounded-lg p-6 bg-gray-50">
                <!-- Preview content will be inserted here -->
            </div>
        </div>
    </div>
    
    <!-- Bulk Options Modal -->
    <div id="bulk-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Bulk Options</h3>
            <p class="text-sm text-gray-600 mb-2">Enter each option on a new line:</p>
            <textarea id="bulk-text" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
            <div class="flex justify-end space-x-3 mt-4">
                <button onclick="closeBulkOptions()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Cancel</button>
                <button onclick="addBulkOptions()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Options</button>
            </div>
        </div>
    </div>

    <!-- Tooltip div for editor feedback -->
    <div id="editor-tooltip" style="position: fixed; bottom: 20px; right: 20px; background: #1f2937; color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; z-index: 1000; opacity: 0; transition: opacity 0.3s; display: none;"></div>

    @push('styles')
    <style>
        /* Clean styling */
        .tox .tox-editor-header {
            border-bottom: 1px solid #e5e7eb !important;
        }
        
        .tox.tox-tinymce {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
        }
        
        #drop-zone.drag-over {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        /* Enhanced Blank styling in TinyMCE */
        .blank-placeholder {
            background-color: #fef3c7;
            border-bottom: 2px solid #f59e0b;
            padding: 0 8px;
            margin: 0 4px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
            font-family: monospace;
            font-size: 13px;
        }
        
        .dropdown-placeholder {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 2px 8px;
            margin: 0 2px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-block;
        }
        
        /* Duplicate warning style */
        .duplicate-warning {
            color: #d97706;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        /* Answer marker styling */
        .answer-marker {
            background-color: #fef3c7;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 14px;
            color: #92400e;
            font-family: monospace;
        }
    </style>
    @endpush

    @push('scripts')
    {{-- TinyMCE CDN --}}
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    {{-- Pass PHP data to JavaScript safely --}}
    <script>
        // Initialize global variables
        window.existingQuestions = [];
        
        @if(isset($existingQuestions) && $existingQuestions->count() > 0)
            window.existingQuestions = {!! json_encode($existingQuestions->pluck('order_number')->toArray()) !!};
        @endif
    </script>

    {{-- Main JavaScript File --}}
    <script src="{{ asset('js/admin/question-create.js') }}"></script>
    @endpush
</x-layout>