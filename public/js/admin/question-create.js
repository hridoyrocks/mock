// Question Create Page - Complete JavaScript
let blankCounter = 0;
let editor;
let passageEditor;

// DOM Ready
document.addEventListener('DOMContentLoaded', function () {
    initializeTinyMCE();
    initializeEventListeners();
    initializeQuestionNumbering();
    initializeFileUpload();
    initializeRelatedTopics();
});

// Initialize TinyMCE Editors
function initializeTinyMCE() {
    // Main Question Editor
    if (tinymce) {
        tinymce.init({
            selector: '.tinymce',
            height: 350,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: `
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6; 
                }
                .blank-placeholder {
                    background-color: #fef3c7;
                    border-bottom: 2px solid #f59e0b;
                    padding: 0 8px;
                    margin: 0 4px;
                    display: inline-block;
                    min-width: 60px;
                    text-align: center;
                    font-family: monospace;
                    font-size: 13px;
                }
                .dropdown-placeholder {
                    background-color: #d1fae5;
                    border: 1px solid #10b981;
                    color: #065f46;
                    padding: 2px 8px;
                    margin: 0 2px;
                    border-radius: 4px;
                    font-weight: 500;
                    display: inline-block;
                }
            `,
            setup: function (ed) {
                editor = ed;
                ed.on('input change', function () {
                    updateWordCount();
                    updateBlanks();
                });
            }
        });

        // Passage Editor
        tinymce.init({
            selector: '.tinymce-passage',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | fullscreen preview',
            content_style: `
                body { 
                    font-family: Georgia, 'Times New Roman', serif; 
                    font-size: 16px; 
                    line-height: 1.8; 
                    color: #333;
                    padding: 20px;
                }
                p { 
                    margin-bottom: 16px; 
                    text-indent: 2em;
                }
                p:first-child {
                    text-indent: 0;
                }
                .answer-marker {
                    background-color: #fef3c7;
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-weight: 600;
                    font-size: 14px;
                    color: #92400e;
                    font-family: monospace;
                }
            `,
            setup: function (editor) {
                passageEditor = editor;

                editor.on('input change', function () {
                    const text = editor.getContent({ format: 'text' });
                    const words = text.trim().split(/\s+/).filter(word => word.length > 0);
                    const el = document.getElementById('passage-word-count');
                    if (el) el.textContent = words.length;
                });

                editor.on('init', function () {
                    processMarkers();
                });
            }
        });
    }
}

