<x-layout>
    <x-slot:title>Edit Test Set - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Test Set') }}
            </h2>
            <a href="{{ route('admin.test-sets.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                Back to Test Sets
            </a>
        </div>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.test-sets.update', $testSet) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Test Set Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $testSet->title) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="section_id" class="block mb-2 text-sm font-medium text-gray-900">Section</label>
                            <select id="section_id" name="section_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select a section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $testSet->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ ucfirst($section->name) }} ({{ $section->time_limit }} minutes)
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $testSet->active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="active" class="ml-2 text-sm font-medium text-gray-900">Make this test set active and available to students</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                            Update Test Set
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>