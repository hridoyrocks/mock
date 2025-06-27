// resources/js/reading-test.js - FIXED VERSION

// ========== Global Variables ==========
let currentColorPicker = null;
let selectedTextRange = null;
let scrollTimeout;
let annotationSystem = null;

// ========== Navigation Handler ==========
const NavigationHandler = {
    init() {
        this.setupPartNavigation();
        this.setupQuestionNavigation();
        this.setupReviewCheckbox();
    },

    setupPartNavigation() {
        const partButtons = document.querySelectorAll('.part-btn');
        const passageContainers = document.querySelectorAll('.passage-container');
        const questionParts = document.querySelectorAll('.part-questions');

        partButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Update active button
                partButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const partNumber = this.dataset.part;

                // Hide all question parts
                questionParts.forEach(part => {
                    part.style.display = 'none';
                });

                // Show questions for this part
                const targetQuestionPart = document.querySelector(`.part-questions[data-part="${partNumber}"]`);
                if (targetQuestionPart) {
                    targetQuestionPart.style.display = 'block';
                }

                // Update passage display
                passageContainers.forEach(container => {
                    container.classList.remove('active');
                });

                const partPassage = document.querySelector(`.passage-container[data-part="${partNumber}"]`);
                if (partPassage) {
                    partPassage.classList.add('active');

                    // Reinitialize annotation system for new passage
                    if (annotationSystem && annotationSystem.reinitializeForContainer) {
                        annotationSystem.reinitializeForContainer(partPassage);
                    }
                }

                // Find first question of this part
                const firstQuestionOfPart = document.querySelector(`.number-btn[data-part="${partNumber}"]`);
                if (firstQuestionOfPart) {
                    firstQuestionOfPart.click();
                }
            });
        });
    },

    setupQuestionNavigation() {
        const navButtons = document.querySelectorAll('.number-btn');

        navButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Update active button
                navButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const questionId = this.dataset.question;
                const blankIndex = this.dataset.blank;
                const partNumber = this.dataset.part;
                const questionElement = document.getElementById(`question-${questionId}`);

                if (questionElement) {
                    // Smooth scroll to question
                    questionElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Focus on specific blank if needed
                    if (blankIndex) {
                        const inputs = questionElement.querySelectorAll('.simple-blank, .simple-dropdown');
                        if (inputs[blankIndex - 1]) {
                            setTimeout(() => inputs[blankIndex - 1].focus(), 300);
                        }
                    }

                    // Update part if needed
                    if (partNumber) {
                        const currentActivePart = document.querySelector('.part-btn.active');
                        if (!currentActivePart || currentActivePart.dataset.part !== partNumber) {
                            const partBtn = document.querySelector(`.part-btn[data-part="${partNumber}"]`);
                            if (partBtn) partBtn.click();
                        }
                    }
                }

                // Update review checkbox
                const reviewCheckbox = document.getElementById('review-checkbox');
                reviewCheckbox.checked = this.classList.contains('flagged');
            });
        });
    },

    setupReviewCheckbox() {
        const reviewCheckbox = document.getElementById('review-checkbox');
        reviewCheckbox.addEventListener('change', function () {
            const currentQuestion = document.querySelector('.number-btn.active');
            if (currentQuestion) {
                if (this.checked) {
                    currentQuestion.classList.add('flagged');
                } else {
                    currentQuestion.classList.remove('flagged');
                }
            }
        });
    }
};