// Initialize Event Listeners
function initializeEventListeners() {
    // Question Type Change
    const questionTypeSelect = document.getElementById('question_type');
    if (questionTypeSelect) {
        questionTypeSelect.addEventListener('change', handleQuestionTypeChange);
    }

    // Add Option Button
    const addOptionBtn = document.getElementById('add-option-btn');
    if (addOptionBtn) {
        addOptionBtn.addEventListener('click', () => addOption());
    }

    // Form Submit
    const questionForm = document.getElementById('questionForm');
    if (questionForm) {
        questionForm.addEventListener('submit', handleFormSubmit);
    }

    // ESC key handlers
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

// Initialize Question Numbering
function initializeQuestionNumbering() {
    const orderInput = document.querySelector('input[name="order_number"]');
    const numberDisplay = document.getElementById('question-number-display');

    if (orderInput && numberDisplay && window.existingQuestions) {
        const existingNumbers = window.existingQuestions;

        orderInput.addEventListener('input', function () {
            const value = this.value || '?';
            numberDisplay.textContent = '#' + value;

            // Check duplicate
            if (existingNumbers.includes(parseInt(value))) {
                let warning = this.parentElement.querySelector('.duplicate-warning');
                if (!warning) {
                    warning = document.createElement('p');
                    warning.className = 'duplicate-warning text-xs text-yellow-600 mt-1';
                    warning.innerHTML = '⚠️ This number already exists. Existing questions will be reordered.';
                    this.parentElement.appendChild(warning);
                }
            } else {
                const warning = this.parentElement.querySelector('.duplicate-warning');
                if (warning) warning.remove();
            }
        });
    }
}

// Question Type Change Handler
function handleQuestionTypeChange() {
    const type = this.value;
    const optionsCard = document.getElementById('options-card');
    const passageSection = document.getElementById('passage-section');
    const mainContentSection = document.getElementById('main-content-section');

    const optionTypes = ['multiple_choice', 'true_false', 'yes_no', 'matching', 'matching_headings'];

    if (type === 'passage') {
        if (passageSection) passageSection.classList.remove('hidden');
        if (optionsCard) optionsCard.classList.add('hidden');
        if (mainContentSection) mainContentSection.classList.add('hidden');

        const orderInput = document.querySelector('input[name="order_number"]');
        const marksInput = document.querySelector('input[name="marks"]');

        if (orderInput) orderInput.value = '0';
        if (marksInput) marksInput.value = '0';
    } else {
        if (passageSection) passageSection.classList.add('hidden');
        if (mainContentSection) mainContentSection.classList.remove('hidden');

        const marksInput = document.querySelector('input[name="marks"]');
        if (marksInput && marksInput.value === '0') {
            marksInput.value = '1';
        }

        if (optionTypes.includes(type)) {
            if (optionsCard) optionsCard.classList.remove('hidden');
            setupDefaultOptions(type);
        } else {
            if (optionsCard) optionsCard.classList.add('hidden');
        }
    }
}

// Blank and Dropdown Functions
window.insertBlank = function () {
    if (editor) {
        blankCounter++;
        const blankHtml = '<span class="blank-placeholder" data-blank="' + blankCounter + '" contenteditable="false">[____' + blankCounter + '____]</span>';
        editor.insertContent(blankHtml);
        updateBlanks();
        showTooltip('Blank inserted! It will appear as ____ in student view.');
    }
};

window.insertDropdown = function () {
    if (editor) {
        const options = prompt('Enter dropdown options separated by comma:\n(e.g., option1, option2, option3)');
        if (options) {
            blankCounter++;
            const dropdownHtml = '<span class="dropdown-placeholder" data-dropdown="' + blankCounter + '" data-options="' + options + '" contenteditable="false">[DROPDOWN_' + blankCounter + ']</span>';
            editor.insertContent(dropdownHtml);
            updateBlanks();
            showTooltip('Dropdown inserted! Students will see a dropdown menu.');
        }
    }
};

// Passage Marker Functions
window.insertAnswerMarker = function () {
    if (!passageEditor) return;

    const questionNum = prompt('Enter question number for this answer location:', '');
    if (!questionNum) return;

    // Use square brackets to avoid blade conflicts
    const marker = '[Q' + questionNum + ']';
    passageEditor.insertContent(marker);

    setTimeout(processMarkers, 100);
};

function processMarkers() {
    if (!passageEditor) return;

    let content = passageEditor.getContent();

    // Replace [Q1] style markers with styled spans
    content = content.replace(/\[Q(\d+)\]/g, function (match, num) {
        return '<span class="answer-marker" contenteditable="false">[Q' + num + ']</span>';
    });

    passageEditor.setContent(content);
}

window.previewMarkers = function () {
    if (!passageEditor) return;

    const content = passageEditor.getContent();
    const markerMatches = content.match(/\[Q\d+\]/g);
    const markerCount = markerMatches ? markerMatches.length : 0;

    const modal = createModal('Passage Preview',
        '<p class="text-sm text-gray-500 mb-4">Found ' + markerCount + ' answer markers</p>' +
        '<div class="prose max-w-none">' + content + '</div>'
    );

    document.body.appendChild(modal);
};

// Options Management
function setupDefaultOptions(type) {
    const container = document.getElementById('options-container');
    if (!container) return;

    container.innerHTML = '';

    if (type === 'true_false') {
        addOption('TRUE', true);
        addOption('FALSE', false);
        addOption('NOT GIVEN', false);
        document.getElementById('add-option-btn').style.display = 'none';
    } else if (type === 'yes_no') {
        addOption('YES', true);
        addOption('NO', false);
        addOption('NOT GIVEN', false);
        document.getElementById('add-option-btn').style.display = 'none';
    } else {
        for (let i = 0; i < 4; i++) {
            addOption('', i === 0);
        }
        document.getElementById('add-option-btn').style.display = 'inline-block';
    }
}

function addOption(content = '', isCorrect = false) {
    const container = document.getElementById('options-container');
    if (!container) return;

    const index = container.children.length;

    const optionDiv = document.createElement('div');
    optionDiv.className = 'flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200';

    optionDiv.innerHTML = [
        '<input type="radio" name="correct_option" value="' + index + '" class="h-4 w-4 text-blue-600" ' + (isCorrect ? 'checked' : '') + '>',
        '<span class="font-medium text-gray-700">' + String.fromCharCode(65 + index) + '.</span>',
        '<input type="text" name="options[' + index + '][content]" value="' + content + '" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Enter option text..." required>',
        '<button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700">',
        '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">',
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
        '</svg>',
        '</button>'
    ].join('');

    container.appendChild(optionDiv);
}

window.removeOption = function (btn) {
    btn.parentElement.remove();
    reindexOptions();
};

function reindexOptions() {
    const options = document.querySelectorAll('#options-container > div');
    options.forEach((option, index) => {
        option.querySelector('input[type="radio"]').value = index;
        option.querySelector('input[type="text"]').name = 'options[' + index + '][content]';
        option.querySelector('span.font-medium').textContent = String.fromCharCode(65 + index) + '.';
    });
}

// Update Functions
function updateWordCount() {
    if (editor) {
        const text = editor.getContent({ format: 'text' });
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const chars = text.length;

        const wordEl = document.getElementById('word-count');
        const charEl = document.getElementById('char-count');

        if (wordEl) wordEl.textContent = words.length;
        if (charEl) charEl.textContent = chars;
    }
}

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

                itemDiv.innerHTML = [
                    '<span class="text-sm font-medium">Dropdown ' + num + ':</span>',
                    '<input type="text" value="' + options + '" name="dropdown_options[' + num + ']" class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded">',
                    '<select name="dropdown_correct[' + num + ']" class="px-2 py-1 text-sm border border-gray-300 rounded">',
                    optionsArray.map((opt, idx) => '<option value="' + idx + '">' + opt + '</option>').join(''),
                    '</select>'
                ].join('');
            } else {
                itemDiv.innerHTML = [
                    '<span class="text-sm font-medium">Blank ' + num + ':</span>',
                    '<input type="text" name="blank_answers[' + num + ']" class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded" placeholder="Correct answer">'
                ].join('');
            }

            blanksList.appendChild(itemDiv);
        });
    } else {
        blanksManager.classList.add('hidden');
    }
}

