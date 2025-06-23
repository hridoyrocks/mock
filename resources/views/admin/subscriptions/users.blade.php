<x-admin-layout>
    <x-slot:title>Subscription Users</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Subscription Users</h1>
                <p class="text-sm text-gray-600 mt-1">Manage user subscriptions and grant access</p>
            </div>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="p-6">
        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Name or email..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="free" {{ request('status') == 'free' ? 'selected' : '' }}>Free Users</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.subscriptions.users') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tests/Month</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AI Used</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->activeSubscription())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->activeSubscription()->plan->slug === 'pro') bg-purple-100 text-purple-800
                                            @elseif($user->activeSubscription()->plan->slug === 'premium') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $user->activeSubscription()->plan->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Free
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->activeSubscription())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->tests_taken_this_month }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->ai_evaluations_used }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->activeSubscription())
                                        {{ $user->activeSubscription()->ends_at->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="openGrantModal({{ $user->id }}, '{{ $user->name }}')" 
                                                class="text-blue-600 hover:text-blue-900">Grant</button>
                                        @if($user->activeSubscription())
                                            <button onclick="openRevokeModal({{ $user->activeSubscription()->id }}, '{{ $user->name }}')" 
                                                    class="text-red-600 hover:text-red-900">Revoke</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>

    {{-- Grant Modal --}}
    <div id="grantModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <form id="grantForm" method="POST">
                @csrf
                <h3 class="text-lg font-bold text-gray-900 mb-4">Grant Subscription to <span id="grantUserName"></span></h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Plan</label>
                    <select name="plan_id" required class="w-full rounded-md border-gray-300 shadow-sm">
                        @foreach(\App\Models\SubscriptionPlan::active()->get() as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} - ৳{{ $plan->price }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (days)</label>
                    <input type="number" name="duration_days" value="30" required 
                           class="w-full rounded-md border-gray-300 shadow-sm">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <textarea name="reason" required rows="2" 
                              class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeGrantModal()" 
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Grant Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Revoke Modal --}}
    <div id="revokeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <form id="revokeForm" method="POST">
                @csrf
                <h3 class="text-lg font-bold text-gray-900 mb-4">Revoke Subscription</h3>
                <p class="mb-4">Are you sure you want to revoke <span id="revokeUserName"></span>'s subscription?</p>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <textarea name="reason" required rows="2" 
                              class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeRevokeModal()" 
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Revoke Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openGrantModal(userId, userName) {
            document.getElementById('grantUserName').innerText = userName;
            document.getElementById('grantForm').action = `/admin/subscriptions/grant/${userId}`;
            document.getElementById('grantModal').classList.remove('hidden');
        }
        
        function closeGrantModal() {
            document.getElementById('grantModal').classList.add('hidden');
        }
        
        function openRevokeModal(subscriptionId, userName) {
            document.getElementById('revokeUserName').innerText = userName;
            document.getElementById('revokeForm').action = `/admin/subscriptions/revoke/${subscriptionId}`;
            document.getElementById('revokeModal').classList.remove('hidden');
        }
        
        function closeRevokeModal() {
            document.getElementById('revokeModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-admin-layout>