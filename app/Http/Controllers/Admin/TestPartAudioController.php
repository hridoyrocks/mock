<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSet;
use App\Models\TestPartAudio;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class TestPartAudioController extends Controller
{
    use HandlesFileUploads;
    
    /**
     * Show part audios management page
     */
    public function index(TestSet $testSet)
    {
        // Only for listening section
        if ($testSet->section->name !== 'listening') {
            return redirect()->route('admin.test-sets.show', $testSet)
                ->with('error', 'Part audios are only for listening section.');
        }
        
        $partAudios = $testSet->partAudios()->get()->keyBy('part_number');
        
        return view('admin.test-sets.part-audios', compact('testSet', 'partAudios'));
    }
    
    /**
     * Upload part audio
     */
    public function upload(Request $request, TestSet $testSet): JsonResponse
    {
        $request->validate([
            'part_number' => 'required|integer|min:1|max:4',
            'audio' => 'required|file|mimes:mp3,wav,ogg,webm|max:51200', // 50MB max
            'transcript' => 'nullable|string'
        ]);
        
        try {
            // Check if audio already exists for this part
            $existingAudio = $testSet->partAudios()
                ->where('part_number', $request->part_number)
                ->first();
            
            // Delete old audio if exists
            if ($existingAudio) {
                $this->deleteFile($existingAudio->audio_path, $existingAudio->storage_disk);
            }
            
            // Upload new audio using trait
            $result = $this->uploadFile(
                $request->file('audio'), 
                'test-audios/set-' . $testSet->id
            );
            
            if (!$result['success']) {
                throw new \Exception('Failed to upload audio file');
            }
            
            // Get audio metadata (duration, size)
            $audioInfo = $this->getAudioInfo($request->file('audio'));
            
            // Create or update part audio record
            $partAudio = TestPartAudio::updateOrCreate(
                [
                    'test_set_id' => $testSet->id,
                    'part_number' => $request->part_number
                ],
                [
                    'audio_path' => $result['path'],
                    'audio_url' => $result['url'],
                    'storage_disk' => $result['disk'],
                    'audio_duration' => $audioInfo['duration'] ?? null,
                    'audio_size' => $result['size'],
                    'transcript' => $request->transcript
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Audio uploaded successfully to ' . strtoupper($result['disk']),
                'audio' => [
                    'id' => $partAudio->id,
                    'part_number' => $partAudio->part_number,
                    'url' => $result['url'],
                    'duration' => $partAudio->formatted_duration,
                    'size' => $this->humanFileSize($result['size']),
                    'storage' => strtoupper($result['disk'])
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload audio: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete part audio
     */
    public function destroy(TestSet $testSet, $partNumber): JsonResponse
    {
        $partAudio = $testSet->partAudios()
            ->where('part_number', $partNumber)
            ->first();
        
        if (!$partAudio) {
            return response()->json([
                'success' => false,
                'message' => 'Audio not found'
            ], 404);
        }
        
        // Check if any questions are using this audio
        $questionsCount = $testSet->questions()
            ->where('part_number', $partNumber)
            ->where('use_part_audio', true)
            ->count();
        
        if ($questionsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete: {$questionsCount} questions are using this audio"
            ], 400);
        }
        
        // Delete file
        $this->deleteFile($partAudio->audio_path, $partAudio->storage_disk);
        
        // Delete record
        $partAudio->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Audio deleted successfully'
        ]);
    }
    
    /**
     * Get audio information using getID3 or ffmpeg
     */
    private function getAudioInfo($file): array
    {
        // You can use getID3 library or ffmpeg command
        // For now, returning basic info
        
        try {
            // If using getID3 (install: composer require james-heinrich/getid3)
            if (class_exists('\getID3')) {
                $getID3 = new \getID3;
                $tempPath = $file->getRealPath();
                $info = $getID3->analyze($tempPath);
                
                return [
                    'duration' => $info['playtime_seconds'] ?? 0,
                    'bitrate' => $info['bitrate'] ?? 0
                ];
            }
            
            // Simple implementation
            return [
                'duration' => 0, // Will be updated when implementing getID3
                'bitrate' => 0
            ];
        } catch (\Exception $e) {
            return ['duration' => 0, 'bitrate' => 0];
        }
    }
}