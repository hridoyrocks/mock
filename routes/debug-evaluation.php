<?php

use Illuminate\Support\Facades\Route;
use App\Models\HumanEvaluationRequest;

Route::middleware(['auth', 'teacher'])->group(function () {
    Route::get('/debug/evaluation/{evaluationRequest}', function (HumanEvaluationRequest $evaluationRequest) {
        if ($evaluationRequest->teacher->user_id !== auth()->id()) {
            abort(403);
        }
        
        $evaluationRequest->load([
            'studentAttempt.testSet.section',
            'studentAttempt.testSet.questions',
            'studentAttempt.answers.question',
            'studentAttempt.answers.speakingRecording'
        ]);
        
        $data = [
            'evaluation_request' => $evaluationRequest->toArray(),
            'student_attempt' => $evaluationRequest->studentAttempt->toArray(),
            'answers' => $evaluationRequest->studentAttempt->answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'question_id' => $answer->question_id,
                    'answer' => $answer->answer,
                    'selected_option_id' => $answer->selected_option_id,
                    'question' => $answer->question ? $answer->question->toArray() : null,
                ];
            })->toArray(),
            'section' => $evaluationRequest->studentAttempt->testSet->section->name,
        ];
        
        return response()->json($data);
    });
});
