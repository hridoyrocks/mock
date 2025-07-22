<?php

namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Exception;

class AIEvaluationServiceWithCDN extends AIEvaluationService
{
    /**
     * Transcribe audio using Whisper API with CDN support
     */
    protected function transcribeAudio(string $audioPath): string
    {
        try {
            // Check if it's a URL (CDN) or local path
            if (filter_var($audioPath, FILTER_VALIDATE_URL)) {
                return $this->transcribeFromCDN($audioPath);
            } else {
                return $this->transcribeFromLocal($audioPath);
            }
        } catch (\Exception $e) {
            Log::error('Audio transcription failed', [
                'error' => $e->getMessage(),
                'path' => $audioPath
            ]);
            throw $e;
        }
    }
    
    /**
     * Transcribe audio from CDN URL
     */
    private function transcribeFromCDN(string $audioUrl): string
    {
        try {
            Log::info('Starting CDN audio transcription', [
                'url' => $audioUrl
            ]);
            
            // Download audio file temporarily
            $tempPath = sys_get_temp_dir() . '/' . uniqid('audio_') . '.mp3';
            
            // Download file from CDN
            $response = Http::timeout(60)->get($audioUrl);
            
            if (!$response->successful()) {
                throw new Exception("Failed to download audio from CDN: " . $response->status());
            }
            
            // Save to temp file
            file_put_contents($tempPath, $response->body());
            
            // Check file size
            $fileSize = filesize($tempPath);
            if ($fileSize > 25 * 1024 * 1024) {
                unlink($tempPath);
                throw new Exception("Audio file too large: {$fileSize} bytes");
            }
            
            // Transcribe using OpenAI
            $response = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($tempPath, 'r'),
                'response_format' => 'json',
                'language' => 'en'
            ]);
            
            // Clean up temp file
            unlink($tempPath);
            
            if (!isset($response->text)) {
                throw new Exception('No transcription text in response');
            }
            
            Log::info('CDN transcription successful', [
                'text_length' => strlen($response->text)
            ]);
            
            return $response->text;
            
        } catch (\Exception $e) {
            // Clean up temp file if exists
            if (isset($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }
            throw $e;
        }
    }
    
    /**
     * Transcribe audio from local path
     */
    private function transcribeFromLocal(string $audioPath): string
    {
        $fullPath = $audioPath;
        
        if (!file_exists($fullPath)) {
            throw new Exception("Audio file not found: {$fullPath}");
        }
        
        $fileSize = filesize($fullPath);
        if ($fileSize > 25 * 1024 * 1024) {
            throw new Exception("Audio file too large: {$fileSize} bytes");
        }
        
        Log::info('Starting local audio transcription', [
            'path' => $audioPath,
            'size' => $fileSize
        ]);
        
        $response = OpenAI::audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($fullPath, 'r'),
            'response_format' => 'json',
            'language' => 'en'
        ]);
        
        if (!isset($response->text)) {
            throw new Exception('No transcription text in response');
        }
        
        Log::info('Local transcription successful', [
            'text_length' => strlen($response->text)
        ]);
        
        return $response->text;
    }
}
