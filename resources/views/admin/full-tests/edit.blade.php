<x-admin-layout>
    <x-slot:title>Edit Full Test - {{ $fullTest->title }}</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.full-tests.index') }}" 
                   class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Full Test</h1>
                    <p class="mt-1 text-sm text-gray-600">Update the full test configuration and assigned sections</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.full-tests.update', $fullTest) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h2>
                    
                    <div class="space-y-5">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Test Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $fullTest->title) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., Complete IELTS Mock Test 1">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Brief description of this full test...">{{ old('description', $fullTest->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type and Status -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Premium Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Test Type
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="is_premium" 
                                               value="0"
                                               {{ old('is_premium', $fullTest->is_premium) == 0 ? 'checked' : '' }}
                                               class="rounded-full border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Free</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="is_premium" 
                                               value="1"
                                               {{ old('is_premium', $fullTest->is_premium) == 1 ? 'checked' : '' }}
                                               class="rounded-full border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <span class="inline-flex items-center">
                                                <svg class="mr-1 h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Premium
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Active Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="active" 
                                               value="1"
                                               {{ old('active', $fullTest->active) == 1 ? 'checked' : '' }}
                                               class="rounded-full border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="active" 
                                               value="0"
                                               {{ old('active', $fullTest->active) == 0 ? 'checked' : '' }}
                                               class="rounded-full border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Assignment -->
                <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Section Assignment</h2>
                    <p class="text-sm text-gray-600 mb-6">Select test sets for each section. Minimum 3 sections required.</p>
                    
                    <div class="space-y-4" x-data="{ selectedCount: 0 }" x-init="
                        selectedCount = Array.from(document.querySelectorAll('select[name$=_test_set_id]'))
                            .filter(select => select.value !== '').length;
                    ">
                        @php
                            $sections = [
                                'listening' => ['icon' => 'fa-headphones', 'color' => 'violet', 'title' => 'Listening'],
                                'reading' => ['icon' => 'fa-book-open', 'color' => 'emerald', 'title' => 'Reading'],
                                'writing' => ['icon' => 'fa-pen-fancy', 'color' => 'amber', 'title' => 'Writing'],
                                'speaking' => ['icon' => 'fa-microphone', 'color' => 'rose', 'title' => 'Speaking']
                            ];
                            
                            $currentTestSets = $fullTest->testSets->keyBy('pivot.section_type');
                        @endphp
                        
                        @foreach($sections as $key => $section)
                            <div class="p-4 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-{{ $section['color'] }}-100 flex items-center justify-center">
                                        <i class="fas {{ $section['icon'] }} text-{{ $section['color'] }}-600"></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <label for="{{ $key }}_test_set_id" class="block text-sm font-medium text-gray-700">
                                            {{ $section['title'] }} Section
                                        </label>
                                        <select name="{{ $key }}_test_set_id" 
                                                id="{{ $key }}_test_set_id"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                @change="
                                                    selectedCount = Array.from(document.querySelectorAll('select[name$=_test_set_id]'))
                                                        .filter(select => select.value !== '').length;
                                                ">
                                            <option value="">-- Select Test Set --</option>
                                            @if(isset($testSets[$section['title']]))
                                                @foreach($testSets[$section['title']] as $testSet)
                                                    <option value="{{ $testSet->id }}"
                                                        {{ old($key.'_test_set_id', $currentTestSets->get($key)?->id) == $testSet->id ? 'selected' : '' }}>
                                                        {{ $testSet->title }} ({{ $testSet->questions()->count() }} questions)
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Section Count Alert -->
                        <div x-show="selectedCount < 3" 
                             x-transition
                             class="p-4 rounded-lg bg-amber-50 border border-amber-200">
                            <div class="flex">
                                <svg class="h-5 w-5 text-amber-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-800">
                                        <span x-text="selectedCount"></span> of 3 minimum sections selected. 
                                        Please select at least 3 sections to create a valid full test.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div x-show="selectedCount >= 3" 
                             x-transition
                             class="p-4 rounded-lg bg-green-50 border border-green-200">
                            <div class="flex">
                                <svg class="h-5 w-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-green-800">
                                        Great! <span x-text="selectedCount"></span> sections selected. 
                                        This test meets the minimum requirements.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Current Status Card -->
                <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Status</h2>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-600">Total Attempts</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $fullTest->attempts()->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Status</dt>
                            <dd>
                                @if($fullTest->active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="mr-1.5 h-2 w-2 rounded-full bg-green-600"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="mr-1.5 h-2 w-2 rounded-full bg-red-600"></span>
                                        Inactive
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Type</dt>
                            <dd>
                                @if($fullTest->is_premium)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Premium
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Free
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600">Created</dt>
                            <dd class="text-sm text-gray-900">{{ $fullTest->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions Card -->
                <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Full Test
                        </button>
                        
                        <a href="{{ route('admin.full-tests.show', $fullTest) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                            View Details
                        </a>
                        
                        <a href="{{ route('admin.full-tests.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 text-gray-600 text-sm font-medium hover:text-gray-900 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="rounded-xl bg-blue-50 p-6 border border-blue-200">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">
                        <svg class="inline h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Tips
                    </h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Minimum 3 sections required</li>
                        <li>• Premium tests are only available to subscribed users</li>
                        <li>• Inactive tests won't appear to students</li>
                        <li>• You can't delete tests with attempts</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Add any additional JavaScript if needed
    </script>
    @endpush
</x-admin-layout>
