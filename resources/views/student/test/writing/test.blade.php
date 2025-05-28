<x-test-layout>
    <x-slot:title>Writing Test - IELTS Mock Test</x-slot>
    
    <div class="min-h-screen bg-gray-50">
        <div class="py-2 bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="{{ asset('images/ielts-logo.png') }}" alt="IELTS Logo" class="h-8 mr-3">
                        <h1 class="text-xl font-medium text-gray-900">{{ $testSet->title }}</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-100 rounded-md px-3 py-1">
                            <span class="text-sm text-gray-800">Time remaining: <span id="timer" class="font-medium">{{ $testSet->section->time_limit }}:00</span></span>
                        </div>
                        
                        <div class="text-sm">
                            <span id="autosave-indicator" class="text-green-500"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <form id="writing-form" action="{{ route('student.writing.submit', $attempt) }}" method="POST">
                        @csrf
                        
                        @php
                            $taskOneQuestion = $testSet->questions->where('question_type', 'essay')->where('order_number', 1)->first();
                            $taskTwoQuestion = $testSet->questions->where('question_type', 'essay')->where('order_number', 2)->first();
                        @endphp
                        
                        <div class="mb-10">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold">Task 1</h2>
                                <div class="text-sm text-gray-500">
                                    <span>Suggested time: 20 minutes</span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="mb-4">
                                    {!! $taskOneQuestion->content !!}
                                </div>
                                
                                @if($taskOneQuestion->media_path)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $taskOneQuestion->media_path) }}" alt="Task 1 Image" class="max-w-full h-auto">
                                    </div>
                                @endif
                                
                                <div class="text-sm text-gray-500">
                                    <p>You should write at least 150 words.</p>
                                </div>
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg">
                                <div class="border-b border-gray-300 px-3 py-2 flex justify-between items-center">
                                    <div class="text-sm">
                                        <span>Word count: <span id="word-count-1">0</span></span>
                                    </div>
                                </div>
                                
                                <textarea 
                                    id="writing-editor-{{ $taskOneQuestion->id }}" 
                                    name="answers[{{ $taskOneQuestion->id }}]" 
                                    class="w-full p-3 focus:outline-none min-h-[250px]" 
                                    placeholder="Write your answer here..."
                                >{{ old('answers.' . $taskOneQuestion->id, $attempt->answers->where('question_id', $taskOneQuestion->id)->first()->answer ?? '') }}</textarea>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold">Task 2</h2>
                                <div class="text-sm text-gray-500">
                                    <span>Suggested time: 40 minutes</span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="mb-4">
                                    {!! $taskTwoQuestion->content !!}
                                </div>
                                
                                <div class="text-sm text-gray-500">
                                    <p>You should write at least 250 words.</p>
                                </div>
                            </div>
                            
                            <div class="border border-gray-300 rounded-lg">
                                <div class="border-b border-gray-300 px-3 py-2 flex justify-between items-center">
                                    <div class="text-sm">
                                        <span>Word count: <span id="word-count-2">0</span></span>
                                    </div>
                                </div>
                                
                                <textarea 
                                    id="writing-editor-{{ $taskTwoQuestion->id }}" 
                                    name="answers[{{ $taskTwoQuestion->id }}]" 
                                    class="w-full p-3 focus:outline-none min-h-[350px]" 
                                    placeholder="Write your answer here..."
                                >{{ old('answers.' . $taskTwoQuestion->id, $attempt->answers->where('question_id', $taskTwoQuestion->id)->first()->answer ?? '') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center border-t pt-6 mt-8">
                            <a href="{{ route('student.writing.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Exit Test</a>
                            
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit Answers</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the timer
            window.timer.init({{ $testSet->section->time_limit }}, 'timer', 'writing-form');
            
            // Initialize the word counters
            const taskOneEditor = document.getElementById('writing-editor-{{ $taskOneQuestion->id }}');
            const taskTwoEditor = document.getElementById('writing-editor-{{ $taskTwoQuestion->id }}');
            const wordCount1 = document.getElementById('word-count-1');
            const wordCount2 = document.getElementById('word-count-2');
            
            function updateWordCount(text, element) {
                const words = text.trim().split(/\s+/).filter(word => word.length > 0);
                element.textContent = words.length;
            }
            
            taskOneEditor.addEventListener('input', function() {
                updateWordCount(this.value, wordCount1);
            });
            
            taskTwoEditor.addEventListener('input', function() {
                updateWordCount(this.value, wordCount2);
            });
            
            // Initial word count
            updateWordCount(taskOneEditor.value, wordCount1);
            updateWordCount(taskTwoEditor.value, wordCount2);
            
            // Initialize the autosave functionality
            window.writingTest.init(
                {{ $attempt->id }}, 
                {{ $taskOneQuestion->id }}, 
                '{{ route('student.writing.autosave', [$attempt->id, $taskOneQuestion->id]) }}'
            );
            
            window.writingTest.init(
                {{ $attempt->id }}, 
                {{ $taskTwoQuestion->id }}, 
                '{{ route('student.writing.autosave', [$attempt->id, $taskTwoQuestion->id]) }}'
            );
        });
    </script>
    @endpush
</x-test-layout>