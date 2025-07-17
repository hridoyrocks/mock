<!-- resources/views/admin/questions/show.blade.php -->
<x-layout>
    <x-slot:title>View Question</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">Question Details</h1>
                        <p class="text-indigo-100 text-sm mt-1">{{ $question->testSet->title }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.questions.edit', $question) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.test-sets.show', $question->testSet) }}" 
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

    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                
                <!-- Question Information -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Question Information</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ str_replace('_', ' ', ucfirst($question->question_type)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">#{{ $question->order_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Part/Passage</dt>
                                <dd class="mt-1 text-sm text-gray-900">Part {{ $question->part_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Marks</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $question->marks }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Section</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($question->testSet->section->name) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $question->created_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Instructions -->
                @if($question->instructions)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Instructions</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700">{{ $question->instructions }}</p>
                    </div>
                </div>
                @endif

                <!-- Question Content -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Question Content</h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! $question->content !!}
                        </div>
                        
                        @if($question->media_path)
                            <div class="mt-4">
                                @if(in_array(pathinfo($question->media_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ Storage::url($question->media_path) }}" alt="Question Media" class="max-w-full h-auto rounded">
                                @else
                                    <audio controls class="w-full mt-4">
                                        <source src="{{ Storage::url($question->media_path) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Answer Options (for MCQ, True/False, etc.) -->
                @if($question->options->count() > 0)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Answer Options</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            @foreach($question->options as $index => $option)
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $option->is_correct ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-sm font-medium">
                                        {{ chr(65 + $index) }}
                                    </span>
                                    <span class="ml-3 text-gray-700">
                                        {{ $option->content }}
                                        @if($option->is_correct)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Correct Answer
                                            </span>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Fill-in-the-blank Answers -->
                @if($question->blanks->count() > 0)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-fill-drip mr-2"></i>Fill in the Blank Answers
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($question->blanks as $blank)
                                <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                                    <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-purple-100 text-purple-800 font-bold">
                                        {{ $blank->blank_number }}
                                    </span>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            Primary Answer: <span class="text-green-600">{{ $blank->correct_answer }}</span>
                                        </div>
                                        @if($blank->alternate_answers && count($blank->alternate_answers) > 0)
                                            <div class="text-sm text-gray-600 mt-1">
                                                Alternative Answers: 
                                                @foreach($blank->alternate_answers as $alt)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 mr-1">
                                                        {{ $alt }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Preview with answers -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Preview with Answers:</h4>
                            <div class="text-gray-700">
                                @php
                                    $previewContent = $question->content;
                                    foreach($question->blanks as $blank) {
                                        $replacement = '<span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium bg-green-100 text-green-700 border-b-2 border-green-500">' . $blank->correct_answer . '</span>';
                                        $previewContent = str_replace("[____{$blank->blank_number}____]", $replacement, $previewContent);
                                    }
                                @endphp
                                {!! $previewContent !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Special Type Answers (Matching, Form, Diagram) -->
                @if($question->matching_pairs)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                        <h3 class="text-lg font-medium text-gray-900">Matching Pairs</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="font-medium text-gray-700">Items</div>
                            <div class="font-medium text-gray-700">Matches</div>
                            @foreach($question->matching_pairs as $index => $pair)
                                <div class="p-3 bg-gray-50 rounded">{{ $pair['left'] }}</div>
                                <div class="p-3 bg-green-50 rounded text-green-700">
                                    {{ chr(65 + $index) }}. {{ $pair['right'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($question->form_structure)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <h3 class="text-lg font-medium text-gray-900">Form Structure</h3>
                    </div>
                    <div class="p-6">
                        <h4 class="font-medium text-gray-900 mb-4">{{ $question->form_structure['title'] ?? 'Form' }}</h4>
                        <div class="space-y-3">
                            @foreach($question->form_structure['fields'] ?? [] as $field)
                                <div class="flex items-center space-x-3">
                                    <span class="text-gray-600 w-1/3">{{ $field['label'] }}:</span>
                                    <span class="flex-1 p-2 bg-green-50 rounded text-green-700 font-medium">
                                        {{ $field['answer'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Audio Transcript -->
                @if($question->audio_transcript)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Audio Transcript</h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-gray-700">
                            {{ $question->audio_transcript }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.questions.edit', $question) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Question
                    </a>
                    
                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>