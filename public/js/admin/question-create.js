// Question Create Page - Complete JavaScript with Enhanced Passage Marker & Explanation Editor
let blankCounter = 0;
let editor;
let passageEditor;
let explanationEditor;

// Enhanced Passage Marker System
const PassageMarker = {
    markers: new Map(),
    currentQuestion: null,

    init: function () {
        this.setupMarkerButtons();
        this.loadExistingMarkers();
        this.setupMarkerPreview();
    },

    setupMarkerButtons: function () {
        // Add marker button to passage editor toolbar
        if (window.passageEditor) {
            const toolbar = document.querySelector('.passage-toolbar');
            if (!toolbar) {
                const markerToolbar = document.createElement('div');
                markerToolbar.className = 'passage-toolbar mb-3';
                markerToolbar.innerHTML = `
                    <div class="flex gap-2">
                        <button type="button" onclick="PassageMarker.showMarkerDialog()" 
                                class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Mark Answer Location
                        </button>
                        <button type="button" onclick="PassageMarker.previewMarkers()" 
                                class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Preview All Markers
                        </button>
                        <button type="button" onclick="PassageMarker.clearMarkers()" 
                                class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Clear All Markers
                        </button>
                    </div>
                `;

                const editorContainer = document.querySelector('.tinymce-passage').parentElement;
                editorContainer.insertBefore(markerToolbar, editorContainer.firstChild);
            }
        }

        // Also add marker list panel
        this.addMarkerListPanel();
    },

    addMarkerListPanel: function () {
        const passageSection = document.getElementById('passage-section');
        if (!passageSection || document.getElementById('markers-panel')) return;

        const panel = document.createElement('div');
        panel.id = 'markers-panel';
        panel.className = 'mt-4 p-4 bg-blue-50 rounded-lg';
        panel.innerHTML = `
            <h4 class="font-semibold text-sm mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Marked Questions:
            </h4>
            <div id="markers-list" class="space-y-2">
                <p class="text-sm text-gray-500">No markers added yet</p>
            </div>
        `;

        const contentDiv = passageSection.querySelector('.p-6');
        if (contentDiv) {
            contentDiv.appendChild(panel);
        }
    },

    showMarkerDialog: function () {
        // Get selected text in passage editor
        if (!passageEditor) return;

        const selection = passageEditor.selection.getContent({ format: 'text' });
        if (!selection) {
            this.showTooltip('Please select text in the passage first!', 'warning');
            return;
        }

        // Get next available marker number
        const usedNumbers = Array.from(this.markers.keys()).map(k => parseInt(k.replace('Q', '')));
        const nextNumber = usedNumbers.length > 0 ? Math.max(...usedNumbers) + 1 : 1;

        // Create marker dialog
        const dialog = document.createElement('div');
        dialog.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        dialog.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold mb-4">Mark Answer Location</h3>
                <p class="text-sm text-gray-600 mb-4">Selected text: <em>"${selection.substring(0, 50)}${selection.length > 50 ? '...' : ''}"</em></p>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Question Number (This will be Q#)
                    </label>
                    <input type="number" id="marker-question-number" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter question number (e.g., 1, 2, 3...)"
                           value="${nextNumber}"
                           min="1" max="40">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button onclick="PassageMarker.closeDialog()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                        Cancel
                    </button>
                    <button onclick="PassageMarker.addMarker()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Add Marker
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(dialog);
        document.getElementById('marker-question-number').focus();
    },

    addMarker: function () {
        const questionNum = document.getElementById('marker-question-number').value;

        if (!questionNum) {
            alert('Please enter a question number');
            return;
        }

        // Get selected content
        const selection = passageEditor.selection.getContent({ format: 'text' });
        const markerId = `Q${questionNum}`;

        // Check if marker already exists
        if (this.markers.has(markerId)) {
            if (!confirm(`Marker ${markerId} already exists. Replace it?`)) {
                return;
            }
            // Remove old marker first
            this.removeMarkerFromPassage(markerId);
        }

        // Replace selection with marked content
        const markedContent = `{{${markerId}}}${selection}{{${markerId}}}`;
        passageEditor.selection.setContent(markedContent);

        // Store marker info
        this.markers.set(markerId, {
            text: selection,
            position: passageEditor.selection.getBookmark()
        });

        // Update UI
        this.updateMarkersList();
        this.updateMarkerDropdown();

        // Close dialog and show success
        this.closeDialog();
        this.showTooltip(`Answer location marked for Question ${questionNum}`, 'success');
    },

    removeMarkerFromPassage: function (markerId) {
        const content = passageEditor.getContent();
        const regex = new RegExp(`\\{\\{${markerId}\\}\\}(.*?)\\{\\{${markerId}\\}\\}`, 'gs');
        const newContent = content.replace(regex, '$1');
        passageEditor.setContent(newContent);
    },

    updateMarkersList: function () {
        const markersList = document.getElementById('markers-list');
        if (!markersList) return;

        if (this.markers.size === 0) {
            markersList.innerHTML = '<p class="text-sm text-gray-500">No markers added yet</p>';
            return;
        }

        markersList.innerHTML = Array.from(this.markers.entries())
            .sort((a, b) => {
                const numA = parseInt(a[0].replace('Q', ''));
                const numB = parseInt(b[0].replace('Q', ''));
                return numA - numB;
            })
            .map(([id, data]) => `
                <div class="flex items-center justify-between p-2 bg-white rounded border border-gray-200 hover:border-blue-300 transition-colors">
                    <div class="flex items-center flex-1">
                        <span class="font-semibold text-blue-600 mr-2">${id}</span>
                        <span class="text-sm text-gray-600 truncate">"${data.text.substring(0, 40)}${data.text.length > 40 ? '...' : ''}"</span>
                    </div>
                    <button onclick="PassageMarker.removeMarker('${id}')" 
                            class="ml-2 text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors"
                            title="Remove marker">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `).join('');
    },

    removeMarker: function (markerId) {
        if (!confirm(`Remove marker ${markerId}?`)) return;

        // Remove from passage
        this.removeMarkerFromPassage(markerId);

        // Remove from map
        this.markers.delete(markerId);

        // Update UI
        this.updateMarkersList();
        this.updateMarkerDropdown();

        this.showTooltip(`Marker ${markerId} removed`, 'info');
    },

    updateMarkerDropdown: function () {
        // Update marker dropdown in main question form
        const markerField = document.getElementById('marker-field');
        if (!markerField) return;

        const questionType = document.getElementById('question_type').value;

        // Show/hide based on question type
        if (questionType && questionType !== 'passage' && this.markers.size > 0) {
            markerField.classList.remove('hidden');

            const select = markerField.querySelector('select[name="marker_id"]');
            if (select) {
                const currentValue = select.value;
                select.innerHTML = '<option value="">-- No marker (optional) --</option>' +
                    Array.from(this.markers.keys())
                        .sort((a, b) => {
                            const numA = parseInt(a.replace('Q', ''));
                            const numB = parseInt(b.replace('Q', ''));
                            return numA - numB;
                        })
                        .map(id =>
                            `<option value="${id}" ${currentValue === id ? 'selected' : ''}>${id} - "${this.markers.get(id).text.substring(0, 30)}..."</option>`
                        ).join('');
            }
        } else {
            markerField.classList.add('hidden');
        }
    },

    previewMarkers: function () {
        if (!passageEditor) return;

        const content = passageEditor.getContent();

        // Create preview modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Passage with Answer Markers</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    <div class="prose max-w-none">
                        ${this.renderMarkedContent(content)}
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-50 rounded">
                        <h4 class="font-semibold mb-2">Marked Questions Summary:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            ${Array.from(this.markers.entries()).map(([id, data]) => `
                                <div class="flex items-start">
                                    <span class="font-medium text-blue-600 mr-2">${id}:</span>
                                    <span class="text-sm text-gray-600">"${data.text.substring(0, 50)}..."</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
    },

    renderMarkedContent: function (content) {
        // Process content to highlight markers
        let processed = content;

        // Highlight markers with different colors
        processed = processed.replace(/\{\{(Q\d+)\}\}(.*?)\{\{\\1\}\}/gs, function (match, marker, text) {
            const num = parseInt(marker.replace('Q', ''));
            const colors = ['#fef3c7', '#dcfce7', '#dbeafe', '#f3e8ff', '#fee2e2'];
            const color = colors[(num - 1) % colors.length];
            return `<span style="background: ${color}; padding: 2px 6px; border-radius: 3px; font-weight: 600;">[${marker}]</span>${text}<span style="background: ${color}; padding: 2px 6px; border-radius: 3px; font-weight: 600;">[${marker}]</span>`;
        });

        return processed;
    },

    clearMarkers: function () {
        if (!confirm('Are you sure you want to clear all markers? This cannot be undone.')) return;

        if (passageEditor) {
            let content = passageEditor.getContent();

            // Remove all markers
            content = content.replace(/\{\{Q\d+\}\}/g, '');

            passageEditor.setContent(content);
        }

        this.markers.clear();
        this.updateMarkersList();
        this.updateMarkerDropdown();
        this.showTooltip('All markers cleared', 'info');
    },

    loadExistingMarkers: function () {
        // Load markers from passage content on edit
        if (passageEditor) {
            passageEditor.on('init', () => {
                const content = passageEditor.getContent();
                const regex = /\{\{(Q\d+)\}\}(.*?)\{\{\\1\}\}/gs;
                let match;

                while ((match = regex.exec(content)) !== null) {
                    const markerId = match[1];
                    const markedText = match[2];

                    this.markers.set(markerId, {
                        text: markedText,
                        position: null
                    });
                }

                if (this.markers.size > 0) {
                    this.updateMarkersList();
                    this.updateMarkerDropdown();
                }
            });
        }
    },

    closeDialog: function () {
        const dialog = document.querySelector('.fixed.inset-0');
        if (dialog) dialog.remove();
    },

    showTooltip: function (message, type = 'info') {
        const colors = {
            info: 'bg-blue-600',
            success: 'bg-green-600',
            warning: 'bg-yellow-600',
            error: 'bg-red-600'
        };

        const tooltip = document.createElement('div');
        tooltip.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
        tooltip.textContent = message;

        document.body.appendChild(tooltip);

        setTimeout(() => {
            tooltip.classList.add('animate-fade-out');
            setTimeout(() => tooltip.remove(), 300);
        }, 3000);
    },

    setupMarkerPreview: function () {
        // Add marker count display
        const passageSection = document.getElementById('passage-section');
        if (passageSection) {
            const markerCount = document.createElement('div');
            markerCount.id = 'marker-count';
            markerCount.className = 'text-sm text-gray-500 mt-2';
            markerCount.innerHTML = '<span id="marker-count-text">No markers added yet</span>';

            const passageEditor = passageSection.querySelector('.tinymce-passage');
            if (passageEditor && passageEditor.parentElement) {
                passageEditor.parentElement.appendChild(markerCount);
            }
        }
    },

    updateMarkerCount: function () {
        const countEl = document.getElementById('marker-count-text');
        if (countEl) {
            if (this.markers.size > 0) {
                countEl.textContent = `${this.markers.size} answer location(s) marked`;
            } else {
                countEl.textContent = 'No markers added yet';
            }
        }
    },

    // Helper method to get marker info for a question
    getQuestionMarkers: function (questionId) {
        return Array.from(this.markers.entries()).filter(([id, data]) => {
            const num = parseInt(id.replace('Q', ''));
            return num === questionId;
        });
    }
};

// Add marker dropdown field to the form
function addMarkerField() {
    const questionTypeDiv = document.querySelector('#question_type').closest('div');
    if (!questionTypeDiv || document.getElementById('marker-field')) return;

    const markerField = document.createElement('div');
    markerField.id = 'marker-field';
    markerField.className = 'hidden';
    markerField.innerHTML = `
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Answer Location Marker
        </label>
        <select name="marker_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- No marker (optional) --</option>
        </select>
        <p class="mt-1 text-xs text-gray-500">Link this question to a marked location in the passage</p>
    `;

    questionTypeDiv.parentElement.insertBefore(markerField, questionTypeDiv.nextSibling);
}

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
            `,
            setup: function (editor) {
                passageEditor = editor;

                editor.on('input change', function () {
                    const text = editor.getContent({ format: 'text' });
                    const words = text.trim().split(/\s+/).filter(word => word.length > 0);
                    const el = document.getElementById('passage-word-count');
                    if (el) el.textContent = words.length;

                    // Update marker count
                    PassageMarker.updateMarkerCount();
                });

                editor.on('init', function () {
                    // Initialize PassageMarker after editor is ready
                    setTimeout(() => {
                        PassageMarker.init();
                    }, 100);
                });
            }
        });
    }
}

// Initialize Explanation Editor
function initializeExplanationEditor() {
    // Check if explanation field exists
    const explanationField = document.querySelector('textarea[name="explanation"]');
    if (!explanationField) return;

    // Create a div for TinyMCE
    const editorDiv = document.createElement('div');
    editorDiv.id = 'explanation-editor';
    editorDiv.className = 'explanation-tinymce';
    editorDiv.innerHTML = explanationField.value;

    // Insert after the textarea and hide it
    explanationField.style.display = 'none';
    explanationField.parentElement.insertBefore(editorDiv, explanationField.nextSibling);

    // Initialize TinyMCE for explanation
    tinymce.init({
        selector: '#explanation-editor',
        height: 300,
        menubar: false,
        plugins: [
            'lists', 'link', 'charmap', 'preview',
            'searchreplace', 'visualblocks', 'code',
            'insertdatetime', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic underline | \
                  alignleft aligncenter alignright | \
                  bullist numlist | explanationTools | templates | \
                  link | removeformat | code',
        content_style: `
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
                font-size: 14px; 
                line-height: 1.6; 
                padding: 12px;
            }
            p { margin-bottom: 12px; }
            ul, ol { margin-bottom: 12px; padding-left: 24px; }
            li { margin-bottom: 6px; }
            strong { color: #1a202c; }
            em { color: #4a5568; }
            .highlight { background-color: #fef3c7; padding: 2px 4px; border-radius: 2px; }
            .correct-answer { color: #059669; font-weight: 600; }
            .incorrect-answer { color: #dc2626; text-decoration: line-through; }
            .tip { background-color: #dbeafe; padding: 8px 12px; border-left: 3px solid #3b82f6; margin: 12px 0; }
            blockquote { 
                border-left: 3px solid #e5e7eb; 
                padding-left: 16px; 
                margin: 16px 0; 
                color: #6b7280; 
                font-style: italic; 
            }
            mark { background-color: #fef3c7; padding: 1px 3px; border-radius: 2px; }
        `,
        toolbar_mode: 'sliding',
        branding: false,
        promotion: false,
        setup: function (editor) {
            explanationEditor = editor;

            // Add custom buttons for common explanation patterns
            editor.ui.registry.addButton('correctAnswer', {
                text: '✓ Correct',
                tooltip: 'Mark as correct answer',
                onAction: function () {
                    editor.insertContent('<span class="correct-answer">' + editor.selection.getContent() + '</span>');
                }
            });

            editor.ui.registry.addButton('incorrectAnswer', {
                text: '✗ Incorrect',
                tooltip: 'Mark as incorrect answer',
                onAction: function () {
                    editor.insertContent('<span class="incorrect-answer">' + editor.selection.getContent() + '</span>');
                }
            });

            editor.ui.registry.addButton('tipBox', {
                text: '💡 Tip',
                tooltip: 'Insert tip box',
                onAction: function () {
                    editor.insertContent('<div class="tip">💡 <strong>Tip:</strong> Enter your tip here...</div>');
                }
            });

            editor.ui.registry.addButton('insertMarkerRef', {
                text: '{{Q}}',
                tooltip: 'Reference a marker',
                onAction: function () {
                    // Show marker reference dialog
                    if (PassageMarker.markers.size === 0) {
                        alert('No markers available. Please mark locations in the passage first.');
                        return;
                    }

                    const markers = Array.from(PassageMarker.markers.keys()).sort();
                    const selected = prompt('Enter marker to reference (e.g., Q1, Q2):', markers[0]);

                    if (selected && PassageMarker.markers.has(selected)) {
                        editor.insertContent(`<mark>{{${selected}}}</mark>`);
                    }
                }
            });

            // Add to toolbar
            editor.ui.registry.addGroupToolbarButton('explanationTools', {
                text: 'Explanation',
                icon: 'info',
                items: 'correctAnswer incorrectAnswer tipBox insertMarkerRef'
            });

            // Quick templates menu
            editor.ui.registry.addMenuButton('templates', {
                text: 'Templates',
                icon: 'template',
                fetch: function (callback) {
                    const items = [
                        {
                            type: 'menuitem',
                            text: 'Marker Reference',
                            onAction: function () {
                                editor.insertContent(`
                                    <p>The answer can be found in the passage where it says <mark>{{Q1}}</mark>.</p>
                                    <p>The passage states: "<em>[QUOTE FROM PASSAGE]</em>"</p>
                                `);
                            }
                        },
                        {
                            type: 'menuitem',
                            text: 'Synonym Explanation',
                            onAction: function () {
                                editor.insertContent(`
                                    <p>The correct answer is <strong class="correct-answer">[OPTION]</strong> because it uses a synonym of "<mark>[WORD]</mark>" from the passage.</p>
                                    <p>In the passage <mark>{{Q1}}</mark>: "<em>[PASSAGE QUOTE]</em>"</p>
                                    <p>The word "<mark>[ORIGINAL]</mark>" means the same as "<mark>[SYNONYM]</mark>" in this context.</p>
                                `);
                            }
                        },
                        {
                            type: 'menuitem',
                            text: 'True/False/Not Given',
                            onAction: function () {
                                editor.insertContent(`
                                    <p>The statement is <strong>[TRUE/FALSE/NOT GIVEN]</strong> because:</p>
                                    <ul>
                                        <li><strong>TRUE:</strong> The passage directly states this information in <mark>{{Q1}}</mark>.</li>
                                        <li><strong>FALSE:</strong> The passage contradicts this by stating [CONTRADICTION].</li>
                                        <li><strong>NOT GIVEN:</strong> This information is not mentioned anywhere in the passage.</li>
                                    </ul>
                                `);
                            }
                        },
                        {
                            type: 'menuitem',
                            text: 'Main Idea',
                            onAction: function () {
                                editor.insertContent(`
                                    <p>The main idea can be found in <mark>{{Q1}}</mark> where the author states:</p>
                                    <blockquote>"[QUOTE FROM PASSAGE]"</blockquote>
                                    <p>Options <span class="incorrect-answer">[WRONG OPTIONS]</span> are incorrect because they focus on specific details rather than the overall message.</p>
                                `);
                            }
                        },
                        {
                            type: 'menuitem',
                            text: 'Multiple Choice',
                            onAction: function () {
                                editor.insertContent(`
                                    <p><strong>Why Option [X] is correct:</strong></p>
                                    <ul>
                                        <li>It accurately reflects [KEY POINT] mentioned in <mark>{{Q1}}</mark></li>
                                        <li>The passage states: "<em>[SUPPORTING QUOTE]</em>"</li>
                                    </ul>
                                    <p><strong>Why other options are incorrect:</strong></p>
                                    <ul>
                                        <li><span class="incorrect-answer">Option A</span>: [REASON]</li>
                                        <li><span class="incorrect-answer">Option B</span>: [REASON]</li>
                                        <li><span class="incorrect-answer">Option C</span>: [REASON]</li>
                                    </ul>
                                `);
                            }
                        },
                        {
                            type: 'menuitem',
                            text: 'Inference Question',
                            onAction: function () {
                                editor.insertContent(`
                                    <p>Although not directly stated, we can infer <strong>[ANSWER]</strong> from the following clues:</p>
                                    <ol>
                                        <li>[CLUE 1] in <mark>{{Q1}}</mark></li>
                                        <li>[CLUE 2] when the author mentions "<em>[QUOTE]</em>"</li>
                                        <li>[CLUE 3] based on the context</li>
                                    </ol>
                                    <p>Therefore, the logical conclusion is [INFERENCE].</p>
                                `);
                            }
                        },
                        {
                            type: 'menuitem',
                            text: 'Matching Headings',
                            onAction: function () {
                                editor.insertContent(`
                                    <p>The correct heading is <strong class="correct-answer">[HEADING]</strong> because:</p>
                                    <ul>
                                        <li>The paragraph mainly discusses [MAIN TOPIC]</li>
                                        <li>Key phrases in <mark>{{Q1}}</mark> like "<mark>[PHRASE 1]</mark>" and "<mark>[PHRASE 2]</mark>" support this heading</li>
                                        <li>Other headings don't capture the central theme of [THEME]</li>
                                    </ul>
                                `);
                            }
                        }
                    ];
                    callback(items);
                }
            });

            // Keyboard shortcuts
            editor.addShortcut('ctrl+shift+c', 'Mark as correct', function () {
                editor.execCommand('mceInsertContent', false, '<span class="correct-answer">' + editor.selection.getContent() + '</span>');
            });

            editor.addShortcut('ctrl+shift+x', 'Mark as incorrect', function () {
                editor.execCommand('mceInsertContent', false, '<span class="incorrect-answer">' + editor.selection.getContent() + '</span>');
            });

            // Sync content back to textarea before form submission
            editor.on('change', function () {
                explanationField.value = editor.getContent();
            });
        }
    });
}

// Initialize Event Listeners
function initializeEventListeners() {
    // Question Type Change
    const questionTypeSelect = document.getElementById('question_type');
    if (questionTypeSelect) {
        // Remove existing listener first
        questionTypeSelect.removeEventListener('change', handleQuestionTypeChange);
        // Then add new one
        questionTypeSelect.addEventListener('change', handleQuestionTypeChange);
    }

    // Add Option Button
    const addOptionBtn = document.getElementById('add-option-btn');
    if (addOptionBtn) {
        // Create a new function reference to avoid duplicate listeners
        const addOptionHandler = () => addOption();
        addOptionBtn.removeEventListener('click', addOptionHandler);
        addOptionBtn.addEventListener('click', addOptionHandler);
    }

    // Form Submit is already handled in main DOMContentLoaded

    // ESC key handlers - use named function to prevent duplicates
    const escHandler = function (e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    };

    document.removeEventListener('keydown', escHandler);
    document.addEventListener('keydown', escHandler);
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

    // Update marker dropdown visibility
    PassageMarker.updateMarkerDropdown();
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
    console.log('Form submission started...');

    const questionType = document.getElementById('question_type').value;

    // Validate question type
    if (!questionType) {
        alert('Please select a question type');
        return false;
    }

    // Save all TinyMCE editors content
    if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
        console.log('TinyMCE content saved');
    }

    // Handle different question types
    if (questionType === 'passage') {
        // For passage type
        if (!handlePassageSubmission()) {
            return false;
        }
    } else {
        // For regular questions
        if (!handleRegularQuestionSubmission()) {
            return false;
        }
    }

    // Handle explanation editor specially
    if (window.explanationEditor) {
        const explanationField = document.querySelector('textarea[name="explanation"]');
        if (explanationField) {
            explanationField.value = window.explanationEditor.getContent();
            console.log('Explanation content synced');
        }
    }

    // Sync any other TinyMCE content
    syncAllEditorContent();

    // Log form data for debugging
    logFormData(this);

    console.log('Submitting form...');
    // Allow form to submit
    this.submit();
}

// Handle passage submission
function handlePassageSubmission() {
    console.log('Handling passage submission...');

    if (!window.passageEditor) {
        alert('Passage editor not initialized');
        return false;
    }

    const passageContent = window.passageEditor.getContent();

    if (!passageContent || passageContent.trim() === '') {
        alert('Please enter passage text');
        return false;
    }

    // Ensure content field has passage content
    let contentField = document.getElementById('content');
    if (!contentField) {
        // Create textarea if not exists
        contentField = document.createElement('textarea');
        contentField.id = 'content';
        contentField.name = 'content';
        contentField.style.display = 'none';
        document.getElementById('questionForm').appendChild(contentField);
    }
    contentField.value = passageContent;

    // Also set in passage_text field if exists
    const passageTextField = document.querySelector('input[name="passage_text"], textarea[name="passage_text"]');
    if (passageTextField) {
        passageTextField.value = passageContent;
    }

    console.log('Passage content set, length:', passageContent.length);
    return true;
}

// Handle regular question submission
function handleRegularQuestionSubmission() {
    console.log('Handling regular question submission...');

    let content = '';

    // Try to get content from TinyMCE editor
    if (window.editor) {
        content = window.editor.getContent();
    } else {
        // Fallback to textarea
        const contentField = document.getElementById('content');
        if (contentField) {
            content = contentField.value;
        }
    }

    if (!content || content.trim() === '') {
        alert('Please enter question content');
        return false;
    }

    // Update content field
    const contentField = document.getElementById('content');
    if (contentField) {
        contentField.value = content;
    }

    console.log('Question content set, length:', content.length);
    return true;
}

// Sync all TinyMCE editor content
function syncAllEditorContent() {
    if (typeof tinymce === 'undefined') return;

    // Get all TinyMCE editors
    const editors = tinymce.get();

    editors.forEach(editor => {
        const editorId = editor.id;
        const textarea = document.getElementById(editorId);

        if (textarea) {
            const content = editor.getContent();
            textarea.value = content;
            console.log(`Synced editor ${editorId}, content length:`, content.length);
        }
    });
}

// Log form data for debugging
function logFormData(form) {
    console.log('=== Form Data Debug ===');
    const formData = new FormData(form);

    for (let [key, value] of formData.entries()) {
        if (typeof value === 'string' && value.length > 100) {
            console.log(`${key}: [${value.length} characters]`);
        } else {
            console.log(`${key}:`, value);
        }
    }

    // Also check hidden fields
    const hiddenInputs = form.querySelectorAll('input[type="hidden"], textarea[style*="display: none"]');
    console.log('Hidden fields count:', hiddenInputs.length);

    hiddenInputs.forEach(input => {
        if (input.value) {
            console.log(`Hidden field ${input.name}: [${input.value.length} characters]`);
        }
    });
}

// Single DOM Ready Handler
document.addEventListener('DOMContentLoaded', function () {
    console.log('Question create form initializing...');

    // Get form reference
    const questionForm = document.getElementById('questionForm');
    if (!questionForm) {
        console.error('Question form not found!');
        return;
    }

    // Remove any existing listeners
    questionForm.removeEventListener('submit', handleFormSubmit);

    // Add new submit listener
    questionForm.addEventListener('submit', handleFormSubmit);
    console.log('Form submit handler attached');

    // Also handle submit button clicks
    const submitButtons = questionForm.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            // Let the form handle submission
            console.log('Submit button clicked:', this.value);
        });
    });

    // Initialize all components
    initializeEventListeners();
    initializeQuestionNumbering();
    initializeFileUpload();
    initializeRelatedTopics();

    // Add marker field to form
    addMarkerField();

    // Delay TinyMCE initialization to ensure DOM is ready
    setTimeout(() => {
        initializeTinyMCE();
        initializeExplanationEditor();
    }, 100);
});

