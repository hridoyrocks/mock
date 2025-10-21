<?php

namespace App\Models;

use App\Traits\ManagesBlanks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use ManagesBlanks;
    protected $fillable = [
    'test_set_id', 
    'question_type', 
    'content', 
    'media_path', 
    'media_url',          // Add this for CDN URLs
    'storage_disk',       // Add this for storage disk info
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
    'read_time',
    'min_response_time',
    'max_response_time',
    'auto_progress',
    'card_theme',
    'speaking_tips',
    'use_part_audio',     // Add this
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
        'read_time' => 'integer',
    'min_response_time' => 'integer',
    'max_response_time' => 'integer',
    'auto_progress' => 'boolean',
    ];

    // Add these helper methods:
public function getCardThemeColors()
{
    $themes = [
        'blue' => ['bg' => '#EBF5FF', 'border' => '#3B82F6', 'text' => '#1E40AF'],
        'purple' => ['bg' => '#F3E8FF', 'border' => '#9333EA', 'text' => '#6B21A8'],
        'green' => ['bg' => '#F0FDF4', 'border' => '#10B981', 'text' => '#166534'],
        'red' => ['bg' => '#FEF2F2', 'border' => '#EF4444', 'text' => '#991B1B'],
    ];
    
    return $themes[$this->card_theme] ?? $themes['blue'];
}

