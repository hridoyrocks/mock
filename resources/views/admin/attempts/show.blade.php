<x-layout>
    <x-slot:title>View Attempt - Admin</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Attempt Details') }}
            </h2>
            <a href="{{ route('admin.attempts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                Back to Attempts
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
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Student and Test Info -->
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Test Information</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Test Set</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $attempt->testSet->title }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Section</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($attempt->testSet->section->name) }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm">
                                        @if ($attempt->status === 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif ($attempt->status === 'in_progress')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                In Progress
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Abandoned
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Band Score</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($attempt->band_score)
                                            <span class="font-medium">{{ $attempt->band_score }}</span>
                                        @else
                                            <span class="text-gray-500">Pending</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Started At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $attempt->start_time->format('M d, Y, g:i a') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Completed At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $attempt->end_time ? $attempt->end_time->format('M d, Y, g:i a') : 'Not completed' }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Time Spent</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @php
                                            $startTime = $attempt->start_time;
                                            $endTime = $attempt->end_time ?? $attempt->updated_at;
                                            $timeSpent = $startTime->diffInMinutes($endTime);
                                        @endphp
                                        {{ $timeSpent }} minutes
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Student Information</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $attempt->user->name }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $attempt->user->email }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Registered Since</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $attempt->user->created_at->format('M d, Y') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Total Attempts</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $attempt->user->attempts->count() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Feedback Section -->
                    @if($attempt->status === 'completed' && in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Evaluation & Feedback</h3>
                                
                                @if(!$attempt->band_score)
                                    <a href="{{ route('admin.attempts.evaluate-form', $attempt) }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                                        Evaluate Now
                                    </a>
                                @endif
                            </div>
                            
                            @if($attempt->band_score)
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <div class="flex flex-wrap gap-4 mb-4">
                                        <div>
                                            <span class="text-sm text-gray-500">Band Score:</span>
                                            <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-800 text-lg font-semibold rounded">{{ $attempt->band_score }}</span>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Feedback:</h4>
                                        <div class="prose max-w-none">
                                            {!! nl2br(e($attempt->feedback)) !!}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <p class="text-yellow-700">This attempt has not been evaluated yet. Click the "Evaluate Now" button to provide feedback and assign a band score.</p>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Student Answers Section -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Student Answers</h3>
                        
                        @if($attempt->testSet->section->name === 'writing')
                            <!-- Writing Answers -->
                            @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                <div class="mb-6 pb-6 border-b border-gray-200 last:border-b-0">
                                    <h4 class="font-medium mb-2">Task {{ $answer->question->order_number }}</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="prose max-w-none">
                                            <p class="text-gray-700 mb-2 italic">{{ $answer->question->content }}</p>
                                            <div class="border-t border-gray-200 pt-4 mt-4">
                                                {!! nl2br(e($answer->answer)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif($attempt->testSet->section->name === 'speaking')
                            <!-- Speaking Answers -->
                            @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                <div class="mb-6 pb-6 border-b border-gray-200 last:border-b-0">
                                    <h4 class="font-medium mb-2">Question {{ $answer->question->order_number }}</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-gray-700 mb-4 italic">{{ $answer->question->content }}</p>
                                        
                                        @if($answer->speakingRecording)
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-900 mb-2">Student's Recording:</h5>
                                                <audio controls class="w-full">
                                                    <source src="{{ asset('storage/' . $answer->speakingRecording->file_path) }}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        @else
                                            <p class="text-gray-500">No recording available for this question.</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Multiple Choice Answers -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student's Answer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correct Answer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                            <tr>
                                                <td class="px-6 py-4 text-sm">
                                                    <span class="font-medium">{{ $answer->question->order_number }}.</span> 
                                                    {{ Str::limit(strip_tags($answer->question->content), 60) }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if($answer->selectedOption)
                                                        {{ $answer->selectedOption->content }}
                                                    @elseif($answer->answer)
                                                        {{ $answer->answer }}
                                                    @else
                                                        <span class="text-gray-400">No answer</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if($answer->question->correctOption())
                                                        {{ $answer->question->correctOption()->content }}
                                                    @else
                                                        <span class="text-gray-400">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm">
                                                    @if($answer->selectedOption && $answer->selectedOption->is_correct)
                                                        <span class="text-green-500">Correct</span>
                                                    @elseif($answer->selectedOption || $answer->answer)
                                                        <span class="text-red-500">Incorrect</span>
                                                    @else
                                                        <span class="text-gray-400">Not answered</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @php
                                $correctAnswers = $attempt->answers->filter(function($answer) {
                                    return $answer->selectedOption && $answer->selectedOption->is_correct;
                                })->count();
                                
                                $totalQuestions = $attempt->answers->count();
                                $accuracy = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
                            @endphp
                            
                            <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Correct Answers</p>
                                        <p class="font-medium">{{ $correctAnswers }} / {{ $totalQuestions }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Accuracy</p>
                                        <p class="font-medium">{{ number_format($accuracy, 1) }}%</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500">Estimated Band Score</p>
                                        @php
                                            if ($attempt->testSet->section->name === 'listening') {
                                                $estimatedScore = App\Helpers\ScoreCalculator::calculateListeningBandScore($correctAnswers, $totalQuestions);
                                            } else {
                                                $estimatedScore = App\Helpers\ScoreCalculator::calculateReadingBandScore($correctAnswers, $totalQuestions);
                                            }
                                        @endphp
                                        <div class="bg-blue-100 text-blue-800 text-lg font-semibold inline-block px-3 py-1 rounded">
                                            {{ $estimatedScore }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex space-x-4 mt-6">
                        @if($attempt->status === 'completed' && in_array($attempt->testSet->section->name, ['writing', 'speaking']) && !$attempt->band_score)
                            <a href="{{ route('admin.attempts.evaluate-form', $attempt) }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                                Evaluate Attempt
                            </a>
                        @endif
                        
                        <form action="{{ route('admin.attempts.destroy', $attempt) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this attempt?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                                Delete Attempt
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>