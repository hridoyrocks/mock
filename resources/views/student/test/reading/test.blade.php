{{-- resources/views/student/test/reading/test.blade.php --}}
<x-test-layout>
    <x-slot:title>IELTS Reading Test</x-slot>
    
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
            padding-bottom: 100px;
            height: calc(100vh - 120px);
        }
        
        .passage-section {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            overflow-y: auto;
        }
        
        .questions-section {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            overflow-y: auto;
        }
        
        .question-box {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .question-box:last-child {
            border-bottom: none;
        }
        
        .question-number {
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .options-list {
            margin-left: 20px;
            margin-top: 10px;
        }
        
        .option-item {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }
        
        .option-radio {
            margin-right: 10px;
            margin-top: 2px;
        }
        
        .text-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .text-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
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
                grid-template-columns: 1fr;
                height: auto;
            }
            
            .nav-numbers {
                display: none;
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
                auto-submit-form-id="reading-form"
                position="integrated"
                :warning-time="600"
                :danger-time="300"
            />
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <!-- Reading Passage -->
        <div class="passage-section">
            <h2 class="text-lg font-semibold mb-4">Reading Passage</h2>
            
            @php
                $passage = $testSet->questions->where('question_type', 'passage')->first();
            @endphp
            
            @if ($passage)
                <div class="prose prose-sm max-w-none">
                    {!! nl2br(e($passage->content)) !!}
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
        
        <!-- Questions Section -->
        <div class="questions-section">
            <h2 class="text-lg font-semibold mb-4">Questions</h2>
            
            <form id="reading-form" action="{{ route('student.reading.submit', $attempt) }}" method="POST">
                @csrf
                
                @php
                    $questions = $testSet->questions->where('question_type', '!=', 'passage')->sortBy('order_number');
                @endphp
                
                @foreach ($questions as $question)
                    <div class="question-box" id="question-{{ $question->order_number }}">
                        <div class="question-number">
                            {{ $question->order_number }}. {!! nl2br(e($question->content)) !!}
                        </div>
                        
                        @if ($question->media_path)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $question->media_path) }}" alt="Question Image" class="max-w-full h-auto">
                            </div>
                        @endif
                        
                        <div class="options-list">
                            @switch($question->question_type)
                                @case('multiple_choice')
                                    @foreach ($question->options as $option)
                                        <div class="option-item">
                                            <input type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" class="option-radio">
                                            <label for="option-{{ $option->id }}">{{ $option->content }}</label>
                                        </div>
                                    @endforeach
                                    @break
                                
                                @case('true_false')
                                    @foreach ($question->options as $option)
                                        <div class="option-item">
                                            <input type="radio" name="answers[{{ $question->id }}]" id="option-{{ $option->id }}" value="{{ $option->id }}" class="option-radio">
                                            <label for="option-{{ $option->id }}">{{ $option->content }}</label>
                                        </div>
                                    @endforeach
                                    @break
                                
                                @case('matching')
                                    <select name="answers[{{ $question->id }}]" class="text-input">
                                        <option value="">Select your answer</option>
                                        @foreach ($question->options as $option)
                                            <option value="{{ $option->id }}">{{ $option->content }}</option>
                                        @endforeach
                                    </select>
                                    @break
                                
                                @case('fill_blank')
                                @case('short_answer')
                                @default
                                    <input type="text" name="answers[{{ $question->id }}]" class="text-input" placeholder="Your answer">
                                    @break
                            @endswitch
                        </div>
                    </div>
                @endforeach
                
                <button type="submit" id="submit-button" class="hidden">Submit</button>
            </form>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-left">
            <div>
                <input type="checkbox" id="review-checkbox">
                <label for="review-checkbox">Review</label>
            </div>
            <div class="nav-numbers">
                <span class="mr-2">Reading</span>
                @php
                    $questionCount = $questions->count();
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
        </div>
    </div>

    <!-- Submit Modal -->
    <div id="submit-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-title" style="color: #059669;">Submit Test?</div>
            <div class="modal-message">
                Are you sure you want to submit your test? You cannot change your answers after submission.
                <br><br>
                <strong>Answered Questions: <span id="answered-count">0</span> / {{ $questions->count() }}</strong>
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
        const submitButton = document.getElementById('submit-button');
        const navButtons = document.querySelectorAll('.number-btn');
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const answeredCountSpan = document.getElementById('answered-count');
        
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
        
        // Handle answer selection
        const inputs = document.querySelectorAll('input[type="radio"], input[type="text"], select');
        inputs.forEach(function(input) {
            input.addEventListener('change', function() {
                const question = this.closest('.question-box');
                const questionNumber = question.id.replace('question-', '');
                
                const navButton = document.querySelector(`.number-btn[data-question="${questionNumber}"]`);
                if (navButton && this.value) {
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
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked, input[type="text"]:not([value=""]), select option:checked:not([value=""])').length;
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
        
        // Save answers function
        function saveAllAnswers() {
            const formData = new FormData(document.getElementById('reading-form'));
            const answers = {};
            
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('answers[') && value) {
                    answers[key] = value;
                }
            }
            
            localStorage.setItem('testAnswers_{{ $attempt->id }}', JSON.stringify(answers));
        }
        
        // Periodically save answers
        setInterval(saveAllAnswers, 30000);
        
        // Load saved answers on page load
        try {
            const savedAnswers = localStorage.getItem('testAnswers_{{ $attempt->id }}');
            
            if (savedAnswers) {
                const answers = JSON.parse(savedAnswers);
                
                Object.keys(answers).forEach(key => {
                    const value = answers[key];
                    const input = document.querySelector(`[name="${key}"]`);
                    
                    if (input) {
                        if (input.type === 'radio') {
                            const radio = document.querySelector(`[name="${key}"][value="${value}"]`);
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
                        } else {
                            input.value = value;
                            if (value) {
                                const question = input.closest('.question-box');
                                if (question) {
                                    const questionNumber = question.id.replace('question-', '');
                                    const navButton = document.querySelector(`.number-btn[data-question="${questionNumber}"]`);
                                    if (navButton) {
                                        navButton.classList.add('answered');
                                    }
                                }
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