public function getProgressiveSettings()
{
    return [
        'read_time' => $this->read_time ?? 5,
        'min_response' => $this->min_response_time ?? 15,
        'max_response' => $this->max_response_time ?? 45,
        'auto_progress' => $this->auto_progress ?? true,
        'theme' => $this->getCardThemeColors()
    ];
}
    
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

    public function blanks(): HasMany
    {
        return $this->hasMany(QuestionBlank::class)->orderBy('blank_number');
    }

    /**
     * Count total blanks/dropdowns in content
     */
    public function countBlanks(): int
{
    $content = $this->content;
    preg_match_all('/\[____\d+____\]/', $content, $blankMatches);
    preg_match_all('/\[DROPDOWN_\d+\]/', $content, $dropdownMatches);
    preg_match_all('/\[HEADING_DROPDOWN_\d+\]/', $content, $headingDropdownMatches);
    preg_match_all('/\[DRAG_\d+\]/', $content, $dragZoneMatches);
    
    $contentCount = count($blankMatches[0]) + count($dropdownMatches[0]) + count($headingDropdownMatches[0]) + count($dragZoneMatches[0]);
    
    // Also check section_specific_data for dropdown_correct
    $dropdownDataCount = 0;
    if ($this->section_specific_data && isset($this->section_specific_data['dropdown_correct'])) {
        $dropdownDataCount = count($this->section_specific_data['dropdown_correct']);
    }
    
    // Check for drag zones in section_specific_data
    $dragZoneCount = 0;
    if ($this->question_type === 'drag_drop' && $this->section_specific_data) {
        if (isset($this->section_specific_data['drop_zones'])) {
            $dragZoneCount = count($this->section_specific_data['drop_zones']);
        } elseif (isset($this->section_specific_data['drag_zones'])) {
            $dragZoneCount = count($this->section_specific_data['drag_zones']);
        }
    }
    
    // Return the maximum to ensure we count all blanks/dropdowns/drag zones
    return max($contentCount, $dropdownDataCount, $dragZoneCount);
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
            'single_choice',
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
        // getPartAudio already handles Full Audio (part_number = 0) fallback
        // It checks full audio first, then specific part audio
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

/**
 * Get the media URL (handles CDN URLs)
 */
public function getMediaUrlAttribute(): ?string
{
    if (!$this->media_path) {
        return null;
    }
    
    // Check if we have a stored CDN URL (for newer uploads)
    if (isset($this->attributes['media_url'])) {
        return $this->attributes['media_url'];
    }
    
    // Check storage disk
    $disk = $this->storage_disk ?? 'public';
    
    if ($disk === 'r2') {
        // Generate R2 CDN URL
        $baseUrl = rtrim(config('filesystems.disks.r2.url'), '/');
        return $baseUrl . '/' . ltrim($this->media_path, '/');
    }
    
    // Local storage URL
    return asset('storage/' . $this->media_path);
}

/**
 * Get matching headings data
 */
public function getMatchingHeadingsData(): array
{
    if ($this->question_type !== 'matching_headings') {
        return [];
    }
    
    $data = $this->section_specific_data ?? [];
    
    return [
        'headings' => $data['headings'] ?? [],
        'mappings' => $data['mappings'] ?? []
    ];
}

/**
 * Set matching headings data
 */
public function setMatchingHeadingsData(array $headings, array $mappings): void
{
    $data = $this->section_specific_data ?? [];
    $data['headings'] = $headings;
    $data['mappings'] = $mappings;
    
    $this->section_specific_data = $data;
    $this->save();
}

/**
 * Check if question has matching headings data
 */
public function hasMatchingHeadingsData(): bool
{
    if ($this->question_type !== 'matching_headings') {
        return false;
    }
    
    $data = $this->getMatchingHeadingsData();
    
    return !empty($data['headings']) && !empty($data['mappings']);
}

/**
 * Check if this is a master matching headings question
 */
public function isMasterMatchingHeading(): bool
{
    return $this->question_type === 'matching_headings' && 
           isset($this->section_specific_data['mappings']) && 
           count($this->section_specific_data['mappings']) > 1;
}

/**
 * Get individual question numbers from master
 */
public function getIndividualQuestionNumbers(): array
{
    if (!$this->isMasterMatchingHeading()) {
        return [];
    }
    
    $mappings = $this->section_specific_data['mappings'] ?? [];
    return array_column($mappings, 'question');
}

/**
 * Generate display for matching headings in test
 */
public function generateMatchingHeadingsDisplay(): array
{
    if (!$this->isMasterMatchingHeading()) {
        return [];
    }
    
    $data = $this->getMatchingHeadingsData();
    $result = [];
    
    // Group data
    $result['instructions'] = $this->instructions ?? 'Choose the correct heading for each paragraph from the list of headings below.';
    $result['headings'] = [];
    
    // Format headings with letters
    foreach ($this->options as $index => $option) {
        $result['headings'][] = [
            'letter' => chr(65 + $index),
            'text' => $option->content
        ];
    }
    
    // Individual questions
    $result['questions'] = [];
    foreach ($data['mappings'] as $mapping) {
        $result['questions'][] = [
            'number' => $mapping['question'],
            'paragraph' => $mapping['paragraph'],
            'correct' => $mapping['correct']
        ];
    }
    
    return $result;
}

/**
 * Get question range string for display (e.g., "14-18")
 */
public function getQuestionRangeAttribute(): string
{
    // For matching headings with mappings
    if ($this->isMasterMatchingHeading()) {
        $numbers = $this->getIndividualQuestionNumbers();
        if (!empty($numbers)) {
            sort($numbers);
            return $numbers[0] . '-' . end($numbers);
        }
    }
    
    // For questions with blanks/dropdowns/drag zones
    $blankCount = $this->countBlanks();
    if ($blankCount > 1) {
        $endNumber = $this->order_number + $blankCount - 1;
        return $this->order_number . '-' . $endNumber;
    }
    
    // For multiple choice with multiple correct answers
    if ($this->question_type === 'multiple_choice') {
        $correctCount = $this->options->where('is_correct', true)->count();
        if ($correctCount > 1) {
            $endNumber = $this->order_number + $correctCount - 1;
            return $this->order_number . '-' . $endNumber;
        }
    }
    
    // Single question
    return (string) $this->order_number;
}

/**
 * Count actual questions in master
 */
public function getActualQuestionCount(): int
{
    // For matching headings
    if ($this->isMasterMatchingHeading()) {
        return count($this->section_specific_data['mappings'] ?? []);
    }
    
    // For questions with blanks/dropdowns/drag zones
    $blankCount = $this->countBlanks();
    if ($blankCount > 0) {
        return $blankCount;
    }
    
    // For multiple choice with multiple correct answers
    if ($this->question_type === 'multiple_choice') {
        $correctCount = $this->options->where('is_correct', true)->count();
        if ($correctCount > 1) {
            return $correctCount;
        }
    }
    
    // Single question
    return 1;
}

/**
 * Get correct answer for display (handles all question types)
 */
public function getCorrectAnswerForDisplay(): string
{
    // Multiple choice types
    if ($this->correctOption()) {
        return $this->correctOption()->content;
    }
    
    // Fill in the blanks and dropdowns
    if ($this->section_specific_data) {
        $answers = [];
        
        // Get blank answers
        if (isset($this->section_specific_data['blank_answers'])) {
            $blankAnswers = $this->section_specific_data['blank_answers'];
            if (is_array($blankAnswers)) {
                foreach ($blankAnswers as $num => $answer) {
                    $answers['blank_' . $num] = $answer;
                }
            }
        }
        
        // Get dropdown answers
        if (isset($this->section_specific_data['dropdown_correct']) && 
            isset($this->section_specific_data['dropdown_options'])) {
            $dropdownCorrect = $this->section_specific_data['dropdown_correct'];
            $dropdownOptions = $this->section_specific_data['dropdown_options'];
            
            if (is_array($dropdownCorrect) && is_array($dropdownOptions)) {
                foreach ($dropdownCorrect as $num => $correctIndex) {
                    if (isset($dropdownOptions[$num])) {
                        $options = array_map('trim', explode(',', $dropdownOptions[$num]));
                        if (isset($options[$correctIndex])) {
                            $answers['dropdown_' . $num] = $options[$correctIndex];
                        }
                    }
                }
            }
        }
        
        if (!empty($answers)) {
            // Sort by key to maintain order
            ksort($answers);
            return implode(', ', $answers);
        }
        
        // Single text answer
        if (isset($this->section_specific_data['correct_answer'])) {
            return $this->section_specific_data['correct_answer'];
        }
    }
    
    return 'See explanation';
}

/**
 * Get dropdown correct answers for display
 */
public function getDropdownCorrectAnswers(): array
{
    if (!$this->section_specific_data || 
        !isset($this->section_specific_data['dropdown_correct']) ||
        !isset($this->section_specific_data['dropdown_options'])) {
        return [];
    }
    
    $correctAnswers = [];
    $dropdownCorrect = $this->section_specific_data['dropdown_correct'];
    $dropdownOptions = $this->section_specific_data['dropdown_options'];
    
    foreach ($dropdownCorrect as $num => $correctIndex) {
        if (isset($dropdownOptions[$num])) {
            $options = array_map('trim', explode(',', $dropdownOptions[$num]));
            if (isset($options[$correctIndex])) {
                $correctAnswers[$num] = $options[$correctIndex];
            }
        }
    }
    
    return $correctAnswers;
}

/**
 * Get blank answers as array
 */
public function getBlankAnswersArray(): array
{
    if ($this->section_specific_data && isset($this->section_specific_data['blank_answers'])) {
        $answers = $this->section_specific_data['blank_answers'];
        if (is_array($answers)) {
            return $answers;
        }
    }
    return [];
}

/**
 * Check if this is an enhanced sentence completion question
 */
public function isEnhancedSentenceCompletion(): bool
{
    return $this->question_type === 'sentence_completion' && 
           isset($this->section_specific_data['sentence_completion']) &&
           is_array($this->section_specific_data['sentence_completion']);
}

/**
 * Get sentence completion data
 */
public function getSentenceCompletionData(): array
{
    if (!$this->isEnhancedSentenceCompletion()) {
        return [];
    }
    
    return $this->section_specific_data['sentence_completion'];
}

/**
 * Get sentence completion question count
 */
public function getSentenceCompletionCount(): int
{
    if (!$this->isEnhancedSentenceCompletion()) {
        return 1;
    }
    
    $data = $this->getSentenceCompletionData();
    return isset($data['sentences']) ? count($data['sentences']) : 0;
}

/**
 * Get sentence completion question numbers
 */
public function getSentenceCompletionQuestionNumbers(): array
{
    if (!$this->isEnhancedSentenceCompletion()) {
        return [$this->order_number];
    }
    
    $data = $this->getSentenceCompletionData();
    $numbers = [];
    
    if (isset($data['sentences'])) {
        foreach ($data['sentences'] as $sentence) {
            if (isset($sentence['questionNumber'])) {
                $numbers[] = $sentence['questionNumber'];
            }
        }
    }
    
    return $numbers;
}
 
}