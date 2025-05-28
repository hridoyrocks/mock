<?php

namespace App\Helpers;

class ScoreCalculator
{
    /**
     * Convert raw marks to IELTS band score.
     *
     * @param int $correctAnswers
     * @param int $totalQuestions
     * @return float
     */
    public static function calculateListeningBandScore(int $correctAnswers, int $totalQuestions): float
    {
        // Standard IELTS listening band conversion
        $percentage = ($correctAnswers / $totalQuestions) * 100;
        
        if ($percentage >= 90) return 9.0;
        if ($percentage >= 85) return 8.5;
        if ($percentage >= 80) return 8.0;
        if ($percentage >= 75) return 7.5;
        if ($percentage >= 70) return 7.0;
        if ($percentage >= 65) return 6.5;
        if ($percentage >= 60) return 6.0;
        if ($percentage >= 55) return 5.5;
        if ($percentage >= 50) return 5.0;
        if ($percentage >= 45) return 4.5;
        if ($percentage >= 40) return 4.0;
        if ($percentage >= 30) return 3.5;
        if ($percentage >= 20) return 3.0;
        if ($percentage >= 10) return 2.5;
        if ($percentage >= 5) return 2.0;
        
        return 1.0;
    }
    
    /**
     * Convert raw marks to IELTS band score for Reading.
     *
     * @param int $correctAnswers
     * @param int $totalQuestions
     * @param string $testType
     * @return float
     */
    public static function calculateReadingBandScore(int $correctAnswers, int $totalQuestions, string $testType = 'academic'): float
    {
        // Different scoring for academic and general training
        $percentage = ($correctAnswers / $totalQuestions) * 100;
        
        if ($testType === 'academic') {
            if ($percentage >= 89) return 9.0;
            if ($percentage >= 85) return 8.5;
            if ($percentage >= 80) return 8.0;
            if ($percentage >= 74) return 7.5;
            if ($percentage >= 67) return 7.0;
            if ($percentage >= 59) return 6.5;
            if ($percentage >= 52) return 6.0;
            if ($percentage >= 44) return 5.5;
            if ($percentage >= 38) return 5.0;
            if ($percentage >= 32) return 4.5;
            if ($percentage >= 26) return 4.0;
            if ($percentage >= 20) return 3.5;
            if ($percentage >= 15) return 3.0;
            if ($percentage >= 10) return 2.5;
            if ($percentage >= 5) return 2.0;
        } else {
            // General Training has a different band score calculation
            if ($percentage >= 89) return 9.0;
            if ($percentage >= 85) return 8.5;
            if ($percentage >= 80) return 8.0;
            if ($percentage >= 74) return 7.5;
            if ($percentage >= 67) return 7.0;
            if ($percentage >= 60) return 6.5;
            if ($percentage >= 52) return 6.0;
            if ($percentage >= 46) return 5.5;
            if ($percentage >= 40) return 5.0;
            if ($percentage >= 32) return 4.5;
            if ($percentage >= 27) return 4.0;
            if ($percentage >= 22) return 3.5;
            if ($percentage >= 16) return 3.0;
            if ($percentage >= 11) return 2.5;
            if ($percentage >= 6) return 2.0;
        }
        
        return 1.0;
    }

    /**
     * Calculate overall band score from individual section scores.
     *
     * @param float $listening
     * @param float $reading
     * @param float $writing
     * @param float $speaking
     * @return float
     */
    public static function calculateOverallBandScore(float $listening, float $reading, float $writing, float $speaking): float
    {
        // Calculate average
        $average = ($listening + $reading + $writing + $speaking) / 4;
        
        // Round to nearest 0.5 or whole number according to IELTS rules
        return round($average * 2) / 2;
    }
}