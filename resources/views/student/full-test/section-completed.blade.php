<x-student-layout>
    <x-slot:title>Section Completed</x-slot>

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Success Animation Container -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-6 animate-bounce"
                     :class="darkMode ? 'bg-green-900' : 'bg-green-100'">
                    <svg class="w-12 h-12" :class="darkMode ? 'text-green-400' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-bold mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    {{ ucfirst($completedSection) }} Section Completed! 🎉
                </h1>
                
                <p class="text-lg" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                    Great job! You've successfully completed the {{ ucfirst($completedSection) }} section.
                </p>
            </div>

            <!-- Score Card (if available) -->
            @if(isset($sectionScore) && $sectionScore > 0)
            <div class="rounded-2xl shadow-xl p-8 mb-6 border-2"
                 :class="darkMode ? 'glass-dark border-green-800' : 'bg-white border-green-200'">
                <div class="text-center">
                    <p class="text-sm font-medium uppercase tracking-wide mb-2"
                       :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                        Your {{ ucfirst($completedSection) }} Band Score
                    </p>
                    <div class="text-6xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-2">
                        {{ number_format($sectionScore, 1) }}
                    </div>
                    <div class="flex items-center justify-center gap-1 text-yellow-500">
                        @for($i = 1; $i <= 9; $i++)
                            @if($i <= floor($sectionScore))
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @elseif($i == ceil($sectionScore) && $sectionScore - floor($sectionScore) >= 0.5)
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <defs>
                                        <linearGradient id="half-fill">
                                            <stop offset="50%" stop-color="currentColor"/>
                                            <stop offset="50%" stop-color="#d1d5db"/>
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#half-fill)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 fill-current" :class="darkMode ? 'text-gray-600' : 'text-gray-300'" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
            @endif

            <!-- Progress Overview -->
            <div class="rounded-2xl shadow-xl p-8 mb-6"
                 :class="darkMode ? 'glass-dark' : 'bg-white'">
                <h2 class="text-xl font-semibold mb-6" :class="darkMode ? 'text-white' : 'text-gray-900'">Test Progress</h2>
                
                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                        <span>{{ $completedSections }} of {{ $totalSections }} sections completed</span>
                        <span>{{ $progressPercentage }}%</span>
                    </div>
                    <div class="w-full rounded-full h-3 overflow-hidden"
                         :class="darkMode ? 'bg-gray-700' : 'bg-gray-200'">
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 h-3 rounded-full transition-all duration-500 ease-out"
                             style="width: {{ $progressPercentage }}%">
                        </div>
                    </div>
                </div>
                
                <!-- Sections Status -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['listening', 'reading', 'writing', 'speaking'] as $section)
                        @if(in_array($section, $availableSections))
                        <div class="text-center p-4 rounded-lg border-2"
                             :class="darkMode ? 
                                ({{ in_array($section, $completedSectionsList) ? 'true' : 'false' }} ? 'bg-green-900/20 border-green-800' : 'bg-gray-700/50 border-gray-600') :
                                ({{ in_array($section, $completedSectionsList) ? 'true' : 'false' }} ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200')">
                            @if(in_array($section, $completedSectionsList))
                                <svg class="w-8 h-8 mx-auto mb-2" :class="darkMode ? 'text-green-400' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($section === $nextSection)
                                <svg class="w-8 h-8 mx-auto mb-2 animate-pulse" :class="darkMode ? 'text-blue-400' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 mx-auto mb-2" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                            
                            <p class="text-sm font-medium" :class="darkMode ? 
                                ({{ in_array($section, $completedSectionsList) ? 'true' : 'false' }} ? 'text-green-300' : ({{ $section === $nextSection ? 'true' : 'false' }} ? 'text-blue-300' : 'text-gray-400')) :
                                ({{ in_array($section, $completedSectionsList) ? 'true' : 'false' }} ? 'text-green-700' : ({{ $section === $nextSection ? 'true' : 'false' }} ? 'text-blue-700' : 'text-gray-600'))">
                                {{ ucfirst($section) }}
                            </p>
                            
                            @if(in_array($section, $completedSectionsList))
                                <p class="text-xs mt-1" :class="darkMode ? 'text-green-400' : 'text-green-600'">Completed</p>
                            @elseif($section === $nextSection)
                                <p class="text-xs mt-1" :class="darkMode ? 'text-blue-400' : 'text-blue-600'">Next</p>
                            @else
                                <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Pending</p>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                @if($hasNextSection)
                    <!-- Continue to Next Section -->
                    <form action="{{ route('student.full-test.section', ['fullTestAttempt' => $fullTestAttemptId, 'section' => $nextSection]) }}" method="GET" class="w-full">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-3">
                            <span class="text-lg">Continue to {{ ucfirst($nextSection) }} Section</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </form>
                    
                    <!-- Take a Break -->
                    <div class="text-center">
                        <p class="text-sm mb-3" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                            Need a break? No problem!
                        </p>
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex items-center gap-2 font-medium transition-colors"
                           :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-gray-900'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Take a Break - Resume Later</span>
                        </a>
                        <p class="text-xs mt-2" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                            Your progress is saved automatically
                        </p>
                    </div>
                @else
                    <!-- View Full Test Results -->
                    <a href="{{ route('student.full-test.results', $fullTestAttemptId) }}"
                       class="w-full bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-lg">View Full Test Results</span>
                    </a>
                @endif
            </div>

            <!-- Motivational Message -->
            <div class="mt-8 text-center">
                @if($hasNextSection)
                    <div class="border rounded-xl p-6"
                         :class="darkMode ? 'bg-blue-900/20 border-blue-800' : 'bg-blue-50 border-blue-200'">
                        <p class="font-medium mb-2" :class="darkMode ? 'text-blue-200' : 'text-blue-900'">
                            💪 Keep up the great work!
                        </p>
                        <p class="text-sm" :class="darkMode ? 'text-blue-300' : 'text-blue-700'">
                            You're {{ $progressPercentage }}% through the test. Stay focused and give your best in the next section!
                        </p>
                    </div>
                @else
                    <div class="border rounded-xl p-6"
                         :class="darkMode ? 'bg-green-900/20 border-green-800' : 'bg-green-50 border-green-200'">
                        <p class="font-medium mb-2" :class="darkMode ? 'text-green-200' : 'text-green-900'">
                            🎊 Congratulations!
                        </p>
                        <p class="text-sm" :class="darkMode ? 'text-green-300' : 'text-green-700'">
                            You've completed all sections of the full test. Check your results to see how you did!
                        </p>
                    </div>
                @endif
            </div>

            <!-- Tips Section -->
            @if($hasNextSection)
            <div class="mt-6 rounded-xl shadow-lg p-6 border-l-4 border-yellow-400"
                 :class="darkMode ? 'glass-dark' : 'bg-white'">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            Quick Tips for Next Section:
                        </h3>
                        <ul class="text-sm space-y-1" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                            @if($nextSection === 'listening')
                                <li>• Make sure your audio is working properly</li>
                                <li>• Take notes while listening</li>
                                <li>• Pay attention to spelling and grammar</li>
                            @elseif($nextSection === 'reading')
                                <li>• Skim the passage first to get an overview</li>
                                <li>• Manage your time effectively (20 minutes per passage)</li>
                                <li>• Look for keywords in questions</li>
                            @elseif($nextSection === 'writing')
                                <li>• Plan your essay structure before writing</li>
                                <li>• Task 1: 20 minutes, Task 2: 40 minutes</li>
                                <li>• Check for spelling and grammar errors</li>
                            @elseif($nextSection === 'speaking')
                                <li>• Test your microphone before starting</li>
                                <li>• Speak clearly and at a natural pace</li>
                                <li>• Develop your answers with examples</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Auto-scroll to top on page load -->
    <script>
        window.scrollTo(0, 0);
    </script>
</x-student-layout>
