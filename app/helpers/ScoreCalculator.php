<?php

namespace App\Helpers;

class ScoreCalculator
{
    /**
     * Calculate IELTS Listening band score based on correct answers
     * Official IELTS conversion for 40 questions
     *
     * @param int $correctAnswers Number of correct answers
     * @param int $totalQuestions Total questions in test
     * @return float Band score
     */
    public static function calculateListeningBandScore(int $correctAnswers, int $totalQuestions): float
    {
        // If test has different number of questions, scale to 40
        if ($totalQuestions != 40 && $totalQuestions > 0) {
            $scaledScore = round(($correctAnswers / $totalQuestions) * 40);
            $correctAnswers = $scaledScore;
        }
        
        // Official IELTS Listening band score conversion
        return match(true) {
            $correctAnswers >= 39 => 9.0,
            $correctAnswers >= 37 => 8.5,
            $correctAnswers >= 35 => 8.0,
            $correctAnswers >= 32 => 7.5,
            $correctAnswers >= 30 => 7.0,
            $correctAnswers >= 26 => 6.5,
            $correctAnswers >= 23 => 6.0,
            $correctAnswers >= 18 => 5.5,
            $correctAnswers >= 16 => 5.0,
            $correctAnswers >= 13 => 4.5,
            $correctAnswers >= 11 => 4.0,
            $correctAnswers >= 9  => 3.5,
            $correctAnswers >= 6  => 3.0,
            $correctAnswers >= 4  => 2.5,
            $correctAnswers == 3  => 2.0,
            $correctAnswers == 2  => 1.5,
            $correctAnswers == 1  => 1.0,
            default => 0.0
        };
    }
    
    /**
     * Calculate IELTS Reading band score based on correct answers
     * Different scoring for Academic vs General Training
     *
     * @param int $correctAnswers Number of correct answers
     * @param int $totalQuestions Total questions in test
     * @param string $testType 'academic' or 'general'
     * @return float Band score
     */
    public static function calculateReadingBandScore(int $correctAnswers, int $totalQuestions, string $testType = 'academic'): float
    {
        // If test has different number of questions, scale to 40
        if ($totalQuestions != 40 && $totalQuestions > 0) {
            $scaledScore = round(($correctAnswers / $totalQuestions) * 40);
            $correctAnswers = $scaledScore;
        }
        
        if ($testType === 'academic') {
            // Academic Reading band score conversion
            return match(true) {
                $correctAnswers >= 39 => 9.0,
                $correctAnswers >= 37 => 8.5,
                $correctAnswers >= 35 => 8.0,
                $correctAnswers >= 33 => 7.5,
                $correctAnswers >= 30 => 7.0,
                $correctAnswers >= 27 => 6.5,
                $correctAnswers >= 23 => 6.0,
                $correctAnswers >= 19 => 5.5,
                $correctAnswers >= 15 => 5.0,
                $correctAnswers >= 13 => 4.5,
                $correctAnswers >= 10 => 4.0,
                $correctAnswers >= 8  => 3.5,
                $correctAnswers >= 6  => 3.0,
                $correctAnswers >= 4  => 2.5,
                $correctAnswers == 3  => 2.0,
                $correctAnswers == 2  => 1.5,
                $correctAnswers == 1  => 1.0,
                default => 0.0
            };
        } else {
            // General Training Reading band score conversion
            return match(true) {
                $correctAnswers >= 40 => 9.0,
                $correctAnswers >= 39 => 8.5,
                $correctAnswers >= 37 => 8.0,
                $correctAnswers >= 36 => 7.5,
                $correctAnswers >= 34 => 7.0,
                $correctAnswers >= 32 => 6.5,
                $correctAnswers >= 30 => 6.0,
                $correctAnswers >= 27 => 5.5,
                $correctAnswers >= 23 => 5.0,
                $correctAnswers >= 19 => 4.5,
                $correctAnswers >= 15 => 4.0,
                $correctAnswers >= 12 => 3.5,
                $correctAnswers >= 9  => 3.0,
                $correctAnswers >= 6  => 2.5,
                $correctAnswers >= 4  => 2.0,
                $correctAnswers == 2  => 1.5,
                $correctAnswers == 1  => 1.0,
                default => 0.0
            };
        }
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
        // Don't give score if less than 25% attempted
        if ($answeredQuestions < $totalQuestions * 0.25) {
            return [
                'band_score' => null,
                'message' => 'Insufficient questions attempted for scoring',
                'min_required' => ceil($totalQuestions * 0.25),
                'answered' => $answeredQuestions,
                'total' => $totalQuestions,
                'completion_percentage' => round(($answeredQuestions / $totalQuestions) * 100, 1)
            ];
        }
        
        // Calculate accuracy on attempted questions
        $accuracy = $answeredQuestions > 0 ? ($correctAnswers / $answeredQuestions) : 0;
        
        // Project score for all questions based on current accuracy
        $projectedCorrect = round($accuracy * $totalQuestions);
        
        // Get appropriate band score
        if ($section === 'listening') {
            $bandScore = self::calculateListeningBandScore($projectedCorrect, $totalQuestions);
        } else {
            $bandScore = self::calculateReadingBandScore($projectedCorrect, $totalQuestions);
        }
        
        // Calculate confidence level based on completion
        $completionRate = ($answeredQuestions / $totalQuestions) * 100;
        $confidence = match(true) {
            $completionRate >= 90 => 'Very High',
            $completionRate >= 75 => 'High',
            $completionRate >= 50 => 'Medium',
            $completionRate >= 25 => 'Low',
            default => 'Very Low'
        };
        
        // Determine if this is a reliable score
        $isReliable = $completionRate >= 80;
        
        return [
            'band_score' => $bandScore,
            'confidence' => $confidence,
            'is_reliable' => $isReliable,
            'answered' => $answeredQuestions,
            'total' => $totalQuestions,
            'correct' => $correctAnswers,
            'projected_correct' => $projectedCorrect,
            'accuracy_percentage' => round($accuracy * 100, 1),
            'completion_percentage' => round($completionRate, 1),
            'message' => self::getScoreMessage($completionRate, $bandScore)
        ];
    }
    
    /**
     * Get appropriate message based on completion rate
     */
    private static function getScoreMessage($completionRate, $bandScore): string
    {
        if ($completionRate >= 90) {
            return "Excellent! Your band score of {$bandScore} is highly reliable.";
        } elseif ($completionRate >= 75) {
            return "Good effort! Your projected band score is {$bandScore}. Complete all questions for the most accurate score.";
        } elseif ($completionRate >= 50) {
            return "Your projected band score is {$bandScore} based on {$completionRate}% completion. Try to answer more questions next time.";
        } else {
            return "Limited data. Your projected band score is {$bandScore}, but you need to answer more questions for an accurate assessment.";
        }
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