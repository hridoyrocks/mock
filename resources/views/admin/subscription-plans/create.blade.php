<x-admin-layout>
    <x-slot:title>Create Subscription Plan</x-slot>
    
    <x-slot:header>
        <div class="flex items-center">
            <a href="{{ route('admin.subscription-plans.index') }}" class="text-red-600 hover:text-red-600 mr-4 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                Create New Subscription Plan
            </h1>
        </div>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Basic Information Card --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition-transform duration-300">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Basic Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Plan Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200"
                                   placeholder="e.g., Premium Plan">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">URL Slug</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500 font-mono text-sm">plans/</span>
                                <input type="text" name="slug" value="{{ old('slug') }}" required
                                       class="w-full pl-16 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200 font-mono"
                                       placeholder="premium">
                            </div>
                            @error('slug')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200"
                                      placeholder="Perfect for serious IELTS candidates...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Pricing Card --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition-transform duration-300">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pricing Details
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Regular Price (BDT)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500 font-bold">৳</span>
                                <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="0.01" required
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring focus:ring-green-200 transition-all duration-200 text-2xl font-bold"
                                       placeholder="999">
                            </div>
                            @error('price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Discount Price (Optional)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500 font-bold">৳</span>
                                <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" step="0.01"
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring focus:ring-green-200 transition-all duration-200 text-2xl font-bold"
                                       placeholder="799">
                            </div>
                            <p class="text-sm text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Leave empty if no discount
                            </p>
                            @error('discount_price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Duration</label>
                                <div class="relative">
                                    <input type="number" name="duration_days" value="{{ old('duration_days', 30) }}" min="1" required
                                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring focus:ring-green-200 transition-all duration-200">
                                    <span class="absolute right-4 top-3.5 text-gray-500 text-sm">days</span>
                                </div>
                                @error('duration_days')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring focus:ring-green-200 transition-all duration-200">
                                @error('sort_order')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Features Selection Card --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden lg:col-span-2">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Select Features
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($features as $feature)
                            <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-blue-400 transition-all duration-200" id="feature-container-{{ $feature->id }}">
                                <div class="flex items-start">
                                    <input type="checkbox" name="features[{{ $feature->id }}][enabled]" value="1"
                                           id="feature-{{ $feature->id }}"
                                           class="feature-checkbox mt-1 w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           data-feature-id="{{ $feature->id }}">
                                    <label for="feature-{{ $feature->id }}" class="ml-3 flex-1 cursor-pointer">
                                        <div class="flex items-center">
                                            @if($feature->icon)
                                                <i class="{{ $feature->icon }} text-blue-600 mr-2"></i>
                                            @endif
                                            <span class="font-bold text-gray-900">{{ $feature->name }}</span>
                                        </div>
                                        @if($feature->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $feature->description }}</p>
                                        @endif
                                    </label>
                                </div>
                                
                                <div class="ml-8 mt-3 grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Value</label>
                                        <input type="text" name="features[{{ $feature->id }}][value]"
                                               id="feature-value-{{ $feature->id }}"
                                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"
                                               placeholder="unlimited"
                                               disabled>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Limit</label>
                                        <input type="number" name="features[{{ $feature->id }}][limit]"
                                               id="feature-limit-{{ $feature->id }}"
                                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"
                                               placeholder="10"
                                               disabled>
                                    </div>
                                </div>
                                <input type="hidden" name="features[{{ $feature->id }}][id]" value="{{ $feature->id }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Options Card --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden lg:col-span-2">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Plan Options
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <label class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3">
                                <span class="font-bold text-gray-900">Active</span>
                                <p class="text-sm text-gray-600">Make this plan visible to users immediately</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1"
                                   class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3">
                                <span class="font-bold text-gray-900">Featured</span>
                                <p class="text-sm text-gray-600">Highlight this plan as most popular</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.subscription-plans.index') }}" 
                   class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-bold flex items-center transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Plan
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