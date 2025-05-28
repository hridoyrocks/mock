<x-test-layout>
    <x-slot:title>IELTS Listening Test</x-slot>
    
    <!-- Meta tags to prevent page caching and force fullscreen -->
    <x-slot:meta>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
    </x-slot:meta>
    
    <!-- Remove default margin/padding and set background color -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Top IELTS Logo Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
        }
        
        /* Red Computer-delivered IELTS Bar */
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
        
        /* Black User Info Bar */
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
        
        /* Timer */
        .timer {
            background-color: #0d6efd;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .timer:hover .seconds {
            display: inline;
        }
        
        .seconds {
            display: none;
        }
        
        /* Content Area */
        .content-area {
            padding: 20px;
        }
        
        /* Test Title */
        .test-title {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        /* Audio Info Box */
        .audio-info {
            border-left: 5px solid #0d6efd;
            background-color: #f0f7ff;
            padding: 15px;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        
        /* Questions Section */
        .questions-section {
            margin-top: 30px;
            opacity: 0.5;
            pointer-events: none;
        }
        
        .question-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .question-box {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .question-number {
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .options-list {
            margin-left: 20px;
        }
        
        .option-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .option-radio {
            margin-right: 10px;
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            border-top: 1px solid #e5e7eb;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-left {
            display: flex;
            align-items: center;
        }
        
        .nav-numbers {
            display: flex;
            margin-left: 15px;
        }
        
        .number-btn {
            width: 28px;
            height: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            border: 1px solid #d1d5db;
            margin-right: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        
        .number-btn.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
        }
        
        .pencil-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #ffc107;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
            cursor: pointer;
        }
        
        .arrow-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: 5px;
            cursor: pointer;
        }
        
        /* Submit Button */
        #submit-test-btn {
            transition: all 0.3s ease;
        }
        
        #submit-test-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Warning Modal */
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
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: bold;
            color: #dc2626;
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

    <!-- User Info Bar with Timer -->
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
            <div class="flex items-center ml-2">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071a1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <input type="range" min="0" max="100" value="75" class="ml-2 w-20">
            </div>
            <!-- Timer -->
            <div class="timer" id="timer-display">
                <span class="minutes">{{ $testSet->section->time_limit }}</span>
                <span class="seconds">:00</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <!-- Test Title -->
        <h1 class="test-title">Listening Test</h1>
        
        <!-- Brief audio notification - No audio progress bar -->
        <div class="audio-info">
            <p>The audio will begin automatically. You will hear the recording ONCE only. The questions will be enabled after the audio has played.</p>
        </div>
        
        <!-- Questions Section -->
        <form id="test-form" action="{{ route('student.listening.submit', $attempt) }}" method="POST">
            @csrf
            
            <div class="questions-section" id="questions-container">
                @foreach($testSet->questions->groupBy('order_number') as $group => $questions)
                    <div class="mb-8">
                        <h2 class="question-title">Questions {{ $group }} - {{ $group + count($questions) - 1 }}</h2>
                        
                        @foreach($questions as $question)
                            <div class="question-box" id="question-{{ $question->order_number }}">
                                <div class="question-number">
                                    {{ $question->order_number }}. {{ $question->content }}
                                </div>
                                
                                <div class="options-list">
                                    @foreach($question->options as $option)
                                        <div class="option-item">
                                            <input type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" class="option-radio">
                                            <label for="option-{{ $option->id }}">{{ $option->content }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
            <button type="submit" id="submit-button" class="hidden">Submit</button>
        </form>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-left">
            <div>
                <input type="checkbox" id="review-checkbox">
                <label for="review-checkbox">Review</label>
            </div>
            <div class="nav-numbers">
                <span class="mr-2">Part 1</span>
                @php
                    $questionCount = $testSet->questions->count();
                    $maxButtons = min($questionCount, 30);
                @endphp
                
                @for ($i = 1; $i <= $maxButtons; $i++)
                    <div class="number-btn {{ $i == 1 ? 'active' : '' }}" data-question="{{ $i }}">{{ $i }}</div>
                @endfor
            </div>
        </div>
        <div class="nav-right">
            <button type="button" id="submit-test-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md mr-3 text-sm font-medium">
                Submit Test
            </button>
            <div class="pencil-btn">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
            </div>
            <div class="arrow-btn" id="prev-btn">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="arrow-btn" id="next-btn">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Warning Modal (initially hidden) -->
    <div id="warning-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-title">Warning!</div>
            <div class="modal-message">
                You cannot leave the test page once it has started. Leaving this page will count as an incomplete attempt.
            </div>
            <button class="modal-button" id="continue-test-btn">Continue Test</button>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div id="submit-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-title" style="color: #059669;">Submit Test?</div>
            <div class="modal-message">
                Are you sure you want to submit your test? You cannot change your answers after submission.
                <br><br>
                <strong>Answered Questions: <span id="answered-count">0</span> / {{ $testSet->questions->count() }}</strong>
            </div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="modal-button" id="confirm-submit-btn">Yes, Submit</button>
                <button class="modal-button" id="cancel-submit-btn" style="background-color: #6b7280;">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Hidden Audio Element -->
    <audio id="test-audio" preload="auto" style="display:none;">
        <source src="{{ asset('storage/' . $testSet->questions->first()->media_path) }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const audio = document.getElementById('test-audio');
    const volumeSlider = document.querySelector('input[type="range"]');
    const questionsContainer = document.getElementById('questions-container');
    const submitButton = document.getElementById('submit-button');
    const navButtons = document.querySelectorAll('.number-btn');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const timerDisplay = document.getElementById('timer-display');
    const minutesElement = timerDisplay.querySelector('.minutes');
    const secondsElement = timerDisplay.querySelector('.seconds');
    const warningModal = document.getElementById('warning-modal');
    const continueTestBtn = document.getElementById('continue-test-btn');
    const submitTestBtn = document.getElementById('submit-test-btn');
    const submitModal = document.getElementById('submit-modal');
    const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
    const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
    const answeredCountSpan = document.getElementById('answered-count');
    
    // Test session management
    const testId = '{{ $attempt->id }}';
    const testDuration = {{ $testSet->section->time_limit }} * 60; // in seconds
    
    // Check if test was abandoned
    const isTestAbandoned = localStorage.getItem('test_' + testId + '_abandoned') === 'true';
    if (isTestAbandoned) {
        // Show abandoned message and redirect
        alert('This test attempt has been marked as abandoned. You will be redirected to the results page.');
        window.location.href = '{{ route("student.results") }}';
        return;
    }
    
    // Initialize or restore timer
    let testStartTime = localStorage.getItem('test_' + testId + '_startTime');
    if (!testStartTime) {
        testStartTime = Date.now();
        localStorage.setItem('test_' + testId + '_startTime', testStartTime);
    }
    
    // Record test session
    localStorage.setItem('testId', testId);
    localStorage.setItem('test_' + testId + '_active', 'true');
    
    // -------------------- PREVENT NAVIGATION --------------------
    
    // Track if user is trying to leave
    let isLeavingPage = false;
    
    // 1. Disable all navigation links (excluding Help and Hide buttons)
    const navLinks = document.querySelectorAll('a:not([href^="#"]):not(.no-nav)');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showWarningModal();
            return false;
        });
    });
    
    // 2. Prevent browser navigation (back/forward)
    let preventCount = 0;
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function(event) {
        history.pushState(null, null, location.href);
        preventCount++;
        if (preventCount <= 2) {
            showWarningModal();
        } else {
            // User is insisting on leaving - mark as abandoned
            markTestAsAbandoned();
        }
    });
    
    // 3. Prevent tab/window close and mark as abandoned if they leave
    window.addEventListener('beforeunload', function(e) {
        if (!isLeavingPage) {
            // Save the current answers
            saveAllAnswers();
            
            // Mark test as potentially abandoned
            localStorage.setItem('test_' + testId + '_possiblyAbandoned', 'true');
            localStorage.setItem('test_' + testId + '_lastActivity', Date.now());
            
            // Cancel the event
            e.preventDefault();
            // Chrome requires returnValue to be set
            e.returnValue = 'You are in the middle of a test. Leaving will mark this attempt as abandoned.';
            return 'You are in the middle of a test. Leaving will mark this attempt as abandoned.';
        }
    });
    
    // 4. Function to show warning modal
    function showWarningModal() {
        warningModal.style.display = 'flex';
    }
    
    // 5. Close warning modal
    continueTestBtn.addEventListener('click', function() {
        warningModal.style.display = 'none';
    });
    
    // 6. Handle keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Block F5 key
        if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
            e.preventDefault();
            showWarningModal();
            return false;
        }
        
        // Block Ctrl+W, Alt+F4 
        if ((e.ctrlKey && e.key === 'w') || (e.altKey && e.key === 'F4')) {
            e.preventDefault();
            showWarningModal();
            return false;
        }
    });
    
    // 7. Detect when user leaves the page focus
    let leftPageCount = 0;
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            leftPageCount++;
            console.log('User left the test page at:', new Date());
            
            // If user leaves page more than 3 times, mark as suspicious
            if (leftPageCount > 3) {
                localStorage.setItem('test_' + testId + '_suspicious', 'true');
            }
        }
    });
    
    // Function to mark test as abandoned
    function markTestAsAbandoned() {
        localStorage.setItem('test_' + testId + '_abandoned', 'true');
        localStorage.removeItem('test_' + testId + '_active');
        
        // Send AJAX request to mark attempt as abandoned
        fetch('{{ route("student.listening.abandon", $attempt) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                attempt_id: testId
            })
        });
    }
    
    // Check if test was possibly abandoned (page was closed)
    window.addEventListener('load', function() {
        const possiblyAbandoned = localStorage.getItem('test_' + testId + '_possiblyAbandoned');
        const lastActivity = localStorage.getItem('test_' + testId + '_lastActivity');
        
        if (possiblyAbandoned === 'true' && lastActivity) {
            const timeSinceLastActivity = Date.now() - parseInt(lastActivity);
            
            // If more than 5 minutes have passed, consider it abandoned
            if (timeSinceLastActivity > 5 * 60 * 1000) {
                markTestAsAbandoned();
                alert('This test has been marked as abandoned due to inactivity.');
                window.location.href = '{{ route("student.results") }}';
                return;
            } else {
                // Clear the possibly abandoned flag
                localStorage.removeItem('test_' + testId + '_possiblyAbandoned');
                localStorage.removeItem('test_' + testId + '_lastActivity');
            }
        }
    });
    
    // Save all current answers to localStorage
    function saveAllAnswers() {
        // Get all selected radio buttons
        const selectedRadios = document.querySelectorAll('input[type="radio"]:checked');
        
        // Create an answers object
        const answers = {};
        selectedRadios.forEach(radio => {
            const questionId = radio.name.replace('answers[', '').replace(']', '');
            answers[questionId] = radio.value;
        });
        
        // Store in local storage as backup
        localStorage.setItem('test_' + testId + '_answers', JSON.stringify(answers));
    }
    
    // Periodically save answers (every 30 seconds)
    setInterval(saveAllAnswers, 30000);
    
    // -------------------- AUDIO AND TEST FUNCTIONALITY --------------------
    
    // Initialize audio volume
    audio.volume = volumeSlider.value / 100;
    
    // Volume control
    volumeSlider.addEventListener('input', function() {
        audio.volume = this.value / 100;
    });
    
    // Start timer with elapsed time consideration
    startTimer();
    
    // Play audio automatically
    audio.play().catch(function(e) {
        console.error('Error playing audio:', e);
        
        // Create a hidden auto-play trigger if needed
        document.addEventListener('click', function autoPlayHandler() {
            audio.play().then(() => {
                document.removeEventListener('click', autoPlayHandler);
            }).catch(err => {
                console.error('Still cannot play audio:', err);
            });
        }, { once: true });
    });
    
    // Audio ended event
    audio.addEventListener('ended', function() {
        // Enable questions
        questionsContainer.style.opacity = '1';
        questionsContainer.style.pointerEvents = 'auto';
        
        // Set first question button as active
        if (navButtons.length > 0) {
            navButtons[0].classList.add('active');
        }
    });
    
    // Enable questions if audio fails to play or for testing
    setTimeout(function() {
        if (audio.paused || audio.ended) {
            questionsContainer.style.opacity = '1';
            questionsContainer.style.pointerEvents = 'auto';
        }
    }, 3000);
    
    // Timer functionality with persistence
    function startTimer() {
        const startTime = parseInt(localStorage.getItem('test_' + testId + '_startTime'));
        const currentTime = Date.now();
        const elapsedSeconds = Math.floor((currentTime - startTime) / 1000);
        let remainingSeconds = testDuration - elapsedSeconds;
        
        // If time is already up
        if (remainingSeconds <= 0) {
            saveAllAnswers();
            submitButton.click();
            return;
        }
        
        const timerInterval = setInterval(function() {
            remainingSeconds--;
            
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                saveAllAnswers();
                isLeavingPage = true; // Allow page to be left for submission
                submitButton.click();
                return;
            }
            
            const minutesLeft = Math.floor(remainingSeconds / 60);
            const secondsLeft = remainingSeconds % 60;
            
            // Visual cue for time running low
            if (remainingSeconds < 60) {
                timerDisplay.style.backgroundColor = '#dc2626'; // Red
                timerDisplay.classList.add('animate-pulse');
            } else if (remainingSeconds < 300) { // Less than 5 minutes
                timerDisplay.style.backgroundColor = '#f59e0b'; // Yellow
            }
            
            // Update timer display
            minutesElement.textContent = minutesLeft;
            secondsElement.textContent = `:${secondsLeft < 10 ? '0' : ''}${secondsLeft}`;
        }, 1000);
    }
    
    // Question navigation
    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            navButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Scroll to question
            const questionNumber = this.dataset.question;
            const questionElement = document.getElementById(`question-${questionNumber}`);
            
            if (questionElement) {
                questionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
    
    // Previous/Next buttons
    prevBtn.addEventListener('click', function() {
        const activeButton = document.querySelector('.number-btn.active');
        if (activeButton && activeButton.previousElementSibling && activeButton.previousElementSibling.classList.contains('number-btn')) {
            activeButton.previousElementSibling.click();
        }
    });
    
    nextBtn.addEventListener('click', function() {
        const activeButton = document.querySelector('.number-btn.active');
        if (activeButton && activeButton.nextElementSibling && activeButton.nextElementSibling.classList.contains('number-btn')) {
            activeButton.nextElementSibling.click();
        }
    });
    
    // Handle answer selection
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    radioButtons.forEach(function(radio) {
        radio.addEventListener('change', function() {
            const questionId = this.name.replace('answers[', '').replace(']', '');
            const question = this.closest('.question-box');
            const questionNumber = question.id.replace('question-', '');
            
            // Mark question as answered in navigation
            const navButton = document.querySelector(`.number-btn[data-question="${questionNumber}"]`);
            if (navButton) {
                navButton.style.backgroundColor = '#10b981'; // Green
                navButton.style.color = 'white';
                navButton.style.borderColor = '#059669';
            }
            
            // Save the answer when it's changed
            saveAllAnswers();
        });
    });
    
    // Review checkbox functionality
    const reviewCheckbox = document.getElementById('review-checkbox');
    reviewCheckbox.addEventListener('change', function() {
        const currentQuestion = document.querySelector('.number-btn.active');
        if (currentQuestion) {
            if (this.checked) {
                currentQuestion.style.border = '2px solid #F59E0B';
            } else {
                currentQuestion.style.border = '';
            }
        }
    });
    
    // Submit button click handler
    submitTestBtn.addEventListener('click', function() {
        // Count answered questions
        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
        answeredCountSpan.textContent = answeredQuestions;
        
        // Show submit confirmation modal
        submitModal.style.display = 'flex';
    });
    
    // Confirm submit button
    confirmSubmitBtn.addEventListener('click', function() {
        saveAllAnswers();
        isLeavingPage = true; // Allow page to be left for submission
        
        // Clear all test data from localStorage
        localStorage.removeItem('test_' + testId + '_startTime');
        localStorage.removeItem('test_' + testId + '_answers');
        localStorage.removeItem('test_' + testId + '_active');
        localStorage.removeItem('test_' + testId + '_abandoned');
        localStorage.removeItem('test_' + testId + '_possiblyAbandoned');
        localStorage.removeItem('test_' + testId + '_lastActivity');
        
        submitButton.click();
    });
    
    // Cancel submit button
    cancelSubmitBtn.addEventListener('click', function() {
        submitModal.style.display = 'none';
    });
    
    // Attempt to load answers from local storage
    try {
        const savedAnswers = localStorage.getItem('test_' + testId + '_answers');
        
        if (savedAnswers) {
            const answers = JSON.parse(savedAnswers);
            
            // Restore each answer
            Object.keys(answers).forEach(questionId => {
                const optionId = answers[questionId];
                const radio = document.querySelector(`input[name="answers[${questionId}]"][value="${optionId}"]`);
                if (radio) {
                    radio.checked = true;
                    
                    // Mark as answered in navigation
                    const question = radio.closest('.question-box');
                    if (question) {
                        const questionNumber = question.id.replace('question-', '');
                        const navButton = document.querySelector(`.number-btn[data-question="${questionNumber}"]`);
                        if (navButton) {
                            navButton.style.backgroundColor = '#10b981';
                            navButton.style.color = 'white';
                            navButton.style.borderColor = '#059669';
                        }
                    }
                }
            });
        }
    } catch (e) {
        console.error('Error restoring saved answers:', e);
    }
});
    </script>
    @endpush
</x-test-layout>