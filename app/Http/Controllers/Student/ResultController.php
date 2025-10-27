<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\Question;
use App\Models\HumanEvaluationRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ResultController extends Controller
{
    /**
     * Display a listing of the student's results.
     */
    public function index(Request $request): View
    {
        $query = StudentAttempt::where('user_id', auth()->id())
            ->with(['testSet', 'testSet.section']);
        
        // Filter by section - exclude full-test filter from regular attempts
        if ($request->has('section') && $request->section !== 'all' && $request->section !== 'full-test') {
            $query->whereHas('testSet.section', function($q) use ($request) {
                $q->where('name', $request->section);
            });
        }
        
        // If filtering for full tests only, return empty collection for regular attempts
        if ($request->has('section') && $request->section === 'full-test') {
            // Create an empty paginator for consistency
            $attempts = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // empty collection
                0, // total items
                10, // per page
                1, // current page
                ['path' => $request->url()]
            );
        } else {
            // Filter by time period for regular attempts
            if ($request->has('period') && $request->period !== 'all') {
                switch($request->period) {
                    case '30days':
                        $query->where('created_at', '>=', now()->subDays(30));
                        break;
                    case '3months':
                        $query->where('created_at', '>=', now()->subMonths(3));
                        break;
                    case '6months':
                        $query->where('created_at', '>=', now()->subMonths(6));
                        break;
                }
            }
            
            $attempts = $query->latest()->paginate(10)->withQueryString();
        }
        
        // Get full test attempts separately
        $fullTestAttempts = collect();
        
        // Only fetch full test attempts if not filtering by specific section or if showing all/full-test
        if (!$request->has('section') || in_array($request->section, ['all', 'full-test'])) {
            $fullTestQuery = \App\Models\FullTestAttempt::where('user_id', auth()->id())
                ->with('fullTest');
            
            // Apply time period filter
            if ($request->has('period') && $request->period !== 'all') {
                switch($request->period) {
                    case '30days':
                        $fullTestQuery->where('created_at', '>=', now()->subDays(30));
                        break;
                    case '3months':
                        $fullTestQuery->where('created_at', '>=', now()->subMonths(3));
                        break;
                    case '6months':
                        $fullTestQuery->where('created_at', '>=', now()->subMonths(6));
                        break;
                }
            }
            
            $fullTestAttempts = $fullTestQuery->latest()->get();
        }
        
        return view('student.results.index', compact('attempts', 'fullTestAttempts'));
    }
    
    /**
     * Display the specified result.
     */
    public function show(Request $request, StudentAttempt $attempt): View
    {
        // Ensure the attempt belongs to the authenticated user
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Load all necessary relationships separately to avoid issues
        $attempt->load('testSet', 'testSet.section');
        
        // Load passages separately and process them
        $passages = $attempt->testSet->questions()
            ->where('question_type', 'passage')
            ->orderBy('part_number')
            ->orderBy('order_number')
            ->get()
            ->map(function($passage) {
                // Process passage content to convert markers to HTML
                $passage->processed_content = Question::processPassageForDisplay(
                    $passage->passage_text ?? $passage->content,
                    true // hide markers for student view
                );
                return $passage;
            });
        
        // Load answers with their relationships
        $attempt->load(['answers.question', 'answers.selectedOption']);
        
        // Process questions to include marker info
        $questionsWithMarkers = collect();
        foreach ($attempt->answers as $answer) {
            if ($answer->question->question_type !== 'passage') {
                $question = $answer->question;
                
                // Check if question has marker and get marker text
                if ($question->marker_id) {
                    $question->marker_text = $question->getMarkerText();
                }
                
                // Process explanation to make marker references clickable
                if ($question->explanation) {
                    $question->processed_explanation = $question->processExplanation();
                }
                
                $questionsWithMarkers->push($answer);
            }
        }
        
        // Calculate statistics for automatically scored sections
        if (in_array($attempt->testSet->section->name, ['listening', 'reading'])) {
            $correctAnswers = 0;
            $answeredQuestions = 0; // Track actual attempted questions
            $totalQuestions = 0;
            
            // First, count actual questions (not individual answers)
            $questions = $attempt->testSet->questions()
                ->where('question_type', '!=', 'passage')
                ->orderBy('part_number')
                ->orderBy('order_number')
                ->get();
            
            // Calculate total question count INCLUDING all sub-questions
            $totalQuestions = $this->calculateTotalQuestions($questions);
            
            // Calculate answers and corrections with improved counting
            $calculationResult = $this->calculateAnswersAndCorrections($questions, $attempt);
            $correctAnswers = $calculationResult['correct'];
            $answeredQuestions = $calculationResult['attempted'];
            
            // Calculate band score using partial test scoring if not all questions attempted
            if ($answeredQuestions < $totalQuestions) {
                $scoreData = \App\Helpers\ScoreCalculator::calculatePartialTestScore(
                    $correctAnswers, 
                    $answeredQuestions, 
                    $totalQuestions, 
                    $attempt->testSet->section->name
                );
                
                $bandScore = $scoreData['band_score'] ?? 0;
                $accuracy = $scoreData['accuracy_percentage'] ?? 0;
                $scoreMessage = $scoreData['message'] ?? '';
                $scoreNote = $scoreData['note'] ?? null;
            } else {
                // Complete test - calculate normally
                $accuracy = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
                
                if ($attempt->testSet->section->name === 'listening') {
                    $bandScore = \App\Helpers\ScoreCalculator::calculateListeningBandScore($correctAnswers, $totalQuestions);
                } else {
                    $bandScore = \App\Helpers\ScoreCalculator::calculateReadingBandScore($correctAnswers, $totalQuestions);
                }
                
                $scoreMessage = "Complete test with band score {$bandScore}";
                $scoreNote = null;
            }
            
            // Update the attempt with calculated band score if not already set
            if (!$attempt->band_score || $attempt->band_score == 0) {
                $attempt->band_score = $bandScore;
                $attempt->save();
            }
            
            // Get current page from request
            $currentPage = $request->get('page', 1);
            $perPage = 10;
            
            return view('student.results.show', compact(
                'attempt', 
                'correctAnswers', 
                'totalQuestions',
                'answeredQuestions',
                'accuracy',
                'passages',
                'questionsWithMarkers',
                'currentPage',
                'perPage',
                'scoreMessage',
                'scoreNote'
            ));
        }
        
        // Check for human evaluation request
        $humanEvaluationRequest = null;
        if (in_array($attempt->testSet->section->name, ['writing', 'speaking'])) {
            $humanEvaluationRequest = HumanEvaluationRequest::with(['teacher.user', 'humanEvaluation'])
                ->where('student_attempt_id', $attempt->id)
                ->first();
        }
        
        // For manually evaluated sections (Writing and Speaking)
        $currentPage = $request->get('page', 1);
        $perPage = 10;
        
        return view('student.results.show', compact('attempt', 'passages', 'humanEvaluationRequest', 'currentPage', 'perPage'));
    }
    
    /**
     * Calculate total questions for a test (handles all question types)
     */
    private function calculateTotalQuestions($questions): int
    {
        $totalQuestions = 0;
        
        foreach ($questions as $question) {
            if ($question->isMasterMatchingHeading()) {
                // Count individual sub-questions from mappings
                $mappings = $question->section_specific_data['mappings'] ?? [];
                $totalQuestions += count($mappings);
            } elseif ($question->question_type === 'sentence_completion' && isset($question->section_specific_data['sentence_completion'])) {
                // Handle enhanced sentence completion questions
                $scData = $question->section_specific_data['sentence_completion'];
                $sentences = $scData['sentences'] ?? [];
                $totalQuestions += count($sentences);
            } elseif ($question->question_type === 'drag_drop') {
                // Handle drag & drop questions
                $dragDropData = $question->section_specific_data ?? [];
                $dropZones = $dragDropData['drop_zones'] ?? [];
                $totalQuestions += max(count($dropZones), 1);
            } elseif ($question->question_type === 'multiple_choice') {
                // For multiple choice, count correct answers as individual questions
                $correctCount = $question->options->where('is_correct', true)->count();
                $totalQuestions += max($correctCount, 1);
            } else {
                // Count blanks and dropdowns for other question types
                $blankCount = 0;
                
                // Count content-based blanks and dropdowns
                $content = $question->content;
                preg_match_all('/\[____\d+____\]/', $content, $blankMatches);
                preg_match_all('/\[DROPDOWN_\d+\]/', $content, $dropdownMatches);
                $blankCount = count($blankMatches[0]) + count($dropdownMatches[0]);
                
                // Count section_specific_data dropdowns
                $dropdownCount = 0;
                if ($question->section_specific_data && isset($question->section_specific_data['dropdown_correct'])) {
                    $dropdownCount = count($question->section_specific_data['dropdown_correct']);
                }
                
                // Count fill_blanks placeholders
                if ($question->question_type === 'fill_blanks') {
                    preg_match_all('/\[____\d+____\]/', $content, $fillBlankMatches);
                    $fillBlankCount = count($fillBlankMatches[0]);
                    $blankCount = max($blankCount, $fillBlankCount);
                }
                
                // Count dropdown_selection placeholders
                if ($question->question_type === 'dropdown_selection') {
                    preg_match_all('/\[DROPDOWN_\d+\]/', $content, $dropdownSelectionMatches);
                    $dropdownSelectionCount = count($dropdownSelectionMatches[0]);
                    $blankCount = max($blankCount, $dropdownSelectionCount);
                }
                
                $totalCount = max($blankCount, $dropdownCount);
                $totalQuestions += max($totalCount, 1);
            }
        }
        
        return $totalQuestions;
    }
    
    /**
     * Calculate answered questions and correct answers (handles all question types)
     */
    private function calculateAnswersAndCorrections($questions, $attempt): array
    {
        $correctAnswers = 0;
        $answeredQuestions = 0;
        
        // Group answers by question ID
        $answersByQuestion = $attempt->answers->groupBy('question_id');
        
        foreach ($questions as $question) {
            $questionAnswers = $answersByQuestion->get($question->id, collect());
            
            if ($question->isMasterMatchingHeading()) {
                // Handle master matching headings - each answer is a sub-question
                $mappings = $question->section_specific_data['mappings'] ?? [];
                foreach ($questionAnswers as $answer) {
                    if ($answer->answer) {
                        $answeredQuestions++;
                        $answerData = json_decode($answer->answer, true);
                        if (isset($answerData['sub_question']) && isset($answerData['selected_letter'])) {
                            // Check if correct based on mappings
                            foreach ($mappings as $mapping) {
                                if ($mapping['question'] == $answerData['sub_question'] && 
                                    $mapping['correct'] == $answerData['selected_letter']) {
                                    $correctAnswers++;
                                    break;
                                }
                            }
                        }
                    }
                }
            } elseif ($question->question_type === 'sentence_completion' && isset($question->section_specific_data['sentence_completion'])) {
                // Handle enhanced sentence completion questions
                $scData = $question->section_specific_data['sentence_completion'];
                $sentences = $scData['sentences'] ?? [];
                
                foreach ($sentences as $sentenceIndex => $sentence) {
                    $questionNumber = $sentence['questionNumber'] ?? ($sentenceIndex + 1);
                    
                    // Find answer for this specific sentence
                    $sentenceAnswer = $questionAnswers->first(function($ans) use ($questionNumber) {
                        $answerData = json_decode($ans->answer, true);
                        if (is_array($answerData) && isset($answerData['sub_question'])) {
                            return (int)$answerData['sub_question'] === $questionNumber;
                        }
                        return false;
                    });
                    
                    if ($sentenceAnswer && $sentenceAnswer->answer) {
                        $answeredQuestions++;
                        $answerData = json_decode($sentenceAnswer->answer, true);
                        
                        if (is_array($answerData) && isset($answerData['selected_answer'])) {
                            $studentAnswer = $answerData['selected_answer'];
                            $correctAnswer = $sentence['correctAnswer'] ?? $sentence['correct_answer'] ?? $sentence['correct'] ?? null;
                            
                            if ($correctAnswer && $studentAnswer === $correctAnswer) {
                                $correctAnswers++;
                            }
                        }
                    }
                }
            } elseif ($question->question_type === 'drag_drop') {
                // Handle drag & drop questions
                $answer = $questionAnswers->first();
                if ($answer && $answer->answer) {
                    $answerData = json_decode($answer->answer, true);
                    if (is_array($answerData)) {
                        $dragDropData = $question->section_specific_data ?? [];
                        $dropZones = $dragDropData['drop_zones'] ?? [];
                        
                        foreach ($dropZones as $zoneIndex => $zone) {
                            $zoneKey = 'zone_' . $zoneIndex;
                            if (isset($answerData[$zoneKey]) && !empty($answerData[$zoneKey])) {
                                $answeredQuestions++;
                                $correctAnswer = $zone['correct_answer'] ?? null;
                                if ($correctAnswer && $answerData[$zoneKey] === $correctAnswer) {
                                    $correctAnswers++;
                                }
                            }
                        }
                    }
                }
            } elseif ($question->question_type === 'multiple_choice') {
                // Handle multiple choice with multiple correct answers
                $correctOptions = $question->options->where('is_correct', true);
                $selectedAnswers = $questionAnswers->where('selected_option_id', '!=', null);
                
                foreach ($selectedAnswers as $answer) {
                    $answeredQuestions++;
                    if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                        $correctAnswers++;
                    }
                }
            } elseif ($question->question_type === 'fill_blanks') {
                // Handle fill in the blanks
                $answer = $questionAnswers->first();
                if ($answer && $answer->answer) {
                    if ($this->isJson($answer->answer)) {
                        $studentAnswers = json_decode($answer->answer, true);
                        
                        // Count each blank separately
                        preg_match_all('/\[____\d+____\]/', $question->content, $matches);
                        foreach ($matches[0] as $match) {
                            preg_match('/\d+/', $match, $numberMatch);
                            $blankNum = $numberMatch[0] ?? null;
                            
                            if ($blankNum && isset($studentAnswers['blank_' . $blankNum])) {
                                $studentAnswer = trim($studentAnswers['blank_' . $blankNum]);
                                if ($studentAnswer !== '') {
                                    $answeredQuestions++;
                                    if ($question->checkBlankAnswer($blankNum, $studentAnswer)) {
                                        $correctAnswers++;
                                    }
                                }
                            }
                        }
                    } else {
                        // Single blank answer
                        if (trim($answer->answer) !== '') {
                            $answeredQuestions++;
                            if ($this->checkTextAnswer($answer)) {
                                $correctAnswers++;
                            }
                        }
                    }
                }
            } elseif ($question->question_type === 'dropdown_selection') {
                // Handle dropdown selection
                $answer = $questionAnswers->first();
                if ($answer && $answer->answer) {
                    if ($this->isJson($answer->answer)) {
                        $studentAnswers = json_decode($answer->answer, true);
                        
                        // Count each dropdown separately
                        preg_match_all('/\[DROPDOWN_\d+\]/', $question->content, $matches);
                        foreach ($matches[0] as $match) {
                            preg_match('/\d+/', $match, $numberMatch);
                            $dropdownNum = $numberMatch[0] ?? null;
                            
                            if ($dropdownNum && isset($studentAnswers['dropdown_' . $dropdownNum])) {
                                $studentAnswer = trim($studentAnswers['dropdown_' . $dropdownNum]);
                                if ($studentAnswer !== '') {
                                    $answeredQuestions++;
                                    
                                    // Check if correct
                                    $sectionData = $question->section_specific_data;
                                    if ($sectionData && isset($sectionData['dropdown_correct'][$dropdownNum])) {
                                        $correctIndex = $sectionData['dropdown_correct'][$dropdownNum];
                                        $dropdownOptions = $sectionData['dropdown_options'][$dropdownNum] ?? '';
                                        
                                        if ($dropdownOptions) {
                                            $options = array_map('trim', explode(',', $dropdownOptions));
                                            $correctOption = $options[$correctIndex] ?? '';
                                            
                                            if ($this->compareAnswers($studentAnswer, $correctOption)) {
                                                $correctAnswers++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                // Handle regular questions (single choice, text answers, etc.)
                $answer = $questionAnswers->first();
                if ($answer) {
                    if ($answer->question->options->count() > 0) {
                        // Multiple/single choice questions
                        if ($answer->selected_option_id) {
                            $answeredQuestions++;
                            if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                                $correctAnswers++;
                            }
                        }
                    } else {
                        // Text-based answers
                        if ($answer->answer && trim($answer->answer) !== '') {
                            $answeredQuestions++;
                            if ($this->checkTextAnswer($answer)) {
                                $correctAnswers++;
                            }
                        }
                    }
                }
            }
        }
        
        return [
            'correct' => $correctAnswers,
            'attempted' => $answeredQuestions
        ];
    }
    
    /**
     * Check if a text-based answer is correct
     */
    private function checkTextAnswer($answer): bool
    {
        $question = $answer->question;
        $studentAnswer = $answer->answer;
        
        // Debug log
        \Log::info('Checking answer', [
            'question_id' => $question->id,
            'student_answer' => $studentAnswer,
            'question_data' => $question->section_specific_data,
            'has_blanks' => $question->blanks()->exists(),
            'blank_count' => $question->blanks()->count(),
            'blanks' => $question->blanks()->get()->toArray()
        ]);
        
        // Handle JSON answers (fill-in-the-blanks with multiple blanks or dropdowns)
        if ($this->isJson($studentAnswer)) {
            $studentAnswers = json_decode($studentAnswer, true);
            
            // Check blanks first
            $results = $question->checkMultipleBlanks($studentAnswers);
            $allCorrect = ($results['total'] > 0 && $results['correct'] === $results['total']);
            
            // Check dropdowns
            $sectionData = $question->section_specific_data;
            if ($sectionData && isset($sectionData['dropdown_correct']) && is_array($sectionData['dropdown_correct'])) {
                // If there are dropdowns, check them
                foreach ($sectionData['dropdown_correct'] as $num => $correctIndex) {
                    $studentDropdownAnswer = null;
                    
                    if (isset($studentAnswers['dropdown_' . $num])) {
                        $studentDropdownAnswer = $studentAnswers['dropdown_' . $num];
                    } elseif (isset($studentAnswers[$num])) {
                        $studentDropdownAnswer = $studentAnswers[$num];
                    }
                    
                    $dropdownOptions = $sectionData['dropdown_options'][$num] ?? '';
                    
                    if ($dropdownOptions) {
                        $options = array_map('trim', explode(',', $dropdownOptions));
                        $correctOption = $options[$correctIndex] ?? '';
                        
                        if (!$this->compareAnswers($studentDropdownAnswer ?? '', $correctOption)) {
                            $allCorrect = false;
                            break;
                        }
                    }
                }
                
                // If only dropdowns exist (no blanks), set allCorrect based on dropdown check
                if ($results['total'] === 0 && count($sectionData['dropdown_correct']) > 0) {
                    $allCorrect = true;
                    foreach ($sectionData['dropdown_correct'] as $num => $correctIndex) {
                        $studentDropdownAnswer = null;
                        
                        if (isset($studentAnswers['dropdown_' . $num])) {
                            $studentDropdownAnswer = $studentAnswers['dropdown_' . $num];
                        } elseif (isset($studentAnswers[$num])) {
                            $studentDropdownAnswer = $studentAnswers[$num];
                        }
                        
                        $dropdownOptions = $sectionData['dropdown_options'][$num] ?? '';
                        
                        if ($dropdownOptions) {
                            $options = array_map('trim', explode(',', $dropdownOptions));
                            $correctOption = $options[$correctIndex] ?? '';
                            
                            if (!$this->compareAnswers($studentDropdownAnswer ?? '', $correctOption)) {
                                $allCorrect = false;
                                break;
                            }
                        }
                    }
                }
            }
            
            return $allCorrect;
        }
        
        // Single text answer - check if it's a single blank
        $blankAnswers = $question->getBlankAnswersArray();
        if (!empty($blankAnswers) && count($blankAnswers) === 1) {
            // Get the first (and only) blank answer
            reset($blankAnswers);
            $blankNum = key($blankAnswers);
            return $question->checkBlankAnswer($blankNum, $studentAnswer);
        }
        
        return false;
    }

    /**
     * Compare two answers with improved flexibility
     */
    private function compareAnswers($studentAnswer, $correctAnswer): bool
    {
        // Handle null/empty cases
        if (empty($studentAnswer) && empty($correctAnswer)) {
            return true;
        }
        
        if (empty($studentAnswer) || empty($correctAnswer)) {
            return false;
        }
        
        // Normalize both answers
        $studentAnswer = $this->normalizeAnswer($studentAnswer);
        $correctAnswer = $this->normalizeAnswer($correctAnswer);
        
        \Log::info('Comparing normalized answers', [
            'student' => $studentAnswer,
            'correct' => $correctAnswer,
            'match' => $studentAnswer === $correctAnswer
        ]);
        
        return $studentAnswer === $correctAnswer;
    }

    /**
     * Normalize answer for comparison
     */
    private function normalizeAnswer($answer): string
    {
        // Convert to string
        $answer = (string) $answer;
        
        // Lowercase
        $answer = strtolower($answer);
        
        // Trim whitespace
        $answer = trim($answer);
        
        // Remove extra spaces
        $answer = preg_replace('/\s+/', ' ', $answer);
        
        // Remove punctuation except apostrophes in contractions
        $answer = preg_replace("/[^\w\s']/", '', $answer);
        
        // Handle common variations
        $replacements = [
            // Contractions
            "don't" => "dont",
            "won't" => "wont",
            "can't" => "cant",
            "shouldn't" => "shouldnt",
            "wouldn't" => "wouldnt",
            "couldn't" => "couldnt",
            "isn't" => "isnt",
            "aren't" => "arent",
            "wasn't" => "wasnt",
            "weren't" => "werent",
            "hasn't" => "hasnt",
            "haven't" => "havent",
            "hadn't" => "hadnt",
            "doesn't" => "doesnt",
            "didn't" => "didnt",
            
            // Number words
            'zero' => '0', 'one' => '1', 'two' => '2', 'three' => '3',
            'four' => '4', 'five' => '5', 'six' => '6', 'seven' => '7',
            'eight' => '8', 'nine' => '9', 'ten' => '10',
            'eleven' => '11', 'twelve' => '12', 'thirteen' => '13',
            'fourteen' => '14', 'fifteen' => '15', 'sixteen' => '16',
            'seventeen' => '17', 'eighteen' => '18', 'nineteen' => '19',
            'twenty' => '20', 'thirty' => '30', 'forty' => '40',
            'fifty' => '50', 'sixty' => '60', 'seventy' => '70',
            'eighty' => '80', 'ninety' => '90', 'hundred' => '100',
            
            // Common variations
            'ok' => 'okay',
            'alright' => 'all right',
        ];
        
        $answer = strtr($answer, $replacements);
        
        // Final trim
        return trim($answer);
    }
    
    /**
     * Check if a string is valid JSON
     */
    private function isJson($string): bool
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    /**
     * Get detailed results data (for AJAX requests)
     */
    public function getDetails(StudentAttempt $attempt): \Illuminate\Http\JsonResponse
    {
        // Ensure the attempt belongs to the authenticated user
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $attempt->load([
            'answers.question.options',
            'answers.selectedOption',
            'answers.question.correctOption'
        ]);
        
        $details = [];
        
        foreach ($attempt->answers as $answer) {
            $isCorrect = false;
            $correctAnswer = '';
            
            if ($answer->question->options->count() > 0) {
                // Multiple choice
                $isCorrect = $answer->selectedOption && $answer->selectedOption->is_correct;
                $correctAnswer = $answer->question->correctOption()->content ?? '';
            } else {
                // Text answer
                $isCorrect = $this->checkTextAnswer($answer);
                $correctAnswer = 'See Explanation';
            }
            
            $details[] = [
                'question_id' => $answer->question->id,
                'question_number' => $answer->question->order_number,
                'is_correct' => $isCorrect,
                'student_answer' => $answer->selectedOption->content ?? $answer->answer ?? 'Not answered',
                'correct_answer' => $correctAnswer,
                'explanation' => $answer->question->explanation,
                'passage_reference' => $answer->question->passage_reference,
                'tips' => $answer->question->tips,
                'difficulty' => $answer->question->difficulty_level,
                'marker_id' => $answer->question->marker_id,
                'marker_text' => $answer->question->getMarkerText(),
            ];
        }
        
        return response()->json([
            'success' => true,
            'details' => $details,
            'summary' => [
                'total_questions' => count($details),
                'correct_answers' => collect($details)->where('is_correct', true)->count(),
                'band_score' => $attempt->band_score,
            ]
        ]);
    }
    
    /**
     * Initiate a test retake
     */
    public function retake(StudentAttempt $attempt): RedirectResponse
    {
        // Ensure the attempt belongs to the authenticated user
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Check if retake is allowed
        if (!$attempt->canRetake()) {
            return redirect()->back()->with('error', 'You cannot retake this test.');
        }
        
        // Get the test section name
        $sectionName = $attempt->testSet->section->name;
        
        // Redirect to the appropriate test section onboarding
        switch ($sectionName) {
            case 'listening':
                return redirect()->route('student.listening.onboarding.confirm-details', $attempt->testSet->id)
                    ->with('info', 'Starting test retake...');
                
            case 'reading':
                return redirect()->route('student.reading.onboarding.confirm-details', $attempt->testSet->id)
                    ->with('info', 'Starting test retake...');
                
            case 'writing':
                return redirect()->route('student.writing.onboarding.confirm-details', $attempt->testSet->id)
                    ->with('info', 'Starting test retake...');
                
            case 'speaking':
                return redirect()->route('student.speaking.onboarding.confirm-details', $attempt->testSet->id)
                    ->with('info', 'Starting test retake...');
                
            default:
                return redirect()->back()->with('error', 'Invalid test section.');
        }
    }
}