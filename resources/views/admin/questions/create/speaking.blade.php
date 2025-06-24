<x-layout>
    <x-slot:title>Add Question - Speaking</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold">Add Speaking Question</h1>
                        <p class="text-orange-100 text-sm mt-1">{{ $testSet->title }}</p>
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
                    <!-- Speaking Question -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Speaking Question</h3>
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
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm" required>
                                            <option value="">Select type...</option>
                                            <option value="part1_personal">Part 1: Personal Questions</option>
                                            <option value="part2_cue_card">Part 2: Cue Card</option>
                                            <option value="part3_discussion">Part 3: Discussion</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Response Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Response Time (minutes) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="time_limit" value="{{ old('time_limit', 2) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm" 
                                               min="1" max="10" required>
                                        <p class="text-xs text-gray-500 mt-1">Part 1: 1-2 min, Part 2: 2 min, Part 3: 4-5 min</p>
                                    </div>
                                    
                                    <!-- Question Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Question Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="order_number" value="{{ old('order_number', $nextQuestionNumber ?? 1) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm" 
                                               min="1" required>
                                    </div>
                                    
                                    <!-- Part Number (Hidden) -->
                                    <input type="hidden" name="part_number" id="part_number" value="{{ old('part_number', 1) }}">
                                    
                                    <!-- Marks (Hidden) -->
                                    <input type="hidden" name="marks" value="{{ old('marks', 1) }}">
                                </div>
                                
                                <div class="space-y-4 sm:space-y-6">
                                    <!-- Question Content -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question / Topic <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="content" name="content" rows="6" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm"
                                                  placeholder="Enter the speaking question or topic..."
                                                  required>{{ old('content') }}</textarea>
                                    </div>
                                    
                                    <!-- Follow-up Questions (Part 3) -->
                                    <div id="followup-questions" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Follow-up Questions
                                        </label>
                                        <textarea name="instructions" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 text-sm"
                                                  placeholder="Enter follow-up questions separated by new lines...">{{ old('instructions') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cue Card Format (Part 2) -->
                            <div id="cue-card-format" class="hidden mt-6">
                                <div class="bg-orange-50 border border-orange-200 rounded-md p-4">
                                    <h4 class="text-sm font-medium text-orange-800 mb-2">Cue Card Format:</h4>
                                    <div class="text-sm text-orange-700">
                                        <p>Describe [topic]</p>
                                        <p class="mt-2">You should say:</p>
                                        <ul class="list-disc list-inside ml-2 mt-1">
                                            <li>Point 1</li>
                                            <li>Point 2</li>
                                            <li>Point 3</li>
                                        </ul>
                                        <p class="mt-2">And explain [explanation requirement]</p>
                                    </div>
                                    <p class="text-xs text-orange-600 mt-3">
                                        Format your question following this structure for better clarity.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons - Sticky on Mobile -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 sticky bottom-0 z-10 border-t sm:border-t-0 sm:relative">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" name="action" value="save" class="flex-1 py-2.5 sm:py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700 transition-colors text-sm sm:text-base">
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
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #EA580C;
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
    <script src="{{ asset('js/admin/question-speaking.js') }}"></script>
    @endpush
</x-layout>