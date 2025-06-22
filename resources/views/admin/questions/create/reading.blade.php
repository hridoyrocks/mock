<x-layout>
    <x-slot:title>Add Reading Passage - {{ $testSet->title }}</x-slot>
    
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">📄 Add Reading Passage</h1>
                        <p class="text-green-100 text-sm mt-1">{{ $testSet->title }} - Reading Section</p>
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-green-600">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-medium">1</div>
                        <span class="ml-2 text-sm font-medium">Add Passage</span>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="h-1 bg-gray-200 rounded"></div>
                    </div>
                    <div class="flex items-center text-gray-400">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-500 rounded-full text-sm font-medium">2</div>
                        <span class="ml-2 text-sm">Add Questions</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.questions.store') }}" method="POST" id="passageForm">
                @csrf
                <input type="hidden" name="test_set_id" value="{{ $testSet->id }}">
                <input type="hidden" name="question_type" value="passage">
                <input type="hidden" name="part_number" value="1">
                <input type="hidden" name="marks" value="0">
                
                <!-- Main Form Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">
                                <svg class="inline-block w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Reading Passage Content
                            </h3>
                            <div class="text-sm text-green-600 font-medium">
                                Passage Order: #{{ $nextQuestionNumber }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Title and Order Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Passage Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="instructions" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="e.g., The History of Navigation"
                                       value="{{ old('instructions', 'Passage ' . $nextQuestionNumber) }}" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Order Number <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="order_number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       value="{{ old('order_number', $nextQuestionNumber) }}" min="0" required>
                            </div>
                        </div>

                        <!-- Marker Instructions -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-blue-900 mb-2">How to Mark Question Locations:</h4>
                                    <ul class="text-sm text-blue-800 space-y-1">
                                        <li>• Type <code class="bg-white px-2 py-0.5 rounded text-xs font-mono">&#123;&#123;Q1&#125;&#125;</code> before and after the answer text</li>
                                        <li>• Example: The Wright brothers achieved <code class="bg-white px-2 py-0.5 rounded text-xs font-mono">&#123;&#123;Q1&#125;&#125;first powered flight&#123;&#123;Q1&#125;&#125;</code> in 1903.</li>
                                        <li>• Use <code class="bg-white px-2 py-0.5 rounded text-xs font-mono">&#123;&#123;Q2&#125;&#125;</code>, <code class="bg-white px-2 py-0.5 rounded text-xs font-mono">&#123;&#123;Q3&#125;&#125;</code>, etc. for different questions</li>
                                        <li>• Markers will be automatically detected and highlighted</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Marker Buttons -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Insert Markers:</label>
                            <div class="flex flex-wrap gap-2">
                                @for($i = 1; $i <= 10; $i++)
                                <button type="button" onclick="insertQuickMarker({{ $i }})" 
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-mono rounded transition-colors">
                                    &#123;&#123;Q{{ $i }}&#125;&#125;...&#123;&#123;Q{{ $i }}&#125;&#125;
                                </button>
                                @endfor
                            </div>
                        </div>

                        <!-- Passage Content Editor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Passage Content <span class="text-red-500">*</span>
                            </label>
                            
                            <textarea id="passageEditor" name="content" class="tinymce-passage" required>{{ old('content') }}</textarea>
                            
                            <!-- Info Bar -->
                            <div class="flex justify-between items-center mt-3 bg-gray-50 rounded p-2">
                                <div class="flex gap-4 text-sm">
                                    <span class="text-gray-600">Words: <span id="passage-word-count" class="font-medium">0</span></span>
                                    <span class="text-gray-600">Characters: <span id="passage-char-count" class="font-medium">0</span></span>
                                </div>
                                <div id="markers-info" class="text-sm">
                                    <span class="text-gray-600">Markers: <span id="markers-count" class="font-medium text-green-600">0</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Live Markers Detection Panel -->
                        <div id="markers-panel" class="mt-6 hidden">
                            <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                                <h4 class="font-semibold text-sm mb-3 flex items-center text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Detected Question Markers:
                                </h4>
                                <div id="markers-list" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <!-- Markers will be listed here -->
                                </div>
                            </div>
                        </div>

                        <!-- Preview Button -->
                        <div class="mt-6 flex justify-end">
                            <button type="button" onclick="previewPassage()" 
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview with Markers
                            </button>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" class="flex-1 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Passage & Continue
                            </button>
                            <a href="{{ route('admin.test-sets.show', $testSet) }}" class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors text-center">
                                Cancel
                            </a>
                        </div>
                        
                        <!-- Next Step Info -->
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex items-center text-blue-800 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span><strong>Next:</strong> After saving, you'll add questions that link to these markers.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Preview Modal -->
    <div id="preview-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 lg:w-4/5 shadow-lg rounded-md bg-white">
            <div class="sticky top-0 bg-white border-b pb-4 mb-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">📄 Passage Preview with Markers</h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Passage Content -->
                <div class="lg:col-span-2">
                    <div id="preview-content" class="prose max-w-none">
                        <!-- Preview content will be inserted here -->
                    </div>
                </div>
                
                <!-- Markers Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-20 bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-sm mb-3 text-gray-800">📍 Question Locations:</h4>
                        <div id="preview-markers-list" class="space-y-2 text-sm">
                            <!-- Markers will be listed here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* TinyMCE Editor Styling */
        .tox .tox-editor-header {
            border-bottom: 1px solid #e5e7eb !important;
        }
        
        .tox.tox-tinymce {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
        }
        
        /* Marker highlighting in preview */
        .marker-highlight {
            background: linear-gradient(to bottom, transparent 60%, #fef3c7 60%);
            padding: 2px 0;
            font-weight: 600;
            color: #92400e;
            position: relative;
        }
        
        .marker-tag {
            display: inline-block;
            background-color: #f59e0b;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 3px;
            margin: 0 2px;
            vertical-align: super;
        }
        
        /* Marker list styles */
        .marker-item {
            background: white;
            border: 1px solid #d1fae5;
            border-radius: 6px;
            padding: 10px;
            transition: all 0.2s;
        }
        
        .marker-item:hover {
            border-color: #16a34a;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }

        /* Live detection animation */
        @keyframes markerPulse {
            0% { opacity: 0.5; transform: scale(0.95); }
            50% { opacity: 1; transform: scale(1); }
            100% { opacity: 0.5; transform: scale(0.95); }
        }
        
        .marker-detected {
            animation: markerPulse 1s ease-in-out;
        }
    </style>
    @endpush

    @push('scripts')
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- External JavaScript File -->
    <script src="{{ asset('js/admin/reading-passage.js') }}"></script>
    @endpush
</x-layout>