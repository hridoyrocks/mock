<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttempt;
use App\Models\StudentAnswer;
use App\Models\TestSet;
use App\Helpers\ScoreCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class ListeningTestController extends Controller
{
    public function index(): View
    {
        $testSets = TestSet::whereHas('section', function ($query) {
            $query->where('name', 'listening');
        })->where('active', true)->get();
        
        return view('student.test.listening.index', compact('testSets'));
    }
    
    public function confirmDetails(TestSet $testSet)
    {
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        // Check if user has already completed this test
        $existingAttempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'completed')
            ->first();
            
        if ($existingAttempt) {
            return redirect()->route('student.results.show', $existingAttempt)
                ->with('info', 'You have already completed this test.');
        }
        
        return view('student.test.listening.onboarding.confirm-details', compact('testSet'));
    }

    public function soundCheck(TestSet $testSet): View
    {
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        return view('student.test.listening.onboarding.sound-check', compact('testSet'));
    }

    public function instructions(TestSet $testSet): View
    {
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        return view('student.test.listening.onboarding.instructions', compact('testSet'));
    }
    
    public function start(TestSet $testSet)
    {
        if ($testSet->section->name !== 'listening') {
            abort(404);
        }
        
        // Check if user has already completed this test
        $existingAttempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'completed')
            ->first();
            
        if ($existingAttempt) {
            return redirect()->route('student.results.show', $existingAttempt)
                ->with('info', 'You have already completed this test.');
        }
        
        // Check if there's an ongoing attempt
        $attempt = StudentAttempt::where('user_id', auth()->id())
            ->where('test_set_id', $testSet->id)
            ->where('status', 'in_progress')
            ->first();
            
        if (!$attempt) {
            // Create a new attempt only if no ongoing attempt exists
            $attempt = StudentAttempt::create([
                'user_id' => auth()->id(),
                'test_set_id' => $testSet->id,
                'start_time' => now(),
                'status' => 'in_progress',
            ]);
        }
        
        return view('student.test.listening.test', compact('testSet', 'attempt'));
    }
    
    public function submit(Request $request, StudentAttempt $attempt): RedirectResponse
{
    // Verify the attempt belongs to the current user and is not already completed
    if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
        return redirect()->route('student.listening.index')
            ->with('error', 'Invalid attempt or test already submitted.');
    }
    
    $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'nullable',
    ]);
    
    DB::transaction(function () use ($request, $attempt) {
        // Get all questions including special types
        $questions = $attempt->testSet->questions()
            ->where('question_type', '!=', 'passage')
            ->get();
        
        // Calculate total question count including sub-questions
        $totalQuestions = 0;
        foreach ($questions as $question) {
            if ($question->question_type === 'matching' && $question->matching_pairs) {
                $totalQuestions += count($question->matching_pairs);
            } elseif ($question->question_type === 'form_completion' && $question->form_structure) {
                $totalQuestions += count($question->form_structure['fields'] ?? []);
            } elseif ($question->question_type === 'plan_map_diagram' && $question->diagram_hotspots) {
                $totalQuestions += count($question->diagram_hotspots);
            } else {
                $totalQuestions++;
            }
        }
        
        // Save answers and calculate score
        $correctAnswers = 0;
        $answeredCount = 0;
        
        foreach ($request->answers as $answerKey => $answer) {
            if ($answer) {
                // Parse answer key for special types
                if (strpos($answerKey, '_') !== false) {
                    // Special type answer: questionId_subIndex
                    list($questionId, $subIndex) = explode('_', $answerKey);
                    $question = $questions->find($questionId);
                    
                    if ($question) {
                        // Check if answer is correct based on type
                        $isCorrect = false;
                        
                        switch ($question->question_type) {
                            case 'matching':
                                if (isset($question->matching_pairs[$subIndex])) {
                                    $correctAnswer = $question->matching_pairs[$subIndex]['right'];
                                    $isCorrect = (trim(strtolower($answer)) === trim(strtolower($correctAnswer)));
                                }
                                break;
                                
                            case 'form_completion':
                                if (isset($question->form_structure['fields'][$subIndex])) {
                                    $correctAnswer = $question->form_structure['fields'][$subIndex]['answer'];
                                    $isCorrect = (trim(strtolower($answer)) === trim(strtolower($correctAnswer)));
                                }
                                break;
                                
                            case 'plan_map_diagram':
                                if (isset($question->diagram_hotspots[$subIndex])) {
                                    $correctAnswer = $question->diagram_hotspots[$subIndex]['answer'];
                                    $isCorrect = (trim(strtolower($answer)) === trim(strtolower($correctAnswer)));
                                }
                                break;
                        }
                        
                        // Save answer
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'answer' => json_encode([
                                    'sub_index' => $subIndex,
                                    'answer' => $answer,
                                    'is_correct' => $isCorrect
                                ]),
                            ]
                        );
                        
                        if ($isCorrect) {
                            $correctAnswers++;
                        }
                        $answeredCount++;
                    }
                } else {
                    // Regular question answer
                    $questionId = $answerKey;
                    
                    StudentAnswer::updateOrCreate(
                        [
                            'attempt_id' => $attempt->id,
                            'question_id' => $questionId,
                        ],
                        [
                            'selected_option_id' => is_numeric($answer) ? $answer : null,
                            'answer' => !is_numeric($answer) ? $answer : null,
                        ]
                    );
                    
                    $answeredCount++;
                    
                    // Check if correct
                    $question = $questions->find($questionId);
                    if ($question) {
                        if ($question->requiresOptions() && is_numeric($answer)) {
                            $option = QuestionOption::find($answer);
                            if ($option && $option->is_correct) {
                                $correctAnswers++;
                            }
                        } elseif (!$question->requiresOptions() && $question->section_specific_data) {
                            // Check text answers
                            // Implementation depends on your scoring logic
                        }
                    }
                }
            }
        }
        
        // Mark attempt as completed
        $attempt->update([
            'end_time' => now(),
            'status' => 'completed',
        ]);
        
        // INCREMENT TEST COUNT
        auth()->user()->incrementTestCount();
        
        // Use the new partial test score calculation
        $scoreData = \App\Helpers\ScoreCalculator::calculatePartialTestScore(
            $correctAnswers, 
            $answeredCount, 
            $totalQuestions,
            'listening'
        );
        
        // Store band score and additional data
        $attempt->update([
            'band_score' => $scoreData['band_score'],
            'completion_rate' => $scoreData['completion_percentage'],
            'confidence_level' => $scoreData['confidence'],
            'is_complete_attempt' => $scoreData['is_reliable'],
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredCount,
            'correct_answers' => $correctAnswers
        ]);
        
        // Store score data in session for display
        session()->flash('score_details', $scoreData);
    });
    
    return redirect()->route('student.results.show', $attempt)
        ->with('success', 'Test submitted successfully!');
}
}