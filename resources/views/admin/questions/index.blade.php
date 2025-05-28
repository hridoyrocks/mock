<x-layout>
    <x-slot:title>Questions - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Questions') }}
            </h2>
            <a href="{{ route('admin.questions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                Add New Question
            </a>
        </div>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <div class="mb-6">
                <form action="{{ route('admin.questions.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Filter by Section</label>
                        <select id="section" name="section" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">All Sections</option>
                            <option value="listening" {{ request('section') == 'listening' ? 'selected' : '' }}>Listening</option>
                            <option value="reading" {{ request('section') == 'reading' ? 'selected' : '' }}>Reading</option>
                            <option value="writing" {{ request('section') == 'writing' ? 'selected' : '' }}>Writing</option>
                            <option value="speaking" {{ request('section') == 'speaking' ? 'selected' : '' }}>Speaking</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="test_set" class="block text-sm font-medium text-gray-700 mb-1">Filter by Test Set</label>
                        <select id="test_set" name="test_set" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">All Test Sets</option>
                            @foreach($testSets as $testSet)
                                <option value="{{ $testSet->id }}" {{ request('test_set') == $testSet->id ? 'selected' : '' }}>
                                    {{ $testSet->title }} ({{ ucfirst($testSet->section->name) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Filter
                        </button>
                        
                        @if(request()->has('section') || request()->has('test_set'))
                            <a href="{{ route('admin.questions.index') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 ml-2">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Set</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($questions as $question)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $question->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $question->order_number }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $question->testSet->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($question->testSet->section->name) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ Str::limit(strip_tags($question->content), 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($question->media_path)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Has Media
                                                </span>
                                            @else
                                                <span class="text-gray-500">None</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if ($questions->isEmpty())
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No questions found.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>