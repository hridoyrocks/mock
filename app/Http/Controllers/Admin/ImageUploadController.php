<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageUploadController extends Controller
{
    use HandlesFileUploads;
    
    public function upload(Request $request)
    {
        Log::info('Image upload request received', [
            'has_file' => $request->hasFile('image'),
            'files' => $request->allFiles()
        ]);
        
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
            ]);
            
            $file = $request->file('image');
            
            // Upload using trait method
            $result = $this->uploadFile($file, 'questions');
            
            if (!$result['success']) {
                throw new \Exception('Failed to upload file');
            }
            
            Log::info('Image uploaded successfully', $result);
            
            return response()->json([
                'success' => true,
                'url' => $result['url'],
                'location' => $result['url'], // TinyMCE sometimes expects 'location'
                'filename' => basename($result['path']),
                'size' => $this->humanFileSize($result['size'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Image upload error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
