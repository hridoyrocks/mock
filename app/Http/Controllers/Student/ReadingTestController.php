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
        
        // Check if user has already completed this test
        $existingAttempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'completed')
            ->first();
            
        if ($existingAttempt) {
            return redirect()->route('student.results.show', $existingAttempt)
                ->with('info', 'You have already completed this test.');
        }
        
        return view('student.test.reading.onboarding.confirm-details', compact('testSet'));
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
        
        // Check if user has already completed this test
        $existingAttempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'completed')
            ->first();
            
        if ($existingAttempt) {
            return redirect()->route('student.results.show', $existingAttempt)
                ->with('info', 'You have already completed this test.');
        }
        
        // Check if there's an ongoing attempt
        $attempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'in_progress')
            ->first();
            
        if (!$attempt) {
            // Create a new attempt only if no ongoing attempt exists
            $attempt = StudentAttempt::create([
                'user_id' => auth()->id(),
                'test_set_id' => $testSet->id,
                'start_time' => now(),
                'status' => 'in_progress',
            ]);
        }
        
        return view('student.test.reading.test', compact('testSet', 'attempt'));
    }
    
    /**
     * Submit the reading test answers.
     */
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
    {
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
            // Get total questions count (excluding passages)
            $totalQuestions = $attempt->testSet->questions()
                ->where('question_type', '!=', 'passage')
                ->count();
            
            // Load questions with their types to determine how to save answers
            $attempt->load('testSet.questions.options');
            $questions = $attempt->testSet->questions->keyBy('id');
            
            // Track answered questions
            $answeredCount = 0;
            
            // Save answers
            foreach ($request->answers as $questionId => $answer) {
                // Skip if no answer provided
                if (empty($answer)) {
                    continue;
                }
                
                $question = $questions->get($questionId);
                if (!$question || $question->question_type === 'passage') {
                    continue;
                }
                
                $answeredCount++;
                
                if (is_array($answer)) {
                    // Handle array answers (blanks or multiple selections)
                    if (isset($answer['blank_1']) || isset($answer['dropdown_1'])) {
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
                    } else {
                        // Multiple selection question
                        foreach ($answer as $value) {
                            if (is_numeric($value)) {
                                StudentAnswer::create([
                                    'attempt_id' => $attempt->id,
                                    'question_id' => $questionId,
                                    'selected_option_id' => $value,
                                    'answer' => null,
                                ]);
                            }
                        }
                    }
                } else {
                    // Single answer
                    $hasOptions = $question->options->count() > 0;
                    
                    if ($hasOptions && is_numeric($answer)) {
                        // Option ID (multiple choice, true/false, etc.)
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
            
            // Calculate score
            $correctAnswers = 0;
            
            // Load answers with options to check correctness
            $attempt->load('answers.selectedOption', 'answers.question');
            
            foreach ($attempt->answers as $answer) {
                $question = $answer->question;
                
                // Skip passage type questions
                if ($question->question_type === 'passage') {
                    continue;
                }
                
                // Check if this is a question with options
                if ($question->options->count() > 0) {
                    if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                        $correctAnswers++;
                    }
                } else {
                    // Text-based answer checking
                    if ($this->checkTextAnswer($answer)) {
                        $correctAnswers++;
                    }
                }
            }
            
            // Use the new partial test score calculation
            $scoreData = \App\Helpers\ScoreCalculator::calculatePartialTestScore(
                $correctAnswers, 
                $answeredCount, 
                $totalQuestions,
                'reading'
            );
            
            // Store band score and additional data
            $attempt->update([
                'band_score' => $scoreData['band_score'],
                'completion_rate' => $scoreData['completion_percentage'],
                'confidence_level' => $scoreData['confidence'],
                'is_complete_attempt' => $scoreData['is_reliable'],
                'total_questions' => $totalQuestions,
                'answered_questions' => $answeredCount,
                'correct_answers' => $correctAnswers
            ]);
            
            // Store score data in session for display
            session()->flash('score_details', $scoreData);
        });
        
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
    
    /**
     * Check if a text-based answer is correct
     */
    protected function checkTextAnswer($answer): bool
    {
        $question = $answer->question;
        $studentAnswer = $answer->answer;
        
        // Debug log
        \Log::info('Checking answer', [
            'question_id' => $question->id,
            'student_answer' => $studentAnswer,
            'question_data' => $question->section_specific_data
        ]);
        
        // Handle JSON answers (fill-in-the-blanks with multiple blanks)
        if ($this->isJson($studentAnswer)) {
            $studentAnswers = json_decode($studentAnswer, true);
            $sectionData = $question->section_specific_data;
            
            if (!$sectionData) {
                \Log::warning('No section data for question', ['question_id' => $question->id]);
                return false;
            }
            
            $allCorrect = true;
            
            // Check blank answers
            if (isset($sectionData['blank_answers']) && is_array($sectionData['blank_answers'])) {
                foreach ($sectionData['blank_answers'] as $num => $correctAnswer) {
                    // Try multiple key formats
                    $studentBlankAnswer = null;
                    
                    // Try blank_1 format
                    if (isset($studentAnswers['blank_' . $num])) {
                        $studentBlankAnswer = $studentAnswers['blank_' . $num];
                    }
                    // Try numeric key
                    elseif (isset($studentAnswers[$num])) {
                        $studentBlankAnswer = $studentAnswers[$num];
                    }
                    // Try string numeric key
                    elseif (isset($studentAnswers[(string)$num])) {
                        $studentBlankAnswer = $studentAnswers[(string)$num];
                    }
                    
                    \Log::info('Checking blank', [
                        'blank_num' => $num,
                        'student_answer' => $studentBlankAnswer,
                        'correct_answer' => $correctAnswer,
                        'all_student_answers' => $studentAnswers
                    ]);
                    
                    if (!$this->compareAnswers($studentBlankAnswer ?? '', $correctAnswer)) {
                        $allCorrect = false;
                        \Log::info('Blank incorrect', ['blank' => $num]);
                        break;
                    }
                }
            }
            
            // Check dropdown answers
            if ($allCorrect && isset($sectionData['dropdown_correct']) && is_array($sectionData['dropdown_correct'])) {
                foreach ($sectionData['dropdown_correct'] as $num => $correctIndex) {
                    // Try multiple key formats
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
            
            return $allCorrect;
        }
        
        // Single text answer
        return false;
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
    protected function isJson($string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    /**
     * Check if student answer matches correct answer (case-insensitive, trimmed)
     * This method is still here for backward compatibility but redirects to compareAnswers
     */
    protected function checkAnswer($studentAnswer, $correctAnswer): bool
    {
        return $this->compareAnswers($studentAnswer, $correctAnswer);
    }
}