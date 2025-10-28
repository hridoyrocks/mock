<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentAttemptController extends Controller
{
    /**
     * Display a listing of the student attempts.
     */
    public function index(Request $request): View
    {
        $query = StudentAttempt::with(['user', 'testSet', 'testSet.section']);
        
        // Filter by section - Fixed to properly handle the filter
        if ($request->filled('section') && $request->section !== '') {
            $query->whereHas('testSet.section', function ($q) use ($request) {
                $q->where('name', $request->section);
            });
        }
        
        // Filter by status - Fixed to properly handle the filter
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by user - Fixed to properly handle the filter
        if ($request->filled('user') && $request->user !== '') {
            $query->where('user_id', $request->user);
        }
        
        // Calculate statistics
        $totalAttempts = StudentAttempt::count();
        $completedCount = StudentAttempt::where('status', 'completed')->count();
        $inProgressCount = StudentAttempt::where('status', 'in_progress')->count();
        $needsEvaluationCount = StudentAttempt::where('status', 'completed')
            ->whereNull('band_score')
            ->whereHas('testSet.section', function ($q) {
                $q->whereIn('name', ['writing', 'speaking']);
            })
            ->count();
        
        $attempts = $query->latest()->paginate(15)->withQueryString();
        
        // Get users for filtering
        $users = User::where('is_admin', false)
            ->orderBy('name')
            ->get();
        
        return view('admin.attempts.index', compact(
            'attempts', 
            'users', 
            'totalAttempts', 
            'completedCount', 
            'inProgressCount', 
            'needsEvaluationCount'
        ));
    }

    /**
     * Display the specified student attempt.
     */
    public function show(StudentAttempt $attempt): View
    {
        $attempt->load(['user', 'testSet', 'testSet.section', 'answers', 'answers.question', 'answers.selectedOption', 'answers.speakingRecording']);
        
        return view('admin.attempts.show', compact('attempt'));
    }

    /**
     * Show the form for evaluating a student attempt.
     */
    public function evaluateForm(StudentAttempt $attempt): View
    {
        $attempt->load(['user', 'testSet', 'testSet.section', 'answers', 'answers.question', 'answers.selectedOption', 'answers.speakingRecording']);
        
        return view('admin.attempts.evaluate', compact('attempt'));
    }

    /**
     * Process the evaluation of a student attempt.
     */
    public function evaluate(Request $request, StudentAttempt $attempt): RedirectResponse
    {
        $request->validate([
            'band_score' => 'required|numeric|min:0|max:9',
            'feedback' => 'nullable|string',
        ]);
        
        $attempt->update([
            'band_score' => $request->band_score,
            'feedback' => $request->feedback,
        ]);
        
        return redirect()->route('admin.attempts.show', $attempt)
            ->with('success', 'Attempt evaluated successfully.');
    }

    /**
     * Bulk delete student attempts.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'attempt_ids' => 'required|array',
            'attempt_ids.*' => 'exists:student_attempts,id'
        ]);
        
        $deletedCount = 0;
        $failedCount = 0;
        
        foreach ($request->attempt_ids as $attemptId) {
            try {
                $attempt = StudentAttempt::find($attemptId);
                if ($attempt) {
                    // Delete related answers
                    $attempt->answers()->delete();
                    // Delete the attempt
                    $attempt->delete();
                    $deletedCount++;
                }
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error('Failed to delete attempt: ' . $attemptId, ['error' => $e->getMessage()]);
            }
        }
        
        $message = "Successfully deleted {$deletedCount} attempt(s).";
        if ($failedCount > 0) {
            $message .= " Failed to delete {$failedCount} attempt(s).";
            return back()->with('warning', $message);
        }
        
        return redirect()->route('admin.attempts.index')
            ->with('success', $message);
    }
    
    /**
     * Remove the specified student attempt from storage.
     */
    public function destroy(StudentAttempt $attempt): RedirectResponse
    {
        $attempt->delete();
        
        return redirect()->route('admin.attempts.index')
            ->with('success', 'Attempt deleted successfully.');
    }
}