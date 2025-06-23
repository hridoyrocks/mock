<x-layout>
    <x-slot:title>Add Question - Writing</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">✍️ Add Writing Question</h1>
                        <p class="text-indigo-100 text-sm mt-1">{{ $testSet->title }}</p>
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
                            <h3 class="text-lg font-medium text-gray-900">Task Details</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-6">
                                    @include('admin.questions.partials.question-settings', [
                                        'questionTypes' => [
                                            'task1_line_graph' => 'Task 1: Line Graph',
                                            'task1_bar_chart' => 'Task 1: Bar Chart',
                                            'task1_pie_chart' => 'Task 1: Pie Chart',
                                            'task1_table' => 'Task 1: Table',
                                            'task1_process' => 'Task 1: Process Diagram',
                                            'task1_map' => 'Task 1: Map',
                                            'task2_opinion' => 'Task 2: Opinion Essay',
                                            'task2_discussion' => 'Task 2: Discussion Essay',
                                            'task2_problem_solution' => 'Task 2: Problem/Solution',
                                            'task2_advantage_disadvantage' => 'Task 2: Advantages/Disadvantages'
                                        ]
                                    ])
                                    
                                    <!-- Word Limit -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Word Limit <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="word_limit" value="{{ old('word_limit', 150) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" 
                                               min="50" max="500" required>
                                        <p class="text-xs text-gray-500 mt-1">Task 1: 150 words, Task 2: 250 words</p>
                                    </div>
                                    
                                    <!-- Time Limit -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Time Limit (minutes) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="time_limit" value="{{ old('time_limit', 20) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" 
                                               min="1" max="60" required>
                                        <p class="text-xs text-gray-500 mt-1">Task 1: 20 minutes, Task 2: 40 minutes</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <!-- Task Instructions -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Task Instructions <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="content" name="content" rows="8" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="e.g., The chart below shows the percentage of households in owned and rented accommodation..."
                                                  required>{{ old('content') }}</textarea>
                                    </div>
                                    
                                    <!-- Sample Answer Points -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Sample Answer Points (Optional)
                                        </label>
                                        <textarea name="instructions" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="Key points students should cover...">{{ old('instructions') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Upload for Task 1 -->
                    <div id="task1-media" class="hidden">
                        @include('admin.questions.partials.media-upload', [
                            'acceptedFormats' => 'image/*',
                            'mediaHelpText' => 'Upload graph, chart, diagram, or map image (PNG, JPG, GIF - max 10MB)'
                        ])
                    </div>
                    
                    @include('admin.questions.partials.action-buttons')
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('scripts')
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-writing.js') }}"></script>
    @endpush
</x-layout>