<x-layout>
    <x-slot:title>Add Question - Listening</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">🎧 Add Listening Question</h1>
                        <p class="text-purple-100 text-sm mt-1">{{ $testSet->title }}</p>
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
                                                  placeholder="e.g., Questions 1-5: Complete the form below. Write NO MORE THAN TWO WORDS AND/OR A NUMBER for each answer.">{{ old('instructions') }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mb-3 flex space-x-2">
                                            <button type="button" onclick="insertBlank()" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                                Insert Blank
                                            </button>
                                        </div>
                                        <textarea id="content" name="content" class="tinymce">{{ old('content') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    @include('admin.questions.partials.question-settings', [
                                        'questionTypes' => [
                                            'multiple_choice' => 'Multiple Choice',
                                            'form_completion' => 'Form Completion',
                                            'note_completion' => 'Note Completion',
                                            'sentence_completion' => 'Sentence Completion',
                                            'short_answer' => 'Short Answer',
                                            'matching' => 'Matching',
                                            'plan_map_diagram' => 'Plan/Map/Diagram Labeling'
                                        ]
                                    ])
                                    
                                    <!-- Audio Transcript -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Audio Transcript
                                        </label>
                                        <textarea name="audio_transcript" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="Enter the transcript of the audio...">{{ old('audio_transcript') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @include('admin.questions.partials.options-manager')
                    
                    <!-- Audio Upload (Required for Listening) -->
                    @include('admin.questions.partials.media-upload', [
                        'acceptedFormats' => '.mp3,.wav,.ogg',
                        'mediaHelpText' => 'Audio files only: MP3, WAV, OGG (max 50MB)'
                    ])
                    
                    @include('admin.questions.partials.action-buttons')
                </div>
            </form>
        </div>
    </div>

    @include('admin.questions.partials.modals')
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-listening.js') }}"></script>
    @endpush
</x-layout>