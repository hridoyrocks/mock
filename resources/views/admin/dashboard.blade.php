<x-admin-layout>
    <x-slot:title>Dashboard</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-600">Here's what's happening with your platform today.</p>
            </div>
            <div class="mt-4 flex space-x-3 sm:mt-0">
                <button class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="lucide-download mr-2"></i>Export
                </button>
                <button class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-dark">
                    <i class="lucide-calendar mr-2"></i>Last 30 Days
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $metrics = [
                [
                    'title' => 'Total Students',
                    'value' => \App\Models\User::where('is_admin', false)->count(),
                    'change' => '+12.5%',
                    'changeType' => 'positive',
                    'icon' => 'users',
                    'color' => 'blue',
                    'subtext' => 'Active users'
                ],
                [
                    'title' => 'Monthly Revenue',
                    'value' => '৳' . number_format(\App\Models\PaymentTransaction::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('amount'), 0),
                    'change' => '+23.1%',
                    'changeType' => 'positive',
                    'icon' => 'trending-up',
                    'color' => 'green',
                    'subtext' => 'This month'
                ],
                [
                    'title' => 'Active Subscriptions',
                    'value' => \App\Models\UserSubscription::active()->count(),
                    'change' => '+18.2%',
                    'changeType' => 'positive',
                    'icon' => 'crown',
                    'color' => 'purple',
                    'subtext' => 'Premium users'
                ],
                [
                    'title' => 'Pending Reviews',
                    'value' => \App\Models\StudentAttempt::where('status', 'completed')->whereNull('band_score')->count(),
                    'change' => '-5',
                    'changeType' => 'negative',
                    'icon' => 'clock',
                    'color' => 'orange',
                    'subtext' => 'Needs attention'
                ]
            ];
        @endphp

        @foreach($metrics as $metric)
            <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">{{ $metric['title'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $metric['value'] }}</p>
                        <div class="mt-2 flex items-center text-sm">
                            <span class="font-medium {{ $metric['changeType'] === 'positive' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $metric['change'] }}
                            </span>
                            <span class="ml-2 text-gray-500">{{ $metric['subtext'] }}</span>
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg 
                        @if($metric['color'] === 'blue') bg-blue-100
                        @elseif($metric['color'] === 'green') bg-green-100
                        @elseif($metric['color'] === 'purple') bg-purple-100
                        @else bg-orange-100
                        @endif">
                        @if($metric['icon'] === 'users')
                            <svg class="h-6 w-6 @if($metric['color'] === 'blue') text-blue-600 @elseif($metric['color'] === 'green') text-green-600 @elseif($metric['color'] === 'purple') text-purple-600 @else text-orange-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        @elseif($metric['icon'] === 'trending-up')
                            <svg class="h-6 w-6 @if($metric['color'] === 'blue') text-blue-600 @elseif($metric['color'] === 'green') text-green-600 @elseif($metric['color'] === 'purple') text-purple-600 @else text-orange-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        @elseif($metric['icon'] === 'crown')
                            <svg class="h-6 w-6 @if($metric['color'] === 'blue') text-blue-600 @elseif($metric['color'] === 'green') text-green-600 @elseif($metric['color'] === 'purple') text-purple-600 @else text-orange-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        @else
                            <svg class="h-6 w-6 @if($metric['color'] === 'blue') text-blue-600 @elseif($metric['color'] === 'green') text-green-600 @elseif($metric['color'] === 'purple') text-purple-600 @else text-orange-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Revenue Chart -->
        <div class="rounded-xl bg-white p-6 shadow-sm lg:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                    <p class="text-sm text-gray-600">Monthly revenue and growth trends</p>
                </div>
                <div class="flex space-x-2">
                    <button class="rounded-lg px-3 py-1 text-sm font-medium text-gray-600 hover:bg-gray-100">Day</button>
                    <button class="rounded-lg bg-gray-100 px-3 py-1 text-sm font-medium text-gray-900">Month</button>
                    <button class="rounded-lg px-3 py-1 text-sm font-medium text-gray-600 hover:bg-gray-100">Year</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">User Distribution</h3>
            <div class="h-80">
                <canvas id="userDistributionChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                @php
                    $plans = [
                        ['name' => 'Free', 'count' => \App\Models\User::where('subscription_status', 'free')->count(), 'color' => 'bg-gray-400'],
                        ['name' => 'Premium', 'count' => \App\Models\User::where('subscription_status', 'premium')->count(), 'color' => 'bg-blue-500'],
                        ['name' => 'Pro', 'count' => \App\Models\User::where('subscription_status', 'pro')->count(), 'color' => 'bg-purple-500']
                    ];
                @endphp
                @foreach($plans as $plan)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full {{ $plan['color'] }}"></div>
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ $plan['name'] }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $plan['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Activity Feed & Quick Actions -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Recent Activity -->
        <div class="rounded-xl bg-white p-6 shadow-sm lg:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                <a href="{{ route('admin.attempts.index') }}" class="text-sm font-medium text-primary hover:text-primary-dark">
                    View all →
                </a>
            </div>
            
            <div class="space-y-4">
                @php
                    $recentAttempts = \App\Models\StudentAttempt::with(['user', 'testSet.section'])
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                
                @forelse($recentAttempts as $attempt)
                    <div class="flex items-center rounded-lg border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $attempt->user->name }}</p>
                            <p class="text-xs text-gray-500">
                                Completed {{ $attempt->testSet->section->name }} test • {{ $attempt->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="ml-4">
                            @if($attempt->band_score)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                    Band {{ $attempt->band_score }}
                                </span>
                            @else
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-500">No recent activity</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.questions.create') }}" 
                       class="flex items-center rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Add Question</p>
                            <p class="text-xs text-gray-500">Create new test content</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.test-sets.create') }}" 
                       class="flex items-center rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">New Test Set</p>
                            <p class="text-xs text-gray-500">Create test collection</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.subscriptions.users') }}" 
                       class="flex items-center rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 00-2-2H5.5A2.5 2.5 0 003 6.5v11A2.5 2.5 0 005.5 20H6a2 2 0 002-2v-2m4-13v13m0-13h2a2 2 0 012 2v2m-4-4h2a2 2 0 012 2v2m0 0v11a2 2 0 01-2 2h-.5a2.5 2.5 0 01-2.5-2.5v-11A2.5 2.5 0 0114.5 4H15a2 2 0 012 2v2z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Grant Access</p>
                            <p class="text-xs text-gray-500">Manage subscriptions</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- System Health -->
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">System Health</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Server Status</span>
                            <span class="font-medium text-green-600">Operational</span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-gray-200">
                            <div class="h-2 w-full rounded-full bg-green-500"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">API Response</span>
                            <span class="font-medium text-gray-900">45ms</span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-gray-200">
                            <div class="h-2 w-3/4 rounded-full bg-blue-500"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Storage Used</span>
                            <span class="font-medium text-gray-900">62%</span>
                        </div>
                        <div class="mt-2 h-2 w-full rounded-full bg-gray-200">
                            <div class="h-2 w-3/5 rounded-full bg-yellow-500"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [
                        @php
                            $monthlyRevenue = [];
                            for ($i = 1; $i <= 12; $i++) {
                                $revenue = \App\Models\PaymentTransaction::where('status', 'completed')
                                    ->whereMonth('created_at', $i)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('amount');
                                $monthlyRevenue[] = $revenue;
                            }
                            echo implode(',', $monthlyRevenue);
                        @endphp
                    ],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                return '৳' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            },
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });

        // User Distribution Chart
        const userDistCtx = document.getElementById('userDistributionChart').getContext('2d');
        const userDistChart = new Chart(userDistCtx, {
            type: 'doughnut',
            data: {
                labels: ['Free', 'Premium', 'Pro'],
                datasets: [{
                    data: [
                        {{ \App\Models\User::where('subscription_status', 'free')->count() }},
                        {{ \App\Models\User::where('subscription_status', 'premium')->count() }},
                        {{ \App\Models\User::where('subscription_status', 'pro')->count() }}
                    ],
                    backgroundColor: ['#9ca3af', '#3b82f6', '#8b5cf6'],
                    borderWidth: 0,
                    spacing: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                cutout: '75%'
            }
        });

        // Real-time updates simulation
        setInterval(() => {
            // Add animation to metrics
            document.querySelectorAll('.metric-card').forEach(card => {
                card.classList.add('animate-pulse');
                setTimeout(() => card.classList.remove('animate-pulse'), 1000);
            });
        }, 30000);
    </script>
    @endpush
</x-admin-layout>