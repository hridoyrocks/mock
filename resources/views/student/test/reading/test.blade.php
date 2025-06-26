{{-- resources/views/student/test/reading/test.blade.php --}}
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

         <!-- Split Divider ADD THIS -->
    <div class="split-divider" id="split-divider"></div>
        
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
    
    return '<span class="question-number-box">' . $displayNum . '</span><input type="text" 
            name="answers[' . $question->id . '][blank_' . $blankCounter . ']" 
            class="gap-input" 
            placeholder="Type your answer"
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
                                                    
                                                    $selectHtml = '<span class="question-number-box">' . $displayNum . '</span>
               <select name="answers[' . $question->id . '][dropdown_' . $dropdownNum . ']" 
                       class="gap-dropdown" 
                       data-question-number="' . $displayNum . '">
               <option value="">Choose answer</option>';
                                                    
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
    window.testConfig = {
        attemptId: {{ $attempt->id }},
        testSetId: {{ $testSet->id }},
        totalQuestions: {{ $totalQuestionCount }}
    };
</script>


@vite('resources/js/reading-test.js')
    @endpush
    
</x-test-layout>