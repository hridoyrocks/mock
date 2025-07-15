<x-admin-layout>
    <x-slot:title>Select Test Set - Add Question</x-slot>
    
    <x-slot:header>
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Select a Test Set
            </h2>
            <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                ← Back to Questions
            </a>
        </div>
    </x-slot:header>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <p class="text-gray-600 mb-6">Choose a test set where you want to add questions:</p>
                
                <!-- Section Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        @foreach($sections as $section)
                            <button type="button" 
                                    class="section-tab py-2 px-1 border-b-2 font-medium text-sm {{ $loop->first ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                    data-section="{{ $section->name }}">
                                <span class="inline-flex items-center">
                                    @switch($section->name)
                                        @case('listening') 🎧 @break
                                        @case('reading') 📖 @break
                                        @case('writing') ✍️ @break
                                        @case('speaking') 🎤 @break
                                    @endswitch
                                    {{ ucfirst($section->name) }}
                                </span>
                            </button>
                        @endforeach
                    </nav>
                </div>
                
                <!-- Test Sets Grid -->
                <div id="test-sets-container">
                    @foreach($sections as $section)
                        <div class="section-content {{ !$loop->first ? 'hidden' : '' }}" data-section="{{ $section->name }}">
                            @php
                                $sectionTestSets = $testSets->where('section_id', $section->id);
                            @endphp
                            
                            @if($sectionTestSets->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($sectionTestSets as $testSet)
                                        <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" 
                                           class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-colors">
                                            <h3 class="font-medium text-gray-900 mb-2">{{ $testSet->title }}</h3>
                                            <div class="text-sm text-gray-600">
                                                <p>Questions: {{ $testSet->questions->count() }}</p>
                                                <p>Status: 
                                                    @if($testSet->active)
                                                        <span class="text-green-600">Active</span>
                                                    @else
                                                        <span class="text-gray-500">Inactive</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No test sets</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a test set for {{ $section->name }}.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.test-sets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            New Test Set
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.section-tab');
            const contents = document.querySelectorAll('.section-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const section = this.dataset.section;
                    
                    // Update active tab
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-blue-500', 'text-blue-600');
                    
                    // Show corresponding content
                    contents.forEach(content => {
                        if (content.dataset.section === section) {
                            content.classList.remove('hidden');
                        } else {
                            content.classList.add('hidden');
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-layout>