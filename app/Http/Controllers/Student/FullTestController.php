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
        
        // Get full tests query based on user's subscription
        $query = FullTest::active()->with('testSets');
        
        if (!$user->hasFeature('premium_full_tests')) {
            $query->free();
        }
        
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
        
        // Check if test has all sections
        if (!$fullTest->hasAllSections()) {
            return redirect()->route('student.full-test.index')
                ->with('error', 'This test is not properly configured. Please try another test.');
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
                
                // Create new attempt
                $attempt = FullTestAttempt::create([
                    'user_id' => $user->id,
                    'full_test_id' => $fullTest->id,
                    'start_time' => now(),
                    'status' => 'in_progress',
                    'current_section' => 'listening'
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
        
        // Validate section
        if (!in_array($section, ['listening', 'reading', 'writing', 'speaking'])) {
            abort(404);
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
        
        // Load all related data
        $fullTestAttempt->load([
            'fullTest',
            'sectionAttempts.studentAttempt.testSet.section',
            'listeningAttempt',
            'readingAttempt',
            'writingAttempt',
            'speakingAttempt'
        ]);
        
        // Ensure test is completed
        if (!$fullTestAttempt->isCompleted()) {
            $fullTestAttempt->markAsCompleted();
        }
        
        return view('student.full-test.results', compact('fullTestAttempt'));
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
}
