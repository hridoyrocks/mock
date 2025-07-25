<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-ai-config', function () {
    $configs = [
        'speaking' => strlen(config('ai-prompts.speaking', '')),
        'writing_task1' => strlen(config('ai-prompts.writing_task1', '')),
        'writing_task2' => strlen(config('ai-prompts.writing_task2', '')),
    ];
    
    return response()->json([
        'status' => 'Config loaded successfully',
        'prompt_lengths' => $configs,
        'all_loaded' => min($configs) > 0
    ]);
});
