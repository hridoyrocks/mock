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
        
        // Filter by section
        if ($request->has('section')) {
            $query->whereHas('testSet.section', function ($q) use ($request) {
                $q->where('name', $request->section);
            });
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by user
        if ($request->has('user')) {
            $query->where('user_id', $request->user);
        }
        
        $attempts = $query->latest()->paginate(15);
        
        // Get users for filtering
        $users = User::where('is_admin', false)->get();
        
        return view('admin.attempts.index', compact('attempts', 'users'));
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
     * Remove the specified student attempt from storage.
     */
    public function destroy(StudentAttempt $attempt): RedirectResponse
    {
        $attempt->delete();
        
        return redirect()->route('admin.attempts.index')
            ->with('success', 'Attempt deleted successfully.');
    }
}