<x-admin-layout>
    <x-slot:title>Edit Token Package</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Edit Token Package</h2>
            </div>
            
            <form action="{{ route('admin.token-packages.update', $tokenPackage) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Package Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tokenPackage->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="e.g., Starter Pack">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="tokens_count" class="block text-sm font-medium text-gray-700">Base Tokens</label>
                        <input type="number" name="tokens_count" id="tokens_count" value="{{ old('tokens_count', $tokenPackage->tokens_count) }}" required min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('tokens_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="bonus_tokens" class="block text-sm font-medium text-gray-700">Bonus Tokens</label>
                        <input type="number" name="bonus_tokens" id="bonus_tokens" value="{{ old('bonus_tokens', $tokenPackage->bonus_tokens) }}" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('bonus_tokens')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (USD)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price', $tokenPackage->price) }}" required min="0.01" step="0.01"
                               class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="9.99">
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $tokenPackage->sort_order) }}" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Live Preview -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Preview</h3>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="text-lg font-semibold" id="preview-name">{{ $tokenPackage->name }}</div>
                        <div class="mt-2">
                            <span class="text-2xl font-bold" id="preview-tokens">{{ $tokenPackage->tokens_count }}</span>
                            <span class="text-gray-500">tokens</span>
                            <span class="text-green-600 text-sm ml-2" id="preview-bonus" style="{{ $tokenPackage->bonus_tokens > 0 ? '' : 'display: none;' }}">+{{ $tokenPackage->bonus_tokens }} bonus</span>
                        </div>
                        <div class="mt-2 text-xl font-bold">$<span id="preview-price">{{ number_format($tokenPackage->price, 2) }}</span></div>
                        <div class="mt-1 text-sm text-gray-500">
                            $<span id="preview-per-token">{{ number_format($tokenPackage->price_per_token, 3) }}</span> per token
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.token-packages.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                        Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Live preview
        document.getElementById('name').addEventListener('input', function() {
            document.getElementById('preview-name').textContent = this.value || 'Package Name';
        });
        
        function updatePreview() {
            const tokens = parseInt(document.getElementById('tokens_count').value) || 0;
            const bonus = parseInt(document.getElementById('bonus_tokens').value) || 0;
            const price = parseFloat(document.getElementById('price').value) || 0;
            const total = tokens + bonus;
            
            document.getElementById('preview-tokens').textContent = tokens;
            document.getElementById('preview-price').textContent = price.toFixed(2);
            
            if (bonus > 0) {
                document.getElementById('preview-bonus').style.display = 'inline';
                document.getElementById('preview-bonus').textContent = `+${bonus} bonus`;
            } else {
                document.getElementById('preview-bonus').style.display = 'none';
            }
            
            if (total > 0) {
                document.getElementById('preview-per-token').textContent = (price / total).toFixed(3);
            }
        }
        
        document.getElementById('tokens_count').addEventListener('input', updatePreview);
        document.getElementById('bonus_tokens').addEventListener('input', updatePreview);
        document.getElementById('price').addEventListener('input', updatePreview);
    </script>
    @endpush
</x-admin-layout>
