{{-- resources/views/student/test/speaking/test.blade.php --}}
<x-test-layout>
    <x-slot:title>IELTS Speaking Test</x-slot>
    
    <x-slot:meta>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
    </x-slot:meta>
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        
        .ielts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .ielts-header-left {
            display: flex;
            align-items: center;
        }
        
        .user-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 20px;
            background-color: #212529;
            color: white;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .content-area {
            padding: 20px;
            padding-bottom: 100px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .info-section {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            margin-bottom: 30px;
            border-radius: 0 6px 6px 0;
        }
        
        .part-section {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .part-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
        }
        
        .part-prompt {
            background-color: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .recording-section {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 16px;
            background-color: #fefefe;
        }
        
        .recording-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .record-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background-color: #dc2626;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .record-btn:hover {
            background-color: #b91c1c;
        }
        
        .record-btn.recording {
            background-color: #ef4444;
            animation: pulse 1.5s infinite;
        }
        
        .stop-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background-color: #6b7280;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .stop-btn:hover {
            background-color: #4b5563;
        }
        
        .recording-status {
            font-size: 14px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .audio-player {
            width: 100%;
            margin-top: 15px;
        }
        
        .existing-recording {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 12px;
            margin-top: 15px;
        }
        
        .file-upload-section {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .file-upload-input {
            margin-top: 8px;
            margin-bottom: 8px;
        }
        
        .upload-btn {
            padding: 6px 12px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .upload-btn:hover {
            background-color: #2563eb;
        }
        
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            border-top: 1px solid #e5e7eb;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }
        
        .nav-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .part-nav {
            display: flex;
            gap: 10px;
        }
        
        .part-btn {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            color: #374151;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .part-btn.active {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .part-btn:hover:not(.active) {
            background-color: #f3f4f6;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .submit-btn {
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 24px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 16px;
        }
        
        .modal-message {
            font-size: 16px;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        
        .modal-button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 5px;
        }
        
        .modal-button:hover {
            background-color: #2563eb;
        }
        
        .modal-button.secondary {
            background-color: #6b7280;
        }
        
        .modal-button.secondary:hover {
            background-color: #4b5563;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @media (max-width: 768px) {
            .content-area {
                padding: 10px;
                padding-bottom: 100px;
            }
            
            .part-nav {
                display: none;
            }
            
            .recording-controls {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>

    <!-- IELTS Header -->
    <div class="ielts-header">
        <div class="ielts-header-left">
            <svg class="w-6 h-6 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-red-600 font-bold text-lg">Computer-delivered IELTS</span>
        </div>
        <div>
            <span class="text-red-600 font-bold text-lg">IELTS</span>
        </div>
    </div>

    <!-- User Info Bar WITH Integrated Timer -->
    <div class="user-bar">
        <div class="user-info">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ auth()->user()->name }} - BI {{ str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="user-controls">
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm no-nav">Help ?</button>
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm no-nav">Hide</button>
            
            {{-- Integrated Timer Component --}}
            <x-test-timer 
                :attempt="$attempt" 
                auto-submit-form-id="speaking-form"
                position="integrated"
                :warning-time="300"
                :danger-time="120"
            />
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <div class="info-section">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-blue-800 font-medium mb-1">IELTS Speaking Test Instructions</p>
                    <p class="text-blue-700 text-sm">
                        This is a simulated IELTS Speaking test. Each part has different time allocations. 
                        Use the record button to capture your responses. Ensure your microphone is working properly before starting.
                    </p>
                </div>
            </div>
        </div>
        
        <form id="speaking-form" action="{{ route('student.speaking.submit', $attempt) }}" method="POST">
            @csrf
            
            @foreach ($testSet->questions->sortBy('order_number') as $question)
                <div class="part-section" id="part-{{ $loop->iteration }}">
                    <div class="part-header">
                        <div>
                            <h2 class="text-xl font-semibold">Part {{ $loop->iteration }}</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                @if ($loop->iteration == 1)
                                    Suggested time: 1-2 minutes
                                @elseif ($loop->iteration == 2)
                                    Suggested time: 3-4 minutes (including preparation)
                                @else
                                    Suggested time: 4-5 minutes
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="part-prompt">
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($question->content)) !!}
                        </div>
                        
                        @if ($question->media_path)
                            <div class="mt-4">
                                <img src="{{ asset('storage/' . $question->media_path) }}" alt="Question Image" class="max-w-full h-auto border rounded">
                            </div>
                        @endif
                    </div>
                    
                    <div class="recording-section">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Your Response</h3>
                        
                        <div class="recording-controls">
                            <button type="button" id="record-button-{{ $question->id }}" class="record-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                Start Recording
                            </button>
                            
                            <button type="button" id="stop-button-{{ $question->id }}" class="stop-btn hidden">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                                </svg>
                                Stop Recording
                            </button>
                            
                            <span class="recording-status" id="recording-status-{{ $question->id }}"></span>
                        </div>
                        
                        <audio id="audio-player-{{ $question->id }}" controls class="audio-player hidden"></audio>
                        
                        @php
                            $answer = $attempt->answers->where('question_id', $question->id)->first();
                            $recordingExists = $answer && $answer->speakingRecording;
                        @endphp
                        
                        @if ($recordingExists)
                            <div class="existing-recording">
                                <p class="text-sm text-green-700 font-medium mb-2">✓ Existing recording:</p>
                                <audio src="{{ asset('storage/' . $answer->speakingRecording->file_path) }}" controls class="w-full"></audio>
                            </div>
                        @endif
                        
                        <div class="file-upload-section">
                            <p class="text-xs text-gray-600 mb-2">Alternatively, you can upload an audio file:</p>
                            <input type="file" id="file-upload-{{ $question->id }}" accept="audio/*" class="file-upload-input text-sm">
                            <br>
                            <button type="button" id="upload-button-{{ $question->id }}" class="upload-btn">
                                Upload File
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <button type="submit" id="submit-button" class="hidden">Submit</button>
        </form>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-left">
            <div class="part-nav">
                @foreach ($testSet->questions as $question)
                    <button type="button" class="part-btn {{ $loop->first ? 'active' : '' }}" data-part="{{ $loop->iteration }}">
                        Part {{ $loop->iteration }}
                    </button>
                @endforeach
            </div>
        </div>
        <div class="nav-right">
            <button type="button" id="submit-test-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md mr-3 text-sm font-medium submit-btn">
                Submit Test
            </button>
        </div>
    </div>

    <!-- Submit Modal -->
    <div id="submit-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-title" style="color: #059669;">Submit Test?</div>
            <div class="modal-message">
                Are you sure you want to submit your speaking test? You cannot change your recordings after submission.
                <br><br>
                <strong>Recorded Parts: <span id="recorded-count">0</span> / {{ $testSet->questions->count() }}</strong>
            </div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="modal-button" id="confirm-submit-btn">Yes, Submit</button>
                <button class="modal-button secondary" id="cancel-submit-btn">Cancel</button>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const partButtons = document.querySelectorAll('.part-btn');
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const submitButton = document.getElementById('submit-button');
        
        // Part navigation
        partButtons.forEach(button => {
            button.addEventListener('click', function() {
                partButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const partNumber = this.dataset.part;
                const partElement = document.getElementById(`part-${partNumber}`);
                
                if (partElement) {
                    partElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Initialize recording functionality for each question
        @foreach ($testSet->questions as $question)
            initializeRecording({{ $question->id }});
            initializeFileUpload({{ $question->id }});
        @endforeach
        
        // Recording initialization function
        function initializeRecording(questionId) {
            const recordButton = document.getElementById(`record-button-${questionId}`);
            const stopButton = document.getElementById(`stop-button-${questionId}`);
            const audioPlayer = document.getElementById(`audio-player-${questionId}`);
            const status = document.getElementById(`recording-status-${questionId}`);
            
            let mediaRecorder;
            let chunks = [];
            
            recordButton.addEventListener('click', async function() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    
                    mediaRecorder.ondataavailable = function(e) {
                        chunks.push(e.data);
                    };
                    
                    mediaRecorder.onstop = function() {
                        const blob = new Blob(chunks, { type: 'audio/webm' });
                        chunks = [];
                        
                        const audioURL = URL.createObjectURL(blob);
                        audioPlayer.src = audioURL;
                        audioPlayer.classList.remove('hidden');
                        
                        // Upload the recording
                        uploadRecording(questionId, blob, status);
                    };
                    
                    mediaRecorder.start();
                    
                    // Update UI
                    recordButton.classList.add('recording', 'hidden');
                    stopButton.classList.remove('hidden');
                    status.innerHTML = '<span style="color: #ef4444;">● Recording...</span>';
                    
                } catch (error) {
                    console.error('Error accessing microphone:', error);
                    status.innerHTML = '<span style="color: #ef4444;">Could not access microphone. Please check permissions.</span>';
                }
            });
            
            stopButton.addEventListener('click', function() {
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                    
                    // Stop all tracks
                    const tracks = mediaRecorder.stream.getTracks();
                    tracks.forEach(track => track.stop());
                    
                    // Update UI
                    recordButton.classList.remove('recording', 'hidden');
                    stopButton.classList.add('hidden');
                    status.innerHTML = '<span style="color: #10b981;">Processing...</span>';
                }
            });
        }
        
        // File upload initialization function
        function initializeFileUpload(questionId) {
            const fileUpload = document.getElementById(`file-upload-${questionId}`);
            const uploadButton = document.getElementById(`upload-button-${questionId}`);
            const status = document.getElementById(`recording-status-${questionId}`);
            
            uploadButton.addEventListener('click', function() {
                if (fileUpload.files.length === 0) {
                    status.innerHTML = '<span style="color: #ef4444;">Please select a file first.</span>';
                    return;
                }
                
                const file = fileUpload.files[0];
                uploadRecording(questionId, file, status);
            });
        }
        
        // Upload recording function
        function uploadRecording(questionId, blob, statusElement) {
            const formData = new FormData();
            formData.append('recording', blob, 'recording.webm');
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            statusElement.innerHTML = '<span style="color: #f59e0b;">Uploading...</span>';
            
            fetch(`{{ route('student.speaking.record', [$attempt->id, '__QUESTION_ID__']) }}`.replace('__QUESTION_ID__', questionId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusElement.innerHTML = '<span style="color: #10b981;">✓ Recording saved</span>';
                    setTimeout(() => {
                        statusElement.innerHTML = '';
                    }, 3000);
                } else {
                    statusElement.innerHTML = '<span style="color: #ef4444;">Error saving recording</span>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusElement.innerHTML = '<span style="color: #ef4444;">Error saving recording</span>';
            });
        }
        
        // Submit functionality
        submitTestBtn.addEventListener('click', function() {
            // Count recorded parts
            const existingRecordings = document.querySelectorAll('.existing-recording').length;
            document.getElementById('recorded-count').textContent = existingRecordings;
            
            submitModal.style.display = 'flex';
        });
        
        confirmSubmitBtn.addEventListener('click', function() {
            if (window.UniversalTimer) {
                window.UniversalTimer.stop();
            }
            submitButton.click();
        });
        
        cancelSubmitBtn.addEventListener('click', function() {
            submitModal.style.display = 'none';
        });
        
        // Scroll spy for part navigation
        window.addEventListener('scroll', function() {
            const parts = document.querySelectorAll('.part-section');
            let activePart = 1;
            
            parts.forEach((part, index) => {
                const rect = part.getBoundingClientRect();
                if (rect.top <= 150 && rect.bottom > 150) {
                    activePart = index + 1;
                }
            });
            
            partButtons.forEach(btn => btn.classList.remove('active'));
            const activeButton = document.querySelector(`[data-part="${activePart}"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }
        });
    });
    </script>
    @endpush
</x-test-layout>