// Listening Question Types Handler
window.ListeningQuestionTypes = {
    optionCounters: {
        single: 0,
        multiple: 0
    },
    
    blankAnswers: {},
    dropdownData: {
        options: {},
        correct: {}
    },
    
    dragDropData: {
        dropZones: [],
        options: [],
        allowReuse: true
    },
    
    init(questionType) {
        console.log('ListeningQuestionTypes.init:', questionType);
        
        // Hide all type-specific panels
        document.querySelectorAll('.type-specific-panel').forEach(panel => {
            panel.style.display = 'none';
        });
        
        // Reset form state
        this.resetFormState();
        
        // Show appropriate panel based on question type
        switch(questionType) {
            case 'fill_blanks':
                this.initFillBlanks();
                break;
                
            case 'single_choice':
                this.initSingleChoice(questionType);
                break;
                
            case 'multiple_choice':
                this.initMultipleChoice();
                break;
                
            case 'dropdown_selection':
                this.initDropdownSelection();
                break;
                
            case 'drag_drop':
                this.initDragDrop();
                break;
        }
    },
    
    resetFormState() {
        // Clear all option containers
        ['single-choice-options-container', 'multiple-choice-options-container'].forEach(id => {
            const container = document.getElementById(id);
            if (container) container.innerHTML = '';
        });
        
        // Reset counters
        this.optionCounters = { single: 0, multiple: 0 };
        this.blankAnswers = {};
        this.dropdownData = { options: {}, correct: {} };
        
        // Hide all options cards
        const optionsCard = document.getElementById('options-card');
        if (optionsCard) optionsCard.classList.add('hidden');
    },
    
    // Initialize Fill in the Blanks
    initFillBlanks() {
        const panel = document.getElementById('fill-blanks-panel');
        if (panel) panel.style.display = 'block';
        
        // Show blank buttons
        const blankButtons = document.getElementById('blank-buttons');
        if (blankButtons) blankButtons.style.display = 'flex';
        
        // Initialize blanks manager
        const blanksManager = document.getElementById('blanks-manager-listening');
        if (blanksManager) blanksManager.classList.remove('hidden');
        
        // Set up blank insertion for content editor
        this.setupBlankInsertion();
    },
    
    // Initialize Single Choice
    initSingleChoice(type) {
        const panel = document.getElementById('single-choice-panel');
        if (panel) panel.style.display = 'block';
        
        // Add 4 empty options for single choice
        for (let i = 0; i < 4; i++) {
            this.addSingleChoiceOption('', i === 0);
        }
    },
    
    // Initialize Multiple Choice
    initMultipleChoice() {
        const panel = document.getElementById('multiple-choice-panel');
        if (panel) panel.style.display = 'block';
        
        // Add 4 empty options by default
        for (let i = 0; i < 4; i++) {
            this.addMultipleChoiceOption('', false);
        }
    },
    
    // Initialize Dropdown Selection
    initDropdownSelection() {
        const panel = document.getElementById('dropdown-panel');
        if (panel) panel.style.display = 'block';
        
        // Show dropdown buttons
        const dropdownButtons = document.getElementById('dropdown-buttons');
        if (dropdownButtons) dropdownButtons.style.display = 'flex';
        
        // Initialize dropdown manager
        const dropdownManager = document.getElementById('dropdown-manager-listening');
        if (dropdownManager) dropdownManager.classList.remove('hidden');
        
        // Set up dropdown insertion
        this.setupDropdownInsertion();
    },
    
    // Add Single Choice Option
    addSingleChoiceOption(content = '', isCorrect = false) {
        const container = document.getElementById('single-choice-options-container');
        if (!container) return;
        
        const index = this.optionCounters.single;
        const optionDiv = document.createElement('div');
        optionDiv.className = 'flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
        optionDiv.innerHTML = `
            <input type="radio" name="correct_option" value="${index}" 
                   class="h-4 w-4 text-blue-600" ${isCorrect ? 'checked' : ''}>
            <span class="font-medium text-gray-700">${String.fromCharCode(65 + index)}.</span>
            <input type="text" name="options[${index}][content]" value="${content}" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                   placeholder="Enter option text..." required>
            <button type="button" onclick="ListeningQuestionTypes.removeSingleOption(this)" 
                    class="text-red-500 hover:text-red-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        container.appendChild(optionDiv);
        this.optionCounters.single++;
    },
    
    // Add Multiple Choice Option
    addMultipleChoiceOption(content = '', isCorrect = false) {
        const container = document.getElementById('multiple-choice-options-container');
        if (!container) return;
        
        const index = this.optionCounters.multiple;
        const optionDiv = document.createElement('div');
        optionDiv.className = 'flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
        optionDiv.innerHTML = `
            <input type="checkbox" name="correct_options[]" value="${index}" 
                   class="h-4 w-4 text-purple-600" ${isCorrect ? 'checked' : ''}>
            <span class="font-medium text-gray-700">${String.fromCharCode(65 + index)}.</span>
            <input type="text" name="options[${index}][content]" value="${content}" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500"
                   placeholder="Enter option text..." required>
            <button type="button" onclick="ListeningQuestionTypes.removeMultipleOption(this)" 
                    class="text-red-500 hover:text-red-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        container.appendChild(optionDiv);
        this.optionCounters.multiple++;
    },
    
    // Remove Single Choice Option
    removeSingleOption(button) {
        button.closest('div').remove();
        this.reindexSingleOptions();
    },
    
    // Remove Multiple Choice Option
    removeMultipleOption(button) {
        button.closest('div').remove();
        this.reindexMultipleOptions();
    },
    
    // Reindex Single Choice Options
    reindexSingleOptions() {
        const container = document.getElementById('single-choice-options-container');
        const options = container.querySelectorAll('> div');
        this.optionCounters.single = 0;
        
        options.forEach((option, index) => {
            const radio = option.querySelector('input[type="radio"]');
            const textInput = option.querySelector('input[type="text"]');
            const label = option.querySelector('span.font-medium');
            
            radio.value = index;
            textInput.name = `options[${index}][content]`;
            label.textContent = String.fromCharCode(65 + index) + '.';
            
            this.optionCounters.single++;
        });
    },
    
    // Reindex Multiple Choice Options
    reindexMultipleOptions() {
        const container = document.getElementById('multiple-choice-options-container');
        const options = container.querySelectorAll('> div');
        this.optionCounters.multiple = 0;
        
        options.forEach((option, index) => {
            const checkbox = option.querySelector('input[type="checkbox"]');
            const textInput = option.querySelector('input[type="text"]');
            const label = option.querySelector('span.font-medium');
            
            checkbox.value = index;
            textInput.name = `options[${index}][content]`;
            label.textContent = String.fromCharCode(65 + index) + '.';
            
            this.optionCounters.multiple++;
        });
    },
    
    // Setup Blank Insertion
    setupBlankInsertion() {
        // This will be called from the main script
        if (!window.listeningBlankCounter) {
            window.listeningBlankCounter = 0;
        }
        
        window.insertListeningBlank = () => {
            const editor = window.contentEditor || tinymce.activeEditor;
            if (!editor) {
                console.error('No editor found');
                return;
            }
            
            window.listeningBlankCounter++;
            const blankText = `[____${window.listeningBlankCounter}____]`;
            editor.insertContent(blankText);
            
            console.log('Inserted blank:', blankText);
            setTimeout(() => this.updateBlanks(), 100);
        };
    },
    
    // Setup Dropdown Insertion
    setupDropdownInsertion() {
        window.listeningDropdownCounter = 0;
        window.insertListeningDropdown = () => {
            const editor = tinymce.activeEditor;
            if (!editor) return;
            
            window.listeningDropdownCounter++;
            const dropdownText = `[DROPDOWN_${window.listeningDropdownCounter}]`;
            editor.insertContent(dropdownText);
            
            setTimeout(() => this.updateDropdowns(), 100);
        };
    },
    
    // Update Blanks Display
    updateBlanks() {
        const editor = window.contentEditor || tinymce.activeEditor;
        if (!editor) {
            console.error('No editor found in updateBlanks');
            return;
        }
        
        const content = editor.getContent({ format: 'text' });
        const blankMatches = content.match(/\[____\d+____\]/g) || [];
        
        console.log('Found blanks:', blankMatches);
        
        const blanksList = document.getElementById('blanks-list-listening');
        const blanksManager = document.getElementById('blanks-manager-listening');
        const counter = document.getElementById('blank-counter-listening');
        
        if (!blanksList || !blanksManager) {
            console.error('Blanks manager elements not found');
            return;
        }
        
        if (blankMatches.length > 0) {
            blanksManager.classList.remove('hidden');
            blanksList.innerHTML = '';
            
            blankMatches.forEach(match => {
                const num = match.match(/\d+/)[0];
                const value = this.blankAnswers[num] || '';
                
                const blankDiv = document.createElement('div');
                blankDiv.className = 'flex items-center gap-2';
                blankDiv.innerHTML = `
                    <span class="text-sm font-medium text-gray-700 min-w-[60px]">Blank ${num}:</span>
                    <input type="text" 
                           name="blank_answers[]" 
                           value="${value}"
                           class="flex-1 px-3 py-1 text-sm border border-gray-300 rounded"
                           placeholder="Correct answer"
                           onchange="ListeningQuestionTypes.updateBlankAnswer(${num}, this.value)"
                           required>
                `;
                
                blanksList.appendChild(blankDiv);
            });
            
            if (counter) counter.textContent = blankMatches.length;
            
            // Update blank counter to highest number
            const highestNum = Math.max(...blankMatches.map(m => parseInt(m.match(/\d+/)[0])));
            window.listeningBlankCounter = highestNum;
        } else {
            blanksManager.classList.add('hidden');
        }
    },
    
    // Update Dropdowns Display
    updateDropdowns() {
        const editor = tinymce.activeEditor;
        if (!editor) return;
        
        const content = editor.getContent({ format: 'text' });
        const dropdownMatches = content.match(/\[DROPDOWN_\d+\]/g) || [];
        
        const dropdownList = document.getElementById('dropdown-list-listening');
        const dropdownManager = document.getElementById('dropdown-manager-listening');
        const counter = document.getElementById('dropdown-counter-listening');
        
        if (!dropdownList || !dropdownManager) return;
        
        if (dropdownMatches.length > 0) {
            dropdownManager.classList.remove('hidden');
            dropdownList.innerHTML = '';
            
            dropdownMatches.forEach(match => {
                const num = match.match(/\d+/)[0];
                const options = this.dropdownData.options[num] || '';
                const correct = this.dropdownData.correct[num] || '0';
                
                const dropdownDiv = document.createElement('div');
                dropdownDiv.className = 'border border-gray-200 rounded-lg p-3';
                dropdownDiv.innerHTML = `
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm font-medium text-gray-700">Dropdown ${num}:</span>
                    </div>
                    <div class="space-y-2">
                        <input type="text" 
                               name="dropdown_options[]" 
                               value="${options}"
                               class="w-full px-3 py-1 text-sm border border-gray-300 rounded"
                               placeholder="Options (comma separated)"
                               onchange="ListeningQuestionTypes.updateDropdownOptions(${num}, this.value)"
                               required>
                        <select name="dropdown_correct[]" 
                                class="w-full px-3 py-1 text-sm border border-gray-300 rounded"
                                onchange="ListeningQuestionTypes.updateDropdownCorrect(${num}, this.value)">
                            ${this.generateDropdownOptions(options, correct)}
                        </select>
                    </div>
                `;
                
                dropdownList.appendChild(dropdownDiv);
            });
            
            if (counter) counter.textContent = dropdownMatches.length;
        } else {
            dropdownManager.classList.add('hidden');
        }
    },
    
    // Helper Functions
    updateBlankAnswer(num, value) {
        this.blankAnswers[num] = value;
    },
    
    updateDropdownOptions(num, value) {
        this.dropdownData.options[num] = value;
        this.updateDropdowns();
    },
    
    updateDropdownCorrect(num, value) {
        this.dropdownData.correct[num] = value;
    },
    
    generateDropdownOptions(optionsString, selectedValue) {
        if (!optionsString) return '<option value="">Enter options first</option>';
        
        const options = optionsString.split(',').map(opt => opt.trim());
        return options.map((opt, idx) => 
            `<option value="${idx}" ${idx == selectedValue ? 'selected' : ''}>${opt}</option>`
        ).join('');
    },
    
    // Initialize Drag & Drop
    initDragDrop() {
        const panel = document.getElementById('drag-drop-panel');
        if (panel) panel.style.display = 'block';
        
        // Reset drag drop data
        this.dragDropData = {
            dropZones: [],
            options: [],
            allowReuse: true
        };
        
        // Add default drop zones and options
        for (let i = 0; i < 3; i++) {
            this.addDropZone();
        }
        
        for (let i = 0; i < 5; i++) {
            this.addDraggableOption();
        }
    },
    
    // Add Drop Zone
    addDropZone() {
        const container = document.getElementById('drop-zones-container');
        if (!container) return;
        
        const index = this.dragDropData.dropZones.length;
        this.dragDropData.dropZones.push({ label: '', correctAnswer: '' });
        
        const dropZoneDiv = document.createElement('div');
        dropZoneDiv.className = 'border border-gray-300 rounded-lg p-3 bg-gray-50';
        dropZoneDiv.dataset.dropZoneIndex = index;
        dropZoneDiv.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                    ${index + 1}
                </div>
                <div class="flex-1 space-y-2">
                    <input type="text" 
                           name="drag_drop_zones[${index}][label]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                           placeholder="Drop zone label (e.g., 'Capital of France is...')"
                           value=""
                           onchange="ListeningQuestionTypes.updateDropZoneLabel(${index}, this.value)"
                           required>
                    <input type="text" 
                           name="drag_drop_zones[${index}][answer]" 
                           class="w-full px-3 py-2 border border-indigo-300 rounded-md text-sm bg-indigo-50"
                           placeholder="Correct answer (must match one of the draggable options)"
                           value=""
                           onchange="ListeningQuestionTypes.updateDropZoneAnswer(${index}, this.value)"
                           required>
                </div>
                <button type="button" 
                        onclick="ListeningQuestionTypes.removeDropZone(${index})" 
                        class="text-red-500 hover:text-red-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        container.appendChild(dropZoneDiv);
    },
    
    // Add Draggable Option
    addDraggableOption() {
        const container = document.getElementById('draggable-options-container');
        if (!container) return;
        
        const index = this.dragDropData.options.length;
        this.dragDropData.options.push('');
        
        const optionDiv = document.createElement('div');
        optionDiv.className = 'flex items-center gap-3 p-2 bg-gray-50 rounded border border-gray-200';
        optionDiv.dataset.optionIndex = index;
        optionDiv.innerHTML = `
            <span class="font-medium text-gray-700 text-sm">${String.fromCharCode(65 + index)}.</span>
            <input type="text" 
                   name="drag_drop_options[]" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm"
                   placeholder="Option text"
                   value=""
                   onchange="ListeningQuestionTypes.updateDraggableOption(${index}, this.value)"
                   required>
            <button type="button" 
                    onclick="ListeningQuestionTypes.removeDraggableOption(${index})" 
                    class="text-red-500 hover:text-red-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        container.appendChild(optionDiv);
    },
    
    // Update Drop Zone Label
    updateDropZoneLabel(index, value) {
        if (this.dragDropData.dropZones[index]) {
            this.dragDropData.dropZones[index].label = value;
        }
    },
    
    // Update Drop Zone Answer
    updateDropZoneAnswer(index, value) {
        if (this.dragDropData.dropZones[index]) {
            this.dragDropData.dropZones[index].correctAnswer = value;
        }
    },
    
    // Update Draggable Option
    updateDraggableOption(index, value) {
        if (this.dragDropData.options[index] !== undefined) {
            this.dragDropData.options[index] = value;
        }
    },
    
    // Remove Drop Zone
    removeDropZone(index) {
        const container = document.getElementById('drop-zones-container');
        const dropZone = container.querySelector(`[data-drop-zone-index="${index}"]`);
        if (dropZone) {
            dropZone.remove();
        }
        
        // Remove from data
        this.dragDropData.dropZones.splice(index, 1);
        
        // Reindex remaining drop zones
        this.reindexDropZones();
    },
    
    // Remove Draggable Option
    removeDraggableOption(index) {
        const container = document.getElementById('draggable-options-container');
        const option = container.querySelector(`[data-option-index="${index}"]`);
        if (option) {
            option.remove();
        }
        
        // Remove from data
        this.dragDropData.options.splice(index, 1);
        
        // Reindex remaining options
        this.reindexDraggableOptions();
    },
    
    // Reindex Drop Zones
    reindexDropZones() {
        const container = document.getElementById('drop-zones-container');
        const dropZones = container.querySelectorAll('[data-drop-zone-index]');
        
        dropZones.forEach((zone, newIndex) => {
            zone.dataset.dropZoneIndex = newIndex;
            
            const numberBadge = zone.querySelector('.bg-indigo-600');
            if (numberBadge) numberBadge.textContent = newIndex + 1;
            
            const inputs = zone.querySelectorAll('input');
            inputs[0].name = `drag_drop_zones[${newIndex}][label]`;
            inputs[0].setAttribute('onchange', `ListeningQuestionTypes.updateDropZoneLabel(${newIndex}, this.value)`);
            inputs[1].name = `drag_drop_zones[${newIndex}][answer]`;
            inputs[1].setAttribute('onchange', `ListeningQuestionTypes.updateDropZoneAnswer(${newIndex}, this.value)`);
            
            const removeBtn = zone.querySelector('button');
            removeBtn.setAttribute('onclick', `ListeningQuestionTypes.removeDropZone(${newIndex})`);
        });
    },
    
    // Reindex Draggable Options
    reindexDraggableOptions() {
        const container = document.getElementById('draggable-options-container');
        const options = container.querySelectorAll('[data-option-index]');
        
        options.forEach((option, newIndex) => {
            option.dataset.optionIndex = newIndex;
            
            const label = option.querySelector('span');
            if (label) label.textContent = String.fromCharCode(65 + newIndex) + '.';
            
            const input = option.querySelector('input[type="text"]');
            input.setAttribute('onchange', `ListeningQuestionTypes.updateDraggableOption(${newIndex}, this.value)`);
            
            const removeBtn = option.querySelector('button');
            removeBtn.setAttribute('onclick', `ListeningQuestionTypes.removeDraggableOption(${newIndex})`);
        });
    },
    
    // Prepare submission data
    prepareSubmissionData() {
        const questionType = document.getElementById('question_type').value;
        const data = {
            type: questionType,
            blanks: this.blankAnswers,
            dropdowns: this.dropdownData,
            dragDrop: this.dragDropData
        };
        
        // Store in hidden input
        const hiddenInput = document.getElementById('listening-question-data');
        if (hiddenInput) {
            hiddenInput.value = JSON.stringify(data);
        }
        
        return data;
    }
};

// Make it globally available
window.ListeningQuestionTypes = ListeningQuestionTypes;