<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\TestSet;
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
        $query = Question::with(['testSet', 'testSet.section', 'options'])
            ->orderBy('test_set_id')
            ->orderBy('order_number');
        
        // Filter by section
        if ($request->has('section') && $request->section != '') {
            $query->whereHas('testSet.section', function ($q) use ($request) {
                $q->where('name', $request->section);
            });
        }
        
        // Filter by test set
        if ($request->has('test_set') && $request->test_set != '') {
            $query->where('test_set_id', $request->test_set);
        }
        
        $questions = $query->paginate(20);
        
        // Get test sets for filtering
        $testSets = TestSet::with('section')->get();
        
        return view('admin.questions.index', compact('questions', 'testSets'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Request $request): View
    {
        $testSets = TestSet::with('section')->get();
        $preselectedTestSet = $request->test_set;
        
        return view('admin.questions.create', compact('testSets', 'preselectedTestSet'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|in:passage,multiple_choice,true_false,matching,fill_blank,short_answer,essay,cue_card',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp3,wav,ogg|max:10240', // 10MB max
        ];

        // Add validation for questions that need options
        if (in_array($request->question_type, ['multiple_choice', 'true_false', 'matching'])) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.content'] = 'required|string';
            $rules['correct_option'] = 'required|integer|min:0';
        }

        $request->validate($rules);
        
        $mediaPath = null;
        
        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $mediaPath = $media->store('questions', 'public');
        }
        
        DB::transaction(function () use ($request, $mediaPath) {
            // Create the question
            $question = Question::create([
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'content' => $request->content,
                'media_path' => $mediaPath,
                'order_number' => $request->order_number,
            ]);
            
            // Create options if applicable
            if (in_array($request->question_type, ['multiple_choice', 'true_false', 'matching']) && isset($request->options)) {
                foreach ($request->options as $index => $option) {
                    if (!empty($option['content'])) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'content' => trim($option['content']),
                            'is_correct' => ($request->correct_option == $index),
                        ]);
                    }
                }
            }
            
            // For True/False questions, auto-create standard options if none provided
            if ($request->question_type === 'true_false' && !isset($request->options)) {
                $tfOptions = ['True', 'False', 'Not Given'];
                foreach ($tfOptions as $index => $optionText) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $optionText,
                        'is_correct' => ($request->correct_option == $index),
                    ]);
                }
            }
        });
        
        return redirect()->route('admin.questions.index')
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
        $testSets = TestSet::with('section')->get();
        
        return view('admin.questions.edit', compact('question', 'testSets'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        $rules = [
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|in:passage,multiple_choice,true_false,matching,fill_blank,short_answer,essay,cue_card',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp3,wav,ogg|max:10240',
        ];

        if (in_array($request->question_type, ['multiple_choice', 'true_false', 'matching'])) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.content'] = 'required|string';
            $rules['correct_option'] = 'required|integer|min:0';
        }

        $request->validate($rules);
        
        $mediaPath = $question->media_path;
        
        // Handle media upload
        if ($request->hasFile('media')) {
            // Delete old media if exists
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }
            
            $media = $request->file('media');
            $mediaPath = $media->store('questions', 'public');
        }
        
        // Handle media removal
        if ($request->has('remove_media') && $mediaPath) {
            Storage::disk('public')->delete($mediaPath);
            $mediaPath = null;
        }
        
        DB::transaction(function () use ($request, $question, $mediaPath) {
            // Update the question
            $question->update([
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'content' => $request->content,
                'media_path' => $mediaPath,
                'order_number' => $request->order_number,
            ]);
            
            // Update options if applicable
            if (in_array($request->question_type, ['multiple_choice', 'true_false', 'matching'])) {
                // Delete old options
                $question->options()->delete();
                
                // Create new options
                if (isset($request->options)) {
                    foreach ($request->options as $index => $option) {
                        if (!empty($option['content'])) {
                            QuestionOption::create([
                                'question_id' => $question->id,
                                'content' => trim($option['content']),
                                'is_correct' => ($request->correct_option == $index),
                            ]);
                        }
                    }
                }
            } else {
                // Remove all options for non-option question types
                $question->options()->delete();
            }
        });
        
        return redirect()->route('admin.questions.index')
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
        
        $question->delete();
        
        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * Get question template based on type (AJAX endpoint)
     */
    public function getTemplate(Request $request)
    {
        $type = $request->get('type');
        
        $templates = [
            'passage' => [
                'needs_options' => false,
                'placeholder' => 'Enter the reading passage or text content here...',
                'description' => 'This will be displayed as reference material for students.'
            ],
            'multiple_choice' => [
                'needs_options' => true,
                'min_options' => 3,
                'max_options' => 6,
                'placeholder' => 'Enter your question here...',
                'option_placeholder' => 'Enter option text...',
                'description' => 'Students will select one correct answer from multiple options.'
            ],
            'true_false' => [
                'needs_options' => true,
                'fixed_options' => ['True', 'False', 'Not Given'],
                'placeholder' => 'Enter a statement for students to evaluate...',
                'description' => 'Students will choose True, False, or Not Given.'
            ],
            'matching' => [
                'needs_options' => true,
                'min_options' => 4,
                'max_options' => 8,
                'placeholder' => 'Enter matching instruction...',
                'option_placeholder' => 'Enter matching pair...',
                'description' => 'Students will match items from two lists.'
            ],
            'fill_blank' => [
                'needs_options' => false,
                'placeholder' => 'Enter text with blanks using _____ for each blank...',
                'description' => 'Students will fill in the missing words.'
            ],
            'short_answer' => [
                'needs_options' => false,
                'placeholder' => 'Enter question requiring a brief written response...',
                'description' => 'Students will provide short text answers.'
            ],
            'essay' => [
                'needs_options' => false,
                'placeholder' => 'Enter essay prompt or task description...',
                'description' => 'Students will write detailed responses (for Writing section).'
            ],
            'cue_card' => [
                'needs_options' => false,
                'placeholder' => 'Enter speaking topic with bullet points for guidance...',
                'description' => 'Students will speak on this topic (for Speaking section).'
            ]
        ];
        
        return response()->json($templates[$type] ?? []);
    }
}