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

// Subscription & Payment Controllers
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AIEvaluationController;

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
    
    // Subscription routes (accessible to all authenticated users)
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
        Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::get('/invoice/{transaction}', [SubscriptionController::class, 'invoice'])->name('invoice');
    });

    // Payment routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/process', [PaymentController::class, 'process'])->name('process');
        Route::get('/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/failed', [PaymentController::class, 'failed'])->name('failed');
        Route::post('/webhook/{provider}', [PaymentController::class, 'webhook'])->name('webhook')->withoutMiddleware(['auth']);
    });
    
    // Student routes
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        // Student Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/progress-data', [App\Http\Controllers\Student\DashboardController::class, 'progressData'])->name('dashboard.progress-data');
        
        // Test routes
        Route::prefix('test')->group(function () {
            Route::get('/', [TestController::class, 'index'])->name('index');
            
            // Listening section - with usage limit check
            Route::prefix('listening')->name('listening.')->group(function () {
                Route::get('/', [ListeningTestController::class, 'index'])->name('index');
                
                // Check usage limit before starting test
                Route::middleware(['usage.limit:mock_test'])->group(function () {
                    Route::get('/onboarding/{testSet}', [ListeningTestController::class, 'confirmDetails'])->name('onboarding.confirm-details');
                    Route::get('/sound-check/{testSet}', [ListeningTestController::class, 'soundCheck'])->name('onboarding.sound-check');
                    Route::get('/instructions/{testSet}', [ListeningTestController::class, 'instructions'])->name('onboarding.instructions');
                    Route::get('/start/{testSet}', [ListeningTestController::class, 'start'])->name('start');
                });
                
                Route::post('/submit/{attempt}', [ListeningTestController::class, 'submit'])->name('submit');
            });
            
            // Reading section - with usage limit check
            Route::prefix('reading')->name('reading.')->group(function () {
                Route::get('/', [ReadingTestController::class, 'index'])->name('index');
                
                Route::middleware(['usage.limit:mock_test'])->group(function () {
                    Route::prefix('onboarding')->name('onboarding.')->group(function () {
                        Route::get('/{testSet}', [ReadingTestController::class, 'confirmDetails'])->name('confirm-details');
                        Route::get('/instructions/{testSet}', [ReadingTestController::class, 'instructions'])->name('instructions');
                    });
                    
                    Route::get('/start/{testSet}', [ReadingTestController::class, 'start'])->name('start');
                });
                
                Route::post('/submit/{attempt}', [ReadingTestController::class, 'submit'])->name('submit');
            });
            
            // Writing section - with feature check for AI evaluation
            Route::prefix('writing')->name('writing.')->group(function () {
                Route::get('/', [WritingTestController::class, 'index'])->name('index');
                
                Route::middleware(['usage.limit:mock_test'])->group(function () {
                    Route::prefix('onboarding')->name('onboarding.')->group(function () {
                        Route::get('/{testSet}', [WritingTestController::class, 'confirmDetails'])->name('confirm-details');
                        Route::get('/instructions/{testSet}', [WritingTestController::class, 'instructions'])->name('instructions');
                    });
                    
                    Route::get('/start/{testSet}', [WritingTestController::class, 'start'])->name('start');
                    Route::post('/autosave/{attempt}/{question}', [WritingTestController::class, 'autosave'])->name('autosave');
                });
                
                Route::post('/submit/{attempt}', [WritingTestController::class, 'submit'])->name('submit');
                
                // AI Evaluation for premium users
                Route::middleware(['feature:ai_writing_evaluation'])->group(function () {
                    Route::post('/evaluate/{attempt}', [AIEvaluationController::class, 'evaluateWriting'])->name('evaluate');
                });
            });
            
            // Speaking section - with feature check for AI evaluation
            Route::prefix('speaking')->name('speaking.')->group(function () {
                Route::get('/', [SpeakingTestController::class, 'index'])->name('index');
                
                Route::middleware(['usage.limit:mock_test'])->group(function () {
                    Route::prefix('onboarding')->name('onboarding.')->group(function () {
                        Route::get('/{testSet}', [SpeakingTestController::class, 'confirmDetails'])->name('confirm-details');
                        Route::get('/microphone-check/{testSet}', [SpeakingTestController::class, 'microphoneCheck'])->name('onboarding.microphone-check');
                        Route::get('/instructions/{testSet}', [SpeakingTestController::class, 'instructions'])->name('instructions');
                    });
                    
                    Route::get('/start/{testSet}', [SpeakingTestController::class, 'start'])->name('start');
                    Route::post('/record/{attempt}/{question}', [SpeakingTestController::class, 'record'])->name('record');
                });
                
                Route::post('/submit/{attempt}', [SpeakingTestController::class, 'submit'])->name('submit');
                
                // AI Evaluation for premium users
                Route::middleware(['feature:ai_speaking_evaluation'])->group(function () {
                    Route::post('/evaluate/{attempt}', [AIEvaluationController::class, 'evaluateSpeaking'])->name('evaluate');
                });
            });
            
            // Results
            Route::get('/results', [ResultController::class, 'index'])->name('results');
            Route::get('/results/{attempt}', [ResultController::class, 'show'])->name('results.show');
            
            // AI Evaluation results (premium feature)
            Route::middleware(['feature:ai_evaluation'])->group(function () {
                Route::get('/results/{attempt}/ai-feedback', [ResultController::class, 'aiFeedback'])->name('results.ai-feedback');
            });
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
        
        // Questions management
        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('index');
            Route::get('/create', [QuestionController::class, 'create'])->name('create');
            Route::post('/', [QuestionController::class, 'store'])->name('store');
            Route::get('/{question}', [QuestionController::class, 'show'])->name('show');
            Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
            Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
            Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
            
            // Reading Section Specific Routes
            Route::get('/reading/{testSet}/questions', [QuestionController::class, 'createReadingQuestion'])->name('reading.questions');
            Route::get('/reading/{testSet}/passage', [QuestionController::class, 'createReadingPassage'])->name('reading.passage');
            Route::get('/reading/{testSet}/markers', [QuestionController::class, 'getPassageMarkers'])->name('reading.markers');
            
            // Additional routes
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
        
        // Subscription management (admin)
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', 'Admin\SubscriptionManagementController@index')->name('index');
            Route::get('/users', 'Admin\SubscriptionManagementController@users')->name('users');
            Route::get('/transactions', 'Admin\SubscriptionManagementController@transactions')->name('transactions');
            Route::post('/grant/{user}', 'Admin\SubscriptionManagementController@grantSubscription')->name('grant');
            Route::post('/revoke/{subscription}', 'Admin\SubscriptionManagementController@revokeSubscription')->name('revoke');
        });
    });
});

// AI Evaluation API routes (protected)
Route::middleware(['auth', 'subscription:premium'])->prefix('ai')->name('ai.')->group(function () {
    Route::post('/evaluate/writing', [AIEvaluationController::class, 'evaluateWriting'])->name('evaluate.writing');
    Route::post('/evaluate/speaking', [AIEvaluationController::class, 'evaluateSpeaking'])->name('evaluate.speaking');
    Route::get('/evaluation/{id}', [AIEvaluationController::class, 'getEvaluation'])->name('evaluation.get');
});