// ========== Answer Manager ==========
const AnswerManager = {
    init(attemptId) {
        this.attemptId = attemptId;
        this.setupAnswerTracking();
        this.loadSavedAnswers();
        this.startAutoSave();
    },

    setupAnswerTracking() {
        // Track fill-in-the-blanks
        document.querySelectorAll('.gap-input, .gap-dropdown').forEach(input => {
            input.addEventListener('change', () => this.trackAnswer(input));
            input.addEventListener('blur', () => this.trackAnswer(input));

            // Auto-width for blanks
            if (input.classList.contains('simple-blank')) {
                input.addEventListener('input', function () {
                    const length = this.value.length;
                    this.style.width = length > 8 ? (length * 9) + 'px' : '120px';
                });
            }

            // Tab navigation
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const allInputs = document.querySelectorAll('.simple-blank, .simple-dropdown');
                    const currentIndex = Array.from(allInputs).indexOf(input);
                    const nextIndex = e.shiftKey ? currentIndex - 1 : currentIndex + 1;

                    if (nextIndex >= 0 && nextIndex < allInputs.length) {
                        allInputs[nextIndex].focus();
                    }
                }
            });
        });

        // Track regular questions
        document.querySelectorAll('input[type="radio"], input[type="text"]:not(.simple-blank), select:not(.simple-dropdown)').forEach(input => {
            input.addEventListener('change', () => this.trackAnswer(input));
        });
    },

    trackAnswer(input) {
        const questionNumber = input.dataset.questionNumber;
        if (questionNumber) {
            const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
            if (navButton) {
                if (input.value.trim()) {
                    navButton.classList.add('answered');
                } else {
                    navButton.classList.remove('answered');
                }
            }
        }
        this.saveAllAnswers();
    },

    saveAllAnswers() {
        const formData = new FormData(document.getElementById('reading-form'));
        const answers = {};

        for (let [key, value] of formData.entries()) {
            if (key.startsWith('answers[') && value) {
                answers[key] = value;
            }
        }

        localStorage.setItem(`testAnswers_${this.attemptId}`, JSON.stringify(answers));
    },

    loadSavedAnswers() {
        try {
            const savedAnswers = localStorage.getItem(`testAnswers_${this.attemptId}`);

            if (savedAnswers) {
                const answers = JSON.parse(savedAnswers);

                Object.keys(answers).forEach(key => {
                    const value = answers[key];
                    const input = document.querySelector(`[name="${key}"]`);

                    if (input) {
                        if (input.type === 'radio') {
                            const radio = document.querySelector(`[name="${key}"][value="${value}"]`);
                            if (radio) {
                                radio.checked = true;
                                this.trackAnswer(radio);
                            }
                        } else {
                            input.value = value;
                            this.trackAnswer(input);
                        }
                    }
                });
            }
        } catch (e) {
            console.error('Error restoring saved answers:', e);
        }
    },

    startAutoSave() {
        setInterval(() => this.saveAllAnswers(), 30000); // Every 30 seconds
    }
};

// ========== Submit Handler ==========
const SubmitHandler = {
    init() {
        this.setupSubmitModal();
    },

    setupSubmitModal() {
        const submitTestBtn = document.getElementById('submit-test-btn');
        const submitModal = document.getElementById('submit-modal');
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const answeredCountSpan = document.getElementById('answered-count');
        const submitButton = document.getElementById('submit-button');

        if (submitTestBtn) {
            submitTestBtn.addEventListener('click', () => {
                const answeredCount = document.querySelectorAll('.number-btn.answered').length;
                answeredCountSpan.textContent = answeredCount;
                submitModal.style.display = 'flex';
            });
        }

        if (confirmSubmitBtn) {
            confirmSubmitBtn.addEventListener('click', () => {
                if (window.UniversalTimer) {
                    window.UniversalTimer.stop();
                }
                AnswerManager.saveAllAnswers();

                // Save annotations before submit
                if (annotationSystem) {
                    annotationSystem.storage.save();
                }

                submitButton.click();
            });
        }

        if (cancelSubmitBtn) {
            cancelSubmitBtn.addEventListener('click', () => {
                submitModal.style.display = 'none';
            });
        }
    }
};

