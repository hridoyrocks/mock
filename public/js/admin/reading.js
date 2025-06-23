// Simple Reading section functionality
let passageEditor = null;

document.addEventListener('DOMContentLoaded', function () {
    // Initialize TinyMCE for reading questions
    initializeTinyMCE('.tinymce');

    // Setup question type handler
    const questionType = document.getElementById('question_type');
    if (questionType) {
        questionType.addEventListener('change', handleReadingQuestionTypeChange);
        // Trigger on load if value exists
        if (questionType.value) {
            handleReadingQuestionTypeChange.call(questionType);
        }
    }
});

// Handle question type changes for reading
function handleReadingQuestionTypeChange() {
    const type = this.value;
    const questionContentField = document.getElementById('question-content-field');
    const passageContentField = document.getElementById('passage-content-field');
    const blanksManager = document.getElementById('blanks-manager');

    // Reset displays
    if (questionContentField) questionContentField.classList.remove('hidden');
    if (passageContentField) passageContentField.classList.add('hidden');
    if (blanksManager) blanksManager.classList.add('hidden');

    if (type === 'passage') {
        // Show passage content field, hide question field
        if (questionContentField) questionContentField.classList.add('hidden');
        if (passageContentField) passageContentField.classList.remove('hidden');

        // Initialize passage editor if not already done
        if (!passageEditor && typeof tinymce !== 'undefined') {
            setTimeout(() => {
                initializePassageEditor();
            }, 100);
        }

        // Set default values
        const orderInput = document.querySelector('input[name="order_number"]');
        const marksInput = document.querySelector('input[name="marks"]');
        if (orderInput && orderInput.value === '') orderInput.value = '0';
        if (marksInput) marksInput.value = '0';

    } else {
        // Regular question types
        if (questionContentField) questionContentField.classList.remove('hidden');
        if (passageContentField) passageContentField.classList.add('hidden');

        // Reset marks if it was 0
        const marksInput = document.querySelector('input[name="marks"]');
        if (marksInput && marksInput.value === '0') {
            marksInput.value = '1';
        }

        // Show blanks manager for fill_blanks type
        if (type === 'fill_blanks') {
            if (blanksManager) blanksManager.classList.remove('hidden');
            // Initialize blanks functionality
            if (typeof updateBlanks === 'function') {
                updateBlanks();
            }
        }
    }
}

// Initialize passage editor
function initializePassageEditor() {
    // First check if element exists
    const passageTextarea = document.querySelector('.tinymce-passage');
    if (!passageTextarea) {
        console.log('Passage textarea not found');
        return;
    }

    // Initialize TinyMCE for passage
    tinymce.init({
        selector: '.tinymce-passage',
        height: 500,
        menubar: true,
        plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | fullscreen preview',
        content_style: 'body { font-family: Georgia, Times New Roman, serif; font-size: 16px; line-height: 1.8; color: #333; }',
        setup: function (editor) {
            passageEditor = editor;
        }
    });
}

// Insert blank for fill-in-the-blanks
window.insertBlank = function () {
    if (editor) {
        const blankCounter = (document.querySelectorAll('[data-blank]').length || 0) + 1;
        const blankHtml = '<span class="blank-placeholder" data-blank="' + blankCounter + '" contenteditable="false" style="background:#fef3c7;padding:2px 8px;margin:0 4px;border-bottom:2px solid #f59e0b;">[____' + blankCounter + '____]</span>';
        editor.insertContent(blankHtml);
        updateBlanks();
    }
};

// Insert dropdown
window.insertDropdown = function () {
    if (editor) {
        const options = prompt('Enter dropdown options separated by comma:\n(e.g., option1, option2, option3)');
        if (options) {
            const dropdownCounter = (document.querySelectorAll('[data-dropdown]').length || 0) + 1;
            const dropdownHtml = '<span class="dropdown-placeholder" data-dropdown="' + dropdownCounter + '" data-options="' + options + '" contenteditable="false" style="background:#d1fae5;border:1px solid #10b981;padding:2px 8px;margin:0 4px;border-radius:4px;">[DROPDOWN_' + dropdownCounter + ']</span>';
            editor.insertContent(dropdownHtml);
            updateBlanks();
        }
    }
};

// Update blanks display
function updateBlanks() {
    if (!editor) return;

    const content = editor.getContent();
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = content;

    const blanks = tempDiv.querySelectorAll('[data-blank], [data-dropdown]');
    const blanksManager = document.getElementById('blanks-manager');
    const blanksList = document.getElementById('blanks-list');

    if (!blanksManager || !blanksList) return;

    if (blanks.length > 0) {
        blanksManager.classList.remove('hidden');
        blanksList.innerHTML = '';

        blanks.forEach((blank) => {
            const isDropdown = blank.hasAttribute('data-dropdown');
            const num = blank.getAttribute(isDropdown ? 'data-dropdown' : 'data-blank');

            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex items-center space-x-2';

            if (isDropdown) {
                const options = blank.getAttribute('data-options');
                const optionsArray = options.split(',').map(opt => opt.trim());

                itemDiv.innerHTML = `
                    <span class="text-sm font-medium">Dropdown ${num}:</span>
                    <input type="text" value="${options}" name="dropdown_options[${num}]" 
                           class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded">
                    <select name="dropdown_correct[${num}]" class="px-2 py-1 text-sm border border-gray-300 rounded">
                        ${optionsArray.map((opt, idx) => `<option value="${idx}">${opt}</option>`).join('')}
                    </select>
                `;
            } else {
                itemDiv.innerHTML = `
                    <span class="text-sm font-medium">Blank ${num}:</span>
                    <input type="text" name="blank_answers[${num}]" 
                           class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded" 
                           placeholder="Correct answer">
                `;
            }

            blanksList.appendChild(itemDiv);
        });
    } else {
        blanksManager.classList.add('hidden');
    }
}

// Override form submission for passage handling
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('questionForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const questionType = document.getElementById('question_type').value;

            // Save all TinyMCE editors first
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }

            if (questionType === 'passage') {
                // For passage type, ensure passage content is properly submitted
                if (passageEditor) {
                    const passageContent = passageEditor.getContent();

                    // Find the passage_text field
                    const passageTextField = document.getElementById('passage_text');
                    if (passageTextField) {
                        passageTextField.value = passageContent;
                    }

                    // Also set in content field as backup
                    const contentField = document.getElementById('content');
                    if (contentField) {
                        contentField.value = passageContent;
                    }

                    console.log('Passage content set:', passageContent.length + ' characters');
                }
            }

            // Continue with normal form submission
            return true;
        });
    }
});

// Section specific handlers (called from question-common.js)
function handleSectionSpecificChange(type) {
    // Additional handling if needed
    const questionType = document.getElementById('question_type');
    if (questionType) {
        handleReadingQuestionTypeChange.call(questionType);
    }
}

// Export functions for use in other scripts
window.updateBlanks = updateBlanks;