# Listening Question Types Update

## Overview
Updated the listening section to support only 4 question types as requested:
1. Fill in the Blanks (fill_blanks)
2. Single Choice (single_choice)
3. Multiple Choice (multiple_choice)
4. Dropdown Selection (dropdown_selection)

## Changes Made

### 1. Create View (`resources/views/admin/questions/create/listening.blade.php`)
- Updated question types array to include only the 4 required types
- Reordered to put "Fill in the Blanks" first as requested
- Kept all existing functionality for these 4 types

### 2. Edit View (`resources/views/admin/questions/edit/listening.blade.php`)
- Updated question types array to match create view
- Removed old panels for matching, form_completion, plan_map_diagram
- Added listening-specific blank and dropdown insertion buttons
- Integrated with `listening-question-types.js` for handling the 4 types
- Added keyboard shortcuts (Alt+B for blanks, Alt+D for dropdowns)

### 3. JavaScript Handler (`public/js/admin/listening-question-types.js`)
- Already supports all 4 required question types
- No changes needed as it handles:
  - Fill in the blanks with dynamic blank management
  - Single choice with radio buttons
  - Multiple choice with checkboxes
  - Dropdown selection with dynamic dropdown management

### 4. Partials (`resources/views/admin/questions/partials/listening-question-types.blade.php`)
- Already contains panels for all 4 question types
- No changes needed

## Removed Question Types
The following question types have been removed from the listening section:
- Matching questions
- Form completion
- Plan/Map/Diagram labeling
- Note completion
- Summary completion
- Sentence completion

## Testing Checklist
- [ ] Fill in the Blanks - Create new question
- [ ] Fill in the Blanks - Edit existing question
- [ ] Single Choice - Create new question
- [ ] Single Choice - Edit existing question
- [ ] Multiple Choice - Create new question
- [ ] Multiple Choice - Edit existing question
- [ ] Dropdown Selection - Create new question
- [ ] Dropdown Selection - Edit existing question
- [ ] Keyboard shortcuts work (Alt+B, Alt+D)
- [ ] Question data saves correctly
- [ ] Student can answer all 4 question types

## Notes
- The controller and student-side views remain unchanged as they already handle these question types
- Database structure remains the same
- No migration needed as we're only limiting the UI options
