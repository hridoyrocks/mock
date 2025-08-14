<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\TestSet;
use App\Helpers\ScoreCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReadingTestController extends Controller
{
    /**
     * Display a listing of the available reading tests.
     */
    public function index(): View
    {
        $testSets = TestSet::whereHas('section', function ($query) {
            $query->where('name', 'reading');
        })->where('active', true)->get();
        
        return view('student.test.reading.index', compact('testSets'));
    }
    
    /**
     * Show candidate information confirmation screen.
     */
    public function confirmDetails(TestSet $testSet)
    {
        // Check if the test belongs to reading section
        if ($testSet->section->name !== 'reading') {
            abort(404);
        }
        
        // Get all attempts for this test
        $attempts = StudentAttempt::getAllAttemptsForUserAndTest(auth()->id(), $testSet->id);
        
        // Show previous attempts if any exist
        $latestAttempt = $attempts->first();
        $canRetake = $latestAttempt && $latestAttempt->canRetake();
        
        return view('student.test.reading.onboarding.confirm-details', compact('testSet', 'attempts', 'canRetake'));
    }

    /**
     * Show the instructions screen.
     */
    public function instructions(TestSet $testSet): View
    {
        // Check if the test belongs to reading section
        if ($testSet->section->name !== 'reading') {
            abort(404);
        }
        
        return view('student.test.reading.onboarding.instructions', compact('testSet'));
    }
    
    /**
     * Start a new reading test.
     */
    public function start(TestSet $testSet)
    {
        // Check if the test belongs to reading section
        if ($testSet->section->name !== 'reading') {
            abort(404);
        }
        
        // Check if there's an ongoing attempt
        $attempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'in_progress')
            ->first();
            
        if (!$attempt) {
            // Get the latest completed attempt to determine attempt number
            $latestAttempt = StudentAttempt::getLatestAttempt(auth()->id(), $testSet->id);
            
            $attemptNumber = 1;
            $isRetake = false;
            $originalAttemptId = null;
            
            if ($latestAttempt) {
                // This is a retake
                $attemptNumber = $latestAttempt->attempt_number + 1;
                $isRetake = true;
                $originalAttemptId = $latestAttempt->original_attempt_id ?? $latestAttempt->id;
            }
            
            // Create a new attempt
            $attempt = StudentAttempt::create([
                'user_id' => auth()->id(),
                'test_set_id' => $testSet->id,
                'start_time' => now(),
                'status' => 'in_progress',
                'attempt_number' => $attemptNumber,
                'is_retake' => $isRetake,
                'original_attempt_id' => $originalAttemptId,
            ]);
        }
        
        return view('student.test.reading.test', compact('testSet', 'attempt'));
    }
    
    /**
     * Submit the reading test answers.
     */
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
    {
        // Log all incoming data
        \Log::info('=== READING TEST SUBMISSION START ===', [
            'attempt_id' => $attempt->id,
            'user_id' => auth()->id(),
            'request_method' => $request->method(),
            'has_answers' => $request->has('answers'),
            'all_input_keys' => array_keys($request->all()),
            'answers_count' => is_array($request->input('answers')) ? count($request->input('answers')) : 0
        ]);
        
        // Log each answer
        if ($request->has('answers')) {
            foreach ($request->input('answers', []) as $key => $value) {
                \Log::info('Answer received', [
                    'key' => $key,
                    'value' => $value,
                    'type' => gettype($value)
                ]);
            }
        }
        
        // Verify the attempt belongs to the current user and is not already completed
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return redirect()->route('student.reading.index')
                ->with('error', 'Invalid attempt or test already submitted.');
        }
        
        // Validate the submission
        $request->validate([
            'answers' => 'required|array',
        ]);
        
        DB::transaction(function () use ($request, $attempt) {
            // Get all questions (excluding passages)
            $questions = $attempt->testSet->questions()
                ->where('question_type', '!=', 'passage')
                ->get();
            
            \Log::info('Questions in test', [
                'total_questions' => $questions->count(),
                'matching_headings_count' => $questions->where('question_type', 'matching_headings')->count()
            ]);
            
            // Calculate total question count INCLUDING blanks
            $totalQuestions = 0;
            foreach ($questions as $question) {
                $blankCount = $question->countBlanks();
                if ($blankCount > 0) {
                    $totalQuestions += $blankCount; // Each blank counts as a separate question
                } elseif ($question->question_type === 'multiple_choice') {
                    // For multiple choice, count each correct option as a question
                    $correctCount = $question->options->where('is_correct', true)->count();
                    if ($correctCount > 1) {
                        $totalQuestions += $correctCount;
                    } else {
                        $totalQuestions += 1;
                    }
                } else {
                    $totalQuestions += 1;
                }
            }
            
            // Track answered questions and correct answers
            $answeredCount = 0;
            $correctAnswers = 0;
            $matchingHeadingSaved = 0;
            
            // Save answers
            foreach ($request->answers as $questionId => $answer) {
                // Check if this is a sentence completion answer (e.g., 123_q14)
                if (str_contains($questionId, '_q')) {
                    // Extract question ID and sub-question number
                    [$actualQuestionId, $subQuestionNum] = explode('_q', $questionId);
                    $subQuestionNum = (int) $subQuestionNum;
                    
                    \Log::info('SENTENCE COMPLETION ANSWER DETECTED', [
                        'original_key' => $questionId,
                        'question_id' => $actualQuestionId,
                        'sub_question_num' => $subQuestionNum,
                        'value' => $answer
                    ]);
                    
                    if (!empty($answer)) {
                        $question = $questions->find($actualQuestionId);
                        if ($question && $question->question_type === 'sentence_completion') {
                            // Save the answer
                            $saved = StudentAnswer::create([
                                'attempt_id' => $attempt->id,
                                'question_id' => $actualQuestionId,
                                'selected_option_id' => null,
                                'answer' => json_encode([
                                    'sub_question' => $subQuestionNum,
                                    'selected_answer' => $answer
                                ]),
                            ]);
                            
                            if ($saved && $saved->exists) {
                                \Log::info('SENTENCE COMPLETION SAVED SUCCESSFULLY', [
                                    'student_answer_id' => $saved->id,
                                    'question_id' => $actualQuestionId,
                                    'sub_question' => $subQuestionNum,
                                    'selected_answer' => $answer
                                ]);
                            }
                            
                            $answeredCount++;
                            
                            // Check if correct based on sentence completion data
                            $sectionData = $question->section_specific_data;
                            if (isset($sectionData['sentence_completion']['sentences'])) {
                                foreach ($sectionData['sentence_completion']['sentences'] as $sentence) {
                                    if ($sentence['questionNumber'] == $subQuestionNum && 
                                        $sentence['correctAnswer'] == $answer) {
                                        $correctAnswers++;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    continue; // Skip to next iteration
                }
                
                // Handle master matching headings with sub-questions (e.g., 123_q14)
                if (str_contains($questionId, '_q')) {
                    // Extract master question ID and sub-question number
                    [$masterQuestionId, $subQuestionNum] = explode('_q', $questionId);
                    $subQuestionNum = (int) $subQuestionNum;
                    
                    \Log::info('MASTER MATCHING HEADING DETECTED', [
                        'original_key' => $questionId,
                        'master_question_id' => $masterQuestionId,
                        'sub_question_num' => $subQuestionNum,
                        'value' => $answer
                    ]);
                    
                    if (!empty($answer)) {
                        $masterQuestion = $questions->find($masterQuestionId);
                        if ($masterQuestion && $masterQuestion->question_type === 'matching_headings' && $masterQuestion->isMasterMatchingHeading()) {
                            // Find the correct option based on letter (A, B, C, etc.)
                            $optionIndex = ord($answer) - ord('A');
                            $option = $masterQuestion->options->sortBy('order')->values()->get($optionIndex);
                            
                            if ($option) {
                                $saved = StudentAnswer::create([
                                    'attempt_id' => $attempt->id,
                                    'question_id' => $masterQuestionId,
                                    'selected_option_id' => $option->id,
                                    'answer' => json_encode([
                                        'sub_question' => $subQuestionNum,
                                        'selected_letter' => $answer,
                                        'option_id' => $option->id
                                    ]),
                                ]);
                                
                                if ($saved && $saved->exists) {
                                    $matchingHeadingSaved++;
                                    \Log::info('MASTER MATCHING HEADING SAVED SUCCESSFULLY', [
                                        'student_answer_id' => $saved->id,
                                        'master_question_id' => $masterQuestionId,
                                        'sub_question' => $subQuestionNum,
                                        'selected_letter' => $answer,
                                        'selected_option_id' => $option->id
                                    ]);
                                }
                                
                                $answeredCount++;
                                
                                // Check if correct based on mappings
                                $mappings = $masterQuestion->section_specific_data['mappings'] ?? [];
                                foreach ($mappings as $mapping) {
                                    if ($mapping['question'] == $subQuestionNum && $mapping['correct'] == $answer) {
                                        $correctAnswers++;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    continue; // Skip to next iteration
                }
                
                // Check if this is a matching headings paragraph answer (legacy format)
                if (str_contains($questionId, '_para_')) {
                    // Extract actual question ID and paragraph index
                    [$actualQuestionId, $paraIndex] = explode('_para_', $questionId);
                    
                    \Log::info('MATCHING HEADING DETECTED (LEGACY)', [
                        'original_key' => $questionId,
                        'question_id' => $actualQuestionId,
                        'para_index' => $paraIndex,
                        'value' => $answer
                    ]);
                    
                    if (!empty($answer) && is_numeric($answer)) {
                        $question = $questions->find($actualQuestionId);
                        if ($question && $question->question_type === 'matching_headings') {
                            $saved = StudentAnswer::create([
                                'attempt_id' => $attempt->id,
                                'question_id' => $actualQuestionId,
                                'selected_option_id' => $answer,
                                'answer' => json_encode(['paragraph' => $paraIndex, 'option_id' => $answer]),
                            ]);
                            
                            if ($saved && $saved->exists) {
                                $matchingHeadingSaved++;
                                \Log::info('MATCHING HEADING SAVED SUCCESSFULLY (LEGACY)', [
                                    'student_answer_id' => $saved->id,
                                    'question_id' => $actualQuestionId,
                                    'paragraph' => $paraIndex,
                                    'selected_option_id' => $answer
                                ]);
                            }
                            
                            $answeredCount++;
                            
                            // Check if correct
                            $option = $question->options->find($answer);
                            if ($option && $option->is_correct) {
                                $correctAnswers++;
                            }
                        }
                    }
                    continue; // Skip to next iteration
                }
                
                // Skip if no answer provided
                if (empty($answer) && $answer !== '0') {
                    continue;
                }
                
                $question = $questions->find($questionId);
                if (!$question || $question->question_type === 'passage') {
                    continue;
                }
                
                // Log the answer being processed
                \Log::info('Processing regular answer', [
                    'question_id' => $questionId,
                    'question_type' => $question->question_type,
                    'answer' => $answer,
                    'is_array' => is_array($answer)
                ]);
                
                if (is_array($answer)) {
                    // Log array answers for debugging
                    \Log::info('Processing array answer for question', [
                        'question_id' => $questionId,
                        'answer_array' => $answer,
                        'question_type' => $question->question_type ?? 'unknown'
                    ]);
                    
                    // Handle array answers (blanks, dropdowns or multiple selections)
                    if (isset($answer['blank_1']) || isset($answer['dropdown_1']) || ($question->question_type === 'dropdown_selection' && isset($answer['dropdown_1']))) {
                        // Fill-in-the-blanks with multiple blanks
                        $combinedAnswer = json_encode($answer);
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'selected_option_id' => null,
                                'answer' => $combinedAnswer,
                            ]
                        );
                        
                        // Check each blank/dropdown separately for IELTS scoring
                        if ($question->question_type === 'dropdown_selection') {
                            $sectionData = $question->section_specific_data;
                            
                            // For dropdown_selection, each dropdown counts as a separate question
                            foreach ($answer as $dropdownKey => $dropdownValue) {
                                if (strpos($dropdownKey, 'dropdown_') === 0 && !empty($dropdownValue)) {
                                    $answeredCount++;
                                    
                                    // Extract dropdown number
                                    $dropdownNum = str_replace('dropdown_', '', $dropdownKey);
                                    
                                    // Check if correct
                                    if (isset($sectionData['dropdown_correct'][$dropdownNum])) {
                                        $correctIndex = $sectionData['dropdown_correct'][$dropdownNum];
                                        $options = explode(',', $sectionData['dropdown_options'][$dropdownNum] ?? '');
                                        $correctAnswer = isset($options[$correctIndex]) ? trim($options[$correctIndex]) : '';
                                        
                                        \Log::info('Dropdown answer check', [
                                            'dropdown_num' => $dropdownNum,
                                            'student_answer' => $dropdownValue,
                                            'correct_answer' => $correctAnswer,
                                            'correct_index' => $correctIndex,
                                            'options' => $options
                                        ]);
                                        
                                        if ($this->compareAnswers($dropdownValue, $correctAnswer)) {
                                            $correctAnswers++;
                                        }
                                    }
                                }
                            }
                        } else {
                            // Regular blank checking
                            $blankResults = $this->checkMultiBlankAnswer($question, $answer);
                            $answeredCount += $blankResults['answered'];
                            $correctAnswers += $blankResults['correct'];
                        }
                    } else {
                        // Multiple selection question (multiple choice with checkboxes)
                        \Log::info('Processing multiple selection answer', [
                            'question_id' => $questionId,
                            'answer_values' => $answer,
                            'question_type' => $question->question_type
                        ]);
                        
                        // First, clear any existing answers for this question
                        StudentAnswer::where('attempt_id', $attempt->id)
                                    ->where('question_id', $questionId)
                                    ->delete();
                        
                        // Check if this is actually saving to database
                        $savedCount = 0;
                        $correctSelections = 0;
                        
                        foreach ($answer as $key => $value) {
                            \Log::info('Individual selection', [
                                'key' => $key,
                                'value' => $value,
                                'is_numeric' => is_numeric($value)
                            ]);
                            
                            if (is_numeric($value)) {
                                $saved = StudentAnswer::create([
                                    'attempt_id' => $attempt->id,
                                    'question_id' => $questionId,
                                    'selected_option_id' => $value,
                                    'answer' => null,
                                ]);
                                
                                if ($saved && $saved->id) {
                                    $savedCount++;
                                    \Log::info('SAVED TO DATABASE', [
                                        'student_answer_id' => $saved->id,
                                        'question_id' => $questionId,
                                        'selected_option_id' => $value
                                    ]);
                                    
                                    // Check if this option is correct
                                    $option = $question->options->find($value);
                                    if ($option && $option->is_correct) {
                                        $correctSelections++;
                                    }
                                } else {
                                    \Log::error('FAILED TO SAVE TO DATABASE', [
                                        'question_id' => $questionId,
                                        'value' => $value
                                    ]);
                                }
                            }
                        }
                        
                        \Log::info('Total saved for this question', [
                            'question_id' => $questionId,
                            'saved_count' => $savedCount,
                            'total_answers' => count($answer),
                            'correct_selections' => $correctSelections
                        ]);
                        
                        // For multiple choice questions, count each correct selection as a mark
                        if ($question->question_type === 'multiple_choice') {
                            $correctAnswers += $correctSelections;
                            $answeredCount += $correctSelections; // Each correct option counts as a question
                        } else {
                            $answeredCount++;
                        }
                    }
                } else {
                    // Single answer
                    $hasOptions = $question->options->count() > 0;
                    
                    if ($hasOptions && is_numeric($answer)) {
                        // Option ID (single choice, true/false, etc.)
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'selected_option_id' => $answer,
                                'answer' => null,
                            ]
                        );
                        
                        // Check if correct
                        $option = $question->options->find($answer);
                        if ($option && $option->is_correct) {
                            $correctAnswers++;
                        }
                    } else {
                        // Text answer
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'selected_option_id' => null,
                                'answer' => $answer,
                            ]
                        );
                        
                        // Check text answer
                        if ($this->checkSingleTextAnswer($question, $answer)) {
                            $correctAnswers++;
                        }
                    }
                    
                    $answeredCount++;
                }
            }
            
            \Log::info('SUBMISSION SUMMARY', [
                'total_questions' => $totalQuestions,
                'answered_count' => $answeredCount,
                'correct_answers' => $correctAnswers,
                'matching_headings_saved' => $matchingHeadingSaved
            ]);
            
            // Mark attempt as completed
            $attempt->update([
                'end_time' => now(),
                'status' => 'completed',
            ]);
            
            // INCREMENT TEST COUNT
            auth()->user()->incrementTestCount();
            
            // Use the new partial test score calculation
            $scoreData = \App\Helpers\ScoreCalculator::calculatePartialTestScore(
                $correctAnswers, 
                $answeredCount, 
                $totalQuestions,
                'reading'
            );
            
            // Log for debugging
            \Log::info('Score calculation result', $scoreData);
            
            // Store band score and additional data
            $updateData = [
                'band_score' => $scoreData['band_score'] ?? null,
                'total_questions' => $totalQuestions,
                'answered_questions' => $answeredCount,
                'correct_answers' => $correctAnswers
            ];
            
            // Only add optional fields if they exist in the score data
            if (isset($scoreData['completion_percentage'])) {
                $updateData['completion_rate'] = $scoreData['completion_percentage'];
            }
            if (isset($scoreData['confidence'])) {
                $updateData['confidence_level'] = $scoreData['confidence'];
            }
            if (isset($scoreData['is_reliable'])) {
                $updateData['is_complete_attempt'] = $scoreData['is_reliable'];
            }
            
            try {
                $attempt->update($updateData);
            } catch (\Exception $e) {
                \Log::error('Failed to update attempt', [
                    'error' => $e->getMessage(),
                    'data' => $updateData
                ]);
                // Continue with basic update
                $attempt->update([
                    'band_score' => $scoreData['band_score'] ?? null,
                    'total_questions' => $totalQuestions,
                    'answered_questions' => $answeredCount,
                    'correct_answers' => $correctAnswers
                ]);
            }
            
            // Store score data in session for display
            session()->flash('score_details', $scoreData);
            
            \Log::info('=== READING TEST SUBMISSION COMPLETE ===');
        });
        
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
    
    /**
     * Check multi-blank answer and return results for IELTS scoring
     */
    protected function checkMultiBlankAnswer($question, $studentAnswers): array
    {
        // Use the new trait method
        $results = $question->checkMultipleBlanks($studentAnswers);
        
        // Also check dropdowns
        $sectionData = $question->section_specific_data;
        $answeredCount = $results['total'];
        $correctCount = $results['correct'];
        
        if ($sectionData && isset($sectionData['dropdown_correct']) && is_array($sectionData['dropdown_correct'])) {
            foreach ($sectionData['dropdown_correct'] as $num => $correctIndex) {
                $studentDropdownAnswer = null;
                
                if (isset($studentAnswers['dropdown_' . $num])) {
                    $studentDropdownAnswer = $studentAnswers['dropdown_' . $num];
                } elseif (isset($studentAnswers[$num])) {
                    $studentDropdownAnswer = $studentAnswers[$num];
                }
                
                if (!empty($studentDropdownAnswer)) {
                    $answeredCount++;
                    
                    $dropdownOptions = $sectionData['dropdown_options'][$num] ?? '';
                    if ($dropdownOptions) {
                        $options = array_map('trim', explode(',', $dropdownOptions));
                        $correctOption = isset($options[$correctIndex]) ? $options[$correctIndex] : '';
                        
                        if ($this->compareAnswers($studentDropdownAnswer, $correctOption)) {
                            $correctCount++;
                        }
                    }
                }
            }
        }
        
        // Check heading dropdowns
        foreach ($studentAnswers as $key => $value) {
            if (strpos($key, 'heading_') === 0 && !empty($value)) {
                $answeredCount++;
                
                // For heading dropdowns, the value is the option ID
                // Check if it's the correct option
                if (is_numeric($value)) {
                    $option = \App\Models\QuestionOption::find($value);
                    if ($option && $option->is_correct) {
                        $correctCount++;
                    }
                }
            }
        }
        
        return [
            'answered' => $answeredCount,
            'correct' => $correctCount,
            'details' => $results['details'] ?? []
        ];
    }
    
    /**
     * Check single text answer
     */
    protected function checkSingleTextAnswer($question, $studentAnswer): bool
    {
        $sectionData = $question->section_specific_data;
        
        // Check if there's a correct answer defined
        if ($sectionData && isset($sectionData['correct_answer'])) {
            return $this->compareAnswers($studentAnswer, $sectionData['correct_answer']);
        }
        
        // For single blank questions, check blank_answers[1]
        if ($sectionData && isset($sectionData['blank_answers']) && isset($sectionData['blank_answers'][1])) {
            return $this->compareAnswers($studentAnswer, $sectionData['blank_answers'][1]);
        }
        
        return false;
    }
    
    /**
     * Check if a text-based answer is correct (DEPRECATED - use checkMultiBlankAnswer instead)
     */
    protected function checkTextAnswer($answer): bool
    {
        $question = $answer->question;
        $studentAnswer = $answer->answer;
        
        // Handle JSON answers (fill-in-the-blanks with multiple blanks)
        if ($this->isJson($studentAnswer)) {
            $studentAnswers = json_decode($studentAnswer, true);
            $results = $this->checkMultiBlankAnswer($question, $studentAnswers);
            
            // For backward compatibility, return true only if ALL blanks are correct
            $totalBlanks = count($question->section_specific_data['blank_answers'] ?? []) + 
                          count($question->section_specific_data['dropdown_correct'] ?? []);
            
            return $totalBlanks > 0 && $results['correct'] === $totalBlanks;
        }
        
        // Single text answer
        return $this->checkSingleTextAnswer($question, $studentAnswer);
    }

    /**
     * Compare two answers with improved flexibility
     */
    protected function compareAnswers($studentAnswer, $correctAnswer): bool
    {
        // Handle null/empty cases
        if (empty($studentAnswer) && empty($correctAnswer)) {
            return true;
        }
        
        if (empty($studentAnswer) || empty($correctAnswer)) {
            return false;
        }
        
        // Normalize both answers
        $studentNormalized = $this->normalizeAnswer($studentAnswer);
        $correctNormalized = $this->normalizeAnswer($correctAnswer);
        
        // Check for exact match after normalization
        if ($studentNormalized === $correctNormalized) {
            return true;
        }
        
        // Check for alternative answers (if correct answer contains '/')
        if (strpos($correctAnswer, '/') !== false) {
            $alternatives = array_map('trim', explode('/', $correctAnswer));
            foreach ($alternatives as $alternative) {
                if ($this->normalizeAnswer($alternative) === $studentNormalized) {
                    return true;
                }
            }
        }
        
        // Check for acceptable variations (if correct answer contains parentheses)
        // e.g., "color(s)" accepts "color" or "colors"
        if (preg_match('/\(([^)]+)\)/', $correctAnswer, $matches)) {
            // Try without the parenthetical part
            $withoutParentheses = str_replace($matches[0], '', $correctAnswer);
            if ($this->normalizeAnswer($withoutParentheses) === $studentNormalized) {
                return true;
            }
            
            // Try with the parenthetical part included
            $withParentheses = str_replace(['(', ')'], '', $correctAnswer);
            if ($this->normalizeAnswer($withParentheses) === $studentNormalized) {
                return true;
            }
        }
        
        \Log::info('Answer comparison failed', [
            'student_normalized' => $studentNormalized,
            'correct_normalized' => $correctNormalized,
            'match' => false
        ]);
        
        return false;
    }

    /**
     * Normalize answer for comparison
     */
    protected function normalizeAnswer($answer): string
    {
        // Convert to string
        $answer = (string) $answer;
        
        // Lowercase
        $answer = strtolower($answer);
        
        // Trim whitespace
        $answer = trim($answer);
        
        // Remove extra spaces
        $answer = preg_replace('/\s+/', ' ', $answer);
        
        // Remove punctuation except apostrophes in contractions and hyphens
        $answer = preg_replace("/[^\w\s'\-]/", '', $answer);
        
        // Handle common variations
        $replacements = [
            // Articles (remove them for flexibility)
            ' the ' => ' ',
            ' a ' => ' ',
            ' an ' => ' ',
            
            // At the beginning
            '/^the\s+/' => '',
            '/^a\s+/' => '',
            '/^an\s+/' => '',
            
            // Contractions
            "don't" => "do not",
            "won't" => "will not",
            "can't" => "cannot",
            "shouldn't" => "should not",
            "wouldn't" => "would not", 
            "couldn't" => "could not",
            "isn't" => "is not",
            "aren't" => "are not",
            "wasn't" => "was not",
            "weren't" => "were not",
            "hasn't" => "has not",
            "haven't" => "have not",
            "hadn't" => "had not",
            "doesn't" => "does not",
            "didn't" => "did not",
            "it's" => "it is",
            "he's" => "he is",
            "she's" => "she is",
            "they're" => "they are",
            "we're" => "we are",
            "you're" => "you are",
            "i'm" => "i am",
            
            // Number words to digits
            'zero' => '0', 'one' => '1', 'two' => '2', 'three' => '3',
            'four' => '4', 'five' => '5', 'six' => '6', 'seven' => '7',
            'eight' => '8', 'nine' => '9', 'ten' => '10',
            'eleven' => '11', 'twelve' => '12', 'thirteen' => '13',
            'fourteen' => '14', 'fifteen' => '15', 'sixteen' => '16',
            'seventeen' => '17', 'eighteen' => '18', 'nineteen' => '19',
            'twenty' => '20', 'thirty' => '30', 'forty' => '40',
            'fifty' => '50', 'sixty' => '60', 'seventy' => '70',
            'eighty' => '80', 'ninety' => '90', 'hundred' => '100',
            'thousand' => '1000',
            
            // Common variations
            'ok' => 'okay',
            'alright' => 'all right',
            '&' => 'and',
            
            // British vs American spelling
            'colour' => 'color',
            'honour' => 'honor',
            'favour' => 'favor',
            'labour' => 'labor',
            'centre' => 'center',
            'theatre' => 'theater',
            'metre' => 'meter',
            'litre' => 'liter',
            'defence' => 'defense',
            'licence' => 'license',
            'practise' => 'practice',
            'organisation' => 'organization',
            'specialise' => 'specialize',
            'analyse' => 'analyze',
            'programme' => 'program',
        ];
        
        // Apply string replacements
        $answer = strtr($answer, $replacements);
        
        // Apply regex replacements
        $answer = preg_replace('/^the\s+/i', '', $answer);
        $answer = preg_replace('/^a\s+/i', '', $answer);
        $answer = preg_replace('/^an\s+/i', '', $answer);
        
        // Remove multiple spaces again after replacements
        $answer = preg_replace('/\s+/', ' ', $answer);
        
        // Final trim
        return trim($answer);
    }
    
    /**
     * Check if a string is valid JSON
     */
    protected function isJson($string): bool
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
