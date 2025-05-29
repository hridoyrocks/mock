{{-- resources/views/student/test/reading/test.blade.php --}}
{{-- BACKUP নিন এই FILE এর --}}

<x-test-layout>
    <x-slot:title>Reading Test - IELTS Mock Test</x-slot>
    
    {{-- ✅ ADD: Include Universal Timer Component --}}
    <x-test-timer 
        :attempt="$attempt" 
        auto-submit-form-id="reading-form"
        position="top-right"
        :warning-time="600"
        :danger-time="300"
    />
    
    <div class="min-h-screen bg-gray-50">
        <div class="py-2 bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="{{ asset('images/ielts-logo.png') }}" alt="IELTS Logo" class="h-8 mr-3">
                        <h1 class="text-xl font-medium text-gray-900">{{ $testSet->title }}</h1>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{-- ✅ CHANGE: Form ID to match timer config --}}
            <form id="reading-form" action="{{ route('student.reading.submit', $attempt) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Reading Passage -->
                    <div class="bg-white shadow-sm rounded-lg p-6 max-h-[80vh] overflow-y-auto">
                        <h2 class="text-lg font-semibold mb-4">Passage</h2>
                        
                        @php
                            $passage = $testSet->questions->where('question_type', 'passage')->first();
                        @endphp
                        
                        @if ($passage)
                            <div class="prose prose-sm max-w-none">
                                {!! $passage->content !!}
                            </div>
                            
                            @if ($passage->media_path)
                                <div class="mt-4">
                                    <img src="{{ asset('storage/' . $passage->media_path) }}" alt="Passage Image" class="max-w-full h-auto">
                                </div>
                            @endif
                        @else
                            <div class="bg-yellow-50 p-4 rounded-md">
                                <p class="text-yellow-700">No passage content found for this test.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Questions -->
                    <div class="bg-white shadow-sm rounded-lg p-6 max-h-[80vh] overflow-y-auto">
                        <h2 class="text-lg font-semibold mb-4">Questions</h2>
                        
                        @php
                            $questions = $testSet->questions->where('question_type', '!=', 'passage');
                        @endphp
                        
                        @foreach ($questions as $question)
                            <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                                <div class="mb-3">
                                    <span class="font-medium">{{ $question->order_number }}.</span> {!! $question->content !!}
                                </div>
                                
                                @if ($question->media_path)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $question->media_path) }}" alt="Question Image" class="max-w-full h-auto">
                                    </div>
                                @endif
                                
                                <div class="ml-6">
                                    @switch($question->question_type)
                                        @case('multiple_choice')
                                            @foreach ($question->options as $option)
                                                <div class="flex items-center mb-2">
                                                    <input type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                    <label for="option-{{ $option->id }}" class="ml-2 text-sm font-medium text-gray-900">
                                                        {{ $option->content }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @break
                                            
                                        @case('true_false')
                                            @foreach ($question->options as $option)
                                                <div class="flex items-center mb-2">
                                                    <input type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                    <label for="option-{{ $option->id }}" class="ml-2 text-sm font-medium text-gray-900">
                                                        {{ $option->content }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @break
                                            
                                        @case('fill_blank')
                                            <div class="mb-2">
                                                <input type="text" name="answers[{{ $question->id }}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Your answer">
                                            </div>
                                            @break
                                            
                                        @case('matching')
                                            <div class="mb-2">
                                                <select name="answers[{{ $question->id }}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                    <option value="">Select your answer</option>
                                                    @foreach ($question->options as $option)
                                                        <option value="{{ $option->id }}">{{ $option->content }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @break
                                            
                                        @default
                                            <div class="mb-2">
                                                <input type="text" name="answers[{{ $question->id }}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Your answer">
                                            </div>
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('student.reading.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" onclick="return confirm('Are you sure you want to exit? Your progress will not be saved.')">
                        Exit Test
                    </a>
                    
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Submit Answers
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    
</x-test-layout>