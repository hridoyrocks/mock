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
        
        /* Marker highlighting */
        .marker-text {
            padding: 3px 6px;
            border-radius: 4px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .marker-text[data-marker="Q1"] { background-color: #fee2e2; }
        .marker-text[data-marker="Q2"] { background-color: #dcfce7; }
        .marker-text[data-marker="Q3"] { background-color: #dbeafe; }
        .marker-text[data-marker="Q4"] { background-color: #f3e8ff; }
        .marker-text[data-marker="Q5"] { background-color: #fef3c7; }
        .marker-text[data-marker="Q6"] { background-color: #e0e7ff; }
        .marker-text[data-marker="Q7"] { background-color: #fce7f3; }
        .marker-text[data-marker="Q8"] { background-color: #d1fae5; }
        .marker-text[data-marker="Q9"] { background-color: #fed7aa; }
        .marker-text[data-marker="Q10"] { background-color: #bae6fd; }
        
        .marker-text.highlight-active {
            animation: pulseGlow 2s infinite;
            transform: scale(1.05);
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.4);
            z-index: 10;
            background-color: #fbbf24 !important;
        }
        
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(251, 191, 36, 0.1); }
            100% { box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.4); }
        }
        
        /* Marker tooltip */
        .marker-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            margin-bottom: 8px;
        }
        
        .marker-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #1f2937;
        }
        
        .marker-text:hover .marker-tooltip {
            opacity: 1;
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
            color: white;
        }
        
        .result-badge.correct { background: #10b981; }
        .result-badge.incorrect { background: #ef4444; }
        
        .marker-indicator {
            background: #e0e7ff;
            color: #3730a3;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
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
        
        .locate-btn:disabled {
            background: #e5e7eb;
            border-color: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
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
            max-width: 800px;
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
        
        .explanation-text p {
            margin-bottom: 12px;
        }
        
        .explanation-text ul,
        .explanation-text ol {
            margin-bottom: 12px;
            padding-left: 24px;
        }
        
        .explanation-text li {
            margin-bottom: 6px;
        }
        
        /* Marker links in explanation */
        .marker-link {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .marker-link:hover {
            background-color: #fbbf24;
            color: white;
            transform: translateY(-1px);
        }
        
        /* Split view mode */
        .split-view-mode {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            height: 100%;
        }
        
        .split-view-passage {
            overflow-y: auto;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }
        
        .split-view-explanation {
            overflow-y: auto;
            padding: 20px;
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
            
            .explanation-content {
                width: 95%;
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
            @if(isset($accuracy))
                <div class="mt-2 opacity-80">
                    <span class="text-lg">Accuracy: {{ number_format($accuracy, 1) }}%</span>
                    <span class="text-sm ml-2">({{ $correctAnswers }}/{{ $totalQuestions }})</span>
                </div>
            @endif
        </div>
        
        <!-- Main Content Grid -->
        <div class="results-grid">
            <!-- Passage Viewer -->
            <div class="passage-viewer" id="passageViewer">
                <h2 class="passage-title">📖 Reading Passage</h2>
                <div class="passage-content" id="passageContent">
                    @if($passages && $passages->count() > 0)
                        @foreach($passages as $passage)
                            <div class="passage-wrapper" data-part="{{ $passage->part_number }}">
                                @if($passage->instructions)
                                    <h3 class="text-lg font-semibold mb-3">{{ $passage->instructions }}</h3>
                                @endif
                                
                                <div id="passageText-{{ $passage->id }}">
                                    {!! $passage->processed_content !!}
                                </div>
                            </div>
                        @endforeach
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
                
                @foreach($questionsWithMarkers ?? $attempt->answers as $answer)
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
                                    @if($answer->question->marker_id)
                                        <span class="marker-indicator">{{ $answer->question->marker_id }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Question Content -->
                            <div class="question-text mb-3">
                                <strong>{!! strip_tags($answer->question->content) !!}</strong>
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
                                @if($answer->question->marker_id)
                                    <button class="action-btn locate-btn" 
                                            onclick="locateAnswer('{{ $answer->question->marker_id }}')"
                                            data-marker="{{ $answer->question->marker_id }}">
                                        📍 Locate in Passage
                                    </button>
                                @else
                                    <button class="action-btn locate-btn" disabled title="No marker available for this question">
                                        📍 No Location Marked
                                    </button>
                                @endif
                                
                                @if($answer->question->explanation)
                                    <button class="action-btn explain-btn"
                                            onclick="showExplanation({{ $answer->question->id }}, {{ $questionNumber }})">
                                        💡 Explanation
                                    </button>
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
        // Store question data
        const questionData = {
            @foreach($questionsWithMarkers ?? $attempt->answers as $answer)
                @if($answer->question->question_type !== 'passage')
                    {{ $answer->question->id }}: {
                        explanation: `{!! addslashes($answer->question->processed_explanation ?? $answer->question->explanation ?? '') !!}`,
                        correctAnswer: `@if($answer->question->options->where('is_correct', true)->first()){{ $answer->question->options->where('is_correct', true)->first()->content }}@endif`,
                        userAnswer: `@if($answer->selectedOption){{ $answer->selectedOption->content }}@elseif($answer->answer){{ $answer->answer }}@else Not Answered @endif`,
                        isCorrect: {{ $answer->selectedOption && $answer->selectedOption->is_correct ? 'true' : 'false' }},
                        markerId: `{{ $answer->question->marker_id ?? '' }}`,
                        markerText: `{{ addslashes($answer->question->marker_text ?? '') }}`,
                        passageReference: `{{ addslashes($answer->question->passage_reference ?? '') }}`,
                        tips: `{{ addslashes($answer->question->tips ?? '') }}`,
                        commonMistakes: `{{ addslashes($answer->question->common_mistakes ?? '') }}`
                    },
                @endif
            @endforeach
        };
        
        // Active marker tracking
        let activeMarker = null;
        
        // Locate answer in passage with enhanced animation
        function locateAnswer(markerId) {
            if (!markerId) return;
            
            // Remove all existing highlights
            document.querySelectorAll('.marker-text').forEach(el => {
                el.classList.remove('highlight-active');
            });
            
            // Remove active state from all locate buttons
            document.querySelectorAll('.locate-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Find the marker in passage
            const markerElement = document.querySelector(`[data-marker="${markerId}"]`);
            
            if (markerElement) {
                // Add highlight animation
                markerElement.classList.add('highlight-active');
                activeMarker = markerId;
                
                // Smooth scroll to the marker
                markerElement.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center',
                    inline: 'nearest'
                });
                
                // Mark locate button as active
                const locateBtn = document.querySelector(`.locate-btn[data-marker="${markerId}"]`);
                if (locateBtn) {
                    locateBtn.classList.add('active');
                    
                    // Reset button after animation
                    setTimeout(() => {
                        locateBtn.classList.remove('active');
                    }, 2000);
                }
                
                // Remove highlight after 5 seconds
                setTimeout(() => {
                    markerElement.classList.remove('highlight-active');
                    activeMarker = null;
                }, 5000);
            } else {
                // Show message if marker not found
                showTooltip('Answer location not found in passage', 'warning');
            }
        }
        
        // Highlight marker from explanation
        function highlightMarker(markerId) {
            // Close modal first for better UX
            closeExplanation();
            
            // Small delay to allow modal to close
            setTimeout(() => {
                locateAnswer(markerId);
            }, 300);
        }
        
        // Show explanation modal with enhanced content
        function showExplanation(questionId, questionNumber) {
            const modal = document.getElementById('explanationModal');
            const title = document.getElementById('modalTitle');
            const body = document.getElementById('modalBody');
            
            const data = questionData[questionId];
            
            if (!data) {
                showTooltip('No explanation available', 'info');
                return;
            }
            
            title.innerHTML = `Question ${questionNumber} - Explanation`;
            
            let explanationContent = `
                <div class="answer-comparison">
                    <div class="answer-box">
                        <div class="answer-label">Your Answer</div>
                        <div class="answer-value ${data.isCorrect ? 'correct' : 'incorrect'}">${data.userAnswer}</div>
                    </div>
                    <div class="answer-box">
                        <div class="answer-label">Correct Answer</div>
                        <div class="answer-value correct">${data.correctAnswer || 'See explanation below'}</div>
                    </div>
                </div>
            `;
            
            // Add marker reference if exists
            if (data.markerId && data.markerText) {
                explanationContent += `
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm font-semibold text-blue-800 mb-1">📍 Answer Location:</p>
                        <p class="text-sm text-blue-700">
                            Look for <span class="marker-link" onclick="highlightMarker('${data.markerId}')">${data.markerId}</span> in the passage:
                            <em>"${data.markerText.substring(0, 100)}${data.markerText.length > 100 ? '...' : ''}"</em>
                        </p>
                    </div>
                `;
            }
            
            // Add main explanation
            if (data.explanation) {
                explanationContent += `
                    <div class="explanation-text mb-4">
                        <h4 class="font-semibold mb-2">Explanation:</h4>
                        ${data.explanation}
                    </div>
                `;
            }
            
            // Add passage reference
            if (data.passageReference) {
                explanationContent += `
                    <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-1">📖 Passage Reference:</p>
                        <p class="text-sm text-gray-600">${data.passageReference}</p>
                    </div>
                `;
            }
            
            // Add common mistakes
            if (data.commonMistakes) {
                explanationContent += `
                    <div class="mb-3 p-3 bg-red-50 rounded-lg">
                        <p class="text-sm font-semibold text-red-800 mb-1">⚠️ Common Mistakes:</p>
                        <p class="text-sm text-red-700">${data.commonMistakes}</p>
                    </div>
                `;
            }
            
            // Add tips
            if (data.tips) {
                explanationContent += `
                    <div class="p-3 bg-green-50 rounded-lg">
                        <p class="text-sm font-semibold text-green-800 mb-1">💡 Tips:</p>
                        <p class="text-sm text-green-700">${data.tips}</p>
                    </div>
                `;
            }
            
            body.innerHTML = explanationContent;
            
            // Show modal
            modal.style.display = 'flex';
            
            // Close on backdrop click
            modal.onclick = function(e) {
                if (e.target === modal) {
                    closeExplanation();
                }
            };
        }
        
        // Close explanation modal
        function closeExplanation() {
            const modal = document.getElementById('explanationModal');
            modal.style.display = 'none';
        }
        
        // Show tooltip message
        function showTooltip(message, type = 'info') {
            const colors = {
                info: 'bg-blue-600',
                success: 'bg-green-600',
                warning: 'bg-yellow-600',
                error: 'bg-red-600'
            };
            
            const tooltip = document.createElement('div');
            tooltip.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white text-sm font-medium z-50 ${colors[type]}`;
            tooltip.style.animation = 'slideIn 0.3s ease-out';
            tooltip.textContent = message;
            
            document.body.appendChild(tooltip);
            
            setTimeout(() => {
                tooltip.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => tooltip.remove(), 300);
            }, 3000);
        }
        
        // Add marker hover effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover tooltips to markers
            document.querySelectorAll('.marker-text').forEach(marker => {
                const markerId = marker.dataset.marker;
                
                // Create tooltip
                const tooltip = document.createElement('div');
                tooltip.className = 'marker-tooltip';
                tooltip.textContent = `Click to see ${markerId} explanation`;
                marker.appendChild(tooltip);
                
                // Click handler
                marker.addEventListener('click', function() {
                    // Find question with this marker
                    for (let [questionId, data] of Object.entries(questionData)) {
                        if (data.markerId === markerId) {
                            // Find question number
                            const questionItems = document.querySelectorAll('.question-item');
                            let questionNumber = 1;
                            
                            questionItems.forEach((item, index) => {
                                if (item.dataset.questionId == questionId) {
                                    questionNumber = index + 1;
                                }
                            });
                            
                            showExplanation(questionId, questionNumber);
                            break;
                        }
                    }
                });
            });
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeExplanation();
                }
            });
        });
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes slideOut {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(20px);
                }
            }
            
            /* Smooth transitions */
            .marker-text {
                transition: all 0.3s ease;
            }
            
            .marker-text:hover {
                transform: scale(1.02);
            }
        `;
        document.head.appendChild(style);
    </script>
    @endpush
</x-layout>