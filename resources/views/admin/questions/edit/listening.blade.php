<x-layout>
    <x-slot:title>Edit Listening Question</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">🎧 Edit Listening Question #{{ $question->order_number }}</h1>
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
            
            <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                @method('PUT')
                
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
                                                  placeholder="e.g., Questions 1-5: Complete the form below...">{{ old('instructions', $question->instructions) }}</textarea>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="content" name="content" class="tinymce">{{ old('content', $question->content) }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <!-- Question Type (Read-only) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
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
                                            <option value="1" {{ $question->part_number == 1 ? 'selected' : '' }}>Part 1 (Social)</option>
                                            <option value="2" {{ $question->part_number == 2 ? 'selected' : '' }}>Part 2 (Monologue)</option>
                                            <option value="3" {{ $question->part_number == 3 ? 'selected' : '' }}>Part 3 (Discussion)</option>
                                            <option value="4" {{ $question->part_number == 4 ? 'selected' : '' }}>Part 4 (Lecture)</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Marks -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Marks</label>
                                        <input type="number" name="marks" value="{{ old('marks', $question->marks) }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" min="0" max="40">
                                    </div>
                                    
                                    <!-- Audio Transcript -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Audio Transcript
                                        </label>
                                        <textarea name="audio_transcript" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                                  placeholder="Enter the transcript of the audio...">{{ old('audio_transcript', $question->audio_transcript) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Options Manager (if applicable) -->
                    @if(in_array($question->question_type, ['multiple_choice', 'matching']))
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Answer Options</h3>
                        </div>
                        
                        <div class="p-6">
                            <div id="options-container" class="space-y-3">
                                @foreach($question->options as $index => $option)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <input type="radio" name="correct_option" value="{{ $index }}" 
                                           class="h-4 w-4 text-blue-600" {{ $option->is_correct ? 'checked' : '' }}>
                                    <span class="font-medium text-gray-700">{{ chr(65 + $index) }}.</span>
                                    <input type="text" name="options[{{ $index }}][content]" value="{{ $option->content }}" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                                    <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="add-option-btn" onclick="addOption()"
                                    class="mt-4 w-full px-4 py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-md hover:border-gray-400 hover:text-gray-600 transition-all">
                                + Add Option
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Audio Upload -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Audio File <span class="text-red-500">*</span></h3>
                        </div>
                        
                        <div class="p-6">
                            @if($question->media_path)
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-gray-700 mb-2">Current audio:</p>
                                <audio controls class="w-full">
                                    <source src="{{ Storage::url($question->media_path) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                                <div class="mt-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="remove_media" value="1" class="mr-2">
                                        <span class="text-sm text-red-600">Remove current audio</span>
                                    </label>
                                </div>
                            </div>
                            @endif
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-md p-8 text-center hover:border-gray-400 transition-colors cursor-pointer"
                                 id="drop-zone" onclick="document.getElementById('media').click()">
                                <input type="file" id="media" name="media" class="hidden" accept=".mp3,.wav,.ogg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-blue-600 hover:text-blue-500">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Audio files only: MP3, WAV, OGG (max 50MB)
                                </p>
                            </div>
                            <div id="media-preview" class="mt-4 hidden">
                                <!-- Preview will be shown here -->
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
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script src="{{ asset('js/admin/question-common.js') }}"></script>
    <script src="{{ asset('js/admin/question-listening.js') }}"></script>
    @endpush
</x-layout>