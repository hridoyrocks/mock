<x-layout>
    <x-slot:title>Test Result - IELTS Mock Test</x-slot>
    
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Result') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-1">{{ $attempt->testSet->title }}</h2>
                        <p class="text-gray-500">{{ ucfirst($attempt->testSet->section->name) }} Section</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="grid md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Date Taken</p>
                                <p class="font-medium">{{ $attempt->created_at->format('F j, Y, g:i a') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Time Spent</p>
                                @php
                                    $startTime = $attempt->start_time;
                                    $endTime = $attempt->end_time ?? $attempt->updated_at;
                                    $timeSpent = $startTime->diffInMinutes($endTime);
                                @endphp
                                <p class="font-medium">{{ $timeSpent }} minutes</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="font-medium capitalize">{{ $attempt->status }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Band Score</p>
                                @if($attempt->band_score)
                                    <div class="bg-blue-100 text-blue-800 text-lg font-semibold inline-block px-3 py-1 rounded">
                                        {{ $attempt->band_score }}
                                    </div>
                                @else
                                    <p class="text-yellow-600">Pending evaluation</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if(in_array($attempt->testSet->section->name, ['listening', 'reading']) && isset($correctAnswers))
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Score Breakdown</h3>
                            
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="grid md:grid-cols-3 gap-4">
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
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Question Analysis</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Question</th>
                                            <th scope="col" class="px-6 py-3">Your Answer</th>
                                            <th scope="col" class="px-6 py-3">Correct Answer</th>
                                            <th scope="col" class="px-6 py-3">Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                            <tr class="bg-white border-b">
                                                <td class="px-6 py-4">
                                                    <span class="font-medium">{{ $answer->question->order_number }}.</span> 
                                                    {!! Str::limit(strip_tags($answer->question->content), 80) !!}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($answer->selectedOption)
                                                        {{ $answer->selectedOption->content }}
                                                    @elseif($answer->answer)
                                                        {{ $answer->answer }}
                                                    @else
                                                        <span class="text-gray-400">No answer</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{ $answer->question->correctOption()->content ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($answer->selectedOption && $answer->selectedOption->is_correct)
                                                        <span class="text-green-500">Correct</span>
                                                    @else
                                                        <span class="text-red-500">Incorrect</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif(in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Feedback</h3>
                            
                            @if($attempt->feedback)
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <div class="prose max-w-none">
                                        {!! nl2br(e($attempt->feedback)) !!}
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                    <p class="text-yellow-700">Your test is currently being evaluated. Please check back later for feedback and band score.</p>
                                </div>
                            @endif
                        </div>
                        
                        @if($attempt->testSet->section->name === 'writing')
                            <div class="mb-6">
                                <h3 class="text-lg font-medium mb-3">Your Responses</h3>
                                
                                @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                    <div class="mb-6">
                                        <h4 class="font-medium mb-2">Task {{ $answer->question->order_number }}</h4>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="prose max-w-none">
                                                {!! nl2br(e($answer->answer)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($attempt->testSet->section->name === 'speaking')
                            <div class="mb-6">
                                <h3 class="text-lg font-medium mb-3">Your Recordings</h3>
                                
                                @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                    <div class="mb-4">
                                        <h4 class="font-medium mb-2">Question {{ $answer->question->order_number }}</h4>
                                        
                                        @if($answer->speakingRecording)
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <audio controls class="w-full">
                                                    <source src="{{ asset('storage/' . $answer->speakingRecording->file_path) }}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        @else
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <p class="text-gray-500">No recording available.</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    
                    <div class="mt-8">
                        <a href="{{ route('student.results') }}" class="text-sm text-blue-600 hover:underline">
                            &larr; Back to all results
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>