# Listening Test Instructions Update - Minimal Style

## Changes Made

### 1. Instruction Styling Updated
**Before**: Instructions had background box with border
```css
background: #f5f5f5;
padding: 12px 20px;
border-left: 4px solid #666;
```

**After**: Minimal style matching reading test
```css
background: none;
padding: 0;
border: none;
font-weight: 600;
color: #1f2937;
```

### 2. Files Updated
- `resources/views/student/test/listening/test.blade.php`
- `resources/views/student/test/listening/question-render.blade.php`

## Result
Instructions now display with:
- No background color
- No border or padding
- Bold text (font-weight: 600)
- Dark gray color (#1f2937)
- 16px margin below
- Same style as reading test

## Visual Comparison
**Before**: 
- Instructions appeared in gray boxes with left border
- More visual weight and separation

**After**:
- Clean, minimal text-only instructions
- Matches reading test style exactly
- Less visual clutter on the page
