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
            // Load questions with their types to determine how to save answers
            $attempt->load('testSet.questions.options');
            $questions = $attempt->testSet->questions->keyBy('id');
            
            // Save answers
            foreach ($request->answers as $questionId => $answer) {
                // Skip if no answer provided
                if (empty($answer)) {
                    continue;
                }
                
                $question = $questions->get($questionId);
                if (!$question) {
                    continue;
                }
                
                if (is_array($answer)) {
                    // Handle array answers (could be blanks or multiple selections)
                    // Check if it's a fill-in-the-blanks question
                    if (isset($answer['blank_1']) || isset($answer['dropdown_1'])) {
                        // This is a fill-in-the-blanks question with multiple blanks
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
                        // This is a multiple selection question
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
                    // Single answer - determine if it's an option ID or text
                    $hasOptions = $question->options->count() > 0;
                    
                    if ($hasOptions && is_numeric($answer)) {
                        // This is an option ID (multiple choice, true/false, etc.)
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
                        // This is a text answer (short answer, fill in the blanks, etc.)
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
            
            // Calculate automatic band score for reading
            $correctAnswers = 0;
            $totalQuestions = 0;
            
            // Load answers with options to check correctness
            $attempt->load('answers.selectedOption', 'answers.question');
            
            foreach ($attempt->answers as $answer) {
                $question = $answer->question;
                
                // Check if this is a question with options (multiple choice, true/false, etc.)
                if ($question->options->count() > 0) {
                    $totalQuestions++;
                    if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                        $correctAnswers++;
                    }
                } else {
                    // This is a text-based answer (short answer, fill-in-the-blanks)
                    $totalQuestions++;
                    
                    // Check if answer is JSON (fill-in-the-blanks with multiple blanks)
                    $studentAnswer = $answer->answer;
                    if ($this->isJson($studentAnswer)) {
                        // Handle fill-in-the-blanks with multiple blanks
                        $studentAnswers = json_decode($studentAnswer, true);
                        $correctData = $question->section_specific_data;
                        
                        if ($correctData) {
                            $allBlanksCorrect = true;
                            
                            // Check each blank answer
                            if (isset($correctData['blank_answers'])) {
                                foreach ($correctData['blank_answers'] as $blankNum => $correctAnswer) {
                                    $studentBlankAnswer = $studentAnswers['blank_' . $blankNum] ?? '';
                                    if (!$this->checkAnswer($studentBlankAnswer, $correctAnswer)) {
                                        $allBlanksCorrect = false;
                                        break;
                                    }
                                }
                            }
                            
                            // Check dropdown answers
                            if (isset($correctData['dropdown_correct'])) {
                                foreach ($correctData['dropdown_correct'] as $dropdownNum => $correctIndex) {
                                    $studentDropdownAnswer = $studentAnswers['dropdown_' . $dropdownNum] ?? '';
                                    $dropdownOptions = $correctData['dropdown_options'][$dropdownNum] ?? '';
                                    
                                    if ($dropdownOptions) {
                                        $options = array_map('trim', explode(',', $dropdownOptions));
                                        $correctOption = $options[$correctIndex] ?? '';
                                        
                                        if (!$this->checkAnswer($studentDropdownAnswer, $correctOption)) {
                                            $allBlanksCorrect = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            
                            if ($allBlanksCorrect) {
                                $correctAnswers++;
                            }
                        }
                    } else {
                        // Single text answer - check against correct answer
                        // This would need to be implemented based on how correct answers are stored
                        // For now, we'll skip automatic checking of single text answers
                    }
                }
            }
            
            // Calculate band score using the helper
            if ($totalQuestions > 0) {
                $bandScore = \App\Helpers\ScoreCalculator::calculateReadingBandScore($correctAnswers, $totalQuestions);
                
                // Update attempt with band score
                $attempt->update(['band_score' => $bandScore]);
            }
        });
        
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
    
    /**
     * Check if a string is valid JSON
     */
    private function isJson($string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    /**
     * Check if student answer matches correct answer (case-insensitive, trimmed)
     */
    private function checkAnswer($studentAnswer, $correctAnswer): bool
    {
        // Normalize both answers
        $studentAnswer = strtolower(trim($studentAnswer));
        $correctAnswer = strtolower(trim($correctAnswer));
        
        // Exact match
        if ($studentAnswer === $correctAnswer) {
            return true;
        }
        
        // You can add more flexible matching here if needed
        // For example, removing punctuation, checking synonyms, etc.
        
        return false;
    }
}