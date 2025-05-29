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
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .task-section {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .task-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
        }
        
        .task-prompt {
            background-color: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .editor-container {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .editor-toolbar {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }
        
        .word-count {
            font-weight: 500;
            color: #374151;
        }
        
        .autosave-status {
            font-size: 12px;
            color: #10b981;
        }
        
        .editor-textarea {
            width: 100%;
            border: none;
            padding: 16px;
            font-size: 14px;
            line-height: 1.6;
            resize: none;
            outline: none;
            font-family: 'Times New Roman', Times, serif;
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
                padding: 10px;
                padding-bottom: 100px;
            }
            
            .task-nav {
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
                auto-submit-form-id="writing-form"
                position="integrated"
                :warning-time="600"
                :danger-time="300"
            />
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        <form id="writing-form" action="{{ route('student.writing.submit', $attempt) }}" method="POST">
            @csrf
            
            @php
                $taskOneQuestion = $testSet->questions->where('question_type', 'essay')->where('order_number', 1)->first();
                $taskTwoQuestion = $testSet->questions->where('question_type', 'essay')->where('order_number', 2)->first();
                $taskOneAnswer = $attempt->answers->where('question_id', $taskOneQuestion->id)->first();
                $taskTwoAnswer = $attempt->answers->where('question_id', $taskTwoQuestion->id)->first();
            @endphp
            
            <!-- Task 1 -->
            <div class="task-section" id="task-1">
                <div class="task-header">
                    <div>
                        <h2 class="text-xl font-semibold">Writing Task 1</h2>
                        <p class="text-sm text-gray-600 mt-1">Suggested time: 20 minutes | Minimum 150 words</p>
                    </div>
                </div>
                
                <div class="task-prompt">
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($taskOneQuestion->content)) !!}
                    </div>
                    
                    @if($taskOneQuestion->media_path)
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $taskOneQuestion->media_path) }}" alt="Task 1 Image" class="max-w-full h-auto border rounded">
                        </div>
                    @endif
                </div>
                
                <div class="editor-container">
                    <div class="editor-toolbar">
                        <div class="word-count">
                            Word count: <span id="word-count-1">0</span>
                        </div>
                        <div class="autosave-status" id="autosave-1"></div>
                    </div>
                    
                    <textarea 
                        id="writing-editor-{{ $taskOneQuestion->id }}" 
                        name="answers[{{ $taskOneQuestion->id }}]" 
                        class="editor-textarea" 
                        rows="12"
                        placeholder="Write your answer here..."
                    >{{ old('answers.' . $taskOneQuestion->id, $taskOneAnswer->answer ?? '') }}</textarea>
                </div>
            </div>
            
            <!-- Task 2 -->
            <div class="task-section" id="task-2">
                <div class="task-header">
                    <div>
                        <h2 class="text-xl font-semibold">Writing Task 2</h2>
                        <p class="text-sm text-gray-600 mt-1">Suggested time: 40 minutes | Minimum 250 words</p>
                    </div>
                </div>
                
                <div class="task-prompt">
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($taskTwoQuestion->content)) !!}
                    </div>
                </div>
                
                <div class="editor-container">
                    <div class="editor-toolbar">
                        <div class="word-count">
                            Word count: <span id="word-count-2">0</span>
                        </div>
                        <div class="autosave-status" id="autosave-2"></div>
                    </div>
                    
                    <textarea 
                        id="writing-editor-{{ $taskTwoQuestion->id }}" 
                        name="answers[{{ $taskTwoQuestion->id }}]" 
                        class="editor-textarea" 
                        rows="15"
                        placeholder="Write your answer here..."
                    >{{ old('answers.' . $taskTwoQuestion->id, $taskTwoAnswer->answer ?? '') }}</textarea>
                </div>
            </div>
            
            <button type="submit" id="submit-button" class="hidden">Submit</button>
        </form>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-left">
            <div class="task-nav">
                <button type="button" class="task-btn active" data-task="1">Task 1</button>
                <button type="button" class="task-btn" data-task="2">Task 2</button>
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
                Are you sure you want to submit your writing test? You cannot change your answers after submission.
                <br><br>
                <strong>Task 1: <span id="task1-words">0</span> words</strong><br>
                <strong>Task 2: <span id="task2-words">0</span> words</strong>
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
        const taskOneEditor = document.getElementById('writing-editor-{{ $taskOneQuestion->id }}');
        const taskTwoEditor = document.getElementById('writing-editor-{{ $taskTwoQuestion->id }}');
        const wordCount1 = document.getElementById('word-count-1');
        const wordCount2 = document.getElementById('word-count-2');
        const autosave1 = document.getElementById('autosave-1');
        const autosave2 = document.getElementById('autosave-2');
        const taskButtons = document.querySelectorAll('.task-btn');
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const submitButton = document.getElementById('submit-button');
        
        // Word count function
        function updateWordCount(text, element) {
            const words = text.trim().split(/\s+/).filter(word => word.length > 0);
            const count = text.trim() === '' ? 0 : words.length;
            element.textContent = count;
            return count;
        }
        
        // Autosave function
        function setupAutosave(editor, questionId, statusElement) {
            let typingTimer;
            const doneTypingInterval = 2000; // 2 seconds
            
            editor.addEventListener('input', function() {
                clearTimeout(typingTimer);
                statusElement.textContent = 'Saving...';
                statusElement.style.color = '#f59e0b';
                
                typingTimer = setTimeout(() => {
                    autosave(questionId, editor.value, statusElement);
                }, doneTypingInterval);
            });
        }
        
        function autosave(questionId, content, statusElement) {
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
                statusElement.textContent = 'Saved';
                statusElement.style.color = '#10b981';
                
                setTimeout(() => {
                    statusElement.textContent = '';
                }, 2000);
            })
            .catch(error => {
                statusElement.textContent = 'Error saving';
                statusElement.style.color = '#ef4444';
            });
        }
        
        // Task navigation
        taskButtons.forEach(button => {
            button.addEventListener('click', function() {
                taskButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const taskNumber = this.dataset.task;
                const taskElement = document.getElementById(`task-${taskNumber}`);
                
                if (taskElement) {
                    taskElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Word count event listeners
        taskOneEditor.addEventListener('input', function() {
            updateWordCount(this.value, wordCount1);
        });
        
        taskTwoEditor.addEventListener('input', function() {
            updateWordCount(this.value, wordCount2);
        });
        
        // Initial word count
        updateWordCount(taskOneEditor.value, wordCount1);
        updateWordCount(taskTwoEditor.value, wordCount2);
        
        // Setup autosave
        setupAutosave(taskOneEditor, {{ $taskOneQuestion->id }}, autosave1);
        setupAutosave(taskTwoEditor, {{ $taskTwoQuestion->id }}, autosave2);
        
        // Submit functionality
        submitTestBtn.addEventListener('click', function() {
            const task1Words = updateWordCount(taskOneEditor.value, wordCount1);
            const task2Words = updateWordCount(taskTwoEditor.value, wordCount2);
            
            document.getElementById('task1-words').textContent = task1Words;
            document.getElementById('task2-words').textContent = task2Words;
            
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
        
        // Scroll spy for task navigation
        window.addEventListener('scroll', function() {
            const task1Element = document.getElementById('task-1');
            const task2Element = document.getElementById('task-2');
            const task1Rect = task1Element.getBoundingClientRect();
            const task2Rect = task2Element.getBoundingClientRect();
            
            // Check which task is more visible
            if (task1Rect.top <= 100 && task1Rect.bottom > 100) {
                taskButtons.forEach(btn => btn.classList.remove('active'));
                document.querySelector('[data-task="1"]').classList.add('active');
            } else if (task2Rect.top <= 100) {
                taskButtons.forEach(btn => btn.classList.remove('active'));
                document.querySelector('[data-task="2"]').classList.add('active');
            }
        });
    });
    </script>
    @endpush
</x-test-layout>