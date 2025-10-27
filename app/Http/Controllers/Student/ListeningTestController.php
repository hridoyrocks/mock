<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\TestSet;
use App\Models\QuestionOption;
use App\Helpers\ScoreCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class ListeningTestController extends Controller
{
    public function index(Request $request): View
    {
        // Get all active categories with counts for listening section
        $categories = \App\Models\TestCategory::active()
            ->ordered()
            ->withCount(['testSets as listening_count' => function ($query) {
                $query->whereHas('section', function ($q) {
                    $q->where('slug', 'listening')->orWhere('name', 'listening');
                })->where('active', true);
            }])
            ->get();
        
        // Get test sets query
        $testSetsQuery = TestSet::whereHas('section', function ($query) {
            $query->where('name', 'listening');
        })->where('active', true);
        
        // Filter by category if selected
        $selectedCategory = null;
        if ($request->has('category') && $request->category) {
            $selectedCategory = \App\Models\TestCategory::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $testSetsQuery->whereHas('categories', function ($query) use ($selectedCategory) {
                    $query->where('test_categories.id', $selectedCategory->id);
                });
            }
        }
        
        $testSets = $testSetsQuery->get();
        
        return view('student.test.listening.index', compact('testSets', 'categories', 'selectedCategory'));
    }
    
    public function confirmDetails(TestSet $testSet)
    {
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        // Get all attempts for this test
        $attempts = StudentAttempt::getAllAttemptsForUserAndTest(auth()->id(), $testSet->id);
        
        // Show previous attempts if any exist
        $latestAttempt = $attempts->first();
        $canRetake = $latestAttempt && $latestAttempt->canRetake();
        
        return view('student.test.listening.onboarding.confirm-details', compact('testSet', 'attempts', 'canRetake'));
    }

    public function soundCheck(TestSet $testSet): View
    {
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        return view('student.test.listening.onboarding.sound-check', compact('testSet'));
    }

    public function instructions(TestSet $testSet): View
    {
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        return view('student.test.listening.onboarding.instructions', compact('testSet'));
    }
    
    public function start(TestSet $testSet)
    {
        if ($testSet->section->name !== 'listening') {
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
                $attemptNumber = ($latestAttempt->attempt_number ?? 1) + 1;
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
        
        return view('student.test.listening.test', compact('testSet', 'attempt'));
    }
    
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
    {
        // Verify the attempt belongs to the current user and is not already completed
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return redirect()->route('student.listening.index')
                ->with('error', 'Invalid attempt or test already submitted.');
        }
        
        // Check if this is part of a full test
        $fullTestSectionAttempt = \App\Models\FullTestSectionAttempt::where('student_attempt_id', $attempt->id)->first();
        $isPartOfFullTest = $fullTestSectionAttempt !== null;
        
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable',
        ]);
        
        DB::transaction(function () use ($request, $attempt, $isPartOfFullTest, $fullTestSectionAttempt) {
            // Get all questions including special types
            $questions = $attempt->testSet->questions()
                ->where('question_type', '!=', 'passage')
                ->get()
                ->keyBy('id');
            
            // IELTS standard is always 40 questions
            $totalQuestions = 40; // Fixed for IELTS standard
            
            // Save answers and calculate score
            $correctAnswers = 0;
            $answeredCount = 0;
            
            foreach ($request->answers as $answerKey => $answer) {
                if (empty($answer) && $answer !== '0') {
                    continue;
                }
                
                // Skip if answer is an empty array
                if (is_array($answer) && empty($answer)) {
                    continue;
                }
                
                // Debug log for problematic answers
                if (is_array($answer)) {
                    \Log::info('Processing array answer', [
                        'answer_key' => $answerKey,
                        'answer' => $answer,
                        'answer_type' => gettype($answer)
                    ]);
                }
                
                // Parse answer key for special types
                if (strpos($answerKey, '_') !== false) {
                    // Special type answer: questionId_subIndex
                    list($questionId, $subIndex) = explode('_', $answerKey);
                    $question = $questions->get($questionId);
                    
                    if ($question) {
                        // Check if answer is correct based on type
                        $isCorrect = false;
                        
                        switch ($question->question_type) {
                            case 'matching':
                                if (isset($question->matching_pairs[$subIndex])) {
                                    $correctAnswer = $question->matching_pairs[$subIndex]['right'];
                                    $isCorrect = $this->compareAnswers($answer, $correctAnswer);
                                }
                                break;
                                
                            case 'form_completion':
                                if (isset($question->form_structure['fields'][$subIndex])) {
                                    $correctAnswer = $question->form_structure['fields'][$subIndex]['answer'];
                                    $isCorrect = $this->compareAnswers($answer, $correctAnswer);
                                }
                                break;
                                
                            case 'plan_map_diagram':
                                if (isset($question->diagram_hotspots[$subIndex])) {
                                    $correctAnswer = $question->diagram_hotspots[$subIndex]['answer'];
                                    $isCorrect = $this->compareAnswers($answer, $correctAnswer);
                                }
                                break;
                                
                            case 'drag_drop':
                                // Handle drag & drop answers
                                $sectionData = $question->section_specific_data ?? [];
                                $dropZones = $sectionData['drop_zones'] ?? [];
                                // Extract zone index from subIndex (e.g., 'zone_0' -> 0)
                                $zoneIdx = str_replace('zone_', '', $subIndex);
                                if (isset($dropZones[$zoneIdx])) {
                                    $correctAnswer = $dropZones[$zoneIdx]['answer'];
                                    $isCorrect = $this->compareAnswers($answer, $correctAnswer);
                                }
                                break;
                        }
                        
                        // Save answer - ensure it's properly formatted
                        $answerData = [
                            'sub_index' => $subIndex,
                            'answer' => is_array($answer) ? json_encode($answer) : $answer,
                            'is_correct' => $isCorrect
                        ];
                        
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'answer' => json_encode($answerData),
                            ]
                        );
                        
                        if ($isCorrect) {
                            $correctAnswers++;
                        }
                        $answeredCount++;
                    }
                } else {
                    // Regular question answer
                    $questionId = $answerKey;
                    $question = $questions->get($questionId);
                    
                    if (!$question) {
                        continue;
                    }
                    
                    // Handle drag & drop questions with zone-based answers
                    if (is_array($answer) && isset($answer['zone_0'])) {
                        // This is a drag & drop question with multiple zones
                        $sectionData = $question->section_specific_data ?? [];
                        $dropZones = $sectionData['drop_zones'] ?? [];
                        
                        foreach ($answer as $zoneKey => $zoneAnswer) {
                            if (strpos($zoneKey, 'zone_') === 0 && !empty($zoneAnswer)) {
                                $zoneIdx = str_replace('zone_', '', $zoneKey);
                                $answeredCount++;
                                
                                // Check if correct
                                if (isset($dropZones[$zoneIdx])) {
                                    $correctAnswer = $dropZones[$zoneIdx]['answer'];
                                    if ($this->compareAnswers($zoneAnswer, $correctAnswer)) {
                                        $correctAnswers++;
                                    }
                                }
                            }
                        }
                        
                        // Store the entire answer array as JSON
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'selected_option_id' => null,
                                'answer' => json_encode($answer),
                            ]
                        );
                    }
                    // Handle fill-in-the-blank questions with multiple blanks
                    elseif (is_array($answer) && (isset($answer['blank_1']) || isset($answer['dropdown_1']))) {
                        // This is a multi-blank question
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'selected_option_id' => null,
                                'answer' => json_encode($answer),
                            ]
                        );
                        
                        // Check each blank separately for IELTS scoring
                        $blankResults = $this->checkMultiBlankAnswer($question, $answer);
                        $answeredCount += $blankResults['answered'];
                        $correctAnswers += $blankResults['correct'];
                    } else {
                        // Single answer (option or text)
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'selected_option_id' => is_numeric($answer) ? $answer : null,
                                'answer' => !is_numeric($answer) ? (is_array($answer) ? json_encode($answer) : $answer) : null,
                            ]
                        );
                        
                        // For multiple choice with multiple correct answers, count each correct selection
                        if ($question->question_type === 'multiple_choice') {
                            $correctCount = $question->options->where('is_correct', true)->count();
                            
                            if ($correctCount > 1) {
                                // Multiple correct answers - check if this is an array of selected options
                                if (is_array($answer)) {
                                    foreach ($answer as $selectedOptionId) {
                                        $answeredCount++;
                                        $option = QuestionOption::find($selectedOptionId);
                                        if ($option && $option->is_correct) {
                                            $correctAnswers++;
                                        }
                                    }
                                } else {
                                    // Single selection (shouldn't happen for multiple correct, but handle it)
                                    $answeredCount++;
                                    $option = QuestionOption::find($answer);
                                    if ($option && $option->is_correct) {
                                        $correctAnswers++;
                                    }
                                }
                            } else {
                                // Single correct answer
                                $answeredCount++;
                                if (is_numeric($answer)) {
                                    $option = QuestionOption::find($answer);
                                    if ($option && $option->is_correct) {
                                        $correctAnswers++;
                                    }
                                }
                            }
                        } else {
                            // Other question types
                            $answeredCount++;
                            
                            // Check if correct
                            if ($question->requiresOptions() && is_numeric($answer)) {
                                $option = QuestionOption::find($answer);
                                if ($option && $option->is_correct) {
                                    $correctAnswers++;
                                }
                            } elseif (!$question->requiresOptions()) {
                                // Check text answer
                                if ($this->checkSingleTextAnswer($question, $answer)) {
                                    $correctAnswers++;
                                }
                            }
                        }
                    }
                }
            }
            
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
                'listening'
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
            
            // If part of full test, update full test attempt
            if ($isPartOfFullTest && $fullTestSectionAttempt) {
                $fullTestAttempt = $fullTestSectionAttempt->fullTestAttempt;
                // Ensure we have a valid band score before updating
                $bandScore = $scoreData['band_score'] ?? 0.0;
                if (is_numeric($bandScore)) {
                    $fullTestAttempt->updateSectionScore('listening', (float)$bandScore);
                }
            }
        });
        
        // If part of full test, redirect to section completed screen
        if ($isPartOfFullTest && $fullTestSectionAttempt) {
            $fullTestAttempt = $fullTestSectionAttempt->fullTestAttempt;
            
            return redirect()->route('student.full-test.section-completed', [
                'fullTestAttempt' => $fullTestAttempt->id,
                'section' => 'listening'
            ])->with('success', 'Listening section completed successfully!');
        }
        
        // Regular test completion
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
    
    /**
     * Check multi-blank answer and return results for IELTS scoring
     */
    protected function checkMultiBlankAnswer($question, $studentAnswers): array
    {
        $sectionData = $question->section_specific_data;
        
        if (!$sectionData) {
            return ['answered' => 0, 'correct' => 0];
        }
        
        $answeredCount = 0;
        $correctCount = 0;
        
        // Check blank answers
        if (isset($sectionData['blank_answers']) && is_array($sectionData['blank_answers'])) {
            foreach ($sectionData['blank_answers'] as $num => $correctAnswer) {
                // Try multiple key formats to find student answer
                $studentBlankAnswer = null;
                
                if (isset($studentAnswers['blank_' . $num])) {
                    $studentBlankAnswer = $studentAnswers['blank_' . $num];
                } elseif (isset($studentAnswers[$num])) {
                    $studentBlankAnswer = $studentAnswers[$num];
                }
                
                // Count as answered if not empty
                if (!empty($studentBlankAnswer)) {
                    $answeredCount++;
                    
                    // Check if correct
                    if ($this->compareAnswers($studentBlankAnswer, $correctAnswer)) {
                        $correctCount++;
                    }
                }
            }
        }
        
        // Check dropdown answers
        if (isset($sectionData['dropdown_correct']) && is_array($sectionData['dropdown_correct'])) {
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
        
        return [
            'answered' => $answeredCount,
            'correct' => $correctCount
        ];
    }
    
    /**
     * Check single text answer
     */
    protected function checkSingleTextAnswer($question, $studentAnswer): bool
    {
        // Handle array student answers
        if (is_array($studentAnswer)) {
            \Log::info('checkSingleTextAnswer received array', [
                'question_id' => $question->id,
                'answer' => $studentAnswer
            ]);
            
            // For drag & drop or complex answers, we shouldn't reach here
            // But if we do, extract first value
            $studentAnswer = !empty($studentAnswer) ? reset($studentAnswer) : '';
        }
        
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
     * Check text answer (DEPRECATED - use checkMultiBlankAnswer instead)
     */
    protected function checkTextAnswer($question, $studentAnswer): bool
    {
        // If it's an array, use the new method
        if (is_array($studentAnswer)) {
            $results = $this->checkMultiBlankAnswer($question, $studentAnswer);
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
        // Handle array answers (convert to string first)
        if (is_array($studentAnswer)) {
            \Log::warning('compareAnswers received array for studentAnswer', [
                'student' => $studentAnswer,
                'correct' => $correctAnswer
            ]);
            
            // Extract the actual answer from array
            if (isset($studentAnswer['zone_0'])) {
                $studentAnswer = $studentAnswer['zone_0'];
            } elseif (isset($studentAnswer['blank_1'])) {
                $studentAnswer = $studentAnswer['blank_1'];
            } else {
                $studentAnswer = !empty($studentAnswer) ? reset($studentAnswer) : '';
            }
        }
        
        if (is_array($correctAnswer)) {
            \Log::warning('compareAnswers received array for correctAnswer', [
                'student' => $studentAnswer,
                'correct' => $correctAnswer
            ]);
            $correctAnswer = !empty($correctAnswer) ? reset($correctAnswer) : '';
        }
        
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
        
        return false;
    }

    /**
     * Safely convert answer to string for database storage
     */
    protected function answerToString($answer): ?string
    {
        if (is_null($answer)) {
            return null;
        }
        
        if (is_array($answer)) {
            return json_encode($answer);
        }
        
        if (is_bool($answer)) {
            return $answer ? '1' : '0';
        }
        
        return (string) $answer;
    }
    
    /**
     * Normalize answer for comparison
     */
    protected function normalizeAnswer($answer): string
    {
        // Handle array answers
        if (is_array($answer)) {
            // If it's a drag & drop zone answer array, extract the first value
            if (isset($answer['zone_0'])) {
                $answer = $answer['zone_0'];
            } elseif (isset($answer['blank_1'])) {
                $answer = $answer['blank_1'];
            } else {
                // Get first non-empty value from array
                $answer = !empty($answer) ? reset($answer) : '';
            }
        }
        
        // Handle null
        if (is_null($answer)) {
            return '';
        }
        
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
}