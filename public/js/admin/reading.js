// Reading section specific functionality
let passageEditor = null;

document.addEventListener('DOMContentLoaded', function () {
    // Initialize TinyMCE for reading questions
    initializeTinyMCE('.tinymce');

    // Setup question type handler
    const questionType = document.getElementById('question_type');
    if (questionType) {
        questionType.addEventListener('change', handleReadingQuestionTypeChange);
    }
});

// Handle question type changes for reading
function handleReadingQuestionTypeChange() {
    const type = this.value;
    const questionContentField = document.getElementById('question-content-field');
    const passageContentField = document.getElementById('passage-content-field');
    const markerSelectField = document.getElementById('marker-select-field');
    const blanksManager = document.getElementById('blanks-manager');

    // Reset displays
    if (questionContentField) questionContentField.classList.remove('hidden');
    if (passageContentField) passageContentField.classList.add('hidden');
    if (markerSelectField) markerSelectField.classList.add('hidden');
    if (blanksManager) blanksManager.classList.add('hidden');

    if (type === 'passage') {
        // Show passage content field, hide question field
        if (questionContentField) questionContentField.classList.add('hidden');
        if (passageContentField) passageContentField.classList.remove('hidden');

        // Initialize passage editor if not already done
        if (!passageEditor) {
            initializePassageEditor();
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

        // Check if we have markers available
        checkAvailableMarkers();

        // Show blanks manager for fill_blanks type
        if (type === 'fill_blanks') {
            // Content editor will handle blanks
        }
    }
}

// Initialize passage editor
function initializePassageEditor() {
    tinymce.init({
        selector: '.tinymce-passage',
        height: 500,
        menubar: true,
        plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | markerTools | removeformat | fullscreen preview',
        content_style: 'body { font-family: Georgia, Times New Roman, serif; font-size: 16px; line-height: 1.8; color: #333; }',
        setup: function (editor) {
            passageEditor = editor;

            // Add marker toolbar button
            editor.ui.registry.addMenuButton('markerTools', {
                text: 'Markers',
                icon: 'bookmark',
                fetch: function (callback) {
                    const items = [];
                    for (let i = 1; i <= 10; i++) {
                        items.push({
                            type: 'menuitem',
                            text: 'Insert Q' + i + ' Marker',
                            onAction: function () {
                                insertMarker(i);
                            }
                        });
                    }
                    callback(items);
                }
            });

            editor.on('change keyup', function () {
                detectMarkers();
            });
        }
    });
}

// Insert marker in passage
function insertMarker(number) {
    if (passageEditor) {
        const selection = passageEditor.selection.getContent({ format: 'text' });
        const text = selection || 'answer location';
        const marker = '{{Q' + number + '}}' + text + '{{Q' + number + '}}';
        passageEditor.insertContent(marker);
    }
}

// Detect markers in passage
function detectMarkers() {
    if (!passageEditor) return;

    const content = passageEditor.getContent({ format: 'text' });
    const regex = /\{\{(Q\d+)\}\}/g;
    const markers = [];
    let match;

    while ((match = regex.exec(content)) !== null) {
        if (!markers.includes(match[1])) {
            markers.push(match[1]);
        }
    }

    // Update marker dropdown
    updateMarkerDropdown(markers);
}

// Update marker dropdown for questions
function updateMarkerDropdown(markers) {
    const markerSelect = document.querySelector('select[name="marker_id"]');
    if (!markerSelect) return;

    markerSelect.innerHTML = '<option value="">-- No specific location --</option>';

    markers.sort((a, b) => {
        const numA = parseInt(a.replace('Q', ''));
        const numB = parseInt(b.replace('Q', ''));
        return numA - numB;
    });

    markers.forEach(marker => {
        const option = document.createElement('option');
        option.value = marker;
        option.textContent = marker + ' - Marked location in passage';
        markerSelect.appendChild(option);
    });
}

// Check available markers on page load
function checkAvailableMarkers() {
    // This would check if there's already a passage with markers
    // For now, just show/hide the marker field
    const markerField = document.getElementById('marker-select-field');
    const questionType = document.getElementById('question_type').value;

    if (markerField && questionType && questionType !== 'passage') {
        // Show marker field for question types (not for passage)
        markerField.classList.remove('hidden');
    }
}

// Insert blank for fill-in-the-blanks
window.insertBlank = function () {
    if (editor) {
        const blankCounter = document.querySelectorAll('[data-blank]').length + 1;
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
            const dropdownCounter = document.querySelectorAll('[data-dropdown]').length + 1;
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

            if (questionType === 'passage') {
                // For passage type, move passage content to main content field
                if (passageEditor) {
                    const passageContent = passageEditor.getContent();

                    // Set content field
                    const contentField = document.getElementById('content');
                    if (contentField) {
                        // If TinyMCE is active on content field
                        if (window.editor) {
                            window.editor.setContent(passageContent);
                        } else {
                            contentField.value = passageContent;
                        }
                    }
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