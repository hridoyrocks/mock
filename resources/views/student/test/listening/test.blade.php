{{-- resources/views/student/test/listening/test.blade.php --}}
<x-test-layout>
    <x-slot:title>IELTS Listening Test</x-slot>
    
    <x-slot:meta>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <link rel="stylesheet" href="{{ asset('css/listening-test-fix.css') }}?v={{ time() }}">
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
            height: 100vh;
            overflow: hidden;
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
            padding: 10px 20px;
            background-color: #1a1a1a;
            color: white;
            height: 50px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        /* Timer Center Wrapper */
        .timer-center-wrapper {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
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
        
        /* ========== MAIN CONTAINER ========== */
        .main-container {
            position: fixed;
            top: 50px; /* User bar height */
            left: 0;
            right: 0;
            bottom: 70px; /* Bottom nav height */
            background: white;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        /* ========== FIXED PART HEADER ========== */
        .part-header-container {
            background: white;
            padding: 20px 40px;
            z-index: 10;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        /* ========== SCROLLABLE CONTENT AREA ========== */
        .content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 30px 40px 120px;
            position: relative;
            background: white;
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
        
        /* Hide original part headers in scrollable area */
        .content-area .part-header {
            display: none;
        }
        
        /* Style for cloned fixed header */
        .part-header-container .part-header {
            display: block;
            margin: 0;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            background: #f0f0f0;
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
            font-size: 15px;
            font-weight: 700;  /* Bold */
            color: #000;
            margin: 35px 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .question-instruction {
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 16px;
            font-weight: 600;
            line-height: 1.6;
        }
        
        /* ========== QUESTION ITEMS - LEFT ALIGNED ========== */
        .question-item {
            padding: 20px 0;
            border-bottom: none;
            font-size: 15px;
            margin-bottom: 20px;
        }
        
        .question-item:last-child {
            border-bottom: none;
        }
        
        .question-content {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .question-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            background: #e8e8e8;
            border: 1px solid #999;
            border-radius: 3px;
            font-weight: 700;
            color: #000;
            font-size: 14px;
            flex-shrink: 0;
            margin-right: 15px;
        }
        
        .question-text {
            flex: 1;
            line-height: 1.6;
            color: #1f2937;
            font-size: 15px;
            font-weight: 500;
        }
        
        /* Options will be styled by external CSS */
        
        /* ========== INPUT FIELDS ========== */
        .answer-input {
            margin-left: 47px;
        }
        
        .text-input, .select-input {
            width: 350px;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 0;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        
        .text-input:hover, .select-input:hover {
            border-color: #999;
            background: #fafafa;
        }
        
        .text-input:focus, .select-input:focus {
            outline: none;
            border-color: #333;
            background: white;
            box-shadow: none;
        }
        
        .text-input::placeholder {
            color: #999;
            font-style: italic;
        }
        
        /* ========== SPECIAL QUESTION TYPES STYLES ========== */
        /* Matching Questions - Official IELTS Style */
        .matching-container {
            user-select: none;
            margin-top: 30px;
            display: flex;
            gap: 40px;
        }
        
        .matching-left-section {
            flex: 1;
        }
        
        .matching-table {
            width: 100%;
        }
        
        .matching-row {
            display: grid;
            grid-template-columns: 40px 250px 180px;
            align-items: center;
            margin-bottom: 15px;
            gap: 15px;
        }
        
        .question-number-inline {
            font-weight: 700;
            background: #e8e8e8;
            padding: 4px 10px;
            border-radius: 3px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 32px;
        }
        
        .matching-question {
            font-size: 15px;
            font-weight: 500;
            color: #1f2937;
        }
        
        .drop-box {
            width: 100%;
            height: 40px;
            border: 2px dashed #d1d5db;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            background: white;
            font-size: 14px;
            padding: 0 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .drop-box.drag-over {
            background: #f9fafb;
            border-color: #9ca3af;
            border-style: solid;
        }
        
        .drop-box.has-answer {
            border-style: solid;
            border-color: #d1d5db;
            background: white;
            cursor: move;
        }
        
        .drop-box .placeholder-text {
            color: #9ca3af;
            font-style: italic;
            font-size: 13px;
        }
        
        .matching-right-section {
            width: 150px;
            flex-shrink: 0;
            margin-left: -180px;
            margin-right: 50px;
        }
        
        .matching-options-container {
            position: sticky;
            top: 100px;
        }
        
        .matching-options-title {
            display: none;
        }
        
        .matching-options-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .draggable-option {
            padding: 6px 12px;
            background: white;
            border: 1px solid #6b7280;
            border-radius: 4px;
            cursor: move;
            transition: all 0.2s;
            font-size: 13px;
            font-weight: 500;
            color: #1f2937;
            text-align: center;
            user-select: none;
        }
        
        .draggable-option:hover:not(.placed) {
            background: #f9fafb;
        }
        
        .draggable-option.dragging {
            opacity: 0.5;
            cursor: grabbing;
        }
        
        .draggable-option.placed {
            display: none !important;
        }
        
        /* Form Completion - Official IELTS Style */
        .form-completion-container {
            margin-left: 40px;
            margin-top: 20px;
        }
        
        .form-wrapper {
            background: white;
            border: 2px solid #000;
            padding: 40px 50px;
            max-width: 650px;
            margin: 0;
            position: relative;
        }
        
        .form-title {
            text-align: center;
            font-weight: 700;
            margin-bottom: 40px;
            color: #000;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .form-field-row {
            display: grid;
            grid-template-columns: 36px 180px 1fr;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #000;
            font-size: 14px;
            text-align: left;
        }
        
        .form-question-number {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            background: #f0f0f0;
            border: 1px solid #999;
            padding: 6px 0;
            text-align: center;
            border-radius: 4px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-input {
            width: 100%;
            max-width: 300px;
            padding: 10px 14px;
            border: 1px solid #999;
            border-radius: 0;
            font-size: 14px;
            background: white;
            font-family: Arial, sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #333;
            background: white;
            box-shadow: none;
        }
        
        .form-input::placeholder {
            color: #999;
            font-style: italic;
            font-size: 13px;
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
            height: 70px;
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
            .main-container {
                bottom: 120px; /* Adjust for mobile bottom nav */
            }
            
            .content-area {
                padding: 20px 20px 120px;
            }
            
            .part-header-container {
                padding: 15px 20px;
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
            
            /* Mobile options styling - handled by external CSS */
            
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
        
        /* ========== QUESTION NAVIGATION ARROWS ========== */
        .question-nav-arrows {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 50;
        }
        
        /* ========== SCROLLBAR STYLING ========== */
        .content-area::-webkit-scrollbar {
            width: 8px;
        }
        
        .content-area::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .content-area::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        .content-area::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .nav-arrow {
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .nav-arrow:hover {
            background: #f8f9fa;
            border-color: #3b82f6;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .nav-arrow:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f5f5f5;
        }
        
        .nav-arrow svg {
            color: #6b7280;
        }
        
        .nav-arrow:hover svg {
            color: #3b82f6;
        }
        
        .nav-arrow:disabled svg {
            color: #d1d5db;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .question-nav-arrows {
                bottom: 140px;
                top: auto;
                transform: none;
                flex-direction: row;
                right: 50%;
                transform: translateX(50%);
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

    

    <!-- Fixed User Info Bar -->
    <div class="user-bar" style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: 50px;">
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
                auto-submit-form-id="listening-form"
                position="integrated"
                :warning-time="300"
                :danger-time="60"
            />
        </div>
        
        <div class="user-controls">
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm help-button" id="help-button">Help ?</button>
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm no-nav">Hide</button>
            <div class="flex items-center ml-2">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071a1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <input type="range" min="0" max="100" value="75" class="ml-2 w-20" id="volume-slider">
            </div>
        </div>
    </div>

    <!-- Main Container with Fixed Part Header and Scrollable Content -->
    <div class="main-container">
        <!-- Fixed Part Header (will be updated dynamically) -->
        <div class="part-header-container" id="fixed-part-header">
            <!-- Part header will be cloned here -->
        </div>
        
        <!-- Scrollable Content Area -->
        <div class="content-area">
        <!-- Question Navigation Arrows -->
        <div class="question-nav-arrows">
            <button type="button" class="nav-arrow prev-arrow" id="prev-question-btn" title="Previous Question">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button type="button" class="nav-arrow next-arrow" id="next-question-btn" title="Next Question">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        
        <form id="listening-form" action="{{ route('student.listening.submit', $attempt) }}" method="POST">
            @csrf
            
            @php
                $allQuestions = $testSet->questions->sortBy('order_number');
                $groupedQuestions = $allQuestions->groupBy('part_number');
                $currentQuestionNumber = 1;
                
                // Pre-calculate total questions including sub-questions
                $totalQuestionCount = 0;
                foreach ($allQuestions as $q) {
                    if ($q->question_type === 'fill_blanks') {
                        preg_match_all('/\[____(\d+)____\]/', $q->content, $matches);
                        $blankCount = count($matches[0]);
                        $totalQuestionCount += ($blankCount > 0 ? $blankCount : 1);
                    } elseif ($q->question_type === 'dropdown_selection') {
                        preg_match_all('/\[DROPDOWN_(\d+)\]/', $q->content, $matches);
                        $dropdownCount = count($matches[0]);
                        $totalQuestionCount += ($dropdownCount > 0 ? $dropdownCount : 1);
                    } elseif ($q->question_type === 'drag_drop') {
                        $dragDropData = $q->section_specific_data ?? [];
                        $dropZones = $dragDropData['drop_zones'] ?? [];
                        $dropZoneCount = count($dropZones);
                        $totalQuestionCount += ($dropZoneCount > 0 ? $dropZoneCount : 1);
                    } else {
                        $totalQuestionCount++;
                    }
                }
            @endphp
            
            @foreach ($groupedQuestions as $partNumber => $partQuestions)
                <div class="part-section {{ $loop->first ? 'active' : '' }}" data-part="{{ $partNumber }}">
                    <!-- Part Header (Hidden in content, will be cloned to fixed position) -->
                    <div class="part-header" data-part-number="{{ $partNumber }}">
                        <div class="part-title">Part {{ $partNumber }}</div>
                        <div class="part-instruction">Listen and answer questions {{ $partNumber == 1 ? '1-10' : ($partNumber == 2 ? '11-20' : ($partNumber == 3 ? '21-30' : '31-40')) }}.</div>
                    </div>

                    <!-- Questions -->
                    @php
                        $questionGroups = $partQuestions->groupBy('question_group');
                        $shownInstructions = [];
                    @endphp
                    
                    @foreach ($questionGroups as $groupName => $questions)
                        @if($groupName)
                            <div class="question-group-header">{{ $groupName }}</div>
                        @endif
                        
                        @foreach ($questions as $question)
                            @php
                                $displayNumber = $currentQuestionNumber;
                            @endphp
                            
                            {{-- Show instruction if not already shown --}}
                            @if($question->instructions && !in_array($question->instructions, $shownInstructions))
                                <div class="question-instruction">{!! $question->instructions !!}</div>
                                @php $shownInstructions[] = $question->instructions; @endphp
                            @endif
                            
                            {{-- Include the question render partial --}}
                            @include('student.test.listening.question-render', [
                                'question' => $question,
                                'displayNumber' => $displayNumber
                            ])
                            
                            @php
                                // Update current question number based on question type
                                if ($question->question_type === 'fill_blanks') {
                                    preg_match_all('/\[____(\d+)____\]/', $question->content, $matches);
                                    $blankCount = count($matches[0]);
                                    $currentQuestionNumber += ($blankCount > 0 ? $blankCount : 1);
                                } elseif ($question->question_type === 'dropdown_selection') {
                                    preg_match_all('/\[DROPDOWN_(\d+)\]/', $question->content, $matches);
                                    $dropdownCount = count($matches[0]);
                                    $currentQuestionNumber += ($dropdownCount > 0 ? $dropdownCount : 1);
                                } elseif ($question->question_type === 'drag_drop') {
                                    $dragDropData = $question->section_specific_data ?? [];
                                    $dropZones = $dragDropData['drop_zones'] ?? [];
                                    $dropZoneCount = count($dropZones);
                                    $currentQuestionNumber += ($dropZoneCount > 0 ? $dropZoneCount : 1);
                                } else {
                                    $currentQuestionNumber++;
                                }
                            @endphp
                        @endforeach
                    @endforeach
                </div>
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
                        @if($question->question_type === 'fill_blanks')
                            @php
                                preg_match_all('/\[____(\d+)____\]/', $question->content, $matches);
                                $blankCount = count($matches[0]);
                                $blankCount = $blankCount > 0 ? $blankCount : 1;
                            @endphp
                            @for($i = 1; $i <= $blankCount; $i++)
                                @php $questionIdMap[$navQuestionNum] = $question->id; @endphp
                                <div class="number-btn {{ $navQuestionNum == 1 ? 'active' : '' }}" 
                                     data-question="{{ $question->id }}"
                                     data-sub-index="{{ $i }}"
                                     data-display-number="{{ $navQuestionNum }}"
                                     data-part="{{ $question->part_number }}">
                                    {{ $navQuestionNum++ }}
                                </div>
                            @endfor
                        @elseif($question->question_type === 'dropdown_selection')
                            @php
                                preg_match_all('/\[DROPDOWN_(\d+)\]/', $question->content, $matches);
                                $dropdownCount = count($matches[0]);
                                $dropdownCount = $dropdownCount > 0 ? $dropdownCount : 1;
                            @endphp
                            @for($i = 1; $i <= $dropdownCount; $i++)
                                @php $questionIdMap[$navQuestionNum] = $question->id; @endphp
                                <div class="number-btn {{ $navQuestionNum == 1 ? 'active' : '' }}" 
                                     data-question="{{ $question->id }}"
                                     data-sub-index="{{ $i }}"
                                     data-display-number="{{ $navQuestionNum }}"
                                     data-part="{{ $question->part_number }}">
                                    {{ $navQuestionNum++ }}
                                </div>
                            @endfor
                        @elseif($question->question_type === 'drag_drop')
                            @php
                                // For drag_drop questions, show one button per drop zone
                                $dragDropData = $question->section_specific_data ?? [];
                                $dropZones = $dragDropData['drop_zones'] ?? [];
                                $dropZoneCount = count($dropZones);
                                $dropZoneCount = $dropZoneCount > 0 ? $dropZoneCount : 1;
                            @endphp
                            @for($i = 0; $i < $dropZoneCount; $i++)
                                @php $questionIdMap[$navQuestionNum] = $question->id; @endphp
                                <div class="number-btn {{ $navQuestionNum == 1 ? 'active' : '' }}" 
                                     data-question="{{ $question->id }}"
                                     data-zone-index="{{ $i }}"
                                     data-display-number="{{ $navQuestionNum }}"
                                     data-part="{{ $question->part_number }}">
                                    {{ $navQuestionNum++ }}
                                </div>
                            @endfor
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
        $audioUrl = null;
        
        if ($partAudio) {
            // Use the audio_url accessor which handles CDN URLs
            $audioUrl = $partAudio->audio_url;
        } else {
            // Fallback: Find first question with audio in this part
            $firstQuestionWithAudio = $testSet->questions()
                ->where('part_number', $partNumber)
                ->where('use_part_audio', false)
                ->whereNotNull('media_path')
                ->first();
                
            if ($firstQuestionWithAudio) {
                // Check if it has a CDN URL
                if ($firstQuestionWithAudio->media_url) {
                    $audioUrl = $firstQuestionWithAudio->media_url;
                } elseif ($firstQuestionWithAudio->storage_disk === 'r2') {
                    // Generate R2 URL
                    $baseUrl = rtrim(config('filesystems.disks.r2.url'), '/');
                    $audioUrl = $baseUrl . '/' . ltrim($firstQuestionWithAudio->media_path, '/');
                } else {
                    // Local storage URL
                    $audioUrl = asset('storage/' . $firstQuestionWithAudio->media_path);
                }
            }
        }
    @endphp
    
    @if($audioUrl)
        <audio id="test-audio-{{ $partNumber }}" preload="auto" style="display:none;">
            <source src="{{ $audioUrl }}" type="audio/mpeg">
            <source src="{{ $audioUrl }}" type="audio/ogg">
            <source src="{{ $audioUrl }}" type="audio/wav">
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
    {{-- Include Drag & Drop Handler --}}
    <script src="{{ asset('js/student/listening-drag-drop.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== Disable Right Click ==========
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
        
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
        
        // ========== Fixed Part Header Management ==========
        function updateFixedPartHeader(partNumber) {
            const fixedHeaderContainer = document.getElementById('fixed-part-header');
            const originalHeader = document.querySelector(`.part-header[data-part-number="${partNumber}"]`);
            
            if (originalHeader && fixedHeaderContainer) {
                // Clone the header
                const clonedHeader = originalHeader.cloneNode(true);
                clonedHeader.style.display = 'block';
                
                // Clear and append
                fixedHeaderContainer.innerHTML = '';
                fixedHeaderContainer.appendChild(clonedHeader);
            }
        }
        
        // Initialize with first part header
        updateFixedPartHeader('1');
        
        // ========== Part Navigation ==========
        partButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetPart = this.dataset.part;
                
                // Update active button
                partButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Show target part
                partSections.forEach(section => {
                    section.classList.remove('active');
                    if (section.dataset.part === targetPart) {
                        section.classList.add('active');
                    }
                });
                
                // Update fixed part header
                updateFixedPartHeader(targetPart);
                
                // Update number buttons visibility
                updateNumberButtonsVisibility(targetPart);
                
                // Play audio for this part
                playPartAudio(targetPart);
            });
        });
        
        // ========== Question Navigation ==========
        numberButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                numberButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const questionId = this.dataset.question;
                const subIndex = this.dataset.subIndex;
                const zoneIndex = this.dataset.zoneIndex;
                const questionElement = document.getElementById(`question-${questionId}`);
                
                if (questionElement) {
                    // Switch to correct part if needed
                    const partNumber = this.dataset.part;
                    const currentActivePart = document.querySelector('.part-btn.active');
                    if (currentActivePart && currentActivePart.dataset.part !== partNumber) {
                        const partBtn = document.querySelector(`.part-btn[data-part="${partNumber}"]`);
                        if (partBtn) {
                            partBtn.click();
                            // Update fixed header when switching parts
                            updateFixedPartHeader(partNumber);
                        }
                    }
                    
                    // For drag_drop questions, scroll to specific drop zone
                    if (zoneIndex !== undefined) {
                        const dropZoneItem = questionElement.querySelector(`[data-zone-index="${zoneIndex}"]`);
                        if (dropZoneItem) {
                            dropZoneItem.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            
                            // Highlight the drop box briefly
                            const dropBox = dropZoneItem.querySelector('.drop-box');
                            if (dropBox) {
                                dropBox.style.transition = 'all 0.3s ease';
                                dropBox.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.5)';
                                setTimeout(() => {
                                    dropBox.style.boxShadow = '';
                                }, 1000);
                            }
                        }
                    } else {
                        // Regular scroll to question
                        questionElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                    
                    // Focus on specific sub-question input if applicable
                    if (subIndex !== undefined) {
                        const input = questionElement.querySelector(`input[name="answers[${questionId}_${subIndex}]"]`);
                        if (input) {
                            setTimeout(() => input.focus(), 300);
                        }
                    }
                }
                
                // Update review checkbox
                reviewCheckbox.checked = this.classList.contains('flagged');
            });
        });
        
        // ========== Review/Flag Functionality ==========
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
        
        // ========== Answer Tracking ==========
        document.querySelectorAll('input[type="radio"], input[type="checkbox"], input[type="text"], select').forEach(input => {
            input.addEventListener('change', function() {
                const questionNumber = this.dataset.questionNumber;
                if (questionNumber) {
                    const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                    if (navButton) {
                        if (this.type === 'radio' || this.type === 'checkbox') {
                            // For radio/checkbox, check if any option is selected
                            const name = this.name;
                            const isAnswered = document.querySelector(`input[name="${name}"]:checked`) !== null;
                            if (isAnswered) {
                                navButton.classList.add('answered');
                            } else {
                                navButton.classList.remove('answered');
                            }
                        } else {
                            // For text/select inputs
                            if (this.value && this.value.trim()) {
                                navButton.classList.add('answered');
                            } else {
                                navButton.classList.remove('answered');
                            }
                        }
                    }
                }
                saveAllAnswers();
                updateAnswerCount();
            });
        });
        
        // ========== Audio Controls ==========
        function playPartAudio(partNumber) {
            // Stop current audio
            if (currentAudio) {
                currentAudio.pause();
            }
            
            // Get audio for this part
            const audio = document.getElementById(`test-audio-${partNumber}`);
            if (audio) {
                currentAudio = audio;
                
                // Set volume
                if (volumeSlider) {
                    audio.volume = volumeSlider.value / 100;
                }
                
                // Add error handling
                audio.addEventListener('error', function(e) {
                    console.error('Audio error:', e);
                    const target = e.target;
                    let errorMsg = 'Audio playback error';
                    
                    // Determine error type
                    if (target.error) {
                        switch(target.error.code) {
                            case target.error.MEDIA_ERR_ABORTED:
                                errorMsg = 'Audio playback aborted';
                                break;
                            case target.error.MEDIA_ERR_NETWORK:
                                errorMsg = 'Network error while loading audio';
                                break;
                            case target.error.MEDIA_ERR_DECODE:
                                errorMsg = 'Audio decoding error';
                                break;
                            case target.error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                                errorMsg = 'Audio format not supported';
                                break;
                        }
                    }
                    
                    // Check source URLs
                    console.log('Audio sources:');
                    const sources = audio.querySelectorAll('source');
                    sources.forEach(source => {
                        console.log('Source URL:', source.src);
                    });
                    
                    alert(`${errorMsg}. Please refresh the page or contact support if the problem persists.`);
                });
                
                // Add loadeddata event to confirm audio is ready
                audio.addEventListener('loadeddata', function() {
                    console.log(`Audio for Part ${partNumber} loaded successfully`);
                });
                
                // Auto-play audio (like real IELTS test)
                audio.play().catch(e => {
                    console.error('Audio playback failed:', e);
                    // In real IELTS, audio plays automatically, so we show a prominent message
                    alert('Audio playback failed. The test audio should play automatically. Please refresh the page and ensure autoplay is enabled in your browser.');
                });
            } else {
                // Check if no audio message exists
                const noAudioMsg = document.getElementById(`no-audio-${partNumber}`);
                if (noAudioMsg) {
                    console.warn(noAudioMsg.dataset.message);
                }
            }
        }
        
        // Volume control
        if (volumeSlider) {
            volumeSlider.addEventListener('input', function() {
                if (currentAudio) {
                    currentAudio.volume = this.value / 100;
                }
            });
        }
        
        // ========== Submit Functionality ==========
        submitTestBtn.addEventListener('click', function() {
            updateAnswerCount();
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
                }
            } catch (e) {
                console.error('Error restoring saved answers:', e);
            }
        }
        
        // ========== Notes & Highlight System (Complete) ==========
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
                    // Skip if clicking on annotation menu, note modal, or notes panel
                    if (e.target.closest('#annotation-menu') || 
                        e.target.closest('#note-modal') || 
                        e.target.closest('#notes-panel')) {
                    return;
                }
                    
                    // Skip if right click
                    if (e.button === 2) {
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
    
    // ========== Question Navigation Arrows ==========
    const prevQuestionBtn = document.getElementById('prev-question-btn');
    const nextQuestionBtn = document.getElementById('next-question-btn');
    let currentQuestionIndex = 0;
    const totalQuestionsNav = document.querySelectorAll('.number-btn').length;
    
    function updateArrowButtons() {
        prevQuestionBtn.disabled = currentQuestionIndex === 0;
        nextQuestionBtn.disabled = currentQuestionIndex === totalQuestionsNav - 1;
    }
    
    function navigateToQuestion(index) {
        if (index < 0 || index >= totalQuestionsNav) return;
        
        currentQuestionIndex = index;
        const targetButton = numberButtons[index];
        if (targetButton) {
            targetButton.click();
        }
            updateArrowButtons();
        }
        
        prevQuestionBtn.addEventListener('click', function() {
            navigateToQuestion(currentQuestionIndex - 1);
        });
        
        nextQuestionBtn.addEventListener('click', function() {
            navigateToQuestion(currentQuestionIndex + 1);
        });
        
        // Update current index when clicking number buttons
        numberButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                currentQuestionIndex = index;
                updateArrowButtons();
            });
        });
        
        // ========== Drag & Drop - Now handled by listening-drag-drop.js ==========
        function initializeDragAndDrop() {
            // New drag & drop system is automatically initialized
            // by listening-drag-drop.js file
            console.log('Drag & Drop initialized via listening-drag-drop.js');
            
            // Fallback for old matching questions if needed
            const draggableOptions = document.querySelectorAll('.draggable-option');
            const dropBoxes = document.querySelectorAll('.drop-box');
            
            console.log('Found elements:', { 
                draggableOptions: draggableOptions.length, 
                dropBoxes: dropBoxes.length 
            });
            
            // Setup draggable options
            draggableOptions.forEach(option => {
                option.addEventListener('dragstart', function(e) {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', this.dataset.option);
                    e.dataTransfer.setData('option-letter', this.dataset.optionLetter);
                    e.dataTransfer.setData('full-text', this.innerHTML);
                    this.classList.add('dragging');
                });
                
                option.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                });
            });
            
            // Setup drop boxes
            dropBoxes.forEach(box => {
                box.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    this.classList.add('drag-over');
                });
                
                box.addEventListener('dragleave', function() {
                    this.classList.remove('drag-over');
                });
                
                box.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');
                    
                    const optionText = e.dataTransfer.getData('text/plain');
                    const fullText = e.dataTransfer.getData('full-text');
                    const questionId = this.dataset.questionId;
                    const index = this.dataset.index;
                    const questionNumber = this.dataset.questionNumber;
                    
                    // Check if box already has an answer
                    if (this.classList.contains('has-answer')) {
                        // Remove the old answer first
                        const oldAnswer = this.textContent.replace(/^[A-Z]\.\s/, '');
                        const oldOption = document.querySelector(`.draggable-option[data-option="${oldAnswer}"]`);
                        if (oldOption) {
                            oldOption.style.display = 'inline-block';
                            oldOption.classList.remove('placed');
                        }
                    }
                    
                    // Add new answer
                    this.innerHTML = fullText;
                    this.classList.add('has-answer');
                    
                    // Update hidden input
                    const hiddenInput = document.querySelector(`input[name="answers[${questionId}_${index}]"]`);
                    if (hiddenInput) {
                        hiddenInput.value = optionText;
                        
                        // Update navigation button
                        const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                        if (navButton) {
                            navButton.classList.add('answered');
                        }
                    }
                    
                    // Hide the dragged option
                    const sourceOption = document.querySelector(`.draggable-option[data-option="${optionText}"]`);
                    if (sourceOption) {
                        sourceOption.style.display = 'none';
                        sourceOption.classList.add('placed');
                    }
                    
                    // Make the answer draggable for removal
                    this.draggable = true;
                    setupAnswerDrag(this);
                    
                    saveAllAnswers();
                    updateAnswerCount();
                });
            });
        }
        
        function setupAnswerDrag(answerBox) {
            answerBox.addEventListener('dragstart', function(e) {
                if (!this.classList.contains('has-answer')) return;
                
                const answerText = this.textContent.replace(/^[A-Z]\.\s/, '');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('remove-answer', 'true');
                e.dataTransfer.setData('answer-text', answerText);
                this.style.opacity = '0.5';
            });
            
            answerBox.addEventListener('dragend', function(e) {
                this.style.opacity = '';
                
                // Always remove answer when dragged out
                if (this.classList.contains('has-answer')) {
                    const answerText = this.textContent.replace(/^[A-Z]\.\s/, '');
                    const questionNumber = this.dataset.questionNumber;
                    const questionId = this.dataset.questionId;
                    const index = this.dataset.index;
                    
                    // Clear the box
                    this.innerHTML = '<span class="placeholder-text">Drag answer here</span>';
                    this.classList.remove('has-answer');
                    this.draggable = false;
                    
                    // Clear hidden input
                    const hiddenInput = document.querySelector(`input[name="answers[${questionId}_${index}]"]`);
                    if (hiddenInput) {
                        hiddenInput.value = '';
                    }
                    
                    // Show the option again
                    const option = document.querySelector(`.draggable-option[data-option="${answerText}"]`);
                    if (option) {
                        option.style.display = 'inline-block';
                        option.classList.remove('placed');
                    }
                    
                    // Update navigation
                    const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                    if (navButton) {
                        navButton.classList.remove('answered');
                    }
                    
                    saveAllAnswers();
                    updateAnswerCount();
                }
            });
        }
        
        // Initialize drag and drop on page load
        initializeDragAndDrop();
        
        // ========== Initialize ==========
        
        // Play first part audio
        playPartAudio('1');
        
        // Update initial visibility
        updateNumberButtonsVisibility('1');
        
        // Load saved answers
        loadSavedAnswers();
        
        // Initialize annotation system
        AnnotationSystem.init();
        
        // Initialize arrow buttons
        updateArrowButtons();
        
        // Periodically save answers
        setInterval(saveAllAnswers, 30000);
        
        // Update answer count
        updateAnswerCount();
    });
    </script>
    @endpush
</x-test-layout>