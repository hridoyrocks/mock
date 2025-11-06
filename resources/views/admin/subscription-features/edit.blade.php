<x-admin-layout>
    <x-slot:title>Edit Feature</x-slot>
    
    <x-slot:header>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.subscription-features.index') }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Feature</h1>
                    <p class="text-sm text-gray-600 mt-1">Update subscription feature details</p>
                </div>
            </div>
            
            {{-- Feature Badge --}}
            <div class="flex items-center space-x-2 px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-lg">
                @if($subscriptionFeature->icon)
                    <i class="{{ $subscriptionFeature->icon }} text-indigo-600"></i>
                @endif
                <code class="text-sm font-mono text-indigo-700">{{ $subscriptionFeature->key }}</code>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Feature Usage Info --}}
        @if($subscriptionFeature->plans->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-900">This feature is currently used in {{ $subscriptionFeature->plans->count() }} plan(s)</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($subscriptionFeature->plans as $plan)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-700">
                                {{ $plan->name }}
                                @if($plan->pivot->value && $plan->pivot->value !== 'true')
                                    <span class="ml-1 text-blue-600">({{ $plan->pivot->value }})</span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs text-blue-700">⚠️ Changing the feature key will affect all plans using this feature</p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-amber-900">This feature is not assigned to any plan</p>
                    <p class="mt-1 text-xs text-amber-700">You can safely edit or delete this feature</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Edit Form --}}
        <form action="{{ route('admin.subscription-features.update', $subscriptionFeature) }}" method="POST" id="featureForm">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Feature Information</h2>
                    <p class="text-sm text-gray-600 mt-1">Update the details for this subscription feature</p>
                </div>
                
                <div class="p-6 space-y-6">
                    {{-- Feature Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Feature Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $subscriptionFeature->name) }}" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                               placeholder="e.g., AI Writing Evaluation">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    {{-- Feature Key --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Feature Key <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="key" 
                                   value="{{ old('key', $subscriptionFeature->key) }}" 
                                   required
                                   pattern="[a-z0-9_]+"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors font-mono text-sm pr-24"
                                   placeholder="e.g., ai_writing_evaluation">
                            @if($subscriptionFeature->plans->count() > 0)
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                        In Use
                                    </span>
                                </div>
                            @endif
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Use lowercase letters, numbers, and underscores only
                        </p>
                        @if($subscriptionFeature->plans->count() > 0)
                            <p class="mt-1.5 text-xs text-amber-600 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Warning: Changing this key will affect {{ $subscriptionFeature->plans->count() }} plan(s)
                            </p>
                        @endif
                        @error('key')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                                  placeholder="Brief description of what this feature provides...">{{ old('description', $subscriptionFeature->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Icon Class --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Icon Class (Optional)
                        </label>
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg border-2 border-gray-300 bg-gray-50" id="iconPreview">
                                    @if($subscriptionFeature->icon)
                                        <i class="{{ $subscriptionFeature->icon }} text-xl text-gray-600"></i>
                                    @else
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="text" 
                                       name="icon" 
                                       value="{{ old('icon', $subscriptionFeature->icon) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors font-mono text-sm"
                                       placeholder="e.g., fas fa-robot"
                                       onkeyup="updateIconPreview(this.value)">
                                <p class="mt-1.5 text-xs text-gray-500">Font Awesome icon class</p>
                            </div>
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        {{-- Common Icons --}}
                        <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-xs font-medium text-gray-700 mb-3">Quick Select Icons:</p>
                            <div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-12 gap-2">
                                @php
                                    $commonIcons = [
                                        'fas fa-robot', 'fas fa-chart-line', 'fas fa-headset', 'fas fa-download',
                                        'fas fa-certificate', 'fas fa-infinity', 'fas fa-check', 'fas fa-star',
                                        'fas fa-bolt', 'fas fa-shield-alt', 'fas fa-users', 'fas fa-book',
                                        'fas fa-microphone', 'fas fa-video', 'fas fa-graduation-cap', 'fas fa-coins',
                                        'fas fa-clipboard-list', 'fas fa-tasks', 'fas fa-calendar-alt', 'fas fa-comments',
                                        'fas fa-file-alt', 'fas fa-stopwatch', 'fas fa-check-circle', 'fas fa-user-tie'
                                    ];
                                @endphp
                                @foreach($commonIcons as $iconClass)
                                    <button type="button" 
                                            onclick="selectIcon('{{ $iconClass }}')"
                                            class="flex items-center justify-center p-2 bg-white border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group {{ $subscriptionFeature->icon === $iconClass ? 'border-indigo-500 bg-indigo-50' : '' }}"
                                            title="{{ $iconClass }}">
                                        <i class="{{ $iconClass }} text-gray-600 group-hover:text-indigo-600 {{ $subscriptionFeature->icon === $iconClass ? 'text-indigo-600' : '' }}"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Form Actions --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between rounded-b-lg">
                    <a href="{{ route('admin.subscription-features.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <div class="flex items-center space-x-3">
                        @if($subscriptionFeature->plans->count() === 0)
                            <form action="{{ route('admin.subscription-features.destroy', $subscriptionFeature) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this feature?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        @endif
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Feature
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    @push('scripts')
    <script>
        function selectIcon(iconClass) {
            document.querySelector('input[name="icon"]').value = iconClass;
            updateIconPreview(iconClass);
            
            // Update button states
            document.querySelectorAll('[onclick^="selectIcon"]').forEach(btn => {
                btn.classList.remove('border-indigo-500', 'bg-indigo-50');
                const icon = btn.querySelector('i');
                icon.classList.remove('text-indigo-600');
                icon.classList.add('text-gray-600');
            });
            
            event.target.closest('button').classList.add('border-indigo-500', 'bg-indigo-50');
            event.target.closest('button').querySelector('i').classList.add('text-indigo-600');
            event.target.closest('button').querySelector('i').classList.remove('text-gray-600');
        }
        
        function updateIconPreview(iconClass) {
            const preview = document.getElementById('iconPreview');
            if (iconClass && iconClass.trim()) {
                preview.innerHTML = `<i class="${iconClass} text-xl text-gray-600"></i>`;
            } else {
                preview.innerHTML = `
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                `;
            }
        }
    </script>
    @endpush
</x-admin-layout>
