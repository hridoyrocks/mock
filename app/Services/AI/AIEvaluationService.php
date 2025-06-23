<?php

namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Exception;

class AIEvaluationService
{
    private $model = 'gpt-4';
    private $temperature = 0.3; // Lower temperature for more consistent evaluations

    /**
     * Evaluate IELTS Writing
     */
    public function evaluateWriting(string $text, string $question, int $taskNumber): array
    {
        try {
            $prompt = $this->buildWritingPrompt($text, $question, $taskNumber);
            
            $response = OpenAI::chat()->create([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $this->getWritingSystemPrompt()],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $this->temperature,
                'max_tokens' => 2000,
            ]);

            $evaluation = json_decode($response->choices[0]->message->content, true);
            
            if (!$evaluation) {
                throw new Exception('Failed to parse AI response');
            }

            return $this->formatWritingEvaluation($evaluation, $text);

        } catch (\Exception $e) {
            Log::error('AI Writing evaluation failed', [
                'error' => $e->getMessage(),
                'text_length' => strlen($text),
            ]);
            throw $e;
        }
    }

    /**
     * Evaluate IELTS Speaking
     */
    public function evaluateSpeaking(string $audioPath, string $question, int $partNumber): array
    {
        try {
            // First, transcribe the audio
            $transcription = $this->transcribeAudio($audioPath);
            
            $prompt = $this->buildSpeakingPrompt($transcription, $question, $partNumber);
            
            $response = OpenAI::chat()->create([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSpeakingSystemPrompt()],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $this->temperature,
                'max_tokens' => 2000,
            ]);

            $evaluation = json_decode($response->choices[0]->message->content, true);
            
            if (!$evaluation) {
                throw new Exception('Failed to parse AI response');
            }

            return $this->formatSpeakingEvaluation($evaluation, $transcription);

        } catch (\Exception $e) {
            Log::error('AI Speaking evaluation failed', [
                'error' => $e->getMessage(),
                'audio_path' => $audioPath,
            ]);
            throw $e;
        }
    }

    /**
     * Transcribe audio using Whisper API
     */
    private function transcribeAudio(string $audioPath): string
    {
        $audio = fopen(storage_path('app/public/' . $audioPath), 'r');
        
        $response = OpenAI::audio()->transcribe([
            'model' => 'whisper-1',
            'file' => $audio,
            'response_format' => 'text',
        ]);

        return $response->text;
    }

    /**
     * Build writing evaluation prompt
     */
    private function buildWritingPrompt(string $text, string $question, int $taskNumber): string
    {
        $wordCount = str_word_count($text);
        $requiredWords = $taskNumber === 1 ? 150 : 250;

        return "Evaluate this IELTS Writing Task {$taskNumber} response:

Question: {$question}
Word Count: {$wordCount} (Required: {$requiredWords}+)

Essay:
{$text}

Please provide a detailed evaluation in JSON format including:
1. Band score (0-9) for each criterion
2. Overall band score
3. Specific feedback for each criterion
4. Grammar errors found
5. Vocabulary suggestions
6. Improvement tips";
    }

    /**
     * Build speaking evaluation prompt
     */
    private function buildSpeakingPrompt(string $transcription, string $question, int $partNumber): string
    {
        $wordCount = str_word_count($transcription);
        $duration = $this->estimateSpeakingDuration($wordCount);

        return "Evaluate this IELTS Speaking Part {$partNumber} response:

Question: {$question}
Response Duration: {$duration}
Word Count: {$wordCount}

Transcription:
{$transcription}

Please provide a detailed evaluation in JSON format including:
1. Band score (0-9) for each criterion
2. Overall band score
3. Specific feedback for each criterion
4. Pronunciation issues
5. Vocabulary range assessment
6. Fluency metrics";
    }

    /**
     * System prompts for consistent evaluation
     */
    private function getWritingSystemPrompt(): string
    {
        return "You are an expert IELTS examiner evaluating Writing responses. 
        Evaluate based on the official IELTS criteria:
        1. Task Achievement/Response
        2. Coherence and Cohesion
        3. Lexical Resource
        4. Grammatical Range and Accuracy
        
        Provide band scores from 0-9 with 0.5 increments. Be fair but strict.
        Return your evaluation as a valid JSON object.";
    }

    private function getSpeakingSystemPrompt(): string
    {
        return "You are an expert IELTS examiner evaluating Speaking responses.
        Evaluate based on the official IELTS criteria:
        1. Fluency and Coherence
        2. Lexical Resource
        3. Grammatical Range and Accuracy
        4. Pronunciation
        
        Provide band scores from 0-9 with 0.5 increments. Be fair but strict.
        Return your evaluation as a valid JSON object.";
    }

    /**
     * Format evaluations for consistent output
     */
    private function formatWritingEvaluation(array $evaluation, string $originalText): array
    {
        return [
            'band_score' => $evaluation['overall_band_score'] ?? 0,
            'criteria' => [
                'Task Achievement' => $evaluation['task_achievement_score'] ?? 0,
                'Coherence and Cohesion' => $evaluation['coherence_cohesion_score'] ?? 0,
                'Lexical Resource' => $evaluation['lexical_resource_score'] ?? 0,
                'Grammar' => $evaluation['grammar_score'] ?? 0,
            ],
            'feedback' => [
                'task_achievement' => $evaluation['task_achievement_feedback'] ?? '',
                'coherence_cohesion' => $evaluation['coherence_cohesion_feedback'] ?? '',
                'lexical_resource' => $evaluation['lexical_resource_feedback'] ?? '',
                'grammar' => $evaluation['grammar_feedback'] ?? '',
            ],
            'word_count' => str_word_count($originalText),
            'grammar_errors' => $evaluation['grammar_errors'] ?? [],
            'vocabulary_suggestions' => $evaluation['vocabulary_suggestions'] ?? [],
            'improvement_tips' => $evaluation['improvement_tips'] ?? [],
        ];
    }

    private function formatSpeakingEvaluation(array $evaluation, string $transcription): array
    {
        return [
            'band_score' => $evaluation['overall_band_score'] ?? 0,
            'criteria' => [
                'Fluency and Coherence' => $evaluation['fluency_coherence_score'] ?? 0,
                'Lexical Resource' => $evaluation['lexical_resource_score'] ?? 0,
                'Grammar' => $evaluation['grammar_score'] ?? 0,
                'Pronunciation' => $evaluation['pronunciation_score'] ?? 0,
            ],
            'feedback' => [
                'fluency_coherence' => $evaluation['fluency_coherence_feedback'] ?? '',
                'lexical_resource' => $evaluation['lexical_resource_feedback'] ?? '',
                'grammar' => $evaluation['grammar_feedback'] ?? '',
                'pronunciation' => $evaluation['pronunciation_feedback'] ?? '',
            ],
            'transcription' => $transcription,
            'word_count' => str_word_count($transcription),
            'pronunciation_issues' => $evaluation['pronunciation_issues'] ?? [],
            'vocabulary_range' => $evaluation['vocabulary_range'] ?? [],
            'tips' => $evaluation['improvement_tips'] ?? [],
        ];
    }

    /**
     * Estimate speaking duration based on word count
     */
    private function estimateSpeakingDuration(int $wordCount): string
    {
        // Average speaking rate: 150-160 words per minute
        $minutes = round($wordCount / 155, 1);
        
        if ($minutes < 1) {
            return round($minutes * 60) . ' seconds';
        }
        
        return $minutes . ' minutes';
    }
}