// Backup save function for direct button onclick
window.saveQuestion = function (action) {
    console.log('Direct save called with action:', action);

    const form = document.getElementById('questionForm');
    if (!form) {
        alert('Form not found!');
        return;
    }

    // Set action if provided
    if (action) {
        let actionInput = form.querySelector('input[name="action"]');
        if (!actionInput) {
            actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            form.appendChild(actionInput);
        }
        actionInput.value = action;
    }

    // Trigger form submission
    const submitEvent = new Event('submit', { cancelable: true });
    form.dispatchEvent(submitEvent);
};

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
    PassageMarker.closeDialog();
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fade-out {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(10px); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    .animate-fade-out {
        animation: fade-out 0.3s ease-out;
    }
    
    /* Passage toolbar styles */
    .passage-toolbar {
        padding: 10px;
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        margin-bottom: 10px;
    }
    
    /* Marker count display */
    #marker-count {
        padding: 8px 12px;
        background-color: #eff6ff;
        border-radius: 6px;
        font-size: 14px;
        color: #1e40af;
    }
    
    /* Explanation editor styles */
    .explanation-tinymce {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        overflow: hidden;
    }
    
    /* Markers panel */
    #markers-panel {
        background-color: #eff6ff;
        border: 1px solid #dbeafe;
    }
    
    #markers-list:empty::after {
        content: 'No markers added yet';
        color: #9ca3af;
        font-size: 14px;
    }
`;
document.head.appendChild(style);