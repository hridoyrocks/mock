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
        
        $questions = $query->orderBy('test_set_id')->orderBy('order_number')->paginate(15);
        
        // Get test sets for filtering
        $testSets = TestSet::with('section')->get();
        
        return view('admin.questions.index', compact('questions', 'testSets'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(): View
    {
        $testSets = TestSet::with('section')->get();
        
        return view('admin.questions.create', compact('testSets'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|string',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'media' => 'nullable|file|max:10240', // 10MB max
            'options' => 'required_if:question_type,multiple_choice,true_false,matching',
            'options.*.content' => 'required_if:question_type,multiple_choice,true_false,matching',
            'correct_option' => 'required_if:question_type,multiple_choice,true_false,matching',
        ]);
        
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
            
            // Create options if applicable (not for passage type)
            if (in_array($request->question_type, ['multiple_choice', 'true_false', 'matching']) && isset($request->options)) {
                foreach ($request->options as $index => $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $option['content'],
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
        $request->validate([
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|string',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'media' => 'nullable|file|max:10240', // 10MB max
            'options' => 'required_if:question_type,multiple_choice,true_false,matching',
            'options.*.content' => 'required_if:question_type,multiple_choice,true_false,matching',
            'correct_option' => 'required_if:question_type,multiple_choice,true_false,matching',
        ]);
        
        $mediaPath = $question->media_path;
        
        if ($request->hasFile('media')) {
            // Delete old media if exists
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }
            
            $media = $request->file('media');
            $mediaPath = $media->store('questions', 'public');
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
            
            // Update options if applicable (not for passage type)
            if (in_array($request->question_type, ['multiple_choice', 'true_false', 'matching']) && isset($request->options)) {
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
}