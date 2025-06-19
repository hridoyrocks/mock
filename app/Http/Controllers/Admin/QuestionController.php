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
            
            // Get existing questions for this test set
            $existingQuestions = $testSet->questions()
                ->orderBy('order_number')
                ->get(['id', 'order_number', 'question_type', 'part_number']);
            
            // Calculate next question number
            $nextQuestionNumber = $existingQuestions->count() > 0 
                ? $existingQuestions->max('order_number') + 1 
                : 1;
            
            return view('admin.questions.create', compact(
                'testSet', 
                'existingQuestions', 
                'nextQuestionNumber'
            ));
        }
        
        // Otherwise show test set selection
        $testSets = TestSet::with('section')->where('active', true)->get();
        $sections = TestSection::all();
        
        return view('admin.questions.select-test-set', compact('testSets', 'sections'));
    }

    /**
     * Store a newly created question in storage.
     */
   public function store(Request $request): RedirectResponse
{
    // Debug logging
    \Log::info('Question Store Request', [
        'question_type' => $request->question_type,
        'has_content' => $request->has('content'),
        'has_passage_text' => $request->has('passage_text'),
        'content_length' => strlen($request->content ?? ''),
        'passage_text_length' => strlen($request->passage_text ?? ''),
    ]);
    
    // Get test set to determine section
    $testSet = TestSet::with('section')->findOrFail($request->test_set_id);
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
    } elseif ($section === 'reading') {
        $rules['part_number'] = 'required|integer|min:1|max:3';
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
        
        // Handle fill in the blanks answers
        if ($request->has('blank_answers')) {
            // Store in section_specific_data as JSON
            $question->section_specific_data = [
                'blank_answers' => $request->blank_answers,
                'dropdown_options' => $request->dropdown_options ?? [],
                'dropdown_correct' => $request->dropdown_correct ?? []
            ];
            $question->save();
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
        
        // Handle fill in the blanks answers
        if ($request->has('blank_answers')) {
            // Store in section_specific_data as JSON
            $question->section_specific_data = [
                'blank_answers' => $request->blank_answers,
                'dropdown_options' => $request->dropdown_options ?? [],
                'dropdown_correct' => $request->dropdown_correct ?? []
            ];
            $question->save();
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
    public function getByPart(TestSet $testSet, $part): \Illuminate\Http\JsonResponse
    {
        $questions = Question::where('test_set_id', $testSet->id)
                            ->where('part_number', $part)
                            ->orderBy('order_number')
                            ->with('options')
                            ->get();

        return response()->json($questions);
    }
}