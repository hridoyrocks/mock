{{-- resources/views/student/dashboard.blade.php --}}
<x-layout>
    <x-slot:title>Student Dashboard - IELTS Mock Test</x-slot>
    
    <div class="min-h-screen bg-gray-50">
        <!-- Student Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-gray-600">Ready to practice your IELTS skills today?</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($stats['average_band_score'])
                            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                Avg Score: {{ number_format($stats['average_band_score'], 1) }}
                            </div>
                        @endif
                        <div class="text-sm text-gray-500">
                            {{ now()->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Tests</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_attempts'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['completed_attempts'] }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">In Progress</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['in_progress_attempts'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Average Score</p>
                            <p class="text-3xl font-bold text-gray-900">
                                @if($stats['average_band_score'])
                                    {{ number_format($stats['average_band_score'], 1) }}
                                @else
                                    --
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Sections and Recent Results -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Take a Test -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Practice Tests</h3>
                    <p class="text-sm text-gray-600 mb-6">Select any section to start practicing:</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('student.listening.index') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 text-center hover:from-blue-100 hover:to-blue-200 transition duration-200">
                            <div class="text-3xl mb-2">🎧</div>
                            <span class="font-medium text-gray-800">Listening</span>
                            <div class="text-xs text-gray-600 mt-1">
                                {{ $testSections->where('name', 'listening')->first()?->testSets->count() ?? 0 }} Tests
                            </div>
                        </a>
                        
                        <a href="{{ route('student.reading.index') }}" class="group bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4 text-center hover:from-green-100 hover:to-green-200 transition duration-200">
                            <div class="text-3xl mb-2">📖</div>
                            <span class="font-medium text-gray-800">Reading</span>
                            <div class="text-xs text-gray-600 mt-1">
                                {{ $testSections->where('name', 'reading')->first()?->testSets->count() ?? 0 }} Tests
                            </div>
                        </a>
                        
                        <a href="{{ route('student.writing.index') }}" class="group bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 text-center hover:from-yellow-100 hover:to-yellow-200 transition duration-200">
                            <div class="text-3xl mb-2">✍️</div>
                            <span class="font-medium text-gray-800">Writing</span>
                            <div class="text-xs text-gray-600 mt-1">
                                {{ $testSections->where('name', 'writing')->first()?->testSets->count() ?? 0 }} Tests
                            </div>
                        </a>
                        
                        <a href="{{ route('student.speaking.index') }}" class="group bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 text-center hover:from-purple-100 hover:to-purple-200 transition duration-200">
                            <div class="text-3xl mb-2">🎤</div>
                            <span class="font-medium text-gray-800">Speaking</span>
                            <div class="text-xs text-gray-600 mt-1">
                                {{ $testSections->where('name', 'speaking')->first()?->testSets->count() ?? 0 }} Tests
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Results -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Test Results</h3>
                    
                    @if($recentAttempts->isNotEmpty())
                        <div class="space-y-4 max-h-64 overflow-y-auto">
                            @foreach($recentAttempts as $attempt)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                                            @switch($attempt->testSet->section->name)
                                                @case('listening') bg-blue-100 @break
                                                @case('reading') bg-green-100 @break
                                                @case('writing') bg-yellow-100 @break
                                                @case('speaking') bg-purple-100 @break
                                            @endswitch">
                                            @switch($attempt->testSet->section->name)
                                                @case('listening') 🎧 @break
                                                @case('reading') 📖 @break
                                                @case('writing') ✍️ @break
                                                @case('speaking') 🎤 @break
                                            @endswitch
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $attempt->testSet->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $attempt->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($attempt->band_score)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                Band: {{ $attempt->band_score }}
                                            </span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ ucfirst($attempt->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('student.results') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all results →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-sm">You haven't taken any tests yet.</p>
                            <p class="text-xs text-gray-400 mt-1">Start with any section above!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section Performance -->
            @if($sectionPerformance->where('attempts_count', '>', 0)->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Section Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($sectionPerformance as $performance)
                            @if($performance['attempts_count'] > 0)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-4xl mb-2">
                                        @switch($performance['name'])
                                            @case('listening') 🎧 @break
                                            @case('reading') 📖 @break
                                            @case('writing') ✍️ @break
                                            @case('speaking') 🎤 @break
                                        @endswitch
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 capitalize">{{ $performance['name'] }}</h4>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-sm text-gray-600">{{ $performance['attempts_count'] }} attempts</p>
                                        <p class="text-sm font-medium text-blue-600">
                                            Avg: {{ number_format($performance['average_score'], 1) }}
                                        </p>
                                        <p class="text-sm text-green-600">
                                            Best: {{ number_format($performance['best_score'], 1) }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('student.results') }}" class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-blue-600">View All Results</span>
                        </div>
                    </a>

                    <a href="{{ route('student.index') }}" class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-600">Browse Tests</span>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">Edit Profile</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>