// ========== Help Guide ==========
const HelpGuide = {
    init() {
        this.setupEventListeners();
    },

    setupEventListeners() {
        const helpButton = document.getElementById('help-button');
        const closeBtn = document.getElementById('help-close-btn');
        const modal = document.getElementById('help-modal');

        if (helpButton) {
            helpButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.open();
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) this.close();
            });
        }
    },

    open() {
        const modal = document.getElementById('help-modal');
        if (modal) {
            modal.style.display = 'flex';
            this.loadContent('overview');
        }
    },

    close() {
        const modal = document.getElementById('help-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    },

    loadContent(section) {
        const contentArea = document.getElementById('help-content');
        if (!contentArea) return;

        contentArea.innerHTML = `
            <div class="help-section">
                <h3>IELTS Reading Test Guide</h3>
                <p>Welcome to the IELTS Computer-Delivered Reading Test.</p>
                <ul>
                    <li><strong>Duration:</strong> 60 minutes</li>
                    <li><strong>Questions:</strong> 40 questions in total</li>
                    <li><strong>Sections:</strong> 3 reading passages</li>
                </ul>
                
                <h4>Test Interface</h4>
                <ul>
                    <li><strong>Left side:</strong> Reading passage</li>
                    <li><strong>Right side:</strong> Questions</li>
                </ul>
                
                <h4>New: Text Annotations</h4>
                <ul>
                    <li><strong>Notes:</strong> Select text and click "Note" to add personal notes</li>
                    <li><strong>Highlights:</strong> Select text and click "Highlight" to color important parts</li>
                    <li><strong>View Notes:</strong> Click the "Notes" button to see all your annotations</li>
                </ul>
            </div>
        `;
    }
};

// ========== Simple Split Divider ==========
const SimpleSplitDivider = {
    init() {
        // Check if desktop
        if (window.innerWidth <= 1024) return;

        const divider = document.getElementById('split-divider');
        const passageSection = document.querySelector('.passage-section');
        const container = document.querySelector('.content-area');

        if (!divider || !passageSection) return;

        let isResizing = false;

        // Mouse down - start resize
        divider.addEventListener('mousedown', (e) => {
            isResizing = true;
            document.body.classList.add('dragging');
            e.preventDefault();
        });

        // Mouse move - resize panels
        document.addEventListener('mousemove', (e) => {
            if (!isResizing) return;

            const containerRect = container.getBoundingClientRect();
            const percentage = ((e.clientX - containerRect.left) / containerRect.width) * 100;

            // Limit between 25% and 75%
            if (percentage >= 25 && percentage <= 75) {
                passageSection.style.flex = `0 0 ${percentage}%`;
            }
        });

        // Mouse up - stop resize
        document.addEventListener('mouseup', () => {
            if (isResizing) {
                isResizing = false;
                document.body.classList.remove('dragging');

                // Save position
                const currentFlex = passageSection.style.flex;
                if (currentFlex) {
                    localStorage.setItem('readingSplitPosition', currentFlex);
                }
            }
        });

        // Load saved position
        const savedPosition = localStorage.getItem('readingSplitPosition');
        if (savedPosition) {
            passageSection.style.flex = savedPosition;
        }

        // Double click to reset
        divider.addEventListener('dblclick', () => {
            passageSection.style.flex = '0 0 50%';
            localStorage.setItem('readingSplitPosition', '0 0 50%');
        });
    }
};

// ========== Simple Annotation System ==========
const SimpleAnnotationSystem = {
    init() {
        this.currentMenu = null;
        this.currentModal = null;
        this.currentRange = null;

        this.createNoteModal();
        this.createNotesPanel();
        this.setupAnnotationHandlers();
        this.restoreAnnotations();
        this.addNotesButton();
    },

    createNoteModal() {
        const modal = document.createElement('div');
        modal.id = 'note-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100000;
        `;

        modal.innerHTML = `
            <div style="
                background: white;
                border-radius: 12px;
                width: 90%;
                max-width: 500px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            ">
                <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111827;">Add Note</h3>
                    <p style="margin: 8px 0 0 0; font-size: 14px; color: #6b7280;" id="selected-text-preview"></p>
                </div>
                <div style="padding: 20px;">
                    <textarea 
                        id="note-textarea"
                        placeholder="Type your note here..."
                        style="
                            width: 100%;
                            min-height: 120px;
                            padding: 12px;
                            border: 1px solid #e5e7eb;
                            border-radius: 8px;
                            font-size: 15px;
                            resize: vertical;
                            font-family: inherit;
                            box-sizing: border-box;
                        "
                    ></textarea>
                    <div style="margin-top: 8px; text-align: right; font-size: 13px; color: #9ca3af;">
                        <span id="char-count">0</span>/500
                    </div>
                </div>
                <div style="
                    padding: 16px 20px;
                    background: #f9fafb;
                    border-top: 1px solid #e5e7eb;
                    display: flex;
                    justify-content: flex-end;
                    gap: 12px;
                    border-radius: 0 0 12px 12px;
                ">
                    <button id="close-note-modal-btn" style="
                        padding: 8px 20px;
                        border: 1px solid #e5e7eb;
                        background: white;
                        border-radius: 6px;
                        font-size: 14px;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">Cancel</button>
                    <button id="save-note-btn" style="
                        padding: 8px 20px;
                        background: #3b82f6;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        font-size: 14px;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">Save Note</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        this.noteModal = modal;

        // Setup event listeners
        const textarea = modal.querySelector('#note-textarea');
        const charCount = modal.querySelector('#char-count');
        textarea.addEventListener('input', () => {
            const count = textarea.value.length;
            charCount.textContent = count;
            if (count > 500) {
                textarea.value = textarea.value.substring(0, 500);
                charCount.textContent = 500;
            }
        });

        document.getElementById('close-note-modal-btn').addEventListener('click', () => {
            this.closeNoteModal();
        });

        document.getElementById('save-note-btn').addEventListener('click', () => {
            this.saveNote();
        });
    },

    createNotesPanel() {
        const panel = document.createElement('div');
        panel.id = 'notes-panel';
        panel.style.cssText = `
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100%;
            background: white;
            box-shadow: -4px 0 6px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease-out;
            z-index: 99998;
            display: flex;
            flex-direction: column;
        `;

        panel.innerHTML = `
            <div style="
                padding: 20px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
            ">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600; flex: 1;">Your Notes</h3>
                <button id="close-notes-panel-btn" style="
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    color: #6b7280;
                    padding: 0;
                    width: 32px;
                    height: 32px;
                ">×</button>
            </div>
            <div id="notes-list" style="
                flex: 1;
                overflow-y: auto;
                padding: 16px;
            "></div>
        `;

        document.body.appendChild(panel);
        this.notesPanel = panel;

        document.getElementById('close-notes-panel-btn').addEventListener('click', () => {
            this.closeNotesPanel();
        });
    },

    closeNoteModal() {
        this.noteModal.style.display = 'none';
        document.getElementById('note-textarea').value = '';
    },

    saveNote() {
        const noteText = document.getElementById('note-textarea').value.trim();
        if (noteText && this.currentRange) {
            const selectedText = this.currentRange.toString();

            // Apply note styling
            const span = document.createElement('span');
            span.style.cssText = 'background-color: #fee2e2; color: #dc2626; border-bottom: 2px solid #dc2626; cursor: pointer;';
            span.textContent = selectedText;
            span.title = noteText;
            span.dataset.note = noteText;
            span.dataset.noteId = Date.now();

            // Add click handler to show note
            span.onclick = () => this.showNoteTooltip(span, noteText);

            try {
                this.currentRange.deleteContents();
                this.currentRange.insertNode(span);
            } catch (error) {
                console.error('Error applying note:', error);
            }

            // Save to localStorage
            this.saveAnnotation('note', selectedText, noteText);

            this.closeNoteModal();
            window.getSelection().removeAllRanges();
            this.hideMenu();
        }
    },

    closeNotesPanel() {
        this.notesPanel.style.right = '-400px';
    },

    openNotesPanel() {
        this.notesPanel.style.right = '0';
        this.updateNotesList();
    },

    showNoteTooltip(element, noteText) {
        // Remove existing tooltip
        const existingTooltip = document.getElementById('note-tooltip');
        if (existingTooltip) existingTooltip.remove();

        const tooltip = document.createElement('div');
        tooltip.id = 'note-tooltip';
        tooltip.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            z-index: 99999;
        `;

        tooltip.innerHTML = `
            <div style="font-size: 14px; color: #374151; margin-bottom: 8px;">${noteText}</div>
            <div style="font-size: 12px; color: #9ca3af;">Click outside to close</div>
        `;

        document.body.appendChild(tooltip);

        const rect = element.getBoundingClientRect();
        tooltip.style.top = `${rect.bottom + window.scrollY + 5}px`;
        tooltip.style.left = `${rect.left + window.scrollX}px`;

        // Remove on click outside
        setTimeout(() => {
            document.addEventListener('click', function removeTooltip(e) {
                if (!tooltip.contains(e.target) && e.target !== element) {
                    tooltip.remove();
                    document.removeEventListener('click', removeTooltip);
                }
            });
        }, 100);
    },

    updateNotesList() {
        const notesList = document.getElementById('notes-list');
        const attemptId = window.testConfig?.attemptId || 'test';
        const annotations = JSON.parse(localStorage.getItem(`annotations_${attemptId}`) || '[]');
        const notes = annotations.filter(a => a.type === 'note');

        if (notes.length === 0) {
            notesList.innerHTML = `
                <div style="text-align: center; color: #9ca3af; padding: 40px;">
                    <p>No notes yet!</p>
                    <p style="font-size: 14px; margin-top: 8px;">Select text and add notes to see them here.</p>
                </div>
            `;
        } else {
            notesList.innerHTML = notes.map((note, index) => `
                <div class="note-item-wrapper" style="
                    background: #f9fafb;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 16px;
                    margin-bottom: 12px;
                    position: relative;
                " data-note-text="${encodeURIComponent(note.text)}" data-note-timestamp="${note.timestamp}">
                    <button class="delete-note-btn" style="
                        position: absolute;
                        top: 12px;
                        right: 12px;
                        background: #fee2e2;
                        border: none;
                        border-radius: 4px;
                        padding: 4px 8px;
                        cursor: pointer;
                        color: #dc2626;
                        font-size: 12px;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fee2e2'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                    <div style="
                        font-size: 13px;
                        color: #6b7280;
                        font-style: italic;
                        margin-bottom: 8px;
                        padding: 8px;
                        background: white;
                        border-radius: 4px;
                        margin-right: 60px;
                    ">"${note.text.substring(0, 100)}${note.text.length > 100 ? '...' : ''}"</div>
                    <div style="font-size: 14px; color: #111827; line-height: 1.5;">${note.data}</div>
                    <div style="
                        margin-top: 12px;
                        font-size: 12px;
                        color: #9ca3af;
                    ">${new Date(note.timestamp).toLocaleString()}</div>
                </div>
            `).join('');

            // Add delete event listeners after rendering
            notesList.querySelectorAll('.delete-note-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const wrapper = btn.closest('.note-item-wrapper');
                    const text = decodeURIComponent(wrapper.dataset.noteText);
                    const timestamp = wrapper.dataset.noteTimestamp;
                    this.deleteNote(text, timestamp);
                });
            });
        }
    },

    setupAnnotationHandlers() {
        // Text selection handler
        document.addEventListener('mouseup', (e) => {
            // Skip if clicking on annotation menu or modal
            if (e.target.closest('#annotation-menu') ||
                e.target.closest('#note-modal') ||
                e.target.closest('#notes-panel')) {
                return;
            }

            setTimeout(() => {
                const selection = window.getSelection();
                const selectedText = selection.toString().trim();

                if (selectedText && selectedText.length >= 3) {
                    const range = selection.getRangeAt(0);
                    const rect = range.getBoundingClientRect();
                    this.currentRange = range;
                    this.showMenu(rect, selectedText);
                } else {
                    this.hideMenu();
                }
            }, 10);
        });

        // Hide menu on document click
        document.addEventListener('mousedown', (e) => {
            if (this.currentMenu && !this.currentMenu.contains(e.target)) {
                this.hideMenu();
            }
        });
    },

    showMenu(rect, selectedText) {
        this.hideMenu();

        const menu = document.createElement('div');
        menu.id = 'annotation-menu';
        menu.style.cssText = `
            position: fixed;
            top: ${rect.top - 50}px;
            left: ${rect.left + (rect.width / 2) - 80}px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 4px;
            display: flex;
            gap: 4px;
            z-index: 99999;
        `;

        // Note button
        const noteBtn = document.createElement('button');
        noteBtn.innerHTML = `
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Note
        `;
        noteBtn.style.cssText = `
            padding: 6px 12px;
            border: none;
            background: #f3f4f6;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        `;

        noteBtn.onmouseover = () => { noteBtn.style.background = '#e5e7eb'; };
        noteBtn.onmouseout = () => { noteBtn.style.background = '#f3f4f6'; };

        noteBtn.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('selected-text-preview').textContent =
                `"${selectedText.substring(0, 50)}${selectedText.length > 50 ? '...' : ''}"`;
            this.noteModal.style.display = 'flex';
            setTimeout(() => {
                document.getElementById('note-textarea').focus();
            }, 100);
        };

        // Highlight button
        const highlightBtn = document.createElement('button');
        highlightBtn.innerHTML = `
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Highlight
        `;
        highlightBtn.style.cssText = noteBtn.style.cssText;
        highlightBtn.onmouseover = () => { highlightBtn.style.background = '#e5e7eb'; };
        highlightBtn.onmouseout = () => { highlightBtn.style.background = '#f3f4f6'; };

        highlightBtn.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();

            // Show color picker
            this.showColorPicker(rect);
        };

        menu.appendChild(noteBtn);
        menu.appendChild(highlightBtn);
        document.body.appendChild(menu);
        this.currentMenu = menu;
    },

    showColorPicker(rect) {
        // Remove existing color picker
        const existingPicker = document.getElementById('color-picker');
        if (existingPicker) existingPicker.remove();

        const colors = [
            { name: 'Yellow', value: '#FFFF00' },
            { name: 'Pink', value: '#fce7f3' },
            { name: 'Blue', value: '#dbeafe' },
            { name: 'Green', value: '#1BFC06' },
            { name: 'Purple', value: '#e9d5ff' },
            { name: 'Orange', value: '#fed7aa' }
        ];

        const picker = document.createElement('div');
        picker.id = 'color-picker';
        picker.style.cssText = `
            position: fixed;
            top: ${rect.top - 50}px;
            left: ${rect.left + (rect.width / 2) - 120}px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 8px;
            display: flex;
            gap: 4px;
            z-index: 100001;
        `;

        colors.forEach(color => {
            const colorBtn = document.createElement('button');
            colorBtn.style.cssText = `
                width: 32px;
                height: 32px;
                background: ${color.value};
                border: 2px solid transparent;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.2s;
            `;
            colorBtn.title = color.name;

            colorBtn.onmouseover = () => {
                colorBtn.style.transform = 'scale(1.1)';
                colorBtn.style.borderColor = '#374151';
            };
            colorBtn.onmouseout = () => {
                colorBtn.style.transform = 'scale(1)';
                colorBtn.style.borderColor = 'transparent';
            };

            colorBtn.onclick = (e) => {
                e.stopPropagation();
                this.applyHighlight(color);
                picker.remove();
            };

            picker.appendChild(colorBtn);
        });

        document.body.appendChild(picker);

        // Remove picker on outside click
        setTimeout(() => {
            document.addEventListener('click', function removePicker(e) {
                if (!picker.contains(e.target)) {
                    picker.remove();
                    document.removeEventListener('click', removePicker);
                }
            });
        }, 100);
    },

    applyHighlight(color) {
        if (this.currentRange) {
            const span = document.createElement('span');
            span.style.backgroundColor = color.value;
            span.style.cursor = 'pointer';
            span.textContent = this.currentRange.toString();
            span.dataset.highlight = color.name;
            span.title = `${color.name} highlight - Click to remove`;

            span.onclick = function (evt) {
                evt.stopPropagation();
                const text = this.textContent;
                this.style.transition = 'background-color 0.3s ease';
                this.style.backgroundColor = 'transparent';

                setTimeout(() => {
                    this.replaceWith(document.createTextNode(text));
                    SimpleAnnotationSystem.removeAnnotation('highlight', text);
                }, 300);
            };

            try {
                this.currentRange.deleteContents();
                this.currentRange.insertNode(span);
                this.saveAnnotation('highlight', span.textContent, color.name);
            } catch (error) {
                console.error('Error applying highlight:', error);
            }

            this.hideMenu();
            window.getSelection().removeAllRanges();
        }
    },

    deleteNote(text, timestamp) {
        if (confirm('Are you sure you want to delete this note?')) {
            const attemptId = window.testConfig?.attemptId || 'test';
            const key = `annotations_${attemptId}`;
            let annotations = JSON.parse(localStorage.getItem(key) || '[]');

            // Remove the specific note
            annotations = annotations.filter(a => !(a.type === 'note' && a.text === text && a.timestamp === timestamp));
            localStorage.setItem(key, JSON.stringify(annotations));

            // Remove from DOM
            const noteElements = document.querySelectorAll('span[data-note]');
            noteElements.forEach(el => {
                if (el.textContent === text) {
                    const parent = el.parentNode;
                    parent.replaceChild(document.createTextNode(text), el);
                }
            });

            // Update notes list
            this.updateNotesList();
        }
    },

    hideMenu() {
        if (this.currentMenu) {
            this.currentMenu.remove();
            this.currentMenu = null;
        }
    },

    saveAnnotation(type, text, data) {
        const attemptId = window.testConfig?.attemptId || 'test';
        const key = `annotations_${attemptId}`;
        const annotations = JSON.parse(localStorage.getItem(key) || '[]');

        annotations.push({
            type: type,
            text: text,
            data: data,
            timestamp: new Date().toISOString()
        });

        localStorage.setItem(key, JSON.stringify(annotations));
    },

    removeAnnotation(type, text) {
        const attemptId = window.testConfig?.attemptId || 'test';
        const key = `annotations_${attemptId}`;
        let annotations = JSON.parse(localStorage.getItem(key) || '[]');

        annotations = annotations.filter(a => !(a.type === type && a.text === text));
        localStorage.setItem(key, JSON.stringify(annotations));
    },

    addNotesButton() {
        const navRight = document.querySelector('.nav-right');
        if (navRight && !document.getElementById('view-notes-btn')) {
            const notesBtn = document.createElement('button');
            notesBtn.id = 'view-notes-btn';
            notesBtn.innerHTML = `
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Notes
            `;
            notesBtn.style.cssText = `
                padding: 8px 16px;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                cursor: pointer;
                margin-right: 12px;
                font-size: 14px;
                display: flex;
                align-items: center;
                transition: all 0.2s;
            `;
            notesBtn.onmouseover = () => { notesBtn.style.borderColor = '#3b82f6'; };
            notesBtn.onmouseout = () => { notesBtn.style.borderColor = '#e5e7eb'; };
            notesBtn.onclick = () => this.openNotesPanel();

            const submitBtn = navRight.querySelector('.submit-test-button');
            navRight.insertBefore(notesBtn, submitBtn);
        }
    },

    findAndStyleText(searchText, styleCallback) {
        const passages = document.querySelectorAll('.passage-content, .passage-container');

        passages.forEach(passage => {
            const walker = document.createTreeWalker(
                passage,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );

            let node;
            while (node = walker.nextNode()) {
                const text = node.textContent;
                const index = text.indexOf(searchText);

                if (index !== -1) {
                    const span = document.createElement('span');
                    const parent = node.parentNode;

                    // Split the text node
                    const before = document.createTextNode(text.substring(0, index));
                    const after = document.createTextNode(text.substring(index + searchText.length));

                    // Apply styling
                    span.textContent = searchText;
                    styleCallback(span);

                    // Replace in DOM
                    parent.insertBefore(before, node);
                    parent.insertBefore(span, node);
                    parent.insertBefore(after, node);
                    parent.removeChild(node);

                    break;
                }
            }
        });
    },

    restoreAnnotations() {
        const attemptId = window.testConfig?.attemptId || 'test';
        const annotations = JSON.parse(localStorage.getItem(`annotations_${attemptId}`) || '[]');

        annotations.forEach(annotation => {
            if (annotation.type === 'note') {
                this.findAndStyleText(annotation.text, (span) => {
                    span.style.cssText = 'background-color: #fee2e2; color: #dc2626; border-bottom: 2px solid #dc2626; cursor: pointer;';
                    span.dataset.note = annotation.data;
                    span.dataset.noteId = Date.now();
                    span.onclick = () => this.showNoteTooltip(span, annotation.data);
                });
            } else if (annotation.type === 'highlight') {
                const colors = {
                    'Yellow': '#fef3c7',
                    'Pink': '#fce7f3',
                    'Blue': '#dbeafe',
                    'Green': '#d1fae5',
                    'Purple': '#e9d5ff',
                    'Orange': '#fed7aa'
                };
                this.findAndStyleText(annotation.text, (span) => {
                    span.style.backgroundColor = colors[annotation.data] || '#fef3c7';
                    span.style.cursor = 'pointer';
                    span.title = 'Click to remove highlight';
                    span.onclick = function (evt) {
                        evt.stopPropagation();
                        const text = this.textContent;
                        this.style.transition = 'background-color 0.3s ease';
                        this.style.backgroundColor = 'transparent';
                        setTimeout(() => {
                            this.replaceWith(document.createTextNode(text));
                            SimpleAnnotationSystem.removeAnnotation('highlight', text);
                        }, 300);
                    };
                });
            }
        });
    }
};

// ========== Initialize Everything ==========
document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing Reading Test System...');

    // Initialize all modules
    NavigationHandler.init();
    AnswerManager.init(window.testConfig?.attemptId || 'test');
    SubmitHandler.init();
    HelpGuide.init();
    SimpleSplitDivider.init();
    SimpleAnnotationSystem.init();

    // Restore annotations after a delay
    setTimeout(() => {
        SimpleAnnotationSystem.restoreAnnotations();
    }, 1000);

    console.log('Reading Test System Ready!');
});