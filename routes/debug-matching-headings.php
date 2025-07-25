<?php

use Illuminate\Support\Facades\Route;
use App\Models\StudentAttempt;
use App\Models\Question;

Route::middleware(['auth', 'role:admin'])->prefix('debug')->group(function () {
    
    Route::get('/matching-headings/{attempt}', function (StudentAttempt $attempt) {
        $attempt->load(['testSet', 'answers']);
        
        // Get all matching headings questions
        $matchingHeadingsQuestions = $attempt->testSet->questions()
            ->where('question_type', 'matching_headings')
            ->get();
        
        $debug = [
            'attempt_id' => $attempt->id,
            'test_set' => $attempt->testSet->name,
            'matching_headings_questions' => []
        ];
        
        foreach ($matchingHeadingsQuestions as $question) {
            $questionData = [
                'id' => $question->id,
                'content' => $question->content,
                'is_master' => $question->isMasterMatchingHeading(),
                'section_data' => $question->section_specific_data,
                'options' => $question->options->map(function($opt) {
                    return [
                        'id' => $opt->id,
                        'content' => $opt->content,
                        'is_correct' => $opt->is_correct
                    ];
                }),
                'answers' => []
            ];
            
            // Get answers for this question
            $answers = $attempt->answers()->where('question_id', $question->id)->get();
            foreach ($answers as $answer) {
                $answerData = [
                    'id' => $answer->id,
                    'selected_option_id' => $answer->selected_option_id,
                    'answer' => $answer->answer
                ];
                
                if ($answer->answer) {
                    $decoded = json_decode($answer->answer, true);
                    $answerData['decoded'] = $decoded;
                }
                
                $questionData['answers'][] = $answerData;
            }
            
            $debug['matching_headings_questions'][] = $questionData;
        }
        
        // Count total questions and answers
        $allQuestions = $attempt->testSet->questions()
            ->where('question_type', '!=', 'passage')
            ->get();
            
        $totalQuestions = 0;
        foreach ($allQuestions as $q) {
            if ($q->isMasterMatchingHeading()) {
                $totalQuestions += $q->getActualQuestionCount();
            } else {
                $blankCount = $q->countBlanks();
                $totalQuestions += $blankCount > 0 ? $blankCount : 1;
            }
        }
        
        $debug['summary'] = [
            'total_questions' => $totalQuestions,
            'total_answers' => $attempt->answers()->count(),
            'matching_headings_count' => $matchingHeadingsQuestions->count()
        ];
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    });
    
    Route::get('/test-matching-submission', function() {
        // Create test data
        $testData = [
            'answers' => [
                '123_q14' => 'A',
                '123_q15' => 'B', 
                '123_q16' => 'C',
                '456' => '789',  // Regular answer
                'blank_test' => ['blank_1' => 'test answer']
            ]
        ];
        
        $processed = [];
        
        foreach ($testData['answers'] as $questionId => $answer) {
            if (str_contains($questionId, '_q')) {
                [$masterQuestionId, $subQuestionNum] = explode('_q', $questionId);
                $processed[] = [
                    'type' => 'master_matching',
                    'master_id' => $masterQuestionId,
                    'sub_question' => $subQuestionNum,
                    'answer' => $answer
                ];
            } else {
                $processed[] = [
                    'type' => 'regular',
                    'question_id' => $questionId,
                    'answer' => $answer
                ];
            }
        }
        
        return response()->json([
            'original' => $testData,
            'processed' => $processed
        ], 200, [], JSON_PRETTY_PRINT);
    });
});
