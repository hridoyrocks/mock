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
        
        /* Progress Bar Styles */
        .progress-container {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .progress-bar {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .progress-segment {
            flex: 1;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            position: relative;
            overflow: hidden;
        }
        
        .progress-segment.active {
            background: #3b82f6;
        }
        
        .progress-segment.completed {
            background: #10b981;
        }
        
        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #6b7280;
        }
        
        .progress-label.active {
            color: #3b82f6;
            font-weight: 600;
        }
        
        .progress-label.completed {
            color: #10b981;
        }
        
        /* Progressive Question Card Styles */
        .question-card-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .question-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            padding: 32px;
            margin-bottom: 24px;
            position: relative;
            transition: all 0.3s ease;
            min-height: 450px;
            display: none;
        }
        
        .question-card.active {
            display: block;
            animation: slideIn 0.5s ease-out;
        }
        
        .question-card.reading-phase {
            border: 2px solid #3B82F6;
            background: #EBF5FF;
        }
        
        .question-card.recording-phase {
            border: 2px solid #EF4444;
            background: #FEF2F2;
        }
        
        .question-card.completed {
            border: 2px solid #10B981;
            background: #F0FDF4;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid rgba(0,0,0,0.1);
        }
        
        .card-phase-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 24px;
        }
        
        .phase-icon {
            font-size: 24px;
        }
        
        .question-display {
            text-align: center;
            margin: 40px 0;
        }
        
        .question-text {
            font-size: 24px;
            line-height: 1.5;
            color: #1F2937;
            margin-bottom: 32px;
            font-weight: 500;
        }
        
        /* Timer Displays */
        .read-timer {
            margin: 32px auto;
            text-align: center;
        }
        
        .timer-circle {
            width: 120px;
            height: 120px;
            margin: 0 auto 16px;
            position: relative;
        }
        
        .timer-circle svg {
            transform: rotate(-90deg);
        }
        
        .timer-circle-bg {
            fill: none;
            stroke: #E5E7EB;
            stroke-width: 8;
        }
        
        .timer-circle-progress {
            fill: none;
            stroke: #3B82F6;
            stroke-width: 8;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.5s ease;
        }
        
        .timer-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
            font-weight: bold;
            color: #3B82F6;
        }
        
        .timer-label {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 8px;
        }
        
        /* Recording Controls */
        .recording-controls {
            text-align: center;
            margin-top: 32px;
        }
        
        .recording-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .recording-dot {
            width: 12px;
            height: 12px;
            background: #EF4444;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.2); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .volume-meter {
            width: 200px;
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        
        .volume-level {
            height: 100%;
            background: #10B981;
            transition: width 0.1s ease;
            border-radius: 4px;
        }
        
        .recording-time {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-top: 16px;
        }
        
        /* Action Buttons */
        .action-button {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 160px;
        }
        
        .action-button.primary {
            background: #3B82F6;
            color: white;
        }
        
        .action-button.primary:hover {
            background: #2563EB;
            transform: translateY(-1px);
        }
        
        .action-button.disabled {
            background: #9CA3AF;
            cursor: not-allowed;
        }
        
        /* Tips Section */
        .tips-section {
            background: #F9FAFB;
            border-radius: 8px;
            padding: 16px;
            margin-top: 24px;
        }
        
        .tips-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #6B7280;
            margin-bottom: 8px;
        }
        
        .tips-content {
            font-size: 14px;
            color: #6B7280;
            line-height: 1.5;
        }
        
        /* Part 2 Cue Card */
        .cue-card {
            background: #FFFBEB;
            border: 2px solid #F59E0B;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            text-align: left;
        }
        
        .cue-card-title {
            font-weight: 600;
            color: #92400E;
            margin-bottom: 16px;
        }
        
        .cue-card-points {
            list-style: none;
            padding: 0;
        }
        
        .cue-card-points li {
            padding: 8px 0;
            padding-left: 24px;
            position: relative;
            color: #78350F;
        }
        
        .cue-card-points li:before {
            content: "•";
            position: absolute;
            left: 8px;
            color: #F59E0B;
            font-weight: bold;
        }
        
        /* Progress Dots */
        .progress-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }
        
        .progress-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #E5E7EB;
            transition: all 0.3s ease;
        }
        
        .progress-dot.completed {
            background: #10B981;
        }
        
        .progress-dot.active {
            background: #3B82F6;
            transform: scale(1.2);
        }
        
        /* Existing styles continue... */
        .info-section {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            margin-bottom: 30px;
            border-radius: 0 6px 6px 0;
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
        
        @media (max-width: 768px) {
            .content-area {
                padding: 10px;
                padding-bottom: 100px;
            }
            
            .question-text {
                font-size: 20px;
            }
            
            .timer-circle {
                width: 100px;
                height: 100px;
            }
            
            .timer-text {
                font-size: 28px;
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
                        This test follows the progressive card system. Read each question carefully during the reading time, then record your answer when prompted.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-segment completed" id="progress-part-1"></div>
                <div class="progress-segment" id="progress-part-2"></div>
                <div class="progress-segment" id="progress-part-3"></div>
            </div>
            <div class="progress-labels">
                <span class="progress-label completed" id="label-part-1">Part 1</span>
                <span class="progress-label" id="label-part-2">Part 2</span>
                <span class="progress-label" id="label-part-3">Part 3</span>
            </div>
        </div>
        
        <form id="speaking-form" action="{{ route('student.speaking.submit', $attempt) }}" method="POST">
            @csrf
            
            <div class="question-card-container">
                @foreach ($testSet->questions->sortBy('order_number') as $index => $question)
                    <!-- Progressive Question Card -->
                    <div class="question-card reading-phase {{ $index === 0 ? 'active' : '' }}" 
                         id="card-{{ $question->id }}"
                         data-question-id="{{ $question->id }}"
                         data-question-index="{{ $index }}"
                         data-part="{{ $loop->iteration }}">
                        
                        <!-- Card Header -->
                        <div class="card-header">
                            <h3 class="card-title">
                                Part {{ $loop->iteration }}
                                @if($loop->iteration == 1)
                                    - Introduction & Interview
                                @elseif($loop->iteration == 2)
                                    - Individual Long Turn
                                @else
                                    - Two-way Discussion
                                @endif
                            </h3>
                            <span class="question-counter">
                                Question {{ $index + 1 }} of {{ $testSet->questions->count() }}
                            </span>
                        </div>

                        <!-- Phase Indicator -->
                        <div class="card-phase-indicator" id="phase-indicator-{{ $question->id }}">
                            <span class="phase-icon">📖</span>
                            <span class="phase-text">Read the question carefully</span>
                        </div>

                        <!-- Question Display -->
                        <div class="question-display">
                            <div class="question-text">
                                {!! nl2br(e($question->content)) !!}
                            </div>

                            <!-- Part 2 Cue Card Points -->
                            @if($loop->iteration == 2 && $question->form_structure)
                                <div class="cue-card">
                                    <div class="cue-card-title">You should say:</div>
                                    <ul class="cue-card-points">
                                        @foreach($question->form_structure['fields'] ?? [] as $point)
                                            <li>{{ $point['label'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Read Timer -->
                        <div class="read-timer" id="read-timer-{{ $question->id }}">
                            <div class="timer-label">
                                @if($loop->iteration == 2)
                                    Preparation time
                                @else
                                    Reading time
                                @endif
                            </div>
                            <div class="timer-circle">
                                <svg width="120" height="120">
                                    <circle cx="60" cy="60" r="54" class="timer-circle-bg"></circle>
                                    <circle cx="60" cy="60" r="54" class="timer-circle-progress"
                                            id="timer-progress-{{ $question->id }}"
                                            stroke-dasharray="339.292"
                                            stroke-dashoffset="0"></circle>
                                </svg>
                                <div class="timer-text" id="timer-text-{{ $question->id }}">
                                    {{ $question->read_time ?? 5 }}
                                </div>
                            </div>
                            <div class="timer-info">
                                @if($loop->iteration == 2)
                                    You can start speaking when ready
                                @else
                                    Recording will start automatically
                                @endif
                            </div>
                        </div>

                        <!-- Recording Controls (hidden initially) -->
                        <div class="recording-controls" id="recording-controls-{{ $question->id }}" style="display: none;">
                            <div class="recording-indicator">
                                <div class="recording-dot"></div>
                                <span>Recording in progress</span>
                                <div class="volume-meter">
                                    <div class="volume-level" id="volume-level-{{ $question->id }}"></div>
                                </div>
                            </div>
                            <div class="recording-time" id="recording-time-{{ $question->id }}">00:00</div>
                            
                            <div class="mt-4">
                                <button type="button" 
                                        class="action-button primary disabled" 
                                        id="stop-btn-{{ $question->id }}"
                                        onclick="stopAndNext({{ $question->id }})"
                                        disabled>
                                    Stop & Next Question
                                </button>
                            </div>
                        </div>

                        <!-- Audio Player (for reviewing) -->
                        <audio id="audio-player-{{ $question->id }}" controls class="audio-player hidden" style="width: 100%; margin-top: 20px;"></audio>

                        <!-- Tips Section -->
                        @if($question->speaking_tips)
                            <div class="tips-section">
                                <div class="tips-header">
                                    <span>💡</span>
                                    <span>Tips</span>
                                </div>
                                <div class="tips-content">
                                    {{ $question->speaking_tips }}
                                </div>
                            </div>
                        @endif

                        <!-- Progress Dots -->
                        <div class="progress-dots">
                            @foreach($testSet->questions as $q)
                                <div class="progress-dot {{ $loop->index < $index ? 'completed' : ($loop->index == $index ? 'active' : '') }}"></div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <button type="submit" id="submit-button" class="hidden">Submit</button>
        </form>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-left">
            <span id="current-part-display" style="font-weight: 600;">Part 1</span>
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
                <strong>Recorded: <span id="recorded-count">0</span> / {{ $testSet->questions->count() }}</strong>
            </div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="modal-button" id="confirm-submit-btn">Yes, Submit</button>
                <button class="modal-button secondary" id="cancel-submit-btn">Cancel</button>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
    // Progressive Card System
    let currentQuestionIndex = 0;
    const questions = @json($testSet->questions->values());
    let mediaRecorders = {};
    let audioChunks = {};
    let timers = {};
    let recordingStartTimes = {};
    let recordingsCompleted = 0;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize first question
        initializeQuestion(0);
        
        // Submit functionality
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const submitButton = document.getElementById('submit-button');
        
        submitTestBtn.addEventListener('click', function() {
            document.getElementById('recorded-count').textContent = recordingsCompleted;
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
    });

    // Initialize a question
    function initializeQuestion(index) {
        if (index >= questions.length) {
            // All questions completed
            showCompletionScreen();
            return;
        }

        const question = questions[index];
        const questionId = question.id;
        
        // Update progress
        updateProgress(index);
        
        // Start reading phase
        startReadingPhase(questionId);
    }

    // Update progress indicators
    function updateProgress(index) {
        const question = questions[index];
        const partNumber = Math.ceil((index + 1) / Math.ceil(questions.length / 3));
        
        // Update progress bar
        document.querySelectorAll('.progress-segment').forEach((segment, i) => {
            if (i < partNumber - 1) {
                segment.classList.add('completed');
                segment.classList.remove('active');
            } else if (i === partNumber - 1) {
                segment.classList.add('active');
                segment.classList.remove('completed');
            } else {
                segment.classList.remove('active', 'completed');
            }
        });
        
        // Update labels
        document.querySelectorAll('.progress-label').forEach((label, i) => {
            if (i < partNumber - 1) {
                label.classList.add('completed');
                label.classList.remove('active');
            } else if (i === partNumber - 1) {
                label.classList.add('active');
                label.classList.remove('completed');
            } else {
                label.classList.remove('active', 'completed');
            }
        });
        
        // Update current part display
        document.getElementById('current-part-display').textContent = `Part ${partNumber}`;
    }

    // Start reading phase with timer
    function startReadingPhase(questionId) {
        const card = document.getElementById(`card-${questionId}`);
        const readTime = questions[currentQuestionIndex].read_time || 5;
        let timeLeft = readTime;
        
        // Update UI
        card.className = 'question-card reading-phase active';
        document.getElementById(`phase-indicator-${questionId}`).innerHTML = `
            <span class="phase-icon">📖</span>
            <span class="phase-text">Read the question carefully</span>
        `;
        
        // Show read timer
        document.getElementById(`read-timer-${questionId}`).style.display = 'block';
        document.getElementById(`recording-controls-${questionId}`).style.display = 'none';
        
        // Start countdown
        const timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay(questionId, timeLeft, readTime);
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                // Auto progress unless it's Part 2 with preparation time
                if (questions[currentQuestionIndex].auto_progress !== false) {
                    startRecordingPhase(questionId);
                } else {
                    // For Part 2, show start button
                    showStartRecordingButton(questionId);
                }
            }
        }, 1000);
        
        timers[`read-${questionId}`] = timerInterval;
    }

    // Show start recording button for Part 2
    function showStartRecordingButton(questionId) {
        const readTimer = document.getElementById(`read-timer-${questionId}`);
        readTimer.innerHTML += `
            <div style="margin-top: 20px;">
                <button type="button" class="action-button primary" onclick="startRecordingPhase(${questionId})">
                    Start Speaking
                </button>
            </div>
        `;
    }

    // Update circular timer display
    function updateTimerDisplay(questionId, current, total) {
        const progress = current / total;
        const circumference = 2 * Math.PI * 54;
        const offset = circumference * (1 - progress);
        
        document.getElementById(`timer-progress-${questionId}`).style.strokeDashoffset = offset;
        document.getElementById(`timer-text-${questionId}`).textContent = current;
    }

    // Start recording phase
    async function startRecordingPhase(questionId) {
        const card = document.getElementById(`card-${questionId}`);
        
        // Update UI
        card.className = 'question-card recording-phase active';
        document.getElementById(`phase-indicator-${questionId}`).innerHTML = `
            <span class="phase-icon">🔴</span>
            <span class="phase-text">Recording - Please speak now</span>
        `;
        
        // Hide read timer, show recording controls
        document.getElementById(`read-timer-${questionId}`).style.display = 'none';
        document.getElementById(`recording-controls-${questionId}`).style.display = 'block';
        
        // Start recording
        try {
            await startRecording(questionId);
        } catch (error) {
            console.error('Failed to start recording:', error);
            alert('Could not access microphone. Please check permissions.');
        }
    }

    // Start audio recording
    async function startRecording(questionId) {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        const mediaRecorder = new MediaRecorder(stream);
        mediaRecorders[questionId] = mediaRecorder;
        audioChunks[questionId] = [];
        
        mediaRecorder.ondataavailable = (event) => {
            audioChunks[questionId].push(event.data);
        };
        
        mediaRecorder.onstop = async () => {
            const audioBlob = new Blob(audioChunks[questionId], { type: 'audio/webm' });
            await uploadRecording(questionId, audioBlob);
            
            // Show audio player for review
            const audioPlayer = document.getElementById(`audio-player-${questionId}`);
            audioPlayer.src = URL.createObjectURL(audioBlob);
            audioPlayer.classList.remove('hidden');
            
            // Stop all tracks
            stream.getTracks().forEach(track => track.stop());
        };
        
        mediaRecorder.start();
        recordingStartTimes[questionId] = Date.now();
        
        // Start recording timer
        updateRecordingTime(questionId);
        
        // Start volume meter
        startVolumeMeter(stream, questionId);
        
        // Auto-stop after max time
        const maxTime = questions[currentQuestionIndex].max_response_time || 45;
        timers[`record-${questionId}`] = setTimeout(() => {
            stopAndNext(questionId);
        }, maxTime * 1000);
    }

    // Update recording time display
    function updateRecordingTime(questionId) {
        const updateTime = () => {
            if (!recordingStartTimes[questionId]) return;
            
            const elapsed = Math.floor((Date.now() - recordingStartTimes[questionId]) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            document.getElementById(`recording-time-${questionId}`).textContent = display;
            
            // Check minimum time
            const minTime = questions[currentQuestionIndex].min_response_time || 15;
            const stopBtn = document.getElementById(`stop-btn-${questionId}`);
            if (elapsed >= minTime) {
                stopBtn.classList.remove('disabled');
                stopBtn.disabled = false;
            }
        };
        
        updateTime();
        timers[`time-${questionId}`] = setInterval(updateTime, 1000);
    }

    // Volume meter visualization
    function startVolumeMeter(stream, questionId) {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const analyser = audioContext.createAnalyser();
        const microphone = audioContext.createMediaStreamSource(stream);
        const dataArray = new Uint8Array(analyser.frequencyBinCount);
        
        analyser.smoothingTimeConstant = 0.8;
        analyser.fftSize = 1024;
        
        microphone.connect(analyser);
        
        const updateVolume = () => {
            analyser.getByteFrequencyData(dataArray);
            let sum = 0;
            for (let i = 0; i < dataArray.length; i++) {
                sum += dataArray[i];
            }
            const average = sum / dataArray.length;
            const percentage = Math.min(100, (average / 128) * 100);
            
            const volumeLevel = document.getElementById(`volume-level-${questionId}`);
            if (volumeLevel) {
                volumeLevel.style.width = percentage + '%';
            }
        };
        
        timers[`volume-${questionId}`] = setInterval(updateVolume, 100);
    }

    // Stop recording and move to next question
    window.stopAndNext = async function(questionId) {
        // Stop recording
        if (mediaRecorders[questionId] && mediaRecorders[questionId].state === 'recording') {
            mediaRecorders[questionId].stop();
        }
        
        // Clear timers
        Object.keys(timers).forEach(key => {
            if (key.includes(questionId)) {
                clearInterval(timers[key]);
                clearTimeout(timers[key]);
            }
        });
        
        // Update card to completed
        const card = document.getElementById(`card-${questionId}`);
        card.className = 'question-card completed active';
        document.getElementById(`phase-indicator-${questionId}`).innerHTML = `
            <span class="phase-icon">✓</span>
            <span class="phase-text">Answer recorded</span>
        `;
        
        recordingsCompleted++;
        
        // Wait a moment then transition
        setTimeout(() => {
            transitionToNext();
        }, 1500);
    }

    // Transition to next question
    function transitionToNext() {
        const currentCard = document.getElementById(`card-${questions[currentQuestionIndex].id}`);
        currentCard.classList.remove('active');
        
        currentQuestionIndex++;
        
        if (currentQuestionIndex < questions.length) {
            const nextCard = document.getElementById(`card-${questions[currentQuestionIndex].id}`);
            nextCard.classList.add('active');
            initializeQuestion(currentQuestionIndex);
        } else {
            showCompletionScreen();
        }
    }

    // Upload recording to server
    async function uploadRecording(questionId, audioBlob) {
        const formData = new FormData();
        formData.append('recording', audioBlob, 'recording.webm');
        
        try {
            const response = await fetch(`{{ url('student/test/speaking/record') }}/${{{ $attempt->id }}}/${questionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });
            
            const data = await response.json();
            if (!data.success) {
                console.error('Failed to save recording');
            }
        } catch (error) {
            console.error('Upload error:', error);
        }
    }

    // Show completion screen
    function showCompletionScreen() {
        document.getElementById('recorded-count').textContent = recordingsCompleted;
        document.getElementById('submit-modal').style.display = 'flex';
    }
    </script>
    @endpush
</x-test-layout>