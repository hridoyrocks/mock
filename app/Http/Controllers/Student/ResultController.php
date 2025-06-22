<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display a listing of the student's results.
     */
    public function index(): View
    {
        $attempts = StudentAttempt::where('user_id', auth()->id())
            ->with(['testSet', 'testSet.section'])
            ->latest()
            ->paginate(10);
        
        return view('student.results.index', compact('attempts'));
    }
    
    /**
     * Display the specified result.
     */
    public function show(StudentAttempt $attempt): View
    {
        // Ensure the attempt belongs to the authenticated user
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Load all necessary relationships separately to avoid issues
        $attempt->load('testSet', 'testSet.section');
        
        // Load passages separately
        $passages = $attempt->testSet->questions()
            ->where('question_type', 'passage')
            ->orderBy('part_number')
            ->orderBy('order_number')
            ->get();
        
        // Load answers with their relationships
        $attempt->load(['answers.question', 'answers.selectedOption']);
        
        // Calculate statistics for automatically scored sections
        if (in_array($attempt->testSet->section->name, ['listening', 'reading'])) {
            $correctAnswers = 0;
            $totalQuestions = 0;
            
            foreach ($attempt->answers as $answer) {
                // Skip passage type questions
                if ($answer->question->question_type === 'passage') {
                    continue;
                }
                
                // Load options for this question
                $answer->question->load('options');
                
                $totalQuestions++;
                
                // Check different answer types
                if ($answer->question->options->count() > 0) {
                    // Multiple choice type questions
                    if ($answer->selectedOption && $answer->selectedOption->is_correct) {
                        $correctAnswers++;
                    }
                } else {
                    // Text-based answers (fill in the blanks, short answer)
                    if ($this->checkTextAnswer($answer)) {
                        $correctAnswers++;
                    }
                }
            }
            
            // Calculate accuracy percentage
            $accuracy = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
            
            return view('student.results.show', compact(
                'attempt', 
                'correctAnswers', 
                'totalQuestions', 
                'accuracy',
                'passages'
            ));
        }
        
        // For manually evaluated sections (Writing and Speaking)
        return view('student.results.show', compact('attempt'));
    }
    
    /**
     * Check if a text-based answer is correct
     */
    private function checkTextAnswer($answer): bool
    {
        $question = $answer->question;
        $studentAnswer = $answer->answer;
        
        // Handle JSON answers (fill-in-the-blanks with multiple blanks)
        if ($this->isJson($studentAnswer)) {
            $studentAnswers = json_decode($studentAnswer, true);
            $sectionData = $question->section_specific_data;
            
            if (!$sectionData) {
                return false;
            }
            
            $allCorrect = true;
            
            // Check blank answers
            if (isset($sectionData['blank_answers'])) {
                foreach ($sectionData['blank_answers'] as $num => $correctAnswer) {
                    $studentBlankAnswer = $studentAnswers['blank_' . $num] ?? '';
                    if (!$this->compareAnswers($studentBlankAnswer, $correctAnswer)) {
                        $allCorrect = false;
                        break;
                    }
                }
            }
            
            // Check dropdown answers
            if (isset($sectionData['dropdown_correct'])) {
                foreach ($sectionData['dropdown_correct'] as $num => $correctIndex) {
                    $studentDropdownAnswer = $studentAnswers['dropdown_' . $num] ?? '';
                    $dropdownOptions = $sectionData['dropdown_options'][$num] ?? '';
                    
                    if ($dropdownOptions) {
                        $options = array_map('trim', explode(',', $dropdownOptions));
                        $correctOption = $options[$correctIndex] ?? '';
                        
                        if (!$this->compareAnswers($studentDropdownAnswer, $correctOption)) {
                            $allCorrect = false;
                            break;
                        }
                    }
                }
            }
            
            return $allCorrect;
        }
        
        // Single text answer - would need correct answer stored
        // For now, return false as we need the correct answer logic
        return false;
    }
    
    /**
     * Compare two answers (case-insensitive, trimmed)
     */
    private function compareAnswers($studentAnswer, $correctAnswer): bool
    {
        $studentAnswer = strtolower(trim($studentAnswer));
        $correctAnswer = strtolower(trim($correctAnswer));
        
        return $studentAnswer === $correctAnswer;
    }
    
    /**
     * Check if a string is valid JSON
     */
    private function isJson($string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    /**
     * Get detailed results data (for AJAX requests)
     */
    public function getDetails(StudentAttempt $attempt): \Illuminate\Http\JsonResponse
    {
        // Ensure the attempt belongs to the authenticated user
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $attempt->load([
            'answers.question.options',
            'answers.selectedOption',
            'answers.question.correctOption'
        ]);
        
        $details = [];
        
        foreach ($attempt->answers as $answer) {
            $isCorrect = false;
            $correctAnswer = '';
            
            if ($answer->question->options->count() > 0) {
                // Multiple choice
                $isCorrect = $answer->selectedOption && $answer->selectedOption->is_correct;
                $correctAnswer = $answer->question->correctOption()->content ?? '';
            } else {
                // Text answer
                $isCorrect = $this->checkTextAnswer($answer);
                $correctAnswer = 'See explanation';
            }
            
            $details[] = [
                'question_id' => $answer->question->id,
                'question_number' => $answer->question->order_number,
                'is_correct' => $isCorrect,
                'student_answer' => $answer->selectedOption->content ?? $answer->answer ?? 'Not answered',
                'correct_answer' => $correctAnswer,
                'explanation' => $answer->question->explanation,
                'passage_reference' => $answer->question->passage_reference,
                'tips' => $answer->question->tips,
                'difficulty' => $answer->question->difficulty_level,
            ];
        }
        
        return response()->json([
            'success' => true,
            'details' => $details,
            'summary' => [
                'total_questions' => count($details),
                'correct_answers' => collect($details)->where('is_correct', true)->count(),
                'band_score' => $attempt->band_score,
            ]
        ]);
    }
}