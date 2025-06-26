// Test Notepad Module
export const TestNotepad = {
    // Configuration
    config: {
        attemptId: null,
        autoSaveInterval: 5000, // 5 seconds
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

        // Check if notes exist and show indicator
        if (this.hasNotes()) {
            document.getElementById('notepad-toggle').classList.add('has-notes');
        }
    },

    // Bind events
    bindEvents() {
        const toggle = document.getElementById('notepad-toggle');
        const panel = document.getElementById('notepad-panel');
        const closeBtn = panel.querySelector('.notepad-close');
        const minimizeBtn = panel.querySelector('.notepad-minimize');
        const clearBtn = document.getElementById('clear-notes');
        const downloadBtn = document.getElementById('download-notes');
        const textarea = document.getElementById('notepad-content');

        // Toggle panel
        toggle.addEventListener('click', () => this.togglePanel());

        // Close panel
        closeBtn.addEventListener('click', () => this.closePanel());

        // Minimize panel
        minimizeBtn.addEventListener('click', () => this.toggleMinimize());

        // Clear notes
        clearBtn.addEventListener('click', () => this.clearNotes());

        // Download notes
        downloadBtn.addEventListener('click', () => this.downloadNotes());

        // Update word count
        textarea.addEventListener('input', () => {
            this.updateWordCount();
            this.setSaveStatus('saving');
        });

        // Save on blur
        textarea.addEventListener('blur', () => this.saveNote());

        // Make panel draggable
        this.makeDraggable(panel.querySelector('.notepad-header'));
    },

    // Toggle panel open/close
    togglePanel() {
        const panel = document.getElementById('notepad-panel');
        const toggle = document.getElementById('notepad-toggle');

        this.state.isOpen = !this.state.isOpen;

        if (this.state.isOpen) {
            panel.classList.add('open');
            toggle.classList.add('active');
            document.getElementById('notepad-content').focus();
        } else {
            panel.classList.remove('open');
            toggle.classList.remove('active');
        }
    },

    // Close panel
    closePanel() {
        this.state.isOpen = false;
        document.getElementById('notepad-panel').classList.remove('open');
        document.getElementById('notepad-toggle').classList.remove('active');
    },

    // Toggle minimize
    toggleMinimize() {
        const panel = document.getElementById('notepad-panel');
        this.state.isMinimized = !this.state.isMinimized;

        if (this.state.isMinimized) {
            panel.classList.add('minimized');
        } else {
            panel.classList.remove('minimized');
        }
    },

    // Save note to localStorage
    saveNote() {
        const content = document.getElementById('notepad-content').value;

        // Only save if content changed
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

            // Update toggle indicator
            if (content.trim()) {
                document.getElementById('notepad-toggle').classList.add('has-notes');
            } else {
                document.getElementById('notepad-toggle').classList.remove('has-notes');
            }
        } catch (e) {
            console.error('Failed to save note:', e);
            this.setSaveStatus('error');
        }
    },

    // Load note from localStorage
    loadNote() {
        const noteData = this.getNoteData();

        if (noteData && noteData.content) {
            document.getElementById('notepad-content').value = noteData.content;
            this.state.lastSavedContent = noteData.content;
            this.updateWordCount();
        }
    },

    // Get note data
    getNoteData() {
        try {
            const data = localStorage.getItem(this.config.storageKey);
            return data ? JSON.parse(data) : null;
        } catch (e) {
            return null;
        }
    },

    // Check if has notes
    hasNotes() {
        const noteData = this.getNoteData();
        return noteData && noteData.content && noteData.content.trim().length > 0;
    },

    // Clear notes
    clearNotes() {
        if (!confirm('Are you sure you want to clear all notes?')) {
            return;
        }

        document.getElementById('notepad-content').value = '';
        localStorage.removeItem(this.config.storageKey);
        this.state.lastSavedContent = '';
        this.updateWordCount();
        document.getElementById('notepad-toggle').classList.remove('has-notes');
        this.setSaveStatus('saved');
    },

    // Download notes
    downloadNotes() {
        const content = document.getElementById('notepad-content').value;
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

    // Update word count
    updateWordCount() {
        const content = document.getElementById('notepad-content').value;
        const wordCount = this.countWords(content);
        document.querySelector('.word-count').textContent = `${wordCount} words`;
    },

    // Count words
    countWords(text) {
        return text.trim().split(/\s+/).filter(word => word.length > 0).length;
    },

    // Set save status
    setSaveStatus(status) {
        const statusEl = document.getElementById('save-status');
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

    // Start auto save
    startAutoSave() {
        this.state.autoSaveTimer = setInterval(() => {
            this.saveNote();
        }, this.config.autoSaveInterval);
    },

    // Stop auto save
    stopAutoSave() {
        if (this.state.autoSaveTimer) {
            clearInterval(this.state.autoSaveTimer);
            this.state.autoSaveTimer = null;
        }
    },

    // Make element draggable
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
            panel.style.top = (panel.offsetTop - pos2) + "px";
            panel.style.right = 'auto';
            panel.style.left = (panel.offsetLeft - pos1) + "px";
            panel.style.transform = 'none';
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    },

    // Cleanup on test submit
    cleanup() {
        this.stopAutoSave();
        localStorage.removeItem(this.config.storageKey);
    }
};