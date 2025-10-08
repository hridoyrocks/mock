<?php

// Include test routes
if (file_exists(base_path('routes/test.php'))) {
    require base_path('routes/test.php');
}

// Include test features route
if (file_exists(base_path('routes/test-features.php'))) {
    require base_path('routes/test-features.php');
}

// Include test config route for checking AI prompts
if (file_exists(base_path('routes/test-config.php'))) {
    require base_path('routes/test-config.php');
}

// Include debug routes
if (file_exists(base_path('routes/debug-teachers.php'))) {
    require base_path('routes/debug-teachers.php');
}

// Include debug routes for reading
if (file_exists(base_path('routes/debug-reading.php'))) {
    require base_path('routes/debug-reading.php');
}

// Include debug routes for matching headings
if (file_exists(base_path('routes/debug-matching-headings.php'))) {
    require base_path('routes/debug-matching-headings.php');
}

// Include test matching display route
if (file_exists(base_path('routes/test-matching-display.php'))) {
    require base_path('routes/test-matching-display.php');
}

// Include debug categories route
if (file_exists(base_path('routes/debug-categories.php'))) {
    require base_path('routes/debug-categories.php');
}

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
use App\Http\Controllers\Admin\SubscriptionManagementController;

// NEW AUTH CONTROLLERS
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Referral Controller
use App\Http\Controllers\Student\ReferralController;

// Legal Pages Routes
Route::get('/privacy-policy', function() {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function() {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/cookie-policy', function() {
    return view('legal.cookie-policy');
})->name('cookie-policy');

// Home route
Route::get('/', function() {
    return view('welcome');
})->name('welcome');

// Alternative home route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Webhook routes (outside auth middleware - MUST be outside auth)
Route::post('/payment/webhook/{provider}', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->middleware(['verify.webhook:{provider}']);

// Apply referral tracking middleware to all routes
Route::middleware(['web', \App\Http\Middleware\TrackReferral::class])->group(function () {

// AUTHENTICATION ROUTES (Guest only)
Route::middleware(['guest'])->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Social Authentication Routes
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('auth.social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('auth.social.callback');
    Route::get('/auth/social/complete', [SocialAuthController::class, 'showCompleteForm'])->name('auth.social.complete');
    Route::post('/auth/social/complete', [SocialAuthController::class, 'complete']);
    
    // OTP Verification Routes
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('auth.verify.otp');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('auth.otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('auth.otp.resend');
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
        ->name('password.request');
    
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');
    
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Test banned status route - REMOVE IN PRODUCTION
Route::get('/test-ban-status', function() {
    if (!auth()->check()) {
        return 'Not logged in';
    }
    
    $user = auth()->user();
    return [
        'user_id' => $user->id,
        'name' => $user->name,
        'banned_at' => $user->banned_at,
        'ban_type' => $user->ban_type,
        'ban_expires_at' => $user->ban_expires_at,
        'is_banned' => $user->isBanned(),
        'is_permanent' => $user->isPermanentlyBanned(),
        'is_temporary' => $user->isTemporarilyBanned(),
    ];
})->middleware('auth');

// Logout Route (authenticated only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Banned User Routes
Route::middleware(['auth'])->prefix('banned')->name('banned.')->group(function () {
    Route::get('/', [App\Http\Controllers\BannedController::class, 'index'])->name('index');
    Route::post('/appeal', [App\Http\Controllers\BannedController::class, 'appeal'])->name('appeal');
});

// Maintenance page (accessible during maintenance) - MOVED HERE
Route::get('/maintenance', [App\Http\Controllers\MaintenanceController::class, 'index'])
    ->name('maintenance')
    ->middleware('auth');

// Audio streaming route
Route::get('/audio/stream/{recording}', [App\Http\Controllers\AudioStreamController::class, 'stream'])
    ->name('audio.stream')
    ->middleware('auth');
Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Email change verification routes
    Route::post('/profile/verify-email-change', [ProfileController::class, 'verifyEmailChange'])->name('profile.verify-email-change');
    Route::post('/profile/resend-email-otp', [ProfileController::class, 'resendEmailChangeOtp'])->name('profile.resend-email-otp');
    Route::post('/profile/cancel-email-change', [ProfileController::class, 'cancelEmailChange'])->name('profile.cancel-email-change');
});

Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar')->middleware('auth');


Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
    // Coupon routes
    Route::prefix('coupon')->name('coupon.')->group(function () {
        Route::post('/validate', [App\Http\Controllers\CouponController::class, 'validate'])->name('validate');
        Route::post('/apply', [App\Http\Controllers\CouponController::class, 'apply'])->name('apply');
        Route::delete('/remove', [App\Http\Controllers\CouponController::class, 'remove'])->name('remove');
    });
});

}); // End of referral tracking middleware



