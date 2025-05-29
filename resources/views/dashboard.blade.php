<x-layout>
    <x-slot:title>Dashboard - IELTS Mock Test</x-slot>
    
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->is_admin)
                <!-- Admin Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Welcome to IELTS Mock Test Platform - Admin Panel</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Test Management</h4>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('admin.sections.index') }}" class="text-blue-600 hover:underline">
                                            Manage Test Sections
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.test-sets.index') }}" class="text-blue-600 hover:underline">
                                            Manage Test Sets
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.questions.index') }}" class="text-blue-600 hover:underline">
                                            Manage Questions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Student Management</h4>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('admin.attempts.index') }}" class="text-green-600 hover:underline">
                                            View Student Attempts
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.attempts.index', ['status' => 'completed']) }}" class="text-green-600 hover:underline">
                                            Evaluate Completed Tests
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Student Dashboard -->
                <div class="space-y-6">
                    <!-- Welcome Section -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-red-600 to-red-600 p-6 text-white">
                            <div class="max-w-7xl mx-auto">
                                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                                <p class="text-white-100 text-lg">Ready to ace your IELTS exam? Your journey to success starts here.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    @php
                        $totalAttempts = auth()->user()->attempts()->count();
                        $completedTests = auth()->user()->attempts()->where('status', 'completed')->count();
                        $averageScore = auth()->user()->attempts()->whereNotNull('band_score')->avg('band_score');
                        $lastTestDate = auth()->user()->attempts()->latest()->first()?->created_at;
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Total Tests Card -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Total Tests</p>
                                    <p class="text-2xl font-semibold text-gray-800">{{ $totalAttempts }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Tests Card -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Completed</p>
                                    <p class="text-2xl font-semibold text-gray-800">{{ $completedTests }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Average Score Card -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Avg. Score</p>
                                    <p class="text-2xl font-semibold text-gray-800">
                                        {{ $averageScore ? number_format($averageScore, 1) : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Last Activity Card -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-yellow-100 rounded-full">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Last Activity</p>
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $lastTestDate ? $lastTestDate->diffForHumans() : 'No activity' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Test Sections -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Start a New Test</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Listening -->
                                    <a href="{{ route('student.listening.index') }}" class="group block p-6 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <span class="text-4xl">🎧</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 mb-1">Listening</h4>
                                            <p class="text-sm text-gray-600">30 min • 40 questions</p>
                                            <p class="text-sm text-blue-600 mt-2 group-hover:underline">Start Test →</p>
                                        </div>
                                    </a>

                                    <!-- Reading -->
                                    <a href="{{ route('student.reading.index') }}" class="group block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <span class="text-4xl">📖</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 mb-1">Reading</h4>
                                            <p class="text-sm text-gray-600">60 min • 40 questions</p>
                                            <p class="text-sm text-green-600 mt-2 group-hover:underline">Start Test →</p>
                                        </div>
                                    </a>

                                    <!-- Writing -->
                                    <a href="{{ route('student.writing.index') }}" class="group block p-6 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <span class="text-4xl">✍️</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 mb-1">Writing</h4>
                                            <p class="text-sm text-gray-600">60 min • 2 tasks</p>
                                            <p class="text-sm text-purple-600 mt-2 group-hover:underline">Start Test →</p>
                                        </div>
                                    </a>

                                    <!-- Speaking -->
                                    <a href="{{ route('student.speaking.index') }}" class="group block p-6 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <span class="text-4xl">🎤</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 mb-1">Speaking</h4>
                                            <p class="text-sm text-gray-600">15 min • 3 parts</p>
                                            <p class="text-sm text-red-600 mt-2 group-hover:underline">Start Test →</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Recent Attempts -->
                            <div class="bg-white rounded-lg shadow p-6 mt-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Recent Test History</h3>
                                    <a href="{{ route('student.results') }}" class="text-sm text-blue-600 hover:underline">View All →</a>
                                </div>
                                
                                @php
                                    $recentAttempts = auth()->user()->attempts()->with('testSet.section')->latest()->take(5)->get();
                                @endphp
                                
                                @if($recentAttempts->isNotEmpty())
                                    <div class="space-y-3">
                                        @foreach($recentAttempts as $attempt)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $attempt->testSet->title }}</p>
                                                    <p class="text-sm text-gray-600">
                                                        {{ ucfirst($attempt->testSet->section->name) }} • {{ $attempt->created_at->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    @if($attempt->band_score)
                                                        <p class="text-sm text-gray-600">Band Score</p>
                                                        <p class="text-lg font-bold text-gray-800">{{ $attempt->band_score }}</p>
                                                    @else
                                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Pending</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-8">No tests taken yet. Start practicing now!</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right Sidebar -->
                        <div class="space-y-6">
                            <!-- Progress Overview -->
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Progress</h3>
                                
                                @php
                                    $sections = ['listening', 'reading', 'writing', 'speaking'];
                                    $sectionColors = [
                                        'listening' => 'bg-blue-600',
                                        'reading' => 'bg-green-600',
                                        'writing' => 'bg-purple-600',
                                        'speaking' => 'bg-red-600'
                                    ];
                                @endphp
                                
                                <div class="space-y-3">
                                    @foreach($sections as $section)
                                        @php
                                            $sectionScore = auth()->user()->attempts()
                                                ->whereHas('testSet.section', function($q) use ($section) {
                                                    $q->where('name', $section);
                                                })
                                                ->where('status', 'completed')
                                                ->whereNotNull('band_score')
                                                ->avg('band_score');
                                        @endphp
                                        <div>
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-700 capitalize">{{ $section }}</span>
                                                <span class="text-gray-600">{{ $sectionScore ? number_format($sectionScore, 1) : 'N/A' }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="{{ $sectionColors[$section] }} h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $sectionScore ? ($sectionScore / 9) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Study Tips -->
                            <div class="bg-yellow-50 rounded-lg shadow p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                                    </svg>
                                    Daily Tip
                                </h3>
                                <p class="text-gray-700 text-sm">
                                    Focus on your weakest section first. Consistent practice in challenging areas will improve your overall band score significantly.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>