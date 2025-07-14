<x-admin-layout>
    <x-slot:title>Subscription Analytics</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Subscription Analytics</h1>
                <p class="mt-2 text-gray-600">Monitor subscriptions, revenue, and user analytics</p>
            </div>
            <div class="mt-4 flex items-center space-x-3 sm:mt-0">
                <a href="{{ route('admin.subscriptions.users') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Manage Users
                </a>
                <a href="{{ route('admin.subscriptions.transactions') }}" 
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Transactions
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Active Subscribers -->
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active Subscribers</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_subscribers']) }}</p>
                    <div class="mt-2 flex items-center text-sm">
                        <svg class="mr-1 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium text-green-600">12%</span>
                        <span class="ml-2 text-gray-500">from last month</span>
                    </div>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue This Month -->
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Revenue This Month</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">৳{{ number_format($stats['revenue_this_month'], 0) }}</p>
                    <div class="mt-2 flex items-center text-sm">
                        @if($stats['revenue_this_month'] > 0)
                            <svg class="mr-1 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium text-green-600">8.5%</span>
                            <span class="ml-2 text-gray-500">growth</span>
                        @else
                            <span class="text-gray-500">No change</span>
                        @endif
                    </div>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New This Week -->
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">New This Week</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['new_subscribers_this_week'] }}</p>
                    <div class="mt-2 flex items-center text-sm">
                        <span class="font-medium text-blue-600">+{{ round(($stats['new_subscribers_this_week'] / max($stats['total_subscribers'], 1)) * 100, 1) }}%</span>
                        <span class="ml-2 text-gray-500">of total</span>
                    </div>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Churn Rate -->
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Churn Rate</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['churn_rate'] }}%</p>
                    <div class="mt-2 flex items-center text-sm">
                        @if($stats['churn_rate'] < 5)
                            <span class="font-medium text-green-600">Healthy</span>
                        @else
                            <span class="font-medium text-red-600">High</span>
                        @endif
                        <span class="ml-2 text-gray-500">monthly average</span>
                    </div>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Plan Distribution -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h3 class="mb-6 text-lg font-semibold text-gray-900">Plan Distribution</h3>
            <div class="h-64">
                <canvas id="planDistributionChart"></canvas>
            </div>
            <div class="mt-6 grid grid-cols-3 gap-4">
                @foreach($planDistribution as $plan)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $plan->count }}</p>
                        <p class="text-sm text-gray-600">{{ $plan->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Expiring Soon</h3>
                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
                    {{ $expiringSoon->count() }} users
                </span>
            </div>
            <div class="space-y-4">
                @if($expiringSoon->count() > 0)
                    @foreach($expiringSoon->take(5) as $subscription)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                                    <span class="text-sm font-medium">{{ substr($subscription->user->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $subscription->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $subscription->user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $subscription->plan->name }}</p>
                                <p class="text-xs text-red-600">{{ $subscription->ends_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($expiringSoon->count() > 5)
                        <a href="{{ route('admin.subscriptions.users') }}?status=expiring" 
                           class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            View all expiring subscriptions
                        </a>
                    @endif
                @else
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No subscriptions expiring soon</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Subscriptions Table -->
    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Subscriptions</h3>
            <p class="mt-1 text-sm text-gray-600">Latest subscription activities</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">End Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($recentSubscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                                        <span class="text-sm font-medium">{{ substr($subscription->user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $subscription->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $subscription->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800">
                                    {{ $subscription->plan->name }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    {{ $subscription->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $subscription->starts_at->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $subscription->ends_at->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <a href="{{ route('admin.subscriptions.users') }}?search={{ $subscription->user->email }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No recent subscriptions</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Plan Distribution Chart
        const ctx = document.getElementById('planDistributionChart').getContext('2d');
        const planDistributionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($planDistribution->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($planDistribution->pluck('count')) !!},
                    backgroundColor: [
                        '#9ca3af',  // Gray for Free
                        '#3b82f6',  // Blue for Premium  
                        '#8b5cf6'   // Purple for Pro
                    ],
                    borderWidth: 0,
                    spacing: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
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
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                label += context.parsed + ' users (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
    @endpush
</x-admin-layout>