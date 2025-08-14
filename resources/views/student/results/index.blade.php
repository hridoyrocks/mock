{{-- resources/views/student/results/index.blade.php --}}
<x-student-layout>
    <x-slot:title>My Results</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#C8102E]/10 via-transparent to-[#C8102E]/5"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <div class="glass rounded-2xl p-8" :class="darkMode ? '' : 'bg-white/90 shadow-xl border-[#C8102E]/10'">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold mb-2" :class="darkMode ? 'text-white' : 'text-gray-800'">
                                My Results 
                            </h1>
                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Track your progress and review your test performance
                            </p>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="flex gap-4">
                            <div class="glass rounded-xl px-6 py-4 text-center" :class="darkMode ? '' : 'bg-white shadow-md border border-[#C8102E]/10'">
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Total Tests</p>
                                <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-[#C8102E]'">
                                    {{ $attempts->total() + $fullTestAttempts->count() }}
                                </p>
                            </div>
                            <div class="glass rounded-xl px-6 py-4 text-center" :class="darkMode ? '' : 'bg-white shadow-md border border-[#C8102E]/10'">
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Avg Score</p>
                                <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-[#C8102E]'">
                                    @php
                                        $allScores = $attempts->where('band_score', '>', 0)->pluck('band_score')
                                            ->merge($fullTestAttempts->where('overall_band_score', '>', 0)->pluck('overall_band_score'));
                                        $avgScore = $allScores->count() > 0 ? $allScores->avg() : null;
                                    @endphp
                                    {{ $avgScore ? number_format($avgScore, 1) : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-7xl mx-auto">
            @if ($attempts->count() > 0 || $fullTestAttempts->count() > 0)
                <!-- Filters Bar -->
                <div class="glass rounded-xl p-4 mb-6" :class="darkMode ? '' : 'bg-white shadow-lg border border-[#C8102E]/10'">
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="flex flex-wrap gap-2" x-data="{ activeSection: '{{ request('section', 'all') }}' }">
                            <a href="{{ route('student.results', array_merge(request()->except('section', 'page'), ['section' => 'all'])) }}" 
                               class="px-4 py-2 rounded-lg transition-all text-sm font-medium"
                               :class="activeSection === 'all' 
                                    ? (darkMode ? 'glass bg-[#C8102E]/20 border-[#C8102E]/50 text-white' : 'bg-[#C8102E] text-white shadow-md') 
                                    : (darkMode ? 'glass text-gray-400 hover:text-white hover:border-gray-500/50' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                <i class="fas fa-filter mr-2"></i>All Sections
                            </a>
                            
                            <a href="{{ route('student.results', array_merge(request()->except('section', 'page'), ['section' => 'listening'])) }}" 
                               class="px-4 py-2 rounded-lg transition-all text-sm font-medium"
                               :class="activeSection === 'listening' 
                                    ? (darkMode ? 'glass bg-[#C8102E]/20 border-[#C8102E]/50 text-white' : 'bg-[#C8102E] text-white shadow-md') 
                                    : (darkMode ? 'glass text-gray-400 hover:text-white hover:border-gray-500/50' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                <i class="fas fa-headphones mr-1"></i>Listening
                            </a>
                            
                            <a href="{{ route('student.results', array_merge(request()->except('section', 'page'), ['section' => 'reading'])) }}" 
                               class="px-4 py-2 rounded-lg transition-all text-sm font-medium"
                               :class="activeSection === 'reading' 
                                    ? (darkMode ? 'glass bg-[#C8102E]/20 border-[#C8102E]/50 text-white' : 'bg-[#C8102E] text-white shadow-md') 
                                    : (darkMode ? 'glass text-gray-400 hover:text-white hover:border-gray-500/50' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                <i class="fas fa-book-open mr-1"></i>Reading
                            </a>
                            
                            <a href="{{ route('student.results', array_merge(request()->except('section', 'page'), ['section' => 'writing'])) }}" 
                               class="px-4 py-2 rounded-lg transition-all text-sm font-medium"
                               :class="activeSection === 'writing' 
                                    ? (darkMode ? 'glass bg-[#C8102E]/20 border-[#C8102E]/50 text-white' : 'bg-[#C8102E] text-white shadow-md') 
                                    : (darkMode ? 'glass text-gray-400 hover:text-white hover:border-gray-500/50' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                <i class="fas fa-pen-fancy mr-1"></i>Writing
                            </a>
                            
                            <a href="{{ route('student.results', array_merge(request()->except('section', 'page'), ['section' => 'speaking'])) }}" 
                               class="px-4 py-2 rounded-lg transition-all text-sm font-medium"
                               :class="activeSection === 'speaking' 
                                    ? (darkMode ? 'glass bg-[#C8102E]/20 border-[#C8102E]/50 text-white' : 'bg-[#C8102E] text-white shadow-md') 
                                    : (darkMode ? 'glass text-gray-400 hover:text-white hover:border-gray-500/50' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                <i class="fas fa-microphone mr-1"></i>Speaking
                            </a>
                            
                            <a href="{{ route('student.results', array_merge(request()->except('section', 'page'), ['section' => 'full-test'])) }}" 
                               class="px-4 py-2 rounded-lg transition-all text-sm font-medium"
                               :class="activeSection === 'full-test' 
                                    ? (darkMode ? 'glass bg-[#C8102E]/20 border-[#C8102E]/50 text-white' : 'bg-[#C8102E] text-white shadow-md') 
                                    : (darkMode ? 'glass text-gray-400 hover:text-white hover:border-gray-500/50' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')">
                                <i class="fas fa-file-alt mr-1"></i>Full Tests
                            </a>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <form method="GET" action="{{ route('student.results') }}" class="flex items-center gap-2">
                                @if(request('section'))
                                    <input type="hidden" name="section" value="{{ request('section') }}">
                                @endif
                                <select name="period" onchange="this.form.submit()" 
                                        class="glass bg-transparent text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#C8102E]"
                                        :class="darkMode ? 'text-white' : 'bg-white text-gray-700 border border-gray-300'">
                                    <option value="all" {{ request('period', 'all') === 'all' ? 'selected' : '' }}>All time</option>
                                    <option value="30days" {{ request('period') === '30days' ? 'selected' : '' }}>Last 30 days</option>
                                    <option value="3months" {{ request('period') === '3months' ? 'selected' : '' }}>Last 3 months</option>
                                    <option value="6months" {{ request('period') === '6months' ? 'selected' : '' }}>Last 6 months</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Results List -->
                <div class="space-y-4">
                    @php
                        // Combine and sort all attempts based on current filter
                        $allAttempts = collect();
                        
                        // Add regular test attempts if not filtering for full tests only
                        if (request('section') !== 'full-test') {
                            foreach ($attempts as $attempt) {
                                $allAttempts->push([
                                    'type' => 'section',
                                    'data' => $attempt,
                                    'created_at' => $attempt->created_at
                                ]);
                            }
                        }
                        
                        // Add full test attempts if showing all or full tests
                        if (in_array(request('section'), ['all', 'full-test', null])) {
                            foreach ($fullTestAttempts as $fullTest) {
                                $allAttempts->push([
                                    'type' => 'full',
                                    'data' => $fullTest,
                                    'created_at' => $fullTest->created_at
                                ]);
                            }
                        }
                        
                        // Sort by date
                        $allAttempts = $allAttempts->sortByDesc('created_at');
                    @endphp
                    
                    @forelse ($allAttempts as $attemptItem)
                        @if($attemptItem['type'] === 'full')
                            @php $fullTest = $attemptItem['data']; @endphp
                            <!-- Full Test Result Card -->
                            <a href="{{ route('student.full-test.results', $fullTest) }}" class="block group">
                                <div class="glass rounded-xl p-6 transition-all duration-300 hover:-translate-y-1"
                                     :class="darkMode 
                                        ? 'hover:border-[#C8102E]/30' 
                                        : 'bg-white shadow-lg hover:shadow-xl border border-gray-200 hover:border-[#C8102E]/30'">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <!-- Full Test Icon -->
                                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                                                <i class="fas fa-file-alt text-white text-xl"></i>
                                            </div>
                                            
                                            <!-- Test Details -->
                                            <div>
                                                <h3 class="font-semibold text-lg transition-colors"
                                                    :class="darkMode ? 'text-white group-hover:text-[#C8102E]' : 'text-gray-800 group-hover:text-[#C8102E]'">
                                                    {{ $fullTest->fullTest->title }}
                                                    <span class="ml-2 text-xs px-2 py-1 rounded-full bg-[#C8102E]/10 text-[#C8102E] font-medium">Full Test</span>
                                                </h3>
                                                <div class="flex items-center gap-4 mt-1">
                                                    <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        <i class="far fa-calendar mr-1"></i>
                                                        {{ $fullTest->created_at->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        <i class="far fa-clock mr-1"></i>
                                                        {{ $fullTest->created_at->format('g:i A') }}
                                                    </span>
                                                    <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        <i class="fas fa-stopwatch mr-1"></i>
                                                        {{ $fullTest->total_time_minutes }} min
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Score & Status -->
                                        <div class="flex items-center gap-6">
                                            <!-- Status Badge -->
                                            <div>
                                                @if ($fullTest->status === 'completed')
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                          :class="darkMode 
                                                            ? 'glass text-green-400 border-green-500/30' 
                                                            : 'bg-green-100 text-green-700 border border-green-300'">
                                                        <i class="fas fa-check-circle mr-1"></i>Completed
                                                    </span>
                                                @elseif ($fullTest->status === 'in_progress')
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                          :class="darkMode 
                                                            ? 'glass text-yellow-400 border-yellow-500/30' 
                                                            : 'bg-yellow-100 text-yellow-700 border border-yellow-300'">
                                                        <i class="fas fa-clock mr-1"></i>In Progress
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                          :class="darkMode 
                                                            ? 'glass text-red-400 border-red-500/30' 
                                                            : 'bg-red-100 text-red-700 border border-red-300'">
                                                        <i class="fas fa-times-circle mr-1"></i>Abandoned
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Band Score -->
                                            <div class="text-right">
                                                @if ($fullTest->overall_band_score)
                                                    <p class="text-3xl font-bold text-[#C8102E]">{{ number_format($fullTest->overall_band_score, 1) }}</p>
                                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Overall Band</p>
                                                @else
                                                    <div class="px-4 py-2 rounded-lg"
                                                         :class="darkMode ? 'glass' : 'bg-gray-100'">
                                                        <p class="text-sm text-yellow-600 font-medium">Pending</p>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- View Arrow -->
                                            <div class="transition-colors" :class="darkMode ? 'text-gray-400 group-hover:text-[#C8102E]' : 'text-gray-400 group-hover:text-[#C8102E]'">
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @else
                            @php 
                                $attempt = $attemptItem['data']; 
                                $sectionIcons = [
                                    'listening' => 'fa-headphones',
                                    'reading' => 'fa-book-open',
                                    'writing' => 'fa-pen-fancy',
                                    'speaking' => 'fa-microphone'
                                ];
                                $sectionColors = [
                                    'listening' => 'from-violet-500 to-purple-500',
                                    'reading' => 'from-blue-500 to-cyan-500',
                                    'writing' => 'from-green-500 to-emerald-500',
                                    'speaking' => 'from-rose-500 to-pink-500'
                                ];
                                $icon = $sectionIcons[$attempt->testSet->section->name] ?? 'fa-question';
                                $gradient = $sectionColors[$attempt->testSet->section->name] ?? 'from-gray-500 to-gray-600';
                            @endphp
                            
                            <!-- Section Test Result Card -->
                            <a href="{{ route('student.results.show', $attempt) }}" class="block group">
                                <div class="glass rounded-xl p-6 transition-all duration-300 hover:-translate-y-1"
                                     :class="darkMode 
                                        ? 'hover:border-[#C8102E]/30' 
                                        : 'bg-white shadow-lg hover:shadow-xl border border-gray-200 hover:border-[#C8102E]/30'">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <!-- Section Icon -->
                                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                                                <i class="fas {{ $icon }} text-white text-xl"></i>
                                            </div>
                                            
                                            <!-- Test Details -->
                                            <div>
                                                <h3 class="font-semibold text-lg transition-colors"
                                                    :class="darkMode ? 'text-white group-hover:text-[#C8102E]' : 'text-gray-800 group-hover:text-[#C8102E]'">
                                                    {{ $attempt->testSet->title }}
                                                    @if($attempt->is_retake)
                                                        <span class="text-sm text-[#C8102E] ml-2">(Attempt {{ $attempt->attempt_number }})</span>
                                                    @endif
                                                </h3>
                                                <div class="flex items-center gap-4 mt-1">
                                                    <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        <i class="far fa-calendar mr-1"></i>
                                                        {{ $attempt->created_at->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        <i class="far fa-clock mr-1"></i>
                                                        {{ $attempt->created_at->format('g:i A') }}
                                                    </span>
                                                    <span class="text-sm capitalize" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ $attempt->testSet->section->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Score & Status -->
                                        <div class="flex items-center gap-6">
                                            <!-- Completion Rate -->
                                            <div class="hidden sm:block">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-24 h-2 rounded-full overflow-hidden"
                                                         :class="darkMode ? 'bg-white/10' : 'bg-gray-200'">
                                                        <div class="h-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full transition-all duration-500"
                                                             style="width: {{ $attempt->completion_rate ?? 100 }}%"></div>
                                                    </div>
                                                    <span class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ $attempt->completion_rate ?? 100 }}%</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            <div>
                                                @if ($attempt->status === 'completed')
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                          :class="darkMode 
                                                            ? 'glass text-green-400 border-green-500/30' 
                                                            : 'bg-green-100 text-green-700 border border-green-300'">
                                                        <i class="fas fa-check-circle mr-1"></i>Completed
                                                    </span>
                                                @elseif ($attempt->status === 'in_progress')
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                          :class="darkMode 
                                                            ? 'glass text-yellow-400 border-yellow-500/30' 
                                                            : 'bg-yellow-100 text-yellow-700 border border-yellow-300'">
                                                        <i class="fas fa-clock mr-1"></i>In Progress
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                          :class="darkMode 
                                                            ? 'glass text-red-400 border-red-500/30' 
                                                            : 'bg-red-100 text-red-700 border border-red-300'">
                                                        <i class="fas fa-times-circle mr-1"></i>Abandoned
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Band Score -->
                                            <div class="text-right">
                                                @if ($attempt->band_score)
                                                    <p class="text-3xl font-bold text-[#C8102E]">{{ number_format($attempt->band_score, 1) }}</p>
                                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Band Score</p>
                                                @else
                                                    <div class="px-4 py-2 rounded-lg"
                                                         :class="darkMode ? 'glass' : 'bg-gray-100'">
                                                        <p class="text-sm text-yellow-600 font-medium">Pending</p>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- View Arrow -->
                                            <div class="transition-colors" :class="darkMode ? 'text-gray-400 group-hover:text-[#C8102E]' : 'text-gray-400 group-hover:text-[#C8102E]'">
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @empty
                        <!-- No results for current filter -->
                        <div class="glass rounded-2xl p-12" :class="darkMode ? '' : 'bg-white shadow-xl'">
                            <div class="text-center">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#C8102E]/20 to-[#A00E27]/20 flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-search text-4xl text-[#C8102E]"></i>
                                </div>
                                <h3 class="text-2xl font-bold mb-3" :class="darkMode ? 'text-white' : 'text-gray-800'">No Results Found</h3>
                                <p class="mb-8 max-w-md mx-auto" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                    No test results found for the selected filters. Try adjusting your search criteria.
                                </p>
                                <a href="{{ route('student.results') }}" 
                                   class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium hover:from-[#A00E27] hover:to-[#8A0C20] transition-all shadow-lg">
                                    <i class="fas fa-filter-circle-xmark mr-2"></i>
                                    Clear Filters
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination for section tests -->
                @if($attempts->count() > 0 && request('section') !== 'full-test')
                    <div class="mt-8 flex justify-center">
                        {{ $attempts->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="glass rounded-2xl p-12" :class="darkMode ? '' : 'bg-white shadow-xl'">
                    <div class="text-center">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#C8102E]/20 to-[#A00E27]/20 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-clipboard-list text-4xl text-[#C8102E]"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3" :class="darkMode ? 'text-white' : 'text-gray-800'">No Test Results Yet</h3>
                        <p class="mb-8 max-w-md mx-auto" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                            Start taking tests to see your results and track your progress. Each test will help you improve your IELTS skills!
                        </p>
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium hover:from-[#A00E27] hover:to-[#8A0C20] transition-all shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>
                            Start Your First Test
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('styles')
    <style>
        /* Custom pagination styling for light/dark theme */
        nav[role="navigation"] .flex.justify-between {
            @apply rounded-xl p-4;
        }
        
        .light-mode nav[role="navigation"] .flex.justify-between {
            @apply bg-white shadow-md border border-gray-200;
        }
        
        .dark nav[role="navigation"] .flex.justify-between {
            @apply glass;
        }
        
        nav[role="navigation"] a, nav[role="navigation"] span {
            @apply transition-colors;
        }
        
        .light-mode nav[role="navigation"] a {
            @apply text-gray-700 hover:text-[#C8102E];
        }
        
        .light-mode nav[role="navigation"] span {
            @apply text-gray-600;
        }
        
        .dark nav[role="navigation"] a {
            @apply text-white;
        }
        
        .dark nav[role="navigation"] .text-gray-500 {
            @apply text-gray-400;
        }
        
        .light-mode nav[role="navigation"] .bg-white {
            @apply bg-[#C8102E] text-white;
        }
        
        .dark nav[role="navigation"] .bg-white {
            @apply bg-transparent glass;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
        // Update active section based on URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section') || 'all';
            
            // Update Alpine.js data
            if (window.Alpine) {
                Alpine.store('activeSection', section);
            }
        });
    </script>
    @endpush
</x-student-layout>
