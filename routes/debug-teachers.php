<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-teachers/{attempt}', function($attemptId) {
    try {
        $attempt = \App\Models\StudentAttempt::findOrFail($attemptId);
        
        // Check ownership
        if ($attempt->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized']);
        }
        
        // Check section
        $section = $attempt->testSet->section->name ?? 'unknown';
        
        // Get teachers
        $teachers = \App\Models\Teacher::with('user')
            ->where('is_available', true)
            ->get();
            
        $filteredTeachers = $teachers->filter(function($teacher) use ($section) {
            $specializations = $teacher->specialization ?? [];
            return in_array($section, $specializations);
        });
        
        return response()->json([
            'attempt_id' => $attemptId,
            'section' => $section,
            'total_teachers' => $teachers->count(),
            'filtered_teachers' => $filteredTeachers->count(),
            'teachers' => $filteredTeachers->map(function($teacher) use ($section) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->user->name ?? 'Unknown',
                    'specialization' => $teacher->specialization,
                    'is_available' => $teacher->is_available,
                    'base_price' => $teacher->evaluation_price_tokens,
                    'calculated_price' => $teacher->calculateTokenPrice($section),
                    'urgent_price' => $teacher->calculateTokenPrice($section, true)
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->middleware('auth');