// Modal Functions
window.showTemplates = function () {
    const modal = document.getElementById('template-modal');
    if (modal) modal.classList.remove('hidden');
};

window.closeTemplates = function () {
    const modal = document.getElementById('template-modal');
    if (modal) modal.classList.add('hidden');
};

window.useTemplate = function (template) {
    const instructionsEl = document.getElementById('instructions');
    if (instructionsEl) instructionsEl.value = template;
    closeTemplates();
};

window.useExplanationTemplate = function (type) {
    const templates = {
        'synonym': 'The correct answer is [OPTION] because it uses a synonym of "[WORD]" from the passage. In the passage, the author uses "[ORIGINAL_WORD]" which has the same meaning as "[SYNONYM]" in the correct option.',
        'true_false': 'The statement is [TRUE/FALSE/NOT GIVEN] because:\n- TRUE: The passage directly states this information in [LOCATION].\n- FALSE: The passage contradicts this by stating [CONTRADICTION].\n- NOT GIVEN: This information is not mentioned anywhere in the passage.',
        'main_idea': 'The main idea of the passage is found in [LOCATION]. The author\'s primary purpose is to [PURPOSE]. Options [WRONG_OPTIONS] are incorrect because they focus on specific details rather than the overall message.',
        'inference': 'Although not directly stated, we can infer [ANSWER] from [EVIDENCE] in the passage. The author implies this through [CLUES].'
    };

    const explanationField = document.querySelector('textarea[name="explanation"]');
    if (explanationField && templates[type]) {
        explanationField.value = templates[type];
        explanationField.focus();
    }
};

