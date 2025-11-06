<x-admin-layout>
    <x-slot:title>Edit Announcement</x-slot>

    <x-slot:header>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.announcements.index') }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Announcement</h1>
                    <p class="text-sm text-gray-600 mt-1">Update announcement details</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 px-4 py-2 bg-{{ $announcement->type_badge_color }}-50 border border-{{ $announcement->type_badge_color }}-200 rounded-lg">
                <span class="text-sm font-medium text-{{ $announcement->type_badge_color }}-800">{{ ucfirst($announcement->type) }}</span>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror">
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="4" required
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 resize-none @error('content') border-red-300 @enderror">{{ old('content', $announcement->content) }}</textarea>
                        @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="info" {{ old('type', $announcement->type) == 'info' ? 'selected' : '' }}>📘 Information</option>
                                <option value="warning" {{ old('type', $announcement->type) == 'warning' ? 'selected' : '' }}>⚠️ Warning</option>
                                <option value="success" {{ old('type', $announcement->type) == 'success' ? 'selected' : '' }}>✅ Success</option>
                                <option value="promotion" {{ old('type', $announcement->type) == 'promotion' ? 'selected' : '' }}>🎁 Promotion</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience <span class="text-red-500">*</span></label>
                            <select name="target_audience" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="all" {{ old('target_audience', $announcement->target_audience) == 'all' ? 'selected' : '' }}>👥 All Users</option>
                                <option value="students" {{ old('target_audience', $announcement->target_audience) == 'students' ? 'selected' : '' }}>🎓 Students</option>
                                <option value="admin" {{ old('target_audience', $announcement->target_audience) == 'admin' ? 'selected' : '' }}>👔 Admins</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Action Button</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                            <input type="text" name="action_button_text" value="{{ old('action_button_text', $announcement->action_button_text) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Button URL</label>
                            <input type="url" name="action_button_url" value="{{ old('action_button_url', $announcement->action_button_url) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Schedule --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Schedule</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time</label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date & Time</label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Settings --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Additional Settings</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority (0-100)</label>
                            <input type="number" name="priority" value="{{ old('priority', $announcement->priority) }}" min="0" max="100"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                            <input type="url" name="image_url" value="{{ old('image_url', $announcement->image_url) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <input type="hidden" name="is_dismissible" value="0">
                        <input type="checkbox" name="is_dismissible" id="is_dismissible" value="1" {{ old('is_dismissible', $announcement->is_dismissible) ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_dismissible" class="ml-3">
                            <span class="text-sm font-medium text-gray-900">Allow users to dismiss</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <a href="{{ route('admin.announcements.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Announcement
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
