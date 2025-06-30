<x-layout>
    <x-slot:title>Reading Passages - {{ $testSet->title }}</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">Reading Passages</h1>
                        <p class="text-green-100 text-sm mt-1">{{ $testSet->title }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.passages.create', $testSet) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-green-700 font-medium rounded-md hover:bg-green-50 transition-all">
                            Add Passage
                        </a>
                        <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur border border-white/20 text-white text-sm font-medium rounded-md hover:bg-white/20 transition-all">
                            Back to Test Set
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="space-y-6">
            @forelse($passages as $passage)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Passage {{ $passage->part_number }}: {{ $passage->instructions }}
                                </h3>
                                <div class="mt-2 text-sm text-gray-600">
                                    {{ Str::limit(strip_tags($passage->content), 200) }}
                                </div>
                                <div class="mt-4 flex items-center space-x-6 text-sm">
                                    <span class="text-gray-500">
                                        Questions: <span class="font-medium text-gray-900">{{ $passage->questions_count }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('admin.passages.add-question', $passage) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                                    Add Question
                                </a>
                                <a href="{{ route('admin.questions.edit', $passage) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-gray-700 text-sm font-medium rounded hover:bg-gray-50">
                                    Edit Passage
                                </a>
                            </div>
                        </div>
                        
                        @if($passage->questions_count > 0)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Questions in this passage:</h4>
                                <div class="space-y-2">
                                    @foreach($testSet->questions()->where('passage_id', $passage->id)->orderBy('order_number')->get() as $question)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm font-medium text-gray-700">#{{ $question->order_number }}</span>
                                                <span class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                                <span class="text-sm text-gray-500">{{ Str::limit(strip_tags($question->content), 50) }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.questions.edit', $question) }}" class="text-sm text-blue-600 hover:text-blue-800">Edit</a>
                                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800" 
                                                            onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                    <div class="text-gray-500">
                        <p class="text-lg font-medium">No passages yet</p>
                        <p class="mt-2">Start by adding your first reading passage.</p>
                        <a href="{{ route('admin.passages.create', $testSet) }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700">
                            Add First Passage
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>