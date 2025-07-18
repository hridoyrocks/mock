{{-- resources/views/student/test/speaking/test.blade.php --}}
<x-test-layout>
    <x-slot:title>IELTS Speaking Test</x-slot>
    
    <x-slot:meta>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
            height: 50px;
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
            height: calc(100vh - 100px);
            overflow-y: auto;
            padding: 20px 20px 80px;
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
        }
        
        /* Timer Center Wrapper */
        .timer-center-wrapper {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
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
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 12px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            z-index: 100;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
            height: 60px;
        }
        
        .submit-test-button {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .submit-test-button:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
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
            color: #111827;
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
        
        /* Beautiful Audio Player */
        .audio-player-container {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            margin-top: 24px;
            position: relative;
            overflow: hidden;
        }

        .audio-player-container::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .audio-visualizer {
            display: none;
            align-items: flex-end;
            justify-content: center;
            height: 80px;
            gap: 4px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .audio-bar {
            width: 6px;
            background: linear-gradient(to top, #4facfe 0%, #00f2fe 100%);
            border-radius: 3px;
            box-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
            transition: height 0.1s ease;
        }

        .audio-bar:nth-child(1) { height: 30px; animation: dance 0.6s ease-in-out infinite; }
        .audio-bar:nth-child(2) { height: 50px; animation: dance 0.6s ease-in-out infinite 0.1s; }
        .audio-bar:nth-child(3) { height: 40px; animation: dance 0.6s ease-in-out infinite 0.2s; }
        .audio-bar:nth-child(4) { height: 60px; animation: dance 0.6s ease-in-out infinite 0.3s; }
        .audio-bar:nth-child(5) { height: 35px; animation: dance 0.6s ease-in-out infinite 0.4s; }
        .audio-bar:nth-child(6) { height: 55px; animation: dance 0.6s ease-in-out infinite 0.5s; }
        .audio-bar:nth-child(7) { height: 45px; animation: dance 0.6s ease-in-out infinite 0.6s; }

        @keyframes dance {
            0%, 100% { transform: scaleY(0.5); }
            50% { transform: scaleY(1.2); }
        }

        .custom-audio-player {
            width: 100%;
            height: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 25px;
            padding: 5px;
            position: relative;
            z-index: 1;
        }

        /* Custom audio controls styling */
        .custom-audio-player::-webkit-media-controls-panel {
            background: transparent;
        }

        .custom-audio-player::-webkit-media-controls-play-button,
        .custom-audio-player::-webkit-media-controls-mute-button {
            background-color: rgba(255,255,255,0.8);
            border-radius: 50%;
        }

        .custom-audio-player::-webkit-media-controls-current-time-display,
        .custom-audio-player::-webkit-media-controls-time-remaining-display {
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .custom-audio-player::-webkit-media-controls-timeline {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }

        .audio-player-label {
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
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
                display: none;
            }
        }
        
        /* Tips Section */
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

   

    <!-- User Info Bar WITH Integrated Timer -->
    <div class="user-bar" style="height: 50px;">
        <div class="user-info">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ auth()->user()->name }} - BI {{ str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        
        {{-- Integrated Timer Component - Center Position --}}
        <div class="timer-center-wrapper">
            <x-test-timer 
                :attempt="$attempt" 
                auto-submit-form-id="speaking-form"
                position="integrated"
                :warning-time="300"
                :danger-time="120"
            />
        </div>
        
        <div class="user-controls">
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm help-button" id="help-button">Help ?</button>
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm no-nav">Hide</button>
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
                                @if($question->part_number == 1)
                                    Part 1
                                @elseif($question->part_number == 2)
                                    Part 2
                                @else
                                    Part 3
                                @endif
                            </h3>
                            <span class="question-counter">
                                Question {{ $index + 1 }} of {{ $testSet->questions->count() }}
                            </span>
                        </div>

                        <!-- Phase Indicator -->
                        <div class="card-phase-indicator" id="phase-indicator-{{ $question->id }}">
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
                                        class="action-button primary" 
                                        id="stop-btn-{{ $question->id }}"
                                        onclick="stopAndNext({{ $question->id }})">
                                    Stop & Next Question
                                </button>
                            </div>
                        </div>

                        <!-- Beautiful Audio Player -->
                        <div class="audio-player-container hidden" id="audio-container-{{ $question->id }}">
                            <div class="audio-player-label">Your Recording</div>
                            <div class="audio-visualizer" id="visualizer-{{ $question->id }}">
                                <div class="audio-bar"></div>
                                <div class="audio-bar"></div>
                                <div class="audio-bar"></div>
                                <div class="audio-bar"></div>
                                <div class="audio-bar"></div>
                                <div class="audio-bar"></div>
                                <div class="audio-bar"></div>
                            </div>
                            <audio id="audio-player-{{ $question->id }}" controls class="custom-audio-player"></audio>
                        </div>

                        <!-- Tips Section -->
                        @if($question->speaking_tips)
                            <div class="tips-section">
                                <div class="tips-header">
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
    <div class="bottom-nav" style="height: 60px;">
        <div class="nav-right">
            <button type="button" id="submit-test-btn" class="submit-test-button">
                Submit Test
            </button>
        </div>
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

    <!-- Part Complete Modal -->
    <div id="part-complete-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h3 class="modal-title text-gray-900">We've completed the section Part <span id="completed-part">1</span>.</h3>
            <p class="modal-message">
                Are you ready to move on to the next section?
            </p>
            <button class="modal-button w-full" onclick="continueToPart()">Next</button>
        </div>
    </div>
    
    @push('scripts')
    <script>
    // Progressive Card System
    const questions = @json($testSet->questions->values());
    const attemptId = {{ $attempt->id }};
    const storageKey = `speaking_test_${attemptId}`;
    
    let mediaRecorders = {};
    let audioChunks = {};
    let timers = {};
    let recordingStartTimes = {};
    
    // Load saved state or initialize
    let savedState = loadTestState();
    let currentQuestionIndex = savedState.currentQuestionIndex || 0;
    let recordingsCompleted = savedState.recordingsCompleted || 0;
    let completedQuestions = savedState.completedQuestions || [];

    // Save state to sessionStorage
    function saveTestState() {
        const state = {
            currentQuestionIndex: currentQuestionIndex,
            recordingsCompleted: recordingsCompleted,
            completedQuestions: completedQuestions,
            timestamp: Date.now()
        };
        sessionStorage.setItem(storageKey, JSON.stringify(state));
    }

    // Load state from sessionStorage
    function loadTestState() {
        const saved = sessionStorage.getItem(storageKey);
        if (saved) {
            const state = JSON.parse(saved);
            // Check if session is still valid (e.g., within 2 hours)
            if (Date.now() - state.timestamp < 2 * 60 * 60 * 1000) {
                return state;
            }
        }
        return {};
    }

    // Clear state on test completion
    function clearTestState() {
        sessionStorage.removeItem(storageKey);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Restore UI state
        restoreUIState();
        
        // Initialize current question
        initializeQuestion(currentQuestionIndex);
        
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
            clearTestState(); // Clear state on submission
            submitButton.click();
        });
        
        cancelSubmitBtn.addEventListener('click', function() {
            submitModal.style.display = 'none';
        });
        
        // Save state before page unload
        window.addEventListener('beforeunload', saveTestState);
    });

    // Restore UI state after reload
    function restoreUIState() {
        // Hide all cards first
        document.querySelectorAll('.question-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Mark completed questions
        completedQuestions.forEach(questionId => {
            const card = document.getElementById(`card-${questionId}`);
            if (card) {
                card.className = 'question-card completed';
                const phaseIndicator = document.getElementById(`phase-indicator-${questionId}`);
                if (phaseIndicator) {
                    phaseIndicator.innerHTML = '<span class="phase-text">Answer recorded</span>';
                }
                
                // Update progress dots
                const questionIndex = questions.findIndex(q => q.id === questionId);
                if (questionIndex !== -1) {
                    const dots = document.querySelectorAll('.progress-dots')[questionIndex];
                    if (dots) {
                        const dot = dots.children[questionIndex];
                        if (dot) dot.classList.add('completed');
                    }
                }
            }
        });
        
        // Show current card
        if (currentQuestionIndex < questions.length) {
            const currentCard = document.getElementById(`card-${questions[currentQuestionIndex].id}`);
            if (currentCard) {
                currentCard.classList.add('active');
            }
        }
    }

    // Initialize a question
    function initializeQuestion(index) {
        if (index >= questions.length) {
            showCompletionScreen();
            return;
        }

        const question = questions[index];
        const questionId = question.id;
        
        startReadingPhase(questionId);
    }

    // Start reading phase with timer
    function startReadingPhase(questionId) {
        const card = document.getElementById(`card-${questionId}`);
        const readTime = questions[currentQuestionIndex].read_time || 5;
        let timeLeft = readTime;
        
        card.className = 'question-card reading-phase active';
        document.getElementById(`phase-indicator-${questionId}`).innerHTML = `
            <span class="phase-text">Read the question carefully</span>
        `;
        
        document.getElementById(`read-timer-${questionId}`).style.display = 'block';
        document.getElementById(`recording-controls-${questionId}`).style.display = 'none';
        
        const timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay(questionId, timeLeft, readTime);
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                if (questions[currentQuestionIndex].auto_progress !== false) {
                    startRecordingPhase(questionId);
                } else {
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
        
        card.className = 'question-card recording-phase active';
        document.getElementById(`phase-indicator-${questionId}`).innerHTML = `
            <span class="phase-text">Recording - Please speak now</span>
        `;
        
        document.getElementById(`read-timer-${questionId}`).style.display = 'none';
        document.getElementById(`recording-controls-${questionId}`).style.display = 'block';
        
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
            
            showAudioPlayer(questionId, audioBlob);
            
            stream.getTracks().forEach(track => track.stop());
        };
        
        mediaRecorder.start();
        recordingStartTimes[questionId] = Date.now();
        
        updateRecordingTime(questionId);
        startVolumeMeter(stream, questionId);
        
        const maxTime = questions[currentQuestionIndex].max_response_time || 45;
        timers[`record-${questionId}`] = setTimeout(() => {
            stopAndNext(questionId);
        }, maxTime * 1000);
    }

    // Update recording time display
    function updateRecordingTime(questionId) {
        const minTime = questions[currentQuestionIndex].min_response_time || 5; // Reduced default
        const stopBtn = document.getElementById(`stop-btn-${questionId}`);
        
        const updateTime = () => {
            if (!recordingStartTimes[questionId]) return;
            
            const elapsed = Math.floor((Date.now() - recordingStartTimes[questionId]) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            document.getElementById(`recording-time-${questionId}`).textContent = display;
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

    // Show audio player with animation
    function showAudioPlayer(questionId, audioBlob) {
        const container = document.getElementById(`audio-container-${questionId}`);
        const audioPlayer = document.getElementById(`audio-player-${questionId}`);
        const visualizer = document.getElementById(`visualizer-${questionId}`);
        
        audioPlayer.src = URL.createObjectURL(audioBlob);
        container.classList.remove('hidden');
        
        // Add smooth appearance animation
        container.style.opacity = '0';
        container.style.transform = 'translateY(20px)';
        setTimeout(() => {
            container.style.transition = 'all 0.5s ease';
            container.style.opacity = '1';
            container.style.transform = 'translateY(0)';
        }, 100);
        
        audioPlayer.addEventListener('play', () => {
            visualizer.style.display = 'flex';
        });
        
        audioPlayer.addEventListener('pause', () => {
            visualizer.style.display = 'none';
        });
        
        audioPlayer.addEventListener('ended', () => {
            visualizer.style.display = 'none';
        });
    }

    // Stop recording and move to next question - IMPROVED VERSION
    window.stopAndNext = async function(questionId) {
        // Immediately disable the button
        const stopBtn = document.getElementById(`stop-btn-${questionId}`);
        stopBtn.disabled = true;
        stopBtn.textContent = 'Processing...';
        
        // Stop recording
        if (mediaRecorders[questionId] && mediaRecorders[questionId].state === 'recording') {
            mediaRecorders[questionId].stop();
        }
        
        // Clear all timers
        Object.keys(timers).forEach(key => {
            if (key.includes(questionId)) {
                clearInterval(timers[key]);
                clearTimeout(timers[key]);
            }
        });
        
        // Update card immediately
        const card = document.getElementById(`card-${questionId}`);
        card.className = 'question-card completed active';
        document.getElementById(`phase-indicator-${questionId}`).innerHTML = `
            <span class="phase-text">Answer recorded</span>
        `;
        
        recordingsCompleted++;
        completedQuestions.push(questionId);
        saveTestState(); // Save state after each question
        
        // Check if part is complete
        if (!checkPartCompletion()) {
            // Immediately transition
            transitionToNext();
        }
    }

    // Check if part is complete
    function checkPartCompletion() {
        const currentPart = questions[currentQuestionIndex].part_number;
        const nextIndex = currentQuestionIndex + 1;
        
        if (nextIndex < questions.length) {
            const nextPart = questions[nextIndex].part_number;
            
            if (currentPart !== nextPart) {
                showPartCompleteModal(currentPart);
                return true;
            }
        }
        return false;
    }

    // Show part completion modal
    function showPartCompleteModal(partNumber) {
        document.getElementById('completed-part').textContent = partNumber;
        document.getElementById('part-complete-modal').style.display = 'flex';
    }

    // Continue to next part
    function continueToPart() {
        document.getElementById('part-complete-modal').style.display = 'none';
        transitionToNext();
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

    // Upload recording to server - FIXED VERSION
    async function uploadRecording(questionId, audioBlob) {
        const formData = new FormData();
        formData.append('recording', audioBlob, 'recording.webm');
        
        try {
            const response = await fetch(`{{ route('student.speaking.record', ['attempt' => $attempt->id, 'question' => ':questionId']) }}`.replace(':questionId', questionId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            if (!data.success) {
                console.error('Failed to save recording:', data);
            } else {
                console.log('Recording saved successfully for question:', questionId);
            }
        } catch (error) {
            console.error('Upload error:', error);
            // Don't alert user to avoid interrupting test flow
            // Recording is already saved locally in blob
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