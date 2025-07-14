<x-student-layout>
    <x-slot:title>AI Speaking Evaluation</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                

                <!-- Test Info Header -->
                <div class="glass rounded-2xl p-8">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                                <i class="fas fa-robot text-purple-400 mr-3"></i>
                                Instant Speaking Evaluation
                            </h1>
                            <p class="text-gray-300">
                                {{ $attempt->testSet->title }} • {{ $attempt->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                        
                        <div class="glass rounded-xl px-8 py-6 text-center border-purple-500/30">
                            <p class="text-gray-400 text-sm mb-1">Overall Band Score</p>
                            <p class="text-5xl font-bold text-white">
                                {{ number_format($evaluation['overall_band'], 1) }}
                            </p>
                            <div class="mt-2 text-xs text-purple-400">AI Generated</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-4">
        <div class="max-w-7xl mx-auto">
            <!-- Overall Criteria Scores -->
            <div class="glass rounded-2xl p-6 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-chart-radar text-purple-400 mr-2"></i>
                    Overall Performance
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($evaluation['overall_scores'] as $criterion => $score)
                    <div class="glass rounded-xl p-6 text-center hover:border-purple-500/50 transition-all">
                        <p class="text-gray-400 text-sm mb-2">{{ $criterion }}</p>
                        <p class="text-3xl font-bold 
                           {{ $score >= 7 ? 'text-green-400' : ($score >= 5 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ number_format($score, 1) }}
                        </p>
                        <div class="mt-3 w-full h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-1000"
                                 style="width: {{ ($score/9)*100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Part-wise Results -->
            @foreach($evaluation['parts'] as $index => $part)
            <div class="glass rounded-2xl p-6 mb-6">
                <!-- Part Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 mr-3">
                            {{ $part['part_number'] }}
                        </span>
                        {{ $part['part_type'] }}
                    </h2>
                    <div class="glass rounded-lg px-4 py-2 border-purple-500/30">
                        <span class="text-2xl font-bold text-white">{{ number_format($part['band_score'], 1) }}</span>
                    </div>
                </div>

                <!-- Question & Duration -->
                <div class="glass rounded-xl p-5 mb-6 bg-purple-500/10 border-purple-500/30">
                    <p class="text-gray-400 text-sm mb-2">Question:</p>
                    <p class="text-white font-medium text-lg">{{ $part['question'] }}</p>
                    <div class="mt-3 flex items-center text-sm text-gray-400">
                        <i class="fas fa-clock text-purple-400 mr-2"></i>
                        Response Duration: {{ $part['duration'] }}
                    </div>
                </div>

                <!-- Transcription -->
                <div class="mb-6">
                    <button onclick="toggleTranscription({{ $index }})" 
                            class="flex items-center justify-between w-full glass rounded-xl p-4 hover:border-purple-500/50 transition-all">
                        <h3 class="font-semibold text-white">
                            <i class="fas fa-microphone text-purple-400 mr-2"></i>
                            Your Response (Transcription)
                        </h3>
                        <i class="fas fa-chevron-down text-purple-400 transition-transform" id="chevron-{{ $index }}"></i>
                    </button>
                    <div id="transcription-{{ $index }}" class="hidden mt-4 glass rounded-xl p-4 bg-blue-500/10 border-blue-500/30">
                        <p class="text-gray-300 whitespace-pre-wrap">{{ $part['transcription'] }}</p>
                    </div>
                </div>

                <!-- Detailed Feedback Grid -->
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <!-- Fluency and Coherence -->
                    <div class="glass rounded-xl p-5 border-l-4 border-blue-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-comment-dots text-blue-400 mr-2"></i>
                            Fluency and Coherence
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $part['feedback']['fluency_coherence'] }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($part['metrics']['speech_rate'])
                            <span class="glass px-3 py-1 rounded-full text-xs text-blue-400 border-blue-500/30">
                                <i class="fas fa-tachometer-alt mr-1"></i>
                                {{ $part['metrics']['speech_rate'] }} wpm
                            </span>
                            @endif
                            @if($part['metrics']['pause_frequency'])
                            <span class="glass px-3 py-1 rounded-full text-xs text-blue-400 border-blue-500/30">
                                <i class="fas fa-pause mr-1"></i>
                                Pause: {{ $part['metrics']['pause_frequency'] }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Lexical Resource -->
                    <div class="glass rounded-xl p-5 border-l-4 border-green-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-book text-green-400 mr-2"></i>
                            Lexical Resource
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $part['feedback']['lexical_resource'] }}</p>
                        @if(!empty($part['vocabulary_range']))
                        <div class="mt-4">
                            <p class="text-xs text-gray-400 mb-2">Vocabulary highlights:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($part['vocabulary_range'] as $word)
                                <span class="glass px-2 py-1 rounded text-xs text-green-400 border-green-500/30">
                                    {{ $word }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Grammar -->
                    <div class="glass rounded-xl p-5 border-l-4 border-yellow-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-spell-check text-yellow-400 mr-2"></i>
                            Grammatical Range and Accuracy
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $part['feedback']['grammar'] }}</p>
                    </div>

                    <!-- Pronunciation -->
                    <div class="glass rounded-xl p-5 border-l-4 border-purple-500">
                        <h3 class="font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-volume-up text-purple-400 mr-2"></i>
                            Pronunciation
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $part['feedback']['pronunciation'] }}</p>
                        @if(!empty($part['pronunciation_issues']))
                        <div class="mt-4">
                            <p class="text-xs text-gray-400 mb-2">Areas to work on:</p>
                            <ul class="space-y-1">
                                @foreach($part['pronunciation_issues'] as $issue)
                                <li class="text-xs text-gray-300 flex items-start">
                                    <i class="fas fa-circle text-purple-400 mr-2 mt-1" style="font-size: 4px;"></i>
                                    {{ $issue }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Part-specific Tips -->
                @if(!empty($part['tips']))
                <div class="glass rounded-xl p-5 bg-purple-500/10 border-purple-500/30">
                    <h3 class="font-semibold text-white mb-3 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                        Tips for This Part
                    </h3>
                    <ul class="space-y-2">
                        @foreach($part['tips'] as $tip)
                        <li class="flex items-start text-sm text-gray-300">
                            <i class="fas fa-check-circle text-purple-400 mr-2 mt-0.5"></i>
                            {{ $tip }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Audio Player -->
                @if($part['audio_url'])
                <div class="mt-6">
                    <label class="text-sm text-gray-400 mb-2 block">
                        <i class="fas fa-headphones mr-2"></i>Listen to your response:
                    </label>
                    <audio controls class="w-full">
                        <source src="{{ $part['audio_url'] }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
                @endif
            </div>
            @endforeach

            <!-- Overall Summary & Recommendations -->
            <div class="glass rounded-2xl p-8 bg-gradient-to-br from-purple-600/20 to-pink-600/20 border-purple-500/30 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-chart-line text-purple-400 mr-2"></i>
                    Overall Summary & Recommendations
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Strengths -->
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            Your Strengths
                        </h3>
                        <ul class="space-y-3">
                            @foreach($evaluation['strengths'] as $strength)
                            <li class="flex items-start text-gray-300">
                                <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5"></i>
                                {{ $strength }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Areas for Improvement -->
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-line text-blue-400 mr-2"></i>
                            Priority Areas for Improvement
                        </h3>
                        <ul class="space-y-3">
                            @foreach($evaluation['improvements'] as $improvement)
                            <li class="flex items-start text-gray-300">
                                <i class="fas fa-arrow-up text-purple-400 mr-3 mt-0.5"></i>
                                {{ $improvement }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Study Plan -->
                <div class="glass rounded-xl p-6">
                    <h3 class="font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-graduation-cap text-purple-400 mr-2"></i>
                        Personalized Study Plan
                    </h3>
                    <div class="space-y-3">
                        @foreach($evaluation['study_plan'] as $step => $action)
                        <div class="flex items-start">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm mr-4 flex-shrink-0">
                                {{ $step + 1 }}
                            </span>
                            <p class="text-gray-300 pt-1">{{ $action }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Progress Tracking -->
            @if(isset($previousScores))
            <div class="glass rounded-2xl p-6 mb-8">
                <h2 class="text-xl font-bold text-white mb-6">
                    <i class="fas fa-chart-area text-purple-400 mr-2"></i>
                    Your Progress Over Time
                </h2>
                <div class="relative" style="height: 300px;">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-center">
                <a href="{{ route('student.speaking.index') }}" 
                   class="px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
                    <i class="fas fa-redo mr-2"></i> Practice Again
                </a>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    function toggleTranscription(index) {
        const transcription = document.getElementById(`transcription-${index}`);
        const chevron = document.getElementById(`chevron-${index}`);
        transcription.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    @if(isset($previousScores))
    // Progress Chart with dark theme
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($previousScores->pluck('date')) !!},
            datasets: [
                {
                    label: 'Overall Score',
                    data: {!! json_encode($previousScores->pluck('overall')) !!},
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.3,
                    borderWidth: 3
                },
                {
                    label: 'Fluency',
                    data: {!! json_encode($previousScores->pluck('fluency')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    borderWidth: 2
                },
                {
                    label: 'Pronunciation',
                    data: {!! json_encode($previousScores->pluck('pronunciation')) !!},
                    borderColor: 'rgb(236, 72, 153)',
                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
                    tension: 0.3,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#fff',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 9,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                }
            }
        }
    });
    @endif
    </script>
    
    @push('styles')
    <style>
        audio::-webkit-media-controls-panel {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .rotate-180 {
            transform: rotate(180deg);
        }
        
        @media print {
            .glass {
                background: white !important;
                color: black !important;
                border: 1px solid #ddd !important;
            }
            
            .text-white, .text-gray-300, .text-gray-400 {
                color: black !important;
            }
            
            body {
                background: white !important;
            }
        }
    </style>
    @endpush
    @endpush
</x-student-layout>