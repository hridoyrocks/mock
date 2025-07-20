<x-admin-layout>
    <x-slot:title>Manage Tokens - {{ $user->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- User Info -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-semibold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.user-tokens.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to Users
                </a>
            </div>
        </div>

        <!-- Current Balance -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Current Token Balance</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $tokenBalance->available_tokens }}</p>
                    <p class="text-sm text-gray-500">Available Tokens</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $tokenBalance->used_tokens }}</p>
                    <p class="text-sm text-gray-500">Used Tokens</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $tokenBalance->available_tokens + $tokenBalance->used_tokens }}</p>
                    <p class="text-sm text-gray-500">Total Received</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Add Tokens -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Tokens</h3>
                <form action="{{ route('admin.user-tokens.add', $user) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="add-tokens" class="block text-sm font-medium text-gray-700">Number of Tokens</label>
                            <input type="number" name="tokens" id="add-tokens" min="1" max="1000" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="10">
                        </div>
                        <div>
                            <label for="add-reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <input type="text" name="reason" id="add-reason" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Promotional bonus">
                        </div>
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Tokens
                        </button>
                    </div>
                </form>
            </div>

            <!-- Deduct Tokens -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Deduct Tokens</h3>
                <form action="{{ route('admin.user-tokens.deduct', $user) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="deduct-tokens" class="block text-sm font-medium text-gray-700">Number of Tokens</label>
                            <input type="number" name="tokens" id="deduct-tokens" min="1" max="{{ $tokenBalance->available_tokens }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="5">
                        </div>
                        <div>
                            <label for="deduct-reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <input type="text" name="reason" id="deduct-reason" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Refund adjustment">
                        </div>
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            Deduct Tokens
                        </button>
                    </div>
                </form>
            </div>

            <!-- Set Exact Balance -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Set Exact Balance</h3>
                <form action="{{ route('admin.user-tokens.set', $user) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="set-tokens" class="block text-sm font-medium text-gray-700">New Balance</label>
                            <input type="number" name="tokens" id="set-tokens" min="0" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="{{ $tokenBalance->available_tokens }}">
                        </div>
                        <div>
                            <label for="set-reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <input type="text" name="reason" id="set-reason" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Balance correction">
                        </div>
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Set Balance
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Token History -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Token Transaction History</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance After</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tokenHistory as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ in_array($transaction->type, ['purchase', 'admin_grant', 'subscription_bonus']) ? 'bg-green-100 text-green-800' : '' }}
                                    {{ in_array($transaction->type, ['usage', 'admin_deduct']) ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $transaction->type == 'admin_set' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $transaction->type == 'refund' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($transaction->amount > 0)
                                    <span class="text-green-600">+{{ $transaction->amount }}</span>
                                @elseif($transaction->amount < 0)
                                    <span class="text-red-600">{{ $transaction->amount }}</span>
                                @else
                                    <span class="text-gray-500">0</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->balance_after }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->reason ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($transaction->admin_id)
                                    {{ \App\Models\User::find($transaction->admin_id)->name ?? 'Unknown' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-sm">
                                No transaction history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
