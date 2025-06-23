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
        // If no test set selected, show selection page
        if (!$request->has('test_set')) {
            $testSets = TestSet::with('section')->where('active', true)->get();
            $sections = TestSection::all();
            
            return view('admin.questions.select-test-set', compact('testSets', 'sections'));
        }
        
        // Get test set with section
        $testSet = TestSet::with('section')->findOrFail($request->test_set);
        
        // Check if test set exists and is active
        if (!$testSet->active) {
            return redirect()->route('admin.test-sets.index')
                ->with('error', 'This test set is not active.');
        }
        
        // Get section name
        $section = $testSet->section->name;
        
        // Common data for all sections
        $existingQuestions = $testSet->questions()
            ->orderBy('part_number')
            ->orderBy('order_number')
            ->get();
            
        $nextQuestionNumber = $this->calculateNextQuestionNumber($testSet);
        
        // Section-specific data
        $data = [
            'testSet' => $testSet,
            'existingQuestions' => $existingQuestions,
            'nextQuestionNumber' => $nextQuestionNumber,
        ];
        
        // Return section-specific view
        return view('admin.questions.create.' . $section, $data);
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
            'order_number' => 'required|integer|min:0',
            'part_number' => 'nullable|integer',
            'marks' => 'nullable|integer|min:0|max:10',
            'instructions' => 'nullable|string',
        ];
        
        // Handle content validation based on question type
        if ($request->question_type === 'passage') {
            // For passage type, content can come from either field
            if (!$request->filled('content') && !$request->filled('passage_text')) {
                return redirect()->back()
                    ->withErrors(['content' => 'Passage content is required'])
                    ->withInput();
            }
            $rules['content'] = 'nullable|string';
            $rules['passage_text'] = 'nullable|string';
        } else {
            // For other question types, content is required
            $rules['content'] = 'required|string';
        }
        
        // Section-specific validation
        switch ($section) {
            case 'listening':
                $rules['media'] = 'required_if:question_type,!=,passage|file|mimes:mp3,wav,ogg|max:51200';
                $rules['part_number'] = 'required|integer|min:1|max:4';
                break;
                
            case 'reading':
                $rules['part_number'] = 'required|integer|min:1|max:3';
                break;
                
            case 'writing':
                $rules['word_limit'] = 'required|integer|min:50|max:500';
                $rules['time_limit'] = 'required|integer|min:1|max:60';
                if (strpos($request->question_type, 'task1') !== false) {
                    $rules['media'] = 'required|file|mimes:jpg,jpeg,png,gif|max:5120';
                }
                break;
                
            case 'speaking':
                $rules['time_limit'] = 'required|integer|min:1|max:10';
                break;
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
        
        DB::transaction(function () use ($request, $testSet, $mediaPath) {
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
            
            // Prepare section specific data for gap fill/dropdown - FIXED VERSION
            $sectionSpecificData = null;
            if ($request->has('blank_answers') || $request->has('dropdown_options')) {
                $blankAnswers = [];
                $dropdownOptions = [];
                $dropdownCorrect = [];
                
                // Process blank answers - ensure consistent numeric keys
                if ($request->has('blank_answers')) {
                    foreach ($request->blank_answers as $key => $answer) {
                        // Ensure numeric keys starting from 1
                        if (is_numeric($key) && !empty(trim($answer))) {
                            $blankAnswers[(int)$key] = trim($answer);
                        }
                    }
                }
                
                // Process dropdown options
                if ($request->has('dropdown_options')) {
                    foreach ($request->dropdown_options as $key => $options) {
                        if (is_numeric($key) && !empty(trim($options))) {
                            $dropdownOptions[(int)$key] = trim($options);
                        }
                    }
                }
                
                // Process dropdown correct answers
                if ($request->has('dropdown_correct')) {
                    foreach ($request->dropdown_correct as $key => $correctIndex) {
                        if (is_numeric($key)) {
                            $dropdownCorrect[(int)$key] = (int)$correctIndex;
                        }
                    }
                }
                
                $sectionSpecificData = [
                    'blank_answers' => $blankAnswers,
                    'dropdown_options' => $dropdownOptions,
                    'dropdown_correct' => $dropdownCorrect
                ];
            }
            
            // Prepare question data
            $questionData = [
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'order_number' => $request->order_number,
                'part_number' => $request->part_number ?? 1,
                'marks' => $request->marks ?? 1,
                'instructions' => $request->instructions,
                'media_path' => $mediaPath,
                'word_limit' => $request->word_limit ?? null,
                'time_limit' => $request->time_limit ?? null,
                'audio_transcript' => $request->audio_transcript ?? null,
                'section_specific_data' => $sectionSpecificData,
            ];
            
            // Handle content based on question type
            if ($request->question_type === 'passage') {
                // For passages, prioritize passage_text, then content
                $passageContent = $request->filled('passage_text') ? $request->passage_text : $request->content;
                
                $questionData['content'] = $passageContent;
                $questionData['passage_text'] = $passageContent;
                
                // Passages typically have 0 marks
                $questionData['marks'] = 0;
            } else {
                // For regular questions
                $questionData['content'] = $request->content;
            }

            $question = Question::create($questionData);
            
            // Handle blanks counting for fill_blanks type
            if ($request->question_type === 'fill_blanks') {
                // Count blanks in content
                $content = $question->content;
                preg_match_all('/\[____\d+____\]/', $content, $blankMatches);
                preg_match_all('/\[DROPDOWN_\d+\]/', $content, $dropdownMatches);
                
                $blankCount = count($blankMatches[0]) + count($dropdownMatches[0]);
                
                if ($blankCount > 0) {
                    $question->blank_count = $blankCount;
                    $question->save();
                }
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
        // Load relationships
        $question->load(['testSet', 'testSet.section', 'options']);
        
        // Get section name
        $section = $question->testSet->section->name;
        
        // Prepare common data
        $data = [
            'question' => $question,
            'testSet' => $question->testSet,
        ];
        
        // Add section-specific question types
        switch ($section) {
            case 'listening':
                $data['questionTypes'] = [
                    'multiple_choice' => 'Multiple Choice',
                    'form_completion' => 'Form Completion',
                    'note_completion' => 'Note Completion',
                    'sentence_completion' => 'Sentence Completion',
                    'short_answer' => 'Short Answer',
                    'matching' => 'Matching',
                    'plan_map_diagram' => 'Plan/Map/Diagram Labeling'
                ];
                break;
                
            case 'reading':
                $data['questionTypes'] = [
                    'passage' => '📄 Reading Passage',
                    'multiple_choice' => 'Multiple Choice',
                    'true_false' => 'True/False/Not Given',
                    'yes_no' => 'Yes/No/Not Given',
                    'matching_headings' => 'Matching Headings',
                    'matching_information' => 'Matching Information',
                    'matching_features' => 'Matching Features',
                    'sentence_completion' => 'Sentence Completion',
                    'summary_completion' => 'Summary Completion',
                    'short_answer' => 'Short Answer',
                    'fill_blanks' => 'Fill in the Blanks'
                ];
                break;
                
            case 'writing':
                $data['questionTypes'] = [
                    'task1_line_graph' => 'Task 1: Line Graph',
                    'task1_bar_chart' => 'Task 1: Bar Chart',
                    'task1_pie_chart' => 'Task 1: Pie Chart',
                    'task1_table' => 'Task 1: Table',
                    'task1_process' => 'Task 1: Process Diagram',
                    'task1_map' => 'Task 1: Map',
                    'task2_opinion' => 'Task 2: Opinion Essay',
                    'task2_discussion' => 'Task 2: Discussion Essay',
                    'task2_problem_solution' => 'Task 2: Problem/Solution',
                    'task2_advantage_disadvantage' => 'Task 2: Advantages/Disadvantages'
                ];
                break;
                
            case 'speaking':
                $data['questionTypes'] = [
                    'part1_personal' => 'Part 1: Personal Questions',
                    'part2_cue_card' => 'Part 2: Cue Card',
                    'part3_discussion' => 'Part 3: Discussion'
                ];
                break;
        }
        
        // Check if section-specific edit view exists
        $sectionView = 'admin.questions.edit.' . $section;
        if (view()->exists($sectionView)) {
            return view($sectionView, $data);
        }
        
        // Otherwise use common edit view
        return view('admin.questions.edit.common', $data);
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        // Get test set to determine section
        $testSet = TestSet::with('section')->findOrFail($request->test_set_id ?? $question->test_set_id);
        $section = $testSet->section->name;
        
        // Base validation rules
        $rules = [
            'question_type' => 'required|string',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:0',
            'part_number' => 'nullable|integer',
            'marks' => 'nullable|integer|min:0|max:10',
            'instructions' => 'nullable|string',
        ];
        
        // Add section-specific rules
        $this->addSectionSpecificRules($rules, $section, $request);
        
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
            // Prepare section specific data for gap fill/dropdown - FIXED VERSION
            $sectionSpecificData = null;
            if ($request->has('blank_answers') || $request->has('dropdown_options')) {
                $blankAnswers = [];
                $dropdownOptions = [];
                $dropdownCorrect = [];
                
                // Process blank answers - ensure consistent numeric keys
                if ($request->has('blank_answers')) {
                    foreach ($request->blank_answers as $key => $answer) {
                        // Ensure numeric keys starting from 1
                        if (is_numeric($key) && !empty(trim($answer))) {
                            $blankAnswers[(int)$key] = trim($answer);
                        }
                    }
                }
                
                // Process dropdown options
                if ($request->has('dropdown_options')) {
                    foreach ($request->dropdown_options as $key => $options) {
                        if (is_numeric($key) && !empty(trim($options))) {
                            $dropdownOptions[(int)$key] = trim($options);
                        }
                    }
                }
                
                // Process dropdown correct answers
                if ($request->has('dropdown_correct')) {
                    foreach ($request->dropdown_correct as $key => $correctIndex) {
                        if (is_numeric($key)) {
                            $dropdownCorrect[(int)$key] = (int)$correctIndex;
                        }
                    }
                }
                
                $sectionSpecificData = [
                    'blank_answers' => $blankAnswers,
                    'dropdown_options' => $dropdownOptions,
                    'dropdown_correct' => $dropdownCorrect
                ];
            }
            
            // Update the question
            $updateData = [
                'question_type' => $request->question_type,
                'content' => $request->content,
                'media_path' => $mediaPath,
                'order_number' => $request->order_number,
                'part_number' => $request->part_number,
                'marks' => $request->marks ?? 1,
                'instructions' => $request->instructions,
                'passage_text' => $request->passage_text,
                'audio_transcript' => $request->audio_transcript,
                'word_limit' => $request->word_limit ?? null,
                'time_limit' => $request->time_limit ?? null,
                'section_specific_data' => $sectionSpecificData,
            ];

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
                    $rules['content'] = 'required|string|min:200';
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
    
    /**
     * Calculate next question number for a test set
     */
    private function calculateNextQuestionNumber(TestSet $testSet): int
    {
        $lastQuestion = $testSet->questions()
            ->orderBy('order_number', 'desc')
            ->first();
            
        if (!$lastQuestion) {
            return 1;
        }
        
        return $lastQuestion->order_number + 1;
    }
}