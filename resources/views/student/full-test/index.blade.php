{{-- resources/views/student/full-test/index.blade.php --}}
<x-student-layout>
    <x-slot:title>Full Tests</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 via-transparent to-purple-600/20"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 animated-gradient bg-clip-text text-transparent">
                        Full IELTS Tests
                    </h1>
                    <p class="text-gray-300 text-xl max-w-3xl mx-auto">
                        Complete all four modules in one sitting - just like the real IELTS exam
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Full Tests Grid -->
    <section class="px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">
            @if($fullTests->isEmpty())
                <div class="glass rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-800/50 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clipboard-list text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No Full Tests Available</h3>
                    <p class="text-gray-400 mb-6">Full tests will be available soon. Check back later!</p>
                    <a href="{{ route('student.test.index') }}" class="btn-primary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Practice Individual Sections
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($fullTests as $fullTest)
                        @php
                            $userAttempts = $attempts->get($fullTest->id) ?? collect();
                            $completedAttempts = $userAttempts->where('status', 'completed');
                            $inProgressAttempt = $userAttempts->where('status', 'in_progress')->first();
                        @endphp
                        
                        <div class="group relative">
                            <!-- Premium Badge -->
                            @if($fullTest->is_premium)
                                <div class="absolute -top-3 -right-3 z-10">
                                    <div class="bg-gradient-to-r from-amber-500 to-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-crown mr-1"></i>
                                        Premium
                                    </div>
                                </div>
                            @endif
                            
                            <div class="glass rounded-2xl p-8 hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-2 h-full flex flex-col">
                                <!-- Icon -->
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform neon-purple">
                                    <i class="fas fa-file-alt text-white text-3xl"></i>
                                </div>
                                
                                <!-- Content -->
                                <h3 class="text-2xl font-bold text-white mb-3 text-center">{{ $fullTest->title }}</h3>
                                
                                @if($fullTest->description)
                                    <p class="text-gray-400 text-sm text-center mb-4">{{ $fullTest->description }}</p>
                                @endif
                                
                                <!-- Status -->
                                @if($completedAttempts->count() > 0)
                                    <div class="text-center mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $completedAttempts->count() }} {{ Str::plural('Attempt', $completedAttempts->count()) }} Completed
                                        </span>
                                    </div>
                                @elseif($inProgressAttempt)
                                    <div class="text-center mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                            <i class="fas fa-clock mr-1"></i>
                                            In Progress
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Test Details -->
                                <div class="space-y-2 mb-6 flex-grow">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-400">Total Duration</span>
                                        <span class="text-white font-medium">~3 hours</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-400">Sections</span>
                                        <span class="text-white font-medium">L, R, W, S</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-400">Questions</span>
                                        <span class="text-white font-medium">~80-85</span>
                                    </div>
                                </div>
                                
                                <!-- CTA -->
                                @if($fullTest->is_premium && !auth()->user()->hasFeature('premium_full_tests'))
                                    <a href="{{ route('subscription.plans') }}" 
                                       class="btn-secondary text-center">
                                        <i class="fas fa-lock mr-2"></i>
                                        Upgrade to Premium
                                    </a>
                                @else
                                    <a href="{{ route('student.full-test.onboarding', $fullTest) }}" 
                                       class="btn-primary text-center">
                                        @if($inProgressAttempt)
                                            <i class="fas fa-play mr-2"></i>
                                            Continue Test
                                        @else
                                            <i class="fas fa-play mr-2"></i>
                                            Start Full Test
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Info Section -->
    <section class="px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="glass rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-white mb-6">About Full Tests</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-3">
                            <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                            What's Included
                        </h3>
                        <ul class="space-y-2 text-gray-400">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-400 mt-1 mr-2"></i>
                                <span>Complete IELTS test experience with all 4 sections</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-400 mt-1 mr-2"></i>
                                <span>Timed sections matching real exam conditions</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-400 mt-1 mr-2"></i>
                                <span>Overall band score calculation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-400 mt-1 mr-2"></i>
                                <span>Detailed performance analysis for each section</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-3">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                            Test Tips
                        </h3>
                        <ul class="space-y-2 text-gray-400">
                            <li class="flex items-start">
                                <i class="fas fa-arrow-right text-purple-400 mt-1 mr-2"></i>
                                <span>Set aside 3 uninterrupted hours</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-arrow-right text-purple-400 mt-1 mr-2"></i>
                                <span>Use headphones for the listening section</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-arrow-right text-purple-400 mt-1 mr-2"></i>
                                <span>Have pen and paper ready for notes</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-arrow-right text-purple-400 mt-1 mr-2"></i>
                                <span>Test your microphone before starting</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-student-layout>
