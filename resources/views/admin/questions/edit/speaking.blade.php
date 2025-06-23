<x-layout>
    <x-slot:title>Edit Speaking Question</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">🎤 Edit Speaking Question #{{ $question->order_number }}</h1>
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
            
            <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Speaking Question -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Speaking Question</h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-6">
                                    <!-- Question Type (Read-only) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Question Type</label>
                                        <input type="text" value="{{ $question->question_type }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                                        <input type="hidden" name="question_type" value="{{ $question->question_type }}">
                                    </div>
                                    
                                    <!-- Question Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Number <span class="text-red-500">*</span></label>
                                        <input type="number" name="order_number" value="{{ old('order_number', $question->order_number) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" min="1" required>
                                    </div>
                                    
                                    <!-- Part Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Part <span class="text-red-500">*</span></label>
                                        <select name="part_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                                            <option value="1" {{ $question->part_number == 1 ? 'selected' : '' }}>Part 1 (Introduction)</option>
                                            <option value="2" {{ $question->part_number == 2 ? 'selected' : '' }}>Part 2 (Cue Card)</option>
                                            <option value="3" {{ $question->part_number == 3 ? 'selected' : '' }}>Part 3 (Discussion)</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Response Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Response Time (minutes) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="time_limit" value="{{ old('time_limit', $question->time_limit) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" 
                                               min="1" max="10" required>
                                        <p class="text-xs text-gray-500 mt-1">Part 1: 1-2 min, Part 2: 2 min, Part 3: 4-5 min</p>
                                    </div>
                                    
                                    <!-- Marks -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Marks</label>
                                        <input type="number" name="marks" value="{{ old('marks', $question->marks) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" min="0" max="40">
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
                                                  required>{{ old('content', $question->content) }}</textarea>
                                    </div>
                                    
                                    <!-- Follow-up Questions (Part 3) -->
                                    @if($question->question_type === 'part3_discussion' || $question->part_number == 3)
                                    <div id="followup-questions">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Follow-up Questions
                                        </label>
                                        <textarea name="instructions" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="Enter follow-up questions separated by new lines...">{{ old('instructions', $question->instructions) }}</textarea>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Cue Card Format (Part 2) -->
                            @if($question->question_type === 'part2_cue_card' || $question->part_number == 2)
                            <div id="cue-card-format" class="mt-6">
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
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Cue Card Points (Optional)
                                        </label>
                                        <textarea name="cue_card_points" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="Enter the cue card bullet points...">{{ old('cue_card_points', $question->section_specific_data['cue_card_points'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Speaking Assessment Criteria -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 bg-orange-50">
                            <h3 class="text-lg font-medium text-gray-900">Assessment Criteria & Sample Answers</h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Band 7-9 Sample -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Band 7-9 Sample Response (Optional)
                                </label>
                                <textarea name="sample_answer_high" rows="6" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                          placeholder="Provide a high-scoring sample response...">{{ old('sample_answer_high', $question->section_specific_data['sample_answer_high'] ?? '') }}</textarea>
                            </div>
                            
                            <!-- Band 5-6 Sample -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Band 5-6 Sample Response (Optional)
                                </label>
                                <textarea name="sample_answer_mid" rows="6" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                          placeholder="Provide a mid-range sample response...">{{ old('sample_answer_mid', $question->section_specific_data['sample_answer_mid'] ?? '') }}</textarea>
                            </div>
                            
                            <!-- Key Vocabulary for Speaking -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Useful Vocabulary & Phrases
                                    </label>
                                    <textarea name="useful_vocabulary" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                              placeholder="List vocabulary and phrases for this topic...">{{ old('useful_vocabulary', $question->section_specific_data['useful_vocabulary'] ?? '') }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Speaking Tips
                                    </label>
                                    <textarea name="speaking_tips" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                              placeholder="Tips for answering this type of question...">{{ old('speaking_tips', $question->section_specific_data['speaking_tips'] ?? '') }}</textarea>
                                </div>
                            </div>
                            
                            <!-- Common Pronunciation Issues -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Common Pronunciation Issues
                                </label>
                                <textarea name="pronunciation_notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                          placeholder="Note any challenging words or sounds...">{{ old('pronunciation_notes', $question->section_specific_data['pronunciation_notes'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                                    Update Question
                                </button>
                                <button type="button" onclick="previewQuestion()" class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors">
                                    Preview
                                </button>
                                <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                                   class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 text-center transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('scripts')
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script>
        // Handle section specific data for speaking
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            // Collect speaking-specific data
            const sampleAnswerHigh = document.querySelector('[name="sample_answer_high"]');
            const sampleAnswerMid = document.querySelector('[name="sample_answer_mid"]');
            const usefulVocabulary = document.querySelector('[name="useful_vocabulary"]');
            const speakingTips = document.querySelector('[name="speaking_tips"]');
            const pronunciationNotes = document.querySelector('[name="pronunciation_notes"]');
            const cueCardPoints = document.querySelector('[name="cue_card_points"]');
            
            // Create section_specific_data
            const sectionData = {
                sample_answer_high: sampleAnswerHigh ? sampleAnswerHigh.value : '',
                sample_answer_mid: sampleAnswerMid ? sampleAnswerMid.value : '',
                useful_vocabulary: usefulVocabulary ? usefulVocabulary.value : '',
                speaking_tips: speakingTips ? speakingTips.value : '',
                pronunciation_notes: pronunciationNotes ? pronunciationNotes.value : '',
                cue_card_points: cueCardPoints ? cueCardPoints.value : ''
            };
            
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'section_specific_data';
            hiddenInput.value = JSON.stringify(sectionData);
            this.appendChild(hiddenInput);
        });
        
        // Dynamic form changes based on part
        const partSelect = document.querySelector('[name="part_number"]');
        const questionType = '{{ $question->question_type }}';
        
        if (partSelect) {
            partSelect.addEventListener('change', function() {
                const part = this.value;
                const followupQuestions = document.getElementById('followup-questions');
                const cueCardFormat = document.getElementById('cue-card-format');
                const timeLimitInput = document.querySelector('[name="time_limit"]');
                
                // Hide all first
                if (followupQuestions) followupQuestions.style.display = 'none';
                if (cueCardFormat) cueCardFormat.style.display = 'none';
                
                // Show based on part
                switch(part) {
                    case '1':
                        if (timeLimitInput) timeLimitInput.value = 1;
                        break;
                    case '2':
                        if (timeLimitInput) timeLimitInput.value = 2;
                        if (cueCardFormat) cueCardFormat.style.display = 'block';
                        break;
                    case '3':
                        if (timeLimitInput) timeLimitInput.value = 5;
                        if (followupQuestions) followupQuestions.style.display = 'block';
                        break;
                }
            });
        }
    </script>
    @endpush
</x-layout>