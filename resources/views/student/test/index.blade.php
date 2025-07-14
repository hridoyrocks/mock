{{-- resources/views/student/test/index.blade.php --}}
<x-student-layout>
    <x-slot:title>Practice Tests</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 animated-gradient bg-clip-text text-transparent">
                        Practice Tests
                    </h1>
                    <p class="text-gray-300 text-xl max-w-3xl mx-auto">
                        Master all four IELTS modules with our comprehensive practice tests
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Test Sections Grid -->
    <section class="px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Listening Card -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-600 to-purple-600 rounded-2xl blur opacity-50 group-hover:opacity-100 transition-all duration-300"></div>
                    <a href="{{ route('student.listening.index') }}" 
                       class="relative block">
                        <div class="glass rounded-2xl p-8 hover:border-violet-500/50 transition-all duration-300 hover:-translate-y-2">
                            <!-- Icon -->
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform neon-purple">
                                <i class="fas fa-headphones text-white text-3xl"></i>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-2xl font-bold text-white mb-3 text-center">Listening</h3>
                            
                            <!-- Details -->
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Duration</span>
                                    <span class="text-white font-medium">30 minutes</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Questions</span>
                                    <span class="text-white font-medium">40 questions</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Format</span>
                                    <span class="text-white font-medium">4 parts</span>
                                </div>
                            </div>
                            
                            <!-- CTA -->
                            <div class="flex items-center justify-center text-violet-400 font-medium group-hover:text-violet-300">
                                Start Practice
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Reading Card -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-green-600 rounded-2xl blur opacity-50 group-hover:opacity-100 transition-all duration-300"></div>
                    <a href="{{ route('student.reading.index') }}" 
                       class="relative block">
                        <div class="glass rounded-2xl p-8 hover:border-emerald-500/50 transition-all duration-300 hover:-translate-y-2">
                            <!-- Icon -->
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform neon-blue">
                                <i class="fas fa-book-open text-white text-3xl"></i>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-2xl font-bold text-white mb-3 text-center">Reading</h3>
                            
                            <!-- Details -->
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Duration</span>
                                    <span class="text-white font-medium">60 minutes</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Questions</span>
                                    <span class="text-white font-medium">40 questions</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Format</span>
                                    <span class="text-white font-medium">3 passages</span>
                                </div>
                            </div>
                            
                            <!-- CTA -->
                            <div class="flex items-center justify-center text-emerald-400 font-medium group-hover:text-emerald-300">
                                Start Practice
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Writing Card -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-600 to-orange-600 rounded-2xl blur opacity-50 group-hover:opacity-100 transition-all duration-300"></div>
                    <a href="{{ route('student.writing.index') }}" 
                       class="relative block">
                        <div class="glass rounded-2xl p-8 hover:border-amber-500/50 transition-all duration-300 hover:-translate-y-2">
                            <!-- Icon -->
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform neon-pink">
                                <i class="fas fa-pen-fancy text-white text-3xl"></i>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-2xl font-bold text-white mb-3 text-center">Writing</h3>
                            
                            <!-- Details -->
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Duration</span>
                                    <span class="text-white font-medium">60 minutes</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Tasks</span>
                                    <span class="text-white font-medium">2 tasks</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Words</span>
                                    <span class="text-white font-medium">150 & 250</span>
                                </div>
                            </div>
                            
                            <!-- CTA -->
                            <div class="flex items-center justify-center text-amber-400 font-medium group-hover:text-amber-300">
                                Start Practice
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Speaking Card -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-600 to-pink-600 rounded-2xl blur opacity-50 group-hover:opacity-100 transition-all duration-300"></div>
                    <a href="{{ route('student.speaking.index') }}" 
                       class="relative block">
                        <div class="glass rounded-2xl p-8 hover:border-rose-500/50 transition-all duration-300 hover:-translate-y-2">
                            <!-- Icon -->
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform neon-pink">
                                <i class="fas fa-microphone text-white text-3xl"></i>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-2xl font-bold text-white mb-3 text-center">Speaking</h3>
                            
                            <!-- Details -->
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Duration</span>
                                    <span class="text-white font-medium">11-14 min</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Parts</span>
                                    <span class="text-white font-medium">3 parts</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Format</span>
                                    <span class="text-white font-medium">1-on-1</span>
                                </div>
                            </div>
                            
                            <!-- CTA -->
                            <div class="flex items-center justify-center text-rose-400 font-medium group-hover:text-rose-300">
                                Start Practice
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-student-layout>