<x-admin-layout>
    <x-slot:title>Edit Feature</x-slot>
    
    <x-slot:header>
        <div class="flex items-center">
            <a href="{{ route('admin.subscription-features.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Subscription Feature</h1>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('admin.subscription-features.update', $subscriptionFeature) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Feature Name</label>
                            <input type="text" name="name" value="{{ old('name', $subscriptionFeature->name) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Feature Key</label>
                            <input type="text" name="key" value="{{ old('key', $subscriptionFeature->key) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Use lowercase with underscores</p>
                            @error('key')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $subscriptionFeature->description) }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class</label>
                            <input type="text" name="icon" value="{{ old('icon', $subscriptionFeature->icon) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., fas fa-robot">
                            <p class="text-sm text-gray-500 mt-1">Font Awesome icon class (optional)</p>
                            @error('icon')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.subscription-features.index') }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Update Feature
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>