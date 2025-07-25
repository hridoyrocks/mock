<x-admin-layout>
    <x-slot name="title">User Details - {{ $user->name }}</x-slot>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-4 py-5 sm:p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <img class="h-16 w-16 rounded-full" src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" alt="{{ $user->name }}">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $user->role_badge_color }}">
                    {{ $user->role }}
                </span>
                @if($user->isBanned())
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        Banned
                    </span>
                @else
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                @endif
                @if($user->email_verified_at)
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        Email Verified
                    </span>
                @else
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Email Unverified
                    </span>
                @endif
                @if($user->phone_verified_at)
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        Phone Verified
                    </span>
                @endif
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6" x-data="{ activeTab: 'overview' }">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Overview
                    </button>
                    <button @click="activeTab = 'subscription'" :class="activeTab === 'subscription' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Subscription
                    </button>
                    <button @click="activeTab = 'attempts'" :class="activeTab === 'attempts' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Test Attempts
                    </button>
                    <button @click="activeTab = 'activity'" :class="activeTab === 'activity' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Activity Log
                    </button>
                    @if($user->teacher)
                    <button @click="activeTab = 'teacher'" :class="activeTab === 'teacher' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Teacher Info
                    </button>
                    @endif
                </nav>

                <!-- Tab Content -->
                <div class="mt-6">
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Country</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($user->country_name)
                                        <div class="flex items-center">
                                            @if($user->country_flag)
                                            <img src="{{ $user->country_flag }}" alt="{{ $user->country_name }}" class="h-4 w-6 mr-2">
                                            @endif
                                            {{ $user->country_name }}
                                        </div>
                                    @else
                                        Not provided
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">City</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->city ?? 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Login Method</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->login_method ?? 'email') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Joined</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y h:i A') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if(\Schema::hasTable('authentication_log') && isset($user->authenticationLogs) && $user->authenticationLogs->first())
                                        {{ $user->authenticationLogs->first()->login_at->format('M d, Y h:i A') }}
                                    @else
                                        Never
                                    @endif
                                </dd>
                            </div>
                            @if($user->referredBy)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Referred By</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.users.show', $user->referredBy) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $user->referredBy->name }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Referral Code</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $user->referral_code }}</dd>
                            </div>
                            @if($user->isBanned())
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Ban Details</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <p class="text-red-600">Banned on {{ $user->banned_at->format('M d, Y h:i A') }}</p>
                                    <p class="mt-1">Reason: {{ $user->ban_reason }}</p>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Subscription Tab -->
                    <div x-show="activeTab === 'subscription'" x-cloak>
                        @if($user->subscriptions->count() > 0)
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ends</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auto Renew</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->subscriptions as $subscription)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $subscription->plan->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($subscription->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $subscription->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $subscription->ends_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $subscription->auto_renew ? 'Yes' : 'No' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No subscription history found.</p>
                        @endif

                        @if($user->evaluationTokens)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Evaluation Tokens</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Available Tokens</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->evaluationTokens->balance }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Purchased</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->evaluationTokens->total_purchased }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Used</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->evaluationTokens->total_used }}</dd>
                                </div>
                            </dl>
                        </div>
                        @endif
                    </div>

                    <!-- Test Attempts Tab -->
                    <div x-show="activeTab === 'attempts'" x-cloak>
                        @if($user->studentAttempts->count() > 0)
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->studentAttempts as $attempt)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $attempt->testSet->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $attempt->testSet->section->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($attempt->score !== null)
                                                    {{ $attempt->score }}/{{ $attempt->total_questions }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y h:i A') : 'In Progress' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.attempts.show', $attempt) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No test attempts found.</p>
                        @endif
                    </div>

                    <!-- Activity Log Tab -->
                    <div x-show="activeTab === 'activity'" x-cloak>
                        @if(\Schema::hasTable('authentication_log') && $user->authenticationLogs && $user->authenticationLogs->count() > 0)
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->authenticationLogs as $log)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $log->login_successful ? 'Login' : 'Failed Login' }}
                                                @if($log->logout_at)
                                                    / Logout
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->ip_address }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                {{ $log->user_agent }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->login_at->format('M d, Y h:i A') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No activity logs found.</p>
                        @endif
                    </div>

                    <!-- Teacher Tab -->
                    @if($user->teacher)
                    <div x-show="activeTab === 'teacher'" x-cloak>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Bio</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->teacher->bio ?: 'No bio provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Hourly Rate</dt>
                                <dd class="mt-1 text-sm text-gray-900">৳{{ number_format($user->teacher->hourly_rate, 2) }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Availability</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->teacher->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $user->teacher->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Total Evaluations</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->teacher->completed_evaluations }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Average Rating</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($user->teacher->average_rating)
                                        {{ number_format($user->teacher->average_rating, 1) }}/5.0
                                    @else
                                        No ratings yet
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Specializations</dt>
                                <dd class="mt-1">
                                    @if($user->teacher->specializations && count($user->teacher->specializations) > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($user->teacher->specializations as $specialization)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $specialization }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-500">No specializations set</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
