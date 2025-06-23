// Reading Passage Editor JavaScript
var passageEditor = null;
var detectedMarkers = new Map();
var lastDetectedCount = 0;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    initializePassageEditor();
    setupFormSubmission();
});

function initializePassageEditor() {
    tinymce.init({
        selector: '.tinymce-passage',
        height: 500,
        menubar: true,
        plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | fullscreen code preview',
        content_style: 'body { font-family: Georgia, Times New Roman, serif; font-size: 16px; line-height: 1.8; color: #333; padding: 20px; }',
        setup: function (editor) {
            passageEditor = editor;

            editor.on('keyup change paste input', function () {
                updateStats();
                detectAndHighlightMarkers();
            });

            editor.on('init', function () {
                updateStats();
                detectAndHighlightMarkers();
            });
        }
    });
}

// Setup form submission
function setupFormSubmission() {
    var form = document.getElementById('passageForm');

    if (form) {
        // Remove any existing listeners
        form.removeEventListener('submit', handleFormSubmit);

        // Add new listener
        form.addEventListener('submit', handleFormSubmit);
    }
}

// Handle form submission
function handleFormSubmit(e) {
    console.log('Form submit triggered');

    if (!passageEditor) {
        e.preventDefault();
        alert('Editor not initialized. Please refresh the page.');
        return false;
    }

    // Get content from TinyMCE
    var content = passageEditor.getContent();
    console.log('Content length:', content.length);

    // Validation
    if (!content || content.trim() === '') {
        e.preventDefault();
        showNotification('Please enter passage content', 'error');
        return false;
    }

    // Find or create hidden input for content
    var contentInput = form.querySelector('input[name="content"]');
    if (!contentInput) {
        contentInput = document.createElement('input');
        contentInput.type = 'hidden';
        contentInput.name = 'content';
        form.appendChild(contentInput);
    }

    // Set the content value
    contentInput.value = content;

    console.log('Form submitting with', detectedMarkers.size, 'markers');

    // Form will submit naturally
    return true;
}

// Manual submit function for button onclick
window.submitPassageForm = function () {
    console.log('Submitting passage...');

    var form = document.getElementById('passageForm');
    if (!form || !passageEditor) {
        alert('Form or editor not ready');
        return;
    }

    // Get content from TinyMCE
    var content = passageEditor.getContent();

    if (!content || content.trim() === '') {
        alert('Please enter passage content');
        return;
    }

    // Create a new textarea with content
    var textarea = document.createElement('textarea');
    textarea.name = 'content';
    textarea.style.display = 'none';
    textarea.value = content;

    // Add to form
    form.appendChild(textarea);

    // Debug log
    console.log('Added content to form, length:', content.length);

    // Submit the form
    form.submit();
};

// Function to insert quick marker
function insertQuickMarker(number) {
    if (!passageEditor) {
        showNotification('Editor not ready', 'error');
        return;
    }

    var selection = passageEditor.selection.getContent({ format: 'text' });
    var text = selection ? selection : 'answer text here';
    var fullMarker = '{{Q' + number + '}}' + text + '{{Q' + number + '}}';

    passageEditor.insertContent(fullMarker);
    showNotification('Marker Q' + number + ' inserted!', 'success');
}

// Detect markers function
function detectAndHighlightMarkers() {
    if (!passageEditor) return;

    var content = passageEditor.getContent({ format: 'text' });
    var regex = /\{\{Q(\d+)\}\}([\s\S]*?)\{\{Q\1\}\}/g;
    var foundMarkers = new Map();
    var match;

    while ((match = regex.exec(content)) !== null) {
        var num = match[1];
        var text = match[2].trim();
        var id = 'Q' + num;

        foundMarkers.set(id, {
            number: parseInt(num),
            text: text,
            fullMatch: match[0]
        });
    }

    var currentCount = foundMarkers.size;
    if (currentCount !== lastDetectedCount) {
        if (currentCount > lastDetectedCount) {
            showNotification('New marker detected!', 'success');
        }
        lastDetectedCount = currentCount;
    }

    detectedMarkers = foundMarkers;
    updateMarkersDisplay();
}

