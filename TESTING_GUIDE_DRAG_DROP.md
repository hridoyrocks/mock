# 🧪 Drag & Drop - Quick Testing Guide

## Step-by-Step Testing

### 1️⃣ Create a Test Question

1. **Login as Admin**
   ```
   URL: /admin/dashboard
   ```

2. **Go to Questions**
   ```
   Admin → Test Sets → Select a Listening Test → Add Question
   ```

3. **Select Drag & Drop Type**
   ```
   Question Type: Drag & Drop
   ```

4. **Configure Drop Zones**
   ```
   Zone 1:
   Label: "The capital of France is"
   Answer: "Paris"
   
   Zone 2:
   Label: "The largest ocean is the"
   Answer: "Pacific Ocean"
   
   Zone 3:
   Label: "The tallest mountain is"
   Answer: "Mount Everest"
   ```

5. **Add Draggable Options**
   ```
   A. Paris
   B. London
   C. Berlin
   D. Pacific Ocean
   E. Atlantic Ocean
   F. Mount Everest
   G. K2
   H. Indian Ocean
   ```

6. **Set Reusability**
   ```
   ☐ Allow options to be reused (unchecked = each option can only be used once)
   ```

7. **Add Question Content**
   ```
   Content: "Listen to the recording and match the items with the correct answers."
   Instructions: "Drag the correct answer to each box."
   Part: 1 (or any part)
   Order Number: (auto-filled)
   ```

8. **Upload Audio** (if needed)

9. **Click "Save Question"**

---

### 2️⃣ Test as Student

1. **Login as Student**
   ```
   URL: /student/dashboard
   ```

2. **Go to Listening Tests**
   ```
   Student → Listening → Select the test set
   ```

3. **Start Test**
   ```
   Click "Start Test" → Complete onboarding
   ```

4. **Test Drag & Drop**
   
   **Action 1: Drag an option**
   - Drag "Paris" from options box
   - Drop it on first zone ("The capital of France is")
   - ✅ Should show: "A. Paris" in green box
   - ✅ Option "Paris" should become disabled/faded

   **Action 2: Try to remove**
   - Hover over the answered zone
   - Click the "×" button
   - ✅ Answer should be removed
   - ✅ Option "Paris" should become active again

   **Action 3: Fill all zones**
   - Drag correct answers to all zones
   - ✅ Each zone should show green border when filled
   - ✅ Navigation numbers should turn green

   **Action 4: Try wrong then correct**
   - Drag "London" to first zone
   - Then drag "Paris" to same zone
   - ✅ "London" should be replaced with "Paris"
   - ✅ "London" option should become active again

5. **Submit Test**
   ```
   Click "Submit Test"
   ```

---

### 3️⃣ Check Results

1. **Results Page Should Show**
   ```
   ✅ Question 5: Correct (Paris)
   ✅ Question 6: Correct (Pacific Ocean)
   ❌ Question 7: Incorrect (Your answer: K2, Correct: Mount Everest)
   
   Total: 2/3 correct
   Band Score: [calculated]
   ```

---

## 🔍 What to Check

### Admin Panel Checks
- [ ] Panel appears when "Drag & Drop" selected
- [ ] Can add drop zones (should create 3 by default)
- [ ] Can remove drop zones
- [ ] Can add options (should create 5 by default)
- [ ] Can remove options
- [ ] Re-indexing works (A, B, C... after removal)
- [ ] Form submits without errors
- [ ] Data saves to database

### Student Interface Checks
- [ ] Drop zones render correctly with labels
- [ ] Options box appears on right side (desktop) or bottom (mobile)
- [ ] Drag starts with visual feedback (opacity 0.5)
- [ ] Drop zone highlights on hover (blue background)
- [ ] Answer appears in zone after drop
- [ ] Remove button (×) appears on hover
- [ ] Options get disabled when used (if no reuse)
- [ ] Navigation button turns green when answered
- [ ] LocalStorage saves answers
- [ ] Answers restore on page refresh

