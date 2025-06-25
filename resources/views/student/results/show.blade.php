<x-student-layout>
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
                                    $totalSeconds = $startTime->diffInSeconds($endTime);
                                    $minutes = floor($totalSeconds / 60);
                                    $seconds = $totalSeconds % 60;
                                @endphp
                                <p class="font-medium">{{ $minutes }} Min {{ $seconds }} Seconds</p>
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

                    {{-- AI Evaluation Button for Writing/Speaking --}}
                    @if(in_array($attempt->testSet->section->name, ['writing', 'speaking']))
                        <div class="mb-6 bg-gradient-to-r from-purple-50 to-blue-50 p-6 rounded-lg border border-purple-200">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">AI Evaluation</h3>
                            
                            @if(auth()->user()->hasFeature('ai_' . $attempt->testSet->section->name . '_evaluation'))
                                @if(!$attempt->ai_evaluated_at)
                                    <button onclick="startAIEvaluation({{ $attempt->id }}, '{{ $attempt->testSet->section->name }}')" 
                                            id="ai-eval-btn"
                                            class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-robot mr-2"></i>
                                        Get AI Evaluation
                                    </button>
                                @else
                                    <div class="space-y-4">
                                        <div class="bg-white p-4 rounded-lg">
                                            <p class="text-sm text-gray-600 mb-2">AI Evaluation Completed</p>
                                            <p class="text-2xl font-bold text-purple-600">Band Score: {{ $attempt->ai_band_score ?? 'N/A' }}</p>
                                        </div>
                                        <a href="{{ route('ai.evaluation.get', $attempt->id) }}" 
                                           class="inline-block bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-teal-700 transition-all">
                                            <i class="fas fa-chart-line mr-2"></i>
                                            View Detailed AI Evaluation
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="bg-white/70 p-4 rounded-lg">
                                    <p class="text-gray-700 mb-3">Upgrade to Premium to unlock AI evaluation for instant feedback and band score prediction.</p>
                                    <a href="{{ route('subscription.plans') }}" 
                                       class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all">
                                        <i class="fas fa-crown mr-2"></i>
                                        Upgrade to Premium
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                    
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
                            <h3 class="text-lg font-medium mb-3">Your Submission</h3>
                            
                            @if($attempt->testSet->section->name === 'writing')
                                @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                    <div class="mb-6 bg-gray-50 p-6 rounded-lg">
                                        <h4 class="font-semibold mb-3 text-gray-800">Task {{ $answer->question->order_number }}</h4>
                                        <div class="bg-white p-4 rounded border border-gray-200">
                                            <div class="prose max-w-none text-gray-700">
                                                {!! nl2br(e($answer->answer)) !!}
                                            </div>
                                            <div class="mt-3 text-sm text-gray-500">
                                                Word count: {{ str_word_count($answer->answer) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif($attempt->testSet->section->name === 'speaking')
                                @foreach($attempt->answers->sortBy('question.order_number') as $answer)
                                    <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                                        <h4 class="font-medium mb-2">Part {{ $answer->question->order_number }}</h4>
                                        
                                        @if($answer->speakingRecording)
                                            <div class="bg-white p-4 rounded border border-gray-200">
                                                <audio controls class="w-full">
                                                    <source src="{{ asset('storage/' . $answer->speakingRecording->file_path) }}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        @else
                                            <div class="bg-white p-4 rounded border border-gray-200">
                                                <p class="text-gray-500">No recording available.</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
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

    {{-- AI Evaluation Modal --}}
    <div id="aiEvalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-xl font-bold mb-4">Starting AI Evaluation...</h3>
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
            </div>
            <p class="text-center text-gray-600">Please wait while we analyze your response...</p>
        </div>
    </div>

    @push('scripts')
    <script>
    function startAIEvaluation(attemptId, type) {
    // Show loading modal
    document.getElementById('aiEvalModal').classList.remove('hidden');
    
    // Disable button
    const button = document.getElementById('ai-eval-btn');
    button.disabled = true;
    
    // Route ঠিক করুন - type অনুযায়ী সঠিক endpoint
    const endpoint = type === 'writing' ? '/ai/evaluate/writing' : '/ai/evaluate/speaking';
    
    // Make API call
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            attempt_id: attemptId
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Failed to start evaluation');
            document.getElementById('aiEvalModal').classList.add('hidden');
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.error || 'An error occurred. Please try again.');
        document.getElementById('aiEvalModal').classList.add('hidden');
        button.disabled = false;
    });
}
    </script>
    @endpush
</x-student-layout>