<?php

namespace App\Helpers;

class ScoreCalculator
{
    /**
     * Calculate IELTS Listening band score based on correct answers
     * Updated IELTS conversion for 40 questions
     *
     * @param int $correctAnswers Number of correct answers
     * @param int $totalQuestions Total questions in test
     * @return float Band score
     */
    public static function calculateListeningBandScore(int $correctAnswers, int $totalQuestions): float
    {
        // Scale to 40 questions (IELTS standard)
        if ($totalQuestions > 0 && $totalQuestions != 40) {
            // Calculate percentage and then scale to 40
            $percentage = ($correctAnswers / $totalQuestions);
            $scaledScore = round($percentage * 40);
            $correctAnswers = $scaledScore;
        }
        
        // Updated IELTS Listening band score conversion table
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
            default => 0.0
        };
    }
    
    /**
     * Calculate IELTS Reading band score based on correct answers
     * Using unified scoring table for both Academic and General Training
     *
     * @param int $correctAnswers Number of correct answers
     * @param int $totalQuestions Total questions in test
     * @param string $testType 'academic' or 'general'
     * @return float Band score
     */
    public static function calculateReadingBandScore(int $correctAnswers, int $totalQuestions, string $testType = 'academic'): float
    {
        // Scale to 40 questions (IELTS standard)
        if ($totalQuestions > 0 && $totalQuestions != 40) {
            // Calculate percentage and then scale to 40
            $percentage = ($correctAnswers / $totalQuestions);
            $scaledScore = round($percentage * 40);
            $correctAnswers = $scaledScore;
        }
        
        // Unified IELTS Reading band score conversion table
        // Same scoring for both Academic and General Training
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
            default => 0.0
        };
    }
    
    /**
     * Calculate band score for partial/incomplete tests
     * Returns projected band score with confidence level
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
                'min_required' => 0,
                'answered' => $answeredQuestions,
                'total' => $totalQuestions,
                'completion_percentage' => 0
            ];
        }
        
        // Calculate band score based on actual correct answers out of total questions
        // NO PROJECTION - just use the actual correct answers
        if ($section === 'listening') {
            $bandScore = self::calculateListeningBandScore($correctAnswers, $totalQuestions);
        } else {
            $bandScore = self::calculateReadingBandScore($correctAnswers, $totalQuestions);
        }
        
        // Calculate accuracy on attempted questions (for display purposes only)
        $accuracy = $answeredQuestions > 0 ? ($correctAnswers / $answeredQuestions) : 0;
        
        // Calculate completion percentage
        $completionRate = ($answeredQuestions / $totalQuestions) * 100;
        
        return [
            'band_score' => $bandScore,
            'confidence' => 'Actual Score',  // Not projected anymore
            'is_reliable' => true,  // Always reliable as it's actual score
            'answered' => $answeredQuestions,
            'total' => $totalQuestions,
            'correct' => $correctAnswers,
            'projected_correct' => $correctAnswers,  // Same as correct now
            'accuracy_percentage' => round($accuracy * 100, 1),
            'completion_percentage' => round($completionRate, 1),
            'message' => "Band Score: {$bandScore} (Correct: {$correctAnswers}/{$totalQuestions})"
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