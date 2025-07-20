<x-admin-layout>
    <x-slot:title>Edit Subscription Plan</x-slot>
    
    <x-slot:header>
        <div class="flex items-center">
            <a href="{{ route('admin.subscription-plans.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Subscription Plan</h1>
        </div>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('admin.subscription-plans.update', $subscriptionPlan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                            <input type="text" name="name" value="{{ old('name', $subscriptionPlan->name) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $subscriptionPlan->slug) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('slug')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $subscriptionPlan->description) }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (BDT)</label>
                            <input type="number" name="price" value="{{ old('price', $subscriptionPlan->price) }}" min="0" step="0.01" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Price (BDT)</label>
                            <input type="number" name="discount_price" value="{{ old('discount_price', $subscriptionPlan->discount_price) }}" min="0" step="0.01"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Leave empty if no discount</p>
                            @error('discount_price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Days)</label>
                            <input type="number" name="duration_days" value="{{ old('duration_days', $subscriptionPlan->duration_days) }}" min="1" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('duration_days')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $subscriptionPlan->sort_order) }}" min="0" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('sort_order')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Features</h2>
                    
                    <div class="space-y-4">
                        @foreach($features as $feature)
                        <div class="border rounded-lg p-4" id="feature-container-{{ $feature->id }}">
                            <div class="flex items-start">
                                <input type="checkbox" name="features[{{ $feature->id }}][enabled]" value="1"
                                       id="feature-{{ $feature->id }}"
                                       {{ isset($planFeatures[$feature->id]) ? 'checked' : '' }}
                                       class="feature-checkbox mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       data-feature-id="{{ $feature->id }}">
                                <label for="feature-{{ $feature->id }}" class="ml-3 flex-1 cursor-pointer">
                                    <span class="font-medium text-gray-900">{{ $feature->name }}</span>
                                    @if($feature->description)
                                        <p class="text-sm text-gray-500">{{ $feature->description }}</p>
                                    @endif
                                </label>
                            </div>
                            
                            <div class="ml-7 mt-3 grid grid-cols-2 gap-4 feature-inputs" data-feature-id="{{ $feature->id }}">
                                <div>
                                    <label class="block text-sm text-gray-700">Value</label>
                                    <input type="text" name="features[{{ $feature->id }}][value]"
                                           id="feature-value-{{ $feature->id }}"
                                           value="{{ $planFeatures[$feature->id]['value'] ?? '' }}"
                                           class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           placeholder="e.g., unlimited, true, 10"
                                           {{ !isset($planFeatures[$feature->id]) ? 'disabled' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">Limit (optional)</label>
                                    <input type="number" name="features[{{ $feature->id }}][limit]"
                                           id="feature-limit-{{ $feature->id }}"
                                           value="{{ $planFeatures[$feature->id]['limit'] ?? '' }}"
                                           class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                           placeholder="Numeric limit"
                                           {{ !isset($planFeatures[$feature->id]) ? 'disabled' : '' }}>
                                </div>
                            </div>
                            <input type="hidden" name="features[{{ $feature->id }}][id]" value="{{ $feature->id }}">
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Options --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Options</h2>
                    
                    <div class="space-y-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ $subscriptionPlan->is_active ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-gray-700">Active (visible to users)</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ $subscriptionPlan->is_featured ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-gray-700">Featured (highlight as most popular)</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('admin.subscription-plans.index') }}" 
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Update Plan
                </button>
            </div>
        </form>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle feature checkbox changes
            const checkboxes = document.querySelectorAll('.feature-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const featureId = this.dataset.featureId;
                    const valueInput = document.getElementById('feature-value-' + featureId);
                    const limitInput = document.getElementById('feature-limit-' + featureId);
                    const container = document.getElementById('feature-container-' + featureId);
                    
                    if (this.checked) {
                        valueInput.disabled = false;
                        limitInput.disabled = false;
                        container.classList.remove('opacity-50');
                    } else {
                        valueInput.disabled = true;
                        limitInput.disabled = true;
                        valueInput.value = '';
                        limitInput.value = '';
                        container.classList.add('opacity-50');
                    }
                });
                
                // Initialize on page load
                const featureId = checkbox.dataset.featureId;
                const container = document.getElementById('feature-container-' + featureId);
                if (!checkbox.checked) {
                    container.classList.add('opacity-50');
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>