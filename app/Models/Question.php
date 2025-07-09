<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
    'test_set_id', 
    'question_type', 
    'content', 
    'media_path', 
    'order_number',
    'part_number',
    'question_group',
    'passage_text',
    'audio_transcript',
    'word_limit',
    'time_limit',
    'instructions',
    'marks',
    'is_example',
    'passage_id',
    'section_specific_data',
    'blank_count',
    'is_sub_question', 
    'parent_question_id',
    'sub_question_index',
    'matching_pairs',      // Add this
    'form_structure',      // Add this
    'diagram_hotspots',    // Add this
    'marker_id',
    'processed_explanation',
    'explanation',
    'passage_reference',
    'common_mistakes',
    'tips',
    'difficulty_level',
    'related_topics',
];

    protected $casts = [
        'word_limit' => 'integer',
        'time_limit' => 'integer',
        'marks' => 'integer',
        'part_number' => 'integer',
        'is_example' => 'boolean',
        'section_specific_data' => 'array',
        'blank_count' => 'integer',
        'is_sub_question' => 'boolean',
        'sub_question_index' => 'integer',
        'related_topics' => 'array',
        'matching_pairs' => 'array',
        'form_structure' => 'array',
        'diagram_hotspots' => 'array',
        'template_type' => 'string',
    ];
    
    /**
     * Process explanation to make {{Q1}} markers clickable
     */
    public function processExplanation(): string
    {
        if (!$this->explanation) {
            return '';
        }
        
        // Convert {{Q1}}, {{Q2}} etc to clickable spans
        $processed = preg_replace(
            '/\{\{(Q\d+)\}\}/',
            '<span class="marker-link" data-marker="$1" onclick="highlightMarker(\'$1\')">$1</span>',
            $this->explanation
        );
        
        return $processed;
    }
    
    /**
     * Get the marker text from passage
     */
    public function getMarkerText(): ?string
    {
        if (!$this->marker_id || !$this->testSet) {
            return null;
        }
        
        // Find passage for this test set
        $passage = $this->testSet->questions()
            ->where('question_type', 'passage')
            ->where('part_number', $this->part_number)
            ->first();
            
        if (!$passage) {
            return null;
        }
        
        // Extract text between markers
        $pattern = '/\{\{' . $this->marker_id . '\}\}(.*?)\{\{' . $this->marker_id . '\}\}/s';
        if (preg_match($pattern, $passage->passage_text ?? $passage->content, $matches)) {
            return trim($matches[1]);
        }
        
        return null;
    }
    
    /**
     * Process passage to add data attributes to markers (static method)
     */
    public static function processPassageForDisplay($passageText, $hideMarkers = true): string
    {
        if ($hideMarkers) {
            // For student view - hide markers but keep text markable
            $processed = preg_replace_callback(
                '/\{\{(Q\d+)\}\}(.*?)\{\{\\1\}\}/s',
                function($matches) {
                    $markerId = $matches[1];
                    $text = $matches[2];
                    return '<span class="marker-text" data-marker="' . $markerId . '" id="marker-' . $markerId . '">' . $text . '</span>';
                },
                $passageText
            );
        } else {
            // For admin view - show markers
            $processed = preg_replace_callback(
                '/\{\{(Q\d+)\}\}/',
                function($matches) {
                    return '<span class="marker-indicator">{{' . $matches[1] . '}}</span>';
                },
                $passageText
            );
        }
        
        return $processed;
    }
    
    /**
     * Check if question has a marker
     */
    public function hasMarker(): bool
    {
        return !empty($this->marker_id);
    }
    
    /**
     * Get all markers from a passage
     */
    public static function extractMarkersFromPassage($passageText): array
    {
        preg_match_all('/\{\{(Q\d+)\}\}/', $passageText, $matches);
        return array_unique($matches[1] ?? []);
    }
    
    /**
     * Validate marker exists in passage
     */
    public function validateMarkerInPassage(): bool
    {
        if (!$this->marker_id) {
            return true; // No marker is valid
        }
        
        $passage = $this->testSet->questions()
            ->where('question_type', 'passage')
            ->where('part_number', $this->part_number)
            ->first();
            
        if (!$passage) {
            return false;
        }
        
        $markers = self::extractMarkersFromPassage($passage->passage_text ?? $passage->content);
        return in_array($this->marker_id, $markers);
    }
    
    // expLanation
    public function hasExplanation(): bool
    {
        return !empty($this->explanation);
    }
    
    public function getDifficultyBadgeClass(): string
    {
        return match($this->difficulty_level) {
            'easy' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'hard' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
    
    public function testSet(): BelongsTo
    {
        return $this->belongsTo(TestSet::class);
    }
    
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function passage(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'passage_id');
    }

    public function relatedQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'passage_id');
    }
    
    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }

    public function correctOptions()
    {
        return $this->options()->where('is_correct', true)->get();
    }

    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'parent_question_id');
    }

    public function subQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'parent_question_id')->orderBy('sub_question_index');
    }

    /**
     * Count total blanks/dropdowns in content
     */
    public function countBlanks(): int
{
    $content = $this->content;
    preg_match_all('/\[____\d+____\]/', $content, $blankMatches);
    preg_match_all('/\[DROPDOWN_\d+\]/', $content, $dropdownMatches);
    
    return count($blankMatches[0]) + count($dropdownMatches[0]);
}

