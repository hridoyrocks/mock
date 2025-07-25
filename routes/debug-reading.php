<?php

use App\Http\Controllers\Debug\ReadingDebugController;
use App\Http\Controllers\Debug\CheckDatabaseController;

// Debug routes for reading test issues
Route::middleware(['auth'])->prefix('debug')->name('debug.')->group(function () {
    Route::get('/reading/matching-headings', [ReadingDebugController::class, 'debugMatchingHeadings'])
        ->name('reading.matching-headings');
    Route::post('/reading/test-submission', [ReadingDebugController::class, 'testFormSubmission'])
        ->name('reading.test-submission');
    Route::get('/check-database', [CheckDatabaseController::class, 'checkMatchingHeadings'])
        ->name('check-database');
    
    // View latest logs
    Route::get('/logs', function() {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $lastLines = array_slice($lines, -100); // Last 100 lines
            return response('<pre>' . implode('', $lastLines) . '</pre>');
        }
        return 'Log file not found';
    })->name('logs');
});
