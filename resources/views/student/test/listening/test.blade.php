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
        /* IELTS Listening Test - Minimal CSS */
        
        /* ========== BASE STYLES ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        /* ========== HEADER STYLES ========== */
        .ielts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 24px;
            background-color: white;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .ielts-header-left {
            display: flex;
            align-items: center;
        }
        
        .user-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 24px;
            background-color: #1a1a1a;
            color: white;
            border-bottom: 1px solid #333;
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
        
        /* ========== CONTENT AREA - FULL WIDTH WHITE ========== */
        .content-area {
            background: white;
            min-height: calc(100vh - 140px);
            padding: 30px 40px 120px;
        }
        
        /* ========== PART SECTIONS ========== */
        .part-section {
            margin-bottom: 40px;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .part-section:not(.active) {
            display: none;
        }
        
        /* ========== PART HEADER - CARD STYLE ========== */
        .part-header {
            background: #f0f0f0;
            padding: 16px 24px;
            margin: 0 0 30px 0;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        
        .part-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 4px;
        }
        
        .part-instruction {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.5;
        }
        
        /* ========== QUESTION GROUP HEADERS ========== */
        .question-group-header {
            font-size: 16px;
            font-weight: 700;  /* Bold */
            color: #1f2937;
            margin: 30px 0 10px 0;
        }
        
        .question-instruction {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 25px;
        }
        
        /* ========== QUESTION ITEMS - LEFT ALIGNED ========== */
        .question-item {
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 15px;
        }
        
        .question-item:last-child {
            border-bottom: none;
        }
        
        .question-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .question-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
            flex-shrink: 0;
        }
        
        .question-text {
            flex: 1;
            line-height: 1.6;
            color: #1f2937;
        }
        
        /* ========== OPTIONS STYLING ========== */
        .options-list {
            margin-left: 40px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .option-item {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            background: #fafafa;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .option-item:hover {
            background: #f0f9ff;
            border-color: #3b82f6;
        }
        
        .option-radio {
            margin-right: 10px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .option-label {
            flex: 1;
            cursor: pointer;
            font-size: 14px;
        }
        
        /* ========== INPUT FIELDS ========== */
        .answer-input {
            margin-left: 40px;
        }
        
        .text-input, .select-input {
            width: 300px;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #fafafa;
        }
        
        .text-input:hover, .select-input:hover {
            border-color: #cbd5e0;
            background: white;
        }
        
        .text-input:focus, .select-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* ========== SPECIAL QUESTION TYPES STYLES ========== */
        /* Matching Questions */
        .matching-container {
            user-select: none;
            margin-left: 40px;
            margin-top: 20px;
        }
        
        .matching-grid {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 20px;
            align-items: start;
        }
        
        .matching-item {
            padding: 12px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        
        .matching-option {
            padding: 12px;
            margin-bottom: 10px;
            background: white;
            border: 2px solid #3b82f6;
            border-radius: 6px;
            cursor: move;
            transition: all 0.2s;
        }
        
        .matching-option:hover {
            transform: scale(1.02);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .matching-option.dragging {
            opacity: 0.5;
        }
        
        .matching-option.drag-over {
            background: #dbeafe !important;
            transform: scale(1.05);
        }
        
        /* Form Completion */
        .form-completion-container {
            margin-left: 40px;
            margin-top: 20px;
        }
        
        .form-wrapper {
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            max-width: 500px;
        }
        
        .form-title {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }
        
        .form-field-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
        }
        
        .form-label {
            min-width: 120px;
            padding-right: 15px;
            font-weight: 500;
            color: #374151;
        }
        
        .form-question-number {
            position: absolute;
            left: -30px;
            font-weight: 600;
            color: #6b7280;
            font-size: 13px;
        }
        
        .form-input {
            flex: 1;
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Diagram Labeling */
        .diagram-container {
            margin-left: 40px;
            margin-top: 20px;
        }
        
        .diagram-wrapper {
            position: relative;
            display: inline-block;
        }
        
        .diagram-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .diagram-hotspot {
            position: absolute;
            transform: translate(-50%, -50%);
            cursor: help;
            transition: transform 0.2s;
        }
        
        .diagram-hotspot:hover {
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .hotspot-marker {
            width: 32px;
            height: 32px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .diagram-answers {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
        }
        
        .diagram-answer-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        
        .diagram-label {
            display: inline-flex;
            width: 28px;
            height: 28px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            flex-shrink: 0;
        }
        
        .diagram-number {
            font-weight: 600;
            color: #6b7280;
            font-size: 13px;
            margin-right: 5px;
        }
        
        .diagram-input {
            flex: 1;
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 13px;
        }
        
        /* ========== BOTTOM NAVIGATION ========== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .nav-left {
            display: flex;
            align-items: center;
            flex: 1;
            gap: 16px;
        }
        
        .review-section {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: white;
            border: 1px solid #e0e0e0;
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
            color: #2c3e50;
            cursor: pointer;
            user-select: none;
        }
        
        .nav-section-container {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }
        
        .section-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }
        
        .parts-nav {
            display: flex;
            gap: 6px;
            border-right: 1px solid #e0e0e0;
            padding-right: 16px;
            margin-right: 12px;
        }
        
        .part-btn {
            padding: 6px 12px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .part-btn:hover {
            background: #f8f9fa;
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .part-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
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
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .number-btn:hover {
            background: #f8f9fa;
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .number-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            font-weight: 600;
        }
        
        .number-btn.answered {
            background: #10b981;
            color: white;
            border-color: #10b981;
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
            background: #f59e0b;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .number-btn.hidden-part {
            display: none;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .btn-secondary {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: white;
            color: #374151;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover {
            border-color: #3b82f6;
            color: #3b82f6;
            background: #eff6ff;
        }
        
        .notes-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            background-color: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 600;
            border-radius: 10px;
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
        }
        
        /* ========== MODAL STYLES ========== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 24px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1a202c;
        }
        
        .modal-message {
            font-size: 16px;
            margin-bottom: 24px;
            line-height: 1.5;
            color: #4a5568;
        }
        
        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .modal-button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin: 0 6px;
            transition: all 0.2s ease;
        }
        
        .modal-button:hover {
            background: #2563eb;
        }
        
        .modal-button.secondary {
            background: #6b7280;
        }
        
        .modal-button.secondary:hover {
            background: #4b5563;
        }
        
        /* ========== HIGHLIGHT & NOTES STYLES ========== */
        .highlighted-text {
            background-color: #fde047;
            padding: 1px 2px;
            border-radius: 2px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .highlighted-text:hover {
            background-color: #facc15;
        }
        
        .note-text {
            background-color: #fee2e2;
            border-bottom: 1px solid #dc2626;
            padding: 1px 2px;
            border-radius: 2px;
            cursor: pointer;
        }
        
        /* Annotation Menu */
        #annotation-menu {
            position: fixed;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 6px;
            display: flex;
            gap: 6px;
            z-index: 99999;
        }
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .content-area {
                padding: 20px 20px 120px;
            }
            
            .parts-nav {
                display: none;
            }
            
            .nav-numbers {
                max-width: 100%;
                gap: 3px;
            }
            
            .number-btn {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
            
            .bottom-nav {
                flex-direction: column;
                gap: 10px;
                padding: 10px;
            }
            
            .nav-left {
                width: 100%;
                flex-direction: column;
                gap: 10px;
            }
            
            .submit-test-button {
                width: 100%;
            }
            
            /* Special types mobile */
            .matching-grid {
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }
            
            .matching-lines {
                display: none;
            }
            
            .form-completion-container table {
                font-size: 13px;
            }
            
            .diagram-container img {
                max-width: 100% !important;
            }
        }
        
        /* ========== ANIMATIONS ========== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
            <button class="bg-white text-black px-3 py-1 rounded text-sm help-button" id="help-button">Help ?</button>
            <button class="bg-white text-black px-3 py-1 rounded text-sm hide-button">Hide</button>
            <div class="flex items-center ml-2">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071a1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <input type="range" min="0" max="100" value="75" class="ml-2 w-20" id="volume-slider">
            </div>
            
            {{-- Integrated Timer Component --}}
            <x-test-timer 
                :attempt="$attempt" 
                auto-submit-form-id="listening-form"
                position="integrated"
                :warning-time="300"
                :danger-time="60"
            />
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <form id="listening-form" action="{{ route('student.listening.submit', $attempt) }}" method="POST">
            @csrf
            
            @php
                $allQuestions = $testSet->questions->sortBy('order_number');
                $groupedQuestions = $allQuestions->groupBy('part_number');
                $currentQuestionNumber = 1;
                
                // Pre-calculate total questions including sub-questions
                $totalQuestionCount = 0;
                foreach ($allQuestions as $q) {
                    if ($q->question_type === 'matching' && $q->matching_pairs) {
                        $totalQuestionCount += count($q->matching_pairs);
                    } elseif ($q->question_type === 'form_completion' && $q->form_structure) {
                        $totalQuestionCount += count($q->form_structure['fields'] ?? []);
                    } elseif ($q->question_type === 'plan_map_diagram' && $q->diagram_hotspots) {
                        $totalQuestionCount += count($q->diagram_hotspots);
                    } else {
                        $totalQuestionCount++;
                    }
                }
            @endphp
            
            @foreach ($groupedQuestions as $partNumber => $partQuestions)
                <div class="part-section {{ $loop->first ? 'active' : '' }}" data-part="{{ $partNumber }}">
                    <!-- Part Header -->
                    <div class="part-header">
                        <div class="part-title">Part {{ $partNumber }}</div>
                        <div class="part-instruction">Listen and answer questions {{ $partNumber == 1 ? '1-10' : ($partNumber == 2 ? '11-20' : ($partNumber == 3 ? '21-30' : '31-40')) }}.</div>
                    </div>

                    <!-- Questions -->
                    @php
                        $questionGroups = $partQuestions->groupBy('question_group');
                    @endphp
                    
                    @foreach ($questionGroups as $groupName => $questions)
                        @if($groupName)
                            <div class="question-group-header">{{ $groupName }}</div>
                        @endif
                        
                        @php
                            $instructions = $questions->pluck('instructions')->filter()->unique();
                        @endphp
                        
                        @foreach($instructions as $instruction)
                            <div class="question-instruction">{{ $instruction }}</div>
                        @endforeach
                        
                        @foreach ($questions as $question)
                            @php
                                $displayNumber = $currentQuestionNumber;
                            @endphp
                            
                            @if($question->question_type === 'matching' && $question->matching_pairs)
                                {{-- MATCHING QUESTION --}}
                                <div class="question-item" id="question-{{ $question->id }}">
                                    <div class="question-content">
                                        <span class="question-number">{{ $displayNumber }}-{{ $displayNumber + count($question->matching_pairs) - 1 }}</span>
                                        <div class="question-text">{!! $question->content !!}</div>
                                    </div>
                                    
                                    <div class="matching-container">
                                        <div class="matching-grid">
                                            <!-- Left side - Questions -->
                                            <div class="matching-left">
                                                @foreach($question->matching_pairs as $index => $pair)
                                                    <div class="matching-item">
                                                        <strong>{{ $displayNumber + $index }}.</strong> {{ $pair['left'] }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            <!-- Center - Lines (for desktop) -->
                                            <div class="matching-lines" style="width: 60px; position: relative;">
                                                <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                                    <!-- Lines will be drawn here by JavaScript -->
                                                </svg>
                                            </div>
                                            
                                            <!-- Right side - Options -->
                                            <div class="matching-right">
                                                @php
                                                    $options = collect($question->matching_pairs)->pluck('right')->shuffle();
                                                @endphp
                                                @foreach($options as $index => $option)
                                                    <div class="matching-option" data-option="{{ $option }}">
                                                        <strong>{{ chr(65 + $index) }}.</strong> {{ $option }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden inputs for answers -->
                                        @foreach($question->matching_pairs as $index => $pair)
                                            <input type="hidden" 
                                                   name="answers[{{ $question->id }}_{{ $index }}]" 
                                                   class="matching-answer"
                                                   data-question-number="{{ $displayNumber + $index }}"
                                                   data-pair-index="{{ $index }}">
                                        @endforeach
                                    </div>
                                </div>
                                @php $currentQuestionNumber += count($question->matching_pairs); @endphp
                                
                            @elseif($question->question_type === 'form_completion' && $question->form_structure)
                                {{-- FORM COMPLETION QUESTION --}}
                                <div class="question-item" id="question-{{ $question->id }}">
                                    <div class="question-content">
                                        <span class="question-number">{{ $displayNumber }}-{{ $displayNumber + count($question->form_structure['fields']) - 1 }}</span>
                                        <div class="question-text">{!! $question->content !!}</div>
                                    </div>
                                    
                                    <div class="form-completion-container">
                                        <div class="form-wrapper">
                                            <h4 class="form-title">{{ $question->form_structure['title'] ?? 'Form' }}</h4>
                                            
                                            @foreach($question->form_structure['fields'] as $index => $field)
                                                <div class="form-field-row">
                                                    <span class="form-question-number">{{ $displayNumber + $index }}</span>
                                                    <label class="form-label">{{ $field['label'] }}:</label>
                                                    <input type="text" 
                                                           name="answers[{{ $question->id }}_{{ $index }}]"
                                                           class="form-input"
                                                           placeholder="Type answer here"
                                                           maxlength="30"
                                                           data-question-number="{{ $displayNumber + $index }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @php $currentQuestionNumber += count($question->form_structure['fields']); @endphp
                                
                            @elseif($question->question_type === 'plan_map_diagram' && $question->diagram_hotspots)
                                {{-- DIAGRAM LABELING QUESTION --}}
                                <div class="question-item" id="question-{{ $question->id }}">
                                    <div class="question-content">
                                        <span class="question-number">{{ $displayNumber }}-{{ $displayNumber + count($question->diagram_hotspots) - 1 }}</span>
                                        <div class="question-text">{!! $question->content !!}</div>
                                    </div>
                                    
                                    <div class="diagram-container">
                                        <div class="diagram-wrapper">
                                            <img src="{{ asset('storage/' . $question->media_path) }}" 
                                                 class="diagram-image"
                                                 alt="Diagram">
                                            
                                            <!-- Add hotspot markers -->
                                            @foreach($question->diagram_hotspots as $hotspot)
                                                <div class="diagram-hotspot" 
                                                     style="left: {{ $hotspot['x'] }}%; top: {{ $hotspot['y'] }}%;">
                                                    <div class="hotspot-marker">{{ $hotspot['label'] }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Answer inputs -->
                                        <div class="diagram-answers">
                                            @foreach($question->diagram_hotspots as $index => $hotspot)
                                                <div class="diagram-answer-item">
                                                    <span class="diagram-label">{{ $hotspot['label'] }}</span>
                                                    <span class="diagram-number">{{ $displayNumber + $index }}.</span>
                                                    <input type="text" 
                                                           name="answers[{{ $question->id }}_{{ $index }}]"
                                                           class="diagram-input"
                                                           placeholder="Type answer"
                                                           maxlength="30"
                                                           data-question-number="{{ $displayNumber + $index }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @php $currentQuestionNumber += count($question->diagram_hotspots); @endphp
                                
                            @else
                                {{-- REGULAR QUESTIONS --}}
                                <div class="question-item" id="question-{{ $question->id }}">
                                    <div class="question-content">
                                        <span class="question-number">{{ $displayNumber }}</span>
                                        <div class="question-text">{!! $question->content !!}</div>
                                    </div>
                                    
                                    @switch($question->question_type)
                                        @case('multiple_choice')
                                            <div class="options-list">
                                                @foreach ($question->options as $optionIndex => $option)
                                                    <label class="option-item">
                                                        <input type="radio" 
                                                               name="answers[{{ $question->id }}]" 
                                                               value="{{ $option->id }}" 
                                                               class="option-radio"
                                                               data-question-number="{{ $displayNumber }}">
                                                        <span class="option-label">
                                                            <strong>{{ chr(65 + $optionIndex) }}.</strong> {{ $option->content }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @break
                                        
                                        @case('form_completion')
                                        @case('note_completion')
                                        @case('sentence_completion')
                                        @case('short_answer')
                                            <div class="answer-input">
                                                <input type="text" 
                                                       name="answers[{{ $question->id }}]" 
                                                       class="text-input" 
                                                       placeholder="Type your answer"
                                                       maxlength="50"
                                                       data-question-number="{{ $displayNumber }}">
                                            </div>
                                            @break
                                        
                                        @case('matching')
                                        @case('plan_map_diagram')
                                            <div class="answer-input">
                                                <select name="answers[{{ $question->id }}]" 
                                                        class="select-input" 
                                                        data-question-number="{{ $displayNumber }}">
                                                    <option value="">Select your answer</option>
                                                    @foreach ($question->options as $optionIndex => $option)
                                                        <option value="{{ $option->id }}">
                                                            {{ chr(65 + $optionIndex) }}. {{ $option->content }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @break
                                    @endswitch
                                </div>
                                @php $currentQuestionNumber++; @endphp
                            @endif
                        @endforeach
                    @endforeach
                </div>
            @endforeach
            
            <button type="submit" id="submit-button" class="hidden">Submit</button>
        </form>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-left">
            <div class="review-section">
                <input type="checkbox" id="review-checkbox" class="review-check">
                <label for="review-checkbox" class="review-label">Flag</label>
            </div>
            
            <div class="nav-section-container">
                <span class="section-label">Listening</span>
                
                {{-- Parts Navigation --}}
                <div class="parts-nav">
                    @foreach($groupedQuestions->keys() as $partNum)
                        <button type="button" class="part-btn {{ $loop->first ? 'active' : '' }}" data-part="{{ $partNum }}">
                            Part {{ $partNum }}
                        </button>
                    @endforeach
                </div>
                
                {{-- Question Numbers --}}
                <div class="nav-numbers">
                    @php 
                        $navQuestionNum = 1;
                        $questionIdMap = [];
                    @endphp
                    @foreach($allQuestions as $question)
                        @if($question->question_type === 'matching' && $question->matching_pairs)
                            @foreach($question->matching_pairs as $index => $pair)
                                @php $questionIdMap[$navQuestionNum] = $question->id; @endphp
                                <div class="number-btn {{ $navQuestionNum == 1 ? 'active' : '' }}" 
                                     data-question="{{ $question->id }}"
                                     data-sub-index="{{ $index }}"
                                     data-display-number="{{ $navQuestionNum }}"
                                     data-part="{{ $question->part_number }}">
                                    {{ $navQuestionNum++ }}
                                </div>
                            @endforeach
                        @elseif($question->question_type === 'form_completion' && $question->form_structure)
                            @foreach($question->form_structure['fields'] as $index => $field)
                                @php $questionIdMap[$navQuestionNum] = $question->id; @endphp
                                <div class="number-btn {{ $navQuestionNum == 1 ? 'active' : '' }}" 
                                     data-question="{{ $question->id }}"
                                     data-sub-index="{{ $index }}"
                                     data-display-number="{{ $navQuestionNum }}"
                                     data-part="{{ $question->part_number }}">
                                    {{ $navQuestionNum++ }}
                                </div>
                            @endforeach
                        @elseif($question->question_type === 'plan_map_diagram' && $question->diagram_hotspots)
                            @foreach($question->diagram_hotspots as $index => $hotspot)
                                @php $questionIdMap[$navQuestionNum] = $question->id; @endphp
                                <div class="number-btn {{ $navQuestionNum == 1 ? 'active' : '' }}" 
                                     data-question="{{ $question->id }}"
                                     data-sub-index="{{ $index }}"
                                     data-display-number="{{ $navQuestionNum }}"
                                     data-part="{{ $question->part_number }}">
                                    {{ $navQuestionNum++ }}
                                </div>
                            @endforeach
                        @else
                            @php $questionIdMap[$navQuestionNum] = $question->id; @endphp
                            <div class="number-btn {{ $navQuestionNum == 1 ? 'active' : '' }}" 
                                 data-question="{{ $question->id }}"
                                 data-display-number="{{ $navQuestionNum }}"
                                 data-part="{{ $question->part_number }}">
                                {{ $navQuestionNum++ }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="nav-right">
            <button type="button" class="btn-secondary" id="notes-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Notes
                <span class="notes-badge" id="notes-count" style="display: none;">0</span>
            </button>
            
            <button type="button" class="btn-secondary" id="fullscreen-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                Fullscreen
            </button>
            
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
                Are you sure you want to submit your test? You cannot change your answers after submission.
                <br><br>
                <strong>Answered Questions: <span id="answered-count">0</span> / {{ $totalQuestionCount }}</strong>
            </div>
            <div class="modal-buttons">
                <button class="modal-button" id="confirm-submit-btn">Yes, Submit</button>
                <button class="modal-button secondary" id="cancel-submit-btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Hidden Audio Elements -->
@foreach($groupedQuestions->keys() as $partNumber)
    @php
        // First try to get part audio
        $partAudio = $testSet->getPartAudio($partNumber);
        $audioPath = null;
        
        if ($partAudio) {
            $audioPath = $partAudio->audio_path;
        } else {
            // Fallback: Find first question with audio in this part
            $firstQuestionWithAudio = $testSet->questions()
                ->where('part_number', $partNumber)
                ->where('use_part_audio', false)
                ->whereNotNull('media_path')
                ->first();
                
            if ($firstQuestionWithAudio) {
                $audioPath = $firstQuestionWithAudio->media_path;
            }
        }
    @endphp
    
    @if($audioPath)
        <audio id="test-audio-{{ $partNumber }}" preload="auto" style="display:none;">
            <source src="{{ asset('storage/' . $audioPath) }}" type="audio/mpeg">
            <source src="{{ asset('storage/' . $audioPath) }}" type="audio/ogg">
            <source src="{{ asset('storage/' . $audioPath) }}" type="audio/wav">
            Your browser does not support the audio element.
        </audio>
    @else
        <!-- No audio for this part -->
        <div id="no-audio-{{ $partNumber }}" style="display:none;" 
             data-message="No audio available for Part {{ $partNumber }}">
        </div>
    @endif
@endforeach
    
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const testConfig = {
        attemptId: {{ $attempt->id }},
        testSetId: {{ $testSet->id }},
        totalQuestions: {{ $totalQuestionCount }}
    };
    
    // Elements
    const form = document.getElementById('listening-form');
    const submitButton = document.getElementById('submit-button');
    const submitTestBtn = document.getElementById('submit-test-btn');
    const submitModal = document.getElementById('submit-modal');
    const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
    const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
    const answeredCountSpan = document.getElementById('answered-count');
    const reviewCheckbox = document.getElementById('review-checkbox');
    const notesBtn = document.getElementById('notes-btn');
    const notesCount = document.getElementById('notes-count');
    const volumeSlider = document.getElementById('volume-slider');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    
    // Part Navigation
    const partButtons = document.querySelectorAll('.part-btn');
    const partSections = document.querySelectorAll('.part-section');
    const numberButtons = document.querySelectorAll('.number-btn');
    
    // Current Audio
    let currentAudio = null;
    let audioPlaybackPosition = {};
    
    // ========== Fullscreen Functionality ==========
    fullscreenBtn.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            // Enter fullscreen
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
            // Update button text and icon
            this.innerHTML = `
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V5m0 0h4m-4 0l5 5m-5 10v-4m0 4h4m-4 0l5-5m5-5v4m0-4h-4m4 0l-5 5m-5 5h4m0 0v4m0-4l-5-5"/>
                </svg>
                Exit Fullscreen
            `;
        } else {
            // Exit fullscreen
            document.exitFullscreen();
            // Update button text and icon
            this.innerHTML = `
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                Fullscreen
            `;
        }
    });
    
    // Update button when fullscreen changes (e.g., user presses ESC)
    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            fullscreenBtn.innerHTML = `
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                Fullscreen
            `;
        }
    });
    
    // ========== Part Navigation ==========
    partButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetPart = this.dataset.part;
            switchToPart(targetPart);
        });
    });
    
    function switchToPart(targetPart) {
        // Save current audio position
        if (currentAudio) {
            const currentPart = document.querySelector('.part-btn.active').dataset.part;
            audioPlaybackPosition[currentPart] = currentAudio.currentTime;
            currentAudio.pause();
        }
        
        // Update active button
        partButtons.forEach(btn => btn.classList.remove('active'));
        const targetButton = document.querySelector(`.part-btn[data-part="${targetPart}"]`);
        if (targetButton) {
            targetButton.classList.add('active');
        }
        
        // Show target part
        partSections.forEach(section => {
            section.classList.remove('active');
            if (section.dataset.part === targetPart) {
                section.classList.add('active');
            }
        });
        
        // Update number buttons visibility
        updateNumberButtonsVisibility(targetPart);
        
        // Play audio for this part
        playPartAudio(targetPart);
    }
    
    // ========== Question Navigation ==========
    numberButtons.forEach(button => {
        button.addEventListener('click', function() {
            navigateToQuestion(this);
        });
    });
    
    function navigateToQuestion(button) {
        // Update active button
        numberButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        const questionId = button.dataset.question;
        const subIndex = button.dataset.subIndex;
        const questionElement = document.getElementById(`question-${questionId}`);
        
        if (questionElement) {
            // Switch to correct part if needed
            const partNumber = button.dataset.part;
            const currentActivePart = document.querySelector('.part-btn.active');
            if (currentActivePart && currentActivePart.dataset.part !== partNumber) {
                switchToPart(partNumber);
            }
            
            // Scroll to question
            questionElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            
            // Focus on specific sub-question input if applicable
            if (subIndex !== undefined) {
                const input = questionElement.querySelector(`input[name="answers[${questionId}_${subIndex}]"]`);
                if (input) {
                    setTimeout(() => input.focus(), 300);
                }
            }
        }
        
        // Update review checkbox
        reviewCheckbox.checked = button.classList.contains('flagged');
    }
    
    // ========== Review/Flag Functionality ==========
    reviewCheckbox.addEventListener('change', function() {
        const currentQuestion = document.querySelector('.number-btn.active');
        if (currentQuestion) {
            if (this.checked) {
                currentQuestion.classList.add('flagged');
            } else {
                currentQuestion.classList.remove('flagged');
            }
            saveFlaggedQuestions();
        }
    });
    
    function saveFlaggedQuestions() {
        const flagged = [];
        document.querySelectorAll('.number-btn.flagged').forEach(btn => {
            flagged.push(btn.dataset.displayNumber);
        });
        localStorage.setItem(`flaggedQuestions_${testConfig.attemptId}`, JSON.stringify(flagged));
    }
    
    function loadFlaggedQuestions() {
        try {
            const flagged = JSON.parse(localStorage.getItem(`flaggedQuestions_${testConfig.attemptId}`) || '[]');
            flagged.forEach(num => {
                const btn = document.querySelector(`.number-btn[data-display-number="${num}"]`);
                if (btn) btn.classList.add('flagged');
            });
        } catch (e) {
            console.error('Error loading flagged questions:', e);
        }
    }
    
    // ========== Answer Tracking ==========
    document.querySelectorAll('input[type="radio"], input[type="text"], select').forEach(input => {
        input.addEventListener('change', function() {
            handleAnswerChange(this);
        });
        
        // Also track input for text fields
        if (input.type === 'text') {
            input.addEventListener('input', debounce(function() {
                handleAnswerChange(this);
            }, 500));
        }
    });
    
    function handleAnswerChange(input) {
        const questionNumber = input.dataset.questionNumber;
        if (questionNumber) {
            const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
            if (navButton) {
                if (input.value && input.value.trim()) {
                    navButton.classList.add('answered');
                } else {
                    navButton.classList.remove('answered');
                }
            }
        }
        saveAllAnswers();
        updateAnswerCount();
    }
    
    // ========== Audio Controls ==========
    function playPartAudio(partNumber) {
        // Stop current audio
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.removeEventListener('ended', handleAudioEnded);
        }
        
        // Check if audio exists for this part
        const audioElement = document.getElementById(`test-audio-${partNumber}`);
        const noAudioElement = document.getElementById(`no-audio-${partNumber}`);
        
        if (audioElement) {
            currentAudio = audioElement;
            
            // Set volume
            if (volumeSlider) {
                currentAudio.volume = volumeSlider.value / 100;
            }
            
            // Restore playback position if exists
            if (audioPlaybackPosition[partNumber]) {
                currentAudio.currentTime = audioPlaybackPosition[partNumber];
            }
            
            // Add ended event listener
            currentAudio.addEventListener('ended', handleAudioEnded);
            
            // Play audio
            currentAudio.play().catch(e => {
                console.log('Audio autoplay blocked:', e);
                showAudioPlayButton();
            });
            
            // Show audio controls
            showAudioControls(true);
        } else if (noAudioElement) {
            // No audio available for this part
            console.warn(noAudioElement.dataset.message);
            showAudioControls(false);
            showNoAudioMessage(partNumber);
        }
    }
    
    function handleAudioEnded() {
        // Audio finished playing
        const currentPart = parseInt(document.querySelector('.part-btn.active').dataset.part);
        
        // Check if there's a next part
        if (currentPart < 4) {
            const nextPart = currentPart + 1;
            const nextPartBtn = document.querySelector(`.part-btn[data-part="${nextPart}"]`);
            
            if (nextPartBtn) {
                // Auto-advance to next part after a short delay
                setTimeout(() => {
                    if (confirm(`Part ${currentPart} audio completed. Move to Part ${nextPart}?`)) {
                        switchToPart(nextPart.toString());
                    }
                }, 1000);
            }
        }
    }
    
    function showAudioControls(hasAudio) {
        // You can add audio control buttons here if needed
        // For example: play/pause, replay, etc.
    }
    
    function showNoAudioMessage(partNumber) {
        // Show a message that no audio is available
        const messageDiv = document.createElement('div');
        messageDiv.className = 'audio-message';
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 14px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        `;
        messageDiv.textContent = `No audio available for Part ${partNumber}`;
        
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
    
    function showAudioPlayButton() {
        const playBtn = document.createElement('button');
        playBtn.className = 'audio-play-btn';
        playBtn.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #3b82f6;
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        `;
        playBtn.innerHTML = `
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="display: inline-block; margin-right: 8px;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
            </svg>
            Click to Play Audio
        `;
        
        playBtn.addEventListener('click', function() {
            if (currentAudio) {
                currentAudio.play();
                playBtn.remove();
            }
        });
        
        document.body.appendChild(playBtn);
    }
    
    // Volume control
    if (volumeSlider) {
        volumeSlider.addEventListener('input', function() {
            if (currentAudio) {
                currentAudio.volume = this.value / 100;
            }
            // Save volume preference
            localStorage.setItem('audioVolume', this.value);
        });
        
        // Load saved volume
        const savedVolume = localStorage.getItem('audioVolume');
        if (savedVolume) {
            volumeSlider.value = savedVolume;
        }
    }
    
    // ========== Keyboard Navigation ==========
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + Arrow keys for navigation
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'ArrowRight':
                    e.preventDefault();
                    navigateToNextQuestion();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    navigateToPreviousQuestion();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    navigateToPreviousPart();
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    navigateToNextPart();
                    break;
            }
        }
        
        // Number keys for quick navigation (1-9)
        if (!e.ctrlKey && !e.metaKey && !e.altKey && e.key >= '1' && e.key <= '9') {
            const input = document.activeElement;
            if (input.tagName !== 'INPUT' && input.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const targetQuestion = parseInt(e.key);
                const currentPart = document.querySelector('.part-btn.active').dataset.part;
                const baseNumber = (parseInt(currentPart) - 1) * 10;
                const questionNumber = baseNumber + targetQuestion;
                
                const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                if (navButton && !navButton.classList.contains('hidden-part')) {
                    navigateToQuestion(navButton);
                }
            }
        }
    });
    
    function navigateToNextQuestion() {
        const current = document.querySelector('.number-btn.active');
        const allVisible = Array.from(numberButtons).filter(btn => !btn.classList.contains('hidden-part'));
        const currentIndex = allVisible.indexOf(current);
        
        if (currentIndex < allVisible.length - 1) {
            navigateToQuestion(allVisible[currentIndex + 1]);
        }
    }
    
    function navigateToPreviousQuestion() {
        const current = document.querySelector('.number-btn.active');
        const allVisible = Array.from(numberButtons).filter(btn => !btn.classList.contains('hidden-part'));
        const currentIndex = allVisible.indexOf(current);
        
        if (currentIndex > 0) {
            navigateToQuestion(allVisible[currentIndex - 1]);
        }
    }
    
    function navigateToNextPart() {
        const currentPart = parseInt(document.querySelector('.part-btn.active').dataset.part);
        if (currentPart < 4) {
            switchToPart((currentPart + 1).toString());
        }
    }
    
    function navigateToPreviousPart() {
        const currentPart = parseInt(document.querySelector('.part-btn.active').dataset.part);
        if (currentPart > 1) {
            switchToPart((currentPart - 1).toString());
        }
    }
    
    // ========== Submit Functionality ==========
    submitTestBtn.addEventListener('click', function() {
        updateAnswerCount();
        const unanswered = testConfig.totalQuestions - document.querySelectorAll('.number-btn.answered').length;
        
        if (unanswered > 0) {
            document.querySelector('#submit-modal .modal-message').innerHTML = `
                <div style="color: #dc2626; font-weight: 600; margin-bottom: 10px;">
                    ⚠️ You have ${unanswered} unanswered questions!
                </div>
                Are you sure you want to submit your test? You cannot change your answers after submission.
                <br><br>
                <strong>Answered Questions: <span id="answered-count">${document.querySelectorAll('.number-btn.answered').length}</span> / ${testConfig.totalQuestions}</strong>
            `;
        }
        
        submitModal.style.display = 'flex';
    });
    
    confirmSubmitBtn.addEventListener('click', function() {
        // Stop timer if exists
        if (window.UniversalTimer) {
            window.UniversalTimer.stop();
        }
        
        // Stop audio
        if (currentAudio) {
            currentAudio.pause();
        }
        
        // Save final state
        saveAllAnswers();
        
        // Clear local storage for this test
        clearTestData();
        
        // Submit form
        submitButton.click();
    });
    
    cancelSubmitBtn.addEventListener('click', function() {
        submitModal.style.display = 'none';
    });
    
    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && submitModal.style.display === 'flex') {
            submitModal.style.display = 'none';
        }
    });
    
    // ========== Helper Functions ==========
    function updateNumberButtonsVisibility(activePart) {
        numberButtons.forEach(btn => {
            if (btn.dataset.part === activePart) {
                btn.classList.remove('hidden-part');
            } else {
                btn.classList.add('hidden-part');
            }
        });
    }
    
    function updateAnswerCount() {
        const answeredCount = document.querySelectorAll('.number-btn.answered').length;
        answeredCountSpan.textContent = answeredCount;
        
        // Update progress
        const progressPercent = (answeredCount / testConfig.totalQuestions) * 100;
        updateProgressBar(progressPercent);
    }
    
    function updateProgressBar(percent) {
        // You can add a progress bar to the UI if needed
        const progressBar = document.getElementById('test-progress');
        if (progressBar) {
            progressBar.style.width = percent + '%';
        }
    }
    
    function saveAllAnswers() {
        const formData = new FormData(form);
        const answers = {};
        
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('answers[') && value) {
                answers[key] = value;
            }
        }
        
        try {
            localStorage.setItem(`testAnswers_${testConfig.attemptId}`, JSON.stringify(answers));
            localStorage.setItem(`testAnswers_${testConfig.attemptId}_timestamp`, new Date().toISOString());
        } catch (e) {
            console.warn('Could not save answers:', e);
        }
    }
    
    function loadSavedAnswers() {
        try {
            const savedAnswers = localStorage.getItem(`testAnswers_${testConfig.attemptId}`);
            
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
                                radio.dispatchEvent(new Event('change'));
                            }
                        } else {
                            input.value = value;
                            input.dispatchEvent(new Event('change'));
                        }
                    }
                });
                
                // Show restore message
                const timestamp = localStorage.getItem(`testAnswers_${testConfig.attemptId}_timestamp`);
                if (timestamp) {
                    const date = new Date(timestamp);
                    showNotification(`Answers restored from ${date.toLocaleTimeString()}`, 'info');
                }
            }
        } catch (e) {
            console.error('Error restoring saved answers:', e);
        }
    }
    
    function clearTestData() {
        try {
            localStorage.removeItem(`testAnswers_${testConfig.attemptId}`);
            localStorage.removeItem(`testAnswers_${testConfig.attemptId}_timestamp`);
            localStorage.removeItem(`flaggedQuestions_${testConfig.attemptId}`);
            localStorage.removeItem(`annotations_${testConfig.attemptId}`);
        } catch (e) {
            console.error('Error clearing test data:', e);
        }
    }
    
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 14px;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // ========== Debounce Helper ==========
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // ========== Matching Questions Drag & Drop ==========
    function initializeMatchingQuestions() {
        const matchingContainers = document.querySelectorAll('.matching-container');
        
        matchingContainers.forEach(container => {
            const options = container.querySelectorAll('.matching-option');
            const items = container.querySelectorAll('.matching-item');
            
            // Make options draggable
            options.forEach(option => {
                option.draggable = true;
                
                option.addEventListener('dragstart', function(e) {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', this.dataset.option);
                    this.classList.add('dragging');
                });
                
                option.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                });
            });
            
            // Make items droppable
            items.forEach((item, index) => {
                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    this.classList.add('drag-over');
                });
                
                item.addEventListener('dragleave', function() {
                    this.classList.remove('drag-over');
                });
                
                item.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');
                    
                    const optionText = e.dataTransfer.getData('text/plain');
                    const questionDiv = this.closest('.question-item');
                    const questionId = questionDiv.id.replace('question-', '');
                    const pairIndex = Array.from(items).indexOf(this);
                    
                    // Update hidden input
                    const input = container.querySelector(`input[data-pair-index="${pairIndex}"]`);
                    if (input) {
                        input.value = optionText;
                        input.dispatchEvent(new Event('change'));
                        
                        // Show matched status
                        this.style.backgroundColor = '#dbeafe';
                        showNotification(`Matched: ${optionText}`, 'info');
                    }
                });
            });
        });
    }
    
    // ========== Notes & Highlight System ==========
    const AnnotationSystem = {
        init() {
            this.currentMenu = null;
            this.currentRange = null;
            this.noteModal = null;
            this.notesPanel = null;
            
            this.createNoteModal();
            this.createNotesPanel();
            this.setupAnnotationHandlers();
            this.restoreAnnotations();
            this.updateNotesCount();
        },
        
        createNoteModal() {
            const modal = document.createElement('div');
            modal.id = 'note-modal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 100000;
            `;
            
            modal.innerHTML = `
                <div style="
                    background: white;
                    border-radius: 8px;
                    width: 90%;
                    max-width: 450px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                ">
                    <div style="padding: 16px; border-bottom: 1px solid #e5e7eb;">
                        <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #111827;">Add Note</h3>
                        <p style="margin: 6px 0 0 0; font-size: 13px; color: #6b7280;" id="selected-text-preview"></p>
                    </div>
                    <div style="padding: 16px;">
                        <textarea 
                            id="note-textarea"
                            placeholder="Type your note here..."
                            style="
                                width: 100%;
                                min-height: 100px;
                                padding: 10px;
                                border: 1px solid #e5e7eb;
                                border-radius: 6px;
                                font-size: 14px;
                                resize: vertical;
                                font-family: inherit;
                                box-sizing: border-box;
                            "
                        ></textarea>
                        <div style="margin-top: 6px; text-align: right; font-size: 12px; color: #9ca3af;">
                            <span id="char-count">0</span>/500
                        </div>
                    </div>
                    <div style="
                        padding: 12px 16px;
                        background: #f9fafb;
                        border-top: 1px solid #e5e7eb;
                        display: flex;
                        justify-content: flex-end;
                        gap: 10px;
                        border-radius: 0 0 8px 8px;
                    ">
                        <button id="close-note-modal-btn" style="
                            padding: 6px 16px;
                            border: 1px solid #e5e7eb;
                            background: white;
                            border-radius: 4px;
                            font-size: 13px;
                            cursor: pointer;
                            transition: all 0.2s;
                        ">Cancel</button>
                        <button id="save-note-btn" style="
                            padding: 6px 16px;
                            background: #3b82f6;
                            color: white;
                            border: none;
                            border-radius: 4px;
                            font-size: 13px;
                            cursor: pointer;
                            transition: all 0.2s;
                        ">Save Note</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            this.noteModal = modal;
            
            // Setup event listeners
            const textarea = modal.querySelector('#note-textarea');
            const charCount = modal.querySelector('#char-count');
            textarea.addEventListener('input', () => {
                const count = textarea.value.length;
                charCount.textContent = count;
                if (count > 500) {
                    textarea.value = textarea.value.substring(0, 500);
                    charCount.textContent = 500;
                }
            });
            
            document.getElementById('close-note-modal-btn').addEventListener('click', () => {
                this.closeNoteModal();
            });
            
            document.getElementById('save-note-btn').addEventListener('click', () => {
                this.saveNote();
            });
        },
        
        createNotesPanel() {
            const panel = document.createElement('div');
            panel.id = 'notes-panel';
            panel.style.cssText = `
                position: fixed;
                top: 0;
                right: -350px;
                width: 350px;
                height: 100%;
                background: white;
                box-shadow: -2px 0 4px rgba(0, 0, 0, 0.1);
                transition: right 0.3s ease-out;
                z-index: 99998;
                display: flex;
                flex-direction: column;
            `;
            
            panel.innerHTML = `
                <div style="
                    padding: 16px;
                    border-bottom: 1px solid #e5e7eb;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    background: #f8f9fa;
                ">
                    <h3 style="margin: 0; font-size: 16px; font-weight: 600; flex: 1;">📝 Your Notes</h3>
                    <button id="close-notes-panel-btn" style="
                        background: none;
                        border: none;
                        font-size: 20px;
                        cursor: pointer;
                        color: #6b7280;
                        padding: 0;
                        width: 28px;
                        height: 28px;
                        border-radius: 4px;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='none'">×</button>
                </div>
                <div id="notes-list" style="
                    flex: 1;
                    overflow-y: auto;
                    padding: 12px;
                "></div>
            `;
            
            document.body.appendChild(panel);
            this.notesPanel = panel;
            
            document.getElementById('close-notes-panel-btn').addEventListener('click', () => {
                this.closeNotesPanel();
            });
        },
        
        setupAnnotationHandlers() {
            document.addEventListener('mouseup', (e) => {
                if (e.target.closest('#annotation-menu') || 
                    e.target.closest('#note-modal') || 
                    e.target.closest('#notes-panel')) {
                    return;
                }
                
                setTimeout(() => {
                    const selection = window.getSelection();
                    const selectedText = selection.toString().trim();
                    
                    if (selectedText && selectedText.length >= 3) {
                        const range = selection.getRangeAt(0);
                        const rect = range.getBoundingClientRect();
                        this.currentRange = range;
                        this.showMenu(rect, selectedText);
                    } else {
                        this.hideMenu();
                    }
                }, 10);
            });
            
            // Hide menu on scroll
            document.addEventListener('scroll', () => {
                this.hideMenu();
            }, true);
        },
        
        showMenu(rect, selectedText) {
            this.hideMenu();
            
            const menu = document.createElement('div');
            menu.id = 'annotation-menu';
            menu.style.cssText = `
                position: fixed;
                top: ${rect.top - 50}px;
                left: ${rect.left + (rect.width / 2) - 80}px;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                padding: 6px;
                display: flex;
                gap: 6px;
                z-index: 99999;
            `;
            
            // Create buttons with proper event handlers
            const noteBtn = document.createElement('button');
            noteBtn.style.cssText = `
                padding: 6px 12px;
                border: none;
                background: #3b82f6;
                color: white;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                display: flex;
                align-items: center;
                gap: 4px;
                transition: all 0.2s;
            `;
            noteBtn.innerHTML = `
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Note
            `;
            noteBtn.addEventListener('click', () => this.showNoteModal());
            
            const highlightBtn = document.createElement('button');
            highlightBtn.style.cssText = `
                padding: 6px 12px;
                border: none;
                background: #fbbf24;
                color: white;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                display: flex;
                align-items: center;
                gap: 4px;
                transition: all 0.2s;
            `;
            highlightBtn.innerHTML = `
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Highlight
            `;
            highlightBtn.addEventListener('click', () => this.highlightText());
            
            menu.appendChild(noteBtn);
            menu.appendChild(highlightBtn);
            document.body.appendChild(menu);
            this.currentMenu = menu;
        },
        
        hideMenu() {
            if (this.currentMenu) {
                this.currentMenu.remove();
                this.currentMenu = null;
            }
        },
        
        showNoteModal() {
            const selectedText = this.currentRange.toString();
            document.getElementById('selected-text-preview').textContent = 
                `"${selectedText.substring(0, 40)}${selectedText.length > 40 ? '...' : ''}"`;
            this.noteModal.style.display = 'flex';
            setTimeout(() => {
                document.getElementById('note-textarea').focus();
            }, 100);
            this.hideMenu();
        },
        
        closeNoteModal() {
            this.noteModal.style.display = 'none';
            document.getElementById('note-textarea').value = '';
            document.getElementById('char-count').textContent = '0';
        },
        
        saveNote() {
            const noteText = document.getElementById('note-textarea').value.trim();
            if (noteText && this.currentRange) {
                const selectedText = this.currentRange.toString();
                
                // Apply note styling
                const span = document.createElement('span');
                span.className = 'note-text';
                span.textContent = selectedText;
                span.title = noteText;
                span.dataset.note = noteText;
                span.dataset.noteId = Date.now();
                
                // Add click handler
                span.onclick = () => this.showNoteTooltip(span, noteText);
                
                try {
                    this.currentRange.deleteContents();
                    this.currentRange.insertNode(span);
                } catch (error) {
                    console.error('Error applying note:', error);
                }
                
                // Save to localStorage
                this.saveAnnotation('note', selectedText, noteText);
                
                this.closeNoteModal();
                window.getSelection().removeAllRanges();
            }
        },
        
        highlightText() {
            if (this.currentRange) {
                const selectedText = this.currentRange.toString();
                
                // Apply highlight styling
                const span = document.createElement('span');
                span.className = 'highlighted-text';
                span.textContent = selectedText;
                span.title = 'Click to remove highlight';
                
                // Add click handler for removal
                span.onclick = (e) => {
                    e.stopPropagation();
                    if (confirm('Remove this highlight?')) {
                        const text = span.textContent;
                        span.style.transition = 'background-color 0.3s ease';
                        span.style.backgroundColor = 'transparent';
                        
                        setTimeout(() => {
                            span.replaceWith(document.createTextNode(text));
                            this.removeAnnotation('highlight', selectedText);
                        }, 300);
                    }
                };
                
                try {
                    this.currentRange.deleteContents();
                    this.currentRange.insertNode(span);
                } catch (error) {
                    console.error('Error applying highlight:', error);
                }
                
                // Save to localStorage
                this.saveAnnotation('highlight', selectedText, 'yellow');
                
                window.getSelection().removeAllRanges();
                this.hideMenu();
            }
        },
        
        removeAnnotation(type, text) {
            let annotations = JSON.parse(localStorage.getItem(`annotations_${testConfig.attemptId}`) || '[]');
            annotations = annotations.filter(a => !(a.type === type && a.text === text));
            localStorage.setItem(`annotations_${testConfig.attemptId}`, JSON.stringify(annotations));
            
            if (type === 'note') {
                this.updateNotesCount();
            }
        },
        
        showNoteTooltip(element, noteText) {
            // Remove existing tooltip
            const existingTooltip = document.getElementById('note-tooltip');
            if (existingTooltip) existingTooltip.remove();
            
            const tooltip = document.createElement('div');
            tooltip.id = 'note-tooltip';
            tooltip.style.cssText = `
                position: absolute;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                max-width: 250px;
                z-index: 99999;
                font-size: 13px;
            `;
            
            tooltip.innerHTML = `
                <div style="color: #374151; margin-bottom: 6px;">${noteText}</div>
                <div style="font-size: 11px; color: #9ca3af;">Click outside to close</div>
            `;
            
            document.body.appendChild(tooltip);
            
            const rect = element.getBoundingClientRect();
            tooltip.style.top = `${rect.bottom + window.scrollY + 4}px`;
            tooltip.style.left = `${rect.left + window.scrollX}px`;
            
            // Remove on click outside
            setTimeout(() => {
                document.addEventListener('click', function removeTooltip(e) {
                    if (!tooltip.contains(e.target) && e.target !== element) {
                        tooltip.remove();
                        document.removeEventListener('click', removeTooltip);
                    }
                });
            }, 100);
        },
        
        saveAnnotation(type, text, data) {
            const annotations = JSON.parse(localStorage.getItem(`annotations_${testConfig.attemptId}`) || '[]');
            annotations.push({
                type: type,
                text: text,
                data: data,
                timestamp: new Date().toISOString()
            });
            localStorage.setItem(`annotations_${testConfig.attemptId}`, JSON.stringify(annotations));
            this.updateNotesCount();
        },
        
        restoreAnnotations() {
            const annotations = JSON.parse(localStorage.getItem(`annotations_${testConfig.attemptId}`) || '[]');
            
            annotations.forEach(annotation => {
                this.findAndStyleText(annotation.text, (span) => {
                    if (annotation.type === 'note') {
                        span.className = 'note-text';
                        span.dataset.note = annotation.data;
                        span.dataset.noteId = Date.now();
                        span.title = 'Click to view note';
                        span.onclick = () => this.showNoteTooltip(span, annotation.data);
                    } else if (annotation.type === 'highlight') {
                        span.className = 'highlighted-text';
                        span.title = 'Click to remove highlight';
                        span.onclick = (e) => {
                            e.stopPropagation();
                            if (confirm('Remove this highlight?')) {
                                const text = span.textContent;
                                span.style.transition = 'background-color 0.3s ease';
                                span.style.backgroundColor = 'transparent';
                                
                                setTimeout(() => {
                                    span.replaceWith(document.createTextNode(text));
                                    this.removeAnnotation('highlight', annotation.text);
                                }, 300);
                            }
                        };
                    }
                });
            });
            
            this.updateNotesCount();
        },
        
        findAndStyleText(searchText, styleCallback) {
            const container = document.querySelector('.content-area');
            const walker = document.createTreeWalker(
                container,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );
            
            let node;
            while (node = walker.nextNode()) {
                const text = node.textContent;
                const index = text.indexOf(searchText);
                
                if (index !== -1 && !node.parentElement.classList.contains('note-text') && 
                    !node.parentElement.classList.contains('highlighted-text')) {
                    const span = document.createElement('span');
                    const parent = node.parentNode;
                    
                    // Split the text node
                    const before = document.createTextNode(text.substring(0, index));
                    const after = document.createTextNode(text.substring(index + searchText.length));
                    
                    // Apply styling
                    span.textContent = searchText;
                    styleCallback(span);
                    
                    // Replace in DOM
                    parent.insertBefore(before, node);
                    parent.insertBefore(span, node);
                    parent.insertBefore(after, node);
                    parent.removeChild(node);
                    
                    break;
                }
            }
        },
        
        updateNotesCount() {
            const annotations = JSON.parse(localStorage.getItem(`annotations_${testConfig.attemptId}`) || '[]');
            const notesCount = annotations.filter(a => a.type === 'note').length;
            const countElement = document.getElementById('notes-count');
            
            if (countElement) {
                if (notesCount > 0) {
                    countElement.textContent = notesCount;
                    countElement.style.display = 'inline-flex';
                } else {
                    countElement.style.display = 'none';
                }
            }
        },
        
        openNotesPanel() {
            this.updateNotesList();
            this.notesPanel.style.right = '0';
        },
        
        closeNotesPanel() {
            this.notesPanel.style.right = '-350px';
        },
        
        updateNotesList() {
            const notesList = document.getElementById('notes-list');
            const annotations = JSON.parse(localStorage.getItem(`annotations_${testConfig.attemptId}`) || '[]');
            const notes = annotations.filter(a => a.type === 'note');
            
            if (notes.length === 0) {
                notesList.innerHTML = `
                    <div style="text-align: center; color: #9ca3af; padding: 30px;">
                        <div style="font-size: 36px; margin-bottom: 12px;">📝</div>
                        <p style="font-size: 14px; margin-bottom: 6px;">No notes yet!</p>
                        <p style="font-size: 12px; margin-top: 6px;">Select text and add notes to see them here.</p>
                    </div>
                `;
            } else {
                notesList.innerHTML = notes.map((note, index) => `
                    <div style="
                        background: #f9fafb;
                        border: 1px solid #e5e7eb;
                        border-radius: 6px;
                        padding: 12px;
                        margin-bottom: 10px;
                        position: relative;
                        transition: all 0.2s ease;
                    " data-note-index="${index}">
                        <button class="delete-note-btn" data-note-text="${encodeURIComponent(note.text)}" data-note-timestamp="${note.timestamp}" style="
                            position: absolute;
                            top: 8px;
                            right: 8px;
                            background: #fee2e2;
                            border: none;
                            border-radius: 3px;
                            padding: 3px 6px;
                            cursor: pointer;
                            color: #dc2626;
                            font-size: 11px;
                            transition: all 0.2s;
                        ">Delete</button>
                        <div style="
                            font-size: 12px;
                            color: #6b7280;
                            font-style: italic;
                            margin-bottom: 6px;
                            padding: 6px;
                            background: white;
                            border-radius: 3px;
                            margin-right: 50px;
                            border-left: 2px solid #3b82f6;
                        ">"${note.text.substring(0, 80)}${note.text.length > 80 ? '...' : ''}"</div>
                        <div style="font-size: 13px; color: #111827; line-height: 1.4; margin-bottom: 6px;">${note.data}</div>
                        <div style="
                            margin-top: 8px;
                            font-size: 11px;
                            color: #9ca3af;
                        ">📅 ${new Date(note.timestamp).toLocaleString()}</div>
                    </div>
                `).join('');
                
                // Add event listeners to delete buttons
                notesList.querySelectorAll('.delete-note-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const text = decodeURIComponent(btn.dataset.noteText);
                        const timestamp = btn.dataset.noteTimestamp;
                        this.deleteNote(text, timestamp);
                    });
                });
            }
        },
        
        deleteNote(text, timestamp) {
            if (confirm('Are you sure you want to delete this note?')) {
                let annotations = JSON.parse(localStorage.getItem(`annotations_${testConfig.attemptId}`) || '[]');
                annotations = annotations.filter(a => !(a.type === 'note' && a.text === text && a.timestamp === timestamp));
                localStorage.setItem(`annotations_${testConfig.attemptId}`, JSON.stringify(annotations));
                
                // Remove from DOM
                const noteElements = document.querySelectorAll('.note-text');
                noteElements.forEach(el => {
                    if (el.textContent === text) {
                        const parent = el.parentNode;
                        parent.replaceChild(document.createTextNode(text), el);
                    }
                });
                
                this.updateNotesList();
                this.updateNotesCount();
            }
        }
    };
    
    // Update notes button handler
    notesBtn.addEventListener('click', function() {
        AnnotationSystem.openNotesPanel();
    });
    
    // ========== Initialize ==========
    
    // Initialize all systems
    initializeMatchingQuestions();
    AnnotationSystem.init();
    
    // Load saved data
    loadSavedAnswers();
    loadFlaggedQuestions();
    
    // Play first part audio
    playPartAudio('1');
    
    // Update initial visibility
    updateNumberButtonsVisibility('1');
    
    // Update answer count
    updateAnswerCount();
    
    // Periodically save answers
    setInterval(saveAllAnswers, 30000);
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
</x-test-layout>