public function getBlankAnswers(): array
{
    if ($this->section_specific_data && isset($this->section_specific_data['blank_answers'])) {
        return $this->section_specific_data['blank_answers'];
    }
    return [];
}

public function getDropdownData(): array
{
    if ($this->section_specific_data) {
        return [
            'options' => $this->section_specific_data['dropdown_options'] ?? [],
            'correct' => $this->section_specific_data['dropdown_correct'] ?? []
        ];
    }
    return ['options' => [], 'correct' => []];
}


public function checkBlankAnswer($blankNumber, $studentAnswer): bool
{
    $blankAnswers = $this->getBlankAnswers();
    $correctAnswer = $blankAnswers[$blankNumber] ?? '';
    
    // Case-insensitive comparison, trimmed
    return strtolower(trim($studentAnswer)) === strtolower(trim($correctAnswer));
}

public function checkDropdownAnswer($dropdownNumber, $selectedIndex): bool
{
    $dropdownData = $this->getDropdownData();
    $correctIndex = $dropdownData['correct'][$dropdownNumber] ?? null;
    
    return $selectedIndex == $correctIndex;
}

    /**
     * Get display number for question (considering sub-questions)
     */
    public function getDisplayNumber(): string
    {
        if ($this->is_sub_question && $this->parentQuestion) {
            return $this->parentQuestion->order_number . '.' . $this->sub_question_index;
        }
        return (string) $this->order_number;
    }

    /**
     * Recalculate order numbers for all questions in test set
     */
    public static function recalculateOrderNumbers($testSetId)
    {
        $questions = self::where('test_set_id', $testSetId)
            ->where('is_sub_question', false)
            ->where('question_type', '!=', 'passage')
            ->orderBy('part_number')
            ->orderBy('order_number')
            ->get();
        
        $currentNumber = 1;
        
        foreach ($questions as $question) {
            $question->order_number = $currentNumber;
            $question->save();
            
            // Count blanks and adjust
            $blankCount = $question->countBlanks();
            if ($blankCount > 0) {
                $currentNumber += $blankCount;
            } else {
                $currentNumber++;
            }
        }
    }

    /**
     * Get question types available for each section
     */
    public static function getQuestionTypesBySection(): array
    {
        return [
            'listening' => [
                'multiple_choice' => 'Multiple Choice',
                'form_completion' => 'Form Completion',
                'note_completion' => 'Note Completion',
                'sentence_completion' => 'Sentence Completion',
                'short_answer' => 'Short Answer',
                'matching' => 'Matching',
                'plan_map_diagram' => 'Plan/Map/Diagram Labeling'
            ],
            'reading' => [
                'passage' => 'Reading Passage',
                'multiple_choice' => 'Multiple Choice',
                'true_false' => 'True/False/Not Given',
                'yes_no' => 'Yes/No/Not Given',
                'matching_headings' => 'Matching Headings',
                'matching_information' => 'Matching Information',
                'matching_features' => 'Matching Features',
                'sentence_completion' => 'Sentence Completion',
                'summary_completion' => 'Summary Completion',
                'short_answer' => 'Short Answer',
                'flow_chart' => 'Flow Chart Completion',
                'table_completion' => 'Table Completion'
            ],
            'writing' => [
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
            ],
            'speaking' => [
                'part1_personal' => 'Part 1: Personal Questions',
                'part2_cue_card' => 'Part 2: Cue Card',
                'part3_discussion' => 'Part 3: Discussion'
            ]
        ];
    }

    /**
     * Get section name from test set
     */
    public function getSectionAttribute(): string
    {
        return $this->testSet->section->name ?? '';
    }

    /**
     * Check if question requires media
     */
    public function requiresMedia(): bool
    {
        $section = $this->section;
        $type = $this->question_type;

        if ($section === 'listening') {
            return true; // All listening questions need audio
        }

        if ($section === 'writing' && in_array($type, [
            'task1_line_graph', 'task1_bar_chart', 'task1_pie_chart', 
            'task1_table', 'task1_process', 'task1_map'
        ])) {
            return true; // Writing Task 1 needs charts/diagrams
        }

        return false;
    }

    /**
     * Get default word limit based on question type
     */
    public function getDefaultWordLimit(): ?int
    {
        $type = $this->question_type;

        if (str_starts_with($type, 'task1_')) {
            return 150; // Writing Task 1
        }

        if (str_starts_with($type, 'task2_')) {
            return 250; // Writing Task 2
        }

        return null;
    }

    /**
     * Get default time limit based on question type
     */
    public function getDefaultTimeLimit(): ?int
    {
        $section = $this->section;
        $type = $this->question_type;

        switch ($section) {
            case 'writing':
                return str_starts_with($type, 'task1_') ? 20 : 40; // minutes
            case 'speaking':
                if ($type === 'part1_personal') return 5;
                if ($type === 'part2_cue_card') return 2;
                if ($type === 'part3_discussion') return 5;
                break;
        }

        return null;
    }

    /**
     * Scope to get questions by section
     */
    public function scopeBySection($query, $section)
    {
        return $query->whereHas('testSet.section', function($q) use ($section) {
            $q->where('name', $section);
        });
    }

    /**
     * Scope to get questions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }

    /**
     * Check if question is a passage (reading)
     */
    public function isPassage(): bool
    {
        return $this->question_type === 'passage';
    }

    /**
     * Check if question requires options
     */
    public function requiresOptions(): bool
    {
        return in_array($this->question_type, [
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
     * Get question instructions based on type
     */
    public function getDefaultInstructions(): string
    {
        $instructions = [
            'multiple_choice' => 'Choose the correct letter, A, B, C or D.',
            'true_false' => 'Do the following statements agree with the information given in the reading passage? Write TRUE if the statement agrees with the information, FALSE if the statement contradicts the information, or NOT GIVEN if there is no information on this.',
            'yes_no' => 'Do the following statements agree with the views of the writer in the reading passage? Write YES if the statement agrees with the views of the writer, NO if the statement contradicts the views of the writer, or NOT GIVEN if it is impossible to say what the writer thinks about this.',
            'short_answer' => 'Answer the questions below. Choose NO MORE THAN TWO WORDS from the passage for each answer.',
            'sentence_completion' => 'Complete the sentences below. Choose NO MORE THAN TWO WORDS from the passage for each answer.',
            'form_completion' => 'Complete the form below. Write NO MORE THAN TWO WORDS AND/OR A NUMBER for each answer.',
            'note_completion' => 'Complete the notes below. Write NO MORE THAN TWO WORDS for each answer.',
        ];

        return $this->instructions ?? $instructions[$this->question_type] ?? '';
    }


    /**
 * Check if question has special layout
 */
public function hasSpecialLayout(): bool
{
    return in_array($this->question_type, [
        'matching',
        'form_completion',
        'plan_map_diagram'
    ]);
}

/**
 * Get matching pairs for display
 */
public function getMatchingPairs(): array
{
    if ($this->question_type !== 'matching' || !$this->matching_pairs) {
        return [];
    }
    
    return $this->matching_pairs;
}

/**
 * Get form structure
 */
public function getFormStructure(): array
{
    if ($this->question_type !== 'form_completion' || !$this->form_structure) {
        return [];
    }
    
    return $this->form_structure;
}

/**
 * Get diagram hotspots
 */
public function getDiagramHotspots(): array
{
    if ($this->question_type !== 'plan_map_diagram' || !$this->diagram_hotspots) {
        return [];
    }
    
    return $this->diagram_hotspots;
}

/**
 * Get the audio path for this question
 */
public function getAudioPathAttribute(): ?string
{
    // If question uses its own audio
    if (!$this->use_part_audio && $this->media_path) {
        return $this->media_path;
    }
    
    // If question uses part audio
    if ($this->use_part_audio && $this->testSet) {
        $partAudio = $this->testSet->getPartAudio($this->part_number);
        return $partAudio ? $partAudio->audio_path : null;
    }
    
    return null;
}

/**
 * Check if question has audio available
 */
public function hasAudio(): bool
{
    return !empty($this->audio_path);
}
 
}