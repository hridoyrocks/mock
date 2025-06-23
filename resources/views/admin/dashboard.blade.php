{{-- resources/views/admin/dashboard.blade.php --}}
<x-admin-layout>
    <x-slot:title>Dashboard</x-slot>
    
    <x-slot:header>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">RX - Admin Dashboard</h1>
            
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Key Metrics with Subscription Stats -->
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
                        @php
                            $premiumUsers = \App\Models\User::where('subscription_status', '!=', 'free')->count();
                            $premiumPercentage = $stats['total_students'] > 0 ? round(($premiumUsers / $stats['total_students']) * 100, 1) : 0;
                        @endphp
                        <p class="text-xs text-green-600 mt-1">{{ $premiumPercentage }}% Premium</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Revenue This Month</p>
                        @php
                            $monthlyRevenue = \App\Models\PaymentTransaction::where('status', 'completed')
                                ->whereMonth('created_at', now()->month)
                                ->sum('amount');
                        @endphp
                        <p class="text-2xl font-bold text-gray-900">৳{{ number_format($monthlyRevenue, 0) }}</p>
                        <p class="text-xs text-green-600 mt-1">+15% from last month</p>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Subscriptions</p>
                        @php
                            $activeSubscriptions = \App\Models\UserSubscription::active()->count();
                        @endphp
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($activeSubscriptions) }}</p>
                        @php
                            $expiringCount = \App\Models\UserSubscription::expiringSoon(7)->count();
                        @endphp
                        @if($expiringCount > 0)
                            <p class="text-xs text-yellow-600 mt-1">{{ $expiringCount }} expiring soon</p>
                        @else
                            <p class="text-xs text-green-600 mt-1">All healthy</p>
                        @endif
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

        <!-- Subscription Overview Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Plan Distribution Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscription Distribution</h3>
                @php
                    $planDistribution = \App\Models\User::select('subscription_status', \DB::raw('count(*) as count'))
                        ->where('is_admin', false)
                        ->groupBy('subscription_status')
                        ->get();
                @endphp
                <div class="space-y-4">
                    @foreach($planDistribution as $plan)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium capitalize">{{ $plan->subscription_status }}</span>
                                <span class="text-gray-600">{{ $plan->count }} users</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full
                                    @if($plan->subscription_status === 'pro') bg-purple-600
                                    @elseif($plan->subscription_status === 'premium') bg-blue-600
                                    @else bg-gray-400
                                    @endif"
                                    style="width: {{ ($plan->count / $stats['total_students']) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Revenue Trends -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Trends</h3>
                    <select class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Last 12 months</option>
                        <option>Last 6 months</option>
                        <option>Last 30 days</option>
                    </select>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Recent Transactions -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                    <a href="{{ route('admin.subscriptions.transactions') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All →</a>
                </div>
                @php
                    $recentTransactions = \App\Models\PaymentTransaction::with(['user', 'subscription.plan'])
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Plan</th>
                                <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td class="py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $transaction->user->email }}</div>
                                    </td>
                                    <td class="py-3">
                                        @if($transaction->subscription)
                                            <span class="text-sm text-gray-900">{{ $transaction->subscription->plan->name }}</span>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <span class="text-sm font-medium text-gray-900">৳{{ number_format($transaction->amount, 0) }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-sm text-gray-500">
                                        {{ $transaction->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">No transactions yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subscription Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscription Stats</h3>
                    <div class="space-y-4">
                        @php
                            $todayRevenue = \App\Models\PaymentTransaction::where('status', 'completed')
                                ->whereDate('created_at', today())
                                ->sum('amount');
                            $newSubscribersToday = \App\Models\UserSubscription::whereDate('created_at', today())->count();
                            $churnRate = 2.5; // Calculate actual churn rate
                        @endphp
                        <div>
                            <p class="text-sm text-gray-600">Today's Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">৳{{ number_format($todayRevenue, 0) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">New Subscribers Today</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $newSubscribersToday }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Churn Rate</p>
                            <p class="text-2xl font-bold {{ $churnRate < 5 ? 'text-green-600' : 'text-red-600' }}">{{ $churnRate }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.subscriptions.users') }}" class="block w-full bg-indigo-600 text-white text-center py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-users mr-2"></i> Manage Users
                        </a>
                        <a href="{{ route('admin.subscriptions.transactions') }}" class="block w-full bg-green-600 text-white text-center py-2 px-4 rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-receipt mr-2"></i> View Transactions
                        </a>
                        <button onclick="showGrantModal()" class="block w-full bg-purple-600 text-white text-center py-2 px-4 rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-gift mr-2"></i> Grant Subscription
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Performance with Subscription Impact -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Section Performance & Usage</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($section_stats as $section)
                    @php
                        // Get usage stats for this section
                        $monthlyAttempts = \App\Models\StudentAttempt::whereHas('testSet', function($q) use ($section) {
                            $q->where('section_id', $section->id);
                        })->whereMonth('created_at', now()->month)->count();
                        
                        $premiumAttempts = \App\Models\StudentAttempt::whereHas('testSet', function($q) use ($section) {
                            $q->where('section_id', $section->id);
                        })->whereHas('user', function($q) {
                            $q->where('subscription_status', '!=', 'free');
                        })->whereMonth('created_at', now()->month)->count();
                    @endphp
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
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Monthly Usage</span>
                                <span class="font-medium">{{ $monthlyAttempts }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Premium %</span>
                                <span class="font-medium text-purple-600">
                                    {{ $monthlyAttempts > 0 ? round(($premiumAttempts / $monthlyAttempts) * 100) : 0 }}%
                                </span>
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

        <!-- AI Evaluation Stats -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">AI Evaluation Usage</h3>
            @php
                $aiWritingCount = \App\Models\StudentAnswer::whereNotNull('ai_evaluation')->whereMonth('ai_evaluated_at', now()->month)->count();
                $aiSpeakingCount = \App\Models\StudentAnswer::whereNotNull('ai_evaluation')->whereMonth('ai_evaluated_at', now()->month)->count();
                $totalAIUsage = $aiWritingCount + $aiSpeakingCount;
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <i class="fas fa-robot text-3xl text-purple-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAIUsage }}</p>
                    <p class="text-sm text-gray-600">Total AI Evaluations</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <i class="fas fa-pen-alt text-3xl text-blue-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-900">{{ $aiWritingCount }}</p>
                    <p class="text-sm text-gray-600">Writing Evaluations</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <i class="fas fa-microphone text-3xl text-green-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-900">{{ $aiSpeakingCount }}</p>
                    <p class="text-sm text-gray-600">Speaking Evaluations</p>
                </div>
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

            <a href="{{ route('admin.subscriptions.users') }}" 
               class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-300 transition-colors">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-900">Manage Subscriptions</p>
                <p class="text-xs text-gray-500 mt-1">User subscriptions</p>
            </a>

            <a href="{{ route('admin.attempts.index', ['status' => 'completed']) }}" 
               class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-300 transition-colors">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <p class="text-sm font-medium text-gray-900">Review Tests</p>
                <p class="text-xs text-gray-500 mt-1">Evaluate submissions</p>
            </a>
        </div>
    </div>

    {{-- Grant Subscription Modal --}}
    <div id="grantModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Grant Subscription</h3>
            <form method="GET" action="{{ route('admin.subscriptions.users') }}">
                <p class="text-sm text-gray-600 mb-4">Search for a user to grant subscription:</p>
                <input type="text" name="search" placeholder="Email or name..." required
                       class="w-full rounded-md border-gray-300 shadow-sm mb-4">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeGrantModal()" 
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                        Search User
                    </button>
                </div>
            </form>
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
                datasets: [
                    {
                        label: 'Revenue (BDT)',
                        data: [45000, 52000, 48000, 65000, 72000, 81000, 95000, 88000, 102000, 115000, 125000, 135000],
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.3
                    },
                    {
                        label: 'Subscriptions',
                        data: [120, 145, 135, 180, 195, 220, 250, 235, 275, 310, 340, 365],
                        borderColor: 'rgb(236, 72, 153)',
                        backgroundColor: 'rgba(236, 72, 153, 0.1)',
                        tension: 0.3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }
            }
        });

        // Modal functions
        function showGrantModal() {
            document.getElementById('grantModal').classList.remove('hidden');
        }
        
        function closeGrantModal() {
            document.getElementById('grantModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-admin-layout>