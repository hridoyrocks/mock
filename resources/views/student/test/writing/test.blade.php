{{-- resources/views/student/test/writing/test.blade.php --}}
<x-test-layout>
    <x-slot:title>IELTS Writing Test</x-slot>
    
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
            height: 100vh;
            overflow: hidden;
        }
        
        .main-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .ielts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            flex-shrink: 0;
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
            flex-shrink: 0;
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
        
        .content-wrapper {
            flex: 1;
            display: flex;
            overflow: hidden;
        }
        
        .left-panel {
            width: 45%;
            background-color: #f8f9fa;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .question-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .task-info {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }
        
        .task-info h3 {
            margin: 0 0 5px 0;
            color: #1f2937;
            font-size: 18px;
        }
        
        .task-info p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        
        .question-prompt {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .question-prompt h4 {
            margin-top: 0;
            color: #1f2937;
        }
        
        .prompt-text {
            line-height: 1.6;
            color: #374151;
        }
        
        .task-image {
            width: 100%;
            max-width: 500px;
            height: auto;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-top: 15px;
        }
        
        .right-panel {
            flex: 1;
            background-color: white;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .editor-header {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        
        .word-count-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .word-count {
            font-weight: 500;
            color: #374151;
            font-size: 16px;
        }
        
        .word-count-number {
            font-weight: bold;
            color: #3b82f6;
        }
        
        .word-requirement {
            font-size: 14px;
            color: #6b7280;
        }
        
        .autosave-status {
            font-size: 14px;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .editor-area {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .editor-textarea {
            width: 100%;
            height: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 20px;
            font-size: 16px;
            line-height: 1.8;
            font-family: 'Times New Roman', Times, serif;
            resize: none;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .editor-textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        
        .task-nav {
            display: flex;
            gap: 10px;
        }
        
        .task-btn {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            color: #374151;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .task-btn.active {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .task-btn:hover:not(.active) {
            background-color: #f3f4f6;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .submit-btn {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
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
            padding: 32px;
            border-radius: 12px;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .modal-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
            color: #1f2937;
        }
        
        .modal-message {
            font-size: 16px;
            margin-bottom: 24px;
            line-height: 1.6;
            color: #4b5563;
        }
        
        .word-summary {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        
        .word-summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .word-summary-item:last-child {
            border-bottom: none;
        }
        
        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 24px;
        }
        
        .modal-button {
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .modal-button.primary {
            background-color: #10b981;
            color: white;
        }
        
        .modal-button.primary:hover {
            background-color: #059669;
        }
        
        .modal-button.secondary {
            background-color: #e5e7eb;
            color: #4b5563;
        }
        
        .modal-button.secondary:hover {
            background-color: #d1d5db;
        }
        
        /* Smooth scrollbar for question panel */
        .question-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .question-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .question-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .question-content::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        @media (max-width: 1024px) {
            .left-panel {
                width: 50%;
            }
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
            }
            
            .left-panel {
                width: 100%;
                height: 40%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .right-panel {
                height: 60%;
            }
        }
    </style>

    <div class="main-container">
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

        <!-- User Info Bar -->
        <div class="user-bar">
            <div class="user-info">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ auth()->user()->name }} - BI {{ str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="user-controls">
                <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm">Help ?</button>
                <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm">Hide</button>
                
                {{-- Timer Component --}}
                <x-test-timer 
                    :attempt="$attempt" 
                    auto-submit-form-id="writing-form"
                    position="integrated"
                    :warning-time="600"
                    :danger-time="300"
                />
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content-wrapper">
            @php
                // Get all questions and sort by order_number
                $questions = $testSet->questions()->orderBy('order_number')->get();
                
                // If we have less than 2 questions, show error
                if ($questions->count() < 2) {
                    echo '<div style="padding: 20px; color: red;">This writing test needs at least 2 questions. Currently has: ' . $questions->count() . '</div>';
                    return;
                }
                
                // Take first 2 questions as Task 1 and Task 2
                $taskOneQuestion = $questions->first();
                $taskTwoQuestion = $questions->skip(1)->first();
                
                // Get existing answers
                $taskOneAnswer = $attempt->answers->where('question_id', $taskOneQuestion->id)->first();
                $taskTwoAnswer = $attempt->answers->where('question_id', $taskTwoQuestion->id)->first();
            @endphp

            <!-- Left Panel - Questions -->
            <div class="left-panel">
                <!-- Task 1 Content -->
                <div class="question-content" id="task-1-content" style="display: block;">
                    <div class="task-info">
                        <h3>Writing Task 1</h3>
                        <p>Suggested time: {{ $taskOneQuestion->time_limit ?? 20 }} minutes | Minimum {{ $taskOneQuestion->word_limit ?? 150 }} words</p>
                    </div>
                    
                    <div class="question-prompt">
                        <h4>Task</h4>
                        <div class="prompt-text">
                            {!! nl2br(e($taskOneQuestion->content)) !!}
                        </div>
                        
                        @if($taskOneQuestion->media_path)
                            <img src="{{ Storage::url($taskOneQuestion->media_path) }}" 
                                 alt="Task 1 Visual" 
                                 class="task-image">
                        @endif
                    </div>
                    
                    @if($taskOneQuestion->instructions)
                    <div class="question-prompt">
                        <h4>Instructions</h4>
                        <div class="prompt-text">
                            {!! nl2br(e($taskOneQuestion->instructions)) !!}
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Task 2 Content -->
                <div class="question-content" id="task-2-content" style="display: none;">
                    <div class="task-info">
                        <h3>Writing Task 2</h3>
                        <p>Suggested time: {{ $taskTwoQuestion->time_limit ?? 40 }} minutes | Minimum {{ $taskTwoQuestion->word_limit ?? 250 }} words</p>
                    </div>
                    
                    <div class="question-prompt">
                        <h4>Essay Task</h4>
                        <div class="prompt-text">
                            {!! nl2br(e($taskTwoQuestion->content)) !!}
                        </div>
                    </div>
                    
                    @if($taskTwoQuestion->instructions)
                    <div class="question-prompt">
                        <h4>Instructions</h4>
                        <div class="prompt-text">
                            {!! nl2br(e($taskTwoQuestion->instructions)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Panel - Writing Area -->
            <div class="right-panel">
                <form id="writing-form" action="{{ route('student.writing.submit', $attempt) }}" method="POST" style="height: 100%; display: flex; flex-direction: column;">
                    @csrf
                    
                    <div class="editor-header">
                        <div class="word-count-info">
                            <div class="word-count">
                                Word count: <span class="word-count-number" id="current-word-count">0</span>
                            </div>
                            <div class="word-requirement" id="word-requirement">
                                Minimum: {{ $taskOneQuestion->word_limit ?? 150 }} words
                            </div>
                        </div>
                        <div class="autosave-status" id="autosave-status">
                            <span id="save-text"></span>
                        </div>
                    </div>
                    
                    <div class="editor-area">
                        <!-- Task 1 Editor -->
                        <textarea 
                            id="editor-task-1" 
                            name="answers[{{ $taskOneQuestion->id }}]" 
                            class="editor-textarea"
                            placeholder="Start writing your Task 1 response here..."
                        >{{ old('answers.' . $taskOneQuestion->id, $taskOneAnswer->answer ?? '') }}</textarea>
                        
                        <!-- Task 2 Editor -->
                        <textarea 
                            id="editor-task-2" 
                            name="answers[{{ $taskTwoQuestion->id }}]" 
                            class="editor-textarea"
                            style="display: none;"
                            placeholder="Start writing your Task 2 essay here..."
                        >{{ old('answers.' . $taskTwoQuestion->id, $taskTwoAnswer->answer ?? '') }}</textarea>
                    </div>
                    
                    <button type="submit" id="submit-button" class="hidden">Submit</button>
                </form>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <div class="bottom-nav">
            <div class="nav-left">
                <div class="task-nav">
                    <button type="button" class="task-btn active" onclick="switchTask(1)">Task 1</button>
                    <button type="button" class="task-btn" onclick="switchTask(2)">Task 2</button>
                </div>
            </div>
            <div class="nav-right">
                <button type="button" id="submit-test-btn" class="submit-btn">
                    Submit Test
                </button>
            </div>
        </div>
    </div>

    <!-- Submit Modal -->
    <div id="submit-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-title">Ready to Submit?</div>
            <div class="modal-message">
                Please review your word count before submitting:
            </div>
            <div class="word-summary">
                <div class="word-summary-item">
                    <span><strong>Task 1:</strong></span>
                    <span id="modal-task1-words">0 words</span>
                </div>
                <div class="word-summary-item">
                    <span><strong>Task 2:</strong></span>
                    <span id="modal-task2-words">0 words</span>
                </div>
            </div>
            <div class="modal-message">
                Once submitted, you cannot change your answers.
            </div>
            <div class="modal-buttons">
                <button class="modal-button primary" id="confirm-submit-btn">Submit Test</button>
                <button class="modal-button secondary" id="cancel-submit-btn">Continue Writing</button>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
    let currentTask = 1;
    const wordCounts = {1: 0, 2: 0};
    let autosaveTimers = {1: null, 2: null};
    const wordLimits = {
        1: {{ $taskOneQuestion->word_limit ?? 150 }},
        2: {{ $taskTwoQuestion->word_limit ?? 250 }}
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        const editor1 = document.getElementById('editor-task-1');
        const editor2 = document.getElementById('editor-task-2');
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const submitButton = document.getElementById('submit-button');
        
        // Initialize word counts
        updateWordCount(1, editor1.value);
        updateWordCount(2, editor2.value);
        
        // Setup word count listeners
        editor1.addEventListener('input', function() {
            updateWordCount(1, this.value);
            setupAutosave(1, this.value);
        });
        
        editor2.addEventListener('input', function() {
            updateWordCount(2, this.value);
            setupAutosave(2, this.value);
        });
        
        // Submit handlers
        submitTestBtn.addEventListener('click', function() {
            document.getElementById('modal-task1-words').textContent = wordCounts[1] + ' words';
            document.getElementById('modal-task2-words').textContent = wordCounts[2] + ' words';
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
    
    function switchTask(taskNumber) {
        currentTask = taskNumber;
        
        // Update bottom nav buttons
        document.querySelectorAll('.task-btn').forEach((btn, index) => {
            if (index === taskNumber - 1) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        // Update question content
        document.getElementById('task-1-content').style.display = taskNumber === 1 ? 'block' : 'none';
        document.getElementById('task-2-content').style.display = taskNumber === 2 ? 'block' : 'none';
        
        // Update editor
        document.getElementById('editor-task-1').style.display = taskNumber === 1 ? 'block' : 'none';
        document.getElementById('editor-task-2').style.display = taskNumber === 2 ? 'block' : 'none';
        
        // Update word count display
        document.getElementById('current-word-count').textContent = wordCounts[taskNumber];
        document.getElementById('word-requirement').textContent = 
            'Minimum: ' + wordLimits[taskNumber] + ' words';
    }
    
    function updateWordCount(taskNumber, text) {
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const count = text.trim() === '' ? 0 : words.length;
        wordCounts[taskNumber] = count;
        
        if (taskNumber === currentTask) {
            document.getElementById('current-word-count').textContent = count;
            
            // Update color based on requirement
            const requirement = wordLimits[taskNumber];
            const countElement = document.getElementById('current-word-count');
            if (count >= requirement) {
                countElement.style.color = '#10b981';
            } else if (count >= requirement * 0.8) {
                countElement.style.color = '#f59e0b';
            } else {
                countElement.style.color = '#3b82f6';
            }
        }
    }
    
    function setupAutosave(taskNumber, content) {
        clearTimeout(autosaveTimers[taskNumber]);
        
        const statusElement = document.getElementById('autosave-status');
        document.getElementById('save-text').textContent = 'Saving...';
        
        autosaveTimers[taskNumber] = setTimeout(() => {
            autosave(taskNumber, content);
        }, 2000);
    }
    
    function autosave(taskNumber, content) {
        const questionId = taskNumber === 1 ? {{ $taskOneQuestion->id }} : {{ $taskTwoQuestion->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`{{ route('student.writing.autosave', [$attempt->id, '__QUESTION_ID__']) }}`.replace('__QUESTION_ID__', questionId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                content: content
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('save-text').textContent = 'Saved';
            
            setTimeout(() => {
                document.getElementById('save-text').textContent = '';
            }, 2000);
        })
        .catch(error => {
            document.getElementById('save-text').textContent = 'Error saving';
        });
    }
    </script>
    @endpush
</x-test-layout>