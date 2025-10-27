{{-- resources/views/student/full-test/results.blade.php --}}
<x-student-layout>
    <x-slot:title>Full Test Results</x-slot>

    <!-- Minimal One Page Design -->
    <section class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Main Card -->
            <div class="glass-dark border border-white/10 rounded-2xl p-6 sm:p-8">
                
                <!-- Header -->
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-white mb-2">
                        Full Test Results
                    </h1>
                    <p class="text-gray-400 text-sm">
                        {{ $fullTestAttempt->fullTest->title }}
                    </p>
                    <p class="text-gray-500 text-xs mt-1">
                        {{ $fullTestAttempt->end_time ? $fullTestAttempt->end_time->format('M d, Y h:i A') : 'In Progress' }}
                    </p>
                </div>

                <!-- Overall Score -->
                <div class="text-center mb-8">
                    <p class="text-sm text-gray-400 uppercase tracking-wide mb-3">Overall Band Score</p>
                    <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-[#C8102E]/10 border-2 border-[#C8102E]/30">
                        <span class="text-4xl font-bold text-[#C8102E]">
                            {{ $fullTestAttempt->overall_band_score ? number_format($fullTestAttempt->overall_band_score, 1) : 'N/A' }}
                        </span>
                    </div>
                    @if($fullTestAttempt->overall_band_score)
                        @php
                            $scoreLabel = match(true) {
                                $fullTestAttempt->overall_band_score >= 8.0 => 'Expert User',
                                $fullTestAttempt->overall_band_score >= 7.0 => 'Good User',
                                $fullTestAttempt->overall_band_score >= 6.0 => 'Competent User',
                                $fullTestAttempt->overall_band_score >= 5.0 => 'Modest User',
                                default => 'Limited User'
                            };
                        @endphp
                        <p class="text-gray-400 text-sm mt-3">{{ $scoreLabel }}</p>
                    @endif
                </div>

                <!-- Section Scores - Compact Grid -->
                @php
                    $sections = [
                        'listening' => ['icon' => 'fa-headphones', 'label' => 'Listening'],
                        'reading' => ['icon' => 'fa-book-open', 'label' => 'Reading'],
                        'writing' => ['icon' => 'fa-pen-fancy', 'label' => 'Writing'],
                        'speaking' => ['icon' => 'fa-microphone', 'label' => 'Speaking']
                    ];
                    
                    $availableSections = $fullTestAttempt->fullTest->getAvailableSections();
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
                    @foreach($sections as $sectionKey => $sectionData)
                        @if(in_array($sectionKey, $availableSections))
                            @php
                                $scoreField = $sectionKey . '_score';
                                $score = $fullTestAttempt->$scoreField;
                            @endphp
                            <div class="glass border border-white/5 rounded-lg p-4 text-center">
                                <i class="fas {{ $sectionData['icon'] }} text-[#C8102E] text-lg mb-2"></i>
                                <p class="text-xs text-gray-400 mb-1">{{ $sectionData['label'] }}</p>
                                <p class="text-2xl font-bold text-white">
                                    {{ $score ? number_format($score, 1) : '-' }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Test Info - Minimal -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center">
                        <p class="text-xs text-gray-400">Duration</p>
                        <p class="text-sm font-medium text-white">
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
                    
                    <div class="text-center">
                        <p class="text-xs text-gray-400">Completed</p>
                        <p class="text-sm font-medium text-white">
                            {{ $fullTestAttempt->sectionAttempts->count() }}/{{ count($availableSections) }}
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-xs text-gray-400">Type</p>
                        <p class="text-sm font-medium {{ $fullTestAttempt->fullTest->is_premium ? 'text-yellow-400' : 'text-white' }}">
                            {{ $fullTestAttempt->fullTest->is_premium ? 'Premium' : 'Free' }}
                        </p>
                    </div>
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
                <div class="glass border border-white/5 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <span class="text-gray-400">Strongest:</span>
                            <span class="text-white ml-2 capitalize">{{ $strongestSection }}</span>
                            <span class="text-green-400 ml-1">({{ number_format($scores[$strongestSection], 1) }})</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Focus on:</span>
                            <span class="text-white ml-2 capitalize">{{ $weakestSection }}</span>
                            <span class="text-yellow-400 ml-1">({{ number_format($scores[$weakestSection], 1) }})</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>
</x-student-layout>
