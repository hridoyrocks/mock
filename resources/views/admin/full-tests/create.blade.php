{{-- resources/views/admin/full-tests/create.blade.php --}}
<x-admin-layout>
    <x-slot:title>Create Full Test</x-slot>

    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-white">Create Full Test</h1>
            <p class="text-gray-400 mt-2">Create a new full IELTS test by combining individual section tests</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.full-tests.store') }}" method="POST">
            @csrf
            
            <div class="glass rounded-lg p-6">
                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-4">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                                Test Title <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title') }}"
                                   class="form-input"
                                   placeholder="e.g., Cambridge IELTS 18 Test 1"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                Description
                            </label>
                            <input type="text" 
                                   name="description" 
                                   id="description" 
                                   value="{{ old('description') }}"
                                   class="form-input"
                                   placeholder="Brief description of the test">
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="is_premium" 
                                       value="1"
                                       {{ old('is_premium') ? 'checked' : '' }}
                                       class="form-checkbox">
                                <span class="text-gray-300">
                                    <i class="fas fa-crown text-amber-400 mr-2"></i>
                                    Premium Test (Requires subscription)
                                </span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="active" 
                                       value="1"
                                       {{ old('active', true) ? 'checked' : '' }}
                                       class="form-checkbox">
                                <span class="text-gray-300">
                                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                    Active (Visible to students)
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section Selection -->
                <div>
                    <h2 class="text-xl font-semibold text-white mb-4">Select Test Sets for Each Section</h2>
                    <p class="text-gray-400 mb-6">Choose one test set for each section to create a complete IELTS test</p>
                    
                    <div class="space-y-6">
                        <!-- Listening Section -->
                        <div class="bg-gray-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-white mb-4">
                                <i class="fas fa-headphones text-violet-400 mr-2"></i>
                                Listening Section
                            </h3>
                            <select name="listening_test_set_id" 
                                    id="listening_test_set_id" 
                                    class="form-select"
                                    required>
                                <option value="">Select a listening test set</option>
                                @foreach($testSets['listening'] ?? [] as $testSet)
                                    <option value="{{ $testSet->id }}" 
                                            {{ old('listening_test_set_id') == $testSet->id ? 'selected' : '' }}>
                                        {{ $testSet->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('listening_test_set_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reading Section -->
                        <div class="bg-gray-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-white mb-4">
                                <i class="fas fa-book-open text-emerald-400 mr-2"></i>
                                Reading Section
                            </h3>
                            <select name="reading_test_set_id" 
                                    id="reading_test_set_id" 
                                    class="form-select"
                                    required>
                                <option value="">Select a reading test set</option>
                                @foreach($testSets['reading'] ?? [] as $testSet)
                                    <option value="{{ $testSet->id }}" 
                                            {{ old('reading_test_set_id') == $testSet->id ? 'selected' : '' }}>
                                        {{ $testSet->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reading_test_set_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Writing Section -->
                        <div class="bg-gray-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-white mb-4">
                                <i class="fas fa-pen-fancy text-amber-400 mr-2"></i>
                                Writing Section
                            </h3>
                            <select name="writing_test_set_id" 
                                    id="writing_test_set_id" 
                                    class="form-select"
                                    required>
                                <option value="">Select a writing test set</option>
                                @foreach($testSets['writing'] ?? [] as $testSet)
                                    <option value="{{ $testSet->id }}" 
                                            {{ old('writing_test_set_id') == $testSet->id ? 'selected' : '' }}>
                                        {{ $testSet->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('writing_test_set_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Speaking Section -->
                        <div class="bg-gray-800/50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-white mb-4">
                                <i class="fas fa-microphone text-rose-400 mr-2"></i>
                                Speaking Section
                            </h3>
                            <select name="speaking_test_set_id" 
                                    id="speaking_test_set_id" 
                                    class="form-select"
                                    required>
                                <option value="">Select a speaking test set</option>
                                @foreach($testSets['speaking'] ?? [] as $testSet)
                                    <option value="{{ $testSet->id }}" 
                                            {{ old('speaking_test_set_id') == $testSet->id ? 'selected' : '' }}>
                                        {{ $testSet->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('speaking_test_set_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.full-tests.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Create Full Test
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
