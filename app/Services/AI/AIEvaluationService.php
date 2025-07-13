<?php

namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            
            if (empty($transcription)) {
                throw new Exception('Failed to transcribe audio - no speech detected');
            }
            
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
    try {
        // audioPath is already full path from controller
        $fullPath = $audioPath;
        
        if (!file_exists($fullPath)) {
            throw new Exception("Audio file not found: {$fullPath}");
        }
        
        // Check file size (OpenAI limit is 25MB)
        $fileSize = filesize($fullPath);
        if ($fileSize > 25 * 1024 * 1024) {
            throw new Exception("Audio file too large: {$fileSize} bytes");
        }
        
        Log::info('Transcribing audio', [
            'path' => $audioPath,
            'size' => $fileSize,
            'exists' => file_exists($fullPath)
        ]);
        
        // Use CURLFile instead of fopen for better compatibility
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.openai.com/v1/audio/transcriptions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'file' => new \CURLFile($fullPath),
                'model' => 'whisper-1',
                'language' => 'en',
                'response_format' => 'json'
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . config('openai.api_key'),
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            throw new Exception("cURL Error: " . $err);
        }
        
        $result = json_decode($response, true);
        
        if (isset($result['error'])) {
            throw new Exception("OpenAI API Error: " . $result['error']['message']);
        }
        
        return $result['text'] ?? '';
        
    } catch (\Exception $e) {
        Log::error('Audio transcription failed', [
            'error' => $e->getMessage(),
            'path' => $audioPath
        ]);
        throw $e;
    }
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

    /**
     * System prompts for consistent evaluation
     */
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
            'original_text' => $originalText, // Store for display in results
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