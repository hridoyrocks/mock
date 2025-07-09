<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSet;
use App\Models\TestPartAudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class TestPartAudioController extends Controller
{
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
            'audio' => 'required|file|mimes:mp3,wav,ogg|max:51200', // 50MB max
            'transcript' => 'nullable|string'
        ]);
        
        try {
            // Check if audio already exists for this part
            $existingAudio = $testSet->partAudios()
                ->where('part_number', $request->part_number)
                ->first();
            
            // Delete old audio if exists
            if ($existingAudio && Storage::disk('public')->exists($existingAudio->audio_path)) {
                Storage::disk('public')->delete($existingAudio->audio_path);
            }
            
            // Upload new audio
            $audio = $request->file('audio');
            $path = $audio->store('test-audios/set-' . $testSet->id, 'public');
            
            // Get audio metadata (duration, size)
            $audioInfo = $this->getAudioInfo(Storage::disk('public')->path($path));
            
            // Create or update part audio record
            $partAudio = TestPartAudio::updateOrCreate(
                [
                    'test_set_id' => $testSet->id,
                    'part_number' => $request->part_number
                ],
                [
                    'audio_path' => $path,
                    'audio_duration' => $audioInfo['duration'] ?? null,
                    'audio_size' => $audio->getSize(),
                    'transcript' => $request->transcript
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Audio uploaded successfully',
                'audio' => [
                    'id' => $partAudio->id,
                    'part_number' => $partAudio->part_number,
                    'path' => Storage::url($partAudio->audio_path),
                    'duration' => $partAudio->formatted_duration,
                    'size' => $partAudio->formatted_size
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
        if (Storage::disk('public')->exists($partAudio->audio_path)) {
            Storage::disk('public')->delete($partAudio->audio_path);
        }
        
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
    private function getAudioInfo($filePath): array
    {
        // You can use getID3 library or ffmpeg command
        // For now, returning dummy data
        // Install: composer require james-heinrich/getid3
        
        try {
            // If using getID3
            // $getID3 = new \getID3;
            // $info = $getID3->analyze($filePath);
            // return [
            //     'duration' => $info['playtime_seconds'] ?? 0,
            //     'bitrate' => $info['bitrate'] ?? 0
            // ];
            
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