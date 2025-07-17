<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionBlank;
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
     * Store fill-in-the-gap question with new method
     */
    private function storeFillInGapQuestion($question, $blanksData)
    {
        if (!$blanksData || !is_array($blanksData)) {
            return;
        }

        foreach ($blanksData as $blankNumber => $blankData) {
            if (empty($blankData['correct_answer'])) {
                continue;
            }

            $alternateAnswers = [];
            if (!empty($blankData['alternate_answers'])) {
                $alternateAnswers = array_map('trim', explode(',', $blankData['alternate_answers']));
                $alternateAnswers = array_filter($alternateAnswers); // Remove empty values
            }

            QuestionBlank::create([
                'question_id' => $question->id,
                'blank_number' => $blankNumber,
                'correct_answer' => trim($blankData['correct_answer']),
                'alternate_answers' => $alternateAnswers ?: null
            ]);
        }
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Get test set to determine section
        $testSet = TestSet::with('section')->findOrFail($request->test_set_id);
        $section = $testSet->section->name;
        
        // Base validation rules
        $rules = [
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|string',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:0',
            'part_number' => 'nullable|integer',
            'marks' => 'nullable|integer|min:0|max:10',
            'instructions' => 'nullable|string',
        ];

        // Validate blanks for fill-in-the-gap questions
        if (in_array($request->question_type, ['sentence_completion', 'note_completion', 'summary_completion', 'fill_blanks'])) {
            $rules['blanks'] = 'required|array';
            $rules['blanks.*.correct_answer'] = 'required|string';
            $rules['blanks.*.alternate_answers'] = 'nullable|string';
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $testSet, $section) {
            // Create the question
            $question = Question::create([
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'content' => $request->content,
                'order_number' => $request->order_number,
                'part_number' => $request->part_number ?? 1,
                'marks' => $request->marks ?? 1,
                'instructions' => $request->instructions,
                'media_path' => null, // Handle media separately if needed
            ]);

            // Handle fill-in-the-gap blanks
            if ($request->has('blanks')) {
                $this->storeFillInGapQuestion($question, $request->blanks);
            }

            // Handle multiple choice options
            if ($request->has('options') && $this->needsOptions($request->question_type)) {
                foreach ($request->options as $index => $option) {
                    if (!empty($option['content'])) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'content' => $option['content'],
                            'is_correct' => ($request->correct_option == $index),
                        ]);
                    }
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
     * Check if question type needs options
     */
    private function needsOptions($questionType): bool
    {
        return in_array($questionType, [
            'multiple_choice',
            'single_choice',
            'true_false',
            'yes_no'
        ]);
    }

    // ... rest of the controller methods remain the same
}