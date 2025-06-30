<x-layout>
    <x-slot:title>Add Reading Passage</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">Add Reading Passage</h1>
                        <p class="text-green-100 text-sm mt-1">{{ $testSet->title }}</p>
                    </div>
                    <a href="{{ route('admin.test-sets.passages', $testSet) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('admin.passages.store', $testSet) }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Passage Details</h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Passage Number -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Passage Number <span class="text-red-500">*</span>
                            </label>
                            <select name="part_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                                <option value="1" {{ $nextPassageNumber == 1 ? 'selected' : '' }}>Passage 1</option>
                                <option value="2" {{ $nextPassageNumber == 2 ? 'selected' : '' }}>Passage 2</option>
                                <option value="3" {{ $nextPassageNumber == 3 ? 'selected' : '' }}>Passage 3</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Passage Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., The History of Aviation" required>
                        </div>
                    </div>
                    
                    <!-- Passage Content -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Passage Content <span class="text-red-500">*</span>
                        </label>
                        <div class="border border-gray-300 rounded-md overflow-hidden" style="height: 500px;">
                            <textarea id="content" name="content" class="tinymce-passage"></textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Paste your reading passage here. You can format text and add images.
                        </p>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.test-sets.passages', $testSet) }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700">
                            Create Passage
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '.tinymce-passage',
            height: '100%',
            menubar: true,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount paste',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | image media | removeformat | code | fullscreen',
            paste_data_images: true,
            images_upload_handler: function(blobInfo, success, failure) {
                // Convert to base64
                const reader = new FileReader();
                reader.onload = function() {
                    success(reader.result);
                };
                reader.readAsDataURL(blobInfo.blob());
            },
            content_style: `
                body { 
                    font-family: Georgia, 'Times New Roman', serif; 
                    font-size: 16px; 
                    line-height: 1.8; 
                    color: #1F2937;
                    padding: 20px;
                    max-width: 100%;
                }
                h1, h2, h3 { 
                    font-weight: bold;
                    margin: 1em 0 0.5em 0;
                }
                p {
                    margin: 1em 0;
                }
                img {
                    max-width: 100%;
                    height: auto;
                    display: block;
                    margin: 1em auto;
                }
            `,
            branding: false,
            resize: false
        });
    </script>
    @endpush
</x-layout>