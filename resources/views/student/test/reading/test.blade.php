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
        
        .part-header {
            background-color: #f3f4f6;
            padding: 12px 16px;
            margin: -20px -20px 20px -20px;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
        }
        
        .question-group-header {
            background-color: #fef3c7;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #92400e;
        }
        
        .question-instructions {
            background-color: #eff6ff;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
            color: #1e40af;
            font-style: italic;
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
        
        /* Fill in the blanks styles */
        .fill-blank-input {
            display: inline-block;
            width: 150px;
            padding: 4px 8px;
            margin: 0 4px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fef3c7;
        }
        
        .fill-blank-input:focus {
            outline: none;
            border-color: #f59e0b;
            background-color: #fffbeb;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.1);
        }
        
        .dropdown-select {
            display: inline-block;
            padding: 4px 8px;
            margin: 0 4px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            background-color: #d1fae5;
            cursor: pointer;
        }
        
        .dropdown-select:focus {
            outline: none;
            border-color: #10b981;
            background-color: #ecfdf5;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
        }
        
        .question-content {
            line-height: 1.8;
            color: #374151;
        }
        
        .blank-placeholder {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 2px 8px;
            margin: 0 2px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-block;
        }
        
        .dropdown-placeholder {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 2px 8px;
            margin: 0 2px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-block;
        }
        
        .passage-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #111827;
        }
        
        .passage-content {
            line-height: 1.8;
            color: #374151;
            text-align: justify;
        }
        
        .passage-content p {
            margin-bottom: 15px;
        }
        
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-left {
            display: flex;
            align-items: center;
            flex: 1;
            gap: 20px;
        }
        
        .review-section {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        .review-check {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .review-label {
            font-size: 14px;
            font-weight: 500;
            color: #212529;
            cursor: pointer;
            user-select: none;
        }
        
        .nav-section-container {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        
        .section-label {
            font-weight: 600;
            color: #212529;
            font-size: 14px;
        }
        
        .parts-nav {
            display: flex;
            gap: 8px;
            border-right: 2px solid #dee2e6;
            padding-right: 15px;
            margin-right: 10px;
        }
        
        .part-btn {
            padding: 6px 16px;
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .part-btn:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }
        
        .part-btn.active {
            background-color: #0066cc;
            color: white;
            border-color: #0066cc;
        }
        
        .nav-numbers {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            max-width: 600px;
        }
        
        .number-btn {
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .number-btn:hover {
            background-color: #e9ecef;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .number-btn.active {
            background-color: #0066cc;
            color: white;
            border-color: #0066cc;
            font-weight: 600;
        }
        
        .number-btn.answered {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        
        .number-btn.flagged {
            position: relative;
            overflow: visible;
        }
        
        .number-btn.flagged::after {
            content: '';
            position: absolute;
            top: -3px;
            right: -3px;
            width: 10px;
            height: 10px;
            background-color: #ffc107;
            border-radius: 50%;
            border: 2px solid #f8f9fa;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
        }
        
        .submit-test-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
        }
        
        .submit-test-button:hover {
            background-color: #218838;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        
        .submit-test-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
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
            
            .parts-nav {
                display: none;
            }
            
            .nav-numbers {
                max-width: 100%;
            }
            
            .review-section {
                padding: 6px 12px;
            }
            
            .section-label {
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
        <!-- Reading Passage(s) -->
        <div class="passage-section">
            @php
                // Group questions by part
                $questionsByPart = $testSet->questions->groupBy('part_number');
                $passages = $testSet->questions->where('question_type', 'passage')->sortBy('order_number');
            @endphp
            
            @if ($passages->count() > 0)
                @foreach ($passages as $passage)
                    <div class="passage-container mb-8">
                        @if($passage->instructions)
                            <div class="passage-title">{{ $passage->instructions }}</div>
                        @else
                            <div class="passage-title">Reading Passage {{ $loop->iteration }}</div>
                        @endif
                        
                        @if($passage->passage_text)
                            <div class="passage-content">
                                {!! nl2br(e($passage->passage_text)) !!}
                            </div>
                        @elseif($passage->content)
                            <div class="passage-content">
                                {!! nl2br(e($passage->content)) !!}
                            </div>
                        @endif
                        
                        @if ($passage->media_path)
                            <div class="mt-4">
                                <img src="{{ Storage::url($passage->media_path) }}" alt="Passage Image" class="max-w-full h-auto rounded">
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="bg-yellow-50 p-4 rounded-md">
                    <p class="text-yellow-700">No passage content found for this test.</p>
                </div>
            @endif
        </div>
        
        <!-- Questions Section -->
        <div class="questions-section">
            <form id="reading-form" action="{{ route('student.reading.submit', $attempt) }}" method="POST">
                @csrf
                
                @php
                    $allQuestions = $testSet->questions
                        ->where('question_type', '!=', 'passage')
                        ->sortBy(['part_number', 'order_number']);
                    $groupedQuestions = $allQuestions->groupBy('part_number');
                @endphp
                
                @foreach ($groupedQuestions as $partNumber => $partQuestions)
                    @if($partNumber)
                        <div class="part-header">
                            Part {{ $partNumber }}
                        </div>
                    @endif
                    
                    @php
                        $questionGroups = $partQuestions->groupBy('question_group');
                    @endphp
                    
                    @foreach ($questionGroups as $groupName => $questions)
                        @if($groupName)
                            <div class="question-group-header">
                                {{ $groupName }}
                            </div>
                        @endif
                        
                        @php
                            // Get unique instructions for this group
                            $instructions = $questions->pluck('instructions')->filter()->unique();
                        @endphp
                        
                        @foreach($instructions as $instruction)
                            <div class="question-instructions">
                                {{ $instruction }}
                            </div>
                        @endforeach
                        
                        @foreach ($questions as $question)
                            <div class="question-box" id="question-{{ $question->order_number }}">
                                @php
                                    // Process content for fill in the blanks
                                    $processedContent = $question->content;
                                    $hasBlanks = false;
                                    $hasDropdowns = false;
                                    
                                    // Check for blanks and dropdowns
                                    if (strpos($processedContent, '[BLANK_') !== false || strpos($processedContent, '[DROPDOWN_') !== false) {
                                        $hasBlanks = strpos($processedContent, '[BLANK_') !== false;
                                        $hasDropdowns = strpos($processedContent, '[DROPDOWN_') !== false;
                                        
                                        // Replace blanks with input fields
                                        $processedContent = preg_replace_callback('/\[BLANK_(\d+)\]/', function($matches) use ($question) {
                                            $blankNum = $matches[1];
                                            return '<input type="text" 
                                                    name="answers[' . $question->id . '][blank_' . $blankNum . ']" 
                                                    class="fill-blank-input" 
                                                    placeholder="____" 
                                                    data-blank="' . $blankNum . '">';
                                        }, $processedContent);
                                        
                                        // Replace dropdowns with select fields
                                        $processedContent = preg_replace_callback('/\[DROPDOWN_(\d+)\]/', function($matches) use ($question) {
                                            $dropdownNum = $matches[1];
                                            // You would need to get dropdown options from question data
                                            return '<select name="answers[' . $question->id . '][dropdown_' . $dropdownNum . ']" 
                                                    class="dropdown-select" 
                                                    data-dropdown="' . $dropdownNum . '">
                                                    <option value="">Choose</option>
                                                    <option value="option1">Option 1</option>
                                                    <option value="option2">Option 2</option>
                                                    </select>';
                                        }, $processedContent);
                                    }
                                @endphp
                                
                                <div class="question-number">
                                    {{ $question->order_number }}. 
                                    @if($hasBlanks || $hasDropdowns)
                                        <div class="question-content">{!! $processedContent !!}</div>
                                    @else
                                        {!! $question->content !!}
                                    @endif
                                </div>
                                
                                @if ($question->media_path)
                                    <div class="mb-3">
                                        <img src="{{ Storage::url($question->media_path) }}" alt="Question Image" class="max-w-full h-auto rounded">
                                    </div>
                                @endif
                                
                                @if(!$hasBlanks && !$hasDropdowns)
                                <div class="options-list">
                                    @switch($question->question_type)
                                        @case('multiple_choice')
                                            @foreach ($question->options as $optionIndex => $option)
                                                <div class="option-item">
                                                    <input type="radio" 
                                                           name="answers[{{ $question->id }}]" 
                                                           id="option-{{ $option->id }}" 
                                                           value="{{ $option->id }}" 
                                                           class="option-radio">
                                                    <label for="option-{{ $option->id }}">
                                                        <strong>{{ chr(65 + $optionIndex) }}.</strong> {{ $option->content }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @break
                                        
                                        @case('true_false')
                                        @case('yes_no')
                                            @foreach ($question->options as $option)
                                                <div class="option-item">
                                                    <input type="radio" 
                                                           name="answers[{{ $question->id }}]" 
                                                           id="option-{{ $option->id }}" 
                                                           value="{{ $option->id }}" 
                                                           class="option-radio">
                                                    <label for="option-{{ $option->id }}">{{ $option->content }}</label>
                                                </div>
                                            @endforeach
                                            @break
                                        
                                        @case('matching')
                                        @case('matching_headings')
                                        @case('matching_information')
                                        @case('matching_features')
                                            <select name="answers[{{ $question->id }}]" class="text-input">
                                                <option value="">Select your answer</option>
                                                @foreach ($question->options as $optionIndex => $option)
                                                    <option value="{{ $option->id }}">
                                                        {{ chr(65 + $optionIndex) }}. {{ $option->content }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @break
                                        
                                        @case('fill_blanks')
                                        @case('sentence_completion')
                                        @case('summary_completion')
                                        @case('short_answer')
                                        @default
                                            <input type="text" 
                                                   name="answers[{{ $question->id }}]" 
                                                   class="text-input" 
                                                   placeholder="Type your answer here"
                                                   maxlength="100">
                                            @break
                                    @endswitch
                                </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                @endforeach
                
                <button type="submit" id="submit-button" class="hidden">Submit</button>
            </form>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-left">
            <div class="review-section">
                <input type="checkbox" id="review-checkbox" class="review-check">
                <label for="review-checkbox" class="review-label">Review</label>
            </div>
            
            <div class="nav-section-container">
                <span class="section-label">Reading</span>
                
                {{-- Parts Navigation --}}
                <div class="parts-nav">
                    @php
                        $partsWithQuestions = $allQuestions->groupBy('part_number')->keys()->filter()->sort();
                    @endphp
                    
                    @foreach($partsWithQuestions as $partNum)
                        <button type="button" class="part-btn {{ $loop->first ? 'active' : '' }}" data-part="{{ $partNum }}">
                            Part {{ $partNum }}
                        </button>
                    @endforeach
                </div>
                
                {{-- Question Numbers --}}
                <div class="nav-numbers">
                    @php
                        $questionCount = $allQuestions->count();
                    @endphp
                    
                    @foreach($allQuestions as $index => $question)
                        <div class="number-btn {{ $index == 0 ? 'active' : '' }}" 
                             data-question="{{ $question->order_number }}"
                             data-part="{{ $question->part_number }}">
                            {{ $question->order_number }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="nav-right">
            <button type="button" id="submit-test-btn" class="submit-test-button">
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
                <strong>Answered Questions: <span id="answered-count">0</span> / {{ $allQuestions->count() }}</strong>
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
        const partButtons = document.querySelectorAll('.part-btn');
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const answeredCountSpan = document.getElementById('answered-count');
        
        // Part navigation
        partButtons.forEach(button => {
            button.addEventListener('click', function() {
                partButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const partNumber = this.dataset.part;
                // Find first question of this part
                const firstQuestionOfPart = document.querySelector(`.number-btn[data-part="${partNumber}"]`);
                if (firstQuestionOfPart) {
                    firstQuestionOfPart.click();
                }
            });
        });
        
        // Question navigation
        navButtons.forEach(button => {
            button.addEventListener('click', function() {
                navButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const questionNumber = this.dataset.question;
                const questionElement = document.getElementById(`question-${questionNumber}`);
                
                if (questionElement) {
                    questionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Update active part
                    const partNumber = this.dataset.part;
                    if (partNumber) {
                        partButtons.forEach(btn => {
                            if (btn.dataset.part === partNumber) {
                                btn.classList.add('active');
                            } else {
                                btn.classList.remove('active');
                            }
                        });
                    }
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
                
                // Check if all blanks/dropdowns in question are filled
                if (this.classList.contains('fill-blank-input') || this.classList.contains('dropdown-select')) {
                    checkAllBlanksInQuestion(question, navButton);
                }
                
                saveAllAnswers();
            });
            
            // Also handle text input on blur
            if (input.type === 'text') {
                input.addEventListener('blur', function() {
                    const question = this.closest('.question-box');
                    const questionNumber = question.id.replace('question-', '');
                    
                    const navButton = document.querySelector(`.number-btn[data-question="${questionNumber}"]`);
                    if (navButton) {
                        if (this.classList.contains('fill-blank-input')) {
                            checkAllBlanksInQuestion(question, navButton);
                        } else if (this.value.trim()) {
                            navButton.classList.add('answered');
                        } else {
                            navButton.classList.remove('answered');
                        }
                    }
                    
                    saveAllAnswers();
                });
            }
        });
        
        // Check if all blanks in a question are filled
        function checkAllBlanksInQuestion(questionElement, navButton) {
            const blanks = questionElement.querySelectorAll('.fill-blank-input, .dropdown-select');
            let allFilled = true;
            
            blanks.forEach(blank => {
                if (!blank.value.trim()) {
                    allFilled = false;
                }
            });
            
            if (navButton) {
                if (allFilled && blanks.length > 0) {
                    navButton.classList.add('answered');
                } else {
                    navButton.classList.remove('answered');
                }
            }
        }
        
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
            let answeredQuestions = 0;
            
            // Count regular questions
            answeredQuestions += document.querySelectorAll('input[type="radio"]:checked').length;
            answeredQuestions += document.querySelectorAll('input[type="text"]:not(.fill-blank-input)[value]:not([value=""])').length;
            answeredQuestions += document.querySelectorAll('select:not(.dropdown-select) option:checked:not([value=""])').length;
            
            // Count fill-in-the-blank questions
            const blankQuestions = new Set();
            document.querySelectorAll('.fill-blank-input, .dropdown-select').forEach(input => {
                const question = input.closest('.question-box');
                if (question) {
                    const blanks = question.querySelectorAll('.fill-blank-input, .dropdown-select');
                    let allFilled = true;
                    blanks.forEach(blank => {
                        if (!blank.value.trim()) {
                            allFilled = false;
                        }
                    });
                    if (allFilled && blanks.length > 0) {
                        blankQuestions.add(question.id);
                    }
                }
            });
            
            answeredQuestions += blankQuestions.size;
            
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
        
        // Scroll to first question on load
        const firstQuestion = document.getElementById('question-1');
        if (firstQuestion) {
            setTimeout(() => {
                firstQuestion.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 500);
        }
    });
    </script>
    @endpush
</x-test-layout>