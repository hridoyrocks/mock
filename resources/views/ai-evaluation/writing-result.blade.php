@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">AI Writing Evaluation</h1>
                    <p class="text-gray-600 mt-1">{{ $attempt->testSet->title }} - {{ $attempt->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Overall Band Score</div>
                    <div class="text-4xl font-bold text-blue-600">{{ number_format($evaluation['overall_band'], 1) }}</div>
                </div>
            </div>
        </div>

        {{-- Task Results --}}
        @foreach($evaluation['tasks'] as $index => $task)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6">
                {{-- Task Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Task {{ $index + 1 }}: {{ $task['question_title'] }}
                    </h2>
                    <span class="text-2xl font-bold text-blue-600">{{ number_format($task['band_score'], 1) }}</span>
                </div>

                {{-- Word Count --}}
                <div class="mb-4">
                    <span class="text-sm text-gray-600">Word Count: </span>
                    <span class="font-medium {{ $task['word_count'] >= $task['required_words'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ $task['word_count'] }} / {{ $task['required_words'] }}
                    </span>
                </div>

                {{-- Criteria Scores --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @foreach($task['criteria'] as $criterion => $score)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">{{ $criterion }}</div>
                        <div class="text-2xl font-bold {{ $score >= 7 ? 'text-green-600' : ($score >= 5 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($score, 1) }}
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Detailed Feedback --}}
                <div class="space-y-4">
                    {{-- Task Achievement --}}
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Task Achievement</h3>
                        <p class="text-gray-700">{{ $task['feedback']['task_achievement'] }}</p>
                    </div>

                    {{-- Coherence and Cohesion --}}
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Coherence and Cohesion</h3>
                        <p class="text-gray-700">{{ $task['feedback']['coherence_cohesion'] }}</p>
                    </div>

                    {{-- Lexical Resource --}}
                    <div class="border-l-4 border-yellow-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Lexical Resource</h3>
                        <p class="text-gray-700">{{ $task['feedback']['lexical_resource'] }}</p>
                        @if(!empty($task['vocabulary_suggestions']))
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-1">Suggested vocabulary improvements:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($task['vocabulary_suggestions'] as $suggestion)
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">
                                    {{ $suggestion['original'] }} → {{ $suggestion['suggested'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Grammar --}}
                    <div class="border-l-4 border-red-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Grammatical Range and Accuracy</h3>
                        <p class="text-gray-700">{{ $task['feedback']['grammar'] }}</p>
                        @if(!empty($task['grammar_errors']))
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-1">Grammar issues found:</p>
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                @foreach($task['grammar_errors'] as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Improvement Tips --}}
                @if(!empty($task['improvement_tips']))
                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">💡 Tips for Improvement</h3>
                    <ul class="space-y-1">
                        @foreach($task['improvement_tips'] as $tip)
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span class="text-blue-800">{{ $tip }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Show/Hide Essay --}}
                <div class="mt-6">
                    <button onclick="toggleEssay({{ $index }})" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        <i class="fas fa-eye mr-1"></i> View Your Essay
                    </button>
                    <div id="essay-{{ $index }}" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">Your Response:</h4>
                        <div class="whitespace-pre-wrap text-gray-700">{{ $task['essay_text'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Overall Summary --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Overall Performance Summary</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">Strengths:</h3>
                    <ul class="space-y-1">
                        @foreach($evaluation['overall_strengths'] as $strength)
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-2"></i>
                            <span>{{ $strength }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Areas for Improvement:</h3>
                    <ul class="space-y-1">
                        @foreach($evaluation['overall_improvements'] as $improvement)
                        <li class="flex items-start">
                            <i class="fas fa-arrow-up mt-1 mr-2"></i>
                            <span>{{ $improvement }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('student.results.show', $attempt) }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Results
            </a>
            <div class="space-x-4">
                <button onclick="window.print()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-print mr-2"></i> Print Report
                </button>
                <button onclick="downloadPDF()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleEssay(index) {
    const essay = document.getElementById(`essay-${index}`);
    essay.classList.toggle('hidden');
}

function downloadPDF() {
    // Implement PDF download functionality
    alert('PDF download will be implemented');
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>
@endpush
@endsection