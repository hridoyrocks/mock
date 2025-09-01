<x-student-layout>
    <x-slot:title>Reading Tests</x-slot>

    <!-- Category Filter -->
    @if($categories->count() > 0)
    <section class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glass rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-400"></i>
                    Filter by Category
                </h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('student.reading.index') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl transition-all {{ !$selectedCategory ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-lg' : 'glass text-gray-300 hover:text-white hover:border-blue-500/50' }}">
                        <i class="fas fa-th mr-2"></i>
                        All Categories
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('student.reading.index', ['category' => $category->slug]) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-xl transition-all {{ $selectedCategory && $selectedCategory->id == $category->id ? 'text-white shadow-lg' : 'glass text-gray-300 hover:text-white hover:border-blue-500/50' }}"
                           @if($selectedCategory && $selectedCategory->id == $category->id)
                               style="background: linear-gradient(135deg, {{ $category->color }}dd, {{ $category->color }}99);"
                           @endif>
                            @if($category->icon)
                                <i class="{{ $category->icon }} mr-2" style="color: {{ $selectedCategory && $selectedCategory->id == $category->id ? 'white' : $category->color }};"></i>
                            @else
                                <div class="w-5 h-5 mr-2 rounded" style="background-color: {{ $category->color }};"></div>
                            @endif
                            {{ $category->name }}
                            <span class="ml-2 text-xs opacity-75">({{ $category->reading_count ?? 0 }})</span>
                        </a>
                    @endforeach
                </div>
                
                @if($selectedCategory)
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p class="text-sm text-gray-400">
                        <i class="fas fa-info-circle mr-2 text-blue-400"></i>
                        {{ $selectedCategory->description ?: 'Showing tests in ' . $selectedCategory->name . ' category' }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Tests Grid -->
    <section class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-white">
                    @if($selectedCategory)
                        {{ $selectedCategory->name }} - Reading Tests
                    @else
                        All Reading Tests
                    @endif
                </h2>
                <p class="text-gray-400 mt-2">
                    {{ $testSets->count() }} {{ Str::plural('test', $testSets->count()) }} available
                </p>
            </div>

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
                            
                            // Get categories for this test
                            $testCategories = $testSet->categories;
                        @endphp
                        
                        <div class="group relative">
                            <!-- Glow Effect -->
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl blur opacity-0 group-hover:opacity-50 transition-opacity duration-300"></div>
                            
                            <!-- Card Content -->
                            <div class="relative glass rounded-2xl p-6 hover:border-blue-500/50 transition-all duration-300 hover:-translate-y-1">
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
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-book-open text-white text-2xl"></i>
                                </div>

                                <!-- Test Title -->
                                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">
                                    {{ $testSet->title }}
                                </h3>

                                <!-- Categories Tags -->
                                @if($testCategories->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($testCategories as $cat)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium border"
                                              style="background-color: {{ $cat->color }}15; color: {{ $cat->color }}; border-color: {{ $cat->color }}40;">
                                            @if($cat->icon)
                                                <i class="{{ $cat->icon }} mr-1 text-xs"></i>
                                            @endif
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Test Stats -->
                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center text-sm text-gray-400">
                                        <i class="fas fa-clock mr-2 text-blue-400"></i>
                                        <span>60 minutes duration</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-400">
                                        <i class="fas fa-users mr-2 text-cyan-400"></i>
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
                                                        class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-medium hover:from-blue-700 hover:to-cyan-700 transition-all group">
                                                    <i class="fas fa-redo mr-2"></i>
                                                    Retake Test
                                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <button onclick="startTest(this, '{{ route('student.reading.onboarding.confirm-details', $testSet) }}')"
                                            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-medium hover:from-blue-700 hover:to-cyan-700 transition-all neon-blue group">
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
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-book-open text-blue-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">
                        @if($selectedCategory)
                            No Tests in {{ $selectedCategory->name }}
                        @else
                            No Tests Available
                        @endif
                    </h3>
                    <p class="text-gray-400 max-w-md mx-auto">
                        @if($selectedCategory)
                            There are no reading tests available in the {{ $selectedCategory->name }} category. Try selecting a different category or view all tests.
                        @else
                            Reading tests will be available soon. Check back later or explore other sections.
                        @endif
                    </p>
                    <div class="mt-6 space-x-4">
                        @if($selectedCategory)
                            <a href="{{ route('student.reading.index') }}" 
                               class="inline-flex items-center text-blue-400 hover:text-blue-300 font-medium">
                                <i class="fas fa-th mr-2"></i>
                                View All Tests
                            </a>
                        @endif
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex items-center text-blue-400 hover:text-blue-300 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Tips Section -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-7xl mx-auto">
            <div class="glass rounded-2xl p-8 border-blue-500/30">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-400 mr-3"></i>
                    Reading Test Tips
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Skim and scan effectively</h4>
                            <p class="text-sm text-gray-400">Read questions first, then quickly scan the passage for answers</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-cyan-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Pay attention to keywords</h4>
                            <p class="text-sm text-gray-400">Identify and underline important words in questions and passages</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-teal-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Manage your time wisely</h4>
                            <p class="text-sm text-gray-400">Allocate about 20 minutes per passage section</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-indigo-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">Don't leave blanks</h4>
                            <p class="text-sm text-gray-400">Make educated guesses if you're running out of time</p>
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
            }, 300);
        }
    </script>
    @endpush
</x-student-layout>