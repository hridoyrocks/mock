<x-admin-layout>
    <x-slot:title>Subscription Users</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Subscription Users</h1>
                <p class="mt-2 text-gray-600">Manage user subscriptions and grant access</p>
            </div>
            <div class="mt-4 flex items-center space-x-3 sm:mt-0">
                <a href="{{ route('admin.subscriptions.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Analytics
                </a>
                <button onclick="openBulkGrantModal()" 
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Grant Bulk Access
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $totalUsers = \App\Models\User::where('is_admin', false)->count();
            $activeSubscriptions = \App\Models\UserSubscription::active()->count();
            $expiringSoon = \App\Models\UserSubscription::expiringSoon(7)->count();
            $freeUsers = \App\Models\User::where('subscription_status', 'free')->count();
        @endphp
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Subscriptions</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($activeSubscriptions) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Expiring Soon</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($expiringSoon) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Free Users</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($freeUsers) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100">
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label class="mb-2 block text-sm font-medium text-gray-700">Search User</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email or phone..."
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-10 pr-4 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="free" {{ request('status') == 'free' ? 'selected' : '' }}>Free Users</option>
                </select>
            </div>
            
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Plan</label>
                <select name="plan" class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 px-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">All Plans</option>
                    <option value="free" {{ request('plan') == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="pro" {{ request('plan') == 'pro' ? 'selected' : '' }}>Pro</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.subscriptions.users') }}" 
                   class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Current Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Usage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Expires</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                                            <span class="text-sm font-medium">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        @if($user->phone_number)
                                            <div class="text-xs text-gray-400">{{ $user->phone_number }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($user->activeSubscription())
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @if($user->activeSubscription()->plan->slug === 'pro') bg-purple-100 text-purple-800
                                        @elseif($user->activeSubscription()->plan->slug === 'premium') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $user->activeSubscription()->plan->name }}
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">
                                        Free
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($user->activeSubscription())
                                    @if($user->activeSubscription()->ends_at->diffInDays(now()) <= 7)
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Expiring Soon
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                            Active
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <svg class="mr-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span>{{ $user->tests_taken_this_month }} tests</span>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <svg class="mr-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $user->ai_evaluations_used }} AI</span>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                @if($user->activeSubscription())
                                    <div>
                                        {{ $user->activeSubscription()->ends_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ $user->activeSubscription()->ends_at->diffForHumans() }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    @if($user->activeSubscription())
                                        <button onclick="openRevokeModal({{ $user->activeSubscription()->id }}, '{{ addslashes($user->name) }}')" 
                                                class="rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700 transition-colors"
                                                data-subscription-id="{{ $user->activeSubscription()->id }}"
                                                data-user-name="{{ $user->name }}">
                                            Revoke
                                        </button>
                                    @else
                                        <button onclick="openGrantModal({{ $user->id }}, '{{ addslashes($user->name) }}')" 
                                                class="rounded-lg bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700 transition-colors">
                                            Grant
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.attempts.index', ['user' => $user->id]) }}" 
                                       class="rounded-lg border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                        View Tests
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Grant Modal -->
    <div id="grantModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4 py-4 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeGrantModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
                <form id="grantForm" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button type="button" onclick="closeGrantModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 00-2-2H5.5A2.5 2.5 0 003 6.5v11A2.5 2.5 0 005.5 20H6a2 2 0 002-2v-2m4-13v13m0-13h2a2 2 0 012 2v2m-4-4h2a2 2 0 012 2v2m0 0v11a2 2 0 01-2 2h-.5a2.5 2.5 0 01-2.5-2.5v-11A2.5 2.5 0 0114.5 4H15a2 2 0 012 2v2z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    Grant Subscription to <span id="grantUserName" class="font-bold"></span>
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Select Plan</label>
                                        <select name="plan_id" required 
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Select a plan</option>
                                            @php
                                                $plans = \App\Models\SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
                                            @endphp
                                            @forelse($plans as $plan)
                                                <option value="{{ $plan->id }}">
                                                    {{ $plan->name }} - ৳{{ number_format($plan->price) }}
                                                    @if($plan->discount_price)
                                                        ({{ $plan->discount_percentage }}% off)
                                                    @endif
                                                    - {{ $plan->duration_days }} days
                                                </option>
                                            @empty
                                                <option value="" disabled>No active plans available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Duration (days)</label>
                                        <input type="number" name="duration_days" value="30" min="1" max="365" required 
                                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Reason</label>
                                        <textarea name="reason" required rows="3" 
                                                  placeholder="e.g., Promotional offer, Customer support resolution..."
                                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" 
                                id="grantSubmitBtn"
                                class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Grant Subscription
                        </button>
                        <button type="button" 
                                onclick="closeGrantModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Revoke Modal -->
    <div id="revokeModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4 py-4 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeRevokeModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
                <form id="revokeForm" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button type="button" onclick="closeRevokeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Revoke Subscription</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to revoke <span id="revokeUserName" class="font-semibold"></span>'s subscription? 
                                        This action will immediately cancel their access to premium features.
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Reason for revocation</label>
                                    <textarea name="reason" required rows="3" 
                                              placeholder="e.g., Violation of terms, Payment issue..."
                                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" 
                                id="revokeSubmitBtn"
                                class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Revoke Subscription
                        </button>
                        <button type="button" 
                                onclick="closeRevokeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize event listeners after DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission handlers
            const grantForm = document.getElementById('grantForm');
            const revokeForm = document.getElementById('revokeForm');
            
            if (grantForm) {
                grantForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    console.log('Grant form submitted');
                    console.log('Form action:', this.action);
                    console.log('Form method:', this.method);
                    
                    // Add loading state to button
                    const btn = document.getElementById('grantSubmitBtn');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                    }
                    
                    // Submit form
                    this.submit();
                });
            }
            
            if (revokeForm) {
                revokeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    console.log('Revoke form submitted');
                    console.log('Form action:', this.action);
                    console.log('Form method:', this.method);
                    
                    // Add loading state to button
                    const btn = document.getElementById('revokeSubmitBtn');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                    }
                    
                    // Submit form
                    this.submit();
                });
            }
        });
        
        // Global functions for modals
        function openGrantModal(userId, userName) {
            console.log('Opening grant modal for user:', userId, userName);
            document.getElementById('grantUserName').innerText = userName;
            const form = document.getElementById('grantForm');
            form.action = `/admin/subscriptions/grant/${userId}`;
            form.method = 'POST';
            console.log('Form action set to:', form.action);
            const modal = document.getElementById('grantModal');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeGrantModal() {
            const modal = document.getElementById('grantModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('grantForm').reset();
            
            // Reset button state
            const btn = document.getElementById('grantSubmitBtn');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Grant Subscription';
            }
        }
        
        function openRevokeModal(subscriptionId, userName) {
            console.log('Opening revoke modal for subscription:', subscriptionId, userName);
            
            if (!subscriptionId) {
                console.error('No subscription ID provided!');
                showToast('Error: No subscription ID found', 'error');
                return;
            }
            
            document.getElementById('revokeUserName').innerText = userName;
            const form = document.getElementById('revokeForm');
            form.action = `/admin/subscriptions/revoke/${subscriptionId}`;
            form.method = 'POST';
            console.log('Form action set to:', form.action);
            const modal = document.getElementById('revokeModal');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeRevokeModal() {
            const modal = document.getElementById('revokeModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('revokeForm').reset();
            
            // Reset button state
            const btn = document.getElementById('revokeSubmitBtn');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Revoke Subscription';
            }
        }
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeGrantModal();
                closeRevokeModal();
            }
        });
        
        function openBulkGrantModal() {
            showToast('Bulk grant feature coming soon!', 'info');
        }
    </script>
    @endpush
</x-admin-layout>

<style>
    /* Modal Styles */
    #grantModal, #revokeModal {
        z-index: 9999 !important;
    }
    
    /* Ensure modal content is above overlay */
    #grantModal .inline-block,
    #revokeModal .inline-block {
        z-index: 10000 !important;
        position: relative;
    }
    
    /* Fix for modal background */
    .fixed.inset-0.transition-opacity {
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    /* Ensure form elements are clickable */
    #grantModal input,
    #grantModal select,
    #grantModal textarea,
    #grantModal button,
    #revokeModal input,
    #revokeModal textarea,
    #revokeModal button {
        position: relative;
        z-index: 10001;
    }
</style>