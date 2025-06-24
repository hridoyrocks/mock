<x-layout>
    <x-slot:title>Add Question - Writing</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold">Add Writing Question</h1>
                        <p class="text-indigo-100 text-sm mt-1">{{ $testSet->title }}</p>
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
                    <!-- Task Details -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Task Details</h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
                                <div class="space-y-4 sm:space-y-6">
                                    <!-- Question Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question Type <span class="text-red-500">*</span>
                                        </label>
                                        <select id="question_type" name="question_type" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 text-sm" required>
                                            <option value="">Select type...</option>
                                            <optgroup label="Task 1">
                                                <option value="task1_line_graph">Line Graph</option>
                                                <option value="task1_bar_chart">Bar Chart</option>
                                                <option value="task1_pie_chart">Pie Chart</option>
                                                <option value="task1_table">Table</option>
                                                <option value="task1_process">Process Diagram</option>
                                                <option value="task1_map">Map</option>
                                            </optgroup>
                                            <optgroup label="Task 2">
                                                <option value="task2_opinion">Opinion Essay</option>
                                                <option value="task2_discussion">Discussion Essay</option>
                                                <option value="task2_problem_solution">Problem/Solution</option>
                                                <option value="task2_advantage_disadvantage">Advantages/Disadvantages</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    
                                    <!-- Word Limit -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Word Limit <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="word_limit" value="{{ old('word_limit', 150) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 text-sm" 
                                               min="50" max="500" required>
                                        <p class="text-xs text-gray-500 mt-1">Task 1: 150 words, Task 2: 250 words</p>
                                    </div>
                                    
                                    <!-- Time Limit -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Time Limit (minutes) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="time_limit" value="{{ old('time_limit', 20) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 text-sm" 
                                               min="1" max="60" required>
                                        <p class="text-xs text-gray-500 mt-1">Task 1: 20 minutes, Task 2: 40 minutes</p>
                                    </div>
                                    
                                    <!-- Question Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Question Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="order_number" value="{{ old('order_number', $nextQuestionNumber ?? 1) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 text-sm" 
                                               min="1" required>
                                    </div>
                                    
                                    <!-- Part Number (Hidden) -->
                                    <input type="hidden" name="part_number" id="part_number" value="{{ old('part_number', 1) }}">
                                    
                                    <!-- Marks (Hidden) -->
                                    <input type="hidden" name="marks" value="{{ old('marks', 1) }}">
                                </div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    <!-- Task Instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Task Instructions <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="content" name="content" rows="8" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 text-sm"
                                                  placeholder="e.g., The chart below shows the percentage of households in owned and rented accommodation..."
                                                  required>{{ old('content') }}</textarea>
                                    </div>
                                    
                                    <!-- Sample Answer Points -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Sample Answer Points (Optional)
                                        </label>
                                        <textarea name="instructions" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 text-sm"
                                                  placeholder="Key points students should cover...">{{ old('instructions') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Upload for Task 1 -->
                    <div id="task1-media" class="bg-white rounded-lg shadow-sm overflow-hidden hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-indigo-50">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">
                                Visual Data <span class="text-red-500">*</span>
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div id="drop-zone" class="border-2 border-dashed border-indigo-300 rounded-lg p-6 sm:p-8 text-center hover:border-indigo-400 transition-colors cursor-pointer bg-indigo-50/30">
                                <svg class="mx-auto h-10 sm:h-12 w-10 sm:w-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <label for="media" class="cursor-pointer text-indigo-600 hover:text-indigo-700 font-medium">
                                        Click to upload
                                    </label>
                                    <span class="hidden sm:inline"> or drag and drop</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 10MB</p>
                                <input id="media" name="media" type="file" class="hidden" accept="image/*">
                            </div>
                            
                            <div id="media-preview" class="mt-4 hidden">
                                <!-- Preview will be shown here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons - Sticky on Mobile -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 sticky bottom-0 z-10 border-t sm:border-t-0 sm:relative">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" name="action" value="save" class="flex-1 py-2.5 sm:py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors text-sm sm:text-base">
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
            border-color: #6366F1 !important;
            background-color: #EEF2FF !important;
        }
        
        /* Clean notification */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #4F46E5;
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
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .sticky {
                box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            }
        }
    </style>
    @endpush
    
    @push('scripts')
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-writing.js') }}"></script>
    @endpush
</x-layout>