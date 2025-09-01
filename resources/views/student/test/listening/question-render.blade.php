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
                
                @if($blankCount > 1)
                    <span class="question-number">{{ $displayNumber }}-{{ $displayNumber + $blankCount - 1 }}</span>
                @else
                    <span class="question-number">{{ $displayNumber }}</span>
                @endif
                
                <div class="question-text">{!! $processedContent !!}</div>
            </div>
        </div>
        @break
        
    @case('single_choice')
    @case('multiple_choice')
        {{-- Single/Multiple Choice Question --}}
        <div class="question-item" id="question-{{ $question->id }}">
            <div class="question-content">
                <span class="question-number">{{ $displayNumber }}</span>
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
}

.inline-dropdown {
    display: inline-block !important;
    margin: 0 4px !important;
    padding: 6px 10px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    font-size: 14px !important;
    min-width: 120px !important;
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
</style>
