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
        
        $questions = $query->orderBy('test_set_id')->orderBy('order_number')->paginate(15);
        
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
        $sections = TestSection::all();
        
        // If section is specified, filter test sets
        $selectedSection = $request->get('section');
        if ($selectedSection) {
            $testSets = $testSets->where('section.name', $selectedSection);
        }
        
        return view('admin.questions.create', compact('testSets', 'sections', 'selectedSection'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Dynamic validation based on question type and section
        $rules = $this->getValidationRules($request->question_type, $request->test_set_id);
        $request->validate($rules);
        
        $mediaPath = null;
        
        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $mediaPath = $media->store('questions', 'public');
        }
        
        DB::transaction(function () use ($request, $mediaPath) {
            // Create the question
            $questionData = [
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'content' => $request->content,
                'media_path' => $mediaPath,
                'order_number' => $request->order_number,
            ];

            // Add section-specific fields
            if ($request->has('word_limit')) {
                $questionData['word_limit'] = $request->word_limit;
            }
            
            if ($request->has('time_limit')) {
                $questionData['time_limit'] = $request->time_limit;
            }

            if ($request->has('instructions')) {
                $questionData['instructions'] = $request->instructions;
            }

            $question = Question::create($questionData);
            
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

            // Handle bulk questions for reading passages
            if ($request->question_type === 'passage' && $request->has('bulk_questions')) {
                $this->createBulkQuestions($question, $request->bulk_questions);
            }
        });
        
        return redirect()->route('admin.questions.index')
            ->with('success', 'Question created successfully.');
    }

    /**
     * Get validation rules based on question type and section
     */
    private function getValidationRules($questionType, $testSetId): array
    {
        $testSet = TestSet::with('section')->find($testSetId);
        $section = $testSet ? $testSet->section->name : null;

        $baseRules = [
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|string',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'media' => 'nullable|file|max:20480', // 20MB max
        ];

        // Section-specific rules
        switch ($section) {
            case 'listening':
                if ($questionType === 'form_completion' || $questionType === 'note_completion') {
                    $baseRules['media'] = 'required|file|mimes:mp3,wav,ogg|max:50240'; // 50MB for audio
                }
                break;
                
            case 'reading':
                if ($questionType === 'passage') {
                    $baseRules['content'] = 'required|string|min:200'; // Minimum 200 chars for passage
                    $baseRules['bulk_questions'] = 'sometimes|array';
                    $baseRules['bulk_questions.*.content'] = 'required|string';
                    $baseRules['bulk_questions.*.type'] = 'required|string';
                }
                break;
                
            case 'writing':
                $baseRules['word_limit'] = 'required|integer|min:150|max:400';
                $baseRules['time_limit'] = 'required|integer|min:20|max:60'; // minutes
                if ($questionType === 'task1_chart') {
                    $baseRules['media'] = 'required|file|mimes:jpg,jpeg,png,gif|max:5120'; // 5MB for images
                }
                break;
                
            case 'speaking':
                $baseRules['time_limit'] = 'required|integer|min:1|max:4'; // minutes
                if ($questionType === 'cue_card') {
                    $baseRules['instructions'] = 'required|string';
                }
                break;
        }

        // Question type specific rules
        if (in_array($questionType, ['multiple_choice', 'true_false', 'matching'])) {
            $baseRules['options'] = 'required|array|min:2';
            $baseRules['options.*.content'] = 'required|string';
            $baseRules['correct_option'] = 'required|integer|min:0';
        }

        return $baseRules;
    }

    /**
     * Check if question type requires options
     */
    private function requiresOptions($questionType): bool
    {
        return in_array($questionType, [
            'multiple_choice', 
            'true_false', 
            'matching',
            'form_completion',
            'sentence_completion'
        ]);
    }

    /**
     * Create bulk questions for reading passages
     */
    private function createBulkQuestions($passage, $bulkQuestions): void
    {
        foreach ($bulkQuestions as $index => $questionData) {
            $question = Question::create([
                'test_set_id' => $passage->test_set_id,
                'question_type' => $questionData['type'],
                'content' => $questionData['content'],
                'order_number' => $passage->order_number + $index + 1,
                'passage_id' => $passage->id, // Link to passage
            ]);

            // Create options if needed
            if (isset($questionData['options'])) {
                foreach ($questionData['options'] as $optionIndex => $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $option['content'],
                        'is_correct' => ($questionData['correct_option'] == $optionIndex),
                    ]);
                }
            }
        }
    }

    /**
     * Bulk upload questions via CSV/Excel
     */
    public function bulkUpload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx|max:2048',
            'test_set_id' => 'required|exists:test_sets,id'
        ]);

        $file = $request->file('file');
        $testSetId = $request->test_set_id;

        // Process file and create questions
        $this->processBulkFile($file, $testSetId);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Questions uploaded successfully via bulk upload.');
    }

    /**
     * Process bulk upload file
     */
    private function processBulkFile($file, $testSetId): void
    {
        // Implementation for CSV/Excel processing
        // This would use libraries like PhpSpreadsheet or League/CSV
        // For now, showing the structure
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question): View
    {
        $question->load(['testSet', 'options']);
        $testSets = TestSet::with('section')->get();
        $sections = TestSection::all();
        
        return view('admin.questions.edit', compact('question', 'testSets', 'sections'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        $rules = $this->getValidationRules($request->question_type, $request->test_set_id);
        $request->validate($rules);
        
        $mediaPath = $question->media_path;
        
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
            $updateData = [
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'content' => $request->content,
                'media_path' => $mediaPath,
                'order_number' => $request->order_number,
            ];

            // Add section-specific fields
            if ($request->has('word_limit')) {
                $updateData['word_limit'] = $request->word_limit;
            }
            
            if ($request->has('time_limit')) {
                $updateData['time_limit'] = $request->time_limit;
            }

            if ($request->has('instructions')) {
                $updateData['instructions'] = $request->instructions;
            }

            $question->update($updateData);
            
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
     * Duplicate a question
     */
    public function duplicate(Question $question): RedirectResponse
    {
        DB::transaction(function () use ($question) {
            $newQuestion = $question->replicate();
            $newQuestion->order_number = $question->order_number + 1;
            $newQuestion->save();

            // Duplicate options
            foreach ($question->options as $option) {
                $newOption = $option->replicate();
                $newOption->question_id = $newQuestion->id;
                $newOption->save();
            }
        });

        return redirect()->route('admin.questions.edit', $newQuestion)
            ->with('success', 'Question duplicated successfully. Please review and modify as needed.');
    }
}