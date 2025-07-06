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

class ListeningTestController extends Controller
{
    public function index(): View
    {
        $testSets = TestSet::whereHas('section', function ($query) {
            $query->where('name', 'listening');
        })->where('active', true)->get();
        
        return view('student.test.listening.index', compact('testSets'));
    }
    
    public function confirmDetails(TestSet $testSet)
    {
        if ($testSet->section->name !== 'listening') {
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
        
        return view('student.test.listening.onboarding.confirm-details', compact('testSet'));
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
        
        return view('student.test.listening.test', compact('testSet', 'attempt'));
    }
    
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
    {
        // Verify the attempt belongs to the current user and is not already completed
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return redirect()->route('student.listening.index')
                ->with('error', 'Invalid attempt or test already submitted.');
        }
        
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable',
        ]);
        
        DB::transaction(function () use ($request, $attempt) {
            // Get total questions count (excluding passages if any)
            $totalQuestions = $attempt->testSet->questions()
                ->where('question_type', '!=', 'passage')
                ->count();
            
            // Save answers and count how many were answered
            $answeredCount = 0;
            foreach ($request->answers as $questionId => $optionId) {
                if ($optionId) {
                    StudentAnswer::updateOrCreate(
                        [
                            'attempt_id' => $attempt->id,
                            'question_id' => $questionId,
                        ],
                        [
                            'selected_option_id' => $optionId,
                        ]
                    );
                    $answeredCount++;
                }
            }
            
            // Mark attempt as completed
            $attempt->update([
                'end_time' => now(),
                'status' => 'completed',
            ]);
            
            // INCREMENT TEST COUNT
            auth()->user()->incrementTestCount();
            
            // Calculate score with new logic
            $correctAnswers = 0;
            
            // Load answers with their selected options
            $attempt->load('answers.selectedOption');
            
            foreach ($attempt->answers as $answer) {
                if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                    $correctAnswers++;
                }
            }
            
            // Use the new partial test score calculation
            $scoreData = \App\Helpers\ScoreCalculator::calculatePartialTestScore(
                $correctAnswers, 
                $answeredCount, 
                $totalQuestions,
                'listening'
            );
            
            // Store band score and additional data
            $attempt->update([
                'band_score' => $scoreData['band_score'],
                'completion_rate' => $scoreData['completion_percentage'],
                'confidence_level' => $scoreData['confidence'],
                'is_complete_attempt' => $scoreData['is_reliable'],
                // Add the missing fields
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
}