<x-admin-layout>
    <x-slot:title>Create Announcement</x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Create Announcement</h1>
                <a href="{{ route('admin.announcements.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to Announcements
                </a>
            </div>

            <div class="bg-white shadow-xl rounded-lg p-6">
                <form action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-300 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <textarea name="content" id="content" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type and Target Audience -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Information</option>
                                    <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>Success</option>
                                    <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Promotion</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="target_audience" class="block text-sm font-medium text-gray-700">Target Audience</label>
                                <select name="target_audience" id="target_audience" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Students Only</option>
                                    <option value="admin" {{ old('target_audience') == 'admin' ? 'selected' : '' }}>Admins Only</option>
                                </select>
                                @error('target_audience')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="space-y-4 border-t pt-4">
                            <h3 class="text-sm font-medium text-gray-700">Action Button (Optional)</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="action_button_text" class="block text-sm font-medium text-gray-700">Button Text</label>
                                    <input type="text" name="action_button_text" id="action_button_text" value="{{ old('action_button_text') }}"
                                           placeholder="e.g., Learn More"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('action_button_text')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="action_button_url" class="block text-sm font-medium text-gray-700">Button URL</label>
                                    <input type="url" name="action_button_url" id="action_button_url" value="{{ old('action_button_url') }}"
                                           placeholder="https://example.com"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('action_button_url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="space-y-4 border-t pt-4">
                            <h3 class="text-sm font-medium text-gray-700">Schedule (Optional)</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="space-y-4 border-t pt-4">
                            <h3 class="text-sm font-medium text-gray-700">Additional Settings</h3>
                            
                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority (0-100)</label>
                                    <input type="number" name="priority" id="priority" value="{{ old('priority', 0) }}"
                                           min="0" max="100"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="mt-1 text-xs text-gray-500">Higher number = higher priority</p>
                                    @error('priority')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL (Optional)</label>
                                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}"
                                           placeholder="https://example.com/image.jpg"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('image_url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="hidden" name="is_dismissible" value="0">
                                    <input type="checkbox" name="is_dismissible" id="is_dismissible" value="1"
                                           {{ old('is_dismissible', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_dismissible" class="ml-2 block text-sm text-gray-900">
                                        Allow users to dismiss
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('admin.announcements.index') }}"
                               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Announcement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
