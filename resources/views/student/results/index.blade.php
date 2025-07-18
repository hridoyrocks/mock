{{-- resources/views/student/results/index.blade.php --}}
<x-student-layout>
    <x-slot:title>My Results</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <div class="glass rounded-2xl p-8">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                                My Results 
                            </h1>
                            
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="flex gap-4">
                            <div class="glass rounded-xl px-6 py-4 text-center">
                                <p class="text-gray-400 text-sm">Total Tests</p>
                                <p class="text-2xl font-bold text-white">{{ $attempts->total() }}</p>
                            </div>
                            <div class="glass rounded-xl px-6 py-4 text-center">
                                <p class="text-gray-400 text-sm">Avg Score</p>
                                <p class="text-2xl font-bold text-white">
                                    {{ $attempts->where('band_score', '>', 0)->avg('band_score') ? number_format($attempts->where('band_score', '>', 0)->avg('band_score'), 1) : 'N/A' }}
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
            @if ($attempts->count() > 0)
                <!-- Filters Bar -->
                <div class="glass rounded-xl p-4 mb-6">
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="flex gap-2">
                            <button class="glass px-4 py-2 rounded-lg text-white hover:border-purple-500/50 transition-all text-sm">
                                <i class="fas fa-filter mr-2"></i>All Sections
                            </button>
                            <button class="glass px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all text-sm">
                                Listening
                            </button>
                            <button class="glass px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all text-sm">
                                Reading
                            </button>
                            <button class="glass px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all text-sm">
                                Writing
                            </button>
                            <button class="glass px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:border-gray-500/50 transition-all text-sm">
                                Speaking
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <select class="glass bg-transparent text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option>Last 30 days</option>
                                <option>Last 3 months</option>
                                <option>Last 6 months</option>
                                <option>All time</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results List -->
                <div class="space-y-4">
                    @foreach ($attempts as $attempt)
                        @php
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
                        
                        <a href="{{ route('student.results.show', $attempt) }}" 
                           class="block group">
                            <div class="glass rounded-xl p-6 hover:border-purple-500/30 transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Section Icon -->
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fas {{ $icon }} text-white text-xl"></i>
                                        </div>
                                        
                                        <!-- Test Details -->
                                        <div>
                                            <h3 class="text-white font-semibold text-lg group-hover:text-purple-400 transition-colors">
                                                {{ $attempt->testSet->title }}
                                                @if($attempt->is_retake)
                                                    <span class="text-sm text-purple-400 ml-2">(Attempt {{ $attempt->attempt_number }})</span>
                                                @endif
                                            </h3>
                                            <div class="flex items-center gap-4 mt-1">
                                                <span class="text-sm text-gray-400">
                                                    <i class="far fa-calendar mr-1"></i>
                                                    {{ $attempt->created_at->format('M d, Y') }}
                                                </span>
                                                <span class="text-sm text-gray-400">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ $attempt->created_at->format('g:i A') }}
                                                </span>
                                                <span class="text-sm text-gray-400 capitalize">
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
                                                <div class="w-24 h-2 bg-white/10 rounded-full overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-500"
                                                         style="width: {{ $attempt->completion_rate ?? 100 }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-400">{{ $attempt->completion_rate ?? 100 }}%</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        <div>
                                            @if ($attempt->status === 'completed')
                                                <span class="glass px-3 py-1 rounded-full text-xs text-green-400 border-green-500/30">
                                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                                </span>
                                            @elseif ($attempt->status === 'in_progress')
                                                <span class="glass px-3 py-1 rounded-full text-xs text-yellow-400 border-yellow-500/30">
                                                    <i class="fas fa-clock mr-1"></i>In Progress
                                                </span>
                                            @else
                                                <span class="glass px-3 py-1 rounded-full text-xs text-red-400 border-red-500/30">
                                                    <i class="fas fa-times-circle mr-1"></i>Abandoned
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Band Score -->
                                        <div class="text-right">
                                            @if ($attempt->band_score)
                                                <p class="text-3xl font-bold text-white">{{ number_format($attempt->band_score, 1) }}</p>
                                                <p class="text-xs text-gray-400">Band Score</p>
                                            @else
                                                <div class="glass px-4 py-2 rounded-lg">
                                                    <p class="text-sm text-yellow-400">Pending</p>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- View Arrow -->
                                        <div class="text-gray-400 group-hover:text-purple-400 transition-colors">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $attempts->links('pagination::tailwind') }}
                </div>
            @else
                <!-- Empty State -->
                <div class="glass rounded-2xl p-12">
                    <div class="text-center">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-clipboard-list text-4xl text-purple-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">No Test Results Yet</h3>
                        <p class="text-gray-400 mb-8 max-w-md mx-auto">
                            Start taking tests to see your results and track your progress. Each test will help you improve your IELTS skills!
                        </p>
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all">
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
        /* Custom pagination styling */
        nav[role="navigation"] .flex.justify-between {
            @apply glass rounded-xl p-4;
        }
        
        nav[role="navigation"] a, nav[role="navigation"] span {
            @apply text-white;
        }
        
        nav[role="navigation"] .text-gray-500 {
            @apply text-gray-400;
        }
        
        nav[role="navigation"] .bg-white {
            @apply bg-transparent glass;
        }
        
        nav[role="navigation"] .text-gray-700 {
            @apply text-white;
        }
    </style>
    @endpush
</x-student-layout>