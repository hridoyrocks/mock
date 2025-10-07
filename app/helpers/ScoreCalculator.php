<?php

namespace App\Helpers;

class ScoreCalculator
{
    /**
     * Calculate IELTS Listening band score based on correct answers
     * Updated IELTS conversion for 40 questions
     *
     * @param int $correctAnswers Number of correct answers
     * @param int $totalQuestions Total questions in test (always 40 for IELTS)
     * @return float Band score
     */
    public static function calculateListeningBandScore(int $correctAnswers, int $totalQuestions): float
    {
        // Direct band score calculation - NO SCALING
        // Use actual correct answers out of 40
        return match(true) {
            $correctAnswers >= 40 => 9.0,    // 40 = 9.0
            $correctAnswers >= 38 => 8.5,    // 38-39 = 8.5
            $correctAnswers >= 36 => 8.0,    // 36-37 = 8.0
            $correctAnswers >= 34 => 7.5,    // 34-35 = 7.5
            $correctAnswers >= 30 => 7.0,    // 32-30 = 7.0
            $correctAnswers >= 27 => 6.5,    // 29-27 = 6.5
            $correctAnswers >= 23 => 6.0,    // 26-23 = 6.0
            $correctAnswers >= 19 => 5.5,    // 22-19 = 5.5
            $correctAnswers >= 15 => 5.0,    // 18-15 = 5.0
            $correctAnswers >= 13 => 4.5,    // 14-13 = 4.5
            $correctAnswers >= 10 => 4.0,    // 12-10 = 4.0
            $correctAnswers >= 8  => 3.5,    // 9-8 = 3.5
            $correctAnswers >= 6  => 3.0,    // 7-6 = 3.0
            $correctAnswers >= 4  => 2.5,    // 5-4 = 2.5
            $correctAnswers >= 3  => 2.0,    // 3 = 2.0
            $correctAnswers >= 2  => 1.5,    // 2 = 1.5
            $correctAnswers >= 1  => 1.0,    // 1 = 1.0
            default => 0.0                   // 0 = 0.0
        };
    }
    
    /**
     * Calculate IELTS Reading band score based on correct answers
     * Using unified scoring table for both Academic and General Training
     *
     * @param int $correctAnswers Number of correct answers
     * @param int $totalQuestions Total questions in test (always 40 for IELTS)
     * @param string $testType 'academic' or 'general'
     * @return float Band score
     */
    public static function calculateReadingBandScore(int $correctAnswers, int $totalQuestions, string $testType = 'academic'): float
    {
        // Direct band score calculation - NO SCALING
        // Use actual correct answers out of 40
        return match(true) {
            $correctAnswers >= 40 => 9.0,    // 40 = 9.0
            $correctAnswers >= 38 => 8.5,    // 38-39 = 8.5
            $correctAnswers >= 36 => 8.0,    // 36-37 = 8.0
            $correctAnswers >= 34 => 7.5,    // 34-35 = 7.5
            $correctAnswers >= 30 => 7.0,    // 32-30 = 7.0
            $correctAnswers >= 27 => 6.5,    // 29-27 = 6.5
            $correctAnswers >= 23 => 6.0,    // 26-23 = 6.0
            $correctAnswers >= 19 => 5.5,    // 22-19 = 5.5
            $correctAnswers >= 15 => 5.0,    // 18-15 = 5.0
            $correctAnswers >= 13 => 4.5,    // 14-13 = 4.5
            $correctAnswers >= 10 => 4.0,    // 12-10 = 4.0
            $correctAnswers >= 8  => 3.5,    // 9-8 = 3.5
            $correctAnswers >= 6  => 3.0,    // 7-6 = 3.0
            $correctAnswers >= 4  => 2.5,    // 5-4 = 2.5
            $correctAnswers >= 3  => 2.0,    // 3 = 2.0
            $correctAnswers >= 2  => 1.5,    // 2 = 1.5
            $correctAnswers >= 1  => 1.0,    // 1 = 1.0
            default => 0.0                   // 0 = 0.0
        };
    }
    