// Public verification route (no auth required)
Route::get('/invoice/verify/{transaction}', [SubscriptionController::class, 'verifyInvoice'])
    ->name('invoice.verify');

// Authenticated routes with role-based dashboard
Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
    // Dashboard route with role-based redirection
    Route::get('/dashboard', function() {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');

    // AI Evaluation routes
    Route::prefix('ai')->name('ai.')->group(function () {
        // Evaluation endpoints
        Route::post('/evaluate/writing', [AIEvaluationController::class, 'evaluateWriting'])
            ->name('evaluation.writing')
            ->middleware(['feature:ai_writing_evaluation']);
        
        Route::post('/evaluate/speaking', [AIEvaluationController::class, 'evaluateSpeaking'])
            ->name('evaluation.speaking')
            ->middleware(['feature:ai_speaking_evaluation']);
        
        // Result page (no status page needed anymore)
        Route::get('/evaluation/{attempt}', [AIEvaluationController::class, 'getEvaluation'])
            ->name('evaluation.get');
    });
    
    // Subscription routes (accessible to all authenticated users)
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
        Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('/toggle-auto-renew', [SubscriptionController::class, 'toggleAutoRenew'])->name('toggle-auto-renew');
        Route::get('/invoice/{transaction}', [SubscriptionController::class, 'invoice'])->name('invoice');
        Route::get('/invoice/{transaction}/download', [SubscriptionController::class, 'downloadInvoice'])->name('invoice.download');
        Route::get('/welcome', [SubscriptionController::class, 'welcome'])->name('welcome');
    });

    // Payment routes (inside auth - except webhook which is outside)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::match(['get', 'post'], '/process', [PaymentController::class, 'process'])->name('process');
        Route::get('/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/failed', [PaymentController::class, 'failed'])->name('failed');
    });
    
    // Student routes
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        // Student Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/progress-data', [App\Http\Controllers\Student\DashboardController::class, 'progressData'])->name('dashboard.progress-data');
        
        // Goals
        Route::post('/goals', [App\Http\Controllers\Student\DashboardController::class, 'storeGoal'])->name('goals.store');
        
        // Achievements
        Route::post('/achievements/mark-seen', [App\Http\Controllers\Student\DashboardController::class, 'markAchievementsSeen'])->name('achievements.mark-seen');
        Route::get('/achievements/{badge}', [App\Http\Controllers\Student\DashboardController::class, 'getAchievementDetails'])->name('achievements.details');
        
        // Leaderboard
        Route::get('/leaderboard/{period?}', [App\Http\Controllers\Student\DashboardController::class, 'getLeaderboard'])->name('leaderboard');
        
        // Announcements
        Route::prefix('announcements')->name('announcements.')->group(function () {
            Route::get('/active', [App\Http\Controllers\Student\AnnouncementController::class, 'getActiveAnnouncements'])->name('active');
            Route::post('/{announcement}/dismiss', [App\Http\Controllers\Student\AnnouncementController::class, 'dismiss'])->name('dismiss');
        });

        // Test routes
        Route::prefix('test')->group(function () {
            // Route::get('/', [TestController::class, 'index'])->name('index'); // Removed
            
            // Full Test routes
            Route::prefix('full-test')->name('full-test.')->group(function () {
                Route::get('/', [App\Http\Controllers\Student\FullTestController::class, 'index'])->name('index');
                Route::get('/{fullTest}/onboarding', [App\Http\Controllers\Student\FullTestController::class, 'onboarding'])->name('onboarding');
                Route::post('/{fullTest}/start', [App\Http\Controllers\Student\FullTestController::class, 'start'])->name('start');
                Route::get('/attempt/{fullTestAttempt}/section/{section}', [App\Http\Controllers\Student\FullTestController::class, 'section'])->name('section');
                Route::post('/attempt/{fullTestAttempt}/complete-section', [App\Http\Controllers\Student\FullTestController::class, 'completeSection'])->name('complete-section');
                Route::get('/attempt/{fullTestAttempt}/results', [App\Http\Controllers\Student\FullTestController::class, 'results'])->name('results');
                Route::post('/attempt/{fullTestAttempt}/abandon', [App\Http\Controllers\Student\FullTestController::class, 'abandon'])->name('abandon');
            });
            
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
                });
                
                // Speaking section - with feature check for AI evaluation
                Route::prefix('speaking')->name('speaking.')->group(function () {
                Route::get('/', [SpeakingTestController::class, 'index'])->name('index');
                
                Route::middleware(['usage.limit:mock_test'])->group(function () {
                Route::prefix('onboarding')->name('onboarding.')->group(function () {
                Route::get('/{testSet}', [SpeakingTestController::class, 'confirmDetails'])->name('confirm-details');
                Route::get('/microphone-check/{testSet}', [SpeakingTestController::class, 'microphoneCheck'])->name('microphone-check');
                Route::get('/instructions/{testSet}', [SpeakingTestController::class, 'instructions'])->name('instructions');
                });
                
                Route::get('/start/{testSet}', [SpeakingTestController::class, 'start'])->name('start');
                Route::post('/record/{attempt}/{question}', [SpeakingTestController::class, 'record'])->name('record');
                });
                
                Route::post('/submit/{attempt}', [SpeakingTestController::class, 'submit'])->name('submit');
                });
                
                // Results
                Route::get('/results', [ResultController::class, 'index'])->name('results');
                Route::get('/results/{attempt}', [ResultController::class, 'show'])->name('results.show');
                Route::post('/results/{attempt}/retake', [ResultController::class, 'retake'])->name('results.retake');
                });
                
                // Human Evaluation
                Route::prefix('human-evaluation')->name('evaluation.')->group(function () {
                    Route::get('/{attempt}/teachers', [App\Http\Controllers\Student\HumanEvaluationController::class, 'showTeachers'])->name('teachers');
                    Route::post('/{attempt}/request', [App\Http\Controllers\Student\HumanEvaluationController::class, 'requestEvaluation'])->name('request');
                    Route::get('/{attempt}/status', [App\Http\Controllers\Student\HumanEvaluationController::class, 'status'])->name('status');
                    Route::get('/{attempt}/result', [App\Http\Controllers\Student\HumanEvaluationController::class, 'viewResult'])->name('result');
                });
                
                // Token Purchase
                Route::prefix('tokens')->name('tokens.')->group(function () {
                Route::get('/purchase', [App\Http\Controllers\Student\HumanEvaluationController::class, 'showTokenPurchase'])->name('purchase');
                Route::post('/purchase', [App\Http\Controllers\Student\HumanEvaluationController::class, 'purchaseTokens'])->name('process');
                });
                
                // Referral System
                Route::prefix('referrals')->name('referrals.')->group(function () {
                    Route::get('/', [ReferralController::class, 'index'])->name('index');
                    Route::post('/redeem/tokens', [ReferralController::class, 'redeemTokens'])->name('redeem.tokens');
                    Route::post('/redeem/subscription', [ReferralController::class, 'redeemSubscription'])->name('redeem.subscription');
                    Route::get('/history', [ReferralController::class, 'getReferralHistory'])->name('history');
                    Route::get('/redemption-history', [ReferralController::class, 'getRedemptionHistory'])->name('redemption-history');
                    Route::get('/stats', [ReferralController::class, 'getStats'])->name('stats');
                });
});
    
    // Teacher routes
    Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Teacher\EvaluationController::class, 'dashboard'])->name('dashboard');
        Route::patch('/toggle-availability', [App\Http\Controllers\Teacher\EvaluationController::class, 'toggleAvailability'])->name('toggle-availability');
        
        Route::prefix('evaluations')->name('evaluations.')->group(function () {
            Route::get('/pending', [App\Http\Controllers\Teacher\EvaluationController::class, 'pending'])->name('pending');
            Route::get('/completed', [App\Http\Controllers\Teacher\EvaluationController::class, 'completed'])->name('completed');
            Route::get('/{evaluationRequest}', [App\Http\Controllers\Teacher\EvaluationController::class, 'show'])->name('show');
            Route::post('/{evaluationRequest}/submit', [App\Http\Controllers\Teacher\EvaluationController::class, 'submit'])->name('submit');
        });
    });
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Test TinyMCE route
        Route::get('/test-tinymce', function() {
            return view('admin.test-tinymce');
        })->name('test-tinymce');
        
        // Debug TinyMCE Config
        Route::get('/tinymce-debug', function() {
            return view('admin.tinymce-debug');
        })->name('tinymce-debug');
        // Admin Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/quick-stats', [App\Http\Controllers\Admin\DashboardController::class, 'quickStats'])->name('dashboard.quick-stats');
        
        // Test sections management
        Route::resource('sections', TestSectionController::class);
        
        // Test sets management
        Route::resource('test-sets', TestSetController::class);
        
        // Full Tests management
        Route::resource('full-tests', App\Http\Controllers\Admin\FullTestController::class);
        Route::patch('/full-tests/{fullTest}/toggle-status', [App\Http\Controllers\Admin\FullTestController::class, 'toggleStatus'])->name('full-tests.toggle-status');
        Route::post('/full-tests/reorder', [App\Http\Controllers\Admin\FullTestController::class, 'reorder'])->name('full-tests.reorder');

        // Add these NEW routes for Part Audio Management
        Route::prefix('test-sets/{testSet}')->name('test-sets.')->group(function () {
            // Part Audio Management Routes
            Route::get('/part-audios', [App\Http\Controllers\Admin\TestPartAudioController::class, 'index'])
                ->name('part-audios');
            Route::post('/part-audios', [App\Http\Controllers\Admin\TestPartAudioController::class, 'upload'])
                ->name('part-audios.upload');
            Route::delete('/part-audios/{partNumber}', [App\Http\Controllers\Admin\TestPartAudioController::class, 'destroy'])
                ->name('part-audios.destroy');
            // Add this helper route for checking part audio existence
            Route::get('/check-part-audio/{partNumber}', function($testSetId, $partNumber) {
                $testSet = \App\Models\TestSet::findOrFail($testSetId);
                return response()->json([
                    'hasAudio' => $testSet->hasPartAudio($partNumber)
                ]);
            })->name('check-part-audio');
        });

        // Questions management
        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('index');
            Route::get('/create', [QuestionController::class, 'create'])->name('create');
            Route::post('/', [QuestionController::class, 'store'])->name('store');
            Route::get('/{question}', [QuestionController::class, 'show'])->name('show');
            Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
            Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
            Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
            
            // AJAX route for loading questions by test set
            Route::get('/ajax/test-set/{testSetId}', [QuestionController::class, 'ajaxTestSet'])->name('ajax.test-set');
            
            // Reading Section Specific Routes
            Route::get('/reading/{testSet}/questions', [QuestionController::class, 'createReadingQuestion'])->name('reading.questions');
            Route::get('/reading/{testSet}/passage', [QuestionController::class, 'createReadingPassage'])->name('reading.passage');
            Route::get('/reading/{testSet}/markers', [QuestionController::class, 'getPassageMarkers'])->name('reading.markers');
            
            // Image upload route for TinyMCE
            Route::post('/upload-image', [App\Http\Controllers\Admin\ImageUploadController::class, 'upload'])
                ->name('upload.image');

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
            Route::get('/', [SubscriptionManagementController::class, 'index'])->name('index');
            Route::get('/users', [SubscriptionManagementController::class, 'users'])->name('users');
            Route::get('/transactions', [SubscriptionManagementController::class, 'transactions'])->name('transactions');
            Route::post('/grant/{user}', [SubscriptionManagementController::class, 'grantSubscription'])->name('grant');
            Route::post('/revoke/{subscription}', [SubscriptionManagementController::class, 'revokeSubscription'])->name('revoke');
        });

        // Subscription Plans Management
        Route::prefix('subscription-plans')->name('subscription-plans.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'store'])->name('store');
            Route::get('/{subscriptionPlan}/edit', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'edit'])->name('edit');
            Route::put('/{subscriptionPlan}', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'update'])->name('update');
            Route::delete('/{subscriptionPlan}', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'destroy'])->name('destroy');
            Route::patch('/{subscriptionPlan}/toggle-status', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/reorder', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'reorder'])->name('reorder');
        });
        
        // Subscription Features Management
        Route::prefix('subscription-features')->name('subscription-features.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SubscriptionFeatureController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\SubscriptionFeatureController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\SubscriptionFeatureController::class, 'store'])->name('store');
            Route::get('/{subscriptionFeature}/edit', [App\Http\Controllers\Admin\SubscriptionFeatureController::class, 'edit'])->name('edit');
            Route::put('/{subscriptionFeature}', [App\Http\Controllers\Admin\SubscriptionFeatureController::class, 'update'])->name('update');
            Route::delete('/{subscriptionFeature}', [App\Http\Controllers\Admin\SubscriptionFeatureController::class, 'destroy'])->name('destroy');
        });
        
        // Teacher Management
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('store');
            Route::get('/{teacher}', [App\Http\Controllers\Admin\TeacherController::class, 'show'])->name('show');
            Route::get('/{teacher}/edit', [App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('edit');
            Route::put('/{teacher}', [App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('update');
            Route::delete('/{teacher}', [App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('destroy');
            Route::patch('/{teacher}/toggle-availability', [App\Http\Controllers\Admin\TeacherController::class, 'toggleAvailability'])->name('toggle-availability');
        });
        
        // Token Packages Management
        Route::prefix('token-packages')->name('token-packages.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\TokenPackageController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\TokenPackageController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\TokenPackageController::class, 'store'])->name('store');
            Route::get('/{tokenPackage}/edit', [App\Http\Controllers\Admin\TokenPackageController::class, 'edit'])->name('edit');
            Route::put('/{tokenPackage}', [App\Http\Controllers\Admin\TokenPackageController::class, 'update'])->name('update');
            Route::delete('/{tokenPackage}', [App\Http\Controllers\Admin\TokenPackageController::class, 'destroy'])->name('destroy');
            Route::patch('/{tokenPackage}/toggle-status', [App\Http\Controllers\Admin\TokenPackageController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // User Token Management
        Route::prefix('user-tokens')->name('user-tokens.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserTokenController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserTokenController::class, 'edit'])->name('edit');
            Route::post('/{user}/add', [App\Http\Controllers\Admin\UserTokenController::class, 'addTokens'])->name('add');
            Route::post('/{user}/deduct', [App\Http\Controllers\Admin\UserTokenController::class, 'deductTokens'])->name('deduct');
            Route::post('/{user}/set', [App\Http\Controllers\Admin\UserTokenController::class, 'setTokens'])->name('set');
        });
        
        // Maintenance Mode Management - FIXED PLACEMENT
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\MaintenanceModeController::class, 'index'])->name('index');
            Route::post('/toggle', [App\Http\Controllers\Admin\MaintenanceModeController::class, 'toggle'])->name('toggle');
        });

// Coupon Management
        // Coupon Management
        Route::prefix('coupons')->name('coupons.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CouponController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\CouponController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\CouponController::class, 'store'])->name('store');
            Route::get('/{coupon}', [App\Http\Controllers\Admin\CouponController::class, 'show'])->name('show');
            Route::get('/{coupon}/edit', [App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('edit');
            Route::put('/{coupon}', [App\Http\Controllers\Admin\CouponController::class, 'update'])->name('update');
            Route::delete('/{coupon}', [App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('destroy');
            Route::patch('/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/bulk-export', [App\Http\Controllers\Admin\CouponController::class, 'bulkExport'])->name('bulk-export');
        });
        
        // Announcement Management
        Route::prefix('announcements')->name('announcements.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('store');
            Route::get('/{announcement}/edit', [App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('edit');
            Route::put('/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('update');
            Route::delete('/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('destroy');
            Route::patch('/{announcement}/toggle-status', [App\Http\Controllers\Admin\AnnouncementController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Website Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/website', [App\Http\Controllers\Admin\WebsiteSettingController::class, 'index'])->name('website');
            Route::post('/website', [App\Http\Controllers\Admin\WebsiteSettingController::class, 'update'])->name('website.update');
            Route::delete('/website/logo', [App\Http\Controllers\Admin\WebsiteSettingController::class, 'removeLogo'])->name('website.remove-logo');
            Route::delete('/website/dark-logo', [App\Http\Controllers\Admin\WebsiteSettingController::class, 'removeDarkModeLogo'])->name('website.remove-dark-logo');
            Route::delete('/website/favicon', [App\Http\Controllers\Admin\WebsiteSettingController::class, 'removeFavicon'])->name('website.remove-favicon');
        });
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
            Route::get('/system', [App\Http\Controllers\Admin\UserController::class, 'systemUsers'])->name('system');
            Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
            Route::get('/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
            Route::get('/{user}/ban', [App\Http\Controllers\Admin\UserController::class, 'showBanForm'])->name('ban-form');
            Route::post('/{user}/ban', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('ban');
            Route::post('/{user}/unban', [App\Http\Controllers\Admin\UserController::class, 'unban'])->name('unban');
            Route::post('/{user}/verify-email', [App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('verify-email');
        });
        
        // Ban Appeals Management
        Route::prefix('ban-appeals')->name('ban-appeals.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BanAppealController::class, 'index'])->name('index');
            Route::get('/{banAppeal}', [App\Http\Controllers\Admin\BanAppealController::class, 'show'])->name('show');
            Route::post('/{banAppeal}/approve', [App\Http\Controllers\Admin\BanAppealController::class, 'approve'])->name('approve');
            Route::post('/{banAppeal}/reject', [App\Http\Controllers\Admin\BanAppealController::class, 'reject'])->name('reject');
        });
        
        // Test Categories Management
        Route::prefix('test-categories')->name('test-categories.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\TestCategoryController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\TestCategoryController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\TestCategoryController::class, 'store'])->name('store');
            Route::get('/{testCategory}', [App\Http\Controllers\Admin\TestCategoryController::class, 'show'])->name('show');
            Route::get('/{testCategory}/edit', [App\Http\Controllers\Admin\TestCategoryController::class, 'edit'])->name('edit');
            Route::put('/{testCategory}', [App\Http\Controllers\Admin\TestCategoryController::class, 'update'])->name('update');
            Route::delete('/{testCategory}', [App\Http\Controllers\Admin\TestCategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{testCategory}/toggle-status', [App\Http\Controllers\Admin\TestCategoryController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/{testCategory}/manage-test-sets', [App\Http\Controllers\Admin\TestCategoryController::class, 'manageTestSets'])->name('manage-test-sets');
            Route::post('/{testCategory}/update-test-sets', [App\Http\Controllers\Admin\TestCategoryController::class, 'updateTestSets'])->name('update-test-sets');
            Route::post('/reorder', [App\Http\Controllers\Admin\TestCategoryController::class, 'reorder'])->name('reorder');
        });

    });
});