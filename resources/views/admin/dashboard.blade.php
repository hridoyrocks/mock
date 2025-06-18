{{-- resources/views/admin/dashboard.blade.php --}}
<x-admin-layout>
    <x-slot:title>Dashboard Overview</x-slot>
    
    <x-slot:header>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="text-sm text-gray-600 mt-1">Welcome back! Here's what's happening with your platform today.</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Total Students -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_students']) }}</p>
                        <p class="text-xs text-green-600 mt-1">+12.5% from last month</p>
                    </div>
                </div>
            </div>

            <!-- Test Collections -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Test Collections</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_test_sets']) }}</p>
                        <p class="text-xs text-blue-600 mt-1">Active</p>
                    </div>
                </div>
            </div>

            <!-- Total Questions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Questions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_questions']) }}</p>
                        <p class="text-xs text-purple-600 mt-1">Across all sections</p>
                    </div>
                </div>
            </div>

            <!-- Pending Reviews -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_evaluations']) }}</p>
                        @if($stats['pending_evaluations'] > 0)
                            <p class="text-xs text-red-600 mt-1">Action required</p>
                        @else
                            <p class="text-xs text-green-600 mt-1">All caught up</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Chart Section -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Test Activity Trends</h3>
                    <select class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Last 12 months</option>
                        <option>Last 6 months</option>
                        <option>Last 30 days</option>
                    </select>
                </div>
                <div class="h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-4 max-h-64 overflow-y-auto">
                    @forelse($recent_attempts as $attempt)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">{{ substr($attempt->user->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $attempt->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $attempt->testSet->title }} - {{ $attempt->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($attempt->status === 'completed') bg-green-100 text-green-800
                                @elseif($attempt->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($attempt->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm text-center py-4">No recent activity</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Section Performance -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Section Performance</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($section_stats as $section)
                    <div class="border rounded-lg p-4 hover:border-indigo-300 transition-colors">
                        <h4 class="text-sm font-semibold text-gray-900 capitalize mb-3">{{ $section->name }}</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Test Sets</span>
                                <span class="font-medium">{{ $section->test_sets_count }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Questions</span>
                                <span class="font-medium">{{ $section->total_questions }}</span>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('admin.questions.create', ['section' => $section->name]) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                    Add Content →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.questions.create') }}" 
               class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-300 transition-colors">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <p class="text-sm font-medium text-gray-900">Create Question</p>
                <p class="text-xs text-gray-500 mt-1">Add new test content</p>
            </a>

            <a href="{{ route('admin.test-sets.create') }}" 
               class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-300 transition-colors">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-900">New Test Set</p>
                <p class="text-xs text-gray-500 mt-1">Create test collection</p>
            </a>

            <a href="{{ route('admin.attempts.index', ['status' => 'completed']) }}" 
               class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-300 transition-colors">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <p class="text-sm font-medium text-gray-900">Review Tests</p>
                <p class="text-xs text-gray-500 mt-1">Evaluate submissions</p>
            </a>

            <a href="#" 
               class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-300 transition-colors">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-900">View Reports</p>
                <p class="text-xs text-gray-500 mt-1">Analytics & insights</p>
            </a>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Configuration
        const ctx = document.getElementById('activityChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Test Attempts',
                    data: {!! json_encode($chart_data) !!},
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>