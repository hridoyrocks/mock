<x-admin-layout>
    <x-slot:title>Create Subscription Feature</x-slot>
    
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
                    <h1 class="text-2xl font-bold text-gray-900">Create New Feature</h1>
                    <p class="text-sm text-gray-600 mt-1">Add a new subscription feature to your system</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Available Features Section --}}
        @if(isset($availableFeatures) && count($availableFeatures) > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-100">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Available System Features</h2>
                            <p class="text-sm text-gray-600">Click any feature to auto-fill the form below</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ array_sum(array_map('count', $availableFeatures)) }} Available
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                @foreach($availableFeatures as $category => $features)
                <div class="mb-8 last:mb-0">
                    {{-- Category Header --}}
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-gray-900 text-white">
                            {{ $category }}
                        </span>
                        <div class="ml-3 flex-1 border-t border-gray-200"></div>
                        <span class="ml-3 text-xs text-gray-500 font-medium">{{ count($features) }} features</span>
                    </div>
                    
                    {{-- Features Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                        @foreach($features as $feature)
                        <button type="button" 
                                onclick="selectFeature('{{ addslashes($feature['key']) }}', '{{ addslashes($feature['name']) }}', '{{ addslashes($feature['description']) }}', '{{ addslashes($feature['icon']) }}')"
                                class="group relative p-4 text-left bg-gray-50 border border-gray-200 rounded-lg hover:bg-white hover:border-indigo-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-200 group-hover:border-indigo-300 group-hover:bg-indigo-50 transition-all">
                                        <i class="{{ $feature['icon'] }} text-lg text-gray-600 group-hover:text-indigo-600 transition-colors"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $feature['name'] }}
                                    </h3>
                                    <code class="inline-block mt-1 px-2 py-0.5 text-xs font-mono bg-white border border-gray-200 rounded text-gray-600 truncate max-w-full">
                                        {{ $feature['key'] }}
                                    </code>
                                    <p class="mt-2 text-xs text-gray-500 line-clamp-2">{{ $feature['description'] }}</p>
                                </div>
                            </div>
                            
                            {{-- Hover Effect --}}
                            <div class="absolute inset-0 border-2 border-indigo-500 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
                
                {{-- Custom Feature Note --}}
                <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-amber-900">Need a custom feature?</p>
                            <p class="mt-1 text-sm text-amber-700">You can manually type your own feature details in the form below if it's not listed above.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Already Created Features --}}
        @if(isset($existingFeatures) && $existingFeatures->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Already Created Features</h2>
                            <p class="text-sm text-gray-600">These features exist in your database</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $existingFeatures->count() }} Created
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    @foreach($existingFeatures as $existing)
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            @if($existing->icon)
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-gray-200">
                                        <i class="{{ $existing->icon }} text-sm text-gray-600"></i>
                                    </div>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $existing->name }}</p>
                                <code class="text-xs text-gray-500 bg-white px-1.5 py-0.5 rounded border border-gray-200 inline-block mt-1">{{ $existing->key }}</code>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Create Feature Form --}}
        <form action="{{ route('admin.subscription-features.store') }}" method="POST" id="featureForm">
            @csrf
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Feature Details</h2>
                    <p class="text-sm text-gray-600 mt-1">Fill in the information below or select from available features above</p>
                </div>
                
                <div class="p-6 space-y-6">
                    {{-- Feature Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Feature Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
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
                        <input type="text" 
                               name="key" 
                               value="{{ old('key') }}" 
                               required
                               pattern="[a-z0-9_]+"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors font-mono text-sm"
                               placeholder="e.g., ai_writing_evaluation">
                        <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Use lowercase letters, numbers, and underscores only (e.g., feature_name_here)
                        </p>
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
                                  placeholder="Brief description of what this feature provides...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Icon Class --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Icon Class (Optional)
                        </label>
                        <input type="text" 
                               name="icon" 
                               value="{{ old('icon') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors font-mono text-sm"
                               placeholder="e.g., fas fa-robot">
                        <p class="mt-1.5 text-xs text-gray-500">Font Awesome icon class (e.g., fas fa-star, fas fa-check)</p>
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
                                            onclick="document.querySelector('input[name=icon]').value = '{{ $iconClass }}'"
                                            class="flex items-center justify-center p-2 bg-white border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors group"
                                            title="{{ $iconClass }}">
                                        <i class="{{ $iconClass }} text-gray-600 group-hover:text-indigo-600"></i>
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
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Feature
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    @push('scripts')
    <script>
        function selectFeature(key, name, description, icon) {
            // Fill form fields
            document.querySelector('input[name="key"]').value = key;
            document.querySelector('input[name="name"]').value = name;
            document.querySelector('textarea[name="description"]').value = description;
            document.querySelector('input[name="icon"]').value = icon;
            
            // Smooth scroll to form
            document.getElementById('featureForm').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
            
            // Visual feedback on form
            const formElement = document.getElementById('featureForm');
            formElement.classList.add('ring-4', 'ring-indigo-500', 'ring-opacity-50');
            setTimeout(() => {
                formElement.classList.remove('ring-4', 'ring-indigo-500', 'ring-opacity-50');
            }, 2000);
            
            // Show success notification
            showNotification('Feature selected: ' + name, 'success');
        }
        
        function showNotification(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2 animate-slide-in`;
            notification.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('animate-fade-out');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
    
    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fade-out {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
        
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
        
        .animate-fade-out {
            animation: fade-out 0.3s ease-out;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @endpush
</x-admin-layout>
