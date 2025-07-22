<?php

namespace App\Http\Controllers;

use App\Models\SpeakingRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AudioStreamController extends Controller
{
    /**
     * Stream audio file from CDN
     */
    public function stream($recordingId): StreamedResponse
    {
        $recording = SpeakingRecording::findOrFail($recordingId);
        
        // Check if user has access to this recording
        if ($recording->answer->attempt->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        
        $disk = $recording->storage_disk ?? 'public';
        
        if ($disk === 'r2' && $recording->file_url) {
            // For R2, redirect to the CDN URL
            return redirect($recording->file_url);
        }
        
        // For local storage, stream the file
        $path = $recording->file_path;
        
        if (!Storage::disk($disk)->exists($path)) {
            abort(404);
        }
        
        $mimeType = $recording->mime_type ?? 'audio/webm';
        $size = Storage::disk($disk)->size($path);
        
        return response()->stream(function () use ($disk, $path) {
            $stream = Storage::disk($disk)->readStream($path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $size,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
