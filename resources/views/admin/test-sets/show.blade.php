<x-layout>
    <x-slot:title>{{ $testSet->title }} - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $testSet->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($testSet->section->name) }}
                    </span>
                    <span class="ml-2 text-gray-500">{{ $testSet->section->time_limit }} minutes</span>
                    @if($testSet->active)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                    + Add Question
                </a>
                <a href="{{ route('admin.test-sets.edit', $testSet) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                    Edit Test Set
                </a>
                <a href="{{ route('admin.test-sets.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                    Back to Test Sets
                </a>
            </div>
        </div>
    </x-slot:header>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Questions</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $testSet->questions->count() }}</p>
                        </div>
                    </div>
                </div>
                
                @php
                    $partCounts = $testSet->questions->groupBy('part_number')->map->count();
                    $maxParts = match($testSet->section->name) {
                        'listening' => 4,
                        'reading' => 3,
                        'speaking' => 3,
                        'writing' => 2,
                        default => 1
                    };
                @endphp
                
                @for($i = 1; $i <= min(3, $maxParts); $i++)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <span class="text-lg font-bold text-purple-600">{{ $i }}</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                @if($testSet->section->name === 'writing')
                                    Task {{ $i }}
                                @else
                                    Part {{ $i }}
                                @endif
                            </p>
                            <p class="text-lg font-semibold text-gray-900">{{ $partCounts[$i] ?? 0 }} questions</p>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            
            <!-- Questions by Part -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Questions</h3>
                </div>
                
                @if($testSet->questions->count() > 0)
                    @php
                        $groupedQuestions = $testSet->questions->sortBy('order_number')->groupBy('part_number');
                    @endphp
                    
                    @foreach($groupedQuestions as $partNumber => $questions)
                        <div class="border-b border-gray-200 last:border-b-0">
                            <div class="bg-gray-50 px-6 py-3">
                                <h4 class="text-sm font-medium text-gray-900">
                                    @if($testSet->section->name === 'writing')
                                        Task {{ $partNumber ?? 'Unassigned' }}
                                    @else
                                        Part {{ $partNumber ?? 'Unassigned' }}
                                    @endif
                                    <span class="ml-2 text-gray-500">({{ $questions->count() }} questions)</span>
                                </h4>
                            </div>
                            
                            <div class="divide-y divide-gray-200">
                                @foreach($questions as $question)
                                    <div class="px-6 py-4 hover:bg-gray-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                                        {{ $question->order_number }}
                                                    </span>
                                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @switch($question->question_type)
                                                            @case('passage') bg-purple-100 text-purple-800 @break
                                                            @case('multiple_choice') bg-blue-100 text-blue-800 @break
                                                            @case('true_false') bg-green-100 text-green-800 @break
                                                            @case('yes_no') bg-teal-100 text-teal-800 @break
                                                            @case('matching_headings') bg-yellow-100 text-yellow-800 @break
                                                            @case('fill_blanks') bg-indigo-100 text-indigo-800 @break
                                                            @case('short_answer') bg-pink-100 text-pink-800 @break
                                                            @default bg-gray-100 text-gray-800
                                                        @endswitch
                                                    ">
                                                        {{ ucwords(str_replace('_', ' ', $question->question_type)) }}
                                                    </span>
                                                    @if($question->media_path)
                                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Media
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-900">
                                                    {{ Str::limit(strip_tags($question->content), 100) }}
                                                </p>
                                                @if($question->instructions)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $question->instructions }}</p>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex items-center space-x-2">
                                                <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No questions yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding questions to this test set.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.questions.create', ['test_set' => $testSet->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add First Question
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Recent Attempts -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Student Attempts</h3>
                
                @php
                    $recentAttempts = $testSet->attempts()->with('user')->latest()->take(5)->get();
                @endphp
                
                @if($recentAttempts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentAttempts as $attempt)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $attempt->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attempt->created_at->format('M d, Y g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($attempt->status === 'completed') bg-green-100 text-green-800
                                                @elseif($attempt->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($attempt->band_score)
                                                {{ $attempt->band_score }}
                                            @else
                                                <span class="text-gray-500">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.attempts.show', $attempt) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No attempts yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-layout>