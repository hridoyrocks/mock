<x-layout>
    <x-slot:title>Manage Part Audios - {{ $testSet->title }}</x-slot>
    
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">Manage Part Audios</h1>
                        <p class="text-purple-100 text-sm mt-1">{{ $testSet->title }}</p>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">How Part Audios Work</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Upload one audio file per part (Part 1-4)</li>
                            <li>All questions in a part will automatically use that part's audio</li>
                            <li>No need to upload audio for individual questions anymore!</li>
                            <li>You can update or replace part audios anytime</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Part Audio Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @for($part = 1; $part <= 4; $part++)
                @php
                    $partAudio = $partAudios[$part] ?? null;
                    $questionCount = $testSet->questions()->where('part_number', $part)->count();
                @endphp
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Part {{ $part }}</h3>
                            <span class="text-sm text-gray-500">{{ $questionCount }} questions</span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if($partAudio)
                            <!-- Audio exists -->
                            <div class="space-y-4">
                                <!-- Audio Player -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <audio controls class="w-full mb-2">
                                        <source src="{{ Storage::url($partAudio->audio_path) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Duration: {{ $partAudio->formatted_duration }}</span>
                                        <span>Size: {{ $partAudio->formatted_size }}</span>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex space-x-3">
                                    <button onclick="replaceAudio({{ $part }})" 
                                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                                        Replace Audio
                                    </button>
                                    <button onclick="deleteAudio({{ $testSet->id }}, {{ $part }})" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium">
                                        Delete
                                    </button>
                                </div>
                                
                                <!-- Transcript -->
                                @if($partAudio->transcript)
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">Transcript:</h4>
                                        <div class="bg-gray-50 rounded p-3 text-sm text-gray-600 max-h-32 overflow-y-auto">
                                            {{ Str::limit($partAudio->transcript, 200) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- No audio -->
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No audio uploaded</h3>
                                <p class="mt-1 text-sm text-gray-500">Upload an audio file for Part {{ $part }}</p>
                                <div class="mt-6">
                                    <button onclick="uploadAudio({{ $part }})" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Upload Audio
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="upload-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Upload Audio for Part <span id="upload-part-number"></span></h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="upload-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="part-number-input" name="part_number">
                
                <div class="space-y-4">
                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Audio File</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="audio-file" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                        <span>Upload a file</span>
                                        <input id="audio-file" name="audio" type="file" class="sr-only" accept=".mp3,.wav,.ogg" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">MP3, WAV, OGG up to 50MB</p>
                            </div>
                        </div>
                        <div id="file-info" class="mt-2 text-sm text-gray-600"></div>
                    </div>
                    
                    <!-- Transcript -->
                    <div>
                        <label for="transcript" class="block text-sm font-medium text-gray-700 mb-2">Transcript (Optional)</label>
                        <textarea id="transcript" name="transcript" rows="4" 
                                  class="shadow-sm focus:ring-purple-500 focus:border-purple-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md"
                                  placeholder="Enter the audio transcript..."></textarea>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div id="upload-progress" class="hidden">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Uploading...</span>
                            <span class="text-sm font-medium text-gray-700" id="progress-percent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="progress-bar" class="bg-purple-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" id="upload-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:col-start-2 sm:text-sm">
                        Upload
                    </button>
                    <button type="button" onclick="closeUploadModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentPart = null;
        
        function uploadAudio(partNumber) {
            currentPart = partNumber;
            document.getElementById('upload-part-number').textContent = partNumber;
            document.getElementById('part-number-input').value = partNumber;
            document.getElementById('upload-modal').classList.remove('hidden');
        }
        
        function replaceAudio(partNumber) {
            uploadAudio(partNumber);
        }
        
        function closeUploadModal() {
            document.getElementById('upload-modal').classList.add('hidden');
            document.getElementById('upload-form').reset();
            document.getElementById('file-info').textContent = '';
            document.getElementById('upload-progress').classList.add('hidden');
        }
        
        function deleteAudio(testSetId, partNumber) {
            if (!confirm('Are you sure you want to delete this audio? This cannot be undone.')) {
                return;
            }
            
            fetch(`/admin/test-sets/${testSetId}/part-audios/${partNumber}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to delete audio');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the audio');
            });
        }
        
        // File input change handler
        document.getElementById('audio-file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileInfo = `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                document.getElementById('file-info').textContent = fileInfo;
            }
        });
        
        // Form submit handler
        document.getElementById('upload-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const uploadBtn = document.getElementById('upload-btn');
            const progressDiv = document.getElementById('upload-progress');
            const progressBar = document.getElementById('progress-bar');
            const progressPercent = document.getElementById('progress-percent');
            
            // Disable button and show progress
            uploadBtn.disabled = true;
            uploadBtn.textContent = 'Uploading...';
            progressDiv.classList.remove('hidden');
            
            // Create XMLHttpRequest for progress tracking
            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressPercent.textContent = percentComplete + '%';
                }
            });
            
            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to upload audio');
                        resetUploadForm();
                    }
                } else {
                    alert('Failed to upload audio');
                    resetUploadForm();
                }
            });
            
            xhr.addEventListener('error', function() {
                alert('An error occurred while uploading the audio');
                resetUploadForm();
            });
            
            xhr.open('POST', `/admin/test-sets/{{ $testSet->id }}/part-audios`);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(formData);
        });
        
        function resetUploadForm() {
            const uploadBtn = document.getElementById('upload-btn');
            const progressDiv = document.getElementById('upload-progress');
            const progressBar = document.getElementById('progress-bar');
            const progressPercent = document.getElementById('progress-percent');
            
            uploadBtn.disabled = false;
            uploadBtn.textContent = 'Upload';
            progressDiv.classList.add('hidden');
            progressBar.style.width = '0%';
            progressPercent.textContent = '0%';
        }
        
        // Drag and drop support
        const dropZone = document.querySelector('.border-dashed');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-purple-400', 'bg-purple-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-purple-400', 'bg-purple-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                document.getElementById('audio-file').files = files;
                const event = new Event('change', { bubbles: true });
                document.getElementById('audio-file').dispatchEvent(event);
            }
        }
    </script>
    @endpush
</x-layout>