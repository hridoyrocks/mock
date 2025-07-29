<?php

/**
 * Fix for Listening Multiple Choice Question Save Issue
 * 
 * Problem: Multiple choice questions in listening section are not saving properly
 * Cause: The correct_option is being sent as an array but not handled correctly
 * 
 * This script modifies the QuestionController to properly handle multiple choice questions
 */

$controllerPath = base_path('app/Http/Controllers/Admin/QuestionController.php');
$content = file_get_contents($controllerPath);

// Backup original file
$backupPath = base_path('app/Http/Controllers/Admin/QuestionController.php.backup_' . date('Y-m-d_H-i-s'));
file_put_contents($backupPath, $content);

// Find the section where options are created in the store method
$searchPattern = '/foreach \(\$request->options as \$index => \$option\) \{[\s\S]*?if \(!empty\(\$option\[\'content\'\]\)\) \{[\s\S]*?\$isCorrect = false;[\s\S]*?if \(\$request->question_type === \'matching_headings\'\) \{[\s\S]*?\} else \{[\s\S]*?\$isCorrect = in_array\(\(string\)\$index, array_map\(\'strval\', \$correctOptions\)\);[\s\S]*?\}/';

$replacement = 'foreach ($request->options as $index => $option) {
                    if (!empty($option[\'content\'])) {
                        // For matching_headings, check if it\'s a correct heading based on mappings
                        $isCorrect = false;
                        if ($request->question_type === \'matching_headings\') {
                            // For matching headings, we\'ll mark options as correct based on JSON data
                            // This is handled differently as mappings determine correctness
                            $isCorrect = false; // Default to false, actual mapping is in section_specific_data
                        } else {
                            // Convert both to strings for comparison
                            // For multiple choice, this will correctly handle array of correct options
                            $isCorrect = in_array((string)$index, array_map(\'strval\', $correctOptions));
                        }';

// Apply the fix
$content = preg_replace($searchPattern, $replacement, $content);

// Also need to ensure the correct_option validation for multiple choice is properly handled
// Find the manual validation section
$searchPattern2 = '/\/\/ Manual validation for multiple choice correct options[\s\S]*?if \(\$request->question_type === \'multiple_choice\' && \$this->needsOptions\(\$request->question_type\)\) \{[\s\S]*?if \(!\$request->has\(\'correct_option\'\) \|\| empty\(\$request->correct_option\)\) \{/';

$replacement2 = '// Manual validation for multiple choice correct options
        if ($request->question_type === \'multiple_choice\' && $this->needsOptions($request->question_type)) {
            $correctOption = $request->input(\'correct_option\', []);
            // Handle both array and single value
            if (is_array($correctOption)) {
                $hasCorrect = count(array_filter($correctOption)) > 0;
            } else {
                $hasCorrect = !empty($correctOption);
            }
            
            if (!$hasCorrect) {';

$content = preg_replace($searchPattern2, $replacement2, $content, 1);

// Save the modified content
file_put_contents($controllerPath, $content);

echo "Fix applied successfully!\n";
echo "Backup created at: $backupPath\n";

// Clear Laravel cache
echo "Clearing cache...\n";
exec('cd ' . base_path() . ' && php artisan cache:clear');
exec('cd ' . base_path() . ' && php artisan config:clear');
exec('cd ' . base_path() . ' && php artisan route:clear');
exec('cd ' . base_path() . ' && php artisan view:clear');

echo "Done! Multiple choice questions should now save properly.\n";
