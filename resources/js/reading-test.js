
import TextAnnotationSystem from './modules/TextAnnotationSystem';

// ========== Global Variables ==========
let currentColorPicker = null;
let selectedTextRange = null;
let scrollTimeout;
let annotationSystem = null; // Add this

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

        submitTestBtn.addEventListener('click', () => {
            const answeredCount = document.querySelectorAll('.number-btn.answered').length;
            answeredCountSpan.textContent = answeredCount;
            submitModal.style.display = 'flex';
        });

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

        cancelSubmitBtn.addEventListener('click', () => {
            submitModal.style.display = 'none';
        });
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

// ========== Initialize Everything ==========


// Replace the simple annotation system in your reading-test.js with this professional version

document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Initializing Professional Annotation System...');

    // Global references
    let currentMenu = null;
    let currentModal = null;
    let currentRange = null;

    // Create modal HTML
    const createNoteModal = () => {
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
            animation: fadeIn 0.2s ease-out;
        `;

        modal.innerHTML = `
            <div style="
                background: white;
                border-radius: 12px;
                width: 90%;
                max-width: 500px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
                animation: slideUp 0.3s ease-out;
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
                    <button onclick="closeNoteModal()" style="
                        padding: 8px 20px;
                        border: 1px solid #e5e7eb;
                        background: white;
                        border-radius: 6px;
                        font-size: 14px;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">Cancel</button>
                    <button onclick="saveNote()" style="
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

        // Add character counter
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

        return modal;
    };

    // Create notes panel
    const createNotesPanel = () => {
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
                justify-content: between;
                align-items: center;
            ">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600; flex: 1;">Your Notes</h3>
                <button onclick="closeNotesPanel()" style="
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
        return panel;
    };

    // Initialize modals
    const noteModal = createNoteModal();
    const notesPanel = createNotesPanel();

    // Global functions for modal
    window.closeNoteModal = () => {
        noteModal.style.display = 'none';
        document.getElementById('note-textarea').value = '';
    };

    window.saveNote = () => {
        const noteText = document.getElementById('note-textarea').value.trim();
        if (noteText && currentRange) {
            const selectedText = currentRange.toString();

            // Apply note styling
            const span = document.createElement('span');
            span.style.cssText = 'background-color: #fee2e2; color: #dc2626; border-bottom: 2px solid #dc2626; cursor: pointer;';
            span.textContent = selectedText;
            span.title = noteText;
            span.dataset.note = noteText;
            span.dataset.noteId = Date.now();

            // Add click handler to show note
            span.onclick = () => showNoteTooltip(span, noteText);

            try {
                currentRange.deleteContents();
                currentRange.insertNode(span);
            } catch (error) {
                console.error('Error applying note:', error);
            }

            // Save to localStorage
            saveAnnotation('note', selectedText, noteText);

            closeNoteModal();
            window.getSelection().removeAllRanges();
            hideMenu();
        }
    };

    window.closeNotesPanel = () => {
        notesPanel.style.right = '-400px';
    };

    window.openNotesPanel = () => {
        notesPanel.style.right = '0';
        updateNotesList();
    };

    // Show note tooltip
    const showNoteTooltip = (element, noteText) => {
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
            animation: fadeIn 0.2s ease-out;
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
    };

    // Update notes list
    const updateNotesList = () => {
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
                <div style="
                    background: #f9fafb;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 16px;
                    margin-bottom: 12px;
                ">
                    <div style="
                        font-size: 13px;
                        color: #6b7280;
                        font-style: italic;
                        margin-bottom: 8px;
                        padding: 8px;
                        background: white;
                        border-radius: 4px;
                    ">"${note.text.substring(0, 100)}${note.text.length > 100 ? '...' : ''}"</div>
                    <div style="font-size: 14px; color: #111827; line-height: 1.5;">${note.data}</div>
                    <div style="
                        margin-top: 12px;
                        font-size: 12px;
                        color: #9ca3af;
                    ">${new Date(note.timestamp).toLocaleString()}</div>
                </div>
            `).join('');
        }
    };

    // Add selection handler
    document.addEventListener('mouseup', function (e) {
        setTimeout(() => {
            const selection = window.getSelection();
            const selectedText = selection.toString().trim();

            if (selectedText && selectedText.length >= 3) {
                const range = selection.getRangeAt(0);
                const rect = range.getBoundingClientRect();
                currentRange = range;
                showMenu(rect, selectedText);
            } else {
                hideMenu();
            }
        }, 10);
    });

    // Show menu function
    function showMenu(rect, selectedText) {
        hideMenu();

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
            animation: fadeIn 0.2s ease-out;
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
            noteModal.style.display = 'flex';
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

            // Direct yellow highlight - no color picker
            if (currentRange) {
                const span = document.createElement('span');
                span.style.backgroundColor = '#fef3c7'; // Yellow
                span.style.cursor = 'pointer';
                span.textContent = currentRange.toString();
                span.dataset.highlight = 'Yellow';
                span.title = 'Click to remove highlight';

                // Add click handler to remove highlight
                span.onclick = function (evt) {
                    evt.stopPropagation();
                    const text = this.textContent;
                    this.style.transition = 'background-color 0.3s ease';
                    this.style.backgroundColor = 'transparent';

                    setTimeout(() => {
                        this.replaceWith(document.createTextNode(text));
                        // Remove from storage
                        removeAnnotation('highlight', text);
                    }, 300);
                };

                try {
                    currentRange.deleteContents();
                    currentRange.insertNode(span);

                    // Save to localStorage
                    saveAnnotation('highlight', span.textContent, 'Yellow');
                    console.log('✅ Yellow highlight applied');
                } catch (error) {
                    console.error('Error applying highlight:', error);
                }

                hideMenu();
                window.getSelection().removeAllRanges();
            }
        };

        menu.appendChild(noteBtn);
        menu.appendChild(highlightBtn);
        document.body.appendChild(menu);
        currentMenu = menu;
    }

    // Show color picker
    function showColorPicker(menu, selectedText) {
        // Remove any existing color picker
        const existingPicker = menu.querySelector('.color-picker-container');
        if (existingPicker) existingPicker.remove();

        const colors = [
            { name: 'Yellow', value: '#fef3c7' },
            { name: 'Red', value: '#fee2e2' },
            { name: 'Blue', value: '#dbeafe' }
        ];

        const colorPicker = document.createElement('div');
        colorPicker.className = 'color-picker-container';
        colorPicker.style.cssText = `
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 4px;
            display: flex;
            gap: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        `;

        colors.forEach(color => {
            const colorBtn = document.createElement('button');
            colorBtn.style.cssText = `
                width: 28px;
                height: 28px;
                background: ${color.value};
                border: 2px solid transparent;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.2s;
            `;
            colorBtn.title = color.name;
            colorBtn.onmouseover = () => { colorBtn.style.borderColor = '#374151'; };
            colorBtn.onmouseout = () => { colorBtn.style.borderColor = 'transparent'; };

            colorBtn.onclick = (e) => {
                e.stopPropagation();
                if (currentRange) {
                    const span = document.createElement('span');
                    span.style.backgroundColor = color.value;
                    span.style.cursor = 'pointer';
                    span.textContent = currentRange.toString();
                    span.dataset.highlight = color.name;

                    // Add click handler to remove highlight
                    span.onclick = function (evt) {
                        evt.stopPropagation();
                        if (confirm('Remove this highlight?')) {
                            const text = this.textContent;
                            const parent = this.parentNode;
                            this.replaceWith(document.createTextNode(text));

                            // Remove from storage
                            removeAnnotation('highlight', text);
                        }
                    };

                    try {
                        currentRange.deleteContents();
                        currentRange.insertNode(span);

                        // Save to localStorage
                        saveAnnotation('highlight', span.textContent, color.name);
                        console.log('✅ Highlight applied:', color.name);
                    } catch (error) {
                        console.error('Error applying highlight:', error);
                    }

                    hideMenu();
                    window.getSelection().removeAllRanges();
                }
            };

            colorPicker.appendChild(colorBtn);
        });

        menu.appendChild(colorPicker);
    }

    // Hide menu
    function hideMenu() {
        if (currentMenu) {
            currentMenu.remove();
            currentMenu = null;
        }
    }

    // Save annotation
    function saveAnnotation(type, text, data) {
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
        console.log('💾 Annotation saved:', type, text);
    }

    // Function to remove annotation from storage
    function removeAnnotation(type, text) {
        const attemptId = window.testConfig?.attemptId || 'test';
        const key = `annotations_${attemptId}`;
        let annotations = JSON.parse(localStorage.getItem(key) || '[]');

        annotations = annotations.filter(a => !(a.type === type && a.text === text));
        localStorage.setItem(key, JSON.stringify(annotations));
        console.log('🗑️ Annotation removed:', type, text);
    }

    // Hide menu on click outside
    document.addEventListener('mousedown', (e) => {
        if (currentMenu && !currentMenu.contains(e.target)) {
            hideMenu();
        }
    });

    // Add Notes button
    const addNotesButton = () => {
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
            notesBtn.onclick = openNotesPanel;

            const submitBtn = navRight.querySelector('.submit-test-button');
            navRight.insertBefore(notesBtn, submitBtn);
        }
    };

    // Add animations CSS
    if (!document.getElementById('annotation-animations')) {
        const style = document.createElement('style');
        style.id = 'annotation-animations';
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { transform: translateY(20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    }

    // Restore annotations on page load
    const restoreAnnotations = () => {
        const attemptId = window.testConfig?.attemptId || 'test';
        const annotations = JSON.parse(localStorage.getItem(`annotations_${attemptId}`) || '[]');

        console.log('📚 Restoring', annotations.length, 'annotations...');

        annotations.forEach(annotation => {
            if (annotation.type === 'note') {
                // Find and style text for notes
                findAndStyleText(annotation.text, (span) => {
                    span.style.cssText = 'background-color: #fee2e2; color: #dc2626; border-bottom: 2px solid #dc2626; cursor: pointer;';
                    span.dataset.note = annotation.data;
                    span.dataset.noteId = Date.now();
                    span.onclick = () => showNoteTooltip(span, annotation.data);
                });
            } else if (annotation.type === 'highlight') {
                // Find and style text for highlights
                const colors = {
                    'Yellow': '#fef3c7',
                    'Red': '#fee2e2',
                    'Blue': '#dbeafe'
                };
                findAndStyleText(annotation.text, (span) => {
                    span.style.backgroundColor = colors[annotation.data] || '#fef3c7';
                });
            }
        });
    };

    // Find text in passages and apply styling
    const findAndStyleText = (searchText, styleCallback) => {
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

                    break; // Found one instance, that's enough
                }
            }
        });
    };

    // Call restore after a delay to ensure passages are loaded
    setTimeout(() => {
        restoreAnnotations();
    }, 1000);

    setTimeout(addNotesButton, 500);
    console.log('✅ Professional Annotation System Ready!');
});