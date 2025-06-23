@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">AI Speaking Evaluation</h1>
                    <p class="text-gray-600 mt-1">{{ $attempt->testSet->title }} - {{ $attempt->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Overall Band Score</div>
                    <div class="text-4xl font-bold text-purple-600">{{ number_format($evaluation['overall_band'], 1) }}</div>
                </div>
            </div>
        </div>

        {{-- Overall Criteria Scores --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Overall Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($evaluation['overall_scores'] as $criterion => $score)
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">{{ $criterion }}</div>
                    <div class="text-3xl font-bold {{ $score >= 7 ? 'text-green-600' : ($score >= 5 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($score, 1) }}
                    </div>
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($score/9)*100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Part-wise Results --}}
        @foreach($evaluation['parts'] as $index => $part)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6">
                {{-- Part Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Part {{ $part['part_number'] }}: {{ $part['part_type'] }}
                    </h2>
                    <span class="text-2xl font-bold text-purple-600">{{ number_format($part['band_score'], 1) }}</span>
                </div>

                {{-- Question & Duration --}}
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Question:</p>
                    <p class="font-medium text-gray-900">{{ $part['question'] }}</p>
                    <div class="mt-2 flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        Response Duration: {{ $part['duration'] }}
                    </div>
                </div>

                {{-- Transcription --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">Your Response (Transcription)</h3>
                        <button onclick="toggleTranscription({{ $index }})" class="text-blue-600 hover:text-blue-700 text-sm">
                            <i class="fas fa-chevron-down mr-1"></i> Show/Hide
                        </button>
                    </div>
                    <div id="transcription-{{ $index }}" class="hidden p-4 bg-blue-50 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $part['transcription'] }}</p>
                    </div>
                </div>

                {{-- Detailed Feedback --}}
                <div class="space-y-4">
                    {{-- Fluency and Coherence --}}
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Fluency and Coherence</h3>
                        <p class="text-gray-700">{{ $part['feedback']['fluency_coherence'] }}</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @if($part['metrics']['speech_rate'])
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                Speech Rate: {{ $part['metrics']['speech_rate'] }} wpm
                            </span>
                            @endif
                            @if($part['metrics']['pause_frequency'])
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                Pause Frequency: {{ $part['metrics']['pause_frequency'] }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Lexical Resource --}}
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Lexical Resource</h3>
                        <p class="text-gray-700">{{ $part['feedback']['lexical_resource'] }}</p>
                        @if(!empty($part['vocabulary_range']))
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-1">Vocabulary highlights:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($part['vocabulary_range'] as $word)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ $word }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Grammar --}}
                    <div class="border-l-4 border-yellow-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Grammatical Range and Accuracy</h3>
                        <p class="text-gray-700">{{ $part['feedback']['grammar'] }}</p>
                    </div>

                    {{-- Pronunciation --}}
                    <div class="border-l-4 border-purple-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Pronunciation</h3>
                        <p class="text-gray-700">{{ $part['feedback']['pronunciation'] }}</p>
                        @if(!empty($part['pronunciation_issues']))
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-1">Areas to work on:</p>
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                @foreach($part['pronunciation_issues'] as $issue)
                                <li>{{ $issue }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Part-specific Tips --}}
                @if(!empty($part['tips']))
                <div class="mt-6 bg-purple-50 rounded-lg p-4">
                    <h3 class="font-semibold text-purple-900 mb-2">💡 Tips for This Part</h3>
                    <ul class="space-y-1">
                        @foreach($part['tips'] as $tip)
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span class="text-purple-800">{{ $tip }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Audio Player --}}
                @if($part['audio_url'])
                <div class="mt-4">
                    <label class="text-sm text-gray-600">Listen to your response:</label>
                    <audio controls class="w-full mt-2">
                        <source src="{{ $part['audio_url'] }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        {{-- Overall Summary & Recommendations --}}
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Overall Summary & Recommendations</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold mb-2">Your Strengths:</h3>
                    <ul class="space-y-1">
                        @foreach($evaluation['strengths'] as $strength)
                        <li class="flex items-start">
                            <i class="fas fa-star mt-1 mr-2"></i>
                            <span>{{ $strength }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Priority Areas for Improvement:</h3>
                    <ul class="space-y-1">
                        @foreach($evaluation['improvements'] as $improvement)
                        <li class="flex items-start">
                            <i class="fas fa-chart-line mt-1 mr-2"></i>
                            <span>{{ $improvement }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="border-t border-purple-400 pt-4">
                <h3 class="font-semibold mb-2">Personalized Study Plan:</h3>
                <div class="bg-white/10 rounded-lg p-4">
                    @foreach($evaluation['study_plan'] as $step => $action)
                    <div class="flex items-start mb-2">
                        <span class="bg-white/20 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">{{ $step + 1 }}</span>
                        <span>{{ $action }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Progress Tracking --}}
        @if(isset($previousScores))
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Progress</h2>
            <canvas id="progressChart" width="100%" height="200"></canvas>
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('student.results.show', $attempt) }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Results
            </a>
            <div class="space-x-4">
                <button onclick="window.print()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-print mr-2"></i> Print Report
                </button>
                <a href="{{ route('student.speaking.index') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition inline-block">
                    <i class="fas fa-redo mr-2"></i> Practice Again
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleTranscription(index) {
    const transcription = document.getElementById(`transcription-${index}`);
    transcription.classList.toggle('hidden');
}

@if(isset($previousScores))
// Progress Chart
const ctx = document.getElementById('progressChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($previousScores->pluck('date')) !!},
        datasets: [
            {
                label: 'Overall Score',
                data: {!! json_encode($previousScores->pluck('overall')) !!},
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.3
            },
            {
                label: 'Fluency',
                data: {!! json_encode($previousScores->pluck('fluency')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3
            },
            {
                label: 'Pronunciation',
                data: {!! json_encode($previousScores->pluck('pronunciation')) !!},
                borderColor: 'rgb(236, 72, 153)',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 9
            }
        }
    }
});
@endif
</script>
@endpush
@endsection