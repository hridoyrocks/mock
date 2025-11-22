{{-- resources/views/student/full-test/results.blade.php --}}
<x-student-layout>
    <x-slot:title>Full Test Results</x-slot>

    <!-- Full Width Design -->
    <section class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto">

            <!-- Main Card -->
            <div class="glass-dark border border-white/10 rounded-2xl p-6 sm:p-8">

                <!-- Header - Redesigned Layout -->
                <div class="flex items-start justify-between mb-4">
                    <!-- Left: Title, Date, Time -->
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-2">
                            Full Test Results
                        </h1>
                        <p class="text-gray-500 text-base">
                            {{ $fullTestAttempt->end_time ? $fullTestAttempt->end_time->format('M d, Y') : 'In Progress' }}
                        </p>
                        <p class="text-gray-500 text-base">
                            {{ $fullTestAttempt->end_time ? $fullTestAttempt->end_time->format('h:i A') : '' }}
                        </p>
                    </div>

                    <!-- Right: Test Title -->
                    <div class="text-right">
                        <p class="text-gray-400 text-base mb-1">Test</p>
                        <p class="text-white text-xl font-semibold">
                            {{ $fullTestAttempt->fullTest->title }}
                        </p>
                    </div>
                </div>

                <!-- Overall Band Score - Top Center -->
                <div class="text-center mb-4">
                    <p class="text-base text-gray-400 uppercase tracking-wide mb-2">Overall Band Score</p>
                    @if($fullTestAttempt->overall_band_score !== null && $fullTestAttempt->overall_band_score !== '')
                        <div class="inline-block">
                            <span class="text-7xl font-bold text-[#C8102E]">
                                {{ number_format($fullTestAttempt->overall_band_score, 1) }}
                            </span>
                        </div>
                        @php
                            $scoreLabel = match(true) {
                                $fullTestAttempt->overall_band_score >= 8.0 => 'Expert User',
                                $fullTestAttempt->overall_band_score >= 7.0 => 'Good User',
                                $fullTestAttempt->overall_band_score >= 6.0 => 'Competent User',
                                $fullTestAttempt->overall_band_score >= 5.0 => 'Modest User',
                                default => 'Limited User'
                            };
                        @endphp
                        <p class="text-gray-400 text-lg mt-2">{{ $scoreLabel }}</p>
                    @else
                        <div class="inline-block">
                            <span class="text-3xl font-medium text-yellow-400 px-4 text-center">
                                Pending Evaluation
                            </span>
                        </div>
                        <p class="text-yellow-400/80 text-base mt-2">
                            Some sections are awaiting teacher evaluation
                        </p>
                    @endif
                </div>

                <!-- Section Scores - Small Cards with Different Style -->
                @php
                    $sections = [
                        'listening' => ['icon' => 'fa-headphones', 'label' => 'Listening', 'gradient' => 'from-purple-500/10 to-purple-600/5', 'border' => 'border-purple-500/20'],
                        'reading' => ['icon' => 'fa-book-open', 'label' => 'Reading', 'gradient' => 'from-blue-500/10 to-blue-600/5', 'border' => 'border-blue-500/20'],
                        'writing' => ['icon' => 'fa-pen-fancy', 'label' => 'Writing', 'gradient' => 'from-green-500/10 to-green-600/5', 'border' => 'border-green-500/20'],
                        'speaking' => ['icon' => 'fa-microphone', 'label' => 'Speaking', 'gradient' => 'from-orange-500/10 to-orange-600/5', 'border' => 'border-orange-500/20']
                    ];

                    $availableSections = $fullTestAttempt->fullTest->getAvailableSections();
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                    @foreach($sections as $sectionKey => $sectionData)
                        @if(in_array($sectionKey, $availableSections))
                            @php
                                $scoreField = $sectionKey . '_score';
                                $score = $fullTestAttempt->$scoreField;
                            @endphp
                            <div class="relative">
                                <!-- Gradient Background -->
                                <div class="absolute inset-0 bg-gradient-to-br {{ $sectionData['gradient'] }} rounded-xl"></div>

                                <!-- Card Content -->
                                <div class="relative border {{ $sectionData['border'] }} rounded-xl p-4 backdrop-blur-sm h-full">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-lg bg-[#C8102E]/20 flex items-center justify-center flex-shrink-0">
                                            <i class="fas {{ $sectionData['icon'] }} text-[#C8102E] text-lg"></i>
                                        </div>
                                        <h3 class="text-base font-semibold text-white">{{ $sectionData['label'] }}</h3>
                                    </div>

                                    <div class="mt-4">
                                        @if($score !== null && $score !== '')
                                            <div class="text-4xl font-bold text-white text-center">
                                                {{ number_format($score, 1) }}
                                            </div>
                                            <p class="text-xs text-gray-400 text-center mt-1">Band Score</p>
                                        @else
                                            <div class="text-lg font-medium text-yellow-400 text-center">
                                                Pending
                                            </div>
                                            <p class="text-xs text-gray-400 text-center mt-1">Awaiting</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Test Info - Compact with Evaluation Button -->
                @php
                    $hasWriting = in_array('writing', $availableSections);
                    $hasSpeaking = in_array('speaking', $availableSections);
                    $needsEvaluation = $hasWriting || $hasSpeaking;

                    $writingRequested = false;
                    $speakingRequested = false;
                    $writingCompleted = false;
                    $speakingCompleted = false;
                    $sectionsNotRequested = [];

                    if ($needsEvaluation) {
                        foreach ($fullTestAttempt->sectionAttempts as $sectionAttempt) {
                            if ($sectionAttempt->section_type === 'writing' && $hasWriting) {
                                $request = $sectionAttempt->studentAttempt->humanEvaluationRequest;
                                if ($request) {
                                    $writingRequested = true;
                                    $writingCompleted = $request->status === 'completed';
                                } else {
                                    $sectionsNotRequested[] = 'Writing';
                                }
                            }

                            if ($sectionAttempt->section_type === 'speaking' && $hasSpeaking) {
                                $request = $sectionAttempt->studentAttempt->humanEvaluationRequest;
                                if ($request) {
                                    $speakingRequested = true;
                                    $speakingCompleted = $request->status === 'completed';
                                } else {
                                    $sectionsNotRequested[] = 'Speaking';
                                }
                            }
                        }
                    }

                    $someRequested = $writingRequested || $speakingRequested;
                    $allCompleted = $writingCompleted && $speakingCompleted;
                    $hasUnrequestedSections = !empty($sectionsNotRequested);
                @endphp

                <div class="flex items-center justify-between gap-4 mb-8">
                    <!-- Test Info Cards - Smaller -->
                    <div class="flex gap-3">
                        <div class="text-center glass border border-white/5 rounded-lg px-4 py-2">
                            <p class="text-xs text-gray-400 mb-1">Duration</p>
                            <p class="text-base font-medium text-white">
                                @if($fullTestAttempt->start_time && $fullTestAttempt->end_time)
                                    @php
                                        $totalSeconds = $fullTestAttempt->start_time->diffInSeconds($fullTestAttempt->end_time);
                                        $hours = floor($totalSeconds / 3600);
                                        $minutes = floor(($totalSeconds % 3600) / 60);
                                    @endphp
                                    {{ $hours }}h {{ $minutes }}m
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <div class="text-center glass border border-white/5 rounded-lg px-4 py-2">
                            <p class="text-xs text-gray-400 mb-1">Completed</p>
                            <p class="text-base font-medium text-white">
                                {{ $fullTestAttempt->sectionAttempts->count() }}/{{ count($availableSections) }}
                            </p>
                        </div>

                        <div class="text-center glass border border-white/5 rounded-lg px-4 py-2">
                            <p class="text-xs text-gray-400 mb-1">Type</p>
                            <p class="text-base font-medium {{ $fullTestAttempt->fullTest->is_premium ? 'text-yellow-400' : 'text-white' }}">
                                {{ $fullTestAttempt->fullTest->is_premium ? 'Premium' : 'Free' }}
                            </p>
                        </div>
                    </div>

                    <!-- Evaluation Button -->
                    @if($needsEvaluation)
                        @if(!$someRequested)
                            <a href="{{ route('student.full-test.request-evaluation', $fullTestAttempt) }}"
                               class="inline-flex items-center px-6 py-2 bg-[#C8102E] hover:bg-[#A00D24] text-white font-semibold text-sm rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Request Evaluation
                            </a>
                        @elseif($writingCompleted || $speakingCompleted)
                            <!-- Show View Evaluation if at least one section is complete -->
                            <div class="flex gap-3">
                                <a href="{{ route('student.full-test.evaluation-details', $fullTestAttempt) }}"
                                   class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Evaluation
                                </a>
                                @if($hasUnrequestedSections)
                                    <a href="{{ route('student.full-test.request-evaluation', $fullTestAttempt) }}"
                                       class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Request More
                                    </a>
                                @endif
                            </div>
                        @elseif($hasUnrequestedSections)
                            <a href="{{ route('student.full-test.request-evaluation', $fullTestAttempt) }}"
                               class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Request More
                            </a>
                        @else
                            <div class="inline-flex items-center px-6 py-2 bg-yellow-600/50 text-white font-semibold text-sm rounded-lg">
                                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                In Progress
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Performance Summary - Optional -->
                @php
                    $scores = [];
                    foreach($availableSections as $section) {
                        $scoreField = $section . '_score';
                        if($fullTestAttempt->$scoreField) {
                            $scores[$section] = $fullTestAttempt->$scoreField;
                        }
                    }
                    if(!empty($scores)) {
                        $strongestSection = array_search(max($scores), $scores);
                        $weakestSection = array_search(min($scores), $scores);
                    }
                @endphp
                
                @if(!empty($scores) && $strongestSection !== $weakestSection)
                <div class="glass border border-white/5 rounded-xl p-6 mb-8">
                    <div class="flex items-center justify-between text-base">
                        <div>
                            <span class="text-gray-400 text-lg">Strongest:</span>
                            <span class="text-white ml-2 capitalize font-semibold text-lg">{{ $strongestSection }}</span>
                            <span class="text-green-400 ml-2 text-xl font-bold">({{ number_format($scores[$strongestSection], 1) }})</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-lg">Focus on:</span>
                            <span class="text-white ml-2 capitalize font-semibold text-lg">{{ $weakestSection }}</span>
                            <span class="text-yellow-400 ml-2 text-xl font-bold">({{ number_format($scores[$weakestSection], 1) }})</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Evaluation Status Messages -->
                @if($needsEvaluation && $someRequested && !($allCompleted && !$hasUnrequestedSections))
                    <div class="glass border border-yellow-500/30 rounded-xl p-4 mb-8">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-base text-white font-medium">
                                    @if($writingRequested && !$writingCompleted && $speakingRequested && !$speakingCompleted)
                                        Your Writing and Speaking evaluations are in progress
                                    @elseif($writingRequested && !$writingCompleted)
                                        Your Writing evaluation is in progress
                                    @elseif($speakingRequested && !$speakingCompleted)
                                        Your Speaking evaluation is in progress
                                    @endif
                                </p>
                                @if($writingCompleted || $speakingCompleted)
                                    <p class="text-sm text-gray-400 mt-1">Some evaluations are complete. Click "View Evaluation" to see details.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </section>
</x-student-layout>
