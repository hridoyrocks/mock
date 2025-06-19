// Help Guide System
const HelpGuide = {
    modal: null,
    currentSection: 'overview',
    testType: 'reading',

    // Content for different test types
    content: {
        reading: {
            overview: `
                <div class="help-section">
                    <h3>📖 IELTS Reading Test Overview</h3>
                    <p>The IELTS Academic Reading test consists of 3 passages with a total of 40 questions. You have 60 minutes to complete all questions.</p>
                    
                    <div class="feature-card">
                        <h4>Test Structure:</h4>
                        <ul>
                            <li>3 reading passages (increasing difficulty)</li>
                            <li>40 questions in total</li>
                            <li>60 minutes time limit</li>
                            <li>No extra time for transferring answers</li>
                        </ul>
                    </div>
                    
                    <div class="help-tip">
                        💡 <strong>Tip:</strong> Spend about 20 minutes on each passage. Use the navigation buttons to move between questions quickly.
                    </div>
                </div>
            `,

            questions: `
                <div class="help-section">
                    <h3>📝 Question Types in Reading Test</h3>
                    
                    <div class="question-type-card">
                        <h4>1. Multiple Choice</h4>
                        <p>Choose the correct answer from 4 options (A, B, C, D).</p>
                        <div class="help-demo">
                            <div class="demo-question">
                                <span class="demo-number">1.</span> What is the main purpose of the passage?
                                <div class="demo-options">
                                    <label><input type="radio" name="demo1"> A. To describe a process</label>
                                    <label><input type="radio" name="demo1"> B. To compare theories</label>
                                    <label><input type="radio" name="demo1"> C. To present research</label>
                                    <label><input type="radio" name="demo1"> D. To argue a point</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-type-card">
                        <h4>2. True/False/Not Given</h4>
                        <p>Determine if statements agree with passage information.</p>
                        <ul>
                            <li><strong>TRUE</strong> - The statement agrees with the information</li>
                            <li><strong>FALSE</strong> - The statement contradicts the information</li>
                            <li><strong>NOT GIVEN</strong> - There is no information about this</li>
                        </ul>
                    </div>
                    
                    <div class="question-type-card">
                        <h4>3. Fill in the Blanks</h4>
                        <p>Complete sentences using words from the passage.</p>
                        <div class="help-demo">
                            <p>The research showed that <span class="demo-blank">1. _____</span> percent of participants preferred <span class="demo-blank">2. _____</span> methods.</p>
                        </div>
                        <div class="help-tip">
                            💡 <strong>Note:</strong> Each blank is numbered separately. Click on any blank to type your answer.
                        </div>
                    </div>
                    
                    <div class="question-type-card">
                        <h4>4. Matching Headings</h4>
                        <p>Match headings to the correct paragraphs in the passage.</p>
                    </div>
                    
                    <div class="question-type-card">
                        <h4>5. Summary Completion</h4>
                        <p>Complete a summary using words from a box or the passage.</p>
                    </div>
                </div>
            `,

            navigation: `
                <div class="help-section">
                    <h3>🧭 Navigation Guide</h3>
                    
                    <div class="feature-card">
                        <h4>Text Highlighting Feature</h4>
                        <ol class="feature-steps">
                            <li>Select any text in the passage by clicking and dragging</li>
                            <li>A color picker will appear with 3 options (Yellow, Green, Blue)</li>
                            <li>Click a color to highlight the selected text</li>
                            <li>Click on highlighted text to remove the highlight</li>
                        </ol>
                    </div>
                    
                    <div class="feature-card">
                        <h4>Question Navigation</h4>
                        <ol class="feature-steps">
                            <li>Use numbered buttons at the bottom to jump to any question</li>
                            <li>Green buttons indicate answered questions</li>
                            <li>Use "Review" checkbox to flag questions for later</li>
                            <li>Part buttons let you switch between passage sections</li>
                        </ol>
                    </div>
                    
                    <div class="feature-card">
                        <h4>Answer Management</h4>
                        <ul>
                            <li>Your answers are automatically saved every 30 seconds</li>
                            <li>You can change answers anytime before submission</li>
                            <li>Use Tab key to move between fill-in-the-blank fields</li>
                        </ul>
                    </div>
                </div>
            `,

            tips: `
                <div class="help-section">
                    <h3>💡 Tips & Strategies</h3>
                    
                    <div class="feature-card">
                        <h4>Time Management</h4>
                        <ul>
                            <li>Spend 20 minutes per passage (including questions)</li>
                            <li>Read questions first, then scan the passage</li>
                            <li>Don't spend too much time on difficult questions</li>
                            <li>Leave 5 minutes at the end for review</li>
                        </ul>
                    </div>
                    
                    <div class="feature-card">
                        <h4>Reading Techniques</h4>
                        <ul>
                            <li><strong>Skimming:</strong> Quick read for general idea</li>
                            <li><strong>Scanning:</strong> Look for specific information</li>
                            <li><strong>Keywords:</strong> Underline important words in questions</li>
                            <li><strong>Paraphrasing:</strong> Answers often use different words than the passage</li>
                        </ul>
                    </div>
                    
                    <div class="feature-card">
                        <h4>Common Mistakes to Avoid</h4>
                        <ul>
                            <li>Don't read the entire passage in detail first</li>
                            <li>Don't leave any questions unanswered</li>
                            <li>Don't assume - stick to passage information</li>
                            <li>Don't ignore instruction words (NO MORE THAN TWO WORDS)</li>
                        </ul>
                    </div>
                    
                    <div class="help-tip">
                        🎯 <strong>Pro Tip:</strong> For True/False/Not Given questions, if you can't find clear evidence in the passage, it's likely "Not Given".
                    </div>
                </div>
            `
        },

        listening: {
            overview: `<div class="help-section"><h3>🎧 IELTS Listening Test Overview</h3><p>Coming soon...</p></div>`,
            questions: `<div class="help-section"><h3>📝 Listening Question Types</h3><p>Coming soon...</p></div>`,
            navigation: `<div class="help-section"><h3>🧭 Listening Navigation</h3><p>Coming soon...</p></div>`,
            tips: `<div class="help-section"><h3>💡 Listening Tips</h3><p>Coming soon...</p></div>`
        },

        writing: {
            overview: `<div class="help-section"><h3>✍️ IELTS Writing Test Overview</h3><p>Coming soon...</p></div>`,
            questions: `<div class="help-section"><h3>📝 Writing Tasks</h3><p>Coming soon...</p></div>`,
            navigation: `<div class="help-section"><h3>🧭 Writing Navigation</h3><p>Coming soon...</p></div>`,
            tips: `<div class="help-section"><h3>💡 Writing Tips</h3><p>Coming soon...</p></div>`
        },

        speaking: {
            overview: `<div class="help-section"><h3>🎤 IELTS Speaking Test Overview</h3><p>Coming soon...</p></div>`,
            questions: `<div class="help-section"><h3>📝 Speaking Parts</h3><p>Coming soon...</p></div>`,
            navigation: `<div class="help-section"><h3>🧭 Speaking Navigation</h3><p>Coming soon...</p></div>`,
            tips: `<div class="help-section"><h3>💡 Speaking Tips</h3><p>Coming soon...</p></div>`
        }
    },

    // Initialize
    init(config = {}) {
        this.testType = config.testType || 'reading';
        this.modal = document.getElementById('help-modal');

        if (!this.modal) {
            console.error('Help modal not found');
            return;
        }

        this.setupEventListeners();
        this.loadContent('overview');
    },

    // Setup event listeners
    setupEventListeners() {
        // Tab clicks
        document.querySelectorAll('.help-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                const section = e.target.dataset.section;
                this.switchTab(section);
            });
        });

        // Close on overlay click
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.style.display !== 'none') {
                this.close();
            }
        });
    },

    // Open modal
    open() {
        this.modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        this.loadContent(this.currentSection);
    },

    // Close modal
    close() {
        this.modal.style.display = 'none';
        document.body.style.overflow = '';
    },

    // Switch tabs
    switchTab(section) {
        // Update active tab
        document.querySelectorAll('.help-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelector(`[data-section="${section}"]`).classList.add('active');

        // Load content
        this.currentSection = section;
        this.loadContent(section);
    },

    // Load content
    loadContent(section) {
        const contentArea = document.getElementById('help-content');
        if (!contentArea) return;

        const content = this.content[this.testType]?.[section] || '<p>Content not available</p>';
        contentArea.innerHTML = content;

        // Animate content
        contentArea.style.opacity = '0';
        setTimeout(() => {
            contentArea.style.transition = 'opacity 0.3s ease-in';
            contentArea.style.opacity = '1';
        }, 50);
    },

    // Show video tutorial
    showVideo() {
        alert('Video tutorial coming soon!');
    }
};

// Make it globally available
window.HelpGuide = HelpGuide;