<x-admin-layout>
    <x-slot:title>User Token Management</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">User Token Management</h2>
                    <p class="mt-1 text-sm text-gray-600">Manage evaluation tokens for users</p>
                </div>
                <div class="flex items-center space-x-2">
                    @php
                        $totalTokens = \App\Models\UserEvaluationToken::sum('available_tokens');
                        $totalUsedTokens = \App\Models\UserEvaluationToken::sum('used_tokens');
                    @endphp
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Active Tokens</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ number_format($totalTokens) }}</p>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm text-gray-500">Total Used</p>
                        <p class="text-2xl font-bold text-gray-600">{{ number_format($totalUsedTokens) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white shadow-sm rounded-lg p-4">
            <form method="GET" class="space-y-4">
                <!-- Search Bar -->
                <div class="flex gap-4">
                    <div class="flex-1 relative">
                        <input type="text" 
                               name="search" 
                               placeholder="Search by name, email or phone..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Filters Row -->
                <div class="flex flex-wrap gap-4">
                    <!-- Token Filter -->
                    <select name="token_filter" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All Token Balances</option>
                        <option value="has_tokens" {{ request('token_filter') == 'has_tokens' ? 'selected' : '' }}>Has Tokens (&gt;0)</option>
                        <option value="no_tokens" {{ request('token_filter') == 'no_tokens' ? 'selected' : '' }}>No Tokens (0)</option>
                        <option value="low_tokens" {{ request('token_filter') == 'low_tokens' ? 'selected' : '' }}>Low Balance (1-10)</option>
                    </select>
                    
                    <!-- Sort By -->
                    <select name="sort_by" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="name" {{ request('sort_by', 'name') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Sort by Email</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sort by Join Date</option>
                        <option value="tokens" {{ request('sort_by') == 'tokens' ? 'selected' : '' }}>Sort by Token Balance</option>
                    </select>
                    
                    <!-- Sort Order -->
                    <select name="sort_order" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                    
                    @if(request()->anyFilled(['search', 'token_filter', 'sort_by', 'sort_order']))
                        <a href="{{ route('admin.user-tokens.index') }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        @if(request()->anyFilled(['search', 'token_filter']))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-800">
                    Found <strong>{{ $users->total() }}</strong> users
                    @if(request('search'))
                        matching "{{ request('search') }}"
                    @endif
                    @if(request('token_filter'))
                        with {{ str_replace('_', ' ', request('token_filter')) }}
                    @endif
                </p>
            </div>
        </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Tokens</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used Tokens</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    @php
                        $tokenBalance = $user->evaluationTokens ?? \App\Models\UserEvaluationToken::getOrCreateForUser($user);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold {{ $tokenBalance->available_tokens > 0 ? 'text-green-600' : 'text-gray-400' }}">{{ $tokenBalance->available_tokens }}</span>
                                <span class="text-sm text-gray-500 ml-1">tokens</span>
                            </div>
                            @if($tokenBalance->available_tokens <= 10 && $tokenBalance->available_tokens > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Low Balance
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>
                                <span class="font-medium">{{ $tokenBalance->used_tokens }}</span> tokens
                                @if($tokenBalance->used_tokens > 0)
                                    <div class="text-xs text-gray-400">Total used</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $lastActivity = \DB::table('token_transactions')
                                    ->where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                            @endphp
                            @if($lastActivity)
                                <div>
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($lastActivity->created_at)->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $lastActivity->type)) }}</div>
                                </div>
                            @else
                                <span class="text-gray-400">No activity</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->hasActiveSubscription())
                                @php
                                    $subscription = $user->activeSubscription()->with('plan')->first();
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($subscription->plan->name) }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Free
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.user-tokens.edit', $user) }}" 
                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Manage Tokens
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request()->anyFilled(['search', 'token_filter']))
                                    Try adjusting your search or filters.
                                @else
                                    Get started by adding some users.
                                @endif
                            </p>
                            @if(request()->anyFilled(['search', 'token_filter']))
                                <div class="mt-4">
                                    <a href="{{ route('admin.user-tokens.index') }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Clear all filters
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
