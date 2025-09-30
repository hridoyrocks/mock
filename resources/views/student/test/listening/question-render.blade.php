{{-- Listening Question Render Partial --}}
@php
    $questionType = $question->question_type;
    $isMultipleChoice = false;
    
    // Check if this is a multiple_choice question
    if ($questionType === 'multiple_choice') {
        $isMultipleChoice = true;
    }
@endphp

@switch($questionType)
    @case('fill_blanks')
        {{-- Fill in the Blanks Question --}}
        <div class="question-item" id="question-{{ $question->id }}">
            <div class="question-content">
                @php
                    // Count blanks in content
                    preg_match_all('/\[____(\d+)____\]/', $question->content, $matches);
                    $blankCount = count($matches[0]);
                    
                    // Process content to replace blanks with inputs
                    $processedContent = $question->content;
                    $blankData = $question->section_specific_data['blank_answers'] ?? [];
                    $blankNumber = $displayNumber;
                    
                    for ($i = 1; $i <= $blankCount; $i++) {
                        $inputHtml = '<input type="text" 
                                            name="answers[' . $question->id . '][blank_' . $i . ']" 
                                            class="text-input inline-blank" 
                                            placeholder="' . $blankNumber . '"
                                            data-question-number="' . $blankNumber . '"
                                            style="width: 150px; display: inline-block; margin: 0 4px;">';
                        
                        $processedContent = str_replace('[____' . $i . '____]', $inputHtml, $processedContent);
                        $blankNumber++;
                    }
                @endphp
                
                {{-- No question number displayed before content --}}
                
                <div class="question-text">{!! $processedContent !!}</div>
            </div>
        </div>
        @break
        
    @case('single_choice')
        {{-- Single Choice Question --}}
        <div class="question-item single-choice-question" id="question-{{ $question->id }}">
            <div class="question-content">
                <span class="question-number">{{ $displayNumber }}</span>
                <div class="question-text">{!! $question->content !!}</div>
            </div>
            
            @if($question->options && $question->options->count() > 0)
                <div class="single-choice-options">
                    @foreach ($question->options as $optionIndex => $option)
                        <div class="single-choice-option-item">
                            <input type="radio" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $option->id }}" 
                                   class="single-choice-radio"
                                   id="option-{{ $question->id }}-{{ $option->id }}"
                                   data-question-number="{{ $displayNumber }}">
                            <label for="option-{{ $question->id }}-{{ $option->id }}" class="single-choice-label">
                                <span class="option-letter">{{ chr(65 + $optionIndex) }}</span>
                                <span class="option-text">{{ $option->content }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- No options found, show text input as fallback --}}
                <div class="answer-input">
                    <input type="text" 
                           name="answers[{{ $question->id }}]" 
                           class="text-input" 
                           placeholder="Type your answer"
                           maxlength="50"
                           data-question-number="{{ $displayNumber }}">
                </div>
            @endif
        </div>
        @break
        
    @case('multiple_choice')
        {{-- Single/Multiple Choice Question --}}
        <div class="question-item" id="question-{{ $question->id }}">
            <div class="question-content">
                @php
                    // Check if multiple correct answers
                    $correctCount = $question->options->where('is_correct', true)->count();
                    $hasMultipleCorrect = $correctCount > 1;
                @endphp
                
                @if($hasMultipleCorrect)
                    <span class="question-number">{{ $displayNumber }}-{{ $displayNumber + $correctCount - 1 }}</span>
                @else
                    <span class="question-number">{{ $displayNumber }}</span>
                @endif
                
                <div class="question-text">{!! $question->content !!}</div>
            </div>
            
            @if($question->options && $question->options->count() > 0)
                <div class="options-list">
                    @foreach ($question->options as $optionIndex => $option)
                        <label class="option-item">
                            @if($isMultipleChoice)
                                <input type="checkbox" 
                                       name="answers[{{ $question->id }}][]" 
                                       value="{{ $option->id }}" 
                                       class="option-checkbox"
                                       id="option-{{ $option->id }}"
                                       data-question-number="{{ $displayNumber }}">
                            @else
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $option->id }}" 
                                       class="option-radio"
                                       id="option-{{ $option->id }}"
                                       data-question-number="{{ $displayNumber }}">
                            @endif
                            <label for="option-{{ $option->id }}" class="option-label">
                                <strong>{{ chr(65 + $optionIndex) }}.</strong> {{ $option->content }}
                            </label>
                        </label>
                    @endforeach
                </div>
            @else
                {{-- No options found, show text input as fallback --}}
                <div class="answer-input">
                    <input type="text" 
                           name="answers[{{ $question->id }}]" 
                           class="text-input" 
                           placeholder="Type your answer"
                           maxlength="50"
                           data-question-number="{{ $displayNumber }}">
                </div>
            @endif
        </div>
        @break
        
    @case('dropdown_selection')
        {{-- Dropdown Selection Question --}}
        <div class="question-item" id="question-{{ $question->id }}">
            <div class="question-content">
                @php
                    // Count dropdowns in content
                    preg_match_all('/\[DROPDOWN_(\d+)\]/', $question->content, $matches);
                    $dropdownCount = count($matches[0]);
                    
                    // Process content to replace dropdowns with select elements
                    $processedContent = $question->content;
                    $dropdownData = $question->section_specific_data ?? [];
                    $dropdownNumber = $displayNumber;
                    
                    for ($i = 1; $i <= $dropdownCount; $i++) {
                        $options = isset($dropdownData['dropdown_options'][$i]) 
                                  ? explode(',', $dropdownData['dropdown_options'][$i]) 
                                  : [];
                        
                        $selectHtml = '<select name="answers[' . $question->id . '][dropdown_' . $i . ']" 
                                              class="select-input inline-dropdown" 
                                              data-question-number="' . $dropdownNumber . '"
                                              style="display: inline-block; margin: 0 4px;">
                                        <option value="">Select...</option>';
                        
                        foreach ($options as $option) {
                            $selectHtml .= '<option value="' . trim($option) . '">' . trim($option) . '</option>';
                        }
                        
                        $selectHtml .= '</select>';
                        
                        $processedContent = str_replace('[DROPDOWN_' . $i . ']', $selectHtml, $processedContent);
                        $dropdownNumber++;
                    }
                @endphp
                
                @if($dropdownCount > 1)
                    <span class="question-number">{{ $displayNumber }}-{{ $displayNumber + $dropdownCount - 1 }}</span>
                @else
                    <span class="question-number">{{ $displayNumber }}</span>
                @endif
                
                <div class="question-text">{!! $processedContent !!}</div>
            </div>
        </div>
        @break
        
    @case('drag_drop')
        {{-- Drag and Drop Question --}}
        <div class="question-item drag-drop-question" id="question-{{ $question->id }}" style="background: none; border: none; box-shadow: none; padding: 0; margin-bottom: 20px;">
            @php
                $sectionData = $question->section_specific_data ?? [];
                
                // Parse drag zones from content
                $content = $question->content;
                preg_match_all('/\[DRAG_(\d+)\]/', $content, $matches);
                $dragZoneNumbers = $matches[1] ?? [];
                
                // Get drag zone answers (now without labels)
                $dragZones = [];
                foreach ($dragZoneNumbers as $num) {
                    if (isset($sectionData['drag_zones'][$num])) {
                        $dragZones[] = [
                            'number' => $num,
                            'answer' => $sectionData['drag_zones'][$num]['answer'] ?? ''
                        ];
                    } else {
                        $dragZones[] = [
                            'number' => $num,
                            'answer' => ''
                        ];
                    }
                }
                
                $options = $sectionData['draggable_options'] ?? [];
                $allowReuse = $sectionData['allow_reuse'] ?? true;
                $dropZoneCount = count($dragZones);
                
                // Process content to replace [DRAG_X] with drop boxes
                $processedContent = $content;
                $questionNumber = $displayNumber;
                
                foreach ($dragZones as $index => $zone) {
                    $num = $zone['number'];
                    $zoneNumber = $displayNumber + $index;
                    
                    // Use 'num' as the actual zone identifier for backend matching
                    $dropBoxHtml = '<span class="drop-box" 
                                         data-question-id="' . $question->id . '"
                                         data-zone-number="' . $num . '"
                                         data-zone-index="' . $index . '"
                                         data-question-number="' . $zoneNumber . '"
                                         data-allow-reuse="' . ($allowReuse ? '1' : '0') . '"
                                         style="display: inline-block; min-width: 100px; max-width: 200px; height: 36px; border: 2px dashed #9ca3af; border-radius: 4px; line-height: 36px; text-align: center; background: white; font-size: 14px; padding: 0 8px; cursor: pointer; margin: 0 4px; vertical-align: baseline;">
                        <span class="placeholder-text" style="color: #000000; font-weight: 700; font-size: 14px;">' . $zoneNumber . '</span>
                    </span>';
                    
                    $processedContent = preg_replace('/\[DRAG_' . $num . '\]/', $dropBoxHtml, $processedContent, 1);
                }
            @endphp
            
            {{-- Draggable Options at Top --}}
            <div class="draggable-options-grid" style="display: flex; flex-wrap: wrap; gap: 12px; margin: 0 0 20px 0; padding: 0; background: none; border: none;">
                @foreach($options as $optionIndex => $optionText)
                    <div class="draggable-option" 
                         draggable="true"
                         data-option-value="{{ $optionText }}"
                         data-option-letter="{{ chr(65 + $optionIndex) }}"
                         style="padding: 10px 16px; background: white; border: 1px solid #d1d5db; border-radius: 4px; cursor: move; font-size: 14px; color: #1f2937; user-select: none; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                        {{ chr(65 + $optionIndex) }}. {{ $optionText }}
                    </div>
                @endforeach
            </div>
            
            {{-- Question Text with inline drop boxes (NO question number shown) --}}
            <div class="question-text" style="font-size: 15px; line-height: 1.6; color: #1f2937;">{!! $processedContent !!}</div>
            
            {{-- Hidden inputs for each drag zone - use zone number for backend matching --}}
            @foreach($dragZones as $index => $zone)
                <input type="hidden" 
                       name="answers[{{ $question->id }}][zone_{{ $zone['number'] }}]" 
                       data-question-number="{{ $displayNumber + $index }}"
                       data-zone-number="{{ $zone['number'] }}">
            @endforeach
        </div>
        @break
    
    @default
        {{-- Fallback for any other type - treat as text input --}}
        <div class="question-item" id="question-{{ $question->id }}">
            <div class="question-content">
                <span class="question-number">{{ $displayNumber }}</span>
                <div class="question-text">{!! $question->content !!}</div>
            </div>
            
            <div class="answer-input">
                <input type="text" 
                       name="answers[{{ $question->id }}]" 
                       class="text-input" 
                       placeholder="Type your answer"
                       maxlength="50"
                       data-question-number="{{ $displayNumber }}">
            </div>
        </div>
        @break
