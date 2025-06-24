{{-- resources/views/student/test/index.blade.php --}}
<x-layout>
    <div class="min-h-screen bg-white">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute transform rotate-45 -top-24 -right-24 w-96 h-96 bg-white rounded-full"></div>
                <div class="absolute transform rotate-45 -bottom-24 -left-24 w-96 h-96 bg-white rounded-full"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 py-20">
                <div class="text-center">
                    <h1 class="text-5xl font-extrabold text-white mb-6">
                        Master Your IELTS Skills
                    </h1>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                        Practice with authentic test materials designed by experts. Track your progress and achieve your target band score.
                    </p>
                 <!--   <div class="mt-10 flex justify-center space-x-8">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-white">2.5M+</div>
                            <div class="text-sm text-blue-200 mt-1">Practice Questions</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-white">98%</div>
                            <div class="text-sm text-blue-200 mt-1">Success Rate</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-white">Band 9</div>
                            <div class="text-sm text-blue-200 mt-1">Maximum Score</div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 -mt-16 pb-20">
            <!-- Section Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Listening Card -->
                <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 opacity-5 rounded-full -mr-16 -mt-16"></div>
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="p-3 bg-blue-50 rounded-xl">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">30 MIN</span>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Listening Test</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Sharpen your listening skills with conversations, monologues, and academic discussions across 4 comprehensive sections.
                        </p>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex space-x-6">
                                <div>
                                    <div class="text-sm text-gray-500">Questions</div>
                                    <div class="text-lg font-semibold text-gray-900">40</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Sections</div>
                                    <div class="text-lg font-semibold text-gray-900">4</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Format</div>
                                    <div class="text-lg font-semibold text-gray-900">Audio</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('student.listening.index') }}" 
                           class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 group">
                            Start Practice
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Reading Card -->
                <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500 opacity-5 rounded-full -mr-16 -mt-16"></div>
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="p-3 bg-indigo-50 rounded-xl">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">60 MIN</span>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Reading Test</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Master complex academic texts with passages from journals, books, and magazines. Practice all question types effectively.
                        </p>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex space-x-6">
                                <div>
                                    <div class="text-sm text-gray-500">Questions</div>
                                    <div class="text-lg font-semibold text-gray-900">40</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Passages</div>
                                    <div class="text-lg font-semibold text-gray-900">3</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Words</div>
                                    <div class="text-lg font-semibold text-gray-900">2.5k+</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('student.reading.index') }}" 
                           class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 group">
                            Start Practice
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Writing Card -->
                <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500 opacity-5 rounded-full -mr-16 -mt-16"></div>
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="p-3 bg-purple-50 rounded-xl">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">60 MIN</span>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Writing Test</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Develop your academic writing with graph descriptions and argumentative essays. Real-time word count and structure guidance.
                        </p>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex space-x-6">
                                <div>
                                    <div class="text-sm text-gray-500">Tasks</div>
                                    <div class="text-lg font-semibold text-gray-900">2</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Task 1</div>
                                    <div class="text-lg font-semibold text-gray-900">150w</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Task 2</div>
                                    <div class="text-lg font-semibold text-gray-900">250w</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('student.writing.index') }}" 
                           class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200 group">
                            Start Practice
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Speaking Card -->
                <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-pink-500 opacity-5 rounded-full -mr-16 -mt-16"></div>
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="p-3 bg-pink-50 rounded-xl">
                                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-pink-100 text-pink-700 text-xs font-semibold rounded-full">11-14 MIN</span>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Speaking Test</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Build confidence with structured interview practice. Record responses, review performance, and improve fluency systematically.
                        </p>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex space-x-6">
                                <div>
                                    <div class="text-sm text-gray-500">Parts</div>
                                    <div class="text-lg font-semibold text-gray-900">3</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Topics</div>
                                    <div class="text-lg font-semibold text-gray-900">50+</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Format</div>
                                    <div class="text-lg font-semibold text-gray-900">1-on-1</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('student.speaking.index') }}" 
                           class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-pink-600 to-pink-700 text-white font-semibold rounded-xl hover:from-pink-700 hover:to-pink-800 transition-all duration-200 group">
                            Start Practice
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features Section 
            <div class="mt-20 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-12">Why Choose Our Platform?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-2xl mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Authentic Materials</h3>
                        <p class="text-gray-600">Real IELTS test format with questions designed by certified examiners</p>
                    </div>
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-2xl mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Instant Feedback</h3>
                        <p class="text-gray-600">Get immediate scores and detailed explanations for every answer</p>
                    </div>
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-2xl mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Track Progress</h3>
                        <p class="text-gray-600">Monitor your improvement with detailed analytics and performance metrics</p>
                    </div>
                </div>
            </div> -->

            <!-- Back Navigation -->
            <div class="mt-16 text-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-layout>