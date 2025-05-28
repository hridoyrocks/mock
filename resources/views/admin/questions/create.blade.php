<x-layout>
    <x-slot:title>Create Question - Admin</x-slot>
    
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Question') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="test_set_id" class="block mb-2 text-sm font-medium text-gray-900">Test Set</label>
                            <select id="test_set_id" name="test_set_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select a test set</option>
                                @foreach($testSets as $testSet)
                                    <option value="{{ $testSet->id }}" {{ old('test_set_id') == $testSet->id ? 'selected' : '' }}>
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
                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>True/False/Not Given</option>
                                <option value="matching" {{ old('question_type') == 'matching' ? 'selected' : '' }}>Matching</option>
                                <option value="fill_blank" {{ old('question_type') == 'fill_blank' ? 'selected' : '' }}>Fill in the Blank</option>
                                <option value="short_answer" {{ old('question_type') == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                <option value="essay" {{ old('question_type') == 'essay' ? 'selected' : '' }}>Essay (Writing)</option>
                                <option value="cue_card" {{ old('question_type') == 'cue_card' ? 'selected' : '' }}>Cue Card (Speaking)</option>
                            </select>
                            @error('question_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="order_number" class="block mb-2 text-sm font-medium text-gray-900">Question Number</label>
                            <input type="number" id="order_number" name="order_number" value="{{ old('order_number') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('order_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="content" class="block mb-2 text-sm font-medium text-gray-900">Question Content</label>
                            <textarea id="content" name="content" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="media" class="block mb-2 text-sm font-medium text-gray-900">Media File (Audio/Image)</label>
                            <input type="file" id="media" name="media" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <p class="mt-1 text-sm text-gray-500">Upload audio for listening questions or images for other question types if needed</p>
                            @error('media')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="options-container" class="mb-6 hidden">
                            <h3 class="text-lg font-medium mb-4">Answer Options</h3>
                            
                            <div id="option-template" class="option-item mb-4 p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <input type="radio" name="correct_option" value="0" class="correct-option w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                    <label class="ml-2 text-sm font-medium text-gray-900">Mark as correct answer</label>
                                </div>
                                
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Option Content</label>
                                    <textarea name="options[0][content]" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                                </div>
                            </div>
                            
                            <div id="options-list" class="space-y-4"></div>
                            
                            <button type="button" id="add-option" class="mt-4 text-sm text-blue-600 hover:underline">+ Add Another Option</button>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg mr-4">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Create Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionType = document.getElementById('question_type');
            const optionsContainer = document.getElementById('options-container');
            const optionTemplate = document.getElementById('option-template');
            const optionsList = document.getElementById('options-list');
            const addOptionBtn = document.getElementById('add-option');
            
            // Hide the template
            optionTemplate.style.display = 'none';
            
            // Show options container for multiple choice and true/false questions
            questionType.addEventListener('change', function() {
                if (this.value === 'multiple_choice' || this.value === 'true_false' || this.value === 'matching') {
                    optionsContainer.classList.remove('hidden');
                    
                    // Clear existing options
                    optionsList.innerHTML = '';
                    
                    // Add initial options based on question type
                    if (this.value === 'true_false') {
                        addOption('True');
                        addOption('False');
                        addOption('Not Given');
                        addOptionBtn.style.display = 'none';
                    } else {
                        addOption();
                        addOption();
                        addOption();
                        addOption();
                        addOptionBtn.style.display = 'block';
                    }
                } else {
                    optionsContainer.classList.add('hidden');
                }
            });
            
            // Add option button click handler
            addOptionBtn.addEventListener('click', function() {
                addOption();
            });
            
            // Function to add an option
            function addOption(predefinedContent = '') {
                const optionCount = optionsList.querySelectorAll('.option-item').length;
                
                // Clone the template
                const newOption = optionTemplate.cloneNode(true);
                newOption.style.display = 'block';
                newOption.id = '';
                
                // Update option index
                const radioInput = newOption.querySelector('.correct-option');
                radioInput.value = optionCount;
                radioInput.name = 'correct_option';
                
                const textarea = newOption.querySelector('textarea');
                textarea.name = `options[${optionCount}][content]`;
                
                if (predefinedContent) {
                    textarea.value = predefinedContent;
                }
                
                // Add delete button for dynamic options
                if (!predefinedContent) {
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className = 'text-red-500 hover:text-red-700 text-sm mt-2';
                    deleteBtn.textContent = 'Remove Option';
                    deleteBtn.addEventListener('click', function() {
                        newOption.remove();
                    });
                    
                    newOption.appendChild(deleteBtn);
                }
                
                optionsList.appendChild(newOption);
            }
        });
    </script>
    @endpush
</x-layout>