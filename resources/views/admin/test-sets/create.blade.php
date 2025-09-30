<x-admin-layout>
    <x-slot:title>Create Test Set</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create Test Set</h1>
                    <p class="mt-1 text-sm text-gray-600">Create a new test set for a specific IELTS section</p>
                </div>
                <a href="{{ route('admin.test-sets.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Test Sets
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center">
                <svg class="mr-3 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Test Set Information</h3>
            </div>
        </div>
        
        <form action="{{ route('admin.test-sets.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Title Field -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Test Set Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}" 
                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('title') border-red-500 @enderror" 
                       placeholder="e.g., Listening Test - Academic Module 1"
                       required>
                @error('title')
                    <p class="mt-2 flex items-center text-sm text-red-600">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            
            <!-- Section Selection -->
            <div>
                <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Section <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select id="section_id" 
                            name="section_id" 
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('section_id') border-red-500 @enderror"
                            required>
                        <option value="">Select a section...</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" 
                                    {{ old('section_id', request('section')) == $section->id ? 'selected' : '' }}
                                    data-time="{{ $section->time_limit }}">
                                {{ ucfirst($section->name) }} - {{ $section->time_limit }} minutes
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('section_id')
                    <p class="mt-2 flex items-center text-sm text-red-600">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </p>
                @else
                    <p class="mt-2 text-sm text-gray-500">
                        <svg class="inline-block mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Select the IELTS section for this test set
                    </p>
                @enderror
            </div>

            <!-- Section Info Display -->
            <div id="section-info" class="hidden rounded-lg border-2 border-indigo-100 bg-indigo-50 p-4">
                <div class="flex items-start">
                    <svg class="mr-3 h-5 w-5 text-indigo-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-indigo-900">Selected Section</h4>
                        <p class="mt-1 text-sm text-indigo-700">
                            <span id="section-name" class="font-medium"></span>
                            <span id="section-time" class="ml-2"></span>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Active Status -->
            <div class="rounded-lg border-2 border-gray-200 p-4 hover:border-green-300 transition-colors">
                <label class="flex items-start cursor-pointer">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" 
                               id="active" 
                               name="active" 
                               value="1" 
                               {{ old('active', true) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    </div>
                    <div class="ml-3">
                        <span class="flex items-center text-sm font-medium text-gray-700">
                            <svg class="mr-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Active Status
                        </span>
                        <p class="text-xs text-gray-500 mt-1">
                            Make this test set active and immediately available to students
                        </p>
                    </div>
                </label>
            </div>

            <!-- Info Alert -->
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-blue-900">What's Next?</h4>
                        <p class="mt-1 text-sm text-blue-700">
                            After creating the test set, you'll be able to add questions to it. Each test set should contain questions specific to the selected IELTS section.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.test-sets.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create Test Set
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section_id');
        const sectionInfo = document.getElementById('section-info');
        const sectionName = document.getElementById('section-name');
        const sectionTime = document.getElementById('section-time');
        
        sectionSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                const name = selectedOption.text.split(' - ')[0];
                const time = selectedOption.getAttribute('data-time');
                
                sectionName.textContent = name;
                sectionTime.textContent = `(${time} minutes)`;
                sectionInfo.classList.remove('hidden');
            } else {
                sectionInfo.classList.add('hidden');
            }
        });
        
        // Trigger on page load if section is pre-selected
        if (sectionSelect.value) {
            sectionSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
