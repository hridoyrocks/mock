<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\SpeakingRecording;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\TestSet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SpeakingTestController extends Controller
{
    /**
     * Display a listing of the available speaking tests.
     */
    public function index(): View
    {
        $testSets = TestSet::whereHas('section', function ($query) {
            $query->where('name', 'speaking');
        })->where('active', true)->get();
        
        return view('student.test.speaking.index', compact('testSets'));
    }
    
    /**
     * Show candidate information confirmation screen.
     */
    public function confirmDetails(TestSet $testSet)
    {
        // Check if the test belongs to speaking section
        if ($testSet->section->name !== 'speaking') {
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
        
        return view('student.test.speaking.onboarding.confirm-details', compact('testSet'));
    }

    /**
     * Show the microphone check screen.
     */
    public function microphoneCheck(TestSet $testSet): View
    {
        // Check if the test belongs to speaking section
        if ($testSet->section->name !== 'speaking') {
            abort(404);
        }
        
        return view('student.test.speaking.onboarding.microphone-check', compact('testSet'));
    }

    /**
     * Show the instructions screen.
     */
    public function instructions(TestSet $testSet): View
    {
        // Check if the test belongs to speaking section
        if ($testSet->section->name !== 'speaking') {
            abort(404);
        }
        
        return view('student.test.speaking.onboarding.instructions', compact('testSet'));
    }
    
    /**
     * Start a new speaking test.
     */
    public function start(TestSet $testSet)
    {
        // Check if the test belongs to speaking section
        if ($testSet->section->name !== 'speaking') {
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
            
            // Pre-create answer records
            foreach ($testSet->questions as $question) {
                StudentAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ]);
            }
        }
        
        // Load attempt with answers
        $attempt->load('answers.speakingRecording');
        
        return view('student.test.speaking.test', compact('testSet', 'attempt'));
    }
    
    /**
     * Save the recorded audio.
     */
    public function record(Request $request, StudentAttempt $attempt, Question $question): JsonResponse
    {
        // Verify the attempt belongs to the current user and is not completed
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Invalid attempt']);
        }
        
        $request->validate([
            'recording' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav,webm',
        ]);
        
        // Find the answer for this question
        $answer = StudentAnswer::firstOrCreate([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
        ]);
        
        // Check if there's an existing recording
        if ($answer->speakingRecording) {
            // Delete old recording
            Storage::disk('public')->delete($answer->speakingRecording->file_path);
            $answer->speakingRecording->delete();
        }
        
        // Store the recording
        $filePath = $request->file('recording')->store('speaking_recordings', 'public');
        
        // Create a new recording
        SpeakingRecording::create([
            'answer_id' => $answer->id,
            'file_path' => $filePath,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Submit the speaking test.
     */
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
    {
        // Verify the attempt belongs to the current user and is not already completed
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return redirect()->route('student.speaking.index')
                ->with('error', 'Invalid attempt or test already submitted.');
        }
        
        // Mark attempt as completed
        $attempt->update([
            'end_time' => now(),
            'status' => 'completed',
        ]);
        
        // INCREMENT TEST COUNT - NEW ADDITION
        auth()->user()->incrementTestCount();
        
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
}