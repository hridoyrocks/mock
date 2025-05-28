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
    /**
     * Display a listing of the available listening tests.
     */
    public function index(): View
    {
        $testSets = TestSet::whereHas('section', function ($query) {
            $query->where('name', 'listening');
        })->where('active', true)->get();
        
        return view('student.test.listening.index', compact('testSets'));
    }
    
    /**
     * Show candidate information confirmation screen.
     */
    public function confirmDetails(TestSet $testSet): View
    {
        // Check if the test belongs to listening section
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        return view('student.test.listening.onboarding.confirm-details', compact('testSet'));
    }

    /**
     * Show the sound check screen.
     */
    public function soundCheck(TestSet $testSet): View
    {
        // Check if the test belongs to listening section
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        return view('student.test.listening.onboarding.sound-check', compact('testSet'));
    }

    /**
     * Show the instructions screen.
     */
    public function instructions(TestSet $testSet): View
    {
        // Check if the test belongs to listening section
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        return view('student.test.listening.onboarding.instructions', compact('testSet'));
    }
    
    /**
     * Start a new listening test.
     */
    public function start(TestSet $testSet): View
    {
        // Check if the test belongs to listening section
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        // Create a new attempt
        $attempt = StudentAttempt::create([
            'user_id' => auth()->id(),
            'test_set_id' => $testSet->id,
            'start_time' => now(),
            'status' => 'in_progress',
        ]);
        
        return view('student.test.listening.test', compact('testSet', 'attempt'));
    }

//hotat kaitaa dile
    public function abandon(Request $request, StudentAttempt $attempt): JsonResponse
{
    // Verify the attempt belongs to the current user
    if ($attempt->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    // Mark attempt as abandoned
    $attempt->update([
        'status' => 'abandoned',
        'end_time' => now(),
    ]);
    
    return response()->json(['success' => true]);
}
    
    /**
     * Submit the listening test answers.
     */
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
{
    // Validate the submission
    $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'nullable',
    ]);
    
    DB::transaction(function () use ($request, $attempt) {
        // Save answers
        foreach ($request->answers as $questionId => $optionId) {
            StudentAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'selected_option_id' => $optionId,
            ]);
        }
        
        // Mark attempt as completed
        $attempt->update([
            'end_time' => now(),
            'status' => 'completed',
        ]);
        
        // Calculate automatic band score for listening
        $correctAnswers = 0;
        $totalQuestions = $attempt->testSet->questions->count();
        
        // Load answers with options to check correctness
        $attempt->load('answers.selectedOption');
        
        foreach ($attempt->answers as $answer) {
            if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                $correctAnswers++;
            }
        }
        
        // Calculate band score using the helper
        $bandScore = \App\Helpers\ScoreCalculator::calculateListeningBandScore($correctAnswers, $totalQuestions);
        
        // Update attempt with band score
        $attempt->update(['band_score' => $bandScore]);
    });
    
    return redirect()->route('student.results.show', $attempt)
        ->with('success', 'Test submitted successfully!');
}
}