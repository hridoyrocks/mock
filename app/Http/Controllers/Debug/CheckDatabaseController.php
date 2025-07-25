<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDatabaseController extends Controller
{
    public function checkMatchingHeadings()
    {
        // Get last 10 student answers for matching_headings questions
        $results = DB::select("
            SELECT 
                sa.id,
                sa.attempt_id,
                sa.question_id,
                sa.answer,
                sa.selected_option_id,
                sa.created_at,
                q.question_type,
                q.content as question_content,
                qo.content as option_content
            FROM student_answers sa
            JOIN questions q ON sa.question_id = q.id
            LEFT JOIN question_options qo ON sa.selected_option_id = qo.id
            WHERE q.question_type = 'matching_headings'
            ORDER BY sa.created_at DESC
            LIMIT 20
        ");
        
        return response()->json([
            'matching_headings_in_database' => count($results) > 0 ? 'YES' : 'NO',
            'total_records' => count($results),
            'records' => $results
        ]);
    }
}
