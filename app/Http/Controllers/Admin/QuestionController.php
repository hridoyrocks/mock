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
    // Debug: Check what's coming in
    \Log::info('Question Store Request:', $request->all());
    
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
        if (!$request->filled('content') && !$request->filled('passage_text')) {
            return redirect()->back()
                ->withErrors(['content' => 'Passage content is required'])
                ->withInput();
        }
        $rules['content'] = 'nullable|string';
        $rules['passage_text'] = 'nullable|string';
    } else {
        $rules['content'] = 'required|string';
    }
    
    // Section-specific validation
    switch ($section) {
        case 'listening':
            // Make media conditional based on question type
            if (!in_array($request->question_type, ['plan_map_diagram'])) {
                $rules['media'] = 'required|file|mimes:mp3,wav,ogg|max:51200';
            }
            $rules['part_number'] = 'required|integer|min:1|max:4';
            break;
    }
    
    // Add type-specific validation rules
    if ($request->question_type === 'matching') {
        $rules['matching_pairs'] = 'required|array|min:2';
        $rules['matching_pairs.*.left'] = 'required|string';
        $rules['matching_pairs.*.right'] = 'required|string';
    }

    if ($request->question_type === 'form_completion') {
        $rules['form_structure.title'] = 'required|string';
        $rules['form_structure.fields'] = 'required|array|min:1';
        $rules['form_structure.fields.*.label'] = 'required|string';
        $rules['form_structure.fields.*.answer'] = 'required|string';
    }

    if ($request->question_type === 'plan_map_diagram') {
        $rules['diagram_image'] = 'required|image|max:5120';
        $rules['diagram_hotspots'] = 'required|array|min:1';
        $rules['diagram_hotspots.*.answer'] = 'required|string';
    }
    
    // Add options validation if needed
    if ($this->requiresOptions($request->question_type)) {
        $rules['options'] = 'required|array|min:2';
        $rules['options.*.content'] = 'required|string';
        $rules['correct_option'] = 'required|integer|min:0';
    }
    
    try {
        $request->validate($rules);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed:', $e->errors());
        throw $e;
    }
    
    // Handle file upload
    $mediaPath = null;
    if ($request->hasFile('media')) {
        $mediaPath = $request->file('media')->store('questions/' . $section, 'public');
    }
    
    // Handle type-specific data
    $typeSpecificData = [];

    switch ($request->question_type) {
        case 'matching':
            if ($request->has('matching_pairs')) {
                $matchingPairs = [];
                foreach ($request->matching_pairs as $pair) {
                    if (!empty($pair['left']) && !empty($pair['right'])) {
                        $matchingPairs[] = [
                            'left' => trim($pair['left']),
                            'right' => trim($pair['right'])
                        ];
                    }
                }
                $typeSpecificData['matching_pairs'] = $matchingPairs;
                \Log::info('Matching pairs:', $matchingPairs);
            }
            break;
            
        case 'form_completion':
            if ($request->has('form_structure')) {
                $formStructure = [
                    'title' => $request->form_structure['title'] ?? 'Form',
                    'fields' => []
                ];
                
                if (isset($request->form_structure['fields'])) {
                    foreach ($request->form_structure['fields'] as $index => $field) {
                        if (!empty($field['label']) && !empty($field['answer'])) {
                            $formStructure['fields'][] = [
                                'label' => trim($field['label']),
                                'blank_id' => $index + 1,
                                'answer' => trim($field['answer'])
                            ];
                        }
                    }
                }
                $typeSpecificData['form_structure'] = $formStructure;
                \Log::info('Form structure:', $formStructure);
            }
            break;
            
        case 'plan_map_diagram':
            // Handle diagram image upload
            if ($request->hasFile('diagram_image')) {
                $diagramPath = $request->file('diagram_image')->store('questions/diagrams', 'public');
                $mediaPath = $diagramPath; // Override media path
                \Log::info('Diagram uploaded:', $diagramPath);
            }
            
            if ($request->has('diagram_hotspots')) {
                $hotspots = [];
                foreach ($request->diagram_hotspots as $index => $hotspot) {
                    if (!empty($hotspot['answer'])) {
                        $hotspots[] = [
                            'id' => $index + 1,
                            'x' => (int)$hotspot['x'],
                            'y' => (int)$hotspot['y'],
                            'label' => $hotspot['label'],
                            'answer' => trim($hotspot['answer'])
                        ];
                    }
                }
                $typeSpecificData['diagram_hotspots'] = $hotspots;
                \Log::info('Diagram hotspots:', $hotspots);
            }
            break;
    }
    
    DB::transaction(function () use ($request, $testSet, $mediaPath, $typeSpecificData) {
        // Prepare question data
        $questionData = [
            'test_set_id' => $request->test_set_id,
            'question_type' => $request->question_type,
            'content' => $request->content,
            'order_number' => $request->order_number,
            'part_number' => $request->part_number ?? 1,
            'marks' => $request->marks ?? 1,
            'instructions' => $request->instructions,
            'media_path' => $mediaPath,
            'audio_transcript' => $request->audio_transcript ?? null,
        ];
        
        // Add type-specific fields directly
        if (isset($typeSpecificData['matching_pairs'])) {
            $questionData['matching_pairs'] = $typeSpecificData['matching_pairs'];
        }
        if (isset($typeSpecificData['form_structure'])) {
            $questionData['form_structure'] = $typeSpecificData['form_structure'];
        }
        if (isset($typeSpecificData['diagram_hotspots'])) {
            $questionData['diagram_hotspots'] = $typeSpecificData['diagram_hotspots'];
        }
        
        // Also add to section_specific_data for backward compatibility
        if (!empty($typeSpecificData)) {
            $questionData['section_specific_data'] = $typeSpecificData;
        }
        
        \Log::info('Creating question with data:', $questionData);
        
        $question = Question::create($questionData);
        
        \Log::info('Question created:', ['id' => $question->id]);
        
        // Create options if applicable
        if ($this->requiresOptions($request->question_type) && isset($request->options)) {
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
            // Media is optional for update (keep existing if not provided)
            $rules['media'] = 'nullable|file|mimes:mp3,wav,ogg|max:51200';
            $rules['part_number'] = 'required|integer|min:1|max:4';
            break;
            
        case 'reading':
            $rules['part_number'] = 'required|integer|min:1|max:3';
            break;
            
        case 'writing':
            $rules['word_limit'] = 'required|integer|min:50|max:500';
            $rules['time_limit'] = 'required|integer|min:1|max:60';
            if (strpos($request->question_type, 'task1') !== false) {
                $rules['media'] = 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120';
            }
            break;
            
        case 'speaking':
            $rules['time_limit'] = 'required|integer|min:1|max:10';
            break;
    }
    
    // Add type-specific validation rules
    if ($request->question_type === 'matching') {
        $rules['matching_pairs'] = 'required|array|min:2';
        $rules['matching_pairs.*.left'] = 'required|string';
        $rules['matching_pairs.*.right'] = 'required|string';
    }

    if ($request->question_type === 'form_completion') {
        $rules['form_structure.title'] = 'required|string';
        $rules['form_structure.fields'] = 'required|array|min:1';
        $rules['form_structure.fields.*.label'] = 'required|string';
        $rules['form_structure.fields.*.answer'] = 'required|string';
    }

    if ($request->question_type === 'plan_map_diagram') {
        // Diagram image is optional for update
        $rules['diagram_image'] = 'nullable|image|max:5120';
        $rules['diagram_hotspots'] = 'required|array|min:1';
        $rules['diagram_hotspots.*.answer'] = 'required|string';
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
    $mediaPath = $question->media_path;
    
    // Handle regular media upload (audio for listening)
    if ($request->hasFile('media')) {
        // Delete old media
        if ($mediaPath && Storage::disk('public')->exists($mediaPath)) {
            Storage::disk('public')->delete($mediaPath);
        }
        $mediaPath = $request->file('media')->store('questions/' . $section, 'public');
    }
    
    // Handle media removal
    if ($request->has('remove_media') && $request->remove_media && $mediaPath) {
        Storage::disk('public')->delete($mediaPath);
        $mediaPath = null;
    }
    
    // Handle type-specific data
    $typeSpecificData = [];

    switch ($request->question_type) {
        case 'matching':
            if ($request->has('matching_pairs')) {
                $matchingPairs = [];
                foreach ($request->matching_pairs as $pair) {
                    if (!empty($pair['left']) && !empty($pair['right'])) {
                        $matchingPairs[] = [
                            'left' => $pair['left'],
                            'right' => $pair['right']
                        ];
                    }
                }
                $typeSpecificData['matching_pairs'] = $matchingPairs;
            }
            break;
            
        case 'form_completion':
            if ($request->has('form_structure')) {
                $formStructure = [
                    'title' => $request->form_structure['title'] ?? 'Form',
                    'fields' => []
                ];
                
                if (isset($request->form_structure['fields'])) {
                    foreach ($request->form_structure['fields'] as $index => $field) {
                        if (!empty($field['label']) && !empty($field['answer'])) {
                            $formStructure['fields'][] = [
                                'label' => $field['label'],
                                'blank_id' => $index + 1,
                                'answer' => $field['answer']
                            ];
                        }
                    }
                }
                $typeSpecificData['form_structure'] = $formStructure;
            }
            break;
            
        case 'plan_map_diagram':
            // Handle diagram image upload
            if ($request->hasFile('diagram_image')) {
                // Delete old diagram if exists
                if ($mediaPath && Storage::disk('public')->exists($mediaPath)) {
                    Storage::disk('public')->delete($mediaPath);
                }
                $diagramPath = $request->file('diagram_image')->store('questions/diagrams', 'public');
                $mediaPath = $diagramPath; // Override media path
            }
            
            if ($request->has('diagram_hotspots')) {
                $hotspots = [];
                foreach ($request->diagram_hotspots as $index => $hotspot) {
                    if (!empty($hotspot['answer'])) {
                        $hotspots[] = [
                            'id' => $index + 1,
                            'x' => (int)$hotspot['x'],
                            'y' => (int)$hotspot['y'],
                            'label' => $hotspot['label'],
                            'answer' => $hotspot['answer']
                        ];
                    }
                }
                $typeSpecificData['diagram_hotspots'] = $hotspots;
            }
            break;
    }
    
    DB::transaction(function () use ($request, $question, $mediaPath, $typeSpecificData) {
        // Prepare section specific data for gap fill/dropdown
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
        
        // Merge type-specific data with existing section_specific_data
        $mergedSectionData = array_merge(
            $sectionSpecificData ?? [],
            $typeSpecificData
        );
        
        // Update the question
        $updateData = [
            'question_type' => $request->question_type,
            'media_path' => $mediaPath,
            'order_number' => $request->order_number,
            'part_number' => $request->part_number,
            'marks' => $request->marks ?? 1,
            'instructions' => $request->instructions,
            'audio_transcript' => $request->audio_transcript,
            'word_limit' => $request->word_limit ?? null,
            'time_limit' => $request->time_limit ?? null,
            'section_specific_data' => !empty($mergedSectionData) ? $mergedSectionData : null,
        ];
        
        // Handle content based on question type
        if ($request->question_type === 'passage') {
            // For passages, prioritize passage_text, then content
            $passageContent = $request->filled('passage_text') ? $request->passage_text : $request->content;
            
            $updateData['content'] = $passageContent;
            $updateData['passage_text'] = $passageContent;
            
            // Passages typically have 0 marks
            $updateData['marks'] = 0;
        } else {
            // For regular questions
            $updateData['content'] = $request->content;
            $updateData['passage_text'] = null;
        }
        
        // Also set the specific fields if they exist
        if (isset($typeSpecificData['matching_pairs'])) {
            $updateData['matching_pairs'] = $typeSpecificData['matching_pairs'];
        } else {
            $updateData['matching_pairs'] = null;
        }
        
        if (isset($typeSpecificData['form_structure'])) {
            $updateData['form_structure'] = $typeSpecificData['form_structure'];
        } else {
            $updateData['form_structure'] = null;
        }
        
        if (isset($typeSpecificData['diagram_hotspots'])) {
            $updateData['diagram_hotspots'] = $typeSpecificData['diagram_hotspots'];
        } else {
            $updateData['diagram_hotspots'] = null;
        }

        $question->update($updateData);
        
        // Handle blanks counting for fill_blanks type
        if ($request->question_type === 'fill_blanks') {
            // Count blanks in content
            $content = $question->content;
            preg_match_all('/\[____\d+____\]/', $content, $blankMatches);
            preg_match_all('/\[DROPDOWN_\d+\]/', $content, $dropdownMatches);
            
            $blankCount = count($blankMatches[0]) + count($dropdownMatches[0]);
            
            $question->blank_count = $blankCount;
            $question->save();
        }
        
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
        } elseif (!$this->requiresOptions($request->question_type)) {
            // If question type changed to non-options type, delete existing options
            $question->options()->delete();
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