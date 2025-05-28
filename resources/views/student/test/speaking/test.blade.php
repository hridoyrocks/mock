<x-layout>
    <x-slot:title>Speaking Test - IELTS Mock Test</x-slot>
    
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
                            <span id="upload-indicator" class="text-green-500"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                This is a simulated IELTS Speaking test. Each question will have a preparation time and a speaking time. Use the record button to capture your response. Make sure your microphone is working properly.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form id="speaking-form" action="{{ route('student.speaking.submit', $attempt) }}" method="POST">
                @csrf
                
                <div class="space-y-8">
                    @foreach ($testSet->questions as $question)
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold mb-3">Part {{ $loop->iteration }}</h2>
                                
                                <div class="mb-6">
                                    <div class="prose prose-sm max-w-none">
                                        {!! $question->content !!}
                                    </div>
                                    
                                    @if ($question->media_path)
                                        <div class="mt-4">
                                            <img src="{{ asset('storage/' . $question->media_path) }}" alt="Question Image" class="max-w-full h-auto">
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mb-4">
                                    <div class="border border-gray-200 rounded-md p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-medium text-gray-700">Your Response</h3>
                                            
                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Suggested time: 
                                                    @if ($loop->iteration == 1)
                                                        1-2 minutes
                                                    @elseif ($loop->iteration == 2)
                                                        3-4 minutes
                                                    @else
                                                        4-5 minutes
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div class="flex space-x-2">
                                                <button type="button" id="record-button-{{ $question->id }}" class="flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                                    </svg>
                                                    Record
                                                </button>
                                                
                                                <button type="button" id="stop-button-{{ $question->id }}" class="hidden flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                                                    </svg>
                                                    Stop
                                                </button>
                                                
                                                <span class="inline-flex text-sm text-gray-500 items-center" id="recording-status-{{ $question->id }}"></span>
                                            </div>
                                            
                                            <div>
                                                <audio id="audio-player-{{ $question->id }}" controls class="w-full hidden"></audio>
                                                
                                                @php
                                                    $answer = $attempt->answers->where('question_id', $question->id)->first();
                                                    $recordingExists = $answer && $answer->speakingRecording;
                                                @endphp
                                                
                                                @if ($recordingExists)
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-600 mb-1">Existing recording:</p>
                                                        <audio src="{{ asset('storage/' . $answer->speakingRecording->file_path) }}" controls class="w-full"></audio>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="text-xs text-gray-500">
                                                <p>Alternatively, you can upload an audio file:</p>
                                                <input type="file" id="file-upload-{{ $question->id }}" accept="audio/*" class="mt-1 block w-full text-sm text-gray-500
                                                    file:mr-4 file:py-2 file:px-4
                                                    file:rounded-md file:border-0
                                                    file:text-sm file:font-medium
                                                    file:bg-blue-50 file:text-blue-700
                                                    hover:file:bg-blue-100">
                                                <button type="button" id="upload-button-{{ $question->id }}" class="mt-2 text-xs text-blue-600 hover:text-blue-800">
                                                    Upload this file
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('student.speaking.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" onclick="return confirm('Are you sure you want to exit? Your progress will not be saved.')">
                        Exit Test
                    </a>
                    
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Submit Test
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the timer
            window.timer.init({{ $testSet->section->time_limit }}, 'timer', 'speaking-form');
            
            // Initialize the speaking test functionality for each question
            @foreach ($testSet->questions as $question)
                window.speakingTest.init(
                    '{{ $question->id }}',
                    '{{ route('student.speaking.record', [$attempt->id, $question->id]) }}'
                );
                
                // File upload handling
                const fileUpload = document.getElementById('file-upload-{{ $question->id }}');
                const uploadButton = document.getElementById('upload-button-{{ $question->id }}');
                
                uploadButton.addEventListener('click', function() {
                    if (fileUpload.files.length === 0) {
                        alert('Please select a file first.');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('recording', fileUpload.files[0]);
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch('{{ route('student.speaking.record', [$attempt->id, $question->id]) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success indicator
                            const indicator = document.getElementById('upload-indicator');
                            if (indicator) {
                                indicator.textContent = 'Recording uploaded';
                                indicator.classList.remove('text-red-500');
                                indicator.classList.add('text-green-500');
                                
                                setTimeout(() => {
                                    indicator.textContent = '';
                                }, 2000);
                            }
                            
                            // Refresh the page to show the uploaded recording
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Show error indicator
                        const indicator = document.getElementById('upload-indicator');
                        if (indicator) {
                            indicator.textContent = 'Error uploading recording';
                            indicator.classList.remove('text-green-500');
                            indicator.classList.add('text-red-500');
                        }
                    });
                });
            @endforeach
        });
    </script>
    @endpush
</x-layout>