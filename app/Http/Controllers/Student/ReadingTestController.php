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
    public function confirmDetails(TestSet $testSet): View
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
    public function start(TestSet $testSet): View
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
            // Save answers
            foreach ($request->answers as $questionId => $answer) {
                if (is_array($answer)) {
                    // For checkbox/multiple selection questions
                    foreach ($answer as $optionId) {
                        StudentAnswer::create([
                            'attempt_id' => $attempt->id,
                            'question_id' => $questionId,
                            'selected_option_id' => $optionId,
                        ]);
                    }
                } else {
                    // For single answer questions (radio, text input)
                    StudentAnswer::updateOrCreate(
                        [
                            'attempt_id' => $attempt->id,
                            'question_id' => $questionId,
                        ],
                        [
                            'selected_option_id' => is_numeric($answer) ? $answer : null,
                            'answer' => !is_numeric($answer) ? $answer : null,
                        ]
                    );
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
                // Count only questions that have options (not text answers)
                if ($answer->question->options->count() > 0) {
                    $totalQuestions++;
                    if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                        $correctAnswers++;
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
}