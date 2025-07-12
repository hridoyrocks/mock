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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            height: 100vh;
            overflow: hidden;
        }
        
        .ielts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .ielts-header-left {
            display: flex;
            align-items: center;
        }
        
        .user-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #1a1a1a;
            color: white;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        
        .user-controls {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .content-area {
            height: calc(100vh - 108px); /* Subtract header heights */
            overflow-y: auto;
            padding: 20px 20px;
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
        }
        
        /* Submit Button in User Bar */
        .submit-btn-top {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .submit-btn-top:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }
        
        .submit-btn-top svg {
            width: 16px;
            height: 16px;
        }
        
        /* Question Card Container */
        .question-card-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* Enhanced Question Card */
        .question-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            padding: 30px;
            margin-bottom: 20px;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 420px;
            display: none;
            border: 2px solid transparent;
        }
        
        .question-card.active {
            display: block;
            animation: slideInUp 0.6s ease-out;
        }
        
        .question-card.reading-phase {
            border-color: #3B82F6;
            background: linear-gradient(to bottom right, #ffffff, #EBF8FF);
        }
        
        .question-card.recording-phase {
            border-color: #EF4444;
            background: linear-gradient(to bottom right, #ffffff, #FEF2F2);
        }
        
        .question-card.completed {
            border-color: #10B981;
            background: linear-gradient(to bottom right, #ffffff, #F0FDF4);
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Card Header */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(0,0,0,0.05);
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .question-counter {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
            background: #f3f4f6;
            padding: 5px 14px;
            border-radius: 20px;
        }
        
        /* Phase Indicator */
        .card-phase-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 24px;
            padding: 12px;
            background: rgba(0,0,0,0.03);
            border-radius: 10px;
        }
        
        .phase-icon {
            font-size: 24px;
        }
        
        /* Question Display */
        .question-display {
            text-align: center;
            margin: 30px 0;
        }
        
        .question-text {
            font-size: 22px;
            line-height: 1.5;
            color: #111827;
            margin-bottom: 30px;
            font-weight: 500;
            letter-spacing: -0.02em;
        }
        
        /* Enhanced Timer */
        .read-timer {
            margin: 30px auto;
            text-align: center;
        }
        
        .timer-circle {
            width: 120px;
            height: 120px;
            margin: 0 auto 16px;
            position: relative;
            filter: drop-shadow(0 4px 12px rgba(59, 130, 246, 0.15));
        }
        
        .timer-circle svg {
            transform: rotate(-90deg);
        }
        
        .timer-circle-bg {
            fill: none;
            stroke: #E5E7EB;
            stroke-width: 6;
        }
        
        .timer-circle-progress {
            fill: none;
            stroke: #3B82F6;
            stroke-width: 6;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.5s ease;
        }
        
        .timer-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
            font-weight: 700;
            color: #3B82F6;
        }
        
        .timer-label {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .timer-info {
            font-size: 13px;
            color: #9CA3AF;
            margin-top: 10px;
        }
        
        /* Recording Controls */
        .recording-controls {
            text-align: center;
            margin-top: 30px;
        }
        
        .recording-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 16px;
            background: rgba(239, 68, 68, 0.05);
            border-radius: 10px;
        }
        
        .recording-dot {
            width: 12px;
            height: 12px;
            background: #EF4444;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
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
            background: linear-gradient(to right, #10B981, #34D399);
            transition: width 0.1s ease;
            border-radius: 4px;
        }
        
        .recording-time {
            font-size: 22px;
            font-weight: 700;
            color: #374151;
            margin-top: 16px;
            font-feature-settings: 'tnum';
        }
        
        /* Action Button */
        .action-button {
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .action-button.primary {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: white;
        }
        
        .action-button.primary:hover:not(.disabled) {
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }
        
        .action-button.disabled {
            background: #9CA3AF;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        /* Part 2 Cue Card */
        .cue-card {
            background: linear-gradient(135deg, #FEF3C7, #FFFBEB);
            border: 2px solid #F59E0B;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
            text-align: left;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1);
        }
        
        .cue-card-title {
            font-weight: 700;
            color: #92400E;
            margin-bottom: 16px;
            font-size: 15px;
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
            font-size: 14px;
            line-height: 1.5;
        }
        
        .cue-card-points li:before {
            content: "•";
            position: absolute;
            left: 8px;
            color: #F59E0B;
            font-weight: bold;
            font-size: 18px;
        }
        
        /* Progress Dots */
        .progress-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
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
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .progress-dot.active {
            background: #3B82F6;
            transform: scale(1.2);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 32px;
            border-radius: 16px;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .modal-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #059669;
        }
        
        .modal-message {
            font-size: 16px;
            margin-bottom: 28px;
            line-height: 1.6;
            color: #4b5563;
        }
        
        .modal-button {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin: 0 6px;
            transition: all 0.2s ease;
        }
        
        .modal-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .modal-button.secondary {
            background: #6b7280;
        }
        
        .modal-button.secondary:hover {
            background: #4b5563;
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .content-area {
                padding: 15px 12px;
            }
            
            .question-card {
                padding: 24px 18px;
                min-height: 400px;
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
            
            .user-bar {
                padding: 10px 15px;
            }
            
            .user-controls {
                gap: 8px;
            }
            
            .submit-btn-top {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            .submit-btn-top span {
                display: none; /* Hide text on mobile, show only icon */
            }
        }
        
        /* Tips Section (Optional) */
        .tips-section {
            background: linear-gradient(135deg, #F9FAFB, #F3F4F6);
            border-radius: 10px;
            padding: 16px;
            margin-top: 24px;
            border: 1px solid #E5E7EB;
        }
        
        .tips-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #6B7280;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .tips-content {
            font-size: 13px;
            color: #6B7280;
            line-height: 1.5;
        }
        
        /* Audio Player for Review */
        .audio-player {
            margin-top: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Scrollbar Styling */
        .content-area::-webkit-scrollbar {
            width: 8px;
        }
        
        .content-area::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        
        .content-area::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        
        .content-area::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
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

    <!-- User Info Bar with Submit Button -->
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
            
            {{-- Submit Button in Top Bar --}}
            <button type="button" id="submit-test-btn" class="submit-btn-top">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Submit Test</span>
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="content-area">
        <form id="speaking-form" action="{{ route('student.speaking.submit', $attempt) }}" method="POST">
            @csrf
            
            <div class="question-card-container">
                @foreach ($testSet->questions->sortBy('order_number') as $index => $question)
                    <!-- Progressive Question Card -->
                    <div class="question-card reading-phase {{ $index === 0 ? 'active' : '' }}" 
                         id="card-{{ $question->id }}"
                         data-question-id="{{ $question->id }}"
                         data-question-index="{{ $index }}"
                         data-part="{{ $question->part_number }}">
                        
                        <!-- Card Header -->
                        <div class="card-header">
                            <h3 class="card-title">
                                Part {{ $question->part_number }}
                                @if($question->part_number == 1)
                                    - Introduction & Interview
                                @elseif($question->part_number == 2)
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
                            @if($question->part_number == 2 && $question->form_structure)
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
                                @if($question->part_number == 2)
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
                                @if($question->part_number == 2)
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

    <!-- Submit Modal -->
    <div id="submit-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-title">Submit Test?</div>
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
        
        // Start reading phase
        startReadingPhase(questionId);
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
            <div style="margin-top: 24px;">
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