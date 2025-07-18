{{-- resources/views/student/full-test/results.blade.php --}}
<x-student-layout>
    <x-slot:title>Full Test Results - {{ $fullTestAttempt->fullTest->title }}</x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Test Completed!</h1>
                <p class="text-gray-400">{{ $fullTestAttempt->fullTest->title }} - Completed on {{ $fullTestAttempt->end_time->format('M d, Y') }}</p>
            </div>

            <!-- Overall Score Card -->
            <div class="glass rounded-2xl p-8 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white mb-6">Overall Band Score</h2>
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mx-auto mb-6 neon-purple">
                        <span class="text-5xl font-bold text-white">{{ number_format($fullTestAttempt->overall_band_score, 1) }}</span>
                    </div>
                    
                    @php
                        $scoreClass = match(true) {
                            $fullTestAttempt->overall_band_score >= 7.5 => 'text-green-400',
                            $fullTestAttempt->overall_band_score >= 6.5 => 'text-blue-400',
                            $fullTestAttempt->overall_band_score >= 5.5 => 'text-yellow-400',
                            default => 'text-red-400'
                        };
                        
                        $scoreLabel = match(true) {
                            $fullTestAttempt->overall_band_score >= 8.0 => 'Expert User',
                            $fullTestAttempt->overall_band_score >= 7.0 => 'Good User',
                            $fullTestAttempt->overall_band_score >= 6.0 => 'Competent User',
                            $fullTestAttempt->overall_band_score >= 5.0 => 'Modest User',
                            default => 'Limited User'
                        };
                    @endphp
                    
                    <p class="{{ $scoreClass }} text-lg font-medium">{{ $scoreLabel }}</p>
                </div>
            </div>

            <!-- Section Scores -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Listening Score -->
                <div class="glass rounded-xl p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-violet-500/20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headphones text-violet-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Listening</h3>
                    <p class="text-3xl font-bold text-violet-400">
                        {{ $fullTestAttempt->listening_score ? number_format($fullTestAttempt->listening_score, 1) : 'N/A' }}
                    </p>
                    @if($fullTestAttempt->listeningAttempt())
                        <p class="text-sm text-gray-400 mt-2">
                            {{ $fullTestAttempt->listeningAttempt()->correct_answers ?? 0 }}/{{ $fullTestAttempt->listeningAttempt()->total_questions ?? 0 }} correct
                        </p>
                    @endif
                </div>

                <!-- Reading Score -->
                <div class="glass rounded-xl p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book-open text-emerald-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Reading</h3>
                    <p class="text-3xl font-bold text-emerald-400">
                        {{ $fullTestAttempt->reading_score ? number_format($fullTestAttempt->reading_score, 1) : 'N/A' }}
                    </p>
                    @if($fullTestAttempt->readingAttempt())
                        <p class="text-sm text-gray-400 mt-2">
                            {{ $fullTestAttempt->readingAttempt()->correct_answers ?? 0 }}/{{ $fullTestAttempt->readingAttempt()->total_questions ?? 0 }} correct
                        </p>
                    @endif
                </div>

                <!-- Writing Score -->
                <div class="glass rounded-xl p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-amber-500/20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-pen-fancy text-amber-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Writing</h3>
                    <p class="text-3xl font-bold text-amber-400">
                        {{ $fullTestAttempt->writing_score ? number_format($fullTestAttempt->writing_score, 1) : 'N/A' }}
                    </p>
                    @if($fullTestAttempt->writingAttempt())
                        <p class="text-sm text-gray-400 mt-2">
                            @if($fullTestAttempt->writingAttempt()->ai_band_score)
                                AI Evaluated
                            @else
                                Pending Evaluation
                            @endif
                        </p>
                    @endif
                </div>

                <!-- Speaking Score -->
                <div class="glass rounded-xl p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-rose-500/20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-microphone text-rose-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Speaking</h3>
                    <p class="text-3xl font-bold text-rose-400">
                        {{ $fullTestAttempt->speaking_score ? number_format($fullTestAttempt->speaking_score, 1) : 'N/A' }}
                    </p>
                    @if($fullTestAttempt->speakingAttempt())
                        <p class="text-sm text-gray-400 mt-2">
                            @if($fullTestAttempt->speakingAttempt()->ai_band_score)
                                AI Evaluated
                            @else
                                Pending Evaluation
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            <!-- Performance Analysis -->
            <div class="glass rounded-2xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">Performance Analysis</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Strengths & Weaknesses -->
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Score Breakdown</h3>
                        @php
                            $scores = [
                                'Listening' => $fullTestAttempt->listening_score,
                                'Reading' => $fullTestAttempt->reading_score,
                                'Writing' => $fullTestAttempt->writing_score,
                                'Speaking' => $fullTestAttempt->speaking_score
                            ];
                            $validScores = array_filter($scores, fn($score) => $score !== null);
                            $maxScore = !empty($validScores) ? max($validScores) : 0;
                            $minScore = !empty($validScores) ? min($validScores) : 0;
                        @endphp
                        
                        <div class="space-y-3">
                            @foreach($scores as $skill => $score)
                                @if($score !== null)
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-400">{{ $skill }}</span>
                                            <span class="text-white font-medium">{{ number_format($score, 1) }}</span>
                                        </div>
                                        <div class="w-full bg-gray-700 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-500 {{ $score == $maxScore ? 'bg-green-500' : ($score == $minScore ? 'bg-red-500' : 'bg-blue-500') }}"
                                                 style="width: {{ ($score / 9) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        @if(count($validScores) > 0)
                            <div class="mt-4 space-y-2">
                                <p class="text-sm text-gray-400">
                                    <span class="text-green-400">Strongest:</span> 
                                    {{ array_search($maxScore, $scores) }} ({{ number_format($maxScore, 1) }})
                                </p>
                                @if($maxScore != $minScore)
                                    <p class="text-sm text-gray-400">
                                        <span class="text-red-400">Needs improvement:</span> 
                                        {{ array_search($minScore, $scores) }} ({{ number_format($minScore, 1) }})
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <!-- Test Stats -->
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Test Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-700">
                                <span class="text-gray-400">Total Duration</span>
                                <span class="text-white font-medium">
                                    {{ $fullTestAttempt->start_time->diffInMinutes($fullTestAttempt->end_time) }} minutes
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-700">
                                <span class="text-gray-400">Completion Date</span>
                                <span class="text-white font-medium">
                                    {{ $fullTestAttempt->end_time->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-700">
                                <span class="text-gray-400">Test Type</span>
                                <span class="text-white font-medium">
                                    @if($fullTestAttempt->fullTest->is_premium)
                                        <span class="text-amber-400">
                                            <i class="fas fa-crown mr-1"></i>Premium
                                        </span>
                                    @else
                                        <span class="text-gray-400">Free</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('student.full-test.index') }}" class="btn-secondary text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Full Tests
                </a>
                
                <a href="{{ route('student.test.results') }}" class="btn-primary text-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    View All Results
                </a>
                
                <!-- View Individual Section Results -->
                <div class="flex-1 flex gap-2 justify-end">
                    @if($fullTestAttempt->listeningAttempt())
                        <a href="{{ route('student.test.results.show', $fullTestAttempt->listeningAttempt()) }}" 
                           class="btn-secondary !px-3 !py-2 text-sm"
                           title="View Listening Results">
                            <i class="fas fa-headphones"></i>
                        </a>
                    @endif
                    
                    @if($fullTestAttempt->readingAttempt())
                        <a href="{{ route('student.test.results.show', $fullTestAttempt->readingAttempt()) }}" 
                           class="btn-secondary !px-3 !py-2 text-sm"
                           title="View Reading Results">
                            <i class="fas fa-book-open"></i>
                        </a>
                    @endif
                    
                    @if($fullTestAttempt->writingAttempt())
                        <a href="{{ route('student.test.results.show', $fullTestAttempt->writingAttempt()) }}" 
                           class="btn-secondary !px-3 !py-2 text-sm"
                           title="View Writing Results">
                            <i class="fas fa-pen-fancy"></i>
                        </a>
                    @endif
                    
                    @if($fullTestAttempt->speakingAttempt())
                        <a href="{{ route('student.test.results.show', $fullTestAttempt->speakingAttempt()) }}" 
                           class="btn-secondary !px-3 !py-2 text-sm"
                           title="View Speaking Results">
                            <i class="fas fa-microphone"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-student-layout>
