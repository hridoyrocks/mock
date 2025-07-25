<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TestSet;
use App\Models\Question;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;

class ReadingDebugController extends Controller
{
    public function debugMatchingHeadings(Request $request)
    {
        // Get the latest reading test attempt
        $latestAttempt = StudentAttempt::whereHas('testSet.section', function($q) {
            $q->where('name', 'reading');
        })
        ->where('user_id', auth()->id())
        ->latest()
        ->first();
        
        if (!$latestAttempt) {
            return response()->json(['error' => 'No reading test attempt found']);
        }
        
        // Get all matching_headings questions from this test
        $matchingHeadingQuestions = Question::where('test_set_id', $latestAttempt->test_set_id)
            ->where('question_type', 'matching_headings')
            ->get();
            
        $debugData = [
            'attempt_id' => $latestAttempt->id,
            'test_set_id' => $latestAttempt->test_set_id,
            'matching_heading_questions' => []
        ];
        
        foreach ($matchingHeadingQuestions as $question) {
            $answers = StudentAnswer::where('attempt_id', $latestAttempt->id)
                ->where('question_id', $question->id)
                ->get();
                
            $debugData['matching_heading_questions'][] = [
                'question_id' => $question->id,
                'question_content' => $question->content,
                'section_specific_data' => $question->section_specific_data,
                'saved_answers' => $answers->map(function($a) {
                    return [
                        'id' => $a->id,
                        'answer' => $a->answer,
                        'selected_option_id' => $a->selected_option_id,
                        'created_at' => $a->created_at
                    ];
                })
            ];
        }
        
        // Also check raw request data
        $debugData['raw_request_data'] = $request->all();
        
        return response()->json($debugData);
    }
    
    public function testFormSubmission(Request $request)
    {
        \Log::info('Test Form Submission', [
            'all_data' => $request->all(),
            'answers' => $request->input('answers', []),
            'method' => $request->method(),
            'content_type' => $request->header('content-type')
        ]);
        
        return response()->json([
            'received_data' => $request->all(),
            'answers' => $request->input('answers', []),
            'headers' => $request->headers->all()
        ]);
    }
}
