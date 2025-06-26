{{-- Floating Notepad Button --}}
<div id="notepad-toggle" class="notepad-toggle" title="Open Notepad">
    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
    </svg>
</div>

{{-- Notepad Panel --}}
<div id="notepad-panel" class="notepad-panel">
    <div class="notepad-header">
        <h4>My Notes</h4>
        <div class="notepad-actions">
            <button class="notepad-minimize" title="Minimize">_</button>
            <button class="notepad-close" title="Close">&times;</button>
        </div>
    </div>
    
    <div class="notepad-body">
        <textarea 
            id="notepad-content" 
            class="notepad-textarea"
            placeholder="Type your notes here..."
            maxlength="5000"
        ></textarea>
    </div>
    
    <div class="notepad-footer">
        <div class="notepad-info">
            <span class="word-count">0 words</span>
            <span class="save-status" id="save-status">
                <i class="fas fa-check-circle"></i> Saved
            </span>
        </div>
        <div class="notepad-controls">
            <button class="btn-clear" id="clear-notes">
                <i class="fas fa-trash"></i> Clear
            </button>
            <button class="btn-download" id="download-notes">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>
</div>