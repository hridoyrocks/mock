<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\HumanEvaluationRequest;
use App\Models\HumanEvaluation;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Teacher dashboard
     */
    public function dashboard()
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        
        // Get statistics
        $stats = [
            'pending' => $teacher->evaluationRequests()->where('status', 'assigned')->count(),
            'in_progress' => $teacher->evaluationRequests()->where('status', 'in_progress')->count(),
            'completed_today' => $teacher->evaluationRequests()
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'total_completed' => $teacher->evaluationRequests()->where('status', 'completed')->count(),
            'earnings_this_month' => $teacher->evaluationRequests()
                ->where('status', 'completed')
                ->whereMonth('completed_at', now()->month)
                ->sum('tokens_used')
        ];
        
        // Get recent evaluations
        $recentEvaluations = $teacher->evaluationRequests()
            ->with(['studentAttempt.testSet.section', 'student'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('teacher.dashboard', compact('teacher', 'stats', 'recentEvaluations'));
    }
    
    /**
     * List of pending evaluations
     */
    public function pending()
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        
        $evaluations = $teacher->evaluationRequests()
            ->whereIn('status', ['assigned', 'in_progress'])
            ->with(['studentAttempt.testSet.section', 'student'])
            ->orderBy('priority', 'desc')
            ->orderBy('deadline_at', 'asc')
            ->paginate(20);
        
        return view('teacher.evaluations.pending', compact('evaluations'));
    }
    
    /**
     * Show evaluation details for grading
     */
    public function show(HumanEvaluationRequest $evaluationRequest)
    {
        // Ensure this evaluation belongs to the teacher
        if ($evaluationRequest->teacher->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Load necessary data
        $evaluationRequest->load([
            'studentAttempt.testSet.section',
            'studentAttempt.testSet.questions',
            'studentAttempt.answers.question',
            'studentAttempt.answers.speakingRecording'
        ]);
        
        // Mark as in progress if still assigned
        if ($evaluationRequest->status === 'assigned') {
            $evaluationRequest->update(['status' => 'in_progress']);
        }
        
        return view('teacher.evaluations.show', compact('evaluationRequest'));
    }
    
    /**
     * Submit evaluation
     */
    public function submit(Request $request, HumanEvaluationRequest $evaluationRequest)
    {
        // Ensure this evaluation belongs to the teacher
        if ($evaluationRequest->teacher->user_id !== auth()->id()) {
            abort(403);
        }
        
        $sectionName = $evaluationRequest->studentAttempt->testSet->section->name;
        
        // Validate based on section type
        if ($sectionName === 'writing') {
            $request->validate([
                'task_scores' => 'required|array',
                'task_scores.*.score' => 'required|numeric|min:0|max:9',
                'task_scores.*.task_achievement' => 'required|numeric|min:0|max:9',
                'task_scores.*.coherence_cohesion' => 'required|numeric|min:0|max:9',
                'task_scores.*.lexical_resource' => 'required|numeric|min:0|max:9',
                'task_scores.*.grammar' => 'required|numeric|min:0|max:9',
                'task_scores.*.feedback' => 'required|string',
                'overall_band_score' => 'required|numeric|min:0|max:9',
                'strengths' => 'required|array',
                'improvements' => 'required|array',
                'error_markings' => 'nullable|json'
            ]);
        } else { // speaking
            $request->validate([
                'task_scores' => 'required|array',
                'task_scores.*.score' => 'required|numeric|min:0|max:9',
                'task_scores.*.fluency_coherence' => 'required|numeric|min:0|max:9',
                'task_scores.*.lexical_resource' => 'required|numeric|min:0|max:9',
                'task_scores.*.grammar' => 'required|numeric|min:0|max:9',
                'task_scores.*.pronunciation' => 'required|numeric|min:0|max:9',
                'task_scores.*.feedback' => 'required|string',
                'overall_band_score' => 'required|numeric|min:0|max:9',
                'strengths' => 'required|array',
                'improvements' => 'required|array'
            ]);
        }
        
        DB::transaction(function () use ($request, $evaluationRequest) {
            // Create human evaluation
            $humanEvaluation = HumanEvaluation::create([
                'evaluation_request_id' => $evaluationRequest->id,
                'evaluator_id' => auth()->id(),
                'task_scores' => $request->task_scores,
                'overall_band_score' => $request->overall_band_score,
                'detailed_feedback' => $request->task_scores, // Store detailed feedback with scores
                'strengths' => $request->strengths,
                'improvements' => $request->improvements,
                'evaluated_at' => now()
            ]);
            
            // Save error markings if provided
            if ($request->has('error_markings') && $request->error_markings) {
                $errorMarkings = json_decode($request->error_markings, true);
                
                foreach ($errorMarkings as $marking) {
                    \App\Models\EvaluationErrorMarking::create([
                        'human_evaluation_id' => $humanEvaluation->id,
                        'student_answer_id' => $marking['answerId'],
                        'task_number' => $marking['taskNumber'],
                        'marked_text' => $marking['text'],
                        'start_position' => $marking['startOffset'],
                        'end_position' => $marking['endOffset'],
                        'error_type' => $marking['errorType'],
                        'comment' => $marking['comment'] ?? $marking['note'] ?? null // Save teacher's note/comment
                    ]);
                }
            }
            
            // Mark request as completed
            $evaluationRequest->markCompleted();
            
            // Update student attempt with human evaluation band score
            $evaluationRequest->studentAttempt->update([
                'band_score' => $request->overall_band_score
            ]);
        });
        
        return redirect()->route('teacher.evaluations.pending')
            ->with('success', 'Evaluation submitted successfully!');
    }
    
    /**
     * View completed evaluations
     */
    public function completed()
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        
        $evaluations = $teacher->evaluationRequests()
            ->where('status', 'completed')
            ->with(['studentAttempt.testSet.section', 'student', 'humanEvaluation'])
            ->latest('completed_at')
            ->paginate(20);
        
        return view('teacher.evaluations.completed', compact('evaluations'));
    }
    
    /**
     * Toggle teacher availability
     */
    public function toggleAvailability()
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        
        $teacher->update([
            'is_available' => !$teacher->is_available
        ]);
        
        return redirect()->back()->with('success', 
            $teacher->is_available ? 'You are now available for evaluations.' : 'You are now unavailable for evaluations.'
        );
    }
}
