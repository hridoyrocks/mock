<x-layout>
    <x-slot:title>Test Results - {{ $attempt->testSet->title }}</x-slot>
    
    @push('styles')
    <style>
        /* Results Page Styles */
        .results-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .results-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .results-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        /* Passage Section */
        .passage-viewer {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 30px;
            height: fit-content;
            max-height: 80vh;
            overflow-y: auto;
            position: sticky;
            top: 20px;
        }
        
        .passage-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a202c;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .passage-content {
            line-height: 1.8;
            color: #4a5568;
            font-size: 16px;
        }
        
        .passage-content p {
            margin-bottom: 16px;
            text-align: justify;
        }
        
        /* Answer Highlighting */
        .answer-highlight {
            background-color: #fef3c7;
            padding: 2px 4px;
            border-radius: 3px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .answer-highlight.active {
            background-color: #fde68a;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3);
            animation: pulse 2s infinite;
        }
        
        .answer-highlight.correct {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .answer-highlight.incorrect {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3); }
            50% { box-shadow: 0 0 0 6px rgba(251, 191, 36, 0.1); }
            100% { box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3); }
        }
        
        /* Questions Section */
        .questions-list {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        
        .question-item {
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 16px;
            transition: all 0.2s ease;
        }
        
        .question-item:hover {
            border-color: #cbd5e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .question-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .question-number {
            font-weight: 600;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .result-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }
        
        .result-badge.correct {
            background: #10b981;
            color: white;
        }
        
        .result-badge.incorrect {
            background: #ef4444;
            color: white;
        }
        
        .question-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .locate-btn {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #374151;
        }
        
        .locate-btn:hover {
            background: #e5e7eb;
        }
        
        .locate-btn.active {
            background: #fbbf24;
            border-color: #f59e0b;
            color: #78350f;
        }
        
        .explain-btn {
            background: #dbeafe;
            border-color: #93c5fd;
            color: #1e40af;
        }
        
        .explain-btn:hover {
            background: #bfdbfe;
        }
        
        /* Explanation Panel */
        .explanation-panel {
            margin-top: 16px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
            display: none;
            animation: slideDown 0.3s ease;
        }
        
        .explanation-panel.show {
            display: block;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .explanation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .explanation-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
        }
        
        .close-explanation {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
        }
        
        .explanation-content {
            color: #4b5563;
            line-height: 1.6;
        }
        
        .answer-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
            padding: 12px;
            background: white;
            border-radius: 6px;
        }
        
        .answer-box {
            text-align: center;
        }
        
        .answer-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        
        .answer-value {
            font-weight: 600;
            font-size: 18px;
        }
        
        .answer-value.correct {
            color: #10b981;
        }
        
        .answer-value.incorrect {
            color: #ef4444;
        }
        
        /* Tips Section */
        .tips-section {
            margin-top: 16px;
            padding: 12px;
            background: #fef3c7;
            border-radius: 6px;
            font-size: 14px;
            color: #78350f;
        }
        
        .tips-icon {
            display: inline-block;
            margin-right: 8px;
        }
        
        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #1a202c;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }
        
        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .results-grid {
                grid-template-columns: 1fr;
            }
            
            .passage-viewer {
                position: relative;
                max-height: 400px;
                margin-bottom: 20px;
            }
            
            .summary-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 640px) {
            .results-container {
                padding: 10px;
            }
            
            .results-header {
                padding: 20px;
            }
            
            .question-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .action-btn {
                width: 100%;
            }
            
            .answer-comparison {
                grid-template-columns: 1fr;
            }
        }
        
        /* Loading State */
        .loading-shimmer {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    @endpush
    
    <div class="results-container">
        <!-- Results Header -->
        <div class="results-header">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Test Results</h1>
                    <p class="text-xl opacity-90">{{ $attempt->testSet->title }}</p>
                    <p class="text-sm opacity-75 mt-2">
                        Completed on {{ $attempt->end_time->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $attempt->band_score ?? 'N/A' }}</div>
                    <div class="text-sm opacity-75">Band Score</div>
                </div>
            </div>
        </div>
        
        <!-- Summary Statistics -->
        <div class="summary-stats">
            <div class="stat-card">
                <div class="stat-value text-green-600">{{ $correctAnswers ?? 0 }}</div>
                <div class="stat-label">Correct Answers</div>
            </div>
            <div class="stat-card">
                <div class="stat-value text-red-600">{{ $totalQuestions - ($correctAnswers ?? 0) }}</div>
                <div class="stat-label">Incorrect Answers</div>
            </div>
            <div class="stat-card">
                <div class="stat-value text-blue-600">{{ round($accuracy ?? 0, 1) }}%</div>
                <div class="stat-label">Accuracy</div>
            </div>
            <div class="stat-card">
                <div class="stat-value text-purple-600">{{ $totalQuestions }}</div>
                <div class="stat-label">Total Questions</div>
            </div>
        </div>
        
        <!-- Main Content Grid -->
        <div class="results-grid">
            <!-- Passage Viewer -->
            <div class="passage-viewer" id="passageViewer">
                <h2 class="passage-title">Reading Passage</h2>
                <div class="passage-content" id="passageContent">
                    <!-- Passage will be loaded here -->
                    <div class="loading-shimmer" style="height: 200px; border-radius: 8px;"></div>
                </div>
            </div>
            
            <!-- Questions List -->
            <div class="questions-list">
                <h2 class="text-xl font-semibold mb-4">Questions & Answers</h2>
                
                @php
                    $questionNumber = 1;
                @endphp
                
                @foreach($attempt->answers as $answer)
                    @if($answer->question->question_type !== 'passage')
                        <div class="question-item" data-question-id="{{ $answer->question->id }}">
                            <div class="question-header">
                                <div class="question-number">
                                    <span class="result-badge {{ $answer->selectedOption && $answer->selectedOption->is_correct ? 'correct' : 'incorrect' }}">
                                        @if($answer->selectedOption && $answer->selectedOption->is_correct)
                                            ✓
                                        @else
                                            ✗
                                        @endif
                                    </span>
                                    Question {{ $questionNumber }}
                                </div>
                                
                                <div class="question-actions">
                                    <button class="action-btn locate-btn" 
                                            onclick="locateAnswer({{ $answer->question->id }}, '{{ $answer->question->passage_reference }}')"
                                            data-question-id="{{ $answer->question->id }}">
                                        📍 Locate
                                    </button>
                                    <button class="action-btn explain-btn"
                                            onclick="toggleExplanation({{ $answer->question->id }})">
                                        💡 Explain
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Question Content -->
                            <div class="question-text mb-3">
                                {{ strip_tags($answer->question->content) }}
                            </div>
                            
                            <!-- Explanation Panel -->
                            <div class="explanation-panel" id="explanation-{{ $answer->question->id }}">
                                <div class="explanation-header">
                                    <h3 class="explanation-title">Explanation</h3>
                                    <button class="close-explanation" onclick="toggleExplanation({{ $answer->question->id }})">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Answer Comparison -->
                                <div class="answer-comparison">
                                    <div class="answer-box">
                                        <div class="answer-label">Your Answer</div>
                                        <div class="answer-value {{ $answer->selectedOption && $answer->selectedOption->is_correct ? 'correct' : 'incorrect' }}">
                                            @if($answer->selectedOption)
                                                {{ $answer->selectedOption->content }}
                                            @elseif($answer->answer)
                                                {{ $answer->answer }}
                                            @else
                                                Not Answered
                                            @endif
                                        </div>
                                    </div>
                                    <div class="answer-box">
                                        <div class="answer-label">Correct Answer</div>
                                        <div class="answer-value correct">
                                            @php
                                                $correctOption = $answer->question->options->where('is_correct', true)->first();
                                            @endphp
                                            @if($correctOption)
                                                {{ $correctOption->content }}
                                            @else
                                                {{ $answer->question->correct_answer ?? 'N/A' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Main Explanation -->
                                <div class="explanation-content">
                                    @if($answer->question->explanation)
                                        {!! $answer->question->explanation !!}
                                    @else
                                        <p class="text-gray-500 italic">No explanation available for this question.</p>
                                    @endif
                                </div>
                                
                                <!-- Tips if available -->
                                @if($answer->question->tips)
                                    <div class="tips-section">
                                        <span class="tips-icon">💡</span>
                                        <strong>Tip:</strong> {{ $answer->question->tips }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @php $questionNumber++; @endphp
                    @endif
                @endforeach
            </div>
        </div>
        
        <!-- Back to Dashboard -->
        <div class="mt-8 text-center">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Load passage content
        document.addEventListener('DOMContentLoaded', function() {
            loadPassageContent();
        });
        
        // Load the reading passage
        function loadPassageContent() {
            // Get the passage from the test set
            @php
                $passage = $attempt->testSet->questions()
                    ->where('question_type', 'passage')
                    ->where('part_number', 1) // Assuming Part 1 for now
                    ->first();
            @endphp
            
            @if($passage)
                const passageContent = `{!! addslashes($passage->passage_text ?? $passage->content) !!}`;
                const passageContainer = document.getElementById('passageContent');
                
                // Process passage with answer markers
                let processedContent = passageContent;
                
                // Convert line breaks to paragraphs
                const paragraphs = processedContent.split('\n\n');
                let htmlContent = '';
                
                paragraphs.forEach(para => {
                    if (para.trim()) {
                        // Replace answer markers with highlighted spans
                        para = para.replace(/\[Q(\d+)\]/g, '<span class="answer-highlight" data-question="$1">[Answer $1]</span>');
                        htmlContent += `<p>${para}</p>`;
                    }
                });
                
                passageContainer.innerHTML = htmlContent;
            @else
                document.getElementById('passageContent').innerHTML = `
                    <div class="text-center text-gray-500 py-8">
                        <p>No passage available for this test.</p>
                    </div>
                `;
            @endif
        }
        
        // Toggle explanation panel
        function toggleExplanation(questionId) {
            const panel = document.getElementById(`explanation-${questionId}`);
            if (panel) {
                panel.classList.toggle('show');
                
                // Close other explanations
                document.querySelectorAll('.explanation-panel').forEach(p => {
                    if (p.id !== `explanation-${questionId}`) {
                        p.classList.remove('show');
                    }
                });
            }
        }
        
        // Locate answer in passage
        function locateAnswer(questionId, passageReference) {
            // Remove all active highlights first
            document.querySelectorAll('.answer-highlight').forEach(el => {
                el.classList.remove('active', 'correct', 'incorrect');
            });
            
            // Remove active state from all locate buttons
            document.querySelectorAll('.locate-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Find and highlight the answer location
            const highlights = document.querySelectorAll(`.answer-highlight[data-question="${questionId}"]`);
            
            if (highlights.length > 0) {
                highlights.forEach(highlight => {
                    highlight.classList.add('active');
                    
                    // Check if answer is correct or incorrect
                    const questionItem = document.querySelector(`[data-question-id="${questionId}"]`);
                    const isCorrect = questionItem.querySelector('.result-badge.correct');
                    
                    if (isCorrect) {
                        highlight.classList.add('correct');
                    } else {
                        highlight.classList.add('incorrect');
                    }
                });
                
                // Scroll to first highlight
                highlights[0].scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Mark locate button as active
                const locateBtn = document.querySelector(`.locate-btn[data-question-id="${questionId}"]`);
                if (locateBtn) {
                    locateBtn.classList.add('active');
                }
            } else if (passageReference) {
                // Try to find by passage reference text
                const passageContent = document.getElementById('passageContent');
                const text = passageContent.textContent;
                
                // Simple text search and highlight
                // This is a basic implementation - you might want to enhance this
                console.log(`Looking for reference: ${passageReference}`);
            }
        }
        
        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // ESC to close all explanations
            if (e.key === 'Escape') {
                document.querySelectorAll('.explanation-panel.show').forEach(panel => {
                    panel.classList.remove('show');
                });
            }
        });
        
        // Auto-save user preferences
        function saveUserPreferences() {
            const preferences = {
                showExplanations: document.querySelectorAll('.explanation-panel.show').length > 0
            };
            localStorage.setItem('resultPreferences', JSON.stringify(preferences));
        }
        
        // Restore user preferences
        function restoreUserPreferences() {
            try {
                const saved = localStorage.getItem('resultPreferences');
                if (saved) {
                    const preferences = JSON.parse(saved);
                    // Apply preferences if needed
                }
            } catch (e) {
                console.error('Error restoring preferences:', e);
            }
        }
        
        // Initialize
        restoreUserPreferences();
    </script>
    @endpush
</x-layout>