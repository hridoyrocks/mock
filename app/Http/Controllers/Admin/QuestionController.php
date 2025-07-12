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
                // Check if part audio exists
                $partAudio = $testSet->getPartAudio($request->part_number ?? 1);
                
                // Make media conditional based on question type and part audio
                if (in_array($request->question_type, ['plan_map_diagram'])) {
                    // Plan/map/diagram might have their own image instead of audio
                    $rules['media'] = 'nullable|file|mimes:mp3,wav,ogg|max:51200';
                } elseif (!$partAudio || $request->input('use_custom_audio') == '1') {
                    // No part audio OR user wants custom audio = required
                    $rules['media'] = 'required|file|mimes:mp3,wav,ogg|max:51200';
                } else {
                    // Part audio exists and user doesn't want custom = optional
                    $rules['media'] = 'nullable|file|mimes:mp3,wav,ogg|max:51200';
                }
                
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
                
                // Add progressive card validation rules
                $rules['read_time'] = 'nullable|integer|min:3|max:60';
                $rules['min_response_time'] = 'nullable|integer|min:10|max:120';
                $rules['max_response_time'] = 'nullable|integer|min:30|max:300';
                $rules['auto_progress'] = 'nullable|boolean';
                $rules['card_theme'] = 'nullable|string|in:blue,purple,green,red';
                $rules['speaking_tips'] = 'nullable|string|max:500';
                $rules['cue_card_points_text'] = 'nullable|string';
                break;
        }
        
        // IMPORTANT: Only add options validation if question type needs it
        if ($this->needsOptions($request->question_type)) {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*.content'] = 'required|string';
            $rules['correct_option'] = 'required|integer|min:0';
        }
        
        // Add type-specific validation rules only if JSON data not provided
        if ($request->question_type === 'matching' && !$request->has('matching_pairs_json')) {
            $rules['matching_pairs'] = 'required|array|min:2';
            $rules['matching_pairs.*.left'] = 'required|string';
            $rules['matching_pairs.*.right'] = 'required|string';
        }

        if ($request->question_type === 'form_completion' && !$request->has('form_structure_json')) {
            $rules['form_structure.title'] = 'required|string';
            $rules['form_structure.fields'] = 'required|array|min:1';
            $rules['form_structure.fields.*.label'] = 'required|string';
            $rules['form_structure.fields.*.answer'] = 'required|string';
        }

        if ($request->question_type === 'plan_map_diagram' && !$request->has('diagram_hotspots_json')) {
            $rules['diagram_image'] = 'required|image|max:5120';
            $rules['diagram_hotspots'] = 'required|array|min:1';
            $rules['diagram_hotspots.*.answer'] = 'required|string';
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
        
        // Handle diagram image for plan_map_diagram
        if ($request->question_type === 'plan_map_diagram' && $request->hasFile('diagram_image')) {
            $diagramPath = $request->file('diagram_image')->store('questions/diagrams', 'public');
            $mediaPath = $diagramPath; // Override media path with diagram
        }
        
        // Handle type-specific data
        $typeSpecificData = [];

        // Check for JSON data first (new approach)
        if ($request->has('matching_pairs_json')) {
            $matchingPairs = json_decode($request->matching_pairs_json, true);
            if ($matchingPairs) {
                $typeSpecificData['matching_pairs'] = $matchingPairs;
                \Log::info('Matching pairs from JSON:', $matchingPairs);
            }
        } elseif ($request->question_type === 'matching' && $request->has('matching_pairs')) {
            // Fallback to old approach
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
                \Log::info('Matching pairs from form:', $matchingPairs);
            }
        }
        
        if ($request->has('form_structure_json')) {
            $formStructure = json_decode($request->form_structure_json, true);
            if ($formStructure) {
                $typeSpecificData['form_structure'] = $formStructure;
                \Log::info('Form structure from JSON:', $formStructure);
            }
        } elseif ($request->question_type === 'form_completion' && $request->has('form_structure')) {
            // Fallback to old approach
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
                \Log::info('Form structure from form:', $formStructure);
            }
        }
        
        if ($request->has('diagram_hotspots_json')) {
            $diagramHotspots = json_decode($request->diagram_hotspots_json, true);
            if ($diagramHotspots) {
                $typeSpecificData['diagram_hotspots'] = $diagramHotspots;
                \Log::info('Diagram hotspots from JSON:', $diagramHotspots);
            }
        } elseif ($request->question_type === 'plan_map_diagram' && $request->has('diagram_hotspots')) {
            // Fallback to old approach
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
                \Log::info('Diagram hotspots from form:', $hotspots);
            }
        }
        
        DB::transaction(function () use ($request, $testSet, $section, $mediaPath, $typeSpecificData) {
            // Determine if question should use part audio
            $usePartAudio = false; // Default to false
            
            if ($section === 'listening') {
                $partAudio = $testSet->getPartAudio($request->part_number ?? 1);
                
                // Use part audio if:
                // 1. Part audio exists AND
                // 2. User didn't upload custom audio AND
                // 3. User didn't explicitly choose custom audio
                if ($partAudio && !$mediaPath && $request->input('use_custom_audio') != '1') {
                    $usePartAudio = true;
                }
            }
            
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
                'use_part_audio' => $usePartAudio,
                'audio_transcript' => $request->audio_transcript ?? null,
                'word_limit' => $request->word_limit ?? null,
                'time_limit' => $request->time_limit ?? null,
            ];
            
            // Add progressive card fields for speaking section
            if ($section === 'speaking') {
                $questionData['read_time'] = $request->read_time ?? $this->getDefaultReadTime($request->question_type);
                $questionData['min_response_time'] = $request->min_response_time ?? $this->getDefaultMinResponse($request->question_type);
                $questionData['max_response_time'] = $request->max_response_time ?? $this->getDefaultMaxResponse($request->question_type);
                $questionData['auto_progress'] = $request->has('auto_progress') ? (bool)$request->auto_progress : true;
                $questionData['card_theme'] = $request->card_theme ?? 'blue';
                $questionData['speaking_tips'] = $request->speaking_tips;
                
                // Handle cue card points for Part 2
                if ($request->question_type === 'part2_cue_card' && $request->has('form_structure_json')) {
                    $questionData['form_structure'] = json_decode($request->form_structure_json, true);
                } elseif ($request->question_type === 'part2_cue_card' && $request->has('cue_card_points_text')) {
                    // Convert text to structure
                    $points = array_filter(array_map('trim', explode("\n", $request->cue_card_points_text)));
                    if (!empty($points)) {
                        $questionData['form_structure'] = [
                            'fields' => array_map(function($point) {
                                return ['label' => $point];
                            }, $points)
                        ];
                    }
                }
            }
            
            // Add type-specific fields directly
            if (isset($typeSpecificData['matching_pairs'])) {
                $questionData['matching_pairs'] = $typeSpecificData['matching_pairs'];
            }
            if (isset($typeSpecificData['form_structure']) && !isset($questionData['form_structure'])) {
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
            
            \Log::info('Question created:', ['id' => $question->id, 'use_part_audio' => $question->use_part_audio]);
            
            // Create options if applicable (using new method)
            if ($this->needsOptions($request->question_type) && isset($request->options)) {
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
    public function update(Request $request, Question $question): RedirectResponse
    {
        // Get test set to determine section
        $testSet = TestSet::with('section')->findOrFail($question->test_set_id);
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

        // Section-specific validation
        if ($section === 'speaking') {
            $rules['time_limit'] = 'required|integer|min:1|max:10';
            $rules['read_time'] = 'nullable|integer|min:3|max:60';
            $rules['min_response_time'] = 'nullable|integer|min:10|max:120';
            $rules['max_response_time'] = 'nullable|integer|min:30|max:300';
            $rules['auto_progress'] = 'nullable|boolean';
            $rules['card_theme'] = 'nullable|string|in:blue,purple,green,red';
            $rules['speaking_tips'] = 'nullable|string|max:500';
        }

        $request->validate($rules);

        // Update data array
        $updateData = [
            'question_type' => $request->question_type,
            'content' => $request->content,
            'order_number' => $request->order_number,
            'part_number' => $request->part_number ?? 1,
            'marks' => $request->marks ?? 1,
            'instructions' => $request->instructions,
            'word_limit' => $request->word_limit ?? null,
            'time_limit' => $request->time_limit ?? null,
        ];

        // Add progressive card fields for speaking
        if ($section === 'speaking') {
            $updateData['read_time'] = $request->read_time ?? $this->getDefaultReadTime($request->question_type);
            $updateData['min_response_time'] = $request->min_response_time ?? $this->getDefaultMinResponse($request->question_type);
            $updateData['max_response_time'] = $request->max_response_time ?? $this->getDefaultMaxResponse($request->question_type);
            $updateData['auto_progress'] = $request->has('auto_progress') ? (bool)$request->auto_progress : true;
            $updateData['card_theme'] = $request->card_theme ?? 'blue';
            $updateData['speaking_tips'] = $request->speaking_tips;
            
            // Handle cue card structure
            if ($request->question_type === 'part2_cue_card' && $request->has('form_structure_json')) {
                $updateData['form_structure'] = json_decode($request->form_structure_json, true);
            }
        }

        $question->update($updateData);

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
     * Check if question type requires options (old method - kept for compatibility)
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
     * Check if question type needs options (excluding special types)
     */
    private function needsOptions($questionType): bool
    {
        // Special types that don't need traditional options
        $specialTypes = ['matching', 'form_completion', 'plan_map_diagram'];
        
        // Text input types that don't need options
        $textTypes = ['short_answer', 'sentence_completion', 'note_completion'];
        
        // If it's a special type or text type, no options needed
        if (in_array($questionType, $specialTypes) || in_array($questionType, $textTypes)) {
            return false;
        }
        
        // Option-based types
        $optionTypes = ['multiple_choice', 'true_false', 'yes_no'];
        
        return in_array($questionType, $optionTypes);
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
    
    /**
     * Get default read time based on question type
     */
    private function getDefaultReadTime($questionType): int
    {
        return match($questionType) {
            'part1_personal' => 5,
            'part2_cue_card' => 60, // This is preparation time
            'part3_discussion' => 8,
            default => 5
        };
    }

    /**
     * Get default minimum response time based on question type
     */
    private function getDefaultMinResponse($questionType): int
    {
        return match($questionType) {
            'part1_personal' => 15,
            'part2_cue_card' => 60,
            'part3_discussion' => 30,
            default => 15
        };
    }

    /**
     * Get default maximum response time based on question type
     */
    private function getDefaultMaxResponse($questionType): int
    {
        return match($questionType) {
            'part1_personal' => 45,
            'part2_cue_card' => 120,
            'part3_discussion' => 90,
            default => 45
        };
    }
}