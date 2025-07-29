{{-- Minimal IELTS Diagram Question Display --}}
@if($question->question_type === 'plan_map_diagram' && $question->diagram_hotspots)
    <div class="question-item" id="question-{{ $question->id }}">
        <div class="question-content">
            <span class="question-number">{{ $displayNumber }}-{{ $displayNumber + count($question->diagram_hotspots['dropdown_options'] ?? []) - 1 }}</span>
            <div class="question-text">{!! $question->content !!}</div>
        </div>
        
        {{-- Compact Diagram Layout --}}
        <div class="diagram-compact-container">
            {{-- Left: Diagram Image --}}
            <div class="diagram-image-section">
                <img src="{{ $question->getMediaUrlAttribute() }}" 
                     alt="Diagram"
                     class="diagram-img">
            </div>
            
            {{-- Right: Answer Options --}}
            <div class="answer-section">
                <h4 class="options-header">Write the correct letter, A-{{ chr(64 + count($question->diagram_hotspots['dropdown_options'] ?? [])) }}, next to questions {{ $displayNumber }}-{{ $displayNumber + count($question->diagram_hotspots['dropdown_options'] ?? []) - 1 }}.</h4>
                
                {{-- Options List --}}
                <div class="options-list">
                    @foreach($question->diagram_hotspots['dropdown_options'] ?? [] as $index => $option)
                        <div class="option-item">
                            <span class="option-badge">{{ chr(65 + $index) }}</span>
                            <span class="option-text">{{ $option }}</span>
                        </div>
                    @endforeach
                </div>
                
                {{-- Answer Inputs --}}
                <div class="answer-inputs">
                    @php
                        $startNumber = $question->diagram_hotspots['start_number'] ?? $displayNumber;
                    @endphp
                    @for($i = 0; $i < count($question->diagram_hotspots['dropdown_options'] ?? []); $i++)
                        <div class="answer-row">
                            <label class="question-num">{{ $startNumber + $i }}</label>
                            <select name="answers[{{ $question->id }}_{{ $i }}]"
                                    class="answer-select"
                                    data-question-number="{{ $startNumber + $i }}">
                                <option value="">___</option>
                                @foreach($question->diagram_hotspots['dropdown_options'] ?? [] as $optIndex => $option)
                                    <option value="{{ $optIndex }}">{{ chr(65 + $optIndex) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    @php $currentQuestionNumber += count($question->diagram_hotspots['dropdown_options'] ?? []); @endphp
@endif

<style>
/* Compact Diagram Styles */
.diagram-compact-container {
    display: flex;
    gap: 20px;
    margin: 15px 0;
    align-items: flex-start;
    max-height: calc(100vh - 300px);
}

/* Diagram Image Section */
.diagram-image-section {
    flex: 0 0 50%;
    max-width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.diagram-img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
    border-radius: 4px;
}

/* Answer Section */
.answer-section {
    flex: 1;
    min-width: 300px;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    max-height: 100%;
    overflow-y: auto;
}

.options-header {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    margin-bottom: 12px;
    line-height: 1.3;
}

/* Options List */
.options-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 15px;
}

.option-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}

.option-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: #333;
    color: white;
    border-radius: 50%;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
}

.option-text {
    color: #333;
    font-weight: 500;
    line-height: 1.2;
}

/* Answer Inputs */
.answer-inputs {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.answer-row {
    display: flex;
    align-items: center;
    gap: 15px;
}

.question-num {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: white;
    border: 2px solid #333;
    border-radius: 4px;
    font-weight: 700;
    font-size: 15px;
    color: #333;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.answer-select {
    flex: 1;
    padding: 8px 12px;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 36px;
}

.answer-select:hover {
    border-color: #666;
    background: #f8f9fa;
}

.answer-select:focus {
    outline: none;
    border-color: #4A90E2;
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
}

/* Tablet Responsive */
@media (max-width: 1024px) {
    .diagram-compact-container {
        max-height: calc(100vh - 250px);
    }
    
    .diagram-img {
        max-height: 350px;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .diagram-compact-container {
        flex-direction: column;
        max-height: none;
    }
    
    .diagram-image-section {
        flex: 1;
        max-width: 100%;
        order: 2;
        margin-top: 10px;
    }
    
    .diagram-img {
        max-height: 250px;
    }
    
    .answer-section {
        order: 1;
        min-width: 100%;
        max-height: none;
        overflow-y: visible;
    }
    
    .options-list {
        grid-template-columns: 1fr;
        gap: 6px;
    }
    
    .answer-inputs {
        gap: 10px;
    }
    
    .answer-row {
        gap: 12px;
    }
    
    .question-num {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .answer-select {
        padding: 6px 10px;
        font-size: 13px;
        min-height: 32px;
    }
}

/* Very Small Mobile */
@media (max-width: 480px) {
    .diagram-compact-container {
        margin: 10px -10px;
    }
    
    .answer-section {
        padding: 12px;
        border-radius: 0;
    }
    
    .diagram-img {
        max-height: 200px;
    }
    
    .options-header {
        font-size: 12px;
    }
    
    .option-item {
        font-size: 12px;
    }
    
    .option-badge {
        width: 20px;
        height: 20px;
        font-size: 10px;
    }
    
    .question-num {
        width: 30px;
        height: 30px;
        font-size: 13px;
    }
    
    .answer-select {
        min-height: 30px;
    }
}
</style>