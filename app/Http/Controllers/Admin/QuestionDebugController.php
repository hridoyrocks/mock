<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionDebugController extends Controller
{
    public function debugMultipleChoice(Request $request)
    {
        \Log::info('=== DEBUG MULTIPLE CHOICE START ===');
        
        // Check what's coming in
        \Log::info('Request method: ' . $request->method());
        \Log::info('Question type: ' . $request->question_type);
        \Log::info('Correct option raw: ', ['value' => $request->input('correct_option')]);
        \Log::info('Is array: ' . (is_array($request->input('correct_option')) ? 'YES' : 'NO'));
        
        // Check validation
        $rules = [
            'question_type' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.content' => 'required|string',
        ];
        
        try {
            $request->validate($rules);
            \Log::info('Basic validation passed');
        } catch (\Exception $e) {
            \Log::error('Validation failed: ' . $e->getMessage());
        }
        
        // Check needsOptions logic
        $needsOptions = $this->needsOptions($request->question_type);
        \Log::info('Needs options: ' . ($needsOptions ? 'YES' : 'NO'));
        
        // Check manual validation
        if ($request->question_type === 'multiple_choice' && $needsOptions) {
            $correctOption = $request->input('correct_option', []);
            
            $hasCorrectOption = false;
            if (is_array($correctOption)) {
                $hasCorrectOption = count(array_filter($correctOption)) > 0;
            } else {
                $hasCorrectOption = !empty($correctOption);
            }
            
            \Log::info('Has correct option: ' . ($hasCorrectOption ? 'YES' : 'NO'));
            \Log::info('Correct options after filter: ', ['value' => array_filter($correctOption)]);
        }
        
        \Log::info('=== DEBUG END ===');
        
        return response()->json([
            'status' => 'debug_complete',
            'check_logs' => true
        ]);
    }
    
    private function needsOptions($questionType): bool
    {
        $specialTypes = ['matching', 'form_completion', 'plan_map_diagram', 'matching_headings'];
        $textTypes = ['short_answer', 'sentence_completion', 'note_completion', 'summary_completion', 'fill_blanks'];
        
        if (in_array($questionType, $specialTypes) || in_array($questionType, $textTypes)) {
            return false;
        }
        
        $optionTypes = ['multiple_choice', 'true_false', 'yes_no', 'matching_information', 'matching_features'];
        
        return in_array($questionType, $optionTypes);
    }
}