// Update markers display
function updateMarkersDisplay() {
    var panel = document.getElementById('markers-panel');
    var list = document.getElementById('markers-list');
    var count = document.getElementById('markers-count');

    if (!panel || !list || !count) return;

    count.textContent = detectedMarkers.size;

    if (detectedMarkers.size > 0) {
        panel.classList.remove('hidden');

        var markers = Array.from(detectedMarkers.entries());
        markers.sort(function (a, b) {
            return a[1].number - b[1].number;
        });

        var html = '';
        markers.forEach(function (marker) {
            var id = marker[0];
            var data = marker[1];
            var shortText = data.text.length > 60 ? data.text.substring(0, 60) + '...' : data.text;

            html += '<div class="marker-item marker-detected">';
            html += '<div class="flex items-start">';
            html += '<span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-800 text-sm font-bold rounded-full mr-3">';
            html += data.number;
            html += '</span>';
            html += '<div class="flex-1">';
            html += '<div class="font-medium text-gray-900">' + id + '</div>';
            html += '<div class="text-sm text-gray-600 mt-1">"' + shortText + '"</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });

        list.innerHTML = html;
    } else {
        panel.classList.add('hidden');
        list.innerHTML = '';
    }
}

// Update stats
function updateStats() {
    if (!passageEditor) return;

    var text = passageEditor.getContent({ format: 'text' });
    var words = text.trim().split(/\s+/).filter(function (w) { return w.length > 0; });

    var wordCount = document.getElementById('passage-word-count');
    var charCount = document.getElementById('passage-char-count');

    if (wordCount) wordCount.textContent = words.length;
    if (charCount) charCount.textContent = text.length;
}

// Preview function
window.previewPassage = function () {
    if (!passageEditor) {
        showNotification('Editor not ready', 'error');
        return;
    }

    var modal = document.getElementById('preview-modal');
    var content = document.getElementById('preview-content');
    var list = document.getElementById('preview-markers-list');

    if (!modal || !content || !list) return;

    var html = passageEditor.getContent();

    // Replace markers with highlighted version
    html = html.replace(/\{\{Q(\d+)\}\}([\s\S]*?)\{\{Q\1\}\}/g, function (match, num, text) {
        return '<span class="marker-tag">Q' + num + '</span>' +
            '<span class="marker-highlight">' + text + '</span>' +
            '<span class="marker-tag">Q' + num + '</span>';
    });

    content.innerHTML = html;

    // Show markers list
    if (detectedMarkers.size > 0) {
        var markers = Array.from(detectedMarkers.entries());
        markers.sort(function (a, b) {
            return a[1].number - b[1].number;
        });

        var listHtml = '';
        markers.forEach(function (marker) {
            var id = marker[0];
            var data = marker[1];
            var truncated = data.text.length > 100 ? data.text.substring(0, 100) + '...' : data.text;

            listHtml += '<div class="bg-white p-3 rounded border border-gray-200">';
            listHtml += '<div class="flex items-center mb-1">';
            listHtml += '<span class="inline-flex items-center justify-center w-6 h-6 bg-amber-100 text-amber-800 text-xs font-bold rounded-full mr-2">';
            listHtml += data.number;
            listHtml += '</span>';
            listHtml += '<span class="font-medium text-gray-900">' + id + '</span>';
            listHtml += '</div>';
            listHtml += '<div class="text-xs text-gray-600 leading-relaxed">"' + truncated + '"</div>';
            listHtml += '</div>';
        });

        list.innerHTML = listHtml;
    } else {
        list.innerHTML = '<p class="text-gray-500 text-center">No markers found</p>';
    }

    modal.classList.remove('hidden');
};

// Close preview
window.closePreview = function () {
    var modal = document.getElementById('preview-modal');
    if (modal) modal.classList.add('hidden');
};

// Show notification
function showNotification(message, type) {
    var colors = {
        info: 'bg-blue-600',
        success: 'bg-green-600',
        warning: 'bg-yellow-600',
        error: 'bg-red-600'
    };

    var div = document.createElement('div');
    div.className = 'fixed bottom-4 right-4 ' + colors[type || 'info'] + ' text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300';
    div.innerHTML = message;

    document.body.appendChild(div);

    setTimeout(function () {
        div.style.opacity = '0';
        setTimeout(function () {
            div.remove();
        }, 300);
    }, 3000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closePreview();
    }
});

// Debug function
window.debugForm = function () {
    console.log('=== Form Debug Info ===');
    console.log('Editor initialized:', !!passageEditor);

    if (passageEditor) {
        console.log('Content length:', passageEditor.getContent().length);
        console.log('Markers found:', detectedMarkers.size);
    }

    var form = document.getElementById('passageForm');
    if (form) {
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);

        var formData = new FormData(form);
        console.log('Form fields:');
        for (var pair of formData.entries()) {
            console.log('  ' + pair[0] + ':', pair[1].substring(0, 50) + '...');
        }
    }
};