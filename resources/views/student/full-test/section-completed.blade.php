{{-- resources/views/student/full-test/section-completed.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Section Completed - IELTS Mock Test</title>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center">
    
    <!-- Centered Container -->
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-3xl mx-auto">
            
            <!-- Main Card - White Background -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8 sm:p-10">
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                        {{ ucfirst($completedSection) }} Section Completed
                    </h1>
                    <p class="text-gray-600">
                        Section successfully submitted
                    </p>
                </div>

                <!-- Score Display (if available) -->
                @if(isset($sectionScore) && $sectionScore > 0)
                <div class="bg-[#C8102E]/5 border border-[#C8102E]/20 rounded-xl p-6 sm:p-8 mb-8">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 uppercase tracking-wide mb-3">
                            {{ ucfirst($completedSection) }} Band Score
                        </p>
                        <div class="text-5xl sm:text-6xl font-bold text-[#C8102E]">
                            {{ number_format($sectionScore, 1) }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Progress Status -->
                <div class="mb-8">
                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-3">
                            <span>Progress</span>
                            <span class="font-medium">{{ $completedSections }}/{{ $totalSections }} completed</span>
                        </div>
                        <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-3 bg-[#C8102E] rounded-full transition-all duration-300"
                                 style="width: {{ $progressPercentage }}%">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sections Status Grid - Larger -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach(['listening', 'reading', 'writing', 'speaking'] as $section)
                            @if(in_array($section, $availableSections))
                            <div class="text-center">
                                <div class="rounded-xl p-4 mb-2 border-2 {{ in_array($section, $completedSectionsList) ? 'border-[#C8102E]/30 bg-[#C8102E]/5' : ($section === $nextSection ? 'border-gray-300 bg-gray-50' : 'border-gray-200 bg-white') }}">
                                    @php
                                        $icons = [
                                            'listening' => 'fa-headphones',
                                            'reading' => 'fa-book-open',
                                            'writing' => 'fa-pen-fancy',
                                            'speaking' => 'fa-microphone'
                                        ];
                                    @endphp
                                    <i class="fas {{ $icons[$section] }} text-xl sm:text-2xl {{ in_array($section, $completedSectionsList) ? 'text-[#C8102E]' : ($section === $nextSection ? 'text-gray-700' : 'text-gray-400') }}"></i>
                                </div>
                                <p class="text-sm font-medium {{ in_array($section, $completedSectionsList) ? 'text-[#C8102E]' : ($section === $nextSection ? 'text-gray-900' : 'text-gray-500') }}">
                                    {{ ucfirst($section) }}
                                </p>
                                <p class="text-xs mt-1 {{ in_array($section, $completedSectionsList) ? 'text-[#C8102E]/70' : ($section === $nextSection ? 'text-gray-600' : 'text-gray-400') }}">
                                    @if(in_array($section, $completedSectionsList))
                                        Complete
                                    @elseif($section === $nextSection)
                                        Next
                                    @else
                                        Pending
                                    @endif
                                </p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons - Larger -->
                <div class="space-y-4">
                    @if($hasNextSection)
                        <!-- Continue Button -->
                        <form action="{{ route('student.full-test.section', ['fullTestAttempt' => $fullTestAttemptId, 'section' => $nextSection]) }}" method="GET" class="w-full">
                            <button type="submit" 
                                    class="w-full py-4 px-6 rounded-xl bg-[#C8102E] text-white font-semibold text-lg hover:bg-[#A00E27] transition-all transform hover:scale-[1.02]">
                                <span>Continue to {{ ucfirst($nextSection) }} Section</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </form>
                        
                        <!-- Take Break Option -->
                        <div class="text-center">
                            <a href="{{ route('student.dashboard') }}" 
                               class="text-gray-600 hover:text-[#C8102E] transition-colors font-medium">
                                Take a break (Progress saved)
                            </a>
                        </div>
                    @else
                        <!-- View Results Button -->
                        <a href="{{ route('student.full-test.results', $fullTestAttemptId) }}"
                           class="block w-full py-4 px-6 rounded-xl bg-[#C8102E] text-white font-semibold text-lg text-center hover:bg-[#A00E27] transition-all transform hover:scale-[1.02]">
                            <span>View Full Test Results</span>
                            <i class="fas fa-chart-bar ml-2"></i>
                        </a>
                    @endif
                </div>

                <!-- Next Section Info (if applicable) -->
                @if($hasNextSection)
                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-gray-500 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">
                                @if($nextSection === 'listening')
                                    Next: Listening section (30 minutes, 40 questions)
                                @elseif($nextSection === 'reading')
                                    Next: Reading section (60 minutes, 40 questions)
                                @elseif($nextSection === 'writing')
                                    Next: Writing section (60 minutes, 2 tasks)
                                @elseif($nextSection === 'speaking')
                                    Next: Speaking section (11-14 minutes, 3 parts)
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-6 p-4 bg-[#C8102E]/5 rounded-xl border border-[#C8102E]/20">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-[#C8102E] mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-700">
                                All sections completed. Your overall band score is now available.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</body>
</html>
