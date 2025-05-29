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
            // Save answers
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
                }
            }
            
            // Mark attempt as completed
            $attempt->update([
                'end_time' => now(),
                'status' => 'completed',
            ]);
            
            // Calculate band score
            $correctAnswers = 0;
            $totalQuestions = $attempt->testSet->questions->count();
            
            $attempt->load('answers.selectedOption');
            
            foreach ($attempt->answers as $answer) {
                if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                    $correctAnswers++;
                }
            }
            
            $bandScore = \App\Helpers\ScoreCalculator::calculateListeningBandScore($correctAnswers, $totalQuestions);
            $attempt->update(['band_score' => $bandScore]);
        });
        
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
}