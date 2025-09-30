/**
 * Listening Test - Drag & Drop Handler
 * Handles drag and drop functionality for both matching and drag-drop question types
 */

window.ListeningDragDrop = {
    init() {
        console.log('Initializing Listening Drag & Drop');
        this.setupDraggableOptions();
        this.setupDropZones();
    },

    setupDraggableOptions() {
        const draggableOptions = document.querySelectorAll('.draggable-option');
        console.log('Found draggable options:', draggableOptions.length);

        draggableOptions.forEach(option => {
            // Debug: Log the data attributes
            console.log('Option data:', {
                optionValue: option.dataset.optionValue,
                optionLetter: option.dataset.optionLetter,
                innerHTML: option.innerHTML
            });
            
            option.addEventListener('dragstart', (e) => {
                const optionValue = option.dataset.optionValue || option.textContent.replace(/^[A-Z]\.\s*/, '');
                const optionLetter = option.dataset.optionLetter || '';
                
                console.log('Drag started:', { optionValue, optionLetter });
                
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', optionValue);
                e.dataTransfer.setData('option-letter', optionLetter);
                e.dataTransfer.setData('full-text', option.innerHTML);
                
                option.classList.add('dragging');
            });

            option.addEventListener('dragend', () => {
                option.classList.remove('dragging');
            });
        });
    },

    setupDropZones() {
        const dropBoxes = document.querySelectorAll('.drop-box');
        console.log('Found drop boxes:', dropBoxes.length);

        dropBoxes.forEach(box => {
            // Dragover event
            box.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                box.classList.add('drag-over');
            });

            // Dragleave event
            box.addEventListener('dragleave', () => {
                box.classList.remove('drag-over');
            });

            // Drop event
            box.addEventListener('drop', (e) => {
                e.preventDefault();
                box.classList.remove('drag-over');

                const optionValue = e.dataTransfer.getData('text/plain');
                const optionLetter = e.dataTransfer.getData('option-letter');
                const fullText = e.dataTransfer.getData('full-text');
                
                const questionId = box.dataset.questionId;
                const zoneIndex = box.dataset.zoneIndex;
                const index = box.dataset.index; // For matching questions
                const questionNumber = box.dataset.questionNumber;
                const allowReuse = box.dataset.allowReuse === '1';

                console.log('Drop event:', {
                    optionValue,
                    questionId,
                    zoneIndex,
                    index,
                    allowReuse,
                    questionNumber
                });

                // Handle existing answer
                if (box.classList.contains('has-answer')) {
                    this.removeExistingAnswer(box, allowReuse);
                }

                // Add new answer
                this.addAnswerToDropBox(box, optionValue, optionLetter, fullText);

                // Update hidden input
                this.updateHiddenInput(box, questionId, zoneIndex, index, optionValue, questionNumber);

                // Handle option visibility
                if (!allowReuse) {
                    this.markOptionAsPlaced(optionValue);
                }

                // Save and update UI
                if (typeof saveAllAnswers === 'function') saveAllAnswers();
                if (typeof updateAnswerCount === 'function') updateAnswerCount();
            });

            // Setup click-to-remove on drop box
            this.setupAnswerRemoval(box);
        });
    },

    removeExistingAnswer(box, allowReuse) {
        const answerText = box.querySelector('.answer-text');
        if (answerText) {
            const oldValue = answerText.textContent.replace(/^[A-Z]\.\s*/, '');
            
            if (!allowReuse) {
                // Restore the old option
                const oldOption = Array.from(document.querySelectorAll('.draggable-option'))
                    .find(opt => {
                        const optVal = opt.dataset.optionValue || opt.dataset.option;
                        return optVal === oldValue || opt.textContent.includes(oldValue);
                    });
                
                if (oldOption) {
                    oldOption.classList.remove('placed');
                }
            }
        }
    },

    addAnswerToDropBox(box, optionValue, optionLetter, fullText) {
        const questionNumber = box.dataset.questionNumber;
        
        // Debug logging
        console.log('Adding answer to box:', { optionValue, optionLetter, fullText });
        
        // Clean the option value - remove letter prefix if present
        let cleanValue = optionValue;
        if (!cleanValue || cleanValue === 'undefined') {
            // Extract text from fullText if optionValue is undefined
            cleanValue = fullText ? fullText.replace(/^[A-Z]\.\s*/, '').trim() : 'Error';
        }
        
        box.innerHTML = `
            <span class="answer-text">${cleanValue}</span>
            <span class="remove-answer" title="Remove answer">×</span>
        `;
        box.classList.add('has-answer');
    },

    updateHiddenInput(box, questionId, zoneIndex, index, optionValue, questionNumber) {
        // Get the actual zone number from data attribute for backend matching
        const zoneNumber = box.dataset.zoneNumber;
        let inputName;
        
        if (zoneNumber !== undefined) {
            // Use zone number from [DRAG_X] for backend matching
            inputName = `answers[${questionId}][zone_${zoneNumber}]`;
        } else if (zoneIndex !== undefined) {
            // Fallback: Drag-drop question with zone index
            inputName = `answers[${questionId}][zone_${zoneIndex}]`;
        } else if (index !== undefined) {
            // Matching question
            inputName = `answers[${questionId}_${index}]`;
        } else {
            console.error('No zone identifier found');
            return;
        }

        const hiddenInput = document.querySelector(`input[name="${inputName}"]`);
        if (hiddenInput) {
            hiddenInput.value = optionValue;
            console.log('Updated input:', inputName, '=', optionValue);

            // Update navigation button
            const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
            if (navButton) {
                navButton.classList.add('answered');
            }
        } else {
            console.error('Hidden input not found:', inputName);
        }
    },

    markOptionAsPlaced(optionValue) {
        const option = document.querySelector(`.draggable-option[data-option-value="${optionValue}"]`) ||
                      document.querySelector(`.draggable-option[data-option="${optionValue}"]`);
        
        if (option) {
            option.classList.add('placed');
        }
    },

    setupAnswerRemoval(box) {
        box.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-answer')) {
                e.stopPropagation();
                this.removeAnswer(box);
            }
        });
    },

    removeAnswer(box) {
        const answerText = box.querySelector('.answer-text');
        if (!answerText) return;

        const optionValue = answerText.textContent.replace(/^[A-Z]\.\s*/, '');
        const allowReuse = box.dataset.allowReuse === '1';
        const questionId = box.dataset.questionId;
        const zoneIndex = box.dataset.zoneIndex;
        const index = box.dataset.index;
        const questionNumber = box.dataset.questionNumber;

        console.log('Removing answer:', optionValue);

        // Clear the drop box and restore question number
        box.innerHTML = `<span class="placeholder-text">${questionNumber}</span>`;
        box.classList.remove('has-answer');

        // Clear hidden input
        let inputName;
        if (zoneIndex !== undefined) {
            inputName = `answers[${questionId}][zone_${zoneIndex}]`;
        } else if (index !== undefined) {
            inputName = `answers[${questionId}_${index}]`;
        }

        const hiddenInput = document.querySelector(`input[name="${inputName}"]`);
        if (hiddenInput) {
            hiddenInput.value = '';
        }

        // Restore option if not reusable
        if (!allowReuse) {
            const option = document.querySelector(`.draggable-option[data-option-value="${optionValue}"]`) ||
                          document.querySelector(`.draggable-option[data-option="${optionValue}"]`);
            
            if (option) {
                option.classList.remove('placed');
            }
        }

        // Update navigation button
        const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
        if (navButton) {
            // Check if there are other answered inputs for this question
            const allInputs = document.querySelectorAll(`input[data-question-number="${questionNumber}"]`);
            const hasAnswers = Array.from(allInputs).some(input => input.value && input.value.trim());
            
            if (!hasAnswers) {
                navButton.classList.remove('answered');
            }
        }

        // Save and update UI
        if (typeof saveAllAnswers === 'function') saveAllAnswers();
        if (typeof updateAnswerCount === 'function') updateAnswerCount();
    }
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        if (document.querySelector('.draggable-option') || document.querySelector('.drop-box')) {
            window.ListeningDragDrop.init();
        }
    });
} else {
    // DOM already loaded
    if (document.querySelector('.draggable-option') || document.querySelector('.drop-box')) {
        window.ListeningDragDrop.init();
    }
}

// Global function for remove button onclick
window.removeDragDropAnswer = function(removeBtn) {
    const dropBox = removeBtn.closest('.drop-box');
    if (dropBox && window.ListeningDragDrop) {
        window.ListeningDragDrop.removeAnswer(dropBox);
    }
};
