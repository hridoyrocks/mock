{{-- resources/views/student/test/reading/index.blade.php --}}
<x-student-layout>
    <x-slot:title>Reading Tests</x-slot>

    <!-- Tests Grid -->
    <section class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            @if ($testSets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($testSets as $testSet)
                        @php
                            $attemptCount = \App\Models\StudentAttempt::where('test_set_id', $testSet->id)->count();
                            $userAttempts = auth()->user()->attempts()
                                ->where('test_set_id', $testSet->id)
                                ->where('status', 'completed')
                                ->orderBy('attempt_number', 'desc')
                                ->get();
                            $userCompleted = $userAttempts->count() > 0;
                            $latestAttempt = $userAttempts->first();
                            $totalAttempts = $userAttempts->count();
                        @endphp
                        
                        <div class="group relative">
                            <!-- Glow Effect -->
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-green-600 rounded-2xl blur opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                            
                            <!-- Card Content -->
                            <div class="relative glass rounded-2xl p-6 hover:border-emerald-500/50 transition-all duration-300 hover:-translate-y-1">
                                <!-- Status Badge -->
                                @if($userCompleted)
                                    <div class="absolute top-4 right-4">
                                        @if($totalAttempts > 1)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30">
                                                <i class="fas fa-redo mr-1"></i>
                                                Retaken ({{ $totalAttempts }}x)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Completed
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Test Icon -->
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-book-reader text-white text-2xl"></i>
                                </div>

                                <!-- Test Title -->
                                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-emerald-400 transition-colors">
                                    {{ $testSet->title }}
                                </h3>

                                <!-- Test Stats -->
                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center text-sm text-gray-400">
                                        <i class="fas fa-clock mr-2 text-emerald-400"></i>
                                        <span>60 minutes duration</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-400">
                                        <i class="fas fa-users mr-2 text-green-400"></i>
                                        <span>{{ $attemptCount }} students attempted</span>
                                    </div>
                                    @if($userCompleted && $latestAttempt->band_score)
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-star mr-2 text-yellow-400"></i>
                                            <span class="text-white">Your Score: <strong>{{ $latestAttempt->band_score }}</strong></span>
                                            @if($totalAttempts > 1)
                                                <span class="text-gray-400 text-xs ml-2">(Latest)</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Button -->
                                @if($userCompleted)
                                    <div class="space-y-3">
                                        <a href="{{ route('student.results.show', $latestAttempt) }}" 
                                           class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium hover:from-gray-700 hover:to-gray-800 transition-all group">
                                            <i class="fas fa-chart-bar mr-2"></i>
                                            View Results
                                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                        </a>
                                        
                                        @if($latestAttempt->canRetake())
                                            <form action="{{ route('student.results.retake', $latestAttempt) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 text-white font-medium hover:from-emerald-700 hover:to-green-700 transition-all group">
                                                    <i class="fas fa-redo mr-2"></i>
                                                    Retake Test
                                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <button onclick="startTest(this, '{{ route('student.reading.onboarding.confirm-details', $testSet) }}')"
                                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 text-white font-medium hover:from-emerald-700 hover:to-green-700 transition-all neon-blue group">
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
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500/20 to-green-500/20 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-book-open text-emerald-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No Tests Available</h3>
                    <p class="text-gray-400 max-w-md mx-auto">
                        Reading tests will be available soon. Check back later or explore other sections.
                    </p>
                    <a href="{{ route('student.index') }}" 
                       class="inline-flex items-center mt-6 text-emerald-400 hover:text-emerald-300 font-medium">
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
            <div class="glass rounded-2xl p-8 border-emerald-500/30">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-400 mr-3"></i>
                    Reading Test Tips
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-emerald-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Skim first, then scan</h4>
                            <p class="text-sm text-gray-400">Get the main idea quickly before finding specific details</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Time management is crucial</h4>
                            <p class="text-sm text-gray-400">Spend about 20 minutes on each passage</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-teal-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Read questions carefully</h4>
                            <p class="text-sm text-gray-400">Understand exactly what information you need to find</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-cyan-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Look for synonyms</h4>
                            <p class="text-sm text-gray-400">Questions often paraphrase information from the text</p>
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