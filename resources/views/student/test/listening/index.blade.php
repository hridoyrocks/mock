<x-student-layout>
    <x-slot:title>Listening Tests</x-slot>

    <!-- Tests Grid -->
    <section class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            @if ($testSets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($testSets as $testSet)
                        @php
                            $attemptCount = \App\Models\StudentAttempt::where('test_set_id', $testSet->id)->count();
                            $userCompleted = auth()->user()->attempts()
                                ->where('test_set_id', $testSet->id)
                                ->where('status', 'completed')
                                ->exists();
                            $userAttempt = $userCompleted ? auth()->user()->attempts()
                                ->where('test_set_id', $testSet->id)
                                ->where('status', 'completed')
                                ->first() : null;
                        @endphp
                        
                        <div class="group relative">
                            <!-- Glow Effect -->
                            <div class="absolute inset-0 bg-gradient-to-br from-violet-600 to-purple-600 rounded-2xl blur opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                            
                            <!-- Card Content -->
                            <div class="relative glass rounded-2xl p-6 hover:border-violet-500/50 transition-all duration-300 hover:-translate-y-1">
                                <!-- Status Badge -->
                                @if($userCompleted)
                                    <div class="absolute top-4 right-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Completed
                                        </span>
                                    </div>
                                @endif

                                <!-- Test Icon -->
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-headphones-alt text-white text-2xl"></i>
                                </div>

                                <!-- Test Title -->
                                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-violet-400 transition-colors">
                                    {{ $testSet->title }}
                                </h3>

                                <!-- Test Stats -->
                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center text-sm text-gray-400">
                                        <i class="fas fa-clock mr-2 text-violet-400"></i>
                                        <span>30 minutes duration</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-400">
                                        <i class="fas fa-users mr-2 text-purple-400"></i>
                                        <span>{{ $attemptCount }} students attempted</span>
                                    </div>
                                    @if($userCompleted && $userAttempt->band_score)
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-star mr-2 text-yellow-400"></i>
                                            <span class="text-white">Your Score: <strong>{{ $userAttempt->band_score }}</strong></span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Button -->
                                @if($userCompleted)
                                    <a href="{{ route('student.results.show', $userAttempt) }}" 
                                       class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium hover:from-gray-700 hover:to-gray-800 transition-all group">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        View Results
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                @else
                                    <button onclick="startTest(this, '{{ route('student.listening.onboarding.confirm-details', $testSet) }}')"
                                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 text-white font-medium hover:from-violet-700 hover:to-purple-700 transition-all neon-purple group">
                                        <i class="fas fa-play-circle mr-2"></i>
                                        <span class="button-text">Start Test</span>
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="glass rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-violet-500/20 to-purple-500/20 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headphones-alt text-violet-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No Tests Available</h3>
                    <p class="text-gray-400 max-w-md mx-auto">
                        Listening tests will be available soon. Check back later or explore other sections.
                    </p>
                    <a href="{{ route('student.index') }}" 
                       class="inline-flex items-center mt-6 text-violet-400 hover:text-violet-300 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to All Tests
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Tips Section -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-7xl mx-auto">
            <div class="glass rounded-2xl p-8 border-violet-500/30">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-400 mr-3"></i>
                    Listening Test Tips
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-violet-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Read questions beforehand</h4>
                            <p class="text-sm text-gray-400">Use the time given to read questions and predict answers</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-purple-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Focus on keywords</h4>
                            <p class="text-sm text-gray-400">Listen for specific information related to the questions</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-pink-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-pink-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Don't panic if you miss something</h4>
                            <p class="text-sm text-gray-400">Keep listening and move on to the next question</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-indigo-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Watch for paraphrasing</h4>
                            <p class="text-sm text-gray-400">Answers often use different words than the questions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @push('scripts')
    <script>
        function startTest(button, url) {
            // Disable button
            button.disabled = true;
            
            // Change button content to loading state
            button.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Loading Test...</span>
            `;
            
            // Add loading class to button
            button.classList.add('opacity-90', 'cursor-not-allowed');
            
            // Navigate to URL after a small delay
            setTimeout(() => {
                window.location.href = url;
            }, 3000);
        }
    </script>
    @endpush
</x-student-layout>