<x-layout>
    <x-slot:title>Debug TinyMCE Config</x-slot>
    
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">TinyMCE Configuration Debug</h1>
        
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <strong>ENV TINYMCE_API_KEY:</strong> 
                <code class="bg-gray-100 px-2 py-1 rounded">{{ env('TINYMCE_API_KEY', 'NOT SET') }}</code>
            </div>
            
            <div>
                <strong>Config services.tinymce.api_key:</strong> 
                <code class="bg-gray-100 px-2 py-1 rounded">{{ config('services.tinymce.api_key', 'NOT SET') }}</code>
            </div>
            
            <div>
                <strong>All Tinymce Config:</strong>
                <pre class="bg-gray-100 p-3 rounded overflow-x-auto">{{ json_encode(config('services.tinymce'), JSON_PRETTY_PRINT) }}</pre>
            </div>
            
            <div>
                <strong>Expected Script Tag:</strong>
                <code class="bg-gray-100 px-2 py-1 rounded block overflow-x-auto">
                    https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js
                </code>
            </div>
            
            <hr>
            
            <div>
                <h3 class="font-bold mb-2">Test TinyMCE:</h3>
                <textarea id="test-editor" class="w-full"></textarea>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#test-editor',
            height: 300,
            menubar: false,
            plugins: ['lists', 'link'],
            toolbar: 'bold italic | bullist numlist | link',
            setup: function(editor) {
                editor.on('init', function() {
                    console.log('TinyMCE initialized successfully!');
                });
            }
        });
    </script>
    @endpush
</x-layout>