### Scoring Checks
- [ ] Each zone counted as 1 question
- [ ] Correct answers marked as correct
- [ ] Wrong answers marked as incorrect
- [ ] Total questions calculated correctly
- [ ] Band score calculated properly
- [ ] Results page shows details

---

## 🐛 Common Issues & Fixes

### Issue 1: Drag doesn't work
```javascript
// Check console for error
// Verify: listening-drag-drop.js loaded
console.log(window.ListeningDragDrop); // Should not be undefined
```

**Fix:** Clear cache and reload

---

### Issue 2: Options don't disable
```javascript
// Check data attribute
const box = document.querySelector('.drop-box');
console.log(box.dataset.allowReuse); // Should be "0" or "1"
```

**Fix:** Verify checkbox state in admin panel

---

### Issue 3: Answers don't save
```javascript
// Check hidden inputs
const inputs = document.querySelectorAll('input[name^="answers"]');
inputs.forEach(i => console.log(i.name, i.value));
```

**Fix:** Verify input name format: `answers[question_id][zone_0]`

---

### Issue 4: Wrong score
```php
// Check controller log
Log::info('Drop zones count', ['count' => count($dropZones)]);
```

**Fix:** Verify drop zones in section_specific_data

---

## 📱 Mobile Testing

### Responsive Design Checks
- [ ] Options box moves to bottom on mobile
- [ ] Drop zones stack vertically
- [ ] Labels wrap properly
- [ ] Touch drag works
- [ ] Remove button accessible
- [ ] No horizontal scroll

---

## ⚡ Performance Testing

### Load Time
- [ ] Page loads < 2 seconds
- [ ] JavaScript initializes quickly
- [ ] No console errors

### Interaction Speed
- [ ] Drag starts immediately
- [ ] Drop registers instantly
- [ ] Remove works without delay
- [ ] Form submission fast

---

## 🎯 Edge Cases to Test

1. **Empty answer submission**
   - Leave some zones empty
   - Submit test
   - ✅ Should count as unanswered

2. **All correct answers**
   - Fill all zones correctly
   - ✅ Should get perfect score

3. **All wrong answers**
   - Fill all zones incorrectly
   - ✅ Should get 0 score

4. **Mixed answers**
   - Some correct, some wrong, some empty
   - ✅ Should calculate correctly

5. **Reuse enabled**
   - Check "Allow reuse" in admin
   - Same option in multiple zones
   - ✅ Should work

6. **Special characters**
   - Use accents: "Café", "São Paulo"
   - ✅ Should match correctly

7. **Case sensitivity**
   - Answer: "Paris", Student: "paris"
   - ✅ Should match (case-insensitive)

---

## ✅ Final Checklist

### Before Production
- [ ] All files uploaded
- [ ] JavaScript cache cleared
- [ ] Database migration run (if any)
- [ ] Permissions checked
- [ ] Logs reviewed
- [ ] Mobile tested
- [ ] Different browsers tested
- [ ] Multiple question types tested
- [ ] Score calculation verified
- [ ] Results display verified

### Documentation
- [ ] DRAG_DROP_IMPLEMENTATION.md present
- [ ] Code comments added
- [ ] Testing guide created
- [ ] Known issues documented

---

## 📞 Support

যদি কোনো সমস্যা হয়:

1. **Check browser console** for JavaScript errors
2. **Check Laravel log** for backend errors
3. **Verify database** section_specific_data structure
4. **Test with different browsers**
5. **Clear all caches**

---

## 🎉 Success Criteria

Test successful if:
- ✅ Question creates without errors
- ✅ Student can drag & drop smoothly
- ✅ Answers save correctly
- ✅ Scoring works accurately
- ✅ Results display properly
- ✅ Mobile responsive
- ✅ No console errors

**Happy Testing! 🚀**
