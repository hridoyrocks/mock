{{-- resources/views/student/test/reading/index.blade.php --}}
<x-student-layout>
    <x-slot:title>Reading Tests</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 via-transparent to-green-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-500 mb-6 neon-blue">
                        <i class="fas fa-book-open text-white text-3xl"></i>
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4">
                        Reading Tests
                    </h1>
                    <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                        Enhance your reading comprehension with real IELTS passages
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Test Info Cards -->
    <section class="px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12">
                <div class="glass rounded-xl p-6 text-center hover-lift">
                    <i class="fas fa-clock text-emerald-400 text-2xl mb-3"></i>
                    <p class="text-2xl font-bold text-white">60</p>
                    <p class="text-sm text-gray-400">Minutes</p>
                </div>
                <div class="glass rounded-xl p-6 text-center hover-lift">
                    <i class="fas fa-list-ol text-green-400 text-2xl mb-3"></i>
                    <p class="text-2xl font-bold text-white">40</p>
                    <p class="text-sm text-gray-400">Questions</p>
                </div>
                <div class="glass rounded-xl p-6 text-center hover-lift">
                    <i class="fas fa-file-alt text-teal-400 text-2xl mb-3"></i>
                    <p class="text-2xl font-bold text-white">3</p>
                    <p class="text-sm text-gray-400">Passages</p>
                </div>
                <div class="glass rounded-xl p-6 text-center hover-lift">
                    <i class="fas fa-graduation-cap text-cyan-400 text-2xl mb-3"></i>
                    <p class="text-2xl font-bold text-white">Academic</p>
                    <p class="text-sm text-gray-400">Text Type</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tests Grid -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
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
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-green-600 rounded-2xl blur opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                            
                            <!-- Card Content -->
                            <div class="relative glass rounded-2xl p-6 hover:border-emerald-500/50 transition-all duration-300 hover:-translate-y-1">
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
                                    <button onclick="window.location.href='{{ route('student.reading.onboarding.confirm-details', $testSet) }}'"
                                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 text-white font-medium hover:from-emerald-700 hover:to-green-700 transition-all neon-blue group">
                                        <i class="fas fa-play-circle mr-2"></i>
                                        Start Test
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
</x-student-layout>