window.previewQuestion = function () {
    const modal = document.getElementById('preview-modal');
    const content = document.getElementById('preview-content');

    if (!modal || !content) return;

    const questionType = document.getElementById('question_type').value;

    let previewHtml = '<div class="space-y-4">';

    if (questionType === 'passage') {
        const passageTitle = document.querySelector('input[name="passage_title"]');
        const passageText = passageEditor ? passageEditor.getContent() : '';

        if (passageTitle && passageTitle.value) {
            previewHtml += '<h3 class="text-lg font-semibold">' + passageTitle.value + '</h3>';
        }
        previewHtml += '<div class="whitespace-pre-wrap">' + passageText + '</div>';
    } else {
        const instructions = document.getElementById('instructions');
        const questionContent = editor ? editor.getContent() : '';

        if (instructions && instructions.value) {
            previewHtml += '<div class="text-sm text-gray-600 italic">' + instructions.value + '</div>';
        }

        previewHtml += '<div class="text-gray-900">' + questionContent + '</div>';

        const optionsContainer = document.getElementById('options-container');
        if (optionsContainer && optionsContainer.children.length > 0) {
            previewHtml += '<div class="mt-4 space-y-2">';
            const options = optionsContainer.querySelectorAll('input[type="text"]');
            options.forEach((option, index) => {
                if (option.value) {
                    previewHtml += '<div class="flex items-center space-x-2">' +
                        '<span class="font-medium">' + String.fromCharCode(65 + index) + '.</span>' +
                        '<span>' + option.value + '</span>' +
                        '</div>';
                }
            });
            previewHtml += '</div>';
        }
    }

    previewHtml += '</div>';

    content.innerHTML = previewHtml;
    modal.classList.remove('hidden');
};

window.closePreview = function () {
    const modal = document.getElementById('preview-modal');
    if (modal) modal.classList.add('hidden');
};

// Bulk Options
window.showBulkOptions = function () {
    const modal = document.getElementById('bulk-modal');
    if (modal) modal.classList.remove('hidden');
};

window.closeBulkOptions = function () {
    const modal = document.getElementById('bulk-modal');
    if (modal) modal.classList.add('hidden');
    const bulkText = document.getElementById('bulk-text');
    if (bulkText) bulkText.value = '';
};

window.addBulkOptions = function () {
    const bulkText = document.getElementById('bulk-text');
    if (bulkText && bulkText.value) {
        const container = document.getElementById('options-container');
        if (container) {
            container.innerHTML = '';

            const options = bulkText.value.split('\n').filter(opt => opt.trim());
            options.forEach((opt, index) => {
                addOption(opt.trim(), index === 0);
            });
        }

        closeBulkOptions();
    }
};

// File Upload
function initializeFileUpload() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('media');

    if (!dropZone || !fileInput) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false);
    });

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFiles(files);
        }
    }

    fileInput.addEventListener('change', function (e) {
        handleFiles(this.files);
    });
}

