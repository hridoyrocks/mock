<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display a listing of the student's results.
     */
    public function index(): View
    {
        $attempts = StudentAttempt::where('user_id', auth()->id())
            ->with(['testSet', 'testSet.section'])
            ->latest()
            ->paginate(10);
        
        return view('student.results.index', compact('attempts'));
    }
    
    /**
     * Display the specified result.
     */
    public function show(StudentAttempt $attempt): View
    {
        // Ensure the attempt belongs to the authenticated user
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $attempt->load(['testSet', 'testSet.section', 'answers', 'answers.question', 'answers.selectedOption']);
        
        // For automatically scored sections (Listening and Reading)
        if (in_array($attempt->testSet->section->name, ['listening', 'reading'])) {
            $correctAnswers = 0;
            $totalQuestions = $attempt->answers->count();
            
            foreach ($attempt->answers as $answer) {
                // Check if the selected option is correct
                if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                    $correctAnswers++;
                }
            }
            
            // Calculate accuracy percentage
            $accuracy = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
            
            return view('student.results.show', compact('attempt', 'correctAnswers', 'totalQuestions', 'accuracy'));
        }
        
        // For manually evaluated sections (Writing and Speaking)
        return view('student.results.show', compact('attempt'));
    }
}