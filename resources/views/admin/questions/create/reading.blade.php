<x-layout>
    <x-slot:title>Add Reading Passage - {{ $testSet->title }}</x-slot>
    
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">📄 Add Reading Passage</h1>
                        <p class="text-green-100 text-sm mt-1">{{ $testSet->title }} - Reading Section</p>
                    </div>
                    <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Test Set
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-green-600">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-medium">1</div>
                        <span class="ml-2 text-sm font-medium">Add Passage</span>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="h-1 bg-gray-200 rounded"></div>
                    </div>
                    <div class="flex items-center text-gray-400">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-500 rounded-full text-sm font-medium">2</div>
                        <span class="ml-2 text-sm">Add Questions</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.questions.store') }}" method="POST" id="passageForm">
                @csrf
                <input type="hidden" name="test_set_id" value="{{ $testSet->id }}">
                <input type="hidden" name="question_type" value="passage">
                <input type="hidden" name="part_number" value="1">
                <input type="hidden" name="marks" value="0">
                
                <!-- Main Form Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">
                                <svg class="inline-block w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Reading Passage Content
                            </h3>
                            <div class="text-sm text-green-600 font-medium">
                                Passage Order: #{{ $nextQuestionNumber }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Title and Order Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Add title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="instructions" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="e.g., Passage 1, The History of Aviation"
                                       value="{{ old('instructions', 'Passage ' . $nextQuestionNumber) }}" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Add order <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="order_number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       value="{{ old('order_number', $nextQuestionNumber) }}" min="0" required>
                            </div>
                        </div>

                        <!-- Marker Instructions -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-blue-900 mb-2">How to Mark Answer Locations:</h4>
                                    <ul class="text-sm text-blue-800 space-y-1">
                                        <li>• Mark answer locations using: <code class="bg-white px-2 py-0.5 rounded text-xs">{{Q1}}text{{Q1}}</code></li>
                                        <li>• Example: The Wright brothers achieved <code class="bg-white px-2 py-0.5 rounded text-xs">{{Q1}}first powered flight{{Q1}}</code> in 1903.</li>
                                        <li>• Use Q1, Q2, Q3... for different questions</li>
                                        <li>• These markers will be linked to questions later</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Passage Content Editor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Add Content <span class="text-red-500">*</span>
                                <svg class="inline-block w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </label>
                            
                            <!-- Editor Toolbar -->
                            <div class="mb-3 flex flex-wrap gap-2">
                                <button type="button" onclick="insertMarker()" 
                                        class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Mark Answer Location
                                </button>
                                <button type="button" onclick="previewPassage()" 
                                        class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Preview Markers
                                </button>
                                <button type="button" onclick="clearAllMarkers()" 
                                        class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Clear Markers
                                </button>
                            </div>
                            
                            <textarea id="passageEditor" name="content" class="tinymce-passage" required>{{ old('content') }}</textarea>
                            
                            <!-- Word Count & Markers Info -->
                            <div class="flex justify-between items-center mt-3 text-sm text-gray-500">
                                <span>Words: <span id="passage-word-count">0</span></span>
                                <span id="markers-count">No markers added yet</span>
                            </div>
                        </div>

                        <!-- Markers List Panel -->
                        <div id="markers-panel" class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200 hidden">
                            <h4 class="font-semibold text-sm mb-3 flex items-center text-green-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                📍 Marked Question Locations:
                            </h4>
                            <div id="markers-list" class="space-y-2">
                                <p class="text-sm text-gray-500">No markers added yet</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" class="flex-1 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Passage
                            </button>
                            <button type="button" onclick="previewPassage()" class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </button>
                            <a href="{{ route('admin.test-sets.show', $testSet) }}" class="flex-1 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 transition-colors text-center">
                                Cancel
                            </a>
                        </div>
                        
                        <!-- Next Step Info -->
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex items-center text-blue-800 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span><strong>Next:</strong> After saving this passage, you'll be able to add questions that reference the marked locations ({{Q1}}, {{Q2}}, etc.)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Preview Modal -->
    <div id="preview-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">📄 Passage Preview</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="preview-content" class="border rounded-lg p-6 bg-gray-50 max-h-96 overflow-y-auto">
                <!-- Preview content will be inserted here -->
            </div>
            
            <!-- Markers Summary -->
            <div id="preview-markers" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
                <h4 class="font-semibold text-sm mb-2 text-green-800">📍 Question Markers Found:</h4>
                <div id="preview-markers-list" class="text-sm text-green-700">
                    <!-- Markers will be listed here -->
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* TinyMCE Editor Styling */
        .tox .tox-editor-header {
            border-bottom: 1px solid #e5e7eb !important;
        }
        
        .tox.tox-tinymce {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
        }
        
        /* Marker highlighting styles */
        .marker-highlight {
            background-color: #fef3c7;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .marker-text {
            background-color: #dcfce7;
            padding: 1px 4px;
            border-radius: 2px;
            border: 1px solid #16a34a;
        }
        
        /* Marker list styles */
        .marker-item {
            background: white;
            border: 1px solid #d1fae5;
            border-radius: 6px;
            padding: 8px 12px;
            transition: all 0.2s;
        }
        
        .marker-item:hover {
            border-color: #16a34a;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
    @endpush

    @push('scripts')
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        let passageEditor;
        let markerCounter = 1;
        let detectedMarkers = new Set();

        // Initialize TinyMCE
        document.addEventListener('DOMContentLoaded', function() {
            initializePassageEditor();
        });

        function initializePassageEditor() {
            tinymce.init({
                selector: '.tinymce-passage',
                height: 500,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | fullscreen preview',
                content_style: `
                    body { 
                        font-family: Georgia, 'Times New Roman', serif; 
                        font-size: 16px; 
                        line-height: 1.8; 
                        color: #333;
                        padding: 20px;
                        max-width: none;
                    }
                    p { 
                        margin-bottom: 16px; 
                        text-indent: 2em;
                    }
                    p:first-child, h1 + p, h2 + p, h3 + p {
                        text-indent: 0;
                    }
                    h1, h2, h3 {
                        text-align: center;
                        margin-bottom: 20px;
                        text-indent: 0;
                    }
                `,
                setup: function(editor) {
                    passageEditor = editor;

                    editor.on('input change', function() {
                        updateWordCount();
                        detectMarkers();
                    });

                    editor.on('init', function() {
                        updateWordCount();
                        detectMarkers();
                    });
                }
            });
        }

        // Insert marker functionality
        function insertMarker() {
            if (!passageEditor) return;

            const selection = passageEditor.selection.getContent({ format: 'text' });
            if (!selection) {
                showNotification('Please select text in the passage first!', 'warning');
                return;
            }

            // Find next available marker number
            const usedNumbers = Array.from(detectedMarkers).map(m => parseInt(m.replace('Q', '')));
            const nextNumber = usedNumbers.length > 0 ? Math.max(...usedNumbers) + 1 : markerCounter;

            const markerId = `Q${nextNumber}`;
            const markedContent = `{{${markerId}}}${selection}{{${markerId}}}`;
            
            passageEditor.selection.setContent(markedContent);
            
            showNotification(`Answer location marked as ${markerId}`, 'success');
            markerCounter = nextNumber + 1;
        }

        // Detect markers in content
        function detectMarkers() {
            if (!passageEditor) return;

            const content = passageEditor.getContent({ format: 'text' });
            const markerRegex = /\{\{(Q\d+)\}\}/g;
            const foundMarkers = new Set();
            let match;

            while ((match = markerRegex.exec(content)) !== null) {
                foundMarkers.add(match[1]);
            }

            detectedMarkers = foundMarkers;
            updateMarkersDisplay();
        }

        // Update markers display
        function updateMarkersDisplay() {
            const markersPanel = document.getElementById('markers-panel');
            const markersList = document.getElementById('markers-list');
            const markersCount = document.getElementById('markers-count');

            if (detectedMarkers.size > 0) {
                markersPanel.classList.remove('hidden');
                markersCount.textContent = `${detectedMarkers.size} marker(s) added`;

                const content = passageEditor.getContent({ format: 'text' });
                const markerItems = Array.from(detectedMarkers)
                    .sort((a, b) => parseInt(a.replace('Q', '')) - parseInt(b.replace('Q', '')))
                    .map(markerId => {
                        // Extract text between markers
                        const regex = new RegExp(`\\{\\{${markerId}\\}\\}(.*?)\\{\\{${markerId}\\}\\}`, 's');
                        const match = content.match(regex);
                        const text = match ? match[1].substring(0, 50) + (match[1].length > 50 ? '...' : '') : 'No text found';

                        return `
                            <div class="marker-item flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <span class="font-semibold text-green-600 mr-3">${markerId}</span>
                                    <span class="text-sm text-gray-600">"${text}"</span>
                                </div>
                                <button onclick="removeMarker('${markerId}')" 
                                        class="ml-2 text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors"
                                        title="Remove marker">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        `;
                    }).join('');

                markersList.innerHTML = markerItems;
            } else {
                markersPanel.classList.add('hidden');
                markersCount.textContent = 'No markers added yet';
                markersList.innerHTML = '<p class="text-sm text-gray-500">No markers added yet</p>';
            }
        }

        // Remove specific marker
        function removeMarker(markerId) {
            if (!confirm(`Remove marker ${markerId}?`)) return;

            let content = passageEditor.getContent();
            const regex = new RegExp(`\\{\\{${markerId}\\}\\}(.*?)\\{\\{${markerId}\\}\\}`, 'gs');
            content = content.replace(regex, '$1');
            
            passageEditor.setContent(content);
            showNotification(`Marker ${markerId} removed`, 'info');
        }

        // Clear all markers
        function clearAllMarkers() {
            if (!confirm('Are you sure you want to clear all markers? This cannot be undone.')) return;

            let content = passageEditor.getContent();
            content = content.replace(/\{\{Q\d+\}\}/g, '');
            
            passageEditor.setContent(content);
            showNotification('All markers cleared', 'info');
        }

        // Update word count
        function updateWordCount() {
            if (!passageEditor) return;

            const text = passageEditor.getContent({ format: 'text' });
            const words = text.trim().split(/\s+/).filter(word => word.length > 0);
            const wordCountEl = document.getElementById('passage-word-count');
            
            if (wordCountEl) {
                wordCountEl.textContent = words.length;
            }
        }

        // Preview passage
        function previewPassage() {
            if (!passageEditor) return;

            const modal = document.getElementById('preview-modal');
            const content = document.getElementById('preview-content');
            const markersList = document.getElementById('preview-markers-list');

            let passageContent = passageEditor.getContent();

            // Highlight markers for preview
            passageContent = passageContent.replace(/\{\{(Q\d+)\}\}(.*?)\{\{\\1\}\}/gs, function(match, marker, text) {
                return `<span class="marker-highlight">[${marker}]</span><span class="marker-text">${text}</span><span class="marker-highlight">[${marker}]</span>`;
            });

            content.innerHTML = `
                <div class="prose max-w-none">
                    ${passageContent}
                </div>
            `;

            // Show markers summary
            if (detectedMarkers.size > 0) {
                const markerSummary = Array.from(detectedMarkers)
                    .sort((a, b) => parseInt(a.replace('Q', '')) - parseInt(b.replace('Q', '')))
                    .map(markerId => {
                        const originalContent = passageEditor.getContent({ format: 'text' });
                        const regex = new RegExp(`\\{\\{${markerId}\\}\\}(.*?)\\{\\{${markerId}\\}\\}`, 's');
                        const match = originalContent.match(regex);
                        const text = match ? match[1].substring(0, 80) + (match[1].length > 80 ? '...' : '') : '';
                        
                        return `<div class="flex items-start mb-2">
                            <span class="font-medium text-green-600 mr-2">${markerId}:</span>
                            <span class="text-sm">"${text}"</span>
                        </div>`;
                    }).join('');
                
                markersList.innerHTML = markerSummary;
            } else {
                markersList.innerHTML = '<span class="text-gray-500">No markers found in the passage</span>';
            }

            modal.classList.remove('hidden');
        }

        // Close preview
        function closePreview() {
            document.getElementById('preview-modal').classList.add('hidden');
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const colors = {
                info: 'bg-blue-600',
                success: 'bg-green-600',
                warning: 'bg-yellow-600',
                error: 'bg-red-600'
            };

            const notification = document.createElement('div');
            notification.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(10px)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Form submission handler
        document.getElementById('passageForm').addEventListener('submit', function(e) {
            if (passageEditor) {
                passageEditor.save();
            }

            const content = passageEditor.getContent({ format: 'text' });
            if (!content || content.trim() === '') {
                e.preventDefault();
                showNotification('Please enter passage content', 'error');
                return false;
            }

            // Show success message
            showNotification('Saving passage...', 'info');
        });

        // ESC key handler
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePreview();
            }
        });
    </script>
    @endpush
</x-layout>