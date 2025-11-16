<x-student-layout>
    <x-slot:title>Speaking Tests</x-slot>
    
    <div x-data="{ 
        showAll: false,
        testsPerPage: 9,
        loading: false
    }" x-init="() => { if (typeof darkMode === 'undefined') { darkMode = localStorage.getItem('darkMode') !== 'false'; } }">
    <div x-cloak>
        
        <!-- Header Section with Glass Effect -->
        <section class="relative overflow-hidden">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#C8102E]/5 via-transparent to-[#C8102E]/5 dark:from-[#C8102E]/10 dark:to-[#C8102E]/10"></div>
            
            <div class="relative px-4 sm:px-6 lg:px-8 py-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Title Section -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center shadow-lg shadow-[#C8102E]/30">
                                    <i class="fas fa-microphone text-white text-xl"></i>
                                </div>
                                <h1 class="text-3xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                    @if(isset($selectedCategory) && $selectedCategory)
                                        {{ $selectedCategory->name }}
                                    @else
                                        Speaking Practice
                                    @endif
                                </h1>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                    {{ auth()->user()->attempts()->whereHas('testSet', function($q) { $q->where('section_id', 4); })->count() }}
                                </p>
                                <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Tests Taken</p>
                            </div>
                            <div class="w-px h-12" :class="darkMode ? 'bg-white/10' : 'bg-gray-300'"></div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-[#C8102E]">
                                    {{ number_format(auth()->user()->attempts()->whereHas('testSet', function($q) { $q->where('section_id', 4); })->avg('band_score') ?? 0, 1) }}
                                </p>
                                <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Avg. Score</p>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="space-y-4">
                        <!-- Category Filter -->
                        @if(isset($categories) && $categories->count() > 0)
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Categories:</span>
                            
                            <a href="{{ route('student.speaking.index') }}" 
                               class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ !$selectedCategory ? 'bg-[#C8102E] text-white shadow-md' : '' }}"
                               :class="!{{ !$selectedCategory ? 'true' : 'false' }} && (darkMode ? 'glass text-gray-300 hover:text-white hover:bg-white/10' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                All Tests
                                <span class="ml-1.5 text-xs opacity-75">({{ $testSets->count() }})</span>
                            </a>
                            
                            @foreach($categories as $category)
                                <a href="{{ route('student.speaking.index', ['category' => $category->slug]) }}" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $selectedCategory && $selectedCategory->id == $category->id ? 'bg-[#C8102E] text-white shadow-md' : '' }}"
                                   :class="!{{ $selectedCategory && $selectedCategory->id == $category->id ? 'true' : 'false' }} && (darkMode ? 'glass text-gray-300 hover:text-white hover:bg-white/10' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} mr-1.5 text-xs"></i>
                                    @endif
                                    {{ $category->name }}
                                    <span class="ml-1.5 text-xs opacity-75">({{ $category->speaking_count ?? 0 }})</span>
                                </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Tests Grid Section -->
        <section class="px-4 sm:px-6 lg:px-8 pb-12">
            <div class="max-w-7xl mx-auto">
                @if ($testSets->count() > 0)
                    <!-- Compact Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($testSets as $index => $testSet)
                            @php
                                $userAttempts = auth()->user()->attempts()
                                    ->where('test_set_id', $testSet->id)
                                    ->where('status', 'completed')
                                    ->orderBy('attempt_number', 'desc')
                                    ->get();
                                $userCompleted = $userAttempts->count() > 0;
                                $latestAttempt = $userAttempts->first();
                                $totalAttempts = $userAttempts->count();
                                $testCategories = $testSet->categories;

                                // Get total attempts count for this test (all users)
                                $totalTestAttempts = \App\Models\StudentAttempt::where('test_set_id', $testSet->id)
                                    ->where('status', 'completed')
                                    ->count();
                            @endphp
                            
                            <div class="group relative rounded-lg border transition-all duration-300 hover:shadow-lg"
                                 x-show="showAll || {{ $index }} < testsPerPage"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 :class="darkMode ? 'glass border-white/10 hover:border-[#C8102E]/50' : 'bg-white border-gray-200 hover:border-[#C8102E]/30'">

                                @if($testSet->is_premium)
                                    <!-- Premium Badge -->
                                    <div class="absolute -top-2 -right-2 z-20">
                                        <div class="bg-gradient-to-r from-amber-500 to-yellow-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg flex items-center gap-1.5">
                                            <i class="fas fa-crown text-sm"></i>
                                            <span>Premium</span>
                                        </div>
                                    </div>

                                    <!-- Lock Overlay -->
                                    @if(!auth()->user()->hasFeature('premium_test_sets'))
                                        <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 backdrop-blur-sm rounded-lg flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="text-center p-4">
                                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center mx-auto mb-3 shadow-2xl transform group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas fa-lock text-white text-2xl"></i>
                                                </div>
                                                <p class="text-white font-bold text-base mb-1">Premium Only</p>
                                                <p class="text-white/90 text-sm">Upgrade to unlock this test</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <!-- Test Card Content -->
                                <div class="p-4" :class="{ 'opacity-75': {{ $testSet->is_premium && !auth()->user()->hasFeature('premium_test_sets') ? 'true' : 'false' }} }">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center flex-shrink-0 relative">
                                                <i class="fas fa-microphone text-white text-sm"></i>
                                                @if($testSet->is_premium && !auth()->user()->hasFeature('premium_test_sets'))
                                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-lock text-white text-[8px]"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h3 class="font-semibold text-base flex-1" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                {{ $testSet->title }}
                                            </h3>
                                        </div>
                                        
                                        @if($userCompleted)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium flex-shrink-0"
                                                  :class="darkMode ? 'glass text-green-400' : 'bg-green-50 text-green-700 border border-green-200'">
                                                <i class="fas fa-check mr-1"></i>
                                                @if($totalAttempts > 1) {{ $totalAttempts }}x @endif
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Categories (Compact) -->
                                    @if($testCategories->count() > 0)
                                    <div class="flex gap-1 mb-3">
                                        @foreach($testCategories->take(2) as $cat)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs"
                                                  :class="darkMode ? 'glass text-gray-300' : 'bg-gray-100 text-gray-600'">
                                                {{ $cat->name }}
                                            </span>
                                        @endforeach
                                        @if($testCategories->count() > 2)
                                            <span class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                                                +{{ $testCategories->count() - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                    @endif

                                    <!-- Quick Info -->
                                    <div class="flex items-center justify-between text-sm mb-3" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        <div class="flex items-center gap-3">
                                            <span class="flex items-center">
                                                <i class="fas fa-clock mr-1 text-[#C8102E]"></i>
                                                15 min
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-comments mr-1 text-[#C8102E]"></i>
                                                3 parts
                                            </span>
                                            <span class="flex items-center" title="{{ $totalTestAttempts }} total attempts">
                                                <i class="fas fa-users mr-1 text-[#C8102E]"></i>
                                                {{ $totalTestAttempts }}
                                            </span>
                                        </div>
                                        @if($userCompleted && $latestAttempt->band_score)
                                            <span class="flex items-center font-semibold text-[#C8102E]">
                                                {{ $latestAttempt->band_score }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    @if($testSet->is_premium && !auth()->user()->hasFeature('premium_test_sets'))
                                        <!-- Premium Locked - Upgrade Button -->
                                        <a href="{{ route('subscription.plans') }}"
                                           class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-md text-sm font-semibold transition-all shadow-md hover:shadow-lg transform hover:scale-105 bg-gradient-to-r from-amber-500 to-yellow-500 text-white hover:from-amber-600 hover:to-yellow-600">
                                            <i class="fas fa-crown"></i>
                                            <span>Upgrade to Unlock</span>
                                        </a>
                                    @elseif($userCompleted)
                                        <div class="flex gap-2">
                                            <a href="{{ route('student.results.show', $latestAttempt) }}"
                                               class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-all"
                                               :class="darkMode ? 'glass text-white hover:bg-white/10' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                                                <i class="fas fa-chart-bar mr-1.5"></i>
                                                Results
                                            </a>

                                            @if($latestAttempt->canRetake())
                                                <form action="{{ route('student.results.retake', $latestAttempt) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full inline-flex items-center justify-center px-3 py-2 rounded-md bg-[#C8102E] text-white text-sm font-medium hover:bg-[#A00E27] transition-all">
                                                        <i class="fas fa-redo mr-1.5"></i>
                                                        Retake
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <button onclick="startTest(this, '{{ route('student.speaking.onboarding.confirm-details', $testSet) }}')"
                                                class="w-full inline-flex items-center justify-center px-3 py-2 rounded-md bg-[#C8102E] text-white text-sm font-medium hover:bg-[#A00E27] transition-all">
                                            <i class="fas fa-play mr-1.5"></i>
                                            Start Test
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Load More Button -->
                    @if($testSets->count() > 9)
                    <div class="text-center mt-8" x-show="!showAll && {{ $testSets->count() }} > testsPerPage">
                        <button @click="showAll = true; loading = true; setTimeout(() => loading = false, 500)" 
                                class="inline-flex items-center px-6 py-3 rounded-lg font-medium transition-all"
                                :class="darkMode ? 'glass text-white hover:bg-white/10 border border-white/20' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 shadow-sm'">
                            <span x-show="!loading">
                                <i class="fas fa-plus mr-2"></i>
                                Show All Tests ({{ $testSets->count() - 9 }} more)
                            </span>
                            <span x-show="loading" x-cloak>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Loading...
                            </span>
                        </button>
                    </div>
                    
                    <!-- Collapse Button -->
                    <div class="text-center mt-4" x-show="showAll" x-cloak>
                        <button @click="showAll = false; window.scrollTo({ top: 0, behavior: 'smooth' })" 
                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all"
                                :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-gray-800'">
                            <i class="fas fa-chevron-up mr-2"></i>
                            Show Less
                        </button>
                    </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto">
                            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4"
                                 :class="darkMode ? 'glass' : 'bg-gray-100'">
                                <i class="fas fa-microphone text-3xl" :class="darkMode ? 'text-gray-400' : 'text-gray-400'"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                @if($selectedCategory)
                                    No Tests in {{ $selectedCategory->name }}
                                @else
                                    No Speaking Tests Available
                                @endif
                            </h3>
                            <p class="mb-6" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                @if($selectedCategory)
                                    Try selecting a different category or check back later.
                                @else
                                    New tests are being added regularly. Check back soon!
                                @endif
                            </p>
                            @if($selectedCategory)
                                <a href="{{ route('student.speaking.index') }}" 
                                   class="inline-flex items-center text-[#C8102E] hover:text-[#A00E27] font-medium">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    View All Tests
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
    </div>
    
    @push('scripts')
    <script>
        function startTest(button, url) {
            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Starting...
            `;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Redirect after a brief delay for better UX
            setTimeout(() => window.location.href = url, 300);
        }
    </script>
    @endpush
</x-student-layout>