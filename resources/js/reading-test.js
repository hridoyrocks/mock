// resources/js/reading-test.js

// ========== Global Variables ==========
let currentColorPicker = null;
let selectedTextRange = null;
let scrollTimeout;

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
                        const inputs = questionElement.querySelectorAll('.gap-input, .gap-dropdown');
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

// ========== Text Highlighter ==========
const TextHighlighter = {
    init() {
        this.setupHighlighting();
        this.setupEventListeners();
    },

    setupHighlighting() {
        const passageContents = document.querySelectorAll('.passage-content');

        passageContents.forEach(passage => {
            let isSelecting = false;

            passage.addEventListener('mousedown', () => isSelecting = false);
            passage.addEventListener('mousemove', () => isSelecting = true);

            passage.addEventListener('mouseup', (e) => {
                setTimeout(() => {
                    const selection = window.getSelection();
                    const selectedText = selection.toString().trim();

                    if (selectedText.length > 0 && isSelecting) {
                        this.removeColorPicker();
                        selectedTextRange = selection.getRangeAt(0).cloneRange();
                        setTimeout(() => this.showColorPicker(e), 50);
                    }
                }, 10);
            });

            // Click on highlighted text to remove
            passage.addEventListener('click', (e) => {
                if (e.target.classList.contains('highlight-yellow') ||
                    e.target.classList.contains('highlight-green') ||
                    e.target.classList.contains('highlight-blue')) {

                    e.preventDefault();
                    e.stopPropagation();

                    e.target.style.transition = 'background-color 0.3s';
                    e.target.style.backgroundColor = 'transparent';

                    setTimeout(() => {
                        const text = e.target.textContent;
                        e.target.replaceWith(document.createTextNode(text));
                    }, 300);
                }
            });
        });
    },

    showColorPicker(e) {
        const selection = window.getSelection();
        if (!selection.rangeCount) return;

        const range = selection.getRangeAt(0);
        const rect = range.getBoundingClientRect();

        const picker = document.createElement('div');
        picker.className = 'color-picker';
        picker.style.opacity = '0';
        picker.style.transform = 'translateY(5px)';
        picker.innerHTML = `
            <button class="color-btn yellow" data-color="yellow" title="Yellow highlight"></button>
            <button class="color-btn green" data-color="green" title="Green highlight"></button>
            <button class="color-btn blue" data-color="blue" title="Blue highlight"></button>
            <div class="color-btn remove" title="Cancel">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        `;

        document.body.appendChild(picker);

        // Calculate position
        const pickerRect = picker.getBoundingClientRect();
        let top = rect.top - pickerRect.height - 15;
        let left = rect.left + (rect.width / 2) - (pickerRect.width / 2);

        if (top < 10) {
            top = rect.bottom + 15;
            picker.classList.add('bottom');
        }

        if (left < 10) left = 10;
        else if (left + pickerRect.width > window.innerWidth - 10) {
            left = window.innerWidth - pickerRect.width - 10;
        }

        picker.style.position = 'fixed';
        picker.style.top = top + 'px';
        picker.style.left = left + 'px';
        picker.style.zIndex = '9999';

        requestAnimationFrame(() => {
            picker.style.transition = 'opacity 0.2s ease-out, transform 0.2s ease-out';
            picker.style.opacity = '1';
            picker.style.transform = 'translateY(0)';
        });

        currentColorPicker = picker;

        // Add click handlers
        picker.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const color = btn.dataset.color;
                if (color) this.applyHighlight(color);

                this.removeColorPicker();
            });
        });
    },

    applyHighlight(color) {
        if (!selectedTextRange) return;

        try {
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(selectedTextRange);

            const selectedText = selection.toString().trim();
            if (!selectedText) return;

            const span = document.createElement('span');
            span.className = `highlight-${color}`;
            span.style.transition = 'background-color 0.3s ease-in';

            try {
                selectedTextRange.surroundContents(span);
            } catch (e) {
                const contents = selectedTextRange.extractContents();
                span.appendChild(contents);
                selectedTextRange.insertNode(span);
            }

            requestAnimationFrame(() => {
                span.style.backgroundColor = '';
            });

        } catch (e) {
            console.error('Error applying highlight:', e);
        } finally {
            window.getSelection().removeAllRanges();
        }
    },

    removeColorPicker() {
        if (currentColorPicker) {
            currentColorPicker.style.opacity = '0';
            currentColorPicker.style.transform = 'translateY(5px)';

            setTimeout(() => {
                if (currentColorPicker && currentColorPicker.parentNode) {
                    currentColorPicker.remove();
                }
                currentColorPicker = null;
            }, 200);
        }
        selectedTextRange = null;
    },

    setupEventListeners() {
        // Close color picker when clicking elsewhere
        document.addEventListener('mousedown', (e) => {
            if (currentColorPicker && !e.target.closest('.color-picker') && !e.target.closest('.passage-content')) {
                this.removeColorPicker();
            }
        });

        // Close on scroll
        document.addEventListener('scroll', () => {
            if (currentColorPicker) {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => this.removeColorPicker(), 100);
            }
        }, true);

        // ESC to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && currentColorPicker) {
                this.removeColorPicker();
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

            // Tab navigation
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const allInputs = document.querySelectorAll('.gap-input, .gap-dropdown');
                    const currentIndex = Array.from(allInputs).indexOf(input);
                    const nextIndex = e.shiftKey ? currentIndex - 1 : currentIndex + 1;

                    if (nextIndex >= 0 && nextIndex < allInputs.length) {
                        allInputs[nextIndex].focus();
                    }
                }
            });
        });

        // Track regular questions
        document.querySelectorAll('input[type="radio"], input[type="text"]:not(.gap-input), select:not(.gap-dropdown)').forEach(input => {
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

            // Cleanup notepad
            TestNotepad.cleanup();

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

// ========== Test Notepad ==========
const TestNotepad = {
    // Configuration
    config: {
        attemptId: null,
        autoSaveInterval: 5000,
        maxLength: 5000,
        storageKey: null
    },

    // State
    state: {
        isOpen: false,
        isMinimized: false,
        autoSaveTimer: null,
        lastSavedContent: ''
    },

    // Initialize
    init(attemptId) {
        this.config.attemptId = attemptId;
        this.config.storageKey = `ielts_notes_${attemptId}`;

        this.bindEvents();
        this.loadNote();
        this.startAutoSave();

        if (this.hasNotes()) {
            document.getElementById('notepad-toggle')?.classList.add('has-notes');
        }
    },

    // Bind events
    bindEvents() {
        const toggle = document.getElementById('notepad-toggle');
        const panel = document.getElementById('notepad-panel');

        if (!toggle || !panel) return;

        const closeBtn = panel.querySelector('.notepad-close');
        const minimizeBtn = panel.querySelector('.notepad-minimize');
        const clearBtn = document.getElementById('clear-notes');
        const downloadBtn = document.getElementById('download-notes');
        const textarea = document.getElementById('notepad-content');

        toggle.addEventListener('click', () => this.togglePanel());
        closeBtn?.addEventListener('click', () => this.closePanel());
        minimizeBtn?.addEventListener('click', () => this.toggleMinimize());
        clearBtn?.addEventListener('click', () => this.clearNotes());
        downloadBtn?.addEventListener('click', () => this.downloadNotes());

        textarea?.addEventListener('input', () => {
            this.updateWordCount();
            this.setSaveStatus('saving');
        });

        textarea?.addEventListener('blur', () => this.saveNote());

        const header = panel.querySelector('.notepad-header');
        if (header) {
            this.makeDraggable(header);
        }
    },

    togglePanel() {
        const panel = document.getElementById('notepad-panel');
        const toggle = document.getElementById('notepad-toggle');

        this.state.isOpen = !this.state.isOpen;

        if (this.state.isOpen) {
            panel?.classList.add('open');
            toggle?.classList.add('active');
            document.getElementById('notepad-content')?.focus();
        } else {
            panel?.classList.remove('open');
            toggle?.classList.remove('active');
        }
    },

    closePanel() {
        this.state.isOpen = false;
        document.getElementById('notepad-panel')?.classList.remove('open');
        document.getElementById('notepad-toggle')?.classList.remove('active');
    },

    toggleMinimize() {
        const panel = document.getElementById('notepad-panel');
        this.state.isMinimized = !this.state.isMinimized;

        if (this.state.isMinimized) {
            panel?.classList.add('minimized');
        } else {
            panel?.classList.remove('minimized');
        }
    },

    saveNote() {
        const textarea = document.getElementById('notepad-content');
        if (!textarea) return;

        const content = textarea.value;

        if (content === this.state.lastSavedContent) {
            return;
        }

        const noteData = {
            attemptId: this.config.attemptId,
            content: content,
            createdAt: this.getNoteData()?.createdAt || new Date().toISOString(),
            lastUpdated: new Date().toISOString(),
            wordCount: this.countWords(content)
        };

        try {
            localStorage.setItem(this.config.storageKey, JSON.stringify(noteData));
            this.state.lastSavedContent = content;
            this.setSaveStatus('saved');

            const toggle = document.getElementById('notepad-toggle');
            if (content.trim()) {
                toggle?.classList.add('has-notes');
            } else {
                toggle?.classList.remove('has-notes');
            }
        } catch (e) {
            console.error('Failed to save note:', e);
            this.setSaveStatus('error');
        }
    },

    loadNote() {
        const noteData = this.getNoteData();
        const textarea = document.getElementById('notepad-content');

        if (noteData && noteData.content && textarea) {
            textarea.value = noteData.content;
            this.state.lastSavedContent = noteData.content;
            this.updateWordCount();
        }
    },

    getNoteData() {
        try {
            const data = localStorage.getItem(this.config.storageKey);
            return data ? JSON.parse(data) : null;
        } catch (e) {
            return null;
        }
    },

    hasNotes() {
        const noteData = this.getNoteData();
        return noteData && noteData.content && noteData.content.trim().length > 0;
    },

    clearNotes() {
        if (!confirm('Are you sure you want to clear all notes?')) {
            return;
        }

        const textarea = document.getElementById('notepad-content');
        if (textarea) {
            textarea.value = '';
        }

        localStorage.removeItem(this.config.storageKey);
        this.state.lastSavedContent = '';
        this.updateWordCount();
        document.getElementById('notepad-toggle')?.classList.remove('has-notes');
        this.setSaveStatus('saved');
    },

    downloadNotes() {
        const textarea = document.getElementById('notepad-content');
        const content = textarea?.value || '';

        if (!content.trim()) {
            alert('No notes to download!');
            return;
        }

        const blob = new Blob([content], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `ielts-notes-${this.config.attemptId}.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    },

    updateWordCount() {
        const textarea = document.getElementById('notepad-content');
        const content = textarea?.value || '';
        const wordCount = this.countWords(content);

        const countEl = document.querySelector('.word-count');
        if (countEl) {
            countEl.textContent = `${wordCount} words`;
        }
    },

    countWords(text) {
        return text.trim().split(/\s+/).filter(word => word.length > 0).length;
    },

    setSaveStatus(status) {
        const statusEl = document.getElementById('save-status');
        if (!statusEl) return;

        statusEl.className = `save-status ${status}`;

        switch (status) {
            case 'saving':
                statusEl.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Saving...';
                break;
            case 'saved':
                statusEl.innerHTML = '<i class="fas fa-check-circle"></i> Saved';
                break;
            case 'error':
                statusEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error';
                break;
        }
    },

    startAutoSave() {
        this.state.autoSaveTimer = setInterval(() => {
            this.saveNote();
        }, this.config.autoSaveInterval);
    },

    stopAutoSave() {
        if (this.state.autoSaveTimer) {
            clearInterval(this.state.autoSaveTimer);
            this.state.autoSaveTimer = null;
        }
    },

    makeDraggable(element) {
        let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

        element.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;

            const panel = document.getElementById('notepad-panel');
            if (panel) {
                panel.style.top = (panel.offsetTop - pos2) + "px";
                panel.style.right = 'auto';
                panel.style.left = (panel.offsetLeft - pos1) + "px";
                panel.style.transform = 'none';
            }
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    },

    cleanup() {
        this.stopAutoSave();
        localStorage.removeItem(this.config.storageKey);
    }
};

// ========== Initialize Everything ==========
document.addEventListener('DOMContentLoaded', function () {
    // Get config from window
    const config = window.testConfig || {};

    // Initialize all modules
    NavigationHandler.init();
    TextHighlighter.init();
    AnswerManager.init(config.attemptId);
    SubmitHandler.init();
    HelpGuide.init();
    SimpleSplitDivider.init();
    TestNotepad.init(config.attemptId);

    // Initialize first part
    const firstPartBtn = document.querySelector('.part-btn');
    if (firstPartBtn) {
        firstPartBtn.click();
    }
});