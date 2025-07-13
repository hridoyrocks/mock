<?php

namespace App\Http\Controllers;

use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Services\AI\AIEvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

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
    public function evaluateWriting(Request $request)
    {
        try {
            Log::info('Writing evaluation started', [
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            $attemptId = $request->input('attempt_id');
            
            if (!$attemptId) {
                return response()->json([
                    'success' => false,
                    'error' => 'No attempt ID provided.'
                ], 400);
            }
            
            $attempt = StudentAttempt::findOrFail($attemptId);

            // Validation checks
            if ($attempt->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized access to this attempt.'
                ], 403);
            }

            if (!auth()->user()->hasFeature('ai_writing_evaluation')) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI evaluation is not available in your current plan.'
                ], 403);
            }

            if ($attempt->testSet->section->name !== 'writing') {
                return response()->json([
                    'success' => false,
                    'error' => 'This attempt is not for writing section.'
                ], 400);
            }

            // Check if already evaluated
            if ($attempt->ai_evaluated_at) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('ai.evaluation.get', $attempt->id)
                ]);
            }

            // Get writing answers
            $answers = $attempt->answers()
                ->with('question')
                ->get();

            $evaluations = [];

            foreach ($answers as $answer) {
                if (empty($answer->answer)) {
                    continue;
                }

                if ($answer->ai_evaluation) {
                    $evaluations[] = $answer->ai_evaluation;
                    continue;
                }

                // Evaluate with AI
                $evaluation = $this->aiService->evaluateWriting(
                    $answer->answer,
                    $answer->question->content,
                    $answer->question->order_number
                );

                // Store evaluation
                $answer->update([
                    'ai_evaluation' => $evaluation,
                    'ai_band_score' => $evaluation['band_score'] ?? null,
                    'ai_evaluated_at' => now(),
                ]);

                $evaluations[] = $evaluation;
                auth()->user()->incrementAIEvaluationCount();
            }

            // Calculate overall band score
            $overallBand = $this->calculateOverallBand($evaluations);

            // Update attempt with AI scores
            $attempt->update([
                'ai_band_score' => $overallBand,
                'ai_evaluated_at' => now(),
            ]);

            // Return status page URL for writing
            return response()->json([
                'success' => true,
                'redirect_url' => route('ai.evaluation.status.page', [
                    'attempt' => $attempt->id,
                    'type' => 'writing'
                ])
            ]);

        } catch (\Exception $e) {
            Log::error('AI Writing evaluation failed', [
                'attempt_id' => $attemptId ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to evaluate writing. Please try again later.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Evaluate speaking with AI.
     */
    public function evaluateSpeaking(Request $request)
    {
        try {
            Log::info('Speaking evaluation started', [
                'user_id' => auth()->id(),
                'attempt_id' => $request->input('attempt_id')
            ]);

            // Test OpenAI connection first
            try {
                $testResponse = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => 'Say hello']
                    ],
                    'max_tokens' => 10
                ]);
                
                Log::info('OpenAI test successful');
            } catch (\Exception $e) {
                Log::error('OpenAI test failed', [
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'AI service is currently unavailable. Please try again later.'
                ], 500);
            }

            $attemptId = $request->input('attempt_id');
            
            if (!$attemptId) {
                return response()->json([
                    'success' => false,
                    'error' => 'No attempt ID provided.'
                ], 400);
            }
            
            $attempt = StudentAttempt::findOrFail($attemptId);

            // Validation checks
            if ($attempt->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized access to this attempt.'
                ], 403);
            }

            if (!auth()->user()->hasFeature('ai_speaking_evaluation')) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI speaking evaluation is not available in your current plan.'
                ], 403);
            }

            if ($attempt->testSet->section->name !== 'speaking') {
                return response()->json([
                    'success' => false,
                    'error' => 'This attempt is not for speaking section.'
                ], 400);
            }

            // Check if already evaluated
            if ($attempt->ai_evaluated_at) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('ai.evaluation.get', $attempt->id)
                ]);
            }

            // Get speaking answers with audio
            $answers = $attempt->answers()
                ->with('question', 'speakingRecording')
                ->whereHas('speakingRecording')
                ->get();

            if ($answers->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No speaking recordings found for this attempt.'
                ], 404);
            }

            $evaluations = [];

            foreach ($answers as $answer) {
                if ($answer->ai_evaluation) {
                    $evaluations[] = $answer->ai_evaluation;
                    continue;
                }

                $audioPath = $answer->speakingRecording->file_path;
                $fullPath = storage_path('app/public/' . $audioPath);
                
                if (!file_exists($fullPath)) {
                    Log::error('Audio file not found', [
                        'path' => $audioPath,
                        'full_path' => $fullPath
                    ]);
                    continue;
                }

                $processedAudioPath = $this->convertAudioIfNeeded($fullPath);

                try {
                    $evaluation = $this->aiService->evaluateSpeaking(
                        $processedAudioPath,
                        $answer->question->content,
                        $answer->question->order_number
                    );

                    $answer->update([
                        'ai_evaluation' => $evaluation,
                        'ai_band_score' => $evaluation['band_score'] ?? null,
                        'ai_evaluated_at' => now(),
                        'transcription' => $evaluation['transcription'] ?? null,
                    ]);

                    $evaluations[] = $evaluation;
                    auth()->user()->incrementAIEvaluationCount();
                    
                } catch (\Exception $e) {
                    Log::error('Failed to evaluate answer', [
                        'answer_id' => $answer->id,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            if (empty($evaluations)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No recordings could be evaluated. Please check the logs.'
                ], 500);
            }

            // Calculate overall band score
            $overallBand = $this->calculateOverallBand($evaluations);

            // Update attempt with AI scores
            $attempt->update([
                'ai_band_score' => $overallBand,
                'ai_evaluated_at' => now(),
            ]);

            // Return status page URL for speaking
            return response()->json([
                'success' => true,
                'redirect_url' => route('ai.evaluation.status.page', [
                    'attempt' => $attempt->id,
                    'type' => 'speaking'
                ])
            ]);

        } catch (\Exception $e) {
            Log::error('AI Speaking evaluation failed', [
                'attempt_id' => $attemptId ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to evaluate speaking. Error: ' . $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }

    /**
     * Show evaluation status page.
     */
    public function showStatusPage($attemptId, $type)
    {
        $attempt = StudentAttempt::findOrFail($attemptId);
        
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        // If already completed, redirect directly to results
        if ($attempt->ai_evaluated_at) {
            return redirect()->route('ai.evaluation.get', $attempt);
        }

        return view('ai-evaluation.status', [
            'attempt' => $attempt,
            'type' => $type
        ]);
    }

    /**
     * Check evaluation status (AJAX).
     */
    public function checkStatus($attemptId)
    {
        try {
            $attempt = StudentAttempt::findOrFail($attemptId);
            
            if ($attempt->user_id !== auth()->id()) {
                return response()->json(['status' => 'unauthorized'], 403);
            }
            
            if ($attempt->ai_evaluated_at) {
                return response()->json([
                    'status' => 'completed',
                    'progress' => 100,
                    'message' => 'Evaluation completed!',
                    'redirect_url' => route('ai.evaluation.get', $attempt->id)
                ]);
            }
            
            // For development without queue
            return response()->json([
                'status' => 'processing',
                'progress' => 50,
                'message' => 'Processing your evaluation...'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to check status'
            ], 500);
        }
    }

    /**
     * Get AI evaluation for an attempt.
     */
    public function getEvaluation($attemptId)
    {
        try {
            $attempt = StudentAttempt::findOrFail($attemptId);

            if ($attempt->user_id !== auth()->id()) {
                abort(403);
            }

            if (!$attempt->ai_evaluated_at) {
                return redirect()->route('student.results.show', $attempt)
                    ->with('error', 'This attempt has not been evaluated yet.');
            }

            $evaluations = $attempt->answers()
                ->whereNotNull('ai_evaluation')
                ->with('question')
                ->get()
                ->map(function ($answer) {
                    return [
                        'question_id' => $answer->question_id,
                        'question_title' => $answer->question->content,
                        'part' => $answer->question->order_number,
                        'evaluation' => $answer->ai_evaluation,
                        'band_score' => $answer->ai_band_score,
                        'evaluated_at' => $answer->ai_evaluated_at,
                        'transcription' => $answer->transcription ?? null,
                    ];
                });

            $section = $attempt->testSet->section->name;
            
            if ($section === 'writing') {
                return view('ai-evaluation.writing-result', [
                    'attempt' => $attempt,
                    'evaluation' => [
                        'overall_band' => $attempt->ai_band_score,
                        'tasks' => $evaluations->map(function ($eval) {
                            return [
                                'question_title' => $eval['question_title'],
                                'band_score' => $eval['band_score'],
                                'word_count' => $eval['evaluation']['word_count'] ?? 0,
                                'required_words' => $eval['part'] == 1 ? 150 : 250,
                                'criteria' => $eval['evaluation']['criteria'] ?? [],
                                'feedback' => $eval['evaluation']['feedback'] ?? [],
                                'grammar_errors' => $eval['evaluation']['grammar_errors'] ?? [],
                                'vocabulary_suggestions' => $eval['evaluation']['vocabulary_suggestions'] ?? [],
                                'improvement_tips' => $eval['evaluation']['improvement_tips'] ?? [],
                                'essay_text' => $eval['evaluation']['original_text'] ?? '',
                            ];
                        })->toArray(),
                        'overall_strengths' => ['Clear structure', 'Good vocabulary range'],
                        'overall_improvements' => ['Work on grammar accuracy', 'Develop ideas more fully'],
                    ]
                ]);
            } else {
                return view('ai-evaluation.speaking-result', [
                    'attempt' => $attempt,
                    'evaluation' => [
                        'overall_band' => $attempt->ai_band_score,
                        'overall_scores' => [
                            'Fluency and Coherence' => $this->calculateCriterionAverage($evaluations, 'Fluency and Coherence'),
                            'Lexical Resource' => $this->calculateCriterionAverage($evaluations, 'Lexical Resource'),
                            'Grammar' => $this->calculateCriterionAverage($evaluations, 'Grammar'),
                            'Pronunciation' => $this->calculateCriterionAverage($evaluations, 'Pronunciation'),
                        ],
                        'parts' => $evaluations->map(function ($eval) use ($attempt) {
                            $recording = $attempt->answers()
                                ->where('question_id', $eval['question_id'])
                                ->first()
                                ->speakingRecording ?? null;
                                
                            return [
                                'part_number' => $eval['part'],
                                'part_type' => "Part {$eval['part']}",
                                'question' => $eval['question_title'],
                                'band_score' => $eval['band_score'],
                                'duration' => $this->formatDuration($eval['evaluation']['word_count'] ?? 0),
                                'transcription' => $eval['transcription'] ?? $eval['evaluation']['transcription'] ?? '',
                                'feedback' => $eval['evaluation']['feedback'] ?? [],
                                'vocabulary_range' => $eval['evaluation']['vocabulary_range'] ?? [],
                                'pronunciation_issues' => $eval['evaluation']['pronunciation_issues'] ?? [],
                                'tips' => $eval['evaluation']['tips'] ?? [],
                                'metrics' => [
                                    'speech_rate' => '150',
                                    'pause_frequency' => 'Moderate',
                                ],
                                'audio_url' => $recording ? Storage::url($recording->file_path) : null,
                            ];
                        })->toArray(),
                        'strengths' => ['Good fluency', 'Clear pronunciation'],
                        'improvements' => ['Expand vocabulary', 'Work on grammar accuracy'],
                        'study_plan' => [
                            'Practice speaking for 30 minutes daily',
                            'Record yourself and listen back',
                            'Learn 5 new words every day',
                        ],
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to get AI evaluation', [
                'attempt_id' => $attemptId,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('student.results.show', $attemptId)
                ->with('error', 'Failed to retrieve evaluation.');
        }
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

        $average = $totalScore / $count;
        return round($average * 2) / 2;
    }
    
    /**
     * Convert audio to MP3 if needed (OpenAI doesn't support webm)
     */
    private function convertAudioIfNeeded($fullPath)
    {
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        
        if (in_array($extension, ['webm', 'ogg'])) {
            $mp3Path = str_replace('.' . $extension, '.mp3', $fullPath);
            
            if (file_exists($mp3Path)) {
                Log::info('MP3 file already exists', ['path' => $mp3Path]);
                return $mp3Path;
            }
            
            $command = "ffmpeg -i " . escapeshellarg($fullPath) . " -acodec libmp3lame -ab 128k " . escapeshellarg($mp3Path) . " 2>&1";
            
            Log::info('Running FFmpeg command', ['command' => $command]);
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                Log::error('FFmpeg conversion failed', [
                    'command' => $command,
                    'output' => $output,
                    'return_code' => $returnCode
                ]);
                
                $command = "ffmpeg -i " . escapeshellarg($fullPath) . " " . escapeshellarg($mp3Path) . " 2>&1";
                exec($command, $output, $returnCode);
                
                if ($returnCode !== 0) {
                    Log::error('Alternative FFmpeg conversion also failed', [
                        'output' => $output
                    ]);
                    return $fullPath;
                }
            }
            
            Log::info('Audio converted successfully', ['mp3_path' => $mp3Path]);
            return $mp3Path;
        }
        
        return $fullPath;
    }
    
    /**
     * Calculate average score for a specific criterion
     */
    private function calculateCriterionAverage($evaluations, $criterion)
    {
        $total = 0;
        $count = 0;
        
        foreach ($evaluations as $eval) {
            if (isset($eval['evaluation']['criteria'][$criterion])) {
                $total += $eval['evaluation']['criteria'][$criterion];
                $count++;
            }
        }
        
        return $count > 0 ? round($total / $count, 1) : 0;
    }
    
    /**
     * Format duration from word count
     */
    private function formatDuration($wordCount)
    {
        $minutes = round($wordCount / 150, 1);
        
        if ($minutes < 1) {
            return round($minutes * 60) . ' seconds';
        }
        
        return $minutes . ' minutes';
    }
}