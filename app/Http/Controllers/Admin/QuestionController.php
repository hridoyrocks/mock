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

class EnhancedQuestionController extends Controller
{
    /**
     * Display section-wise question management interface.
     */
    public function index(Request $request): View
    {
        $sections = TestSection::with(['testSets.questions' => function ($query) {
            $query->orderBy('order_number');
        }])->get();

        $selectedSection = $request->get('section', 'listening');
        $currentSection = $sections->where('name', $selectedSection)->first();

        return view('admin.questions.enhanced-index', compact('sections', 'selectedSection', 'currentSection'));
    }

    /**
     * Show the advanced question creation interface.
     */
    public function create(Request $request): View
    {
        $testSets = TestSet::with('section')->get();
        $preselectedTestSet = $request->test_set;
        $preselectedSection = $request->section;
        
        return view('admin.questions.enhanced-create', compact('testSets', 'preselectedTestSet', 'preselectedSection'));
    }

    /**
     * Store a newly created question with enhanced features.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'test_set_id' => 'required|exists:test_sets,id',
            'question_type' => 'required|in:passage,multiple_choice,true_false,matching,fill_blank,short_answer,essay,cue_card',
            'content' => 'required|string',
            'order_number' => 'required|integer|min:1',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp3,wav,ogg,mp4|max:20480', // 20MB max
            'explanation' => 'nullable|string',
            'difficulty_level' => 'nullable|in:easy,medium,hard',
            'tags' => 'nullable|array',
            'time_limit' => 'nullable|integer|min:1',
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
            // Process content with shortcodes
            $processedContent = $this->processShortcodes($request->content);
            
            // Create the question
            $question = Question::create([
                'test_set_id' => $request->test_set_id,
                'question_type' => $request->question_type,
                'content' => $processedContent,
                'original_content' => $request->content, // Store original with shortcodes
                'media_path' => $mediaPath,
                'order_number' => $request->order_number,
                'explanation' => $request->explanation,
                'difficulty_level' => $request->difficulty_level ?? 'medium',
                'tags' => $request->tags ? json_encode($request->tags) : null,
                'time_limit' => $request->time_limit,
            ]);
            
            // Create options if applicable
            if (in_array($request->question_type, ['multiple_choice', 'true_false', 'matching']) && isset($request->options)) {
                foreach ($request->options as $index => $option) {
                    if (!empty($option['content'])) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'content' => trim($option['content']),
                            'is_correct' => ($request->correct_option == $index),
                            'explanation' => $option['explanation'] ?? null,
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
        
        return redirect()->route('admin.questions.enhanced.index', ['section' => $request->section ?? 'listening'])
            ->with('success', 'Question created successfully with enhanced features.');
    }

    /**
     * Update question order via drag and drop.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order_number' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->questions as $questionData) {
                Question::where('id', $questionData['id'])
                    ->update(['order_number' => $questionData['order_number']]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Bulk operations on questions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,duplicate,move,change_difficulty',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
            'target_test_set' => 'nullable|exists:test_sets,id',
            'difficulty_level' => 'nullable|in:easy,medium,hard',
        ]);

        $questions = Question::whereIn('id', $request->question_ids);

        switch ($request->action) {
            case 'delete':
                $questions->delete();
                break;
            
            case 'duplicate':
                foreach ($questions->get() as $question) {
                    $this->duplicateQuestion($question);
                }
                break;
            
            case 'move':
                if ($request->target_test_set) {
                    $questions->update(['test_set_id' => $request->target_test_set]);
                }
                break;
            
            case 'change_difficulty':
                if ($request->difficulty_level) {
                    $questions->update(['difficulty_level' => $request->difficulty_level]);
                }
                break;
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get question templates for different types.
     */
    public function getTemplate(Request $request)
    {
        $type = $request->get('type');
        
        $templates = [
            'passage' => [
                'needs_options' => false,
                'shortcodes' => ['[highlight]', '[underline]', '[bold]'],
                'placeholder' => 'Enter the reading passage or text content here...\n\nUse shortcodes like [highlight]important text[/highlight] to emphasize content.',
                'description' => 'This will be displayed as reference material for students.',
                'example' => 'The Industrial Revolution was a period of [highlight]major industrialization[/highlight] that took place during the late 1700s and early 1800s.',
            ],
            'multiple_choice' => [
                'needs_options' => true,
                'min_options' => 3,
                'max_options' => 6,
                'shortcodes' => ['[blank]', '[highlight]', '[underline]'],
                'placeholder' => 'Enter your question here...\n\nUse [blank] for fill-in-the-blank style questions.',
                'option_placeholder' => 'Enter option text...',
                'description' => 'Students will select one correct answer from multiple options.',
                'example' => 'What is the capital of [blank]?\nA) London\nB) Paris\nC) Berlin\nD) Rome',
            ],
            'true_false' => [
                'needs_options' => true,
                'fixed_options' => ['True', 'False', 'Not Given'],
                'shortcodes' => ['[highlight]', '[underline]'],
                'placeholder' => 'Enter a statement for students to evaluate...',
                'description' => 'Students will choose True, False, or Not Given.',
                'example' => 'The passage states that [highlight]climate change[/highlight] is the primary concern for scientists.',
            ],
            'matching' => [
                'needs_options' => true,
                'min_options' => 4,
                'max_options' => 8,
                'shortcodes' => ['[list]', '[item]'],
                'placeholder' => 'Enter matching instruction...\n\nUse [list] and [item] for structured lists.',
                'option_placeholder' => 'Enter matching pair...',
                'description' => 'Students will match items from two lists.',
                'example' => 'Match the following countries with their capitals:\n[list]\n[item]France - Paris[/item]\n[item]Germany - Berlin[/item]\n[/list]',
            ],
            'fill_blank' => [
                'needs_options' => false,
                'shortcodes' => ['[blank]', '[blank:5]', '[blank:word]'],
                'placeholder' => 'Enter text with blanks using [blank] shortcode...',
                'description' => 'Students will fill in the missing words.',
                'example' => 'The [blank] Revolution began in the [blank:4] century and changed the way people [blank:worked].',
            ],
            'short_answer' => [
                'needs_options' => false,
                'shortcodes' => ['[highlight]', '[underline]', '[word_limit:50]'],
                'placeholder' => 'Enter question requiring a brief written response...\n\nUse [word_limit:X] to set word limits.',
                'description' => 'Students will provide short text answers.',
                'example' => 'Explain the main cause of the Industrial Revolution in [word_limit:30] words.',
            ],
            'essay' => [
                'needs_options' => false,
                'shortcodes' => ['[word_limit:250]', '[time_limit:40]', '[highlight]'],
                'placeholder' => 'Enter essay prompt or task description...\n\nUse [word_limit:X] and [time_limit:X] for requirements.',
                'description' => 'Students will write detailed responses (for Writing section).',
                'example' => 'Discuss the impact of technology on modern education. [word_limit:250] [time_limit:40]',
            ],
            'cue_card' => [
                'needs_options' => false,
                'shortcodes' => ['[time_limit:2]', '[bullet]', '[highlight]'],
                'placeholder' => 'Enter speaking topic with bullet points for guidance...\n\nUse [bullet] for bullet points.',
                'description' => 'Students will speak on this topic (for Speaking section).',
                'example' => 'Describe a memorable trip you have taken.\n[bullet]Where did you go?\n[bullet]Who did you go with?\n[bullet]What made it memorable?\n[time_limit:2]',
            ]
        ];
        
        return response()->json($templates[$type] ?? []);
    }

