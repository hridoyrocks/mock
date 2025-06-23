<?php

namespace App\Http\Controllers;

use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Services\AI\AIEvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AIEvaluationController extends Controller
{
    protected $aiService;

    public function __construct(AIEvaluationService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Evaluate writing with AI.
     */
    public function evaluateWriting(Request $request, StudentAttempt $attempt)
    {
        // Check if user owns this attempt
        if ($attempt->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this attempt.');
        }

        // Check if user has access to AI evaluation
        if (!auth()->user()->hasFeature('ai_writing_evaluation')) {
            return response()->json([
                'error' => 'AI evaluation is not available in your current plan.'
            ], 403);
        }

        // Check if attempt is for writing section
        if ($attempt->section->type !== 'writing') {
            return response()->json([
                'error' => 'This attempt is not for writing section.'
            ], 400);
        }

        try {
            // Get writing answers
            $answers = $attempt->answers()
                ->with('question')
                ->get();

            $evaluations = [];

            foreach ($answers as $answer) {
                if (empty($answer->answer_text)) {
                    continue;
                }

                // Check if already evaluated
                if ($answer->ai_evaluation) {
                    $evaluations[] = $answer->ai_evaluation;
                    continue;
                }

                // Evaluate with AI
                $evaluation = $this->aiService->evaluateWriting(
                    $answer->answer_text,
                    $answer->question->title,
                    $answer->question->part // Task 1 or Task 2
                );

                // Store evaluation
                $answer->update([
                    'ai_evaluation' => $evaluation,
                    'ai_band_score' => $evaluation['band_score'] ?? null,
                    'ai_evaluated_at' => now(),
                ]);

                $evaluations[] = $evaluation;

                // Increment AI usage counter
                auth()->user()->incrementAIEvaluationCount();
            }

            // Calculate overall band score
            $overallBand = $this->calculateOverallBand($evaluations);

            // Update attempt with AI scores
            $attempt->update([
                'ai_band_score' => $overallBand,
                'ai_evaluated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'evaluations' => $evaluations,
                'overall_band' => $overallBand,
                'message' => 'Writing evaluation completed successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('AI Writing evaluation failed', [
                'attempt_id' => $attempt->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to evaluate writing. Please try again later.'
            ], 500);
        }
    }

    /**
     * Evaluate speaking with AI.
     */
    public function evaluateSpeaking(Request $request, StudentAttempt $attempt)
    {
        // Check if user owns this attempt
        if ($attempt->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this attempt.');
        }

        // Check if user has access to AI evaluation
        if (!auth()->user()->hasFeature('ai_speaking_evaluation')) {
            return response()->json([
                'error' => 'AI speaking evaluation is not available in your current plan.'
            ], 403);
        }

        // Check if attempt is for speaking section
        if ($attempt->section->type !== 'speaking') {
            return response()->json([
                'error' => 'This attempt is not for speaking section.'
            ], 400);
        }

        try {
            // Get speaking answers with audio
            $answers = $attempt->answers()
                ->with('question')
                ->whereNotNull('audio_path')
                ->get();

            $evaluations = [];

            foreach ($answers as $answer) {
                // Check if already evaluated
                if ($answer->ai_evaluation) {
                    $evaluations[] = $answer->ai_evaluation;
                    continue;
                }

                // Transcribe and evaluate with AI
                $evaluation = $this->aiService->evaluateSpeaking(
                    $answer->audio_path,
                    $answer->question->title,
                    $answer->question->part
                );

                // Store evaluation
                $answer->update([
                    'ai_evaluation' => $evaluation,
                    'ai_band_score' => $evaluation['band_score'] ?? null,
                    'ai_evaluated_at' => now(),
                    'transcription' => $evaluation['transcription'] ?? null,
                ]);

                $evaluations[] = $evaluation;

                // Increment AI usage counter
                auth()->user()->incrementAIEvaluationCount();
            }

            // Calculate overall band score
            $overallBand = $this->calculateOverallBand($evaluations);

            // Update attempt with AI scores
            $attempt->update([
                'ai_band_score' => $overallBand,
                'ai_evaluated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'evaluations' => $evaluations,
                'overall_band' => $overallBand,
                'message' => 'Speaking evaluation completed successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('AI Speaking evaluation failed', [
                'attempt_id' => $attempt->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to evaluate speaking. Please try again later.'
            ], 500);
        }
    }

    /**
     * Get AI evaluation for an attempt.
     */
    public function getEvaluation($attemptId)
    {
        $attempt = StudentAttempt::findOrFail($attemptId);

        // Check if user owns this attempt
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $evaluations = $attempt->answers()
            ->whereNotNull('ai_evaluation')
            ->get()
            ->map(function ($answer) {
                return [
                    'question_id' => $answer->question_id,
                    'question_title' => $answer->question->title,
                    'part' => $answer->question->part,
                    'evaluation' => $answer->ai_evaluation,
                    'band_score' => $answer->ai_band_score,
                    'evaluated_at' => $answer->ai_evaluated_at,
                ];
            });

        return response()->json([
            'attempt_id' => $attempt->id,
            'section' => $attempt->section->name,
            'overall_band' => $attempt->ai_band_score,
            'evaluations' => $evaluations,
            'evaluated_at' => $attempt->ai_evaluated_at,
        ]);
    }

    /**
     * Calculate overall band score from evaluations.
     */
    private function calculateOverallBand(array $evaluations): float
    {
        if (empty($evaluations)) {
            return 0;
        }

        $totalScore = 0;
        $count = 0;

        foreach ($evaluations as $evaluation) {
            if (isset($evaluation['band_score'])) {
                $totalScore += $evaluation['band_score'];
                $count++;
            }
        }

        if ($count === 0) {
            return 0;
        }

        // Round to nearest 0.5
        $average = $totalScore / $count;
        return round($average * 2) / 2;
    }
}