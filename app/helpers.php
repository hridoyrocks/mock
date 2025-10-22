<?php

/**
 * Helper Functions for IELTS Mock Platform
 */

if (!function_exists('formatBandScore')) {
    /**
     * Format band score to IELTS official format (0.5 increments)
     * Examples: 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0
     * 
     * @param float|null $score
     * @return float|null
     */
    function formatBandScore($score)
    {
        if ($score === null) {
            return null;
        }
        
        // Round to nearest 0.5
        return round($score * 2) / 2;
    }
}

if (!function_exists('displayBandScore')) {
    /**
     * Display band score in IELTS format with one decimal place
     * 
     * @param float|null $score
     * @param string $default
     * @return string
     */
    function displayBandScore($score, $default = 'N/A')
    {
        if ($score === null) {
            return $default;
        }
        
        return number_format(formatBandScore($score), 1);
    }
}
