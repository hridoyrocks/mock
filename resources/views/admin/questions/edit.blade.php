<x-layout>
    <x-slot:title>Edit Question - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Question') }}
            </h2>
            <a href="{{ route('admin.test-sets.show', $question->testSet) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                Back to Test Set
            </a>
        </div>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="test_set_id" class="block mb-2 text-sm font-medium text-gray-900">Test Set</label>
                            <select id="test_set_id" name="test_set_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select a test set</option>
                                @foreach($testSets as $testSet)
                                    <option value="{{ $testSet->id }}" {{ old('test_set_id', $question->test_set_id) == $testSet->id ? 'selected' : '' }}>
                                        {{ $testSet->title }} ({{ ucfirst($testSet->section->name) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('test_set_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="question_type" class="block mb-2 text-sm font-medium text-gray-900">Question Type</label>
                            <select id="question_type" name="question_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select a question type</option>
                                <option value="multiple_choice" {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="true_false" {{ old('question_type', $question->question_type) == 'true_false' ? 'selected' : '' }}>True/False/Not Given</option>
                                <option value="matching" {{ old('question_type', $question->question_type) == 'matching' ? 'selected' : '' }}>Matching</option>
                                <option value="fill_blank" {{ old('question_type', $question->question_type) == 'fill_blank' ? 'selected' : '' }}>Fill in the Blank</option>
                                <option value="short_answer" {{ old('question_type', $question->question_type) == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                <option value="essay" {{ old('question_type', $question->question_type) == 'essay' ? 'selected' : '' }}>Essay (Writing)</option>
                                <option value="cue_card" {{ old('question_type', $question->question_type) == 'cue_card' ? 'selected' : '' }}>Cue Card (Speaking)</option>
                                <option value="passage" {{ old('question_type', $question->question_type) == 'passage' ? 'selected' : '' }}>Reading Passage</option>
                            </select>
                            @error('question_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="order_number" class="block mb-2 text-sm font-medium text-gray-900">Question Number</label>
                            <input type="number" id="order_number" name="order_number" value="{{ old('order_number', $question->order_number) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('order_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="content" class="block mb-2 text-sm font-medium text-gray-900">Question Content</label>
                            <textarea id="content" name="content" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('content', $question->content) }}</textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="media" class="block mb-2 text-sm font-medium text-gray-900">Media File (Audio/Image)</label>
                            <input type="file" id="media" name="media" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <p class="mt-1 text-sm text-gray-500">Upload new media file if you want to replace the existing one</p>
                            
                            @if($question->media_path)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700">Current media:</p>
                                    @if(Str::endsWith($question->media_path, ['.mp3', '.wav', '.ogg']))
                                        <audio controls class="mt-1">
                                            <source src="{{ asset('storage/' . $question->media_path) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    @else
                                        <img src="{{ asset('storage/' . $question->media_path) }}" alt="Question media" class="mt-1 max-h-40">
                                    @endif
                                </div>
                                
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="remove_media" class="rounded text-red-500 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-red-500">Remove existing media</span>
                                    </label>
                                </div>
                            @endif
                            
                            @error('media')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        @if(in_array($question->question_type, ['multiple_choice', 'true_false', 'matching']))
                            <div id="options-container" class="mb-6">
                                <h3 class="text-lg font-medium mb-4">Answer Options</h3>
                                
                                @foreach($question->options as $index => $option)
                                    <div class="option-item mb-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <input type="radio" name="correct_option" value="{{ $index }}" class="correct-option w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" {{ $option->is_correct ? 'checked' : '' }}>
                                            <label class="ml-2 text-sm font-medium text-gray-900">Mark as correct answer</label>
                                        </div>
                                        
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Option Content</label>
                                            <textarea name="options[{{ $index }}][content]" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $option->content }}</textarea>
                                            <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">
                                        </div>
                                        
                                        <button type="button" class="text-red-500 hover:text-red-700 text-sm mt-2 remove-option">Remove Option</button>
                                    </div>
                                @endforeach
                                
                                <div id="options-list" class="space-y-4"></div>
                                
                                <button type="button" id="add-option" class="mt-4 text-sm text-blue-600 hover:underline">+ Add Another Option</button>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.test-sets.show', $question->testSet) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg mr-4">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Update Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if(in_array($question->question_type, ['multiple_choice', 'true_false', 'matching']))
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const questionType = document.getElementById('question_type');
                const optionsContainer = document.getElementById('options-container');
                const optionsList = document.getElementById('options-list');
                const addOptionBtn = document.getElementById('add-option');
                
                // Show/hide options based on question type
                questionType.addEventListener('change', function() {
                    if (this.value === 'multiple_choice' || this.value === 'true_false' || this.value === 'matching') {
                        optionsContainer.classList.remove('hidden');
                    } else {
                        optionsContainer.classList.add('hidden');
                    }
                });
                
                // Add option button click handler
                addOptionBtn.addEventListener('click', function() {
                    addOption();
                });
                
                // Remove option button click handler
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('remove-option')) {
                        e.target.closest('.option-item').remove();
                    }
                });
                
                // Function to add an option
                function addOption() {
                    const optionItems = document.querySelectorAll('.option-item');
                    const optionCount = optionItems.length;
                    
                    const newOption = document.createElement('div');
                    newOption.className = 'option-item mb-4 p-4 border border-gray-200 rounded-lg';
                    newOption.innerHTML = `
                        <div class="flex items-center mb-2">
                            <input type="radio" name="correct_option" value="${optionCount}" class="correct-option w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                            <label class="ml-2 text-sm font-medium text-gray-900">Mark as correct answer</label>
                        </div>
                        
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Option Content</label>
                            <textarea name="options[${optionCount}][content]" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                        </div>
                        
                        <button type="button" class="text-red-500 hover:text-red-700 text-sm mt-2 remove-option">Remove Option</button>
                    `;
                    
                    optionsList.appendChild(newOption);
                }
            });
        </script>
        @endpush
    @endif
</x-layout>