    /**
     * Process shortcodes in content.
     */
    private function processShortcodes($content)
    {
        // Define shortcode patterns and their replacements
        $shortcodes = [
            // Text formatting
            '/\[highlight\](.*?)\[\/highlight\]/s' => '<mark class="bg-yellow-200 px-1 rounded">$1</mark>',
            '/\[underline\](.*?)\[\/underline\]/s' => '<u class="decoration-2">$1</u>',
            '/\[bold\](.*?)\[\/bold\]/s' => '<strong class="font-semibold">$1</strong>',
            
            // Fill in the blank
            '/\[blank\]/' => '<span class="inline-block border-b-2 border-gray-400 min-w-[80px] h-6 mx-1"></span>',
            '/\[blank:(\d+)\]/' => '<span class="inline-block border-b-2 border-gray-400 min-w-[${1}px] h-6 mx-1"></span>',
            '/\[blank:(\w+)\]/' => '<span class="inline-block border-b-2 border-gray-400 min-w-[100px] h-6 mx-1" data-answer="$1"></span>',
            
            // Lists
            '/\[list\](.*?)\[\/list\]/s' => '<ul class="list-disc ml-6 my-2">$1</ul>',
            '/\[item\](.*?)\[\/item\]/s' => '<li class="mb-1">$1</li>',
            '/\[bullet\]/' => '<li class="mb-1">',
            
            // Metadata (these won't be displayed but stored for processing)
            '/\[word_limit:(\d+)\]/' => '<span class="hidden" data-word-limit="$1"></span>',
            '/\[time_limit:(\d+)\]/' => '<span class="hidden" data-time-limit="$1"></span>',
        ];

        $processedContent = $content;
        foreach ($shortcodes as $pattern => $replacement) {
            $processedContent = preg_replace($pattern, $replacement, $processedContent);
        }

        return $processedContent;
    }

    /**
     * Duplicate a question with all its options.
     */
    private function duplicateQuestion($originalQuestion)
    {
        $newQuestion = $originalQuestion->replicate();
        $newQuestion->order_number = Question::where('test_set_id', $originalQuestion->test_set_id)->max('order_number') + 1;
        $newQuestion->save();

        // Duplicate options
        foreach ($originalQuestion->options as $option) {
            $newOption = $option->replicate();
            $newOption->question_id = $newQuestion->id;
            $newOption->save();
        }

        return $newQuestion;
    }

    /**
     * Preview question with processed shortcodes.
     */
    public function preview(Request $request)
    {
        $content = $request->get('content', '');
        $processedContent = $this->processShortcodes($content);
        
        return response()->json([
            'original' => $content,
            'processed' => $processedContent
        ]);
    }

    /**
     * Get shortcode help.
     */
    public function shortcodeHelp()
    {
        $shortcodes = [
            'Text Formatting' => [
                '[highlight]text[/highlight]' => 'Highlights text with yellow background',
                '[underline]text[/underline]' => 'Underlines text',
                '[bold]text[/bold]' => 'Makes text bold',
            ],
            'Fill in the Blank' => [
                '[blank]' => 'Creates a standard blank space',
                '[blank:100]' => 'Creates a blank space with specific width (pixels)',
                '[blank:answer]' => 'Creates a blank with expected answer for reference',
            ],
            'Lists' => [
                '[list][item]Item 1[/item][item]Item 2[/item][/list]' => 'Creates a bulleted list',
                '[bullet]' => 'Creates a single bullet point',
            ],
            'Metadata' => [
                '[word_limit:250]' => 'Sets word limit for responses',
                '[time_limit:40]' => 'Sets time limit in minutes',
            ],
        ];

        return response()->json($shortcodes);
    }
}