<x-admin-layout>
    <x-slot:title>Create Feature</x-slot>
    
    <x-slot:header>
        <div class="flex items-center">
            <a href="{{ route('admin.subscription-features.index') }}" class="text-indigo-600 hover:text-indigo-800 mr-4 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Create New Feature
            </h1>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('admin.subscription-features.store') }}" method="POST">
                @csrf
                
                {{-- Main Card --}}
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    {{-- Card Header --}}
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-16 -mr-16 bg-white/10 rounded-full p-32 transform rotate-45"></div>
                        <div class="absolute bottom-0 left-0 -mb-16 -ml-16 bg-white/10 rounded-full p-32 transform -rotate-12"></div>
                        <div class="relative z-10">
                            <h2 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                Feature Details
                            </h2>
                            <p class="text-indigo-100 mt-2">Define a new feature for your subscription plans</p>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-8 space-y-6">
                        {{-- Feature Name --}}
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                Feature Name
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all duration-200"
                                       placeholder="e.g., AI Writing Evaluation">
                            </div>
                            @error('name')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        {{-- Feature Key --}}
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                Feature Key
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <input type="text" name="key" value="{{ old('key') }}" required
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all duration-200 font-mono"
                                       placeholder="e.g., ai_writing_evaluation"
                                       pattern="[a-z0-9_]+">
                            </div>
                            <p class="text-sm text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Use lowercase letters, numbers, and underscores only
                            </p>
                            @error('key')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Description --}}
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                Description
                            </label>
                            <div class="relative">
                                <textarea name="description" rows="4"
                                          class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all duration-200"
                                          placeholder="Brief description of what this feature provides...">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Icon Selection --}}
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                Icon Class
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="icon" value="{{ old('icon') }}"
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all duration-200"
                                       placeholder="e.g., fas fa-robot">
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Font Awesome icon class (optional)</p>
                            
                            {{-- Icon Preview --}}
                            <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                                <p class="text-sm font-medium text-gray-700 mb-3">Common Icons:</p>
                                <div class="grid grid-cols-4 md:grid-cols-6 gap-3">
                                    @php
                                        $commonIcons = [
                                            'fas fa-robot' => 'AI Features',
                                            'fas fa-chart-line' => 'Analytics',
                                            'fas fa-headset' => 'Support',
                                            'fas fa-download' => 'Downloads',
                                            'fas fa-certificate' => 'Certificate',
                                            'fas fa-infinity' => 'Unlimited',
                                            'fas fa-check' => 'Check',
                                            'fas fa-star' => 'Premium',
                                            'fas fa-bolt' => 'Fast',
                                            'fas fa-shield-alt' => 'Security',
                                            'fas fa-users' => 'Team',
                                            'fas fa-book' => 'Resources'
                                        ];
                                    @endphp
                                    @foreach($commonIcons as $iconClass => $label)
                                        <button type="button" 
                                                onclick="document.querySelector('input[name=icon]').value = '{{ $iconClass }}'"
                                                class="p-3 bg-white border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all group">
                                            <i class="{{ $iconClass }} text-lg text-gray-600 group-hover:text-indigo-600"></i>
                                            <p class="text-xs text-gray-500 mt-1">{{ $label }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('icon')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.subscription-features.index') }}" 
                       class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-bold flex items-center transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Feature
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>