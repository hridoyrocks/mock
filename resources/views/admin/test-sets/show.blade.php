<x-layout>
    <x-slot:title>View Test Set - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Set Details') }}
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
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $testSet->title }}</h3>
                        <div class="mt-2 flex flex-wrap items-center gap-4">
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-md">
                                {{ ucfirst($testSet->section->name) }} Section
                            </span>
                            <span class="inline-flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $testSet->section->time_limit }} minutes
                            </span>
                            @if($testSet->active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">
                                Created: {{ $testSet->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Questions</h3>
                            <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                Add New Question
                            </a>
                        </div>
                        
                        @if($testSet->questions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($testSet->questions->sortBy('order_number') as $question)
                                            <tr>
                                                <td class="py-4 px-6 text-sm text-gray-900">{{ $question->order_number }}</td>
                                                <td class="py-4 px-6 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</td>
                                                <td class="py-4 px-6 text-sm text-gray-900">
                                                    {{ Str::limit(strip_tags($question->content), 60) }}
                                                </td>
                                                <td class="py-4 px-6 text-sm">
                                                    @if($question->media_path)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Has media
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">No media</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 text-sm">
                                                    <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-yellow-700">No questions found for this test set. Add questions using the button above.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Student Attempts</h3>
                        </div>
                        
                        @php
                            $attempts = $testSet->attempts()->with('user')->latest()->take(5)->get();
                        @endphp
                        
                        @if($attempts->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Band Score</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($attempts as $attempt)
                                            <tr>
                                                <td class="py-4 px-6 text-sm text-gray-900">{{ $attempt->user->name }}</td>
                                                <td class="py-4 px-6 text-sm text-gray-500">{{ $attempt->created_at->format('M d, Y, g:i a') }}</td>
                                                <td class="py-4 px-6 text-sm">
                                                    @if($attempt->status === 'completed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Completed
                                                        </span>
                                                    @elseif($attempt->status === 'in_progress')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            In Progress
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Abandoned
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-900">
                                                    @if($attempt->band_score)
                                                        <span class="font-medium">{{ $attempt->band_score }}</span>
                                                    @else
                                                        <span class="text-gray-500">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 text-sm">
                                                    <a href="{{ route('admin.attempts.show', $attempt) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                    @if($attempt->status === 'completed' && in_array($testSet->section->name, ['writing', 'speaking']) && !$attempt->band_score)
                                                        <a href="{{ route('admin.attempts.evaluate-form', $attempt) }}" class="text-green-600 hover:text-green-900">Evaluate</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.attempts.index', ['test_set' => $testSet->id]) }}" class="text-sm text-blue-600 hover:underline">
                                    View all attempts →
                                </a>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">No student attempts found for this test set yet.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex space-x-4 mt-6">
                        <a href="{{ route('admin.test-sets.edit', $testSet) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                            Edit Test Set
                        </a>
                        
                        <form action="{{ route('admin.test-sets.destroy', $testSet) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this test set? This will also delete all associated questions and student attempts.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                                Delete Test Set
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>