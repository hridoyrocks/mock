<x-admin-layout>
    <x-slot:title>Payment Transactions</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payment Transactions</h1>
                <p class="mt-2 text-gray-600">View and manage all payment transactions</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.subscriptions.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Analytics
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Transactions</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totals['total_transactions']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Successful</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ number_format($totals['successful_transactions']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">৳{{ number_format($totals['total_amount'], 0) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Success Rate</p>
                    @php
                        $successRate = $totals['total_transactions'] > 0 
                            ? round(($totals['successful_transactions'] / $totals['total_transactions']) * 100, 1) 
                            : 0;
                    @endphp
                    <p class="mt-2 text-3xl font-bold {{ $successRate > 90 ? 'text-green-600' : 'text-yellow-600' }}">{{ $successRate }}%</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $successRate > 90 ? 'bg-green-100' : 'bg-yellow-100' }}">
                    <svg class="h-6 w-6 {{ $successRate > 90 ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">All Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="payment_method" class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">All Methods</option>
                    <option value="stripe" {{ request('payment_method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                    <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                    <option value="admin_granted" {{ request('payment_method') == 'admin_granted' ? 'selected' : '' }}>Admin Granted</option>
                </select>
            </div>
            
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                       class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.subscriptions.transactions') }}" 
                   class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 font-mono">{{ $transaction->transaction_id }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                                            <span class="text-sm font-medium">{{ substr($transaction->user->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($transaction->subscription && $transaction->subscription->plan)
                                    <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800">
                                        {{ $transaction->subscription->plan->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $methodColors = [
                                        'stripe' => 'bg-purple-100 text-purple-800',
                                        'bkash' => 'bg-pink-100 text-pink-800',
                                        'nagad' => 'bg-orange-100 text-orange-800',
                                        'admin_granted' => 'bg-gray-100 text-gray-800',
                                        'admin_revoked' => 'bg-red-100 text-red-800'
                                    ];
                                    $colorClass = $methodColors[$transaction->payment_method] ?? 'bg-gray-100 text-gray-800';
                                    
                                    // Get admin name if admin granted
                                    $methodDisplay = ucwords(str_replace('_', ' ', $transaction->payment_method));
                                    if (in_array($transaction->payment_method, ['admin_granted', 'admin_revoked'])) {
                                        // Try to extract admin ID from payment_reference (format: ADMIN_GRANT_123 or ADMIN_REVOKE_123)
                                        if ($transaction->payment_reference) {
                                            preg_match('/ADMIN_(?:GRANT|REVOKE)_(\d+)/', $transaction->payment_reference, $matches);
                                            if (!empty($matches[1])) {
                                                $adminUser = \App\Models\User::find($matches[1]);
                                                if ($adminUser) {
                                                    $action = $transaction->payment_method === 'admin_granted' ? 'Granted' : 'Revoked';
                                                    $methodDisplay = 'Admin ' . $action . ' (' . $adminUser->name . ')';
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $colorClass }}" 
                                      @if(in_array($transaction->payment_method, ['admin_granted', 'admin_revoked']) && $transaction->notes)
                                        title="{{ $transaction->notes }}"
                                      @endif>
                                    {{ $methodDisplay }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900">৳{{ number_format($transaction->amount, 2) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <button onclick="viewDetails('{{ $transaction->id }}')" 
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium transition-colors">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No transactions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                {{ $transactions->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Transaction Details Modal -->
    <div x-data="{ open: false }" 
         x-show="open" 
         @transaction-details.window="open = true"
         id="detailsModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Transaction Details</h3>
                        <button @click="open = false" onclick="closeDetailsModal()" 
                                class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div id="modalContent" class="space-y-4">
                        <!-- Content will be loaded dynamically -->
                        <div class="animate-pulse">
                            <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                            <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function viewDetails(transactionId) {
            document.getElementById('detailsModal').style.display = 'block';
            window.dispatchEvent(new CustomEvent('transaction-details'));
            
            // In real implementation, fetch details via AJAX
            // Example:
            // fetch(`/admin/transactions/${transactionId}`)
            //     .then(response => response.json())
            //     .then(data => {
            //         document.getElementById('modalContent').innerHTML = renderTransactionDetails(data);
            //     });
        }
        
        function closeDetailsModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }
    </script>
    @endpush
</x-admin-layout>