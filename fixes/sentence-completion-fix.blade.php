{{-- Fix for sentence completion display number --}}
{{-- Replace this line around line 728 in test.blade.php --}}

{{-- OLD CODE: --}}
$displayNum = $sentence['questionNumber'];

{{-- NEW CODE: --}}
// Get the display number from the item's question_numbers array
$displayNum = isset($item['question_numbers'][$sentenceIndex]) ? $item['question_numbers'][$sentenceIndex] : ($item['display_number'] + $sentenceIndex);

{{-- EXPLANATION: --}}
{{-- The issue is that $sentence['questionNumber'] contains the stored question number from admin panel, 
     but we need to use the actual display number from the $item['question_numbers'] array which 
     contains the correctly calculated sequential numbers for the test. --}}
