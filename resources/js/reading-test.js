

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

    // Initialize first part
    const firstPartBtn = document.querySelector('.part-btn');
    if (firstPartBtn) {
        firstPartBtn.click();
    }
});