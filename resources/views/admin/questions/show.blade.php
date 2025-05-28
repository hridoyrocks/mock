<x-layout>
    <x-slot:title>View Question - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Question Details') }}
            </h2>
            <a href="{{ route('admin.test-sets.show', $question->testSet) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                Back to Test Set
            </a>
        </div>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Question #{{ $question->order_number }}</h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-md">
                                {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                            </span>
                        </div>
                        
                        <div class="prose max-w-none mb-4">
                            {!! $question->content !!}
                        </div>
                        
                        @if($question->media_path)
                            <div class="mt-4 mb-4">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Media:</h4>
                                @if(Str::endsWith($question->media_path, ['.mp3', '.wav', '.ogg']))
                                    <audio controls class="w-full">
                                        <source src="{{ asset('storage/' . $question->media_path) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @else
                                    <img src="{{ asset('storage/' . $question->media_path) }}" alt="Question media" class="max-h-64">
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    @if($question->options->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Answer Options:</h4>
                            
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                    <div class="flex items-start p-3 {{ $option->is_correct ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }} rounded-lg">
                                        <div class="flex-1">
                                            <p class="text-gray-900">{{ $option->content }}</p>
                                        </div>
                                        @if($option->is_correct)
                                            <span class="inline-flex items-center text-green-700 text-sm font-medium">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Correct Answer
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex space-x-4 mt-6">
                        <a href="{{ route('admin.questions.edit', $question) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                            Edit Question
                        </a>
                        
                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                                Delete Question
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>