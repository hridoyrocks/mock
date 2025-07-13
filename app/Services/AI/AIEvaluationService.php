<?php

namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class AIEvaluationService
{
    private $model = 'gpt-4';
    private $temperature = 0.3;
    private $timeout = 120; // 2 minutes timeout

    /**
     * Evaluate IELTS Writing
     */
    public function evaluateWriting(string $text, string $question, int $taskNumber): array
    {
        try {
            Log::info('Starting writing evaluation', [
                'text_length' => strlen($text),
                'task_number' => $taskNumber,
                'has_api_key' => !empty(config('openai.api_key'))
            ]);

            // Check API key
            if (empty(config('openai.api_key'))) {
                throw new Exception('OpenAI API key is not configured');
            }

            $prompt = $this->buildWritingPrompt($text, $question, $taskNumber);
            
            // Fix: Use the facade directly without creating new client
            $response = OpenAI::chat()->create([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $this->getWritingSystemPrompt()],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $this->temperature,
                'max_tokens' => 2000,
            ]);

            $content = $response->choices[0]->message->content;
            Log::info('AI Response received', ['response_length' => strlen($content)]);

            $evaluation = json_decode($content, true);
            
            if (!$evaluation) {
                Log::error('Failed to parse AI response', ['response' => $content]);
                throw new Exception('Failed to parse AI response');
            }

            return $this->formatWritingEvaluation($evaluation, $text);

        } catch (\Exception $e) {
            Log::error('AI Writing evaluation failed', [
                'error' => $e->getMessage(),
                'text_length' => strlen($text),
                'trace' => $e->getTraceAsString()
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
            Log::info('Starting speaking evaluation', [
                'audio_path' => $audioPath,
                'part_number' => $partNumber
            ]);

            // First, transcribe the audio
            $transcription = $this->transcribeAudio($audioPath);
            
            if (empty($transcription)) {
                throw new Exception('Failed to transcribe audio - no speech detected');
            }
            
            Log::info('Transcription complete', ['length' => strlen($transcription)]);
            
            $prompt = $this->buildSpeakingPrompt($transcription, $question, $partNumber);
            
            // Fix: Use the facade directly
            $response = OpenAI::chat()->create([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSpeakingSystemPrompt()],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $this->temperature,
                'max_tokens' => 2000,
            ]);

            $content = $response->choices[0]->message->content;
            $evaluation = json_decode($content, true);
            
            if (!$evaluation) {
                Log::error('Failed to parse AI response for speaking');
                throw new Exception('Failed to parse AI response');
            }

            return $this->formatSpeakingEvaluation($evaluation, $transcription);

        } catch (\Exception $e) {
            Log::error('AI Speaking evaluation failed', [
                'error' => $e->getMessage(),
                'audio_path' => $audioPath,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Transcribe audio using Whisper API
     */
    private function transcribeAudio(string $audioPath): string
    {
        try {
            $fullPath = $audioPath;
            
            if (!file_exists($fullPath)) {
                throw new Exception("Audio file not found: {$fullPath}");
            }
            
            $fileSize = filesize($fullPath);
            if ($fileSize > 25 * 1024 * 1024) {
                throw new Exception("Audio file too large: {$fileSize} bytes");
            }
            
            Log::info('Starting audio transcription', [
                'path' => $audioPath,
                'size' => $fileSize
            ]);
            
            // Fix: Use OpenAI facade for transcription
            $response = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($fullPath, 'r'),
                'response_format' => 'json',
                'language' => 'en'
            ]);
            
            if (!isset($response->text)) {
                throw new Exception('No transcription text in response');
            }
            
            Log::info('Transcription successful', [
                'text_length' => strlen($response->text)
            ]);
            
            return $response->text;
            
        } catch (\Exception $e) {
            Log::error('Audio transcription failed', [
                'error' => $e->getMessage(),
                'path' => $audioPath
            ]);
            throw $e;
        }
    }

    // Rest of the methods remain the same...
    private function buildWritingPrompt(string $text, string $question, int $taskNumber): string
    {
        $wordCount = str_word_count($text);
        $requiredWords = $taskNumber === 1 ? 150 : 250;

        return "Evaluate this IELTS Writing Task {$taskNumber} response:

Question: {$question}
Word Count: {$wordCount} (Required: {$requiredWords}+)

Essay:
{$text}

Please provide a detailed evaluation in JSON format with the following structure:
{
    \"overall_band_score\": 6.5,
    \"task_achievement_score\": 7.0,
    \"coherence_cohesion_score\": 6.5,
    \"lexical_resource_score\": 6.0,
    \"grammar_score\": 6.5,
    \"task_achievement_feedback\": \"Your response addresses all parts of the task...\",
    \"coherence_cohesion_feedback\": \"The essay has a clear structure...\",
    \"lexical_resource_feedback\": \"You use a range of vocabulary...\",
    \"grammar_feedback\": \"You demonstrate good control of grammar...\",
    \"grammar_errors\": [\"Subject-verb agreement in paragraph 2\", \"Article usage\"],
    \"vocabulary_suggestions\": [{\"original\": \"big\", \"suggested\": \"substantial\"}, {\"original\": \"many\", \"suggested\": \"numerous\"}],
    \"improvement_tips\": [\"Use more complex sentence structures\", \"Include more specific examples\"]
}";
    }

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

Please provide a detailed evaluation in JSON format with the following structure:
{
    \"overall_band_score\": 6.5,
    \"fluency_coherence_score\": 7.0,
    \"lexical_resource_score\": 6.5,
    \"grammar_score\": 6.0,
    \"pronunciation_score\": 6.5,
    \"fluency_coherence_feedback\": \"You speak with good fluency...\",
    \"lexical_resource_feedback\": \"You use a range of vocabulary...\",
    \"grammar_feedback\": \"Your grammar is generally accurate...\",
    \"pronunciation_feedback\": \"Your pronunciation is clear...\",
    \"pronunciation_issues\": [\"Difficulty with 'th' sounds\", \"Word stress in multi-syllable words\"],
    \"vocabulary_range\": [\"excellent\", \"substantial\", \"moreover\"],
    \"improvement_tips\": [\"Practice linking words for better fluency\", \"Work on intonation patterns\"]
}";
    }

    private function getWritingSystemPrompt(): string
    {
        return "You are an expert IELTS examiner evaluating Writing responses. 
        Evaluate based on the official IELTS criteria:
        1. Task Achievement/Response (25%)
        2. Coherence and Cohesion (25%)
        3. Lexical Resource (25%)
        4. Grammatical Range and Accuracy (25%)
        
        Provide band scores from 0-9 with 0.5 increments. Be fair but strict.
        Return your evaluation as a valid JSON object only, with no additional text.";
    }

    private function getSpeakingSystemPrompt(): string
    {
        return "You are an expert IELTS examiner evaluating Speaking responses.
        Evaluate based on the official IELTS criteria:
        1. Fluency and Coherence (25%)
        2. Lexical Resource (25%)
        3. Grammatical Range and Accuracy (25%)
        4. Pronunciation (25%)
        
        Provide band scores from 0-9 with 0.5 increments. Be fair but strict.
        Return your evaluation as a valid JSON object only, with no additional text.";
    }

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
            'original_text' => $originalText,
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

    private function estimateSpeakingDuration(int $wordCount): string
    {
        $minutes = round($wordCount / 155, 1);
        
        if ($minutes < 1) {
            return round($minutes * 60) . ' seconds';
        }
        
        return $minutes . ' minutes';
    }
}