    /**
     * Calculate band score for partial/incomplete tests
     * Returns actual band score based on correct answers, not projected
     *
     * @param int $correctAnswers Number of correct answers
     * @param int $answeredQuestions Number of questions attempted
     * @param int $totalQuestions Total questions in test
     * @param string $section 'listening' or 'reading'
     * @return array Contains band_score, confidence, and other details
     */
    public static function calculatePartialTestScore(int $correctAnswers, int $answeredQuestions, int $totalQuestions, string $section = 'listening'): array
    {
        // Don't give score if no questions attempted
        if ($answeredQuestions == 0) {
            return [
                'band_score' => null,
                'message' => 'No questions attempted',
                'answered' => $answeredQuestions,
                'total' => $totalQuestions,
                'completion_percentage' => 0
            ];
        }
        
        // Calculate band score based on ACTUAL correct answers, not projected
        // Even if only 2 out of 40 answered, score based on those 2
        if ($section === 'listening') {
            $bandScore = self::calculateListeningBandScore($correctAnswers, $totalQuestions);
        } else {
            $bandScore = self::calculateReadingBandScore($correctAnswers, $totalQuestions);
        }
        
        // Calculate accuracy on attempted questions
        $accuracy = $answeredQuestions > 0 ? ($correctAnswers / $answeredQuestions) : 0;
        
        // Calculate completion rate
        $completionRate = ($answeredQuestions / $totalQuestions) * 100;
        
        // Determine confidence level based on how many questions were attempted
        $confidence = match(true) {
            $completionRate >= 90 => 'Very High',
            $completionRate >= 75 => 'High',
            $completionRate >= 50 => 'Medium',
            $completionRate >= 25 => 'Low',
            $completionRate < 25 => 'Very Low (Partial Test)'
        };
        
        // Determine if this is a reliable score (only if 80%+ completed)
        $isReliable = $completionRate >= 80;
        
        // Create appropriate message based on completion
        $message = match(true) {
            $completionRate == 100 => "Complete test with band score {$bandScore}",
            $completionRate >= 90 => "Band score {$bandScore} based on {$answeredQuestions}/{$totalQuestions} questions (Highly reliable)",
            $completionRate >= 75 => "Band score {$bandScore} based on {$answeredQuestions}/{$totalQuestions} questions (Reliable)",
            $completionRate >= 50 => "Band score {$bandScore} based on {$answeredQuestions}/{$totalQuestions} questions (Moderate reliability)",
            $completionRate >= 25 => "Band score {$bandScore} based on {$answeredQuestions}/{$totalQuestions} questions (Low reliability)",
            default => "Band score {$bandScore} based on only {$answeredQuestions}/{$totalQuestions} questions attempted"
        };
        
        return [
            'band_score' => $bandScore,
            'confidence' => $confidence,
            'is_reliable' => $isReliable,
            'answered' => $answeredQuestions,
            'total' => $totalQuestions,
            'correct' => $correctAnswers,
            'accuracy_percentage' => round($accuracy * 100, 1),
            'completion_percentage' => round($completionRate, 1),
            'message' => $message,
            'note' => $completionRate < 50 ? 'Note: This score is based on limited data. Complete more questions for accurate assessment.' : null
        ];
    }


    /**
     * Calculate overall band score from individual section scores
     *
     * @param float $listening
     * @param float $reading
     * @param float $writing
     * @param float $speaking
     * @return float Overall band score
     */
    public static function calculateOverallBandScore(float $listening, float $reading, float $writing, float $speaking): float
    {
        // Calculate average
        $average = ($listening + $reading + $writing + $speaking) / 4;
        
        // Round to nearest 0.5 according to IELTS rules
        // 0.25 rounds down, 0.75 rounds up
        $decimal = $average - floor($average);
        
        if ($decimal < 0.25) {
            return floor($average);
        } elseif ($decimal < 0.75) {
            return floor($average) + 0.5;
        } else {
            return ceil($average);
        }
    }
    
    /**
     * Get band score description
     */
    public static function getBandDescription($bandScore): string
    {
        return match(true) {
            $bandScore >= 9.0 => 'Expert User',
            $bandScore >= 8.0 => 'Very Good User',
            $bandScore >= 7.0 => 'Good User',
            $bandScore >= 6.0 => 'Competent User',
            $bandScore >= 5.0 => 'Modest User',
            $bandScore >= 4.0 => 'Limited User',
            $bandScore >= 3.0 => 'Extremely Limited User',
            $bandScore >= 2.0 => 'Intermittent User',
            $bandScore >= 1.0 => 'Non User',
            default => 'Did not attempt'
        };
    }
    
    /**
     * Get color class for band score display
     */
    public static function getBandColorClass($bandScore): string
    {
        return match(true) {
            $bandScore >= 7.0 => 'text-green-600 bg-green-100',
            $bandScore >= 6.0 => 'text-blue-600 bg-blue-100',
            $bandScore >= 5.0 => 'text-yellow-600 bg-yellow-100',
            $bandScore >= 4.0 => 'text-orange-600 bg-orange-100',
            default => 'text-red-600 bg-red-100'
        };
    }
}