function handleFiles(files) {
    if (files.length > 0) {
        const file = files[0];
        const preview = document.getElementById('media-preview');
        if (!preview) return;

        preview.innerHTML = '';
        preview.classList.remove('hidden');

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = [
                    '<div class="relative inline-block">',
                    '<img src="' + e.target.result + '" class="max-h-48 rounded">',
                    '<button type="button" onclick="clearMedia()" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full transform translate-x-1/2 -translate-y-1/2">',
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">',
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                    '</svg>',
                    '</button>',
                    '</div>',
                    '<p class="text-sm text-gray-600 mt-2">' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)</p>'
                ].join('');
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('audio/')) {
            preview.innerHTML = [
                '<div class="relative">',
                '<audio controls class="w-full">',
                '<source src="' + URL.createObjectURL(file) + '" type="' + file.type + '">',
                '</audio>',
                '<button type="button" onclick="clearMedia()" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full transform translate-x-1/2 -translate-y-1/2">',
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                '</svg>',
                '</button>',
                '</div>',
                '<p class="text-sm text-gray-600 mt-2">' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)</p>'
            ].join('');
        }
    }
}

window.clearMedia = function () {
    const fileInput = document.getElementById('media');
    const preview = document.getElementById('media-preview');

    if (fileInput) fileInput.value = '';
    if (preview) {
        preview.innerHTML = '';
        preview.classList.add('hidden');
    }
};

// Related Topics
function initializeRelatedTopics() {
    const topicsInput = document.getElementById('related-topics-input');
    const topicTags = document.getElementById('topic-tags');

    if (!topicsInput || !topicTags) return;

    let topics = [];

    topicsInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const value = this.value.trim();
            if (value && !topics.includes(value)) {
                addTopic(value);
                this.value = '';
            }
        }
    });

    function addTopic(topic) {
        topics.push(topic);
        renderTopics();
    }

    window.removeTopic = function (index) {
        topics.splice(index, 1);
        renderTopics();
    };

    function renderTopics() {
        topicTags.innerHTML = topics.map((topic, index) =>
            '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">' +
            topic +
            '<button type="button" onclick="removeTopic(' + index + ')" class="ml-2 text-blue-600 hover:text-blue-800">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' +
            '</svg>' +
            '</button>' +
            '</span>'
        ).join('');

        let hiddenInput = document.querySelector('input[name="related_topics"]');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'related_topics';
            topicsInput.parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = JSON.stringify(topics);
    }
}

// Form Submit Handler
function handleFormSubmit(e) {
    e.preventDefault();

    const questionType = document.getElementById('question_type').value;

    if (questionType === 'passage') {
        const passageText = passageEditor ? passageEditor.getContent() : '';

        if (!passageText || passageText.trim() === '') {
            alert('Please enter passage text');
            return false;
        }

        const contentTextarea = document.getElementById('content');
        if (editor) {
            editor.setContent(passageText);
            editor.save();
        } else if (contentTextarea) {
            contentTextarea.value = passageText;
        }
    } else {
        if (editor) {
            editor.save();
        }

        const content = editor ? editor.getContent() : document.getElementById('content').value;
        if (!content || content.trim() === '') {
            alert('Please enter question content');
            return false;
        }
    }

    this.submit();
}

// Utility Functions
function showTooltip(message) {
    const tooltip = document.getElementById('editor-tooltip');
    if (!tooltip) return;

    tooltip.textContent = message;
    tooltip.style.display = 'block';
    tooltip.style.opacity = '1';

    setTimeout(() => {
        tooltip.style.opacity = '0';
        setTimeout(() => {
            tooltip.style.display = 'none';
        }, 300);
    }, 3000);
}

function createModal(title, content) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';

    modal.innerHTML = [
        '<div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">',
        '<div class="p-4 border-b flex justify-between items-center">',
        '<h3 class="text-lg font-semibold">' + title + '</h3>',
        '<button onclick="this.closest(\'.fixed\').remove()" class="text-gray-500 hover:text-gray-700">',
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">',
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
        '</svg>',
        '</button>',
        '</div>',
        '<div class="p-6 overflow-y-auto max-h-[70vh]">',
        content,
        '</div>',
        '</div>'
    ].join('');

    return modal;
}

function closeAllModals() {
    closeTemplates();
    closePreview();
    closeBulkOptions();
}