@endswitch

<style>
.inline-blank {
    width: 150px !important;
    display: inline-block !important;
    margin: 0 4px !important;
    padding: 6px 10px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    font-size: 14px !important;
    text-align: center !important;
    font-weight: 700 !important;
    color: #000000 !important;
    font-style: normal !important;
}

.inline-blank::placeholder {
    text-align: center !important;
    font-weight: 700 !important;
    color: #000000 !important;
    opacity: 1 !important;
    font-style: normal !important;
}

.inline-dropdown {
    display: inline-block !important;
    margin: 0 4px !important;
    padding: 4px 8px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    font-size: 13px !important;
    min-width: 100px !important;
    max-width: 140px !important;
    height: 32px !important;
}

/* Remove instruction background styling */
.question-instruction {
    font-size: 14px !important;
    color: #1f2937 !important;
    margin-bottom: 16px !important;
    font-weight: 600 !important;
    line-height: 1.6 !important;
    /* Remove all background and border styling */
    background: none !important;
    padding: 0 !important;
    border: none !important;
}

.question-instruction p {
    margin: 0 0 8px 0 !important;
}

.question-instruction p:last-child {
    margin-bottom: 0 !important;
}

/* Single Choice Options Styling */
.single-choice-options {
    margin: 20px 0 20px 47px;
}

