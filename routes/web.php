<?php

use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StudentAttemptController;
use App\Http\Controllers\Admin\TestSectionController;
use App\Http\Controllers\Admin\TestSetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\ListeningTestController;
use App\Http\Controllers\Student\ReadingTestController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\SpeakingTestController;
use App\Http\Controllers\Student\TestController;
use App\Http\Controllers\Student\WritingTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;



Route::middleware(['auth'])->group(function () {
   
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student routes
    Route::middleware(['role:student'])->prefix('test')->name('student.')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');
        
        // Listening section
        
        Route::prefix('listening')->name('listening.')->group(function () {
            Route::get('/', [ListeningTestController::class, 'index'])->name('index');
            
            // Onboarding routes
            Route::prefix('onboarding')->name('onboarding.')->group(function () {
                Route::get('/{testSet}', [ListeningTestController::class, 'confirmDetails'])->name('confirm-details');
                Route::get('/sound-check/{testSet}', [ListeningTestController::class, 'soundCheck'])->name('sound-check');
                Route::get('/instructions/{testSet}', [ListeningTestController::class, 'instructions'])->name('instructions');
            });

            Route::post('/abandon/{attempt}', [ListeningTestController::class, 'abandon'])->name('abandon');
            
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
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Test sections management
        Route::resource('sections', TestSectionController::class);
        
        // Test sets management
        Route::resource('test-sets', TestSetController::class);
        
        // Questions management
        Route::resource('questions', QuestionController::class);
        
        // Student attempts management
        Route::resource('attempts', StudentAttemptController::class);
        Route::get('/attempts/{attempt}/evaluate', [StudentAttemptController::class, 'evaluateForm'])->name('attempts.evaluate-form');
        Route::post('/attempts/{attempt}/evaluate', [StudentAttemptController::class, 'evaluate'])->name('attempts.evaluate');
    });
});