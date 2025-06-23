<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\TestSet;
use App\Models\TestSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    /**
     * Display a listing of the questions.
     */
    public function index(Request $request): View
    {
        $query = Question::with(['testSet', 'testSet.section', 'options']);
        
        // Filter by section
        if ($request->has('section')) {
            $query->whereHas('testSet.section', function ($q) use ($request) {
                $q->where('name', $request->section);
            });
        }
        
        // Filter by test set
        if ($request->has('test_set')) {
            $query->where('test_set_id', $request->test_set);
        }
        
        // Filter by part
        if ($request->has('part')) {
            $query->where('part_number', $request->part);
        }
        
        $questions = $query->orderBy('test_set_id')
                          ->orderBy('part_number')
                          ->orderBy('order_number')
                          ->paginate(20);
        
        // Get test sets for filtering
        $testSets = TestSet::with('section')->get();
        
        return view('admin.questions.index', compact('questions', 'testSets'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Request $request): View
    {
        // If test_set is provided, use that specific test set
        if ($request->has('test_set')) {
            $testSet = TestSet::with('section')->findOrFail($request->test_set);
            
            // Section-based blade file selection
            $sectionView = match($testSet->section->name) {
                'listening' => 'admin.questions.create.listening',
                'reading' => $this->handleReadingCreate($testSet, $request),
                'writing' => 'admin.questions.create.writing',
                'speaking' => 'admin.questions.create.speaking',
                default => 'admin.questions.create.reading' // fallback
            };
            
            // If it's not reading or returns a view directly, handle normally
            if ($testSet->section->name !== 'reading' || is_string($sectionView)) {
                $existingQuestions = $testSet->questions()->orderBy('order_number')->get();
                $nextQuestionNumber = $existingQuestions->count() > 0 
                    ? $existingQuestions->max('order_number') + 1 
                    : 1;
                
                return view($sectionView, compact('testSet', 'existingQuestions', 'nextQuestionNumber'));
            }
            
            // For reading, the view response is already handled in handleReadingCreate
            return $sectionView;
        }
        
        // Otherwise show test set selection
        $testSets = TestSet::with('section')->where('active', true)->get();
        $sections = TestSection::all();
        
        return view('admin.questions.select-test-set', compact('testSets', 'sections'));
    }

    /**
     * Handle Reading section creation logic (2-step process)
     */
    private function handleReadingCreate(TestSet $testSet, Request $request)
    {
        // Check if there's already a passage for this test set
        $existingPassage = $testSet->questions()
            ->where('question_type', 'passage')
            ->first();
        
        // If no passage exists, show Step 1: Create Passage
        if (!$existingPassage) {
            $existingQuestions = $testSet->questions()->orderBy('order_number')->get();
            $nextQuestionNumber = $existingQuestions->count() > 0 
                ? $existingQuestions->max('order_number') + 1 
                : 0; // Passage starts at 0
            
            return view('admin.questions.create.reading', compact('testSet', 'existingQuestions', 'nextQuestionNumber'));
        }
        
        // If passage exists, show Step 2: Add Questions
        return $this->showReadingQuestionForm($testSet, $existingPassage);
    }

    /**
     * Show Reading Question Creation Form (Step 2)
     */
    private function showReadingQuestionForm(TestSet $testSet, Question $passage)
    {
        // Get existing questions (excluding passage and sub-questions)
        $existingQuestions = $testSet->questions()
            ->where('question_type', '!=', 'passage')
            ->where('is_sub_question', false)
            ->orderBy('order_number')
            ->get();
        
        $nextQuestionNumber = $existingQuestions->count() > 0 
            ? $existingQuestions->max('order_number') + 1 
            : 1;
        
        // Process passage content to extract markers
        $passageContent = $passage->passage_text ?? $passage->content;
        $availableMarkers = $this->extractMarkersFromPassage($passageContent);
        $markerTexts = $this->getMarkerTexts($passageContent, $availableMarkers);
        $processedPassage = $this->processPassageForDisplay($passageContent);
        
        return view('admin.questions.create.reading-question', compact(
            'testSet', 
            'passage', 
            'existingQuestions', 
            'nextQuestionNumber',
            'availableMarkers',
            'markerTexts',
            'processedPassage'
        ));
    }

    /**
     * Extract markers from passage content
     */
    private function extractMarkersFromPassage(string $passageContent): array
    {
        preg_match_all('/\{\{(Q\d+)\}\}/', $passageContent, $matches);
        return array_unique($matches[1] ?? []);
    }

    /**
     * Get text content for each marker
     */
    private function getMarkerTexts(string $passageContent, array $markers): array
    {
        $markerTexts = [];
        
        foreach ($markers as $marker) {
            $pattern = '/\{\{' . preg_quote($marker) . '\}\}(.*?)\{\{' . preg_quote($marker) . '\}\}/s';
            if (preg_match($pattern, $passageContent, $matches)) {
                $markerTexts[$marker] = trim(strip_tags($matches[1]));
            }
        }
        
        return $markerTexts;
    }

    /**
     * Process passage for display with highlighted markers
     */
    private function processPassageForDisplay(string $passageContent): string
    {
        // Convert markers to highlighted spans for display
        $processed = preg_replace_callback(
            '/\{\{(Q\d+)\}\}(.*?)\{\{\\1\}\}/s',
            function($matches) {
                $markerId = $matches[1];
                $text = $matches[2];
                return '<span class="marker-highlight" data-marker="' . $markerId . '" title="Question Location: ' . $markerId . '">' . $text . '</span>';
            },
            $passageContent
        );
        
        return $processed;
    }

    /**
     * Show form for creating reading passage specifically
     */
    public function createReadingPassage(TestSet $testSet): View
    {
        // Ensure this is a reading test set
        if ($testSet->section->name !== 'reading') {
            abort(404);
        }
        
        $existingQuestions = $testSet->questions()->orderBy('order_number')->get();
        $nextQuestionNumber = $existingQuestions->count() > 0 
            ? $existingQuestions->max('order_number') + 1 
            : 0; // Passage starts at 0
        
        return view('admin.questions.create.reading', compact('testSet', 'existingQuestions', 'nextQuestionNumber'));
    }

    /**
     * Create Reading Question (Step 2)
     */
    public function createReadingQuestion(TestSet $testSet): View
    {
        // Ensure this is a reading test set
        if ($testSet->section->name !== 'reading') {
            abort(404);
        }
        
        // Find the passage for this test set
        $passage = $testSet->questions()
            ->where('question_type', 'passage')
            ->first();
        
        if (!$passage) {
            return redirect()->route('admin.questions.create', ['test_set' => $testSet->id])
                ->with('error', 'Please create a reading passage first before adding questions.');
        }
        
        return $this->showReadingQuestionForm($testSet, $passage);
    }

    /**
     * API route for getting passage markers (AJAX)
     */
    public function getPassageMarkers(TestSet $testSet): JsonResponse
    {
        $passage = $testSet->questions()
            ->where('question_type', 'passage')
            ->first();
        
        if (!$passage) {
            return response()->json(['markers' => [], 'markerTexts' => []]);
        }
        
        $passageContent = $passage->passage_text ?? $passage->content;
        $availableMarkers = $this->extractMarkersFromPassage($passageContent);
        $markerTexts = $this->getMarkerTexts($passageContent, $availableMarkers);
        
        return response()->json([
            'markers' => $availableMarkers,
            'markerTexts' => $markerTexts,
            'passageContent' => $passageContent
        ]);
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Get test set to determine section
        $testSet = TestSet::with('section')->findOrFail($request->test_set_id);
        $section = $testSet->section->name;
        
        // Handle Reading section specially
        if ($section === 'reading') {
            return $this->handleReadingStore($request, $testSet);
        }
        
        // Handle other sections with existing logic
        return $this->handleRegularStore($request, $testSet);
    }

    /**
     * Handle Reading section storage (both passage and questions)
     */
    private function handleReadingStore(Request $request, TestSet $testSet): RedirectResponse
    {
        $questionType = $request->question_type;
        
        if ($questionType === 'passage') {
            return $this->storeReadingPassage($request, $testSet);
        } else {
            return $this->storeReadingQuestion($request, $testSet);
        }
    }

    /**
     * Store Reading Passage (Step 1)
     */
     private function storeReadingPassage(Request $request, TestSet $testSet): RedirectResponse
    {
        // Validate passage data
        $validated = $request->validate([
            'test_set_id' => 'required|exists:test_sets,id',
            'instructions' => 'required|string|max:255', // Passage title
            'content' => 'required|string|min:200', // Passage content from TinyMCE
            'order_number' => 'required|integer|min:0',
            'part_number' => 'integer|min:1|max:3',
        ]);
        
        DB::transaction(function () use ($request, $testSet) {
            // Check if passage already exists for this test set
            $existingPassage = $testSet->questions()
                ->where('question_type', 'passage')
                ->first();
            
            if ($existingPassage) {
                // Update existing passage
                $existingPassage->update([
                    'instructions' => $request->instructions,
                    'content' => $request->content,
                    'passage_text' => $request->content, // Store in both fields
                    'order_number' => $request->order_number,
                    'part_number' => $request->part_number ?? 1,
                    'marks' => 0,
                ]);
            } else {
                // Create new passage
                Question::create([
                    'test_set_id' => $testSet->id,
                    'question_type' => 'passage',
                    'content' => $request->content, // Main content
                    'passage_text' => $request->content, // Duplicate for compatibility
                    'instructions' => $request->instructions, // Title
                    'order_number' => $request->order_number,
                    'part_number' => $request->part_number ?? 1,
                    'marks' => 0,
                    'is_example' => false,
                ]);
            }
        });
        
        // Redirect to Step 2: Add Questions
        return redirect()->route('admin.questions.create', ['test_set' => $testSet->id])
            ->with('success', 'Reading passage saved successfully! Now you can add questions.');
    }

    /**
     * Store Reading Question (Step 2)
     */
    private function storeReadingQuestion(Request $request, TestSet $testSet): RedirectResponse
    {
        // Base validation rules for reading questions
        $rules = [
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|string',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'part_number' => 'integer|min:1|max:3',
            'marks' => 'integer|min:0|max:10',
            'instructions' => 'nullable|string',
            'marker_id' => 'nullable|string', // Q1, Q2, etc.
            'explanation' => 'nullable|string',
            'passage_reference' => 'nullable|string',
            'tips' => 'nullable|string',
            'common_mistakes' => 'nullable|string',
            'difficulty_level' => 'nullable|in:easy,medium,hard',
        ];
        
        // Add options validation if needed
        if ($this->requiresOptions($request->question_type)) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.content'] = 'required|string';
            $rules['correct_option'] = 'required|integer|min:0';
        }
        
        // Add validation for gap fill answers
        if ($request->has('blank_answers')) {
            $rules['blank_answers'] = 'array';
            $rules['blank_answers.*'] = 'nullable|string';
        }
        
        if ($request->has('dropdown_options')) {
            $rules['dropdown_options'] = 'array';
            $rules['dropdown_options.*'] = 'nullable|string';
            $rules['dropdown_correct'] = 'array';
            $rules['dropdown_correct.*'] = 'nullable|integer';
        }
        
        $request->validate($rules);
        
        DB::transaction(function () use ($request, $testSet) {
            // Check if we need to reorder existing questions
            $existingQuestion = Question::where('test_set_id', $testSet->id)
                ->where('order_number', $request->order_number)
                ->where('question_type', '!=', 'passage')
                ->exists();
                
            if ($existingQuestion) {
                // Increment order numbers of existing questions
                Question::where('test_set_id', $testSet->id)
                    ->where('order_number', '>=', $request->order_number)
                    ->where('question_type', '!=', 'passage')
                    ->increment('order_number');
            }
            
            // Prepare section specific data for gap fill/dropdown
            $sectionSpecificData = null;
            if ($request->has('blank_answers') || $request->has('dropdown_options')) {
                $sectionSpecificData = [
                    'blank_answers' => $request->blank_answers ?? [],
                    'dropdown_options' => $request->dropdown_options ?? [],
                    'dropdown_correct' => $request->dropdown_correct ?? []
                ];
            }
            
            // Create the question
            $question = Question::create([
                'test_set_id' => $testSet->id,
                'question_type' => $request->question_type,
                'content' => $request->content,
                'order_number' => $request->order_number,
                'part_number' => $request->part_number ?? 1,
                'marks' => $request->marks ?? 1,
                'instructions' => $request->instructions,
                'marker_id' => $request->marker_id,
                'explanation' => $request->explanation,
                'passage_reference' => $request->passage_reference,
                'tips' => $request->tips,
                'common_mistakes' => $request->common_mistakes,
                'difficulty_level' => $request->difficulty_level,
                'section_specific_data' => $sectionSpecificData,
                'is_example' => false,
            ]);
            
            // Handle blanks if present
            $blankCount = $question->countBlanks();
            if ($blankCount > 0) {
                $question->blank_count = $blankCount;
                $question->save();
                
                // Create sub-questions for tracking each blank separately
                for ($i = 1; $i <= $blankCount; $i++) {
                    Question::create([
                        'test_set_id' => $question->test_set_id,
                        'question_type' => 'sub_blank',
                        'content' => "Blank {$i} of Question {$question->order_number}",
                        'order_number' => $question->order_number + $i,
                        'part_number' => $question->part_number,
                        'marks' => 1,
                        'is_sub_question' => true,
                        'parent_question_id' => $question->id,
                        'sub_question_index' => $i,
                    ]);
                }
                
                // Recalculate all order numbers for the test set
                Question::recalculateOrderNumbers($question->test_set_id);
            }
            
            // Create options if applicable
            if ($this->requiresOptions($request->question_type) && isset($request->options)) {
                foreach ($request->options as $index => $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $option['content'],
                        'is_correct' => ($request->correct_option == $index),
                    ]);
                }
            }
        });
        
        // Redirect based on action
        if ($request->action === 'save_and_new') {
            return redirect()->route('admin.questions.create', ['test_set' => $testSet->id])
                ->with('success', 'Question created successfully. Add another question.');
        }
        
        return redirect()->route('admin.test-sets.show', $testSet->id)
            ->with('success', 'Reading question created successfully.');
    }

    /**
     * Handle regular (non-reading) question storage
     */
    private function handleRegularStore(Request $request, TestSet $testSet): RedirectResponse
    {
        // Debug logging
        \Log::info('Question Store Request', [
            'question_type' => $request->question_type,
            'has_content' => $request->has('content'),
            'has_passage_text' => $request->has('passage_text'),
            'content_length' => strlen($request->content ?? ''),
            'passage_text_length' => strlen($request->passage_text ?? ''),
            'blank_answers' => $request->blank_answers ?? [],
            'dropdown_options' => $request->dropdown_options ?? [],
            'dropdown_correct' => $request->dropdown_correct ?? []
        ]);
        
        $section = $testSet->section->name;
        
        // Base validation rules
        $rules = [
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|string',
            'order_number' => 'required|integer|min:0',
            'part_number' => 'nullable|integer',
            'question_group' => 'nullable|string',
            'marks' => 'nullable|integer|min:0|max:10',
            'is_example' => 'nullable|boolean',
            'instructions' => 'nullable|string',
            'passage_text' => 'nullable|string',
            'audio_transcript' => 'nullable|string',
        ];
        
        // Handle passage type specially
        if ($request->question_type === 'passage') {
            $rules['content'] = 'nullable|string'; // Make content optional for passage
            $rules['passage_text'] = 'required|string'; // Always required for passage type
            $rules['order_number'] = 'required|integer|min:0'; // Allow 0 for passages
            $rules['marks'] = 'nullable|integer|min:0'; // Allow 0 for passages
        } else {
            $rules['content'] = 'required|string';
        }
        
        // Section-specific validation
        if ($section === 'listening') {
            $rules['media'] = 'required_if:question_type,!=,passage|file|mimes:mp3,wav,ogg|max:51200';
            $rules['part_number'] = 'required|integer|min:1|max:4';
        } elseif ($section === 'writing') {
            $rules['word_limit'] = 'required|integer|min:50|max:500';
            $rules['time_limit'] = 'required|integer|min:1|max:60';
            if (strpos($request->question_type, 'task1') !== false) {
                $rules['media'] = 'required|file|mimes:jpg,jpeg,png,gif|max:5120';
            }
        } elseif ($section === 'speaking') {
            $rules['time_limit'] = 'required|integer|min:1|max:10';
        }
        
        // Add options validation if needed
        if ($this->requiresOptions($request->question_type)) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.content'] = 'required|string';
            $rules['correct_option'] = 'required|integer|min:0';
        }
        
        // Add validation for gap fill answers
        if ($request->has('blank_answers')) {
            $rules['blank_answers'] = 'array';
            $rules['blank_answers.*'] = 'nullable|string';
        }
        
        if ($request->has('dropdown_options')) {
            $rules['dropdown_options'] = 'array';
            $rules['dropdown_options.*'] = 'nullable|string';
            $rules['dropdown_correct'] = 'array';
            $rules['dropdown_correct.*'] = 'nullable|integer';
        }
        
        $request->validate($rules);
        
        // Handle file upload
        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('questions/' . $section, 'public');
        }
        
        DB::transaction(function () use ($request, $mediaPath) {
            // Check if we need to reorder existing questions
            $existingQuestion = Question::where('test_set_id', $request->test_set_id)
                ->where('order_number', $request->order_number)
                ->exists();
                
            if ($existingQuestion) {
                // Increment order numbers of existing questions
                Question::where('test_set_id', $request->test_set_id)
                    ->where('order_number', '>=', $request->order_number)
                    ->increment('order_number');
            }
            
            // Prepare section specific data for gap fill/dropdown
            $sectionSpecificData = null;
            if ($request->has('blank_answers') || $request->has('dropdown_options')) {
                $sectionSpecificData = [
                    'blank_answers' => $request->blank_answers ?? [],
                    'dropdown_options' => $request->dropdown_options ?? [],
                    'dropdown_correct' => $request->dropdown_correct ?? []
                ];
                
                \Log::info('Saving Gap Fill/Dropdown Data', [
                    'section_specific_data' => $sectionSpecificData
                ]);
            }
            
            // Prepare question data
            $questionData = [
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'order_number' => $request->order_number,
                'part_number' => $request->part_number,
                'question_group' => $request->question_group,
                'marks' => $request->marks ?? 1,
                'is_example' => $request->is_example ?? false,
                'instructions' => $request->instructions,
                'audio_transcript' => $request->audio_transcript,
                'word_limit' => $request->word_limit ?? null,
                'time_limit' => $request->time_limit ?? null,
                'media_path' => $mediaPath,
                'section_specific_data' => $sectionSpecificData,
            ];
            
            // Handle content based on question type
            if ($request->question_type === 'passage') {
                // For passages, use passage_text as content
                $questionData['content'] = $request->passage_text;
                $questionData['passage_text'] = $request->passage_text;
                
                // Use passage_title as instructions if provided
                if ($request->filled('passage_title')) {
                    $questionData['instructions'] = $request->passage_title;
                }
                
                \Log::info('Passage data prepared', [
                    'title' => $request->passage_title ?? 'No title',
                    'textLength' => strlen($request->passage_text ?? '')
                ]);
            } else {
                // For regular questions
                $questionData['content'] = $request->content;
                $questionData['passage_text'] = $request->passage_text;
            }

            $question = Question::create($questionData);
            
            // Count and store blanks/dropdowns for fill-in-the-blanks questions
            $blankCount = $question->countBlanks();
            if ($blankCount > 0) {
                $question->blank_count = $blankCount;
                $question->save();
                
                \Log::info('Question with Blanks Saved', [
                    'question_id' => $question->id,
                    'blank_count' => $blankCount,
                    'section_specific_data' => $question->section_specific_data,
                ]);
                
                // Create sub-questions for tracking each blank separately
                for ($i = 1; $i <= $blankCount; $i++) {
                    Question::create([
                        'test_set_id' => $question->test_set_id,
                        'question_type' => 'sub_blank',
                        'content' => "Blank {$i} of Question {$question->order_number}",
                        'order_number' => $question->order_number + $i,
                        'part_number' => $question->part_number,
                        'marks' => 1,
                        'is_sub_question' => true,
                        'parent_question_id' => $question->id,
                        'sub_question_index' => $i,
                    ]);
                }
                
                // Recalculate all order numbers for the test set
                Question::recalculateOrderNumbers($question->test_set_id);
            }
            
            // Create options if applicable
            if ($this->requiresOptions($request->question_type) && isset($request->options)) {
                foreach ($request->options as $index => $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $option['content'],
                        'is_correct' => ($request->correct_option == $index),
                    ]);
                }
            }
        });
        
        // Redirect based on action
        if ($request->action === 'save_and_new') {
            return redirect()->route('admin.questions.create', ['test_set' => $request->test_set_id])
                ->with('success', 'Question created successfully. Add another question.');
        }
        
        return redirect()->route('admin.test-sets.show', $request->test_set_id)
            ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question): View
    {
        $question->load(['testSet', 'testSet.section', 'options']);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question): View
    {
        $question->load(['testSet', 'options']);
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        // Get test set to determine section
        $testSet = TestSet::with('section')->findOrFail($request->test_set_id ?? $question->test_set_id);
        $section = $testSet->section->name;
        
        // Use same validation as store
        $rules = [
            'question_type' => 'required|string',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'part_number' => 'nullable|integer',
            'question_group' => 'nullable|string',
            'marks' => 'nullable|integer|min:1|max:10',
            'is_example' => 'nullable|boolean',
            'instructions' => 'nullable|string',
            'passage_text' => 'nullable|string',
            'audio_transcript' => 'nullable|string',
        ];
        
        // Add section-specific rules (similar to store)
        $this->addSectionSpecificRules($rules, $section, $request);
        
        $request->validate($rules);
        
        // Handle file upload
        $mediaPath = $question->media_path;
        if ($request->hasFile('media')) {
            // Delete old media
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }
            $mediaPath = $request->file('media')->store('questions/' . $section, 'public');
        }
        
        // Handle media removal
        if ($request->has('remove_media') && $mediaPath) {
            Storage::disk('public')->delete($mediaPath);
            $mediaPath = null;
        }
        
        DB::transaction(function () use ($request, $question, $mediaPath) {
            // Delete existing sub-questions if any
            $question->subQuestions()->delete();
            
            // Prepare section specific data for gap fill/dropdown
            $sectionSpecificData = null;
            if ($request->has('blank_answers') || $request->has('dropdown_options')) {
                $sectionSpecificData = [
                    'blank_answers' => $request->blank_answers ?? [],
                    'dropdown_options' => $request->dropdown_options ?? [],
                    'dropdown_correct' => $request->dropdown_correct ?? []
                ];
            }
            
            // Update the question
            $updateData = [
                'question_type' => $request->question_type,
                'content' => $request->content,
                'media_path' => $mediaPath,
                'order_number' => $request->order_number,
                'part_number' => $request->part_number,
                'question_group' => $request->question_group,
                'marks' => $request->marks ?? 1,
                'is_example' => $request->is_example ?? false,
                'instructions' => $request->instructions,
                'passage_text' => $request->passage_text,
                'audio_transcript' => $request->audio_transcript,
                'word_limit' => $request->word_limit ?? null,
                'time_limit' => $request->time_limit ?? null,
                'section_specific_data' => $sectionSpecificData,
            ];

            $question->update($updateData);
            
            // Recount blanks and create sub-questions if needed
            $blankCount = $question->countBlanks();
            $question->blank_count = $blankCount;
            $question->save();
            
            if ($blankCount > 0) {
                // Create new sub-questions for each blank
                for ($i = 1; $i <= $blankCount; $i++) {
                    Question::create([
                        'test_set_id' => $question->test_set_id,
                        'question_type' => 'sub_blank',
                        'content' => "Blank {$i} of Question {$question->order_number}",
                        'order_number' => $question->order_number + $i,
                        'part_number' => $question->part_number,
                        'marks' => 1,
                        'is_sub_question' => true,
                        'parent_question_id' => $question->id,
                        'sub_question_index' => $i,
                    ]);
                }
            }
            
            // Recalculate all order numbers for the test set
            Question::recalculateOrderNumbers($question->test_set_id);
            
            // Update options if applicable
            if ($this->requiresOptions($request->question_type) && isset($request->options)) {
                // Delete old options
                $question->options()->delete();
                
                // Create new options
                foreach ($request->options as $index => $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $option['content'],
                        'is_correct' => ($request->correct_option == $index),
                    ]);
                }
            }
        });
        
        return redirect()->route('admin.test-sets.show', $question->test_set_id)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Question $question): RedirectResponse
    {
        // Delete media if exists
        if ($question->media_path) {
            Storage::disk('public')->delete($question->media_path);
        }
        
        $testSetId = $question->test_set_id;
        $question->delete();
        
        return redirect()->route('admin.test-sets.show', $testSetId)
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * Check if question type requires options
     */
    private function requiresOptions($questionType): bool
    {
        return in_array($questionType, [
            'multiple_choice', 
            'true_false', 
            'yes_no',
            'matching',
            'matching_headings',
            'matching_information',
            'matching_features'
        ]);
    }

    /**
     * Add section-specific validation rules
     */
    private function addSectionSpecificRules(&$rules, $section, $request)
    {
        switch ($section) {
            case 'listening':
                if (!$request->question_type || $request->question_type !== 'passage') {
                    $rules['media'] = 'nullable|file|mimes:mp3,wav,ogg|max:51200';
                }
                $rules['part_number'] = 'required|integer|min:1|max:4';
                break;
                
            case 'reading':
                $rules['part_number'] = 'required|integer|min:1|max:3';
                if ($request->question_type === 'passage') {
                    $rules['passage_text'] = 'required|string|min:200';
                }
                break;
                
            case 'writing':
                $rules['word_limit'] = 'required|integer|min:50|max:500';
                $rules['time_limit'] = 'required|integer|min:1|max:60';
                break;
                
            case 'speaking':
                $rules['time_limit'] = 'required|integer|min:1|max:10';
                break;
        }
    }

    /**
     * Get questions by part (AJAX)
     */
    public function getByPart(TestSet $testSet, $part): JsonResponse
    {
        $questions = Question::where('test_set_id', $testSet->id)
                            ->where('part_number', $part)
                            ->orderBy('order_number')
                            ->with('options')
                            ->get();

        return response()->json($questions);
    }
}