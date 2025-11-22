<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FullTest;
use App\Models\FullTestAttempt;
use App\Models\StudentAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FullTestController extends Controller
{
    /**
     * Display available full tests.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get all active categories
        $categories = \App\Models\TestCategory::active()
            ->ordered()
            ->get();
        
        // Get all full tests (premium tests will be shown as locked)
        $query = FullTest::active()->with('testSets');
        
        // Filter by category if selected
        $selectedCategory = null;
        if ($request->has('category') && $request->category) {
            $selectedCategory = \App\Models\TestCategory::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $query->whereHas('testSets', function ($q) use ($selectedCategory) {
                    $q->whereHas('categories', function ($catQuery) use ($selectedCategory) {
                        $catQuery->where('test_categories.id', $selectedCategory->id);
                    });
                });
            }
        }
        
        $fullTests = $query->orderBy('order_number')->get();
        
        // Get user's attempts
        $attempts = FullTestAttempt::where('user_id', $user->id)
            ->with('fullTest')
            ->latest()
            ->get()
            ->groupBy('full_test_id');
        
        return view('student.full-test.index', compact('fullTests', 'attempts', 'categories', 'selectedCategory'));
    }

    /**
     * Show onboarding/confirmation page.
     */
    public function onboarding(FullTest $fullTest)
    {
        $user = auth()->user();
        
        // Check if test is premium and user has access
        if ($fullTest->is_premium && !$user->hasFeature('premium_full_tests')) {
            return redirect()->route('subscription.plans')
                ->with('error', 'This full test is available for premium users only.');
        }
        
        // Check usage limit
        if (!$user->canTakeMoreTests()) {
            return redirect()->route('subscription.plans')
                ->with('error', 'You have reached your monthly test limit. Please upgrade your plan.');
        }
        
        // Check if test has minimum required sections
        if (!$fullTest->hasMinimumSections()) {
            return redirect()->route('student.full-test.index')
                ->with('error', 'This test is not properly configured. Minimum 3 sections are required.');
        }
        
        // Get any in-progress attempt
        $inProgressAttempt = FullTestAttempt::where('user_id', $user->id)
            ->where('full_test_id', $fullTest->id)
            ->where('status', 'in_progress')
            ->first();
        
        return view('student.full-test.onboarding', compact('fullTest', 'inProgressAttempt'));
    }

    /**
     * Start or resume full test.
     */
    public function start(Request $request, FullTest $fullTest)
    {
        $user = auth()->user();
        
        // Validate access
        if ($fullTest->is_premium && !$user->hasFeature('premium_full_tests')) {
            return redirect()->route('subscription.plans')
                ->with('error', 'This full test is available for premium users only.');
        }
        
        DB::beginTransaction();
        
        try {
            // Check for existing in-progress attempt
            $attempt = FullTestAttempt::where('user_id', $user->id)
                ->where('full_test_id', $fullTest->id)
                ->where('status', 'in_progress')
                ->first();
            
            if (!$attempt) {
                // Check usage limit before creating new attempt
                if (!$user->canTakeMoreTests()) {
                    DB::rollback();
                    return redirect()->route('subscription.plans')
                        ->with('error', 'You have reached your monthly test limit.');
                }
                
                // Get first available section
                $availableSections = $fullTest->getAvailableSections();
                $firstSection = $availableSections[0] ?? 'listening';
                
                // Create new attempt
                $attempt = FullTestAttempt::create([
                    'user_id' => $user->id,
                    'full_test_id' => $fullTest->id,
                    'start_time' => now(),
                    'status' => 'in_progress',
                    'current_section' => $firstSection
                ]);
                
                // Increment user's test count
                $user->incrementTestCount();
            }
            
            DB::commit();
            
            // Redirect to current/next section
            $nextSection = $attempt->getNextSection() ?? $attempt->current_section;
            
            return redirect()->route('student.full-test.section', [
                'fullTestAttempt' => $attempt->id,
                'section' => $nextSection
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('student.full-test.index')
                ->with('error', 'Failed to start test. Please try again.');
        }
    }

    /**
     * Display section test.
     */
    public function section(FullTestAttempt $fullTestAttempt, string $section)
    {
        // Validate user owns this attempt
        if ($fullTestAttempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if test is premium and user has access
        if ($fullTestAttempt->fullTest->is_premium && !auth()->user()->hasFeature('premium_full_tests')) {
            return redirect()->route('subscription.plans')
                ->with('error', 'This full test is available for premium users only.');
        }
        
        // Validate section
        if (!in_array($section, ['listening', 'reading', 'writing', 'speaking'])) {
            abort(404);
        }
        
        // Check if this test has this section
        if (!$fullTestAttempt->fullTest->hasSection($section)) {
            // Skip to next available section
            $nextSection = $fullTestAttempt->getNextSection();
            if ($nextSection) {
                return redirect()->route('student.full-test.section', [
                    'fullTestAttempt' => $fullTestAttempt->id,
                    'section' => $nextSection
                ]);
            } else {
                return redirect()->route('student.full-test.results', $fullTestAttempt);
            }
        }
        
        // Check if section already completed
        $sectionAttempt = $fullTestAttempt->sectionAttempts()
            ->where('section_type', $section)
            ->first();
        
        if ($sectionAttempt) {
            // Section already completed, redirect to next section or results
            $nextSection = $fullTestAttempt->getNextSection();
            
            if ($nextSection) {
                return redirect()->route('student.full-test.section', [
                    'fullTestAttempt' => $fullTestAttempt->id,
                    'section' => $nextSection
                ]);
            } else {
                return redirect()->route('student.full-test.results', $fullTestAttempt);
            }
        }
        
        // Update current section
        $fullTestAttempt->update(['current_section' => $section]);
        
        // Get test set for this section
        $testSet = $fullTestAttempt->fullTest->{$section . 'TestSet'}();
        
        if (!$testSet) {
            return redirect()->route('student.full-test.index')
                ->with('error', 'Test section not configured properly.');
        }
        
        // Create student attempt for this section
        $studentAttempt = StudentAttempt::create([
            'user_id' => auth()->id(),
            'test_set_id' => $testSet->id,
            'start_time' => now(),
            'status' => 'in_progress',
            'is_complete_attempt' => true,
            'total_questions' => $testSet->questions()->count()
        ]);
        
        // Link to full test attempt
        $fullTestAttempt->sectionAttempts()->create([
            'student_attempt_id' => $studentAttempt->id,
            'section_type' => $section
        ]);
        
        // Redirect to appropriate section controller
        switch ($section) {
            case 'listening':
                return redirect()->route('student.listening.start', $testSet);
            case 'reading':
                return redirect()->route('student.reading.start', $testSet);
            case 'writing':
                return redirect()->route('student.writing.start', $testSet);
            case 'speaking':
                return redirect()->route('student.speaking.start', $testSet);
        }
    }

    /**
     * Complete section and move to next.
     */
    public function completeSection(Request $request, FullTestAttempt $fullTestAttempt)
    {
        // This will be called via AJAX when a section is completed
        $section = $request->input('section');
        $score = $request->input('score');
        
        // Update section score
        if ($score !== null) {
            $fullTestAttempt->updateSectionScore($section, $score);
        }
        
        // Get next section
        $nextSection = $fullTestAttempt->getNextSection();
        
        if ($nextSection) {
            return response()->json([
                'success' => true,
                'next_url' => route('student.full-test.section', [
                    'fullTestAttempt' => $fullTestAttempt->id,
                    'section' => $nextSection
                ])
            ]);
        } else {
            // All sections completed
            $fullTestAttempt->markAsCompleted();
            
            return response()->json([
                'success' => true,
                'next_url' => route('student.full-test.results', $fullTestAttempt)
            ]);
        }
    }

    /**
     * Show test results.
     */
    public function results(FullTestAttempt $fullTestAttempt)
    {
        // Validate user owns this attempt
        if ($fullTestAttempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Refresh to get latest scores from database
        $fullTestAttempt->refresh();

        // Load all related data
        $fullTestAttempt->load([
            'fullTest',
            'sectionAttempts.studentAttempt.testSet.section'
        ]);

        // Ensure test is completed
        if (!$fullTestAttempt->isCompleted()) {
            $fullTestAttempt->markAsCompleted();
            $fullTestAttempt->refresh();
        }

        \Log::info("Full test results - Overall: " . ($fullTestAttempt->overall_band_score ?? 'null') .
                   ", L: " . ($fullTestAttempt->listening_score ?? 'null') .
                   ", R: " . ($fullTestAttempt->reading_score ?? 'null') .
                   ", W: " . ($fullTestAttempt->writing_score ?? 'null') .
                   ", S: " . ($fullTestAttempt->speaking_score ?? 'null'));

        return view('student.full-test.results', compact('fullTestAttempt'));
    }

    /**
     * Show detailed evaluation results for full test.
     */
    public function evaluationDetails(FullTestAttempt $fullTestAttempt)
    {
        // Validate user owns this attempt
        if ($fullTestAttempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Load all necessary relationships
        $fullTestAttempt->load([
            'fullTest',
            'sectionAttempts.studentAttempt' => function($query) {
                $query->with([
                    'testSet.section',
                    'answers.question',
                    'answers.selectedOption',
                    'answers.speakingRecording',
                    'humanEvaluationRequest.humanEvaluation.errorMarkings'
                ]);
            }
        ]);

        return view('student.full-test.evaluation-details', compact('fullTestAttempt'));
    }

    /**
     * Abandon test.
     */
    public function abandon(FullTestAttempt $fullTestAttempt)
    {
        // Validate user owns this attempt
        if ($fullTestAttempt->user_id !== auth()->id()) {
            abort(403);
        }

        $fullTestAttempt->update([
            'status' => 'abandoned',
            'end_time' => now()
        ]);

        return redirect()->route('student.full-test.index')
            ->with('info', 'Test has been abandoned. You can start a new attempt anytime.');
    }

    /**
     * Show teacher selection page for full test evaluation.
     */
    public function requestEvaluation(FullTestAttempt $fullTestAttempt)
    {
        // Validate user owns this attempt
        if ($fullTestAttempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Load section attempts with student attempts and evaluation requests
        $fullTestAttempt->load(['sectionAttempts.studentAttempt.humanEvaluationRequest', 'fullTest']);

        // Get writing and speaking sections that need evaluation
        $sectionsNeedingEvaluation = [];
        foreach ($fullTestAttempt->sectionAttempts as $sectionAttempt) {
            if (in_array($sectionAttempt->section_type, ['writing', 'speaking'])) {
                // Check if already requested
                if (!$sectionAttempt->studentAttempt->humanEvaluationRequest) {
                    $sectionsNeedingEvaluation[] = [
                        'type' => $sectionAttempt->section_type,
                        'student_attempt' => $sectionAttempt->studentAttempt
                    ];
                }
            }
        }

        if (empty($sectionsNeedingEvaluation)) {
            return redirect()->route('student.full-test.results', $fullTestAttempt)
                ->with('info', 'Evaluation already requested for all sections.');
        }

        // Get available teachers who can evaluate writing or speaking
        $teachers = \App\Models\Teacher::with('user')
            ->where('is_available', true)
            ->get()
            ->filter(function ($teacher) {
                $specializations = $teacher->specialization ?? [];
                return collect($specializations)->contains(function ($spec) {
                    return in_array(strtolower($spec), ['writing', 'speaking']);
                });
            })
            ->values();

        // Get user's token balance
        $tokenBalance = \App\Models\UserEvaluationToken::getOrCreateForUser(auth()->user());

        return view('student.full-test.request-evaluation', compact(
            'fullTestAttempt',
            'sectionsNeedingEvaluation',
            'teachers',
            'tokenBalance'
        ));
    }

    /**
     * Submit full test evaluation request.
     */
    public function submitEvaluationRequest(Request $request, FullTestAttempt $fullTestAttempt)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'priority' => 'required|in:normal,urgent',
            'sections' => 'required|array|min:1',
            'sections.*' => 'required|exists:student_attempts,id'
        ]);

        // Validate user owns this attempt
        if ($fullTestAttempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Load section attempts
        $fullTestAttempt->load(['sectionAttempts.studentAttempt.humanEvaluationRequest', 'fullTest']);

        // Get selected sections to evaluate
        $selectedStudentAttemptIds = $request->sections;
        $sectionsNeedingEvaluation = [];

        foreach ($fullTestAttempt->sectionAttempts as $sectionAttempt) {
            // Check if this section's student attempt was selected
            if (in_array($sectionAttempt->student_attempt_id, $selectedStudentAttemptIds)) {
                // Verify it's writing or speaking and not already requested
                if (in_array($sectionAttempt->section_type, ['writing', 'speaking'])) {
                    if (!$sectionAttempt->studentAttempt->humanEvaluationRequest) {
                        $sectionsNeedingEvaluation[] = $sectionAttempt;
                    }
                }
            }
        }

        if (empty($sectionsNeedingEvaluation)) {
            return redirect()->route('student.full-test.results', $fullTestAttempt)
                ->with('info', 'Selected sections have already been requested for evaluation.');
        }

        $teacher = \App\Models\Teacher::findOrFail($request->teacher_id);
        $isPriority = $request->priority === 'urgent';

        // Calculate total token cost
        $totalTokenCost = 0;
        foreach ($sectionsNeedingEvaluation as $sectionAttempt) {
            $sectionType = $sectionAttempt->section_type;
            $sectionTokenCost = $teacher->calculateTokenPrice($sectionType, $isPriority);
            $totalTokenCost += $sectionTokenCost;

            \Log::info("Token calculation for {$sectionType}: {$sectionTokenCost} tokens (Priority: " . ($isPriority ? 'Yes' : 'No') . ")");
        }

        \Log::info("Total token cost for full test evaluation: {$totalTokenCost} tokens");

        // Check token balance
        $tokenBalance = \App\Models\UserEvaluationToken::getOrCreateForUser(auth()->user());
        \Log::info("User token balance before deduction: {$tokenBalance->available_tokens} tokens");

        if (!$tokenBalance->hasTokens($totalTokenCost)) {
            return redirect()->route('student.tokens.purchase')
                ->with('error', "You need {$totalTokenCost} tokens for this evaluation. Your balance: {$tokenBalance->available_tokens}");
        }

        DB::beginTransaction();

        try {
            // Deduct tokens
            $tokenBalance->useTokens($totalTokenCost);
            \Log::info("Tokens deducted successfully. New balance: {$tokenBalance->available_tokens} tokens");

            // Create evaluation requests for each section
            foreach ($sectionsNeedingEvaluation as $sectionAttempt) {
                $studentAttempt = $sectionAttempt->studentAttempt;
                $sectionType = $sectionAttempt->section_type;
                $sectionTokenCost = $teacher->calculateTokenPrice($sectionType, $isPriority);

                $evaluationRequest = \App\Models\HumanEvaluationRequest::create([
                    'student_attempt_id' => $studentAttempt->id,
                    'student_id' => auth()->id(),
                    'teacher_id' => $teacher->id,
                    'tokens_used' => $sectionTokenCost,
                    'status' => 'assigned',
                    'priority' => $isPriority ? 'urgent' : 'normal',
                    'requested_at' => now(),
                    'assigned_at' => now(),
                    'deadline_at' => now()->addHours($isPriority ? 12 : 48)
                ]);

                // Log token transaction
                DB::table('token_transactions')->insert([
                    'user_id' => auth()->id(),
                    'type' => 'usage',
                    'amount' => -$sectionTokenCost,
                    'balance_after' => $tokenBalance->available_tokens,
                    'reason' => "Full test evaluation - {$sectionType} section ({$fullTestAttempt->fullTest->title})",
                    'evaluation_request_id' => $evaluationRequest->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            // Send notification to teacher
            try {
                $teacher->user->notify(new \App\Notifications\NewEvaluationRequest(
                    \App\Models\HumanEvaluationRequest::where('teacher_id', $teacher->id)
                        ->whereIn('student_attempt_id', collect($sectionsNeedingEvaluation)->pluck('student_attempt_id'))
                        ->latest()
                        ->first()
                ));
            } catch (\Exception $e) {
                \Log::error('Failed to send notification to teacher', [
                    'teacher_id' => $teacher->id,
                    'error' => $e->getMessage()
                ]);
            }

            $sectionCount = count($sectionsNeedingEvaluation);
            $sectionNames = collect($sectionsNeedingEvaluation)->pluck('section_type')->map(function($type) {
                return ucfirst($type);
            })->join(' and ');

            return redirect()->route('student.full-test.results', $fullTestAttempt)
                ->with('success', "Evaluation request submitted successfully! Your {$sectionNames} " .
                       ($sectionCount > 1 ? 'sections are' : 'section is') . " now being evaluated.");

        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Failed to submit full test evaluation request', [
                'error' => $e->getMessage(),
                'full_test_attempt_id' => $fullTestAttempt->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to submit evaluation request. Please try again.');
        }
    }
    
    /**
     * Show section completed screen.
     */
    public function sectionCompleted(FullTestAttempt $fullTestAttempt, string $section)
    {
        // Validate user owns this attempt
        if ($fullTestAttempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Validate section name
        if (!in_array($section, ['listening', 'reading', 'writing', 'speaking'])) {
            abort(404);
        }

        // Refresh to get latest scores from database
        $fullTestAttempt->refresh();

        // Store completed section name
        $completedSection = $section;

        // Load full test with sections
        $fullTestAttempt->load('fullTest', 'sectionAttempts');

        // Get section score if available
        $sectionScore = null;
        if (in_array($completedSection, ['listening', 'reading'])) {
            $scoreField = $completedSection . '_score';
            $sectionScore = $fullTestAttempt->$scoreField;
            \Log::info("Section completed - {$completedSection} score: " . ($sectionScore ?? 'null'));
        }
        
        // Get available sections and completed sections
        $availableSections = $fullTestAttempt->fullTest->getAvailableSections();
        $completedSectionsList = $fullTestAttempt->sectionAttempts->pluck('section_type')->toArray();
        
        // Calculate progress
        $completedSections = count($completedSectionsList);
        $totalSections = count($availableSections);
        $progressPercentage = $totalSections > 0 ? round(($completedSections / $totalSections) * 100) : 0;
        
        // Get next section
        $nextSection = $fullTestAttempt->getNextSection();
        $hasNextSection = $nextSection !== null;
        
        // Full test attempt ID
        $fullTestAttemptId = $fullTestAttempt->id;
        
        return view('student.full-test.section-completed', compact(
            'fullTestAttempt',
            'completedSection',
            'sectionScore',
            'availableSections',
            'completedSectionsList',
            'completedSections',
            'totalSections',
            'progressPercentage',
            'nextSection',
            'hasNextSection',
            'fullTestAttemptId'
        ));
    }
}
