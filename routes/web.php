<?php

use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StudentAttemptController;
use App\Http\Controllers\Admin\TestSectionController;
use App\Http\Controllers\Admin\TestSetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\ListeningTestController;
use App\Http\Controllers\Student\ReadingTestController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\SpeakingTestController;
use App\Http\Controllers\Student\TestController;
use App\Http\Controllers\Student\WritingTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Profile routes (authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authenticated routes with role-based dashboard
Route::middleware(['auth'])->group(function () {
    // Dashboard route with role-based redirection
    Route::get('/dashboard', function() {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');
    
    // Student routes
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        // Student Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/progress-data', [App\Http\Controllers\Student\DashboardController::class, 'progressData'])->name('dashboard.progress-data');
        
        // Test routes
        Route::prefix('test')->group(function () {
            Route::get('/', [TestController::class, 'index'])->name('index');
            
            // Listening section - Updated routes
            Route::prefix('listening')->name('listening.')->group(function () {
                Route::get('/', [ListeningTestController::class, 'index'])->name('index');
                
                // Simplified onboarding routes
                Route::get('/onboarding/{testSet}', [ListeningTestController::class, 'confirmDetails'])->name('onboarding.confirm-details');
                Route::get('/sound-check/{testSet}', [ListeningTestController::class, 'soundCheck'])->name('onboarding.sound-check');
                Route::get('/instructions/{testSet}', [ListeningTestController::class, 'instructions'])->name('onboarding.instructions');
                
                Route::get('/start/{testSet}', [ListeningTestController::class, 'start'])->name('start');
                Route::post('/submit/{attempt}', [ListeningTestController::class, 'submit'])->name('submit');
            });
            
            // Reading section
            Route::prefix('reading')->name('reading.')->group(function () {
                Route::get('/', [ReadingTestController::class, 'index'])->name('index');
                
                // Onboarding routes
                Route::prefix('onboarding')->name('onboarding.')->group(function () {
                    Route::get('/{testSet}', [ReadingTestController::class, 'confirmDetails'])->name('confirm-details');
                    Route::get('/instructions/{testSet}', [ReadingTestController::class, 'instructions'])->name('instructions');
                });
                
                Route::get('/start/{testSet}', [ReadingTestController::class, 'start'])->name('start');
                Route::post('/submit/{attempt}', [ReadingTestController::class, 'submit'])->name('submit');
            });
            
            // Writing section
            Route::prefix('writing')->name('writing.')->group(function () {
                Route::get('/', [WritingTestController::class, 'index'])->name('index');
                
                // Onboarding routes
                Route::prefix('onboarding')->name('onboarding.')->group(function () {
                    Route::get('/{testSet}', [WritingTestController::class, 'confirmDetails'])->name('confirm-details');
                    Route::get('/instructions/{testSet}', [WritingTestController::class, 'instructions'])->name('instructions');
                });
                
                Route::get('/start/{testSet}', [WritingTestController::class, 'start'])->name('start');
                Route::post('/autosave/{attempt}/{question}', [WritingTestController::class, 'autosave'])->name('autosave');
                Route::post('/submit/{attempt}', [WritingTestController::class, 'submit'])->name('submit');
            });
            
            // Speaking section
            Route::prefix('speaking')->name('speaking.')->group(function () {
                Route::get('/', [SpeakingTestController::class, 'index'])->name('index');
                
                // Onboarding routes
                Route::prefix('onboarding')->name('onboarding.')->group(function () {
                    Route::get('/{testSet}', [SpeakingTestController::class, 'confirmDetails'])->name('confirm-details');
                    Route::get('/microphone-check/{testSet}', [SpeakingTestController::class, 'microphoneCheck'])->name('microphone-check');
                    Route::get('/instructions/{testSet}', [SpeakingTestController::class, 'instructions'])->name('instructions');
                });
                
                Route::get('/start/{testSet}', [SpeakingTestController::class, 'start'])->name('start');
                Route::post('/record/{attempt}/{question}', [SpeakingTestController::class, 'record'])->name('record');
                Route::post('/submit/{attempt}', [SpeakingTestController::class, 'submit'])->name('submit');
            });
            
            // Results
            Route::get('/results', [ResultController::class, 'index'])->name('results');
            Route::get('/results/{attempt}', [ResultController::class, 'show'])->name('results.show');
        });
    });
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/quick-stats', [App\Http\Controllers\Admin\DashboardController::class, 'quickStats'])->name('dashboard.quick-stats');
        
        // Test sections management
        Route::resource('sections', TestSectionController::class);
        
        // Test sets management
        Route::resource('test-sets', TestSetController::class);
        
        // Questions management - Updated routes
Route::prefix('questions')->name('questions.')->group(function () {
    Route::get('/', [QuestionController::class, 'index'])->name('index');
    Route::get('/create', [QuestionController::class, 'create'])->name('create');
    Route::post('/', [QuestionController::class, 'store'])->name('store');
    Route::get('/{question}', [QuestionController::class, 'show'])->name('show');
    Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
    Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
    Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
    
    // New routes
    Route::post('/{question}/duplicate', [QuestionController::class, 'duplicate'])->name('duplicate');
    Route::get('/bulk-import/{testSet}', [QuestionController::class, 'bulkImportForm'])->name('bulk-import');
    Route::post('/bulk-import/{testSet}', [QuestionController::class, 'bulkImport'])->name('bulk-import.process');
    Route::post('/reorder', [QuestionController::class, 'reorder'])->name('reorder');
    Route::get('/test-set/{testSet}/part/{part}', [QuestionController::class, 'getByPart'])->name('get-by-part');
});
        
        // Student attempts management
        Route::resource('attempts', StudentAttemptController::class);
        Route::get('/attempts/{attempt}/evaluate', [StudentAttemptController::class, 'evaluateForm'])->name('attempts.evaluate-form');
        Route::post('/attempts/{attempt}/evaluate', [StudentAttemptController::class, 'evaluate'])->name('attempts.evaluate');
    });
});