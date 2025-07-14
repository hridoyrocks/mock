{{-- resources/views/student/test/index.blade.php --}}
<x-student-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Simple Header -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-3xl font-semibold text-gray-900">Practice Tests</h1>
                <p class="text-gray-500 mt-2">Select a test section to begin your practice</p>
            </div>
        </div>

        <!-- Test Sections Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Listening -->
                <a href="{{ route('student.listening.index') }}" 
                   class="group bg-white rounded-2xl p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Listening</h3>
                    <p class="text-sm text-gray-500 mb-4">30 minutes • 40 questions</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        Start Practice
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <!-- Reading -->
                <a href="{{ route('student.reading.index') }}" 
                   class="group bg-white rounded-2xl p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-100 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Reading</h3>
                    <p class="text-sm text-gray-500 mb-4">60 minutes • 40 questions</p>
                    <div class="flex items-center text-indigo-600 text-sm font-medium">
                        Start Practice
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <!-- Writing -->
                <a href="{{ route('student.writing.index') }}" 
                   class="group bg-white rounded-2xl p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Writing</h3>
                    <p class="text-sm text-gray-500 mb-4">60 minutes • 2 tasks</p>
                    <div class="flex items-center text-emerald-600 text-sm font-medium">
                        Start Practice
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <!-- Speaking -->
                <a href="{{ route('student.speaking.index') }}" 
                   class="group bg-white rounded-2xl p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-100 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Speaking</h3>
                    <p class="text-sm text-gray-500 mb-4">11-14 minutes • 3 parts</p>
                    <div class="flex items-center text-purple-600 text-sm font-medium">
                        Start Practice
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Simple Info Section -->
            <div class="mt-16 bg-white rounded-2xl p-8 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl font-semibold text-gray-900 mb-1">2.5 hours</div>
                        <div class="text-sm text-gray-500">Total test time</div>
                    </div>
                    <div>
                        <div class="text-3xl font-semibold text-gray-900 mb-1">4 sections</div>
                        <div class="text-sm text-gray-500">Complete coverage</div>
                    </div>
                    <div>
                        <div class="text-3xl font-semibold text-gray-900 mb-1">Band 0-9</div>
                        <div class="text-sm text-gray-500">Scoring range</div>
                    </div>
                    <div>
                        <div class="text-3xl font-semibold text-gray-900 mb-1">Real format</div>
                        <div class="text-sm text-gray-500">Authentic questions</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>