/**
 * Listening Test - Drag & Drop Handler
 * Handles drag and drop functionality for both matching and drag-drop question types
 */

window.ListeningDragDrop = {
    init() {
        console.log('Initializing Listening Drag & Drop');
        this.setupDraggableOptions();
        this.setupDropZones();
        
        // INSTANT INITIAL COUNT
        this.instantUpdateCount();
    },
    
    instantUpdateCount() {
        // Fast direct count
        const totalAnswered = document.querySelectorAll('.number-btn.answered').length;
        const answeredSpan = document.getElementById('answered-count');
        if (answeredSpan) {
            answeredSpan.textContent = totalAnswered;
        }
    },
    
    initializeAnswerCount() {
        // IMPORTANT: First clear all drag-drop question answered states
        const dropBoxes = document.querySelectorAll('.drop-box');
        
        dropBoxes.forEach(box => {
            const questionNumber = box.dataset.questionNumber;
            const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
            
            // Check if box ACTUALLY has answer (not just placeholder)
            const hasRealAnswer = box.classList.contains('has-answer') || 
                                 (box.textContent && 
                                  box.textContent.trim() !== questionNumber && 
                                  box.textContent.trim() !== '' &&
                                  !box.querySelector('.placeholder-text'));
            
            if (navButton) {
                if (hasRealAnswer) {
                    navButton.classList.add('answered');
                } else {
                    // IMPORTANT: Remove answered if no real answer
                    navButton.classList.remove('answered');
                }
            }
        });
        
        // INSTANT UPDATE
        this.instantUpdateCount();
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
                // Force border style change to dashed
                box.style.borderStyle = 'dashed';
                box.style.borderColor = '#000000';
                console.log('Drag over - class added:', box.classList.contains('drag-over'));
            });

            // Dragleave event
            box.addEventListener('dragleave', () => {
                box.classList.remove('drag-over');
                // Reset border style to solid
                box.style.borderStyle = 'solid';
                box.style.borderColor = '#000000';
                console.log('Drag leave - class removed');
            });

            // Drop event
            box.addEventListener('drop', (e) => {
                e.preventDefault();
                box.classList.remove('drag-over');
                box.style.borderStyle = 'solid';
                box.style.borderColor = '#000000';

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
                
                // Mark that drag was successful - don't clear on dragend
                if (e.dataTransfer.getData('from-box') === 'true') {
                    const sourceBoxId = e.dataTransfer.getData('source-box-id');
                    const [qId, zIndex] = sourceBoxId.split('_');
                    const sourceBox = document.querySelector(`.drop-box[data-question-id="${qId}"][data-zone-index="${zIndex}"]`);
                    if (sourceBox) {
                        delete sourceBox.dataset.shouldClear;
                    }
                }

                // Save and update UI - INSTANT UPDATE
                if (typeof saveAllAnswers === 'function') saveAllAnswers();
                
                // INSTANT UPDATE BOTTOM COUNT
                const answeredButtons = document.querySelectorAll('.number-btn.answered').length;
                const answeredSpan = document.getElementById('answered-count');
                if (answeredSpan) {
                    answeredSpan.textContent = answeredButtons;
                }
            });

            // Setup click-to-remove on drop box
            this.setupAnswerRemoval(box);
        });
    },

    removeExistingAnswer(box, allowReuse) {
        const answerText = box.textContent.trim();
        const questionNumber = box.dataset.questionNumber;
        
        if (answerText && answerText !== questionNumber) {
            const oldValue = answerText;
            
            if (!allowReuse) {
                // Restore the old option
                const oldOption = Array.from(document.querySelectorAll('.draggable-option'))
                    .find(opt => {
                        const optVal = opt.dataset.optionValue || opt.dataset.option;
                        return optVal === oldValue || opt.textContent.includes(oldValue);
                    });
                
                if (oldOption) {
                    this.restoreOption(oldValue);
                }
            }
            
            // Clear the box
            box.innerHTML = `<span class="placeholder-text">${questionNumber}</span>`;
            box.classList.remove('has-answer');
            box.removeAttribute('draggable');
            
            // Remove answered state for this specific box
            const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
            if (navButton) {
                navButton.classList.remove('answered');
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
        
        // Clear the box and add answer with proper width
        box.style.display = 'inline-flex';
        box.style.alignItems = 'center';
        box.style.justifyContent = 'center';
        box.style.minWidth = '150px';
        box.style.width = 'auto';
        box.style.padding = '0 15px';
        
        box.textContent = cleanValue;
        box.classList.add('has-answer');
        box.setAttribute('draggable', 'true');
        
        // Add drag handlers to filled box for re-dragging
        this.setupFilledBoxDrag(box, cleanValue, optionLetter);
    },
    
    setupFilledBoxDrag(box, optionValue, optionLetter) {
        // Make filled box draggable
        box.addEventListener('dragstart', (e) => {
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', optionValue);
            e.dataTransfer.setData('option-letter', optionLetter || '');
            e.dataTransfer.setData('from-box', 'true');
            e.dataTransfer.setData('source-box-id', box.dataset.questionId + '_' + box.dataset.zoneIndex);
            
            box.classList.add('dragging-from-box');
            
            // Mark box for clearing on drag end
            box.dataset.shouldClear = 'true';
        });
        
        box.addEventListener('dragend', () => {
            box.classList.remove('dragging-from-box');
            
            // Check if we should clear this box (it was dragged but not successfully dropped elsewhere)
            if (box.dataset.shouldClear === 'true') {
                const questionNumber = box.dataset.questionNumber;
                const zoneNumber = box.dataset.zoneNumber;
                const questionId = box.dataset.questionId;
                
                // Reset box to empty state with proper styling
                box.style.display = 'inline-flex';
                box.style.alignItems = 'center';
                box.style.justifyContent = 'center';
                box.innerHTML = `<span class="placeholder-text">${questionNumber}</span>`;
                box.classList.remove('has-answer');
                box.removeAttribute('draggable');
                delete box.dataset.shouldClear;
                
                // Clear the hidden input
                const inputName = zoneNumber !== undefined 
                    ? `answers[${questionId}][zone_${zoneNumber}]`
                    : `answers[${questionId}][zone_${box.dataset.zoneIndex}]`;
                
                const hiddenInput = document.querySelector(`input[name="${inputName}"]`);
                if (hiddenInput) {
                    hiddenInput.value = '';
                    hiddenInput.dispatchEvent(new Event('change'));
                }
                
                // Remove answered state from navigation button
                const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
                if (navButton) {
                    navButton.classList.remove('answered');
                    
                    // INSTANT BOTTOM COUNT UPDATE
                    const totalAnswered = document.querySelectorAll('.number-btn.answered').length;
                    const answeredSpan = document.getElementById('answered-count');
                    if (answeredSpan) {
                        answeredSpan.textContent = totalAnswered;
                    }
                }
                
                // Restore the option to the draggable list
                this.restoreOption(optionValue);
                
                // Save the removal
                if (typeof saveAllAnswers === 'function') {
                    saveAllAnswers();
                }
            }
        });
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

            // Update navigation button for THIS specific zone - INSTANT
            const navButton = document.querySelector(`.number-btn[data-display-number="${questionNumber}"]`);
            if (navButton && !navButton.classList.contains('answered')) {
                navButton.classList.add('answered');
                
                // INSTANT BOTTOM COUNT UPDATE
                const totalAnswered = document.querySelectorAll('.number-btn.answered').length;
                const answeredSpan = document.getElementById('answered-count');
                if (answeredSpan) {
                    answeredSpan.textContent = totalAnswered;
                }
            }
        } else {
            console.error('Hidden input not found:', inputName);
        }
    },

    markOptionAsPlaced(optionValue) {
        const options = document.querySelectorAll(`.draggable-option[data-option-value="${optionValue}"]`);
        
        if (options.length === 0) {
            // Try alternative selector
            const altOptions = document.querySelectorAll(`.draggable-option[data-option="${optionValue}"]`);
            altOptions.forEach(option => {
                option.classList.add('placed');
                option.setAttribute('style', 'display: none !important;');
            });
        } else {
            options.forEach(option => {
                option.classList.add('placed');
                option.setAttribute('style', 'display: none !important;');
            });
        }
    },

    restoreOption(optionValue) {
        const options = document.querySelectorAll(`.draggable-option[data-option-value="${optionValue}"]`);
        
        if (options.length === 0) {
            // Try alternative selector
            const altOptions = document.querySelectorAll(`.draggable-option[data-option="${optionValue}"]`);
            altOptions.forEach(option => {
                option.classList.remove('placed');
                option.style.display = '';
                option.removeAttribute('style');
            });
        } else {
            options.forEach(option => {
                option.classList.remove('placed');
                option.style.display = '';
                option.removeAttribute('style');
            });
        }
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
