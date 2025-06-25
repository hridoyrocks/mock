{{-- resources/views/student/dashboard.blade.php --}}
<x-student-layout>
    <x-slot:title>Student Dashboard - IELTS Practice Platform</x-slot>
    
    <x-slot:header>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-sm text-gray-600 mt-1">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </x-slot>
    
    <!-- Dashboard Content -->
    <div class="p-6">
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Tests This Month -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tests This Month</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ auth()->user()->tests_taken_this_month }}</p>
                        @php
                            $testLimit = auth()->user()->getFeatureLimit('mock_tests_per_month');
                        @endphp
                        <p class="text-xs text-gray-500 mt-1">
                            @if($testLimit === 'unlimited')
                                Unlimited tests
                            @else
                                of {{ $testLimit }} used
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Average Band Score -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Average Band Score</p>
                        @if($stats['average_band_score'])
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['average_band_score'], 1) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Overall performance</p>
                        @else
                            <p class="text-2xl font-bold text-gray-900 mt-2">-</p>
                            <p class="text-xs text-gray-500 mt-1">No tests taken yet</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- AI Evaluations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">AI Evaluations</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ auth()->user()->ai_evaluations_used }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if(auth()->user()->hasFeature('ai_writing_evaluation'))
                                Available
                            @else
                                <a href="{{ route('subscription.plans') }}" class="text-indigo-600 hover:text-indigo-800">Upgrade</a>
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-robot text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Days Left -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Subscription</p>
                        @if(auth()->user()->activeSubscription())
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ auth()->user()->activeSubscription()->days_remaining }}</p>
                            <p class="text-xs text-gray-500 mt-1">Days remaining</p>
                        @else
                            <p class="text-2xl font-bold text-gray-900 mt-2">Free</p>
                            <p class="text-xs text-gray-500 mt-1">Forever</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-crown text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Chart & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
                    <a href="{{ route('student.results') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        View All →
                    </a>
                </div>

                @if($recentAttempts->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($recentAttempts as $attempt)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                        @switch($attempt->testSet->section->name)
                                            @case('listening') bg-blue-100 @break
                                            @case('reading') bg-green-100 @break
                                            @case('writing') bg-yellow-100 @break
                                            @case('speaking') bg-purple-100 @break
                                        @endswitch">
                                        @switch($attempt->testSet->section->name)
                                            @case('listening')
                                                <i class="fas fa-headphones text-blue-600"></i>
                                                @break
                                            @case('reading')
                                                <i class="fas fa-book-open text-green-600"></i>
                                                @break
                                            @case('writing')
                                                <i class="fas fa-pen text-yellow-600"></i>
                                                @break
                                            @case('speaking')
                                                <i class="fas fa-microphone text-purple-600"></i>
                                                @break
                                        @endswitch
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $attempt->testSet->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $attempt->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($attempt->band_score)
                                        <div class="text-xl font-bold text-gray-900">{{ number_format($attempt->band_score, 1) }}</div>
                                        <p class="text-xs text-gray-600">Band Score</p>
                                    @else
                                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No test attempts yet</p>
                        <a href="{{ route('student.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Start Your First Test
                        </a>
                    </div>
                @endif
            </div>

            <!-- Performance Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Performance Overview</h2>
                
                @if($sectionPerformance->where('attempts_count', '>', 0)->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($sectionPerformance as $performance)
                            @if($performance['attempts_count'] > 0)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $performance['name'] }}</span>
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($performance['average_score'], 1) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full
                                            @switch($performance['name'])
                                                @case('listening') bg-blue-500 @break
                                                @case('reading') bg-green-500 @break
                                                @case('writing') bg-yellow-500 @break
                                                @case('speaking') bg-purple-500 @break
                                            @endswitch"
                                            style="width: {{ min(($performance['average_score'] / 9) * 100, 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-sm">No performance data yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-sm p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Ready to practice?</h2>
                    <p class="text-indigo-100">Choose a test section to improve your IELTS score</p>
                </div>
                <a href="{{ route('student.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                    Start Practice
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</x-student-layout>