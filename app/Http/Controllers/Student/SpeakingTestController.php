<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\SpeakingRecording;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\TestSet;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SpeakingTestController extends Controller
{
    use HandlesFileUploads;
    
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
        
        // Get all attempts for this test
        $attempts = StudentAttempt::getAllAttemptsForUserAndTest(auth()->id(), $testSet->id);
        
        // Show previous attempts if any exist
        $latestAttempt = $attempts->first();
        $canRetake = $latestAttempt && $latestAttempt->canRetake();
        
        return view('student.test.speaking.onboarding.confirm-details', compact('testSet', 'attempts', 'canRetake'));
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
            $this->deleteFile(
                $answer->speakingRecording->file_path, 
                $answer->speakingRecording->storage_disk
            );
            $answer->speakingRecording->delete();
        }
        
        // Upload recording using trait (to R2 if configured)
        $result = $this->uploadFile(
            $request->file('recording'),
            'speaking-recordings/attempt-' . $attempt->id
        );
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload recording'
            ]);
        }
        
        // Create a new recording with CDN URL
        SpeakingRecording::create([
            'answer_id' => $answer->id,
            'file_path' => $result['path'],
            'file_url' => $result['url'],
            'storage_disk' => $result['disk'],
            'file_size' => $result['size'],
            'mime_type' => $result['mime_type']
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Recording saved successfully',
            'storage' => strtoupper($result['disk']),
            'url' => $result['url']
        ]);
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
    
    // Calculate completion rate
    $totalQuestions = $attempt->testSet->questions()->count();
    $recordedAnswers = $attempt->answers()
        ->whereHas('speakingRecording')
        ->count();
    
    $completionRate = $totalQuestions > 0 ? round(($recordedAnswers / $totalQuestions) * 100, 2) : 0;
    
    // Mark attempt as completed
    $attempt->update([
        'end_time' => now(),
        'status' => 'completed',
        'completion_rate' => $completionRate,
        'total_questions' => $totalQuestions,
        'answered_questions' => $recordedAnswers,
        'is_complete_attempt' => ($completionRate >= 80),
    ]);
    
    // INCREMENT TEST COUNT
    auth()->user()->incrementTestCount();
    
    return redirect()->route('student.results.show', $attempt)
        ->with('success', 'Test submitted successfully!');
}
}