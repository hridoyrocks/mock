{{-- resources/views/student/full-test/results.blade.php --}}
<x-student-layout>
    <x-slot:title>Full Test Results</x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Test Completed!</h1>
                <p class="text-gray-400">{{ $fullTestAttempt->fullTest->title }}</p>
                <p class="text-gray-500 text-sm mt-2">
                    Completed on {{ $fullTestAttempt->end_time ? $fullTestAttempt->end_time->format('M d, Y h:i A') : 'In Progress' }}
                </p>
            </div>

            <!-- Overall Score Card -->
            <div class="glass rounded-2xl p-8 mb-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white mb-6">Overall Band Score</h2>
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mx-auto mb-6">
                        <span class="text-5xl font-bold text-white">
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
                        <p class="text-purple-400 text-lg font-medium">{{ $scoreLabel }}</p>
                    @endif
                </div>
            </div>

            <!-- Section Scores Grid -->
            @php
                $sections = [
                    'listening' => ['icon' => 'fa-headphones', 'color' => 'violet', 'label' => 'Listening'],
                    'reading' => ['icon' => 'fa-book-open', 'color' => 'emerald', 'label' => 'Reading'],
                    'writing' => ['icon' => 'fa-pen-fancy', 'color' => 'amber', 'label' => 'Writing'],
                    'speaking' => ['icon' => 'fa-microphone', 'color' => 'rose', 'label' => 'Speaking']
                ];
                
                $availableSections = $fullTestAttempt->fullTest->getAvailableSections();
                $gridCols = count($availableSections) == 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-4';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 {{ $gridCols }} gap-6 mb-8">
                @foreach($sections as $sectionKey => $sectionData)
                    @if(in_array($sectionKey, $availableSections))
                        <div class="glass rounded-xl p-6 text-center">
                            <div class="w-16 h-16 rounded-full bg-{{ $sectionData['color'] }}-500/20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas {{ $sectionData['icon'] }} text-{{ $sectionData['color'] }}-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-white mb-2">{{ $sectionData['label'] }}</h3>
                            <p class="text-3xl font-bold text-{{ $sectionData['color'] }}-400">
                                @php
                                    $scoreField = $sectionKey . '_score';
                                @endphp
                                {{ $fullTestAttempt->$scoreField ? number_format($fullTestAttempt->$scoreField, 1) : 'N/A' }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Test Information -->
            <div class="glass rounded-2xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">Test Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Test Duration</p>
                        <p class="text-white text-lg font-medium">
                            @if($fullTestAttempt->start_time && $fullTestAttempt->end_time)
                                @php
                                    $totalSeconds = $fullTestAttempt->start_time->diffInSeconds($fullTestAttempt->end_time);
                                    $hours = floor($totalSeconds / 3600);
                                    $minutes = floor(($totalSeconds % 3600) / 60);
                                    $seconds = $totalSeconds % 60;
                                    
                                    $durationParts = [];
                                    if ($hours > 0) {
                                        $durationParts[] = $hours . ' ' . ($hours == 1 ? 'Hour' : 'Hours');
                                    }
                                    if ($minutes > 0) {
                                        $durationParts[] = $minutes . ' ' . ($minutes == 1 ? 'Minute' : 'Minutes');
                                    }
                                    if ($seconds > 0 || empty($durationParts)) {
                                        $durationParts[] = $seconds . ' ' . ($seconds == 1 ? 'Second' : 'Seconds');
                                    }
                                    
                                    $formattedDuration = implode(' ', $durationParts);
                                @endphp
                                {{ $formattedDuration }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Test Status</p>
                        <p class="text-white text-lg font-medium capitalize">
                            {{ $fullTestAttempt->status }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Sections Completed</p>
                        <p class="text-white text-lg font-medium">
                            {{ $fullTestAttempt->sectionAttempts->count() }} / {{ count($availableSections) }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Test Type</p>
                        <p class="text-white text-lg font-medium">
                            @if($fullTestAttempt->fullTest->is_premium)
                                <span class="text-amber-400">
                                    <i class="fas fa-crown mr-1"></i>Premium
                                </span>
                            @else
                                <span class="text-gray-400">Free</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('student.full-test.index') }}" class="btn-secondary text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Full Tests
                </a>
                
                <a href="{{ route('student.dashboard') }}" class="btn-primary text-center">
                    <i class="fas fa-home mr-2"></i>
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-student-layout>
