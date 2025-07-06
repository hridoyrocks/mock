

<x-layout>
    <x-slot:title>{{ $testSet->title }} - Test Set Details</x-slot>
    
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">{{ $testSet->title }}</h1>
                        <p class="text-indigo-100 text-sm mt-1">
                            @switch($testSet->section->name)
                                @case('listening')  Listening Section @break
                                @case('reading')  Reading Section @break
                                @case('writing')  Writing Section @break
                                @case('speaking')  Speaking Section @break
                                @default {{ ucfirst($testSet->section->name) }} Section
                            @endswitch
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        @if($testSet->section->name === 'reading')
                            @php
                                $hasPassage = $testSet->questions()->where('question_type', 'passage')->exists();
                            @endphp
                            
                            @if(!$hasPassage)
                                <!-- Step 1: Add Passage -->
                                <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                     Add Reading Passage
                                </a>
                            @else
                                <!-- Step 2: Add Questions -->
                                <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                     Add Questions
                                </a>
                                
                                <!-- Edit Passage Button -->
                                @php $passage = $testSet->questions()->where('question_type', 'passage')->first(); @endphp
                                @if($passage)
                                <a href="{{ route('admin.questions.edit', $passage) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                     Edit Passage
                                </a>
                                @endif
                            @endif
                        @else
                            <!-- Regular Add Question Button for other sections -->
                            <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Question
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.test-sets.edit', $testSet) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Test Set
                        </a>
                        
                        <a href="{{ route('admin.test-sets.index') }}" 
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
    </div>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative" role="alert">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative" role="alert">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            <!-- Reading Section Special Layout -->
            @if($testSet->section->name === 'reading')
                @php
                    $passage = $testSet->questions()->where('question_type', 'passage')->first();
                    $questions = $testSet->questions()->where('question_type', '!=', 'passage')->orderBy('order_number')->get();
                @endphp
                
                <!-- Step Progress for Reading -->
                <div class="mb-8">
                    <div class="flex items-center">
                        <div class="flex items-center {{ $passage ? 'text-green-600' : 'text-blue-600' }}">
                            <div class="flex items-center justify-center w-8 h-8 {{ $passage ? 'bg-green-600' : 'bg-blue-600' }} text-white rounded-full text-sm font-medium">
                                @if($passage)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    1
                                @endif
                            </div>
                            <span class="ml-2 text-sm font-medium">{{ $passage ? 'Passage Added' : 'Add Passage' }}</span>
                        </div>
                        <div class="flex-1 mx-4">
                            <div class="h-1 {{ $passage ? 'bg-green-600' : 'bg-gray-200' }} rounded"></div>
                        </div>
                        <div class="flex items-center {{ $passage ? 'text-blue-600' : 'text-gray-400' }}">
                            <div class="flex items-center justify-center w-8 h-8 {{ $passage ? 'bg-blue-600' : 'bg-gray-200' }} {{ $passage ? 'text-white' : 'text-gray-500' }} rounded-full text-sm font-medium">2</div>
                            <span class="ml-2 text-sm font-medium">Add Questions</span>
                        </div>
                    </div>
                </div>
                
                <!-- Two Column Layout for Reading -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left Column: Passage -->
                    <div class="lg:col-span-1">
                        @if($passage)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-4">
                                <div class="px-4 py-3 border-b border-gray-200 bg-green-50">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            {{ $passage->instructions ?? 'Reading Passage' }}
                                        </h3>
                                        <span class="text-xs text-green-600 font-medium">✓ Added</span>
                                    </div>
                                </div>
                                <div class="p-4 max-h-96 overflow-y-auto">
                                    <div class="prose prose-sm max-w-none text-sm leading-relaxed">
                                        @php
                                            $processedContent = $passage->passage_text ?? $passage->content;
                                            // Highlight markers for display
                                            $processedContent = preg_replace_callback(
                                                '/\{\{(Q\d+)\}\}(.*?)\{\{\\1\}\}/s',
                                                function($matches) {
                                                    $markerId = $matches[1];
                                                    $text = $matches[2];
                                                    return '<span class="bg-yellow-100 px-1 rounded font-medium" title="Question Location: ' . $markerId . '">' . $text . '</span>';
                                                },
                                                $processedContent
                                            );
                                        @endphp
                                        {!! $processedContent !!}
                                    </div>
                                </div>
                                
                                <!-- Available Markers -->
                                @php
                                    $availableMarkers = [];
                                    $passageContent = $passage->passage_text ?? $passage->content;
                                    preg_match_all('/\{\{(Q\d+)\}\}/', $passageContent, $matches);
                                    $availableMarkers = array_unique($matches[1] ?? []);
                                @endphp
                                @if(count($availableMarkers) > 0)
                                <div class="px-4 py-3 border-t border-gray-200 bg-blue-50">
                                    <h4 class="text-xs font-medium text-blue-900 mb-2">📍 Available Answer Locations:</h4>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($availableMarkers as $marker)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-300">
                                            {{ $marker }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <!-- Passage Not Added Yet -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                <div class="p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Reading Passage</h3>
                                    <p class="mt-1 text-sm text-gray-500">Start by adding a reading passage for this test set.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            📄 Add Reading Passage
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Questions -->
                    <div class="lg:col-span-2">
                        @if($questions->count() > 0)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            📝 Questions ({{ $questions->count() }})
                                        </h3>
                                        @if($passage)
                                        <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add Question
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="divide-y divide-gray-200">
                                    @foreach ($questions as $question)
                                        <div class="p-6 hover:bg-gray-50 transition-colors">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <span class="flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-600 rounded-full text-xs font-medium">
                                                            {{ $question->order_number }}
                                                        </span>
                                                        
                                                        <!-- Question Type Badge -->
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                            @switch($question->question_type)
                                                                @case('multiple_choice') bg-blue-100 text-blue-800 @break
                                                                @case('true_false') bg-green-100 text-green-800 @break
                                                                @case('yes_no') bg-green-100 text-green-800 @break
                                                                @case('matching_headings') bg-yellow-100 text-yellow-800 @break
                                                                @case('sentence_completion') bg-purple-100 text-purple-800 @break
                                                                @case('short_answer') bg-pink-100 text-pink-800 @break
                                                                @default bg-gray-100 text-gray-800
                                                            @endswitch
                                                        ">
                                                            {{ ucfirst(str_replace(['_', 'matching'], [' ', 'Match'], $question->question_type)) }}
                                                        </span>
                                                        
                                                        <!-- Marker Reference -->
                                                        @if($question->marker_id)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            🔗 {{ $question->marker_id }}
                                                        </span>
                                                        @endif
                                                        
                                                        <!-- Difficulty -->
                                                        @if($question->difficulty_level)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                            @switch($question->difficulty_level)
                                                                @case('easy') bg-green-100 text-green-800 @break
                                                                @case('medium') bg-yellow-100 text-yellow-800 @break
                                                                @case('hard') bg-red-100 text-red-800 @break
                                                            @endswitch
                                                        ">
                                                            @switch($question->difficulty_level)
                                                                @case('easy') 🟢 Easy @break
                                                                @case('medium') 🟡 Medium @break
                                                                @case('hard') 🔴 Hard @break
                                                            @endswitch
                                                        </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="text-sm text-gray-900 mb-2">
                                                        {{ Str::limit(strip_tags($question->content), 100) }}
                                                    </div>
                                                    
                                                    @if($question->instructions)
                                                    <div class="text-xs text-gray-500 italic mb-2">
                                                        {{ Str::limit($question->instructions, 80) }}
                                                    </div>
                                                    @endif
                                                    
                                                    <!-- Options Preview -->
                                                    @if($question->options->count() > 0)
                                                    <div class="text-xs text-gray-600">
                                                        <span class="font-medium">Options:</span>
                                                        @foreach($question->options->take(2) as $option)
                                                            {{ chr(65 + $loop->index) }}. {{ Str::limit($option->content, 25) }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                        @if($question->options->count() > 2)
                                                            <span class="text-gray-400">... +{{ $question->options->count() - 2 }} more</span>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex items-center space-x-2 ml-4">
                                                    <a href="{{ route('admin.questions.show', $question) }}" 
                                                       class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('admin.questions.edit', $question) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- No Questions Yet -->
                            @if($passage)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                <div class="p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Questions Added</h3>
                                    <p class="mt-1 text-sm text-gray-500">Start adding questions based on your reading passage.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            ➕ Add First Question
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
                
            @else
                <!-- Regular Layout for Other Sections -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                Questions ({{ $testSet->questions->count() }})
                            </h3>
                        </div>
                        
                        @if($testSet->questions->count() > 0)
                            <div class="space-y-4">
                                @foreach ($testSet->questions->sortBy('order_number') as $question)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full text-sm font-medium">
                                                {{ $question->order_number }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit(strip_tags($question->content), 80) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                                    @if($question->options->count() > 0) • {{ $question->options->count() }} options @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.questions.show', $question) }}" class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No questions found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first question.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Question
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Statistics Summary -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Questions</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        @if($testSet->section->name === 'reading')
                                            {{ $testSet->questions()->where('question_type', '!=', 'passage')->count() }}
                                        @else
                                            {{ $testSet->questions->count() }}
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Multiple Choice</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $testSet->questions->where('question_type', 'multiple_choice')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">With Options</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $testSet->questions()->has('options')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Status</dt>
                                    <dd class="text-lg font-medium {{ $testSet->active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $testSet->active ? 'Active' : 'Inactive' }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>