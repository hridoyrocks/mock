<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\TestSet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WritingTestController extends Controller
{
    /**
     * Display a listing of the available writing tests.
     */
    public function index(Request $request): View
    {
        // Get all active categories with counts for writing section
        $categories = \App\Models\TestCategory::active()
            ->ordered()
            ->withCount(['testSets as writing_count' => function ($query) {
                $query->whereHas('section', function ($q) {
                    $q->where('slug', 'writing')->orWhere('name', 'writing');
                })->where('active', true);
            }])
            ->get();
        
        // Get test sets query
        $testSetsQuery = TestSet::whereHas('section', function ($query) {
            $query->where('name', 'writing');
        })->where('active', true);
        
        // Filter by category if selected
        $selectedCategory = null;
        if ($request->has('category') && $request->category) {
            $selectedCategory = \App\Models\TestCategory::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $testSetsQuery->whereHas('categories', function ($query) use ($selectedCategory) {
                    $query->where('test_categories.id', $selectedCategory->id);
                });
            }
        }
        
        $testSets = $testSetsQuery->get();
        
        return view('student.test.writing.index', compact('testSets', 'categories', 'selectedCategory'));
    }
    
    /**
     * Show candidate information confirmation screen.
     */
    public function confirmDetails(TestSet $testSet)
    {
        // Check if the test belongs to writing section
        if ($testSet->section->name !== 'writing') {
            abort(404);
        }

        // Check if test is premium and user has access
        if ($testSet->is_premium && !auth()->user()->hasFeature('premium_test_sets')) {
            return redirect()->route('subscription.plans')
                ->with('error', 'This test is available for premium users only.');
        }

        // Get all attempts for this test
        $attempts = StudentAttempt::getAllAttemptsForUserAndTest(auth()->id(), $testSet->id);

        // Show previous attempts if any exist
        $latestAttempt = $attempts->first();
        $canRetake = $latestAttempt && $latestAttempt->canRetake();

        return view('student.test.writing.onboarding.confirm-details', compact('testSet', 'attempts', 'canRetake'));
    }

    /**
     * Show the instructions screen.
     */
    public function instructions(TestSet $testSet): View
    {
        // Check if the test belongs to writing section
        if ($testSet->section->name !== 'writing') {
            abort(404);
        }
        
        return view('student.test.writing.onboarding.instructions', compact('testSet'));
    }
    
    /**
     * Start a new writing test.
     */
    public function start(TestSet $testSet)
    {
        // Debug: Log what's happening
        \Log::info('Writing Test Start Method Called', [
            'test_set_id' => $testSet->id,
            'section' => $testSet->section->name ?? 'no section',
            'user_id' => auth()->id()
        ]);

        // Check if test is premium and user has access
        if ($testSet->is_premium && !auth()->user()->hasFeature('premium_test_sets')) {
            return redirect()->route('subscription.plans')
                ->with('error', 'This test is available for premium users only.');
        }

        // Check if the test belongs to writing section
        if ($testSet->section->name !== 'writing') {
            \Log::error('Not a writing test', ['section' => $testSet->section->name]);
            abort(404);
        }
        
        // No need to check for existing completed attempts - allow retakes
        
        // Get all questions for this test set
        $allQuestions = $testSet->questions()->get();
        \Log::info('All questions in test set', [
            'count' => $allQuestions->count(),
            'questions' => $allQuestions->map(function($q) {
                return [
                    'id' => $q->id,
                    'order_number' => $q->order_number,
                    'part_number' => $q->part_number,
                    'type' => $q->question_type
                ];
            })
        ]);
        
        // Get writing questions with more flexible criteria
        $questions = $testSet->questions()
            ->whereIn('part_number', [1, 2])
            ->orderBy('part_number')
            ->orderBy('order_number')
            ->get();
        
        \Log::info('Filtered writing questions', [
            'count' => $questions->count(),
            'questions' => $questions->toArray()
        ]);
        
        // If no questions found, try without part_number filter
        if ($questions->count() < 2) {
            \Log::warning('Not enough questions with part_number 1,2. Trying all questions.');
            
            // Get any 2 questions from the test set
            $questions = $testSet->questions()
                ->orderBy('order_number')
                ->limit(2)
                ->get();
                
            \Log::info('Questions without part filter', [
                'count' => $questions->count()
            ]);
        }
        
        // Ensure we have at least 2 questions
        if ($questions->count() < 2) {
            \Log::error('Not enough questions in test set');
            return redirect()->route('student.writing.index')
                ->with('error', 'This writing test needs at least 2 questions. Found: ' . $questions->count());
        }
        
        // Assign questions to tasks
        $taskOneQuestion = $questions->first();
        $taskTwoQuestion = $questions->skip(1)->first();
        
        \Log::info('Task questions assigned', [
            'task1' => $taskOneQuestion ? $taskOneQuestion->id : 'null',
            'task2' => $taskTwoQuestion ? $taskTwoQuestion->id : 'null'
        ]);
        
        // Check if there's an ongoing attempt
        $attempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'in_progress')
            ->first();
            
        if (!$attempt) {
            // Get the latest completed attempt to determine attempt number
            $latestAttempt = StudentAttempt::getLatestAttempt(auth()->id(), $testSet->id);
            
            $attemptNumber = 1;
            $isRetake = false;
            $originalAttemptId = null;
            
            if ($latestAttempt) {
                // This is a retake
                $attemptNumber = $latestAttempt->attempt_number + 1;
                $isRetake = true;
                $originalAttemptId = $latestAttempt->original_attempt_id ?? $latestAttempt->id;
            }
            
            // Create a new attempt
            $attempt = StudentAttempt::create([
                'user_id' => auth()->id(),
                'test_set_id' => $testSet->id,
                'start_time' => now(),
                'status' => 'in_progress',
                'attempt_number' => $attemptNumber,
                'is_retake' => $isRetake,
                'original_attempt_id' => $originalAttemptId,
            ]);
            
            \Log::info('Created new attempt', ['attempt_id' => $attempt->id]);
            
            // Pre-create answer records for both tasks
            foreach ([$taskOneQuestion, $taskTwoQuestion] as $question) {
                if ($question) {
                    StudentAnswer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'answer' => '',
                    ]);
                }
            }
        }
        
        // Load attempt with answers
        $attempt->load('answers');
        
        \Log::info('Returning view with data');
        
        return view('student.test.writing.test', compact('testSet', 'attempt'));
    }
    
    /**
     * Auto-save the writing test answer.
     */
    public function autosave(Request $request, StudentAttempt $attempt, Question $question): JsonResponse
    {
        // Verify the attempt belongs to the current user and is not completed
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Invalid attempt']);
        }
        
        $request->validate([
            'content' => 'required|string',
        ]);
        
        // Find or create the answer
        $answer = StudentAnswer::firstOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            [
                'answer' => $request->content,
            ]
        );
        
        // Update the answer if it already exists
        if (!$answer->wasRecentlyCreated) {
            $answer->update([
                'answer' => $request->content,
            ]);
        }
        
        // Update completion rate on autosave (optional)
        $this->updateCompletionRate($attempt);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Submit the writing test answers.
     */
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
    {
        // Verify the attempt belongs to the current user and is not already completed
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return redirect()->route('student.writing.index')
                ->with('error', 'Invalid attempt or test already submitted.');
        }
        
        // Check if this is part of a full test
        $fullTestSectionAttempt = \App\Models\FullTestSectionAttempt::where('student_attempt_id', $attempt->id)->first();
        $isPartOfFullTest = $fullTestSectionAttempt !== null;
        
        // Validate the submission
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);
        
        DB::transaction(function () use ($request, $attempt, $isPartOfFullTest, $fullTestSectionAttempt) {
            $totalQuestions = 0;
            $answeredQuestions = 0;
            
            // Save answers
            foreach ($request->answers as $questionId => $content) {
                $totalQuestions++;
                
                // Find existing answer
                $answer = StudentAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $questionId)
                    ->first();
                
                if ($answer) {
                    $answer->update(['answer' => $content]);
                } else {
                    StudentAnswer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                        'answer' => $content,
                    ]);
                }
                
                // Count as answered if content is not empty
                if (!empty(trim($content))) {
                    $answeredQuestions++;
                }
            }
            
            // Calculate completion rate
            $completionRate = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100, 2) : 0;
            
            // Mark attempt as completed with proper stats
            $attempt->update([
                'end_time' => now(),
                'status' => 'completed',
                'completion_rate' => $completionRate,
                'total_questions' => $totalQuestions,
                'answered_questions' => $answeredQuestions,
                'is_complete_attempt' => ($completionRate >= 80), // Consider 80%+ as complete
            ]);

            // Increment test count
            auth()->user()->incrementTestCount();
            
            // If part of full test, update full test attempt with placeholder score
            // Writing needs human evaluation, so we'll set a placeholder score of 0
            if ($isPartOfFullTest && $fullTestSectionAttempt) {
                $fullTestAttempt = $fullTestSectionAttempt->fullTestAttempt;
                // Set placeholder score - will be updated after evaluation
                $fullTestAttempt->updateSectionScore('writing', 0.0);
            }
        });
        
        // If part of full test, redirect to section completed screen
        if ($isPartOfFullTest && $fullTestSectionAttempt) {
            $fullTestAttempt = $fullTestSectionAttempt->fullTestAttempt;
            
            return redirect()->route('student.full-test.section-completed', [
                'fullTestAttempt' => $fullTestAttempt->id,
                'section' => 'writing'
            ])->with('success', 'Writing section completed successfully!');
        }
        
        // Regular test completion
        return redirect()->route('student.results.show', $attempt)
            ->with('success', 'Test submitted successfully!');
    }
    
    /**
     * Update completion rate for an attempt
     */
    private function updateCompletionRate(StudentAttempt $attempt)
    {
        $totalQuestions = $attempt->testSet->questions()->count();
        $answeredQuestions = 0;
        
        // Get all answers for this attempt
        $answers = $attempt->answers()->with('question')->get();
        
        foreach ($answers as $answer) {
            // Count as answered if has content
            if (!empty(trim($answer->answer))) {
                $answeredQuestions++;
            }
        }
        
        $completionRate = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100, 2) : 0;
        
        $attempt->update([
            'completion_rate' => $completionRate,
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
        ]);
    }
}