.single-choice-option-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 16px;
    position: relative;
}

.single-choice-radio {
    width: 20px;
    height: 20px;
    margin-top: 3px;
    margin-right: 12px;
    cursor: pointer;
    flex-shrink: 0;
    accent-color: #1f2937;
}

.single-choice-label {
    display: flex;
    align-items: baseline;
    flex: 1;
    cursor: pointer;
    padding: 10px 16px;
    border-radius: 6px;
    transition: all 0.2s;
    background: white;
    border: 1px solid transparent;
}

.single-choice-label:hover {
    background: #f9fafb;
    border-color: #e5e7eb;
}

.single-choice-radio:checked + .single-choice-label {
    background: #f3f4f6;
    border-color: #374151;
}

.single-choice-radio:checked + .single-choice-label .option-letter {
    background: #1f2937;
    color: white;
}

.single-choice-radio:checked + .single-choice-label .option-text {
    color: #111827;
    font-weight: 600;
}

.option-letter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    background: #f3f4f6;
    border-radius: 4px;
    font-weight: 700;
    font-size: 14px;
    color: #374151;
    margin-right: 12px;
    flex-shrink: 0;
    transition: all 0.2s;
}

.option-text {
    font-size: 15px;
    line-height: 1.6;
    color: #1f2937;
    transition: all 0.2s;
}

