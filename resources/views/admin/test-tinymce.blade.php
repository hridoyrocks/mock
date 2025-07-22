<x-layout>
    <x-slot:title>Test TinyMCE Image Upload</x-slot>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-4">Test TinyMCE Image Upload</h1>
            
            <form method="POST" action="#">
                @csrf
                <textarea id="content" name="content" class="tinymce"></textarea>
                
                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '.tinymce',
            height: 400,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help',
            images_upload_url: '{{ route("admin.questions.upload.image") }}',
            images_upload_base_path: '/',
            images_upload_credentials: true,
            automatic_uploads: true,
            images_upload_handler: function (blobInfo, success, failure, progress) {
                return new Promise(function(resolve, reject) {
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route("admin.questions.upload.image") }}');
                    
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    
                    xhr.upload.onprogress = function (e) {
                        progress(e.loaded / e.total * 100);
                    };
                    
                    xhr.onload = function() {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }
                        
                        try {
                            const json = JSON.parse(xhr.responseText);
                            console.log('Upload response:', json);
                            
                            if (!json || !json.success) {
                                reject('Upload failed: ' + (json.message || 'Unknown error'));
                                return;
                            }
                            
                            // TinyMCE expects an object with location property
                            resolve(json.url);
                        } catch (e) {
                            reject('Invalid JSON response: ' + xhr.responseText);
                        }
                    };
                    
                    xhr.onerror = function () {
                        reject('Image upload failed due to a network error.');
                    };
                    
                    const formData = new FormData();
                    formData.append('image', blobInfo.blob(), blobInfo.filename());
                    
                    xhr.send(formData);
                });
            }
        });
    </script>
    @endpush
</x-layout>
