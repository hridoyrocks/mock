{{-- resources/views/student/test/listening/test.blade.php --}}
<x-test-layout>
    <x-slot:title>IELTS Listening Test</x-slot>
    
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
        }
        
        .test-title {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        .audio-info {
            border-left: 5px solid #0d6efd;
            background-color: #f0f7ff;
            padding: 15px;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        
        .questions-section {
            margin-top: 30px;
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
            z-index: 100;
        }
        
        .nav-left {
            display: flex;
            align-items: center;
        }
        
        .nav-numbers {
            display: flex;
            margin-left: 15px;
            flex-wrap: wrap;
            gap: 2px;
        }
        
        .number-btn {
            width: 28px;
            height: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            border: 1px solid #d1d5db;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .number-btn:hover {
            background-color: #e5e7eb;
        }
        
        .number-btn.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .number-btn.answered {
            background-color: #10b981;
            color: white;
            border-color: #059669;
        }
        
        .number-btn.flagged {
            border: 2px solid #f59e0b;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .pencil-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #ffc107;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .pencil-btn:hover {
            background-color: #ffca2c;
        }
        
        .arrow-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .arrow-btn:hover {
            background-color: #e5e7eb;
        }
        
        .arrow-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
            <div class="flex items-center ml-2">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071a1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <input type="range" min="0" max="100" value="75" class="ml-2 w-20" id="volume-slider">
            </div>
            
            {{-- ✅ ADD: Integrated Timer Component --}}
            <x-test-timer 
                :attempt="$attempt" 
                auto-submit-form-id="test-form"
                position="integrated"
                :warning-time="300"
                :danger-time="60"
            />
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <h1 class="test-title">Listening Test</h1>
        
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
            <button type="button" id="submit-test-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md mr-3 text-sm font-medium submit-btn">
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

    <!-- Warning & Submit Modals -->
    <div id="warning-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-title" style="color: #dc2626;">Warning!</div>
            <div class="modal-message">
                You cannot leave the test page once it has started. Leaving this page will count as an incomplete attempt.
            </div>
            <button class="modal-button" id="continue-test-btn">Continue Test</button>
        </div>
    </div>

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
                <button class="modal-button secondary" id="cancel-submit-btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Hidden Audio Element -->
    @if($testSet->questions->first() && $testSet->questions->first()->media_path)
        <audio id="test-audio" preload="auto" style="display:none;">
            <source src="{{ asset('storage/' . $testSet->questions->first()->media_path) }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endif
    
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const audio = document.getElementById('test-audio');
        const volumeSlider = document.getElementById('volume-slider');
        const questionsContainer = document.getElementById('questions-container');
        const submitButton = document.getElementById('submit-button');
        const navButtons = document.querySelectorAll('.number-btn');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const warningModal = document.getElementById('warning-modal');
        const continueTestBtn = document.getElementById('continue-test-btn');
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const answeredCountSpan = document.getElementById('answered-count');
        
        // Audio handling
        if (audio) {
            if (volumeSlider) {
                audio.volume = volumeSlider.value / 100;
                volumeSlider.addEventListener('input', function() {
                    audio.volume = this.value / 100;
                });
            }
            
            audio.play().catch(function(e) {
                console.log('Audio autoplay blocked:', e.message);
            });
            
            audio.addEventListener('ended', function() {
                console.log('Audio finished playing');
            });
        }
        
        // Question navigation
        navButtons.forEach(button => {
            button.addEventListener('click', function() {
                navButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
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
                const question = this.closest('.question-box');
                const questionNumber = question.id.replace('question-', '');
                
                const navButton = document.querySelector(`.number-btn[data-question="${questionNumber}"]`);
                if (navButton) {
                    navButton.classList.add('answered');
                }
                
                saveAllAnswers();
            });
        });
        
        // Review checkbox functionality
        const reviewCheckbox = document.getElementById('review-checkbox');
        reviewCheckbox.addEventListener('change', function() {
            const currentQuestion = document.querySelector('.number-btn.active');
            if (currentQuestion) {
                if (this.checked) {
                    currentQuestion.classList.add('flagged');
                } else {
                    currentQuestion.classList.remove('flagged');
                }
            }
        });
        
        // Submit functionality
        submitTestBtn.addEventListener('click', function() {
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            answeredCountSpan.textContent = answeredQuestions;
            submitModal.style.display = 'flex';
        });
        
        confirmSubmitBtn.addEventListener('click', function() {
            if (window.UniversalTimer) {
                window.UniversalTimer.stop();
            }
            saveAllAnswers();
            submitButton.click();
        });
        
        cancelSubmitBtn.addEventListener('click', function() {
            submitModal.style.display = 'none';
        });
        
        continueTestBtn.addEventListener('click', function() {
            warningModal.style.display = 'none';
        });
        
        // Save answers function
        function saveAllAnswers() {
            const selectedRadios = document.querySelectorAll('input[type="radio"]:checked');
            const answers = {};
            selectedRadios.forEach(radio => {
                const questionId = radio.name.replace('answers[', '').replace(']', '');
                answers[questionId] = radio.value;
            });
            
            localStorage.setItem('testAnswers_{{ $attempt->id }}', JSON.stringify(answers));
        }
        
        // Periodically save answers
        setInterval(saveAllAnswers, 30000);
        
        // Load saved answers on page load
        try {
            const savedAnswers = localStorage.getItem('testAnswers_{{ $attempt->id }}');
            
            if (savedAnswers) {
                const answers = JSON.parse(savedAnswers);
                
                Object.keys(answers).forEach(questionId => {
                    const optionId = answers[questionId];
                    const radio = document.querySelector(`input[name="answers[${questionId}]"][value="${optionId}"]`);
                    if (radio) {
                        radio.checked = true;
                        
                        const question = radio.closest('.question-box');
                        if (question) {
                            const questionNumber = question.id.replace('question-', '');
                            const navButton = document.querySelector(`.number-btn[data-question="${questionNumber}"]`);
                            if (navButton) {
                                navButton.classList.add('answered');
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