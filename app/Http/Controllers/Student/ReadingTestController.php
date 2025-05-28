<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\TestSet;
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
     * Start a new reading test.
     */
    public function start(TestSet $testSet): View
    {
        // Check if the test belongs to reading section
        if ($testSet->section->name !== 'reading') {
            abort(404);
        }
        
        // Create a new attempt
        $attempt = StudentAttempt::create([
            'user_id' => auth()->id(),
            'test_set_id' => $testSet->id,
            'start_time' => now(),
            'status' => 'in_progress',
        ]);
        
        return view('student.test.reading.test', compact('testSet', 'attempt'));
    }
    
    /**
     * Submit the reading test answers.
     */
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
    {
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
                    StudentAnswer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                        'selected_option_id' => is_numeric($answer) ? $answer : null,
                        'answer' => !is_numeric($answer) ? $answer : null,
                    ]);
                }
            }
            
            // Mark attempt as completed
            $attempt->update([
                'end_time' => now(),
                'status' => 'completed',
            ]);
        });
        
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
}