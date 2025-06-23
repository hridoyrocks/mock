<x-layout>
    <x-slot:title>Add Question - Speaking</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">🎤 Add Speaking Question</h1>
                        <p class="text-orange-100 text-sm mt-1">{{ $testSet->title }}</p>
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
                            <h3 class="text-lg font-medium text-gray-900">Speaking Question</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-6">
                                    @include('admin.questions.partials.question-settings', [
                                        'questionTypes' => [
                                            'part1_personal' => 'Part 1: Personal Questions',
                                            'part2_cue_card' => 'Part 2: Cue Card',
                                            'part3_discussion' => 'Part 3: Discussion'
                                        ]
                                    ])
                                    
                                    <!-- Response Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Response Time (minutes) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="time_limit" value="{{ old('time_limit', 2) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" 
                                               min="1" max="10" required>
                                        <p class="text-xs text-gray-500 mt-1">Part 1: 1-2 min, Part 2: 2 min, Part 3: 4-5 min</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <!-- Question Content -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question / Topic <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="content" name="content" rows="6" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  required>{{ old('content') }}</textarea>
                                    </div>
                                    
                                    <!-- Follow-up Questions (Part 3) -->
                                    <div id="followup-questions" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Follow-up Questions
                                        </label>
                                        <textarea name="instructions" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="Enter follow-up questions separated by new lines...">{{ old('instructions') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cue Card Format (Part 2) -->
                            <div id="cue-card-format" class="hidden mt-6">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <h4 class="text-sm font-medium text-yellow-800 mb-2">Cue Card Format:</h4>
                                    <div class="text-sm text-yellow-700">
                                        <p>Describe [topic]</p>
                                        <p class="mt-2">You should say:</p>
                                        <ul class="list-disc list-inside ml-2 mt-1">
                                            <li>Point 1</li>
                                            <li>Point 2</li>
                                            <li>Point 3</li>
                                        </ul>
                                        <p class="mt-2">And explain [explanation requirement]</p>
                                    </div>
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
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-speaking.js') }}"></script>
    @endpush
</x-layout>