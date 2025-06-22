<x-layout>
    <x-slot:title>Test Results - {{ $attempt->testSet->title }}</x-slot>
    
    @push('styles')
    <style>
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
            text-align: center;
        }
        
        .results-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            height: calc(100vh - 200px);
        }
        
        /* Passage Section */
        .passage-viewer {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
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
        
        /* Answer highlighting */
        .answer-location {
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .answer-location.highlight-active {
            background-color: #fbbf24 !important;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3);
            animation: pulseGlow 2s infinite;
        }
        
        .answer-location.q1 { background-color: #fee2e2; color: #991b1b; }
        .answer-location.q2 { background-color: #dcfce7; color: #166534; }
        .answer-location.q3 { background-color: #dbeafe; color: #1e40af; }
        .answer-location.q4 { background-color: #f3e8ff; color: #7c3aed; }
        .answer-location.q5 { background-color: #fef3c7; color: #92400e; }
        
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3); }
            50% { box-shadow: 0 0 0 6px rgba(251, 191, 36, 0.1); }
            100% { box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3); }
        }
        
        /* Questions Section */
        .questions-list {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            overflow-y: auto;
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
            justify-content: between;
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
            color: white;
        }
        
        .result-badge.correct { background: #10b981; }
        .result-badge.incorrect { background: #ef4444; }
        
        .question-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        
        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .locate-btn {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }
        
        .locate-btn:hover {
            background: #d97706;
            transform: translateY(-1px);
        }
        
        .locate-btn.active {
            background: #dc2626;
            border-color: #dc2626;
            animation: buttonPulse 1s ease-out;
        }
        
        .explain-btn {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .explain-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        
        @keyframes buttonPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        /* Explanation Modal */
        .explanation-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .explanation-content {
            background: white;
            border-radius: 12px;
            max-width: 600px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .explanation-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .explanation-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .close-btn:hover {
            color: #374151;
            background: #f3f4f6;
        }
        
        .explanation-body {
            padding: 20px;
        }
        
        .answer-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .answer-box {
            text-align: center;
        }
        
        .answer-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .answer-value {
            font-weight: 600;
            font-size: 16px;
        }
        
        .answer-value.correct { color: #10b981; }
        .answer-value.incorrect { color: #ef4444; }
        
        .explanation-text {
            line-height: 1.6;
            color: #374151;
        }
        
        .passage-reference {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .passage-reference:hover {
            background: #fbbf24;
            color: white;
        }
        
        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .results-grid {
                grid-template-columns: 1fr;
                height: auto;
            }
            
            .passage-viewer {
                position: relative;
                max-height: 400px;
                margin-bottom: 20px;
            }
        }
        
        @media (max-width: 640px) {
            .results-container {
                padding: 10px;
            }
            
            .question-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            .action-btn {
                justify-content: center;
            }
            
            .answer-comparison {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }
    </style>
    @endpush
    
    <div class="results-container">
        <!-- Results Header -->
        <div class="results-header">
            <h1 class="text-3xl font-bold mb-2">Test Results</h1>
            <p class="text-xl opacity-90">{{ $attempt->testSet->title }}</p>
            <div class="mt-4">
                <span class="text-4xl font-bold">{{ $attempt->band_score ?? 'N/A' }}</span>
                <span class="text-lg ml-2">Band Score</span>
            </div>
        </div>
        
        <!-- Main Content Grid -->
        <div class="results-grid">
            <!-- Passage Viewer -->
            <div class="passage-viewer" id="passageViewer">
                <h2 class="passage-title">📖 Reading Passage</h2>
                <div class="passage-content" id="passageContent">
                    @php
                        // Get the reading passage for this test
                        $passage = $attempt->testSet->questions()
                            ->where('question_type', 'passage')
                            ->first();
                    @endphp
                    
                    @if($passage)
                        @php
                            // Process passage content to add answer location markers
                            $content = $passage->passage_text ?? $passage->content;
                            
                            // Add answer location markers based on questions
                            $questionNumber = 1;
                            foreach($attempt->answers as $answer) {
                                if($answer->question->question_type !== 'passage') {
                                    // Add markers for answer locations (you can customize this logic)
                                    $content = $this->addAnswerMarkers($content, $questionNumber, $answer->question);
                                    $questionNumber++;
                                }
                            }
                        @endphp
                        
                        <div id="passageText">
                            {!! $this->processPassageContent($content) !!}
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <p>No passage available for this test.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Questions List -->
            <div class="questions-list">
                <h2 class="text-xl font-semibold mb-4">📝 Questions & Answers</h2>
                
                @php $questionNumber = 1; @endphp
                
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
                                    <span>Question {{ $questionNumber }}</span>
                                </div>
                            </div>
                            
                            <!-- Question Content -->
                            <div class="question-text mb-3">
                                <strong>{{ strip_tags($answer->question->content) }}</strong>
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
                                            See Explanation
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="question-actions">
                                <button class="action-btn locate-btn" 
                                        onclick="locateAnswer({{ $questionNumber }})"
                                        data-question-number="{{ $questionNumber }}">
                                    📍 Locate in Passage
                                </button>
                                <button class="action-btn explain-btn"
                                        onclick="showExplanation({{ $answer->question->id }}, {{ $questionNumber }})">
                                    💡 Explanation
                                </button>
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
    
    <!-- Explanation Modal -->
    <div id="explanationModal" class="explanation-modal">
        <div class="explanation-content">
            <div class="explanation-header">
                <h3 class="explanation-title" id="modalTitle">Question Explanation</h3>
                <button class="close-btn" onclick="closeExplanation()">&times;</button>
            </div>
            <div class="explanation-body" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Store question explanations data
        const questionExplanations = {
            @foreach($attempt->answers as $answer)
                @if($answer->question->question_type !== 'passage' && $answer->question->explanation)
                    {{ $answer->question->id }}: {
                        explanation: `{!! addslashes($answer->question->explanation) !!}`,
                        correctAnswer: `@if($answer->question->options->where('is_correct', true)->first()){{ $answer->question->options->where('is_correct', true)->first()->content }}@endif`,
                        userAnswer: `@if($answer->selectedOption){{ $answer->selectedOption->content }}@elseif($answer->answer){{ $answer->answer }}@else Not Answered @endif`,
                        isCorrect: {{ $answer->selectedOption && $answer->selectedOption->is_correct ? 'true' : 'false' }}
                    },
                @endif
            @endforeach
        };
        
        // Locate answer in passage with smooth animation
        function locateAnswer(questionNumber) {
            // Remove all existing highlights
            document.querySelectorAll('.answer-location').forEach(el => {
                el.classList.remove('highlight-active');
            });
            
            // Remove active state from all locate buttons
            document.querySelectorAll('.locate-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Find and highlight the answer location
            const answerLocation = document.querySelector(`.answer-location.q${questionNumber}`);
            
            if (answerLocation) {
                // Add highlight animation
                answerLocation.classList.add('highlight-active');
                
                // Smooth scroll to the answer location
                answerLocation.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center',
                    inline: 'nearest'
                });
                
                // Mark locate button as active
                const locateBtn = document.querySelector(`.locate-btn[data-question-number="${questionNumber}"]`);
                if (locateBtn) {
                    locateBtn.classList.add('active');
                    
                    // Reset button after animation
                    setTimeout(() => {
                        locateBtn.classList.remove('active');
                    }, 2000);
                }
                
                // Remove highlight after 3 seconds
                setTimeout(() => {
                    answerLocation.classList.remove('highlight-active');
                }, 3000);
            } else {
                // Show message if answer location not found
                showTooltip('Answer location not marked in this passage', 'warning');
            }
        }
        
        // Show explanation modal
        function showExplanation(questionId, questionNumber) {
            const modal = document.getElementById('explanationModal');
            const title = document.getElementById('modalTitle');
            const body = document.getElementById('modalBody');
            
            const data = questionExplanations[questionId];
            
            if (data) {
                title.textContent = `Question ${questionNumber} - Explanation`;
                
                body.innerHTML = `
                    <div class="answer-comparison">
                        <div class="answer-box">
                            <div class="answer-label">Your Answer</div>
                            <div class="answer-value ${data.isCorrect ? 'correct' : 'incorrect'}">${data.userAnswer}</div>
                        </div>
                        <div class="answer-box">
                            <div class="answer-label">Correct Answer</div>
                            <div class="answer-value correct">${data.correctAnswer}</div>
                        </div>
                    </div>
                    
                    <div class="explanation-text">
                        ${processExplanationText(data.explanation, questionNumber)}
                    </div>
                `;
            } else {
                title.textContent = `Question ${questionNumber} - No Explanation`;
                body.innerHTML = `
                    <div class="text-center text-gray-500 py-8">
                        <p>No explanation available for this question.</p>
                    </div>
                `;
            }
            
            // Show modal
            modal.style.display = 'flex';
            
            // Close on backdrop click
            modal.onclick = function(e) {
                if (e.target === modal) {
                    closeExplanation();
                }
            };
        }
        
        // Process explanation text to add interactive passage references
        function processExplanationText(explanation, questionNumber) {
            // Convert passage references to clickable elements
            let processed = explanation;
            
            // Replace "paragraph X" with clickable references
            processed = processed.replace(/paragraph (\d+)/gi, function(match, num) {
                return `<span class="passage-reference" onclick="highlightParagraph(${num})">paragraph ${num}</span>`;
            });
            
            // Replace line references
            processed = processed.replace(/line (\d+)/gi, function(match, num) {
                return `<span class="passage-reference" onclick="highlightLine(${num})">line ${num}</span>`;
            });
            
            // Add question-specific reference
            processed += `<br><br><div style="margin-top: 16px; padding: 12px; background: #f3f4f6; border-radius: 6px; font-size: 14px;">
                <strong>💡 Quick Tip:</strong> <span class="passage-reference" onclick="locateAnswer(${questionNumber}); closeExplanation();">Click here to see the answer location in the passage</span>
            </div>`;
            
            return processed;
        }
        
        // Highlight specific paragraph
        function highlightParagraph(paragraphNumber) {
            const paragraphs = document.querySelectorAll('.passage-content p');
            
            if (paragraphs[paragraphNumber - 1]) {
                // Remove existing highlights
                paragraphs.forEach(p => p.classList.remove('highlight-active'));
                
                // Add highlight to target paragraph
                const targetParagraph = paragraphs[paragraphNumber - 1];
                targetParagraph.classList.add('highlight-active');
                
                // Scroll to paragraph
                targetParagraph.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Remove highlight after 3 seconds
                setTimeout(() => {
                    targetParagraph.classList.remove('highlight-active');
                }, 3000);
                
                // Close modal
                closeExplanation();
            }
        }
        
        // Close explanation modal
        function closeExplanation() {
            const modal = document.getElementById('explanationModal');
            modal.style.display = 'none';
        }
        
        // Show tooltip message
        function showTooltip(message, type = 'info') {
            const tooltip = document.createElement('div');
            tooltip.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white text-sm font-medium z-50 ${
                type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'
            }`;
            tooltip.style.animation = 'slideIn 0.3s ease-out';
            tooltip.textContent = message;
            
            document.body.appendChild(tooltip);
            
            setTimeout(() => {
                tooltip.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => tooltip.remove(), 300);
            }, 3000);
        }
        
        // Keyboard support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeExplanation();
            }
        });
        
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Results page loaded with interactive explanations');
        });
    </script>
    @endpush
</x-layout>