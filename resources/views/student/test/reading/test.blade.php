{{-- resources/views/student/test/reading/test.blade.php --}}
<x-test-layout>
    <x-slot name="title">IELTS Reading Test</x-slot>
    
    <x-slot name="meta">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
    </x-slot>
    
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
            position: relative;
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
        
        /* Simple underline blanks - NO BOX */
        .simple-blank {
            all: unset;
            border-bottom: 1px solid #9ca3af;
            min-width: 120px;
            display: inline-block;
            text-align: center;
            padding-bottom: 2px;
            margin: 0 8px;
            font-size: inherit;
            font-family: inherit;
            color: #1f2937;
            transition: all 0.2s;
        }
        
        .simple-blank:focus {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 1px;
        }
        
        .simple-blank:not(:placeholder-shown) {
            border-bottom-color: #059669;
            font-weight: 500;
        }
        
        .simple-blank::placeholder {
            color: transparent;
        }
        
        /* Simple dropdown */
        .simple-dropdown {
            all: unset;
            border-bottom: 1px solid #9ca3af;
            min-width: 120px;
            display: inline-block;
            padding: 0 4px 2px 4px;
            margin: 0 8px;
            cursor: pointer;
            font-size: inherit;
            font-family: inherit;
        }
        
        .simple-dropdown:focus {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 1px;
        }
        
        /* Question number inline style */
        .question-number-inline {
            font-weight: 600;
            color: #1f2937;
            margin-right: 8px;
            display: inline-block;
            min-width: 20px;
        }
        
        .question-content {
            line-height: 2;
        }
        
        /* Passage highlight styles */
        .passage-content.highlighted {
            background-color: #fef3c7;
            transition: background-color 0.3s;
        }
        
        /* Text highlight colors */
        .highlight-yellow {
            background-color: #fef3c7;
            padding: 2px 4px;
            border-radius: 2px;
            cursor: pointer;
        }
        
        .highlight-green {
            background-color: #d1fae5;
            padding: 2px 4px;
            border-radius: 2px;
            cursor: pointer;
        }
        
        .highlight-blue {
            background-color: #dbeafe;
            padding: 2px 4px;
            border-radius: 2px;
            cursor: pointer;
        }
        
        /* Color picker popup */
        .color-picker {
            position: fixed;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            display: flex;
            gap: 8px;
            align-items: center;
            transition: opacity 0.2s ease-out, transform 0.2s ease-out;
        }
        
        .color-picker::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid white;
        }
        
        .color-picker.bottom::before {
            top: -8px;
            bottom: auto;
            border-top: none;
            border-bottom: 8px solid white;
        }
        
        .color-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .color-btn:hover {
            transform: scale(1.1);
            border-color: #374151;
        }
        
        .color-btn.yellow {
            background-color: #fbbf24;
        }
        
        .color-btn.green {
            background-color: #34d399;
        }
        
        .color-btn.blue {
            background-color: #60a5fa;
        }
        
        .color-btn.remove {
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Highlighted text hover effect */
        .highlight-yellow:hover,
        .highlight-green:hover,
        .highlight-blue:hover {
            filter: brightness(0.95);
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
        
        /* Passage container styles */
        .passage-container {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }
        
        .passage-container.active {
            display: block;
        }
        
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
        
        .passage-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a1a1a;
            line-height: 1.3;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .passage-content {
            line-height: 1.9;
            color: #374151;
            text-align: justify;
            font-size: 15px;
            cursor: text;
            user-select: text;
        }
        
        .passage-content p {
            margin-bottom: 16px;
            text-indent: 2em;
        }
        
        .passage-content p:first-child {
            text-indent: 0;
        }
        
        .no-passage-message {
            background-color: #fef3c7;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: #92400e;
            font-weight: 500;
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

        /* Help Guide Modal Styles */
        .help-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .help-modal-container {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        /* Header */
        .help-modal-header {
            background: #1e40af;
            color: white;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .help-header-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .help-icon {
            width: 28px;
            height: 28px;
        }

        .help-modal-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .help-close-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .help-close-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Tabs */
        .help-tabs-container {
            display: flex;
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            overflow-x: auto;
        }

        .help-tab {
            flex: 1;
            padding: 12px 20px;
            background: none;
            border: none;
            color: #6b7280;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
        }

        .help-tab:hover {
            color: #374151;
            background-color: rgba(59, 130, 246, 0.05);
        }

        .help-tab.active {
            color:rgb(208, 18, 18);
            border-bottom-color: #1e40af;
            background-color: white;
        }

        /* Content Area */
        .help-content-area {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: white;
        }

        .help-section {
            line-height: 1.6;
        }

        .help-section h3 {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 16px 0;
        }

        .help-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin: 20px 0 12px 0;
        }

        .help-section p {
            color: #4b5563;
            margin-bottom: 12px;
        }

        .help-section ul,
        .help-section ol {
            margin: 0 0 16px 0;
            padding-left: 24px;
        }

        .help-section li {
            color: #4b5563;
            margin-bottom: 8px;
        }

        .help-section strong {
            color: #1f2937;
            font-weight: 600;
        }

        /* Footer */
        .help-modal-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .help-footer-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .help-version {
            color: #9ca3af;
            font-size: 12px;
        }

        .help-footer-right {
            display: flex;
            gap: 12px;
        }

        .help-btn-secondary {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .help-btn-secondary:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-area {
                grid-template-columns: 1fr;
                height: auto;
                padding: 10px;
                padding-bottom: 120px;
            }
            
            .passage-section,
            .questions-section {
                height: auto;
                max-height: none;
                margin-bottom: 20px;
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
            
            .review-section {
                padding: 6px 12px;
            }
            
            .section-label {
                display: none;
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
            
            .nav-section-container {
                width: 100%;
            }
            
            .submit-test-button {
                width: 100%;
            }
            
            .question-number-inline {
                font-size: 14px;
            }
            
            .simple-blank,
            .simple-dropdown {
                min-width: 80px;
                margin: 0 4px;
            }
            
            .color-picker {
                top: auto !important;
                bottom: 40px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
            }
            
            .color-picker::before {
                top: auto;
                bottom: -8px;
                border-top: none;
                border-bottom: 8px solid white;
            }

            .help-modal-overlay {
                padding: 0;
            }
            
            .help-modal-container {
                max-width: 100%;
                max-height: 100%;
                border-radius: 0;
            }
            
            .help-tabs-container {
                justify-content: flex-start;
            }
            
            .help-tab {
                flex: none;
                min-width: 100px;
            }
            
            .help-content-area {
                padding: 16px;
            }
            
            .help-modal-footer {
                flex-direction: column;
                gap: 12px;
            }
            
            .help-footer-left,
            .help-footer-right {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 640px) {
            .ielts-header {
                padding: 8px 12px;
            }
            
            .ielts-header svg {
                width: 20px;
                height: 20px;
            }
            
            .ielts-header span {
                font-size: 14px;
            }
            
            .user-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 10px;
            }
            
            .user-controls {
                width: 100%;
                justify-content: space-between;
            }
            
            .passage-title {
                font-size: 18px;
            }
            
            .passage-content {
                font-size: 14px;
            }
            
            .question-box {
                font-size: 14px;
            }
            
            .modal-content {
                width: 90%;
                padding: 20px;
            }
        }
        
        /* Landscape mode for mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .content-area {
                grid-template-columns: 1fr 1fr;
                height: calc(100vh - 100px);
                padding-bottom: 80px;
            }
            
            .bottom-nav {
                flex-direction: row;
            }
            
            .nav-numbers {
                max-height: 40px;
                overflow-y: auto;
            }
        }

        /* Animations */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .help-modal-overlay[style*="display: flex"] .help-modal-container {
            animation: modalFadeIn 0.2s ease-out;
        }

        /* Scrollbar Styling */
        .help-content-area::-webkit-scrollbar {
            width: 8px;
        }

        .help-content-area::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .help-content-area::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        .help-content-area::-webkit-scrollbar-thumb:hover {
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

    <!-- User Info Bar WITH Integrated Timer -->
    <div class="user-bar">
        <div class="user-info">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ auth()->user()->name }} - BI {{ str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="user-controls">
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm help-button" id="help-button">Help ?</button>
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
        <!-- Reading Passage(s) Section -->
        <div class="passage-section">
            @php
                // Get all passages ordered by part and order
                $passages = $testSet->questions
                    ->where('question_type', 'passage')
                    ->sortBy(['part_number', 'order_number']);
                
                // Group passages by part
                $passagesByPart = $passages->groupBy('part_number');
                
                // Get all questions excluding passages
                $allQuestions = $testSet->questions
                    ->where('question_type', '!=', 'passage')
                    ->sortBy(['part_number', 'order_number']);
                    
                // Build display array with proper numbering for blanks
                $displayQuestions = [];
                $currentQuestionNumber = 1;
                
                foreach ($allQuestions as $question) {
                    $blankCount = 0;
                    
                    // Count blanks in this question
                    preg_match_all('/\[BLANK_\d+\]|\[____\d+____\]/', $question->content, $blankMatches);
                    preg_match_all('/\[DROPDOWN_\d+\]/', $question->content, $dropdownMatches);
                    $blankCount = count($blankMatches[0]) + count($dropdownMatches[0]);
                    
                    if ($blankCount > 0) {
                        // Store blank numbers for this question
                        $blankNumbers = [];
                        for ($i = 1; $i <= $blankCount; $i++) {
                            $blankNumbers[$i] = $currentQuestionNumber;
                            $currentQuestionNumber++;
                        }
                        
                        $displayQuestions[] = [
                            'question' => $question,
                            'has_blanks' => true,
                            'blank_numbers' => $blankNumbers,
                            'first_number' => $blankNumbers[1]
                        ];
                    } else {
                        // Regular question
                        $displayQuestions[] = [
                            'question' => $question,
                            'has_blanks' => false,
                            'display_number' => $currentQuestionNumber
                        ];
                        $currentQuestionNumber++;
                    }
                }
                
                $totalQuestionCount = $currentQuestionNumber - 1;
                $partsWithQuestions = $allQuestions->groupBy('part_number')->keys()->filter()->sort();
            @endphp
            
            @if ($passages->count() > 0)
                {{-- Show all passages for all parts --}}
                @foreach($partsWithQuestions as $partNumber)
                    <div class="passage-container {{ $loop->first ? 'active' : '' }}" 
                         data-part="{{ $partNumber }}"
                         id="passage-part-{{ $partNumber }}">
                        
                        @if($passagesByPart->has($partNumber))
                            {{-- Part has passages --}}
                            @foreach($passagesByPart[$partNumber] as $passage)
                                <div class="passage-content-wrapper">
                                    @if($passage->instructions)
                                        <h2 class="passage-title">{{ $passage->instructions }}</h2>
                                    @else
                                        <h2 class="passage-title">Reading Passage {{ $partNumber }}</h2>
                                    @endif
                                    
                                    @if($passage->passage_text)
                                        <div class="passage-content">
                                            @php
                                                $paragraphs = explode("\n\n", $passage->passage_text);
                                            @endphp
                                            @foreach($paragraphs as $paragraph)
                                                @if(trim($paragraph))
                                                    <p>{!! nl2br(e(trim($paragraph))) !!}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @elseif($passage->content)
                                        <div class="passage-content">
                                            @php
                                                $paragraphs = explode("\n\n", $passage->content);
                                            @endphp
                                            @foreach($paragraphs as $paragraph)
                                                @if(trim($paragraph))
                                                    <p>{!! nl2br(e(trim($paragraph))) !!}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    @if ($passage->media_path)
                                        <div class="mt-4">
                                            <img src="{{ Storage::url($passage->media_path) }}" 
                                                 alt="Passage Image" 
                                                 class="max-w-full h-auto rounded border border-gray-200">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            {{-- Part has no passage - show message --}}
                            <div class="no-passage-message">
                                <svg class="w-12 h-12 mx-auto mb-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p>No reading passage available for Part {{ $partNumber }}.</p>
                                <p class="text-sm mt-2">Questions are shown on the right side.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="no-passage-message">
                    <svg class="w-12 h-12 mx-auto mb-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p>No reading passages available for this test.</p>
                    <p class="text-sm mt-2">Please contact your administrator.</p>
                </div>
            @endif
        </div>
        
        <!-- Questions Section -->
        <div class="questions-section">
            <form id="reading-form" action="{{ route('student.reading.submit', $attempt) }}" method="POST">
                @csrf
                
                @php
                    $groupedQuestions = collect($displayQuestions)->groupBy(function($item) {
                        return $item['question']->part_number;
                    });
                @endphp
                
                @foreach ($groupedQuestions as $partNumber => $partQuestions)
                    <div class="part-questions" data-part="{{ $partNumber }}" style="{{ !$loop->first ? 'display: none;' : '' }}">
                        @if($partNumber)
                            <div class="part-header">
                                Part {{ $partNumber }}
                            </div>
                        @endif
                        
                        @php
                            $questionGroups = $partQuestions->groupBy(function($item) {
                                return $item['question']->question_group;
                            });
                        @endphp
                        
                        @foreach ($questionGroups as $groupName => $questions)
                            @if($groupName)
                                <div class="question-group-header">
                                    {{ $groupName }}
                                </div>
                            @endif
                            
                            @php
                                $instructions = $questions->pluck('question.instructions')->filter()->unique();
                            @endphp
                            
                            @foreach($instructions as $instruction)
                                <div class="question-instructions">
                                    {{ $instruction }}
                                </div>
                            @endforeach
                            
                            @foreach ($questions as $item)
                                @php
                                    $question = $item['question'];
                                    $hasBlanks = $item['has_blanks'];
                                @endphp
                                
                                <div class="question-box" id="question-{{ $question->id }}">
                                    @if($hasBlanks)
                                        {{-- Fill-in-the-blanks question with simple numbered blanks --}}
                                        @php
                                            $processedContent = $question->content;
                                            $blankNumbers = $item['blank_numbers'];
                                            
                                            // Replace blanks with simple underline inputs
                                            $blankCounter = 0;
                                            $processedContent = preg_replace_callback('/\[BLANK_(\d+)\]|\[____(\d+)____\]/', function($matches) use ($question, &$blankCounter, $blankNumbers) {
                                                $blankCounter++;
                                                $displayNum = $blankNumbers[$blankCounter];
                                                
                                                return '<span class="question-number-inline">' . $displayNum . '.</span><input type="text" 
                                                        name="answers[' . $question->id . '][blank_' . $blankCounter . ']" 
                                                        class="simple-blank" 
                                                        placeholder=""
                                                        data-question-number="' . $displayNum . '">';
                                            }, $processedContent);
                                            
                                            // Replace dropdowns similarly
                                            if ($question->section_specific_data) {
                                                $dropdownOptions = $question->section_specific_data['dropdown_options'] ?? [];
                                                
                                                $processedContent = preg_replace_callback('/\[DROPDOWN_(\d+)\]/', function($matches) use ($question, $dropdownOptions, &$blankCounter, $blankNumbers) {
                                                    $dropdownNum = $matches[1];
                                                    $blankCounter++;
                                                    $displayNum = $blankNumbers[$blankCounter];
                                                    $options = isset($dropdownOptions[$dropdownNum]) ? explode(',', $dropdownOptions[$dropdownNum]) : [];
                                                    
                                                    $selectHtml = '<span class="question-number-inline">' . $displayNum . '.</span>
                                                                   <select name="answers[' . $question->id . '][dropdown_' . $dropdownNum . ']" 
                                                                           class="simple-dropdown" 
                                                                           data-question-number="' . $displayNum . '">
                                                                   <option value="">______</option>';
                                                    
                                                    foreach ($options as $option) {
                                                        $selectHtml .= '<option value="' . trim($option) . '">' . trim($option) . '</option>';
                                                    }
                                                    
                                                    $selectHtml .= '</select>';
                                                    return $selectHtml;
                                                }, $processedContent);
                                            }
                                        @endphp
                                        
                                        <div class="question-content">
                                            {!! $processedContent !!}
                                        </div>
                                    @else
                                        {{-- Regular question --}}
                                        <div class="question-number">
                                            {{ $item['display_number'] }}. {!! $question->content !!}
                                        </div>
                                        
                                        @if ($question->media_path)
                                            <div class="mb-3">
                                                <img src="{{ Storage::url($question->media_path) }}" alt="Question Image" class="max-w-full h-auto rounded">
                                            </div>
                                        @endif
                                        
                                        <div class="options-list">
                                            @switch($question->question_type)
                                                @case('multiple_choice')
                                                    @foreach ($question->options as $optionIndex => $option)
                                                        <div class="option-item">
                                                            <input type="radio" 
                                                                   name="answers[{{ $question->id }}]" 
                                                                   id="option-{{ $option->id }}" 
                                                                   value="{{ $option->id }}" 
                                                                   class="option-radio"
                                                                   data-question-number="{{ $item['display_number'] }}">
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
                                                                   class="option-radio"
                                                                   data-question-number="{{ $item['display_number'] }}">
                                                            <label for="option-{{ $option->id }}">{{ $option->content }}</label>
                                                        </div>
                                                    @endforeach
                                                    @break
                                                
                                                @case('matching')
                                                @case('matching_headings')
                                                @case('matching_information')
                                                @case('matching_features')
                                                    <select name="answers[{{ $question->id }}]" class="text-input" data-question-number="{{ $item['display_number'] }}">
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
                                                           maxlength="100"
                                                           data-question-number="{{ $item['display_number'] }}">
                                                    @break
                                            @endswitch
                                        </div>
                                    @endif
                                </div>
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
                <label for="review-checkbox" class="review-label">Review</label>
            </div>
            
            <div class="nav-section-container">
                <span class="section-label">Reading</span>
                
                {{-- Parts Navigation - Show all parts --}}
                <div class="parts-nav">
                    @foreach($partsWithQuestions as $partNum)
                        <button type="button" class="part-btn {{ $loop->first ? 'active' : '' }}" data-part="{{ $partNum }}">
                            Part {{ $partNum }}
                        </button>
                    @endforeach
                </div>
                
                {{-- Question Numbers --}}
                <div class="nav-numbers">
                    @foreach($displayQuestions as $item)
                        @if($item['has_blanks'])
                            {{-- Show number for each blank --}}
                            @foreach($item['blank_numbers'] as $blankIndex => $number)
                                <div class="number-btn {{ $loop->parent->first && $loop->first ? 'active' : '' }}" 
                                     data-question="{{ $item['question']->id }}"
                                     data-blank="{{ $blankIndex }}"
                                     data-display-number="{{ $number }}"
                                     data-part="{{ $item['question']->part_number }}">
                                    {{ $number }}
                                </div>
                            @endforeach
                        @else
                            {{-- Regular question button --}}
                            <div class="number-btn {{ $loop->first ? 'active' : '' }}" 
                                 data-question="{{ $item['question']->id }}"
                                 data-display-number="{{ $item['display_number'] }}"
                                 data-part="{{ $item['question']->part_number }}">
                                {{ $item['display_number'] }}
                            </div>
                        @endif
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
                <strong>Answered Questions: <span id="answered-count">0</span> / {{ $totalQuestionCount }}</strong>
            </div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="modal-button" id="confirm-submit-btn">Yes, Submit</button>
                <button class="modal-button secondary" id="cancel-submit-btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Help Guide Modal (Inline Implementation) -->
    <div id="help-modal" class="help-modal-overlay" style="display: none;">
        <div class="help-modal-container">
            <!-- Header -->
            <div class="help-modal-header">
                <div class="help-header-content">
                    <svg class="help-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h2 class="help-modal-title">Test Guide</h2>
                </div>
                <button class="help-close-btn" id="help-close-btn">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
         
            
            <!-- Dynamic Content Area -->
            <div class="help-content-area" id="help-content">
                <!-- Content will be loaded here -->
            </div>
            
            <!-- Footer -->
            <div class="help-modal-footer">
                <div class="help-footer-left">
                    <span class="help-version">RX 1.0</span>
                </div>
                <div class="help-footer-right">
                    <button class="help-btn-secondary" id="help-video-btn">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Watch Tutorial
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Inline Help Guide Implementation
    (function() {
        const HelpGuide = {
            init: function() {
                this.loadContent('overview');
                this.setupEventListeners();
            },
            
            setupEventListeners: function() {
                // Tab switching
                document.querySelectorAll('.help-tab').forEach(tab => {
                    tab.addEventListener('click', (e) => {
                        e.preventDefault();
                        const section = tab.dataset.section;
                        
                        // Update active tab
                        document.querySelectorAll('.help-tab').forEach(t => t.classList.remove('active'));
                        tab.classList.add('active');
                        
                        // Load content
                        this.loadContent(section);
                    });
                });
                
                // Close button
                const closeBtn = document.getElementById('help-close-btn');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => this.close());
                }
                
                // Click outside to close
                const modal = document.getElementById('help-modal');
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.close();
                    }
                });
                
                // Video button
                const videoBtn = document.getElementById('help-video-btn');
                if (videoBtn) {
                    videoBtn.addEventListener('click', () => {
                        alert('Video tutorial coming soon!');
                    });
                }
            },
            
            open: function() {
                const modal = document.getElementById('help-modal');
                if (modal) {
                    modal.style.display = 'flex';
                    this.loadContent('overview');
                }
            },
            
            close: function() {
                const modal = document.getElementById('help-modal');
                if (modal) {
                    modal.style.display = 'none';
                }
            },
            
            loadContent: function(section) {
                const contentArea = document.getElementById('help-content');
                if (!contentArea) return;
                
                const contents = {
                    overview: `
                        <div class="help-section">
                            <h3>Banglay IELTS - Reading Guide</h3>
                            <p>Welcome to the IELTS Computer-Delivered Reading Test. This test consists of:</p>
                            <ul>
                                <li><strong>Duration:</strong> 60 minutes</li>
                                <li><strong>Questions:</strong> 40 questions in total</li>
                                <li><strong>Sections:</strong> 3 reading passages</li>
                                <li><strong>Difficulty:</strong> Passages increase in difficulty</li>
                            </ul>
                            
                            <h4>Test Interface</h4>
                            <p>The screen is divided into two sections:</p>
                            <ul>
                                <li><strong>Left side:</strong> Reading passage</li>
                                <li><strong>Right side:</strong> Questions</li>
                            </ul>
                            
                            <h4>Important Features</h4>
                            <ul>
                                <li>You can highlight text in the passage using different colors</li>
                                <li>Timer shows remaining time at the top of the screen</li>
                                <li>Navigation buttons at the bottom let you jump between questions</li>
                                <li>Review checkbox allows you to flag questions for later review</li>
                            </ul>
                        </div>
                    `,
                    
                   
                   
                    
                    
                };
                
                contentArea.innerHTML = contents[section] || '<p>Content not available</p>';
            }
        };
        
        // Make it globally available
        window.HelpGuide = HelpGuide;
        
        // Initialize after DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            HelpGuide.init();
            
            // Setup help button
            const helpButton = document.getElementById('help-button');
            if (helpButton) {
                helpButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    HelpGuide.open();
                });
            }
        });
    })();

    // Continue with your existing test code
    document.addEventListener('DOMContentLoaded', function() {
        // Your existing code continues here...
        const submitButton = document.getElementById('submit-button');
        const navButtons = document.querySelectorAll('.number-btn');
        const partButtons = document.querySelectorAll('.part-btn');
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const answeredCountSpan = document.getElementById('answered-count');
        const passageContainers = document.querySelectorAll('.passage-container');
        const questionParts = document.querySelectorAll('.part-questions');
        
        // Part navigation - Show corresponding passage and questions
        partButtons.forEach(button => {
            button.addEventListener('click', function() {
                partButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const partNumber = this.dataset.part;
                
                // Hide all question parts first
                questionParts.forEach(part => {
                    part.style.display = 'none';
                });
                
                // Show questions for this part
                const targetQuestionPart = document.querySelector(`.part-questions[data-part="${partNumber}"]`);
                if (targetQuestionPart) {
                    targetQuestionPart.style.display = 'block';
                }
                
                // Update passage display
                updatePassageDisplay(partNumber);
                
                // Find first question of this part
                const firstQuestionOfPart = document.querySelector(`.number-btn[data-part="${partNumber}"]`);
                if (firstQuestionOfPart) {
                    firstQuestionOfPart.click();
                }
            });
        });
        
        // Function to update passage display based on part
        function updatePassageDisplay(partNumber) {
            // Hide all passages first
            passageContainers.forEach(container => {
                container.classList.remove('active');
            });
            
            // Show the passage for this part
            const partPassage = document.querySelector(`.passage-container[data-part="${partNumber}"]`);
            if (partPassage) {
                partPassage.classList.add('active');
            }
        }
        
        // Question navigation
        navButtons.forEach(button => {
            button.addEventListener('click', function() {
                navButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const questionId = this.dataset.question;
                const blankIndex = this.dataset.blank;
                const partNumber = this.dataset.part;
                const questionElement = document.getElementById(`question-${questionId}`);
                
                if (questionElement) {
                    // Smooth scroll to question
                    questionElement.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    
                    // If specific blank, focus on it
                    if (blankIndex) {
                        const inputs = questionElement.querySelectorAll('.simple-blank, .simple-dropdown');
                        if (inputs[blankIndex - 1]) {
                            setTimeout(() => inputs[blankIndex - 1].focus(), 300);
                        }
                    }
                    
                    // Update active part if needed
                    if (partNumber) {
                        const currentActivePart = document.querySelector('.part-btn.active');
                        if (!currentActivePart || currentActivePart.dataset.part !== partNumber) {
                            partButtons.forEach(btn => {
                                if (btn.dataset.part === partNumber) {
                                    btn.click(); // This will update everything
                                }
                            });
                        }
                    }
                }
                
                // Update review checkbox based on flagged status
                const reviewCheckbox = document.getElementById('review-checkbox');
                reviewCheckbox.checked = this.classList.contains('flagged');
            });
        });
        
        // Handle individual blank/dropdown tracking
        document.querySelectorAll('.simple-blank, .simple-dropdown').forEach(input => {
            input.addEventListener('change', function() {
                const questionNumber = this.dataset.questionNumber;
                if (questionNumber) {
                    const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                    if (navButton && this.value.trim()) {
                        navButton.classList.add('answered');
                    } else if (navButton && !this.value.trim()) {
                        navButton.classList.remove('answered');
                    }
                }
                saveAllAnswers();
            });
            
            input.addEventListener('blur', function() {
                const questionNumber = this.dataset.questionNumber;
                if (questionNumber) {
                    const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                    if (navButton && this.value.trim()) {
                        navButton.classList.add('answered');
                    } else if (navButton && !this.value.trim()) {
                        navButton.classList.remove('answered');
                    }
                }
                saveAllAnswers();
            });
        });
        
        // Handle regular questions (radio, text, select)
        document.querySelectorAll('input[type="radio"], input[type="text"]:not(.simple-blank), select:not(.simple-dropdown)').forEach(input => {
            input.addEventListener('change', function() {
                const questionNumber = this.dataset.questionNumber;
                if (questionNumber) {
                    const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                    if (navButton && this.value) {
                        navButton.classList.add('answered');
                    }
                }
                saveAllAnswers();
            });
        });
        
        // Simple auto-width adjustment for blanks
        document.querySelectorAll('.simple-blank').forEach(input => {
            input.addEventListener('input', function() {
                // Auto adjust width
                const length = this.value.length;
                if (length > 8) {
                    this.style.width = (length * 9) + 'px';
                } else {
                    this.style.width = '120px';
                }
            });
            
            // Tab navigation between blanks
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const allInputs = document.querySelectorAll('.simple-blank, .simple-dropdown');
                    const currentIndex = Array.from(allInputs).indexOf(this);
                    const nextIndex = e.shiftKey ? currentIndex - 1 : currentIndex + 1;
                    
                    if (nextIndex >= 0 && nextIndex < allInputs.length) {
                        allInputs[nextIndex].focus();
                    }
                }
            });
        });
        
        // Passage selection functionality with color picker
        const passageContents = document.querySelectorAll('.passage-content');
        let currentColorPicker = null;
        let selectedTextRange = null;
        
        // Initialize passage selection
        passageContents.forEach(passage => {
            // Prevent text selection on simple click
            let isSelecting = false;
            
            passage.addEventListener('mousedown', function() {
                isSelecting = false;
            });
            
            passage.addEventListener('mousemove', function() {
                isSelecting = true;
            });
            
            // Enable text selection with color picker
            passage.addEventListener('mouseup', function(e) {
                // Small delay to ensure selection is complete
                setTimeout(() => {
                    const selection = window.getSelection();
                    const selectedText = selection.toString().trim();
                    
                    if (selectedText.length > 0 && isSelecting) {
                        // Remove any existing color picker first
                        removeColorPicker();
                        
                        // Store the range for later use
                        selectedTextRange = selection.getRangeAt(0).cloneRange();
                        
                        // Show color picker with slight delay for smooth animation
                        setTimeout(() => {
                            showColorPicker(e);
                        }, 50);
                    }
                }, 10);
            });
            
            // Click on highlighted text to remove highlight
            passage.addEventListener('click', function(e) {
                if (e.target.classList.contains('highlight-yellow') || 
                    e.target.classList.contains('highlight-green') || 
                    e.target.classList.contains('highlight-blue')) {
                    
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Remove highlight with animation
                    e.target.style.transition = 'background-color 0.3s';
                    e.target.style.backgroundColor = 'transparent';
                    
                    setTimeout(() => {
                        const text = e.target.textContent;
                        e.target.replaceWith(document.createTextNode(text));
                    }, 300);
                }
            });
        });
        
        // Show color picker with smooth animation
        function showColorPicker(e) {
            // Get selection bounds
            const selection = window.getSelection();
            if (!selection.rangeCount) return;
            
            const range = selection.getRangeAt(0);
            const rect = range.getBoundingClientRect();
            
            // Create color picker element
            const picker = document.createElement('div');
            picker.className = 'color-picker';
            picker.style.opacity = '0';
            picker.style.transform = 'translateY(5px)';
            picker.innerHTML = `
                <button class="color-btn yellow" data-color="yellow" title="Yellow highlight"></button>
                <button class="color-btn green" data-color="green" title="Green highlight"></button>
                <button class="color-btn blue" data-color="blue" title="Blue highlight"></button>
                <div class="color-btn remove" title="Cancel">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            `;
            
            // Add to body first to calculate dimensions
            document.body.appendChild(picker);
            
            // Calculate position
            const pickerRect = picker.getBoundingClientRect();
            let top = rect.top - pickerRect.height - 15;
            let left = rect.left + (rect.width / 2) - (pickerRect.width / 2);
            
            // Adjust for viewport bounds
            if (top < 10) {
                top = rect.bottom + 15;
                picker.classList.add('bottom');
            }
            
            if (left < 10) {
                left = 10;
            } else if (left + pickerRect.width > window.innerWidth - 10) {
                left = window.innerWidth - pickerRect.width - 10;
            }
            
            // Apply position
            picker.style.position = 'fixed';
            picker.style.top = top + 'px';
            picker.style.left = left + 'px';
            picker.style.zIndex = '9999';
            
            // Animate in
            requestAnimationFrame(() => {
                picker.style.transition = 'opacity 0.2s ease-out, transform 0.2s ease-out';
                picker.style.opacity = '1';
                picker.style.transform = 'translateY(0)';
            });
            
            currentColorPicker = picker;
            
            // Add click handlers
            picker.querySelectorAll('.color-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const color = this.dataset.color;
                    
                    if (color) {
                        applyHighlight(color);
                    }
                    
                    removeColorPicker();
                });
            });
        }
        
        // Apply highlight to selected text with better handling
        function applyHighlight(color) {
            if (!selectedTextRange) return;
            
            try {
                // Restore the selection
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(selectedTextRange);
                
                // Check if selection is valid
                const selectedText = selection.toString().trim();
                if (!selectedText) return;
                
                // Create span with highlight
                const span = document.createElement('span');
                span.className = `highlight-${color}`;
                span.style.transition = 'background-color 0.3s ease-in';
                
                try {
                    // Try to wrap the selected content
                    selectedTextRange.surroundContents(span);
                } catch (e) {
                    // If surroundContents fails, use alternative method
                    const contents = selectedTextRange.extractContents();
                    span.appendChild(contents);
                    selectedTextRange.insertNode(span);
                }
                
                // Animate the highlight
                requestAnimationFrame(() => {
                    span.style.backgroundColor = '';
                });
                
            } catch (e) {
                console.error('Error applying highlight:', e);
            } finally {
                // Clear selection
                window.getSelection().removeAllRanges();
            }
        }
        
        // Remove color picker with animation
        function removeColorPicker() {
            if (currentColorPicker) {
                currentColorPicker.style.opacity = '0';
                currentColorPicker.style.transform = 'translateY(5px)';
                
                setTimeout(() => {
                    if (currentColorPicker && currentColorPicker.parentNode) {
                        currentColorPicker.remove();
                    }
                    currentColorPicker = null;
                }, 200);
            }
            selectedTextRange = null;
        }
        
        // Close color picker when clicking elsewhere
        document.addEventListener('mousedown', function(e) {
            if (currentColorPicker && !e.target.closest('.color-picker') && !e.target.closest('.passage-content')) {
                removeColorPicker();
            }
        });
        
        // Close color picker on scroll with debounce
        let scrollTimeout;
        document.addEventListener('scroll', function() {
            if (currentColorPicker) {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    removeColorPicker();
                }, 100);
            }
        }, true);
        
        // Keyboard support - ESC to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && currentColorPicker) {
                removeColorPicker();
            }
        });
        
        // Mobile touch support
        if ('ontouchstart' in window) {
            passageContents.forEach(passage => {
                let touchTimer;
                
                passage.addEventListener('touchstart', function(e) {
                    touchTimer = setTimeout(() => {
                        // Long press to select word
                        const touch = e.touches[0];
                        const word = getWordAtPoint(touch.clientX, touch.clientY);
                        
                        if (word) {
                            // Select the word
                            const selection = window.getSelection();
                            const range = document.createRange();
                            
                            // Find and select the word
                            const textNodes = getTextNodes(passage);
                            for (let node of textNodes) {
                                const index = node.textContent.indexOf(word);
                                if (index !== -1) {
                                    range.setStart(node, index);
                                    range.setEnd(node, index + word.length);
                                    selection.removeAllRanges();
                                    selection.addRange(range);
                                    
                                    // Show color picker
                                    showColorPicker(e);
                                    break;
                                }
                            }
                        }
                    }, 500);
                });
                
                passage.addEventListener('touchend', function() {
                    clearTimeout(touchTimer);
                });
                
                passage.addEventListener('touchmove', function() {
                    clearTimeout(touchTimer);
                });
            });
        }
        
        // Helper function to get all text nodes
        function getTextNodes(element) {
            const textNodes = [];
            const walker = document.createTreeWalker(
                element,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );
            
            let node;
            while (node = walker.nextNode()) {
                textNodes.push(node);
            }
            
            return textNodes;
        }
        
        // Helper function to get word at click point
        function getWordAtPoint(x, y) {
            const range = document.caretRangeFromPoint(x, y);
            if (range && range.startContainer.nodeType === Node.TEXT_NODE) {
                const text = range.startContainer.textContent;
                const offset = range.startOffset;
                
                // Find word boundaries
                let start = offset;
                let end = offset;
                
                while (start > 0 && /\S/.test(text[start - 1])) start--;
                while (end < text.length && /\S/.test(text[end])) end++;
                
                return text.substring(start, end).trim();
            }
            return null;
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
            const answeredCount = document.querySelectorAll('.number-btn.answered').length;
            answeredCountSpan.textContent = answeredCount;
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
        setInterval(saveAllAnswers, 30000); // Every 30 seconds
        
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
                                // Update nav button
                                const questionNumber = radio.dataset.questionNumber;
                                if (questionNumber) {
                                    const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                                    if (navButton) {
                                        navButton.classList.add('answered');
                                    }
                                }
                            }
                        } else {
                            input.value = value;
                            if (value) {
                                // Update nav button
                                const questionNumber = input.dataset.questionNumber;
                                if (questionNumber) {
                                    const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
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
        
        // Initialize first part on load
        const firstPartBtn = document.querySelector('.part-btn');
        if (firstPartBtn) {
            firstPartBtn.click();
        }
    });
    </script>
    @endpush
</x-test-layout>