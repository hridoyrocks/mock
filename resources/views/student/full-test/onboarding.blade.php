{{-- resources/views/student/full-test/onboarding.blade.php --}}
<x-student-layout>
    <x-slot:title>{{ $fullTest->title }} - Test Instructions</x-slot>

    <!-- Minimal Clean Design with Dashboard Red Theme - Wider Layout -->
    <section class="px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="max-w-4xl mx-auto">
            
            <!-- Main Card - Wider -->
            <div class="glass-dark border border-white/10 rounded-2xl p-6 lg:p-8">
                
                <!-- Header - Smaller -->
                <div class="text-center mb-8">
                    <h1 class="text-xl lg:text-2xl font-bold text-white mb-2">
                        Test Instructions
                    </h1>
                    <p class="text-gray-400 text-sm max-w-2xl mx-auto">
                        This mock test simulates the actual IELTS computer-based test environment. 
                        Follow all instructions to get the most accurate practice experience.
                    </p>
                </div>

                <!-- Content Sections - Compact -->
                <div class="space-y-5">
                    
                    <!-- System Requirements -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#C8102E]/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-laptop text-[#C8102E]"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-white font-semibold mb-1.5 text-sm">System Requirements</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Make sure you have a <span class="text-white">stable internet</span> connection, a working 
                                @if(in_array('listening', $fullTest->getAvailableSections()))
                                    <span class="text-white">headphone</span>
                                @endif
                                @if(in_array('speaking', $fullTest->getAvailableSections()))
                                    @if(in_array('listening', $fullTest->getAvailableSections())) and @endif
                                    <span class="text-white">microphone</span>
                                @endif
                                . Use a desktop or laptop computer for accurate experience.
                            </p>
                        </div>
                    </div>

                    <!-- Test Duration -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#C8102E]/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-[#C8102E]"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-white font-semibold mb-2 text-sm">Test Duration</h3>
                            
                            @php
                                $sections = $fullTest->getAvailableSections();
                                $totalMinutes = 0;
                                $sectionTimes = [
                                    'listening' => ['time' => 32, 'label' => 'Listening Test', 'duration' => '32 mins', 'icon' => 'fa-headphones'],
                                    'reading' => ['time' => 60, 'label' => 'Reading Test', 'duration' => '60 mins', 'icon' => 'fa-book-open'],
                                    'writing' => ['time' => 60, 'label' => 'Writing Test', 'duration' => '60 mins', 'icon' => 'fa-pen-fancy'],
                                    'speaking' => ['time' => 13, 'label' => 'Speaking Test', 'duration' => '13-15 mins', 'icon' => 'fa-microphone']
                                ];
                            @endphp
                            
                            <p class="text-gray-400 text-sm mb-3">
                                The test will take approximately 
                                @php
                                    foreach($sections as $section) {
                                        if(isset($sectionTimes[$section])) {
                                            $totalMinutes += $sectionTimes[$section]['time'];
                                        }
                                    }
                                    $hours = floor($totalMinutes / 60);
                                    $mins = $totalMinutes % 60;
                                @endphp
                                <span class="text-white font-semibold">{{ $hours }} hours and {{ $mins }} minutes</span> in total.
                            </p>
                            
                            <!-- Test Duration Cards Grid -->
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($sections as $section)
                                    @if(isset($sectionTimes[$section]))
                                        @php $sectionInfo = $sectionTimes[$section]; @endphp
                                        <div class="flex items-center gap-2.5 p-3 glass border border-white/5 rounded-lg">
                                            <div class="w-8 h-8 rounded bg-[#C8102E]/10 flex items-center justify-center flex-shrink-0">
                                                <i class="fas {{ $sectionInfo['icon'] }} text-[#C8102E] text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-500">{{ $sectionInfo['label'] }}</p>
                                                <p class="text-sm text-white font-medium">{{ $sectionInfo['duration'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Exam Rules -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#C8102E]/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clipboard-list text-[#C8102E]"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-white font-semibold mb-1.5 text-sm">Exam Rules</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                You will take the section tests one by one starting with Listening test. 
                                Once you start a section, the <span class="text-white">timer cannot be paused</span>. 
                                Ensure you have the time and environment to complete each section without interruption.
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Important Note Box - Compact -->
                <div class="mt-6 p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/30">
                    <div class="flex items-start gap-2.5">
                        <i class="fas fa-exclamation-circle text-yellow-400 text-sm mt-0.5"></i>
                        <p class="text-sm text-gray-300">
                            <strong class="text-yellow-400">Important:</strong> Make sure you are in a quiet environment 
                            and have allocated enough uninterrupted time before starting the test.
                        </p>
                    </div>
                </div>

                <!-- Action Button - Compact -->
                <div class="mt-8 text-center">
                    @if($inProgressAttempt)
                        <form action="{{ route('student.full-test.start', $fullTest) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-2.5 rounded-lg bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all transform hover:scale-105">
                                Understand & Continue
                            </button>
                        </form>
                    @else
                        <form action="{{ route('student.full-test.start', $fullTest) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-2.5 rounded-lg bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all transform hover:scale-105">
                                Understand & Continue
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </section>
</x-student-layout>
