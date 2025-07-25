<?php

use Illuminate\Support\Facades\Route;
use App\Models\Question;

Route::get('/test-matching-headings-display', function() {
    // Find a matching headings question
    $question = Question::where('question_type', 'matching_headings')
        ->whereHas('testSet', function($q) {
            $q->whereHas('section', function($s) {
                $s->where('name', 'reading');
            });
        })
        ->first();
    
    if (!$question) {
        return response()->json(['error' => 'No matching headings question found']);
    }
    
    $debug = [
        'question_id' => $question->id,
        'is_master' => $question->isMasterMatchingHeading(),
        'section_data' => $question->section_specific_data,
        'options_count' => $question->options()->count(),
        'options' => $question->options->map(fn($o) => [
            'id' => $o->id,
            'content' => $o->content,
            'is_correct' => $o->is_correct
        ])
    ];
    
    if ($question->isMasterMatchingHeading()) {
        try {
            $display = $question->generateMatchingHeadingsDisplay();
            $debug['display_data'] = $display;
        } catch (\Exception $e) {
            $debug['display_error'] = $e->getMessage();
            $debug['display_trace'] = $e->getTraceAsString();
        }
    }
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
});
