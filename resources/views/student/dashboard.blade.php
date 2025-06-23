{{-- resources/views/student/dashboard.blade.php --}}
<x-layout>
    <x-slot:title>Student Dashboard - IELTS Practice Platform</x-slot>
    
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section with Gradient Background -->
        <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-xl text-blue-100">Ready to ace your IELTS exam? Let's continue your journey.</p>
                    </div>
                    <div class="mt-6 md:mt-0 flex flex-col items-end">
                        <!-- Subscription Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                @if(auth()->user()->subscription_status === 'pro') bg-purple-500 text-white
                                @elseif(auth()->user()->subscription_status === 'premium') bg-blue-500 text-white
                                @else bg-gray-200 text-gray-700
                                @endif">
                                <i class="fas fa-crown mr-2"></i>
                                {{ ucfirst(auth()->user()->subscription_status) }} Member
                            </span>
                        </div>
                        <div class="bg-white/20 backdrop-blur-lg rounded-xl px-6 py-4 border border-white/30">
                            <p class="text-sm text-blue-100 mb-1">Overall Progress</p>
                            <div class="flex items-baseline">
                                @if($stats['average_band_score'])
                                    <span class="text-3xl font-bold">{{ number_format($stats['average_band_score'], 1) }}</span>
                                    <span class="text-lg ml-2 text-blue-100">Band Score</span>
                                @else
                                    <span class="text-lg text-blue-100">No tests taken yet</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-10">
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:bg-white/20 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-100">Tests This Month</p>
                                <p class="text-3xl font-bold mt-1">{{ auth()->user()->tests_taken_this_month }}</p>
                                @php
                                    $testLimit = auth()->user()->getFeatureLimit('mock_tests_per_month');
                                @endphp
                                <p class="text-xs text-blue-200 mt-1">
                                    @if($testLimit === 'unlimited')
                                        Unlimited
                                    @else
                                        of {{ $testLimit }} used
                                    @endif
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:bg-white/20 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-100">AI Evaluations</p>
                                <p class="text-3xl font-bold mt-1">{{ auth()->user()->ai_evaluations_used }}</p>
                                <p class="text-xs text-blue-200 mt-1">
                                    @if(auth()->user()->hasFeature('ai_writing_evaluation'))
                                        Available
                                    @else
                                        <a href="{{ route('subscription.plans') }}" class="underline">Upgrade</a>
                                    @endif
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:bg-white/20 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-100">Days Left</p>
                                @if(auth()->user()->activeSubscription())
                                    <p class="text-3xl font-bold mt-1">{{ auth()->user()->activeSubscription()->days_remaining }}</p>
                                    <p class="text-xs text-blue-200 mt-1">Until renewal</p>
                                @else
                                    <p class="text-2xl font-bold mt-1">Free</p>
                                    <p class="text-xs text-blue-200 mt-1">Forever</p>
                                @endif
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:bg-white/20 transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-100">Study Streak</p>
                                <p class="text-3xl font-bold mt-1">7</p>
                                <p class="text-xs text-blue-200 mt-1">Days in a row</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-8">
            <!-- Subscription Alert (if needed) -->
            @if(auth()->user()->subscription_status === 'free')
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl shadow-xl p-6 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-rocket text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Unlock Your Full Potential!</h3>
                            <p class="text-purple-100">Upgrade to Premium for unlimited tests, AI evaluation, and personalized study plans.</p>
                        </div>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="bg-white text-purple-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Upgrade Now
                    </a>
                </div>
            </div>
            @endif

            @if(auth()->user()->activeSubscription() && auth()->user()->activeSubscription()->days_remaining <= 7)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Your subscription expires in {{ auth()->user()->activeSubscription()->days_remaining }} days. 
                            <a href="{{ route('subscription.index') }}" class="font-medium underline">Renew now</a> to keep your premium features.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Practice Tests Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Practice Tests</h2>
                        <p class="text-gray-600 mt-1">Choose a section to start practicing</p>
                    </div>
                    <a href="{{ route('student.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                        View All Tests →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Test sections cards (same as before) -->
                    <!-- Listening Section -->
                    <a href="{{ route('student.listening.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition-all duration-300 border border-blue-200">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-200 rounded-full -mr-12 -mt-12 opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Listening</h3>
                            <p class="text-sm text-gray-600 mb-4">Practice with audio recordings</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $testSections->where('name', 'listening')->first()?->testSets->count() ?? 0 }} Tests</span>
                                <svg class="w-5 h-5 text-blue-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Reading Section -->
                    <a href="{{ route('student.reading.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition-all duration-300 border border-green-200">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-green-200 rounded-full -mr-12 -mt-12 opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reading</h3>
                            <p class="text-sm text-gray-600 mb-4">Comprehension passages</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $testSections->where('name', 'reading')->first()?->testSets->count() ?? 0 }} Tests</span>
                                <svg class="w-5 h-5 text-green-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Writing Section -->
                    <a href="{{ route('student.writing.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 hover:shadow-lg transition-all duration-300 border border-yellow-200">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-200 rounded-full -mr-12 -mt-12 opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-yellow-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Writing</h3>
                            <p class="text-sm text-gray-600 mb-4">Essay & report writing</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $testSections->where('name', 'writing')->first()?->testSets->count() ?? 0 }} Tests</span>
                                @if(!auth()->user()->hasFeature('ai_writing_evaluation'))
                                    <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">AI Pro</span>
                                @else
                                    <svg class="w-5 h-5 text-yellow-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </a>

                    <!-- Speaking Section -->
                    <a href="{{ route('student.speaking.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition-all duration-300 border border-purple-200">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-purple-200 rounded-full -mr-12 -mt-12 opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Speaking</h3>
                            <p class="text-sm text-gray-600 mb-4">Interview practice</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $testSections->where('name', 'speaking')->first()?->testSets->count() ?? 0 }} Tests</span>
                                @if(!auth()->user()->hasFeature('ai_speaking_evaluation'))
                                    <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">AI Pro</span>
                                @else
                                    <svg class="w-5 h-5 text-purple-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Recent Activity -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Recent Activity</h2>
                        <a href="{{ route('student.results') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                            View All →
                        </a>
                    </div>

                    @if($recentAttempts->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($recentAttempts as $attempt)
                                <div class="group bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                                                @switch($attempt->testSet->section->name)
                                                    @case('listening') bg-blue-100 @break
                                                    @case('reading') bg-green-100 @break
                                                    @case('writing') bg-yellow-100 @break
                                                    @case('speaking') bg-purple-100 @break
                                                @endswitch">
                                                @switch($attempt->testSet->section->name)
                                                    @case('listening')
                                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                                        </svg>
                                                        @break
                                                    @case('reading')
                                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                        @break
                                                    @case('writing')
                                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                        @break
                                                    @case('speaking')
                                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                                        </svg>
                                                        @break
                                                @endswitch
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $attempt->testSet->title }}</h4>
                                                <p class="text-sm text-gray-600 capitalize">{{ $attempt->testSet->section->name }} Test</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $attempt->created_at->format('M d, Y at h:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($attempt->band_score)
                                                <div class="text-2xl font-bold text-gray-900">{{ number_format($attempt->band_score, 1) }}</div>
                                                <p class="text-xs text-gray-600">Band Score</p>
                                                @if($attempt->ai_band_score)
                                                    <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full mt-1 inline-block">
                                                        <i class="fas fa-robot mr-1"></i> AI: {{ number_format($attempt->ai_band_score, 1) }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                                    @if($attempt->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($attempt->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($attempt->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($attempt->status === 'completed')
                                        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                                            <a href="{{ route('student.results.show', $attempt) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                View Detailed Results →
                                            </a>
                                            @if(in_array($attempt->testSet->section->name, ['writing', 'speaking']) && !$attempt->ai_evaluated_at)
                                                @if(auth()->user()->hasFeature('ai_' . $attempt->testSet->section->name . '_evaluation'))
                                                    <button class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                                        <i class="fas fa-robot mr-1"></i> Get AI Evaluation
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500">No test attempts yet</p>
                            <p class="text-sm text-gray-400 mt-1">Start practicing to see your progress here</p>
                        </div>
                    @endif
                </div>

                <!-- Performance Overview & Premium Features -->
                <div class="space-y-8">
                    <!-- Performance Overview -->
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Overview</h2>
                        
                        @if($sectionPerformance->where('attempts_count', '>', 0)->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($sectionPerformance as $performance)
                                    @if($performance['attempts_count'] > 0)
                                        <div>
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-sm font-semibold text-gray-700 capitalize">{{ $performance['name'] }}</h4>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($performance['average_score'], 1) }}</span>
                                            </div>
                                            <div class="relative w-full bg-gray-200 rounded-full h-3">
                                                <div class="absolute top-0 left-0 h-full rounded-full bg-gradient-to-r
                                                    @switch($performance['name'])
                                                        @case('listening') from-blue-400 to-blue-600 @break
                                                        @case('reading') from-green-400 to-green-600 @break
                                                        @case('writing') from-yellow-400 to-yellow-600 @break
                                                        @case('speaking') from-purple-400 to-purple-600 @break
                                                    @endswitch"
                                                    style="width: {{ min(($performance['average_score'] / 9) * 100, 100) }}%">
                                                </div>
                                            </div>
                                            <div class="flex justify-between mt-1">
                                                <span class="text-xs text-gray-500">{{ $performance['attempts_count'] }} attempts</span>
                                                <span class="text-xs text-gray-500">Best: {{ number_format($performance['best_score'], 1) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-8 p-4 bg-indigo-50 rounded-lg">
                                <p class="text-sm text-indigo-900 font-medium mb-2">Pro Tip:</p>
                                <p class="text-xs text-indigo-700">Focus on your weakest section to improve your overall band score. Consistent practice leads to better results!</p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">No performance data yet</p>
                                <p class="text-xs text-gray-400 mt-1">Complete some tests to track your progress</p>
                            </div>
                        @endif
                    </div>

                    <!-- Premium Features Card -->
                    @if(auth()->user()->subscription_status !== 'free')
                    <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
                        <h3 class="text-xl font-bold mb-4">Your Premium Features</h3>
                        <ul class="space-y-3">
                            @if(auth()->user()->hasFeature('ai_writing_evaluation'))
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>AI Writing Evaluation</span>
                            </li>
                            @endif
                            @if(auth()->user()->hasFeature('ai_speaking_evaluation'))
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>AI Speaking Evaluation</span>
                            </li>
                            @endif
                            @if(auth()->user()->hasFeature('detailed_analytics'))
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Detailed Analytics</span>
                            </li>
                            @endif
                            @if(auth()->user()->hasFeature('priority_support'))
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Priority Support</span>
                            </li>
                            @endif
                        </ul>
                        <a href="{{ route('subscription.index') }}" class="inline-block mt-6 bg-white text-purple-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                            Manage Subscription
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Learning Resources -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2 class="text-3xl font-bold mb-4">Ready to improve your score?</h2>
                        <p class="text-lg text-indigo-100 mb-6">Access comprehensive study materials, practice tests, and expert tips to boost your IELTS performance.</p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('student.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                                Start Practice Test
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                            <a href="#" class="inline-flex items-center px-6 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-indigo-600 transition-all">
                                Study Resources
                            </a>
                        </div>
                    </div>
                    <div class="hidden lg:flex justify-end">
                        <div class="relative">
                            <div class="absolute inset-0 bg-white/20 rounded-full blur-3xl"></div>
                            <svg class="relative w-64 h-64 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>