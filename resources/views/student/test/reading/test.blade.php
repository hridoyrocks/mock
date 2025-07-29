@php
    use Illuminate\Support\Str;
@endphp
<x-test-layout>
    <x-slot name="title">IELTS Reading Test</x-slot>
    
    <x-slot name="meta">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
    </x-slot>
    
 {{-- CSS Link --}}
@vite(['resources/css/reading-test.css', 'resources/css/test-notepad.css'])

  
    <!-- User Info Bar WITH Integrated Timer -->
    <div class="user-bar" style="height: 50px;">
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
                auto-submit-form-id="reading-form"
                position="integrated"
                :warning-time="600"
                :danger-time="300"
            />
        </div>
        
        <div class="user-controls">
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm help-button" id="help-button">Help ?</button>
            <button class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm no-nav">Hide</button>
        </div>
    </div>


    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <!-- Part Header Container -->
        <div class="global-part-header" id="global-part-header">
            <!-- Part header will be inserted here by JavaScript -->
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
        <!-- Reading Passage(s) Section -->
        <div class="passage-section" style="position: relative; z-index: 1;">
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
                    
                    // Special handling for master matching headings
                    if ($question->question_type === 'matching_headings' && $question->isMasterMatchingHeading()) {
                        $individualNumbers = $question->getIndividualQuestionNumbers();
                        $mappingCount = count($individualNumbers);
                        
                        if ($mappingCount > 0) {
                            // Store all question numbers for this master question
                            $questionNumbers = [];
                            for ($i = 0; $i < $mappingCount; $i++) {
                                $questionNumbers[$i] = $currentQuestionNumber + $i;
                            }
                            
                            $displayQuestions[] = [
                                'question' => $question,
                                'has_blanks' => false,
                                'is_master' => true,
                                'display_number' => $currentQuestionNumber,
                                'question_numbers' => $questionNumbers,
                                'count' => $mappingCount
                            ];
                            
                            $currentQuestionNumber += $mappingCount;
                        } else {
                            // Regular matching heading
                            $displayQuestions[] = [
                                'question' => $question,
                                'has_blanks' => false,
                                'display_number' => $currentQuestionNumber
                            ];
                            $currentQuestionNumber++;
                        }
                    } elseif ($question->question_type === 'sentence_completion' && isset($question->section_specific_data['sentence_completion'])) {
                        // Handle sentence completion with multiple sentences
                        $scData = $question->section_specific_data['sentence_completion'];
                        $sentenceCount = isset($scData['sentences']) ? count($scData['sentences']) : 0;
                        
                        if ($sentenceCount > 0) {
                            // Store all question numbers for this sentence completion
                            $questionNumbers = [];
                            for ($i = 0; $i < $sentenceCount; $i++) {
                                $questionNumbers[$i] = $currentQuestionNumber + $i;
                            }
                            
                            $displayQuestions[] = [
                                'question' => $question,
                                'has_blanks' => false,
                                'is_sentence_completion' => true,
                                'display_number' => $currentQuestionNumber,
                                'question_numbers' => $questionNumbers,
                                'count' => $sentenceCount
                            ];
                            
                            $currentQuestionNumber += $sentenceCount;
                        } else {
                            // Fallback for sentence completion without data
                            $displayQuestions[] = [
                                'question' => $question,
                                'has_blanks' => false,
                                'display_number' => $currentQuestionNumber
                            ];
                            $currentQuestionNumber++;
                        }
                    } else {
                        // Count blanks in this question
                        preg_match_all('/\[BLANK_\d+\]|\[____\d+____\]/', $question->content, $blankMatches);
                        preg_match_all('/\[DROPDOWN_\d+\]/', $question->content, $dropdownMatches);
                        preg_match_all('/\[HEADING_DROPDOWN_\d+\]/', $question->content, $headingDropdownMatches);
                        $blankCount = count($blankMatches[0]) + count($dropdownMatches[0]) + count($headingDropdownMatches[0]);
                        
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
                                        <h2 class="passage-title">{!! $passage->instructions !!}</h2>
                                    @else
                                        <h2 class="passage-title">Reading Passage {{ $partNumber }}</h2>
                                    @endif
                                    
                                    @if($passage->passage_text)
                                        <div class="passage-content">
                                            {!! $passage->passage_text !!}
                                        </div>
                                    @elseif($passage->content)
                                        <div class="passage-content">
                                            {!! $passage->content !!}
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
                            <div class="passage-content-wrapper">
                                <div class="no-passage-message">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p>No reading passage available for Part {{ $partNumber }}.</p>
                                    <p class="text-sm mt-2">Questions are shown on the right side.</p>
                                </div>
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

         <!-- Split Divider - Minimal Line with Icon -->
    <div class="split-divider" id="split-divider" title="Drag to resize | Double-click to reset" style="position: relative; z-index: 100;">
        <span></span>
    </div>
        
        <!-- Questions Section -->
        <div class="questions-section" style="position: relative; z-index: 1;">
            <form id="reading-form" action="{{ route('student.reading.submit', $attempt) }}" method="POST">
                @csrf
                
                @php
                    $groupedQuestions = collect($displayQuestions)->groupBy(function($item) {
                        return $item['question']->part_number;
                    });
                @endphp
                
                @foreach ($groupedQuestions as $partNumber => $partQuestions)
                    <div class="part-questions" data-part="{{ $partNumber }}" style="{{ !$loop->first ? 'display: none;' : '' }}">
                        @php
                            // Store question range info for JavaScript
                            $startNumber = 1;
                            $endNumber = 0;
                            $currentCount = 0;
                            
                            foreach ($displayQuestions as $idx => $item) {
                                if ($item['question']->part_number < $partNumber) {
                                    if ($item['has_blanks']) {
                                        $currentCount += count($item['blank_numbers']);
                                    } else {
                                        $currentCount++;
                                    }
                                } elseif ($item['question']->part_number == $partNumber) {
                                    if ($startNumber == 1 && $currentCount > 0) {
                                        $startNumber = $currentCount + 1;
                                    }
                                    if ($item['has_blanks']) {
                                        $endNumber = $currentCount + count($item['blank_numbers']);
                                        $currentCount = $endNumber;
                                    } else {
                                        $currentCount++;
                                        $endNumber = $currentCount;
                                    }
                                }
                            }
                            
                            if ($startNumber == 1 && $endNumber == 0) {
                                $startNumber = 1;
                                $endNumber = count($partQuestions);
                            } elseif ($startNumber > 1 && $endNumber == 0) {
                                $endNumber = $startNumber + count($partQuestions) - 1;
                            }
                        @endphp
                        <div class="part-questions-inner" data-start-number="{{ $startNumber }}" data-end-number="{{ $endNumber }}" data-part-number="{{ $partNumber }}">
                        
                        @php
                        $questionGroups = $partQuestions->groupBy(function($item) {
                            return $item['question']->question_group;
                        });
                        $shownInstructions = [];
                        $processedQuestions = [];
                        @endphp
                        
                        @foreach ($questionGroups as $groupName => $questions)
                            @if($groupName)
                                @php
                                    // Check if this group contains sentence completion questions
                                    $groupHasSentenceCompletion = false;
                                    foreach($questions as $item) {
                                        if($item['question']->question_type === 'sentence_completion') {
                                            $groupHasSentenceCompletion = true;
                                            break;
                                        }
                                    }
                                @endphp
                                
                                @if(!$groupHasSentenceCompletion)
                                    <div class="question-group-header">
                                        {{ $groupName }}
                                    </div>
                                @endif
                            @endif
                            
                            @php
                                // Group questions by instruction
                                $questionsByInstruction = $questions->groupBy(function($item) {
                                    // Skip instruction grouping for sentence completion
                                    if($item['question']->question_type === 'sentence_completion') {
                                        return 'no-instruction';
                                    }
                                    return $item['question']->instructions ?: 'no-instruction';
                                });
                            @endphp
                            
                            @foreach($questionsByInstruction as $instruction => $instructionQuestions)
                                @if($instruction !== 'no-instruction')
                                    @php
                                        // Skip showing instruction here for sentence completion as it has custom display
                                        $skipInstructionTypes = ['sentence_completion'];
                                        $shouldShowInstruction = true;
                                        foreach($instructionQuestions as $q) {
                                            if(in_array($q['question']->question_type, $skipInstructionTypes)) {
                                                $shouldShowInstruction = false;
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if($shouldShowInstruction)
                                        <div class="question-instructions">
                                            {!! $instruction !!}
                                        </div>
                                    @endif
                                @endif
                                
                                @foreach ($instructionQuestions as $item)
                                @php
                                    $question = $item['question'];
                                    $hasBlanks = $item['has_blanks'];
                                @endphp
                                
                                <div class="ielts-question-item" id="question-{{ $question->id }}" style="margin-bottom: 24px;">
                                    {{-- Question Title/Instructions - Skip default handling for sentence completion --}}
                                    @if($question->instructions && !isset($shownInstructions[$question->instructions]) && $question->question_type !== 'sentence_completion')
                                        <div class="question-instructions" style="margin-bottom: 12px; font-weight: 600; color: #1f2937;">
                                            {!! $question->instructions !!}
                                        </div>
                                        @php $shownInstructions[$question->instructions] = true; @endphp
                                    @endif
                                    
                                    @if($hasBlanks)
                                        {{-- Check if this is a heading dropdown question --}}
                                        @php
                                            $hasHeadingDropdowns = preg_match('/\[HEADING_DROPDOWN_\d+\]/', $question->content);
                                            $headingOptions = [];
                                            
                                            if ($hasHeadingDropdowns && $question->question_group) {
                                                // Get heading options from matching_headings question in same group
                                                $headingQuestion = $testSet->questions
                                                    ->where('question_type', 'matching_headings')
                                                    ->where('question_group', $question->question_group)
                                                    ->where('part_number', $question->part_number)
                                                    ->first();
                                                
                                                if ($headingQuestion && $headingQuestion->options) {
                                                    $headingOptions = $headingQuestion->options;
                                                }
                                            }
                                        @endphp
                                        
                                        {{-- Show heading list if this is first heading dropdown question in group --}}
                                        @if($hasHeadingDropdowns && count($headingOptions) > 0)
                                            @php
                                                $showHeadingsList = true;
                                                if ($loop->index > 0) {
                                                    $prevQuestion = $questions[$loop->index - 1]['question'] ?? null;
                                                    if ($prevQuestion && $prevQuestion->question_group === $question->question_group && preg_match('/\[HEADING_DROPDOWN_\d+\]/', $prevQuestion->content)) {
                                                        $showHeadingsList = false;
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($showHeadingsList)
                                                <div style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                                    <div style="font-weight: bold; margin-bottom: 10px;">List of Headings</div>
                                                    @foreach ($headingOptions as $optionIndex => $option)
                                                        <div style="margin-bottom: 5px;">
                                                            <strong>{{ chr(65 + $optionIndex) }}.</strong> {{ $option->content }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                        
                                        {{-- Fill-in-the-blanks question with simple numbered blanks --}}
                                        @php
                                            $processedContent = $question->content;
                                            $blankNumbers = $item['blank_numbers'];
                                            
                                            // Replace blanks with simple underline inputs
                                            $blankCounter = 0;
$processedContent = preg_replace_callback('/\[BLANK_(\d+)\]|\[____(\d+)____\]/', function($matches) use ($question, &$blankCounter, $blankNumbers) {
    $blankCounter++;
    $displayNum = $blankNumbers[$blankCounter];
    
    return '<input type="text" 
            name="answers[' . $question->id . '][blank_' . $blankCounter . ']" 
            class="gap-input" 
            data-question-number="' . $displayNum . '"
            placeholder="' . $displayNum . '"
            autocomplete="off">';
}, $processedContent);
                                            
                                            // Replace heading dropdowns
                                            $processedContent = preg_replace_callback('/\[HEADING_DROPDOWN_(\d+)\]/', function($matches) use ($question, &$blankCounter, $blankNumbers) {
                                                $dropdownNum = $matches[1];
                                                $blankCounter++;
                                                $displayNum = $blankNumbers[$blankCounter];
                                                
                                                // Get heading options from the first matching_headings question in the group
                                                $headingOptions = [];
                                                if ($question->question_group) {
                                                    $headingQuestion = $question->testSet->questions()
                                                        ->where('question_type', 'matching_headings')
                                                        ->where('question_group', $question->question_group)
                                                        ->where('part_number', $question->part_number)
                                                        ->first();
                                                    
                                                    if ($headingQuestion && $headingQuestion->options) {
                                                        $headingOptions = $headingQuestion->options;
                                                    }
                                                }
                                                
                                                $selectHtml = '<select name="answers[' . $question->id . '][heading_' . $dropdownNum . ']" 
                       class="gap-dropdown" 
                       data-question-number="' . $displayNum . '">
               <option value="">' . $displayNum . '</option>';
                                                
                                                foreach ($headingOptions as $index => $option) {
                                                    $selectHtml .= '<option value="' . $option->id . '">' . chr(65 + $index) . '</option>';
                                                }
                                                
                                                $selectHtml .= '</select>';
                                                return $selectHtml;
                                            }, $processedContent);
                                            
                                            // Replace dropdowns similarly
                                            if ($question->section_specific_data) {
                                                $dropdownOptions = $question->section_specific_data['dropdown_options'] ?? [];
                                                
                                                $processedContent = preg_replace_callback('/\[DROPDOWN_(\d+)\]/', function($matches) use ($question, $dropdownOptions, &$blankCounter, $blankNumbers) {
                                                    $dropdownNum = $matches[1];
                                                    $blankCounter++;
                                                    $displayNum = $blankNumbers[$blankCounter];
                                                    $options = isset($dropdownOptions[$dropdownNum]) ? explode(',', $dropdownOptions[$dropdownNum]) : [];
                                                    
                                                    $selectHtml = '<select name="answers[' . $question->id . '][dropdown_' . $dropdownNum . ']" 
                       class="gap-dropdown" 
                       data-question-number="' . $displayNum . '">
               <option value="">' . $displayNum . '</option>';
                                                    
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
                                        @if($question->question_type !== 'sentence_completion')
                                            <div class="ielts-q-number" style="font-weight: 700 !important; font-size: 14px !important; color: #000000 !important; line-height: 1.5 !important; margin-bottom: 10px !important; display: block !important; padding: 0 !important; background: none !important; border: none !important;">
                                                <span style="font-weight: 700 !important;">{{ $item['display_number'] }}.</span> {!! strip_tags($question->content) !!}
                                            </div>
                                        @endif
                                            
                                            @if ($question->media_path)
                                                <div class="mb-3">
                                                    <img src="{{ Storage::url($question->media_path) }}" alt="Question Image" class="max-w-full h-auto rounded">
                                                </div>
                                            @endif
                                            
                                        <div class="ielts-options" style="margin-left: 24px; margin-top: 8px;">
                                            @switch($question->question_type)
                                                @case('single_choice')
                                                @case('multiple_choice')
                                                    @foreach ($question->options as $optionIndex => $option)
                                                        <div class="ielts-option" style="margin-bottom: 6px !important; display: flex !important; align-items: center !important; padding: 0 !important; background: none !important;">
                                                            <input type="radio" 
                                                                   name="answers[{{ $question->id }}]" 
                                                                   id="option-{{ $option->id }}" 
                                                                   value="{{ $option->id }}" 
                                                                   style="-webkit-appearance: radio !important; -moz-appearance: radio !important; appearance: radio !important; margin: 0 !important; margin-right: 8px !important; width: 14px !important; height: 14px !important; cursor: pointer !important; padding: 0 !important;"
                                                                   data-question-number="{{ $item['display_number'] }}">
                                                            <label for="option-{{ $option->id }}" style="cursor: pointer !important; font-size: 14px !important; color: #000000 !important; font-weight: normal !important; margin: 0 !important; padding: 0 !important; line-height: 1.4 !important;">
                                                                <strong style="font-weight: 700 !important;">{{ chr(65 + $optionIndex) }}.</strong> {{ $option->content }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                    @break
                                                
                                                @case('true_false')
                                                @case('yes_no')
                                                    @foreach ($question->options as $option)
                                                        <div class="ielts-option" style="margin-bottom: 6px !important; display: flex !important; align-items: center !important; padding: 0 !important; background: none !important;">
                                                            <input type="radio" 
                                                                   name="answers[{{ $question->id }}]" 
                                                                   id="option-{{ $option->id }}" 
                                                                   value="{{ $option->id }}" 
                                                                   style="-webkit-appearance: radio !important; -moz-appearance: radio !important; appearance: radio !important; margin: 0 !important; margin-right: 8px !important; width: 14px !important; height: 14px !important; cursor: pointer !important; padding: 0 !important;"
                                                                   data-question-number="{{ $item['display_number'] }}">
                                                            <label for="option-{{ $option->id }}" style="cursor: pointer !important; font-size: 14px !important; color: #000000 !important; font-weight: normal !important; margin: 0 !important; padding: 0 !important; line-height: 1.4 !important;">
                                                                {{ strtoupper($option->content) }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                    @break
                                                
                                                @case('matching_headings')
                                                    {{-- Master Matching Headings Implementation --}}
                                                    @php
                                                        $displayData = $question->generateMatchingHeadingsDisplay();
                                                        $isFirstInGroup = true;
                                                        
                                                        // Check if this is first question in group
                                                        if ($question->question_group) {
                                                            foreach($questions as $prevItem) {
                                                                if ($prevItem['question']->id == $question->id) break;
                                                                if ($prevItem['question']->question_type === 'matching_headings' && 
                                                                    $prevItem['question']->question_group === $question->question_group) {
                                                                    $isFirstInGroup = false;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($question->isMasterMatchingHeading())
                                                        {{-- This is a master question with multiple mappings --}}
                                                        @if($isFirstInGroup && !empty($displayData['headings']))
                                                            {{-- Show headings list once --}}
                                                            <div style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                                                <div style="font-weight: bold; margin-bottom: 10px;">List of Headings:</div>
                                                                @foreach ($displayData['headings'] as $heading)
                                                                    <div style="margin-bottom: 5px;">
                                                                        <strong>{{ $heading['letter'] }}.</strong> {{ $heading['text'] }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        
                                                        {{-- Display individual questions from master --}}
                                                        @foreach($displayData['questions'] as $qIndex => $questionData)
                                                            @php
                                                                $currentDisplayNumber = isset($item['question_numbers'][$qIndex]) ? $item['question_numbers'][$qIndex] : ($item['display_number'] + $qIndex);
                                                                $questionNumber = isset($questionData['number']) ? $questionData['number'] : (isset($questionData['question']) ? $questionData['question'] : ($item['display_number'] + $qIndex));
                                                                $paragraphLabel = isset($questionData['paragraph']) ? $questionData['paragraph'] : chr(65 + $qIndex);
                                                                $fieldName = 'answers[' . $question->id . '_q' . $questionNumber . ']';
                                                            @endphp
                                                            <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                                                                <span style="font-weight: 700; min-width: 30px;">{{ $currentDisplayNumber }}.</span>
                                                                <span style="min-width: 100px;">Paragraph {{ $paragraphLabel }}:</span>
                                                                <select name="{{ $fieldName }}" 
                                                                        data-question-number="{{ $currentDisplayNumber }}" 
                                                                        data-question-id="{{ $question->id }}"
                                                                        data-sub-question="{{ $questionNumber }}"
                                                                        class="matching-heading-select"
                                                                        style="padding: 5px; border: 1px solid #ccc;">
                                                                    <option value="">Choose</option>
                                                                    @foreach ($displayData['headings'] as $heading)
                                                                        <option value="{{ $heading['letter'] }}">
                                                                            {{ $heading['letter'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endforeach
                                                        
                                                    @else
                                                        {{-- EMERGENCY DEBUG - Show why sentence completion is not rendering --}}
                                                        @php
                                                            \Log::emergency('=== SENTENCE COMPLETION NOT RENDERING ===', [
                                                                'question_id' => $question->id,
                                                                'question_type' => $question->question_type,
                                                                'has_section_specific_data' => isset($question->section_specific_data),
                                                                'section_specific_data' => $question->section_specific_data ?? 'null',
                                                                'has_sentence_completion_key' => isset($question->section_specific_data['sentence_completion']),
                                                                'sectionData' => $sectionData ?? 'null',
                                                                'hasSentenceCompletionData_value' => $hasSentenceCompletionData
                                                            ]);
                                                            
                                                            // Debug for browser console
                                                            echo '<script>console.error("SENTENCE COMPLETION NOT RENDERING:", ' . json_encode([
                                                                'question_id' => $question->id,
                                                                'question_type' => $question->question_type,
                                                                'has_section_data' => isset($sectionData),
                                                                'hasSentenceCompletionData' => $hasSentenceCompletionData,
                                                                'section_specific_data' => $question->section_specific_data
                                                            ]) . ');</script>';
                                                        @endphp
                                                        
                                                        {{-- Show debug info on page --}}
                                                        <div style="background: #ffcccc; border: 2px solid #ff0000; padding: 10px; margin: 10px 0;">
                                                            <strong>DEBUG: Sentence Completion Not Rendering</strong><br>
                                                            Question ID: {{ $question->id }}<br>
                                                            Question Type: {{ $question->question_type }}<br>
                                                            Has Section Data: {{ isset($sectionData) ? 'Yes' : 'No' }}<br>
                                                            Has Sentence Completion Data: {{ $hasSentenceCompletionData ? 'Yes' : 'No' }}<br>
                                                            Section Data Keys: {{ $sectionData ? implode(', ', array_keys($sectionData)) : 'None' }}
                                                        </div>
                                                        {{-- Fallback to old implementation --}}
                                                        @php
                                                            $showHeadingsList = true;
                                                            if ($loop->index > 0) {
                                                                $prevQuestion = $questions[$loop->index - 1]['question'] ?? null;
                                                                if ($prevQuestion && $prevQuestion->question_type === 'matching_headings' && $prevQuestion->question_group === $question->question_group) {
                                                                    $showHeadingsList = false;
                                                                }
                                                            }
                                                        @endphp
                                                        
                                                        @if($showHeadingsList && count($question->options) > 0)
                                                            <div style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
                                                                <div style="font-weight: bold; margin-bottom: 10px;">List of Headings:</div>
                                                                @foreach ($question->options->sortBy('order') as $optionIndex => $option)
                                                                    <div style="margin-bottom: 5px;">
                                                                        <strong>{{ chr(65 + $optionIndex) }}.</strong> {{ $option->content }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        
                                                        <div style="margin-left: 24px; display: flex; align-items: center; gap: 10px;">
                                                            <select name="answers[{{ $question->id }}]" 
                                                                    data-question-number="{{ $item['display_number'] }}" 
                                                                    style="width: 60px; padding: 5px; border: 1px solid #ccc;">
                                                                <option value=""></option>
                                                                @foreach ($question->options as $optionIndex => $option)
                                                                    <option value="{{ $option->id }}">{{ chr(65 + $optionIndex) }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span>{{ strip_tags($question->content) }}</span>
                                                        </div>
                                                    @endif
                                                    @break
                                                    
                                                @case('matching')
                                                @case('matching_information')
                                                @case('matching_features')
                                                    <div class="ielts-matching-dropdown">
                                                        <select name="answers[{{ $question->id }}]" 
                                                                class="ielts-select" 
                                                                data-question-number="{{ $item['display_number'] }}">
                                                            <option value="" disabled selected>Select your answer</option>
                                                            @foreach ($question->options as $optionIndex => $option)
                                                                <option value="{{ $option->id }}">
                                                                    {{ chr(65 + $optionIndex) }}. {{ Str::limit($option->content, 50) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @break
                                                
                                                @case('fill_blanks')
                                                @case('sentence_completion')
                                                    {{-- Enhanced Sentence Completion Display --}}
                                                    @php
                                                        $sectionData = $question->section_specific_data;
                                                        $hasSentenceCompletionData = isset($sectionData['sentence_completion']);
                                                    @endphp
                                                    
                                                    {{-- Skip question title display for sentence completion - it has its own format --}}
                                                    
                                                    @if($hasSentenceCompletionData)
                                                        @php
                                                            $scData = $sectionData['sentence_completion'];
                                                            
                                                            // EMERGENCY DEBUG - Log everything
                                                            \Log::emergency('=== SENTENCE COMPLETION DEBUG ===', [
                                                                'question_id' => $question->id,
                                                                'question_type' => $question->question_type,
                                                                'has_section_data' => isset($sectionData),
                                                                'section_data_keys' => $sectionData ? array_keys($sectionData) : 'null',
                                                                'has_sc_data' => isset($sectionData['sentence_completion']),
                                                                'sc_data' => $scData ?? 'No SC data',
                                                                'has_sentences' => isset($scData['sentences']),
                                                                'sentence_count' => isset($scData['sentences']) ? count($scData['sentences']) : 0,
                                                                'has_options' => isset($scData['options']),
                                                                'option_count' => isset($scData['options']) ? count($scData['options']) : 0
                                                            ]);
                                                            
                                                            // Debug log for browser console - EXPANDED
                                                            echo '<script>console.log("SENTENCE COMPLETION EXPANDED DEBUG:", ' . json_encode([
                                                                'question_id' => $question->id,
                                                                'has_sc_data' => isset($scData),
                                                                'sc_data_full' => $scData ?? null,
                                                                'sentences_count' => isset($scData['sentences']) ? count($scData['sentences']) : 0,
                                                                'sentences_data' => $scData['sentences'] ?? null,
                                                                'options_count' => isset($scData['options']) ? count($scData['options']) : 0,
                                                                'options_data' => $scData['options'] ?? null,
                                                                'item_question_numbers' => $item['question_numbers'] ?? null,
                                                                'item_display_number' => $item['display_number'] ?? null
                                                            ]) . ');</script>';
                                                            
                                                            $showWordList = true;
                                                            
                                                            // Check if word list already shown in this group
                                                            if ($question->question_group && isset($shownInstructions[$question->question_group . '_sc_wordlist'])) {
                                                                $showWordList = false;
                                                            } elseif ($question->question_group) {
                                                                $shownInstructions[$question->question_group . '_sc_wordlist'] = true;
                                                            }
                                                            
                                                            // Get question range for title
                                                            $startNum = $item['display_number'];
                                                            $sentenceCount = isset($scData['sentences']) ? count($scData['sentences']) : 0;
                                                            $endNum = $startNum + $sentenceCount - 1;
                                                        @endphp
                                                        
                                                        {{-- Question Title with Range --}}
                                                        <div style="margin-bottom: 16px; font-weight: 700; font-size: 15px; color: #111827;">
                                                            Questions {{ $startNum }}-{{ $endNum }}
                                                        </div>
                                                        
                                                        {{-- Display instruction from question or default --}}
                                                        <div style="margin-bottom: 16px; font-size: 14px; color: #374151;">
                                                            @if($question->instructions)
                                                                {!! $question->instructions !!}
                                                            @else
                                                                Complete the sentences below. Choose NO MORE THAN ONE WORD from the list for each answer.
                                                            @endif
                                                        </div>
                                                        
                                                        @if($showWordList)
                                                            {{-- Word List Box - Simple Black-White Design --}}
                                                            @if(isset($scData['options']) && count($scData['options']) > 0)
                                                                <div class="word-list-box">
                                                                    <div style="font-weight: 600; margin-bottom: 12px; font-size: 15px; color: #000000; display: flex; align-items: center;">
                                                                        <svg style="width: 18px; height: 18px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                                        </svg>
                                                                        Word List
                                                                    </div>
                                                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 8px;">
                                                                        @foreach($scData['options'] as $option)
                                                                            <div class="word-list-item">
                                                                                <strong style="color: #000000; font-size: 15px;">{{ $option['id'] }}</strong>
                                                                                <span style="color: #333333; margin-left: 6px;">{{ $option['text'] }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                        
                                                        {{-- Sentences --}}
                                                        @if(isset($scData['sentences']))
                                                            <script>console.log('PROCESSING SENTENCES:', @json($scData['sentences']));</script>
                                                            <div style="margin-top: 15px;">
                                                                @foreach($scData['sentences'] as $sentenceIndex => $sentence)
                                                                    @php
                                                                        // Get the display number from the item's question_numbers array
                                                                        $displayNum = isset($item['question_numbers'][$sentenceIndex]) ? $item['question_numbers'][$sentenceIndex] : ($item['display_number'] + $sentenceIndex);
                                                                        $sentenceText = $sentence['text'];
                                                                        
                                                                        echo '<script>console.log("PROCESSING SENTENCE ' . $sentenceIndex . ':", ' . json_encode([
                                                                            'sentenceIndex' => $sentenceIndex,
                                                                            'sentence' => $sentence,
                                                                            'displayNum' => $displayNum,
                                                                            'sentenceText' => $sentenceText,
                                                                            'hasGap' => strpos($sentenceText, '[GAP]') !== false
                                                                        ]) . ');</script>';
                                                                        
                                                                        // Replace [GAP] with dropdown - ULTRA COMPACT WITH PROPER ALIGNMENT
                                                                        $dropdownHtml = '<select name="answers[' . $question->id . '_q' . $displayNum . ']" '
                                                                                      . 'class="sc-dropdown visible-dropdown" '
                                                                                      . 'data-question-number="' . $displayNum . '" '
                                                                                      . 'data-question-id="' . $question->id . '" '
                                                                                      . 'style="display: inline-block; margin: 0 4px; padding: 2px 6px; border: 1px solid #666666; border-radius: 2px; font-size: 12px; font-weight: 500; min-width: 40px; max-width: 50px; background: #ffffff; color: #000000; cursor: pointer; vertical-align: baseline; line-height: normal;">';
                                                                        $dropdownHtml .= '<option value="" style="color: #666666;">Select</option>';
                                                                        
                                                                        foreach($scData['options'] as $option) {
                                                                            $dropdownHtml .= '<option value="' . $option['id'] . '">' . $option['id'] . '</option>';
                                                                        }
                                                                        
                                                                        $dropdownHtml .= '</select>';
                                                                        
                                                                        echo '<script>console.log("DROPDOWN HTML CREATED:", ' . json_encode($dropdownHtml) . ');</script>';
                                                                        
                                                                        $processedText = str_replace('[GAP]', $dropdownHtml, $sentenceText);
                                                                        
                                                                        // SMART FALLBACK: Handle cases where [GAP] might be missing or malformed
                                                                        if (strpos($processedText, '<select') === false) {
                                                                            // No dropdown found in processed text, add it intelligently
                                                                            if (trim($sentenceText)) {
                                                                                // If sentence ends with punctuation, add dropdown before it
                                                                                if (preg_match('/[.!?]\s*$/', $sentenceText)) {
                                                                                    $processedText = preg_replace('/([.!?]\s*)$/', ' ' . $dropdownHtml . '$1', $sentenceText);
                                                                                } else {
                                                                                    // Add dropdown at the end
                                                                                    $processedText = trim($sentenceText) . ' ' . $dropdownHtml;
                                                                                }
                                                                                echo '<script>console.log("SMART FALLBACK: Added dropdown intelligently");</script>';
                                                                            } else {
                                                                                // Empty sentence, just show dropdown
                                                                                $processedText = $dropdownHtml;
                                                                            }
                                                                        } else {
                                                                            echo '<script>console.log("SUCCESS: [GAP] replacement worked");</script>';
                                                                        }
                                                                        
                                                                        echo '<script>console.log("PROCESSED TEXT:", ' . json_encode($processedText) . ');</script>';
                                                                    @endphp
                                                                    
                                                                    <div style="margin-bottom: 10px; display: flex; align-items: baseline;">
                                                                        <span style="font-weight: 700; min-width: 35px; margin-right: 8px; font-size: 14px; color: #1f2937;">{{ $displayNum }}.</span>
                                                                        <div style="flex: 1; font-size: 14px; line-height: 1.6; color: #374151;">{!! $processedText !!}</div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    @else
                                                        {{-- Fallback to old display --}}
                                                        <input type="text" 
                                                               name="answers[{{ $question->id }}]" 
                                                               style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                                               placeholder="Enter your answer"
                                                               maxlength="100"
                                                               data-question-number="{{ $item['display_number'] }}">
                                                    @endif
                                                    @break
                                                @case('summary_completion')
                                                @case('short_answer')
                                                @default
                                                    <input type="text" 
                                                           name="answers[{{ $question->id }}]" 
                                                           style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;"
                                                           placeholder="Enter your answer"
                                                           maxlength="100"
                                                           data-question-number="{{ $item['display_number'] }}">
                                                    @break
                                            @endswitch
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @endforeach
                        @endforeach
                        </div> <!-- End part-questions-inner -->
                    </div>
                @endforeach
                
                <button type="submit" id="submit-button" class="hidden" onclick="console.log('Submit button clicked')">Submit</button>
            </form>
        </div>
        </div> <!-- End content-area -->
    </div> <!-- End main-content-wrapper -->

    <!-- Bottom Navigation -->
    <div class="bottom-nav" style="height: 60px;">
        <div class="nav-left">
            <div class="review-section">
    <input type="checkbox" id="review-checkbox" class="review-check">
    <label for="review-checkbox" class="review-label">Flag</label>
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
                        @elseif(isset($item['is_master']) && $item['is_master'])
                            {{-- Master matching heading buttons --}}
                            @foreach($item['question_numbers'] as $subIndex => $number)
                                <div class="number-btn {{ $loop->parent->first && $loop->first ? 'active' : '' }}" 
                                     data-question="{{ $item['question']->id }}"
                                     data-sub-question="{{ $subIndex }}"
                                     data-display-number="{{ $number }}"
                                     data-part="{{ $item['question']->part_number }}">
                                    {{ $number }}
                                </div>
                            @endforeach
                        @elseif(isset($item['is_sentence_completion']) && $item['is_sentence_completion'])
                            {{-- Sentence completion buttons --}}
                            @foreach($item['question_numbers'] as $subIndex => $number)
                                <div class="number-btn {{ $loop->parent->first && $loop->first ? 'active' : '' }}" 
                                     data-question="{{ $item['question']->id }}"
                                     data-sub-question="{{ $subIndex }}"
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
    window.testConfig = {
        attemptId: {{ $attempt->id }},
        testSetId: {{ $testSet->id }},
        totalQuestions: {{ $totalQuestionCount }}
    };
    
    // Debug form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reading-form');
        const submitBtn = document.getElementById('submit-button');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submission triggered');
                
                // Check if there are any required fields
                const requiredFields = form.querySelectorAll('[required]');
                let hasEmptyRequired = false;
                
                requiredFields.forEach(field => {
                    if (!field.value || field.value.trim() === '') {
                        console.error('Empty required field:', field.name);
                        hasEmptyRequired = true;
                    }
                });
                
                if (hasEmptyRequired) {
                    console.error('Form has empty required fields');
                }
                
                // Log all form data
                const formData = new FormData(form);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, ':', value);
                }
            });
        }
    });
</script>

@vite('resources/js/reading-test.js')
<script src="{{ asset('js/matching-headings-enhanced-fix.js') }}"></script>
<script src="{{ asset('js/sentence-completion-handler.js') }}"></script>

<!-- CLEAN MINIMAL DROPDOWN STYLING -->
<style>
.sc-dropdown, .visible-dropdown {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    z-index: 999 !important;
    margin: 0 4px !important;
    padding: 2px 6px !important;
    border: 1px solid #666666 !important;
    border-radius: 2px !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    min-width: 40px !important;
    max-width: 50px !important;
    background: #ffffff !important;
    color: #000000 !important;
    cursor: pointer !important;
    appearance: auto !important;
    -webkit-appearance: menulist !important;
    -moz-appearance: menulist !important;
    vertical-align: baseline !important;
    line-height: normal !important;
}

.sc-dropdown:hover, .visible-dropdown:hover {
    background: #f5f5f5 !important;
    border-color: #333333 !important;
}

.sc-dropdown:focus, .visible-dropdown:focus {
    outline: none !important;
    border-color: #000000 !important;
    background: #ffffff !important;
}

.sc-dropdown[data-answered="true"], .visible-dropdown[data-answered="true"] {
    background: #f0f0f0 !important;
    border-color: #333333 !important;
    color: #000000 !important;
    font-weight: 600 !important;
}

.sc-dropdown option, .visible-dropdown option {
    padding: 8px 12px !important;
    font-size: 14px !important;
    background-color: white !important;
    color: #000000 !important;
}

.sc-dropdown option:first-child, .visible-dropdown option:first-child {
    color: #666666 !important;
    font-style: italic !important;
}

/* Force all parent containers to show dropdowns */
.ielts-question-item, .sentence-preview, div[style*="display: flex"] {
    overflow: visible !important;
}

/* Make sure dropdowns are always on top */
select[name*="_q"] {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    z-index: 999 !important;
    margin: 0 4px !important;
    padding: 2px 6px !important;
    border: 1px solid #666666 !important;
    background: #ffffff !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    color: #000000 !important;
    border-radius: 2px !important;
    cursor: pointer !important;
    min-width: 40px !important;
    max-width: 50px !important;
    vertical-align: baseline !important;
    line-height: normal !important;
}

/* Simple navigation button styles */
.number-btn.answered {
    background: #333333 !important;
    color: white !important;
    font-weight: 600 !important;
}

/* Simple black-white word list styling */
.word-list-box {
    background: #ffffff;
    border: 2px solid #000000;
    border-radius: 6px;
    padding: 16px;
    margin-bottom: 20px;
}

.word-list-item {
    background: #ffffff;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #cccccc;
    text-align: center;
    margin-bottom: 8px;
}

.word-list-item:hover {
    background: #f5f5f5;
    border-color: #999999;
}
</style>
    @endpush
    
</x-test-layout>