/* Single/Multiple Choice Options Styling */
.options-list {
    margin: 20px 0;
    margin-left: 47px;
}

.option-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
    cursor: pointer;
    padding: 12px;
    border-radius: 6px;
    transition: all 0.2s;
    border: 1px solid transparent;
}

.option-item:hover {
    background: #f9fafb;
    border-color: #e5e7eb;
}

.option-radio,
.option-checkbox {
    margin-top: 2px;
    margin-right: 12px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    flex-shrink: 0;
    accent-color: #1f2937;
}

.option-label {
    flex: 1;
    font-size: 15px;
    line-height: 1.6;
    color: #1f2937;
    cursor: pointer;
    display: flex;
    align-items: baseline;
}

.option-label strong {
    font-weight: 700;
    margin-right: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    background: #f3f4f6;
    border-radius: 4px;
    font-size: 14px;
    color: #374151;
    transition: all 0.2s;
}

/* Selected state */
.option-item:has(input:checked) {
    background: #f3f4f6;
    border: 1px solid #374151;
    margin-left: -1px;
    padding: 11px;
}

.option-item:has(input:checked) .option-label {
    color: #111827;
    font-weight: 600;
}

.option-item:has(input:checked) .option-label strong {
    background: #1f2937;
    color: white;
}

/* Drag and Drop Styles - Inline in Content */

/* Drop box styles - now inline in text */
.drop-box {
    display: inline-block;
    min-width: 100px;
    max-width: 200px;
    height: 36px;
    border: 2px dashed #9ca3af;
    border-radius: 4px;
    line-height: 36px;
    text-align: center;
    transition: all 0.2s;
    background: white;
    font-size: 14px;
    padding: 0 8px;
    cursor: pointer;
    position: relative;
    margin: 0 4px;
    vertical-align: baseline;
    white-space: nowrap;
    overflow: hidden;
}

.drop-box.drag-over {
    background: #eff6ff;
    border-color: #3b82f6;
    border-style: solid;
}

.drop-box.has-answer {
    border-style: solid;
    border-color: #10b981;
    background: #ecfdf5;
    color: #065f46;
    font-weight: 500;
    cursor: move;
    padding-right: 28px;
}

.drop-box .placeholder-text {
    color: #000000;
    font-style: normal;
    font-size: 14px;
    font-weight: 700;
    line-height: 1;
}

.drop-box .answer-text {
    color: #065f46;
    font-weight: 500;
    font-size: 12px;
    line-height: 36px;
    display: inline-block;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: baseline;
}

.drop-box .remove-answer {
    position: absolute;
    right: 4px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #ef4444;
    color: white;
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 11px;
    line-height: 18px;
}

.drop-box.has-answer:hover .remove-answer {
    display: flex;
}

/* Draggable Options - Above content */
.draggable-options-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 16px 0 20px 0;
    padding: 0;
    background: none;
    border: none;
    border-radius: 0;
}

.draggable-option {
    padding: 10px 16px;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    cursor: move;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: normal;
    color: #1f2937;
    user-select: none;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.draggable-option:hover:not(.placed) {
    background: #f9fafb;
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.draggable-option.dragging {
    opacity: 0.5;
    cursor: grabbing;
}

.draggable-option.placed {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f3f4f6;
    border-color: #d1d5db;
}
</style>
