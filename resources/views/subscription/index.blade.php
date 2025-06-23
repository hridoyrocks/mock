@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Subscription</h1>
            <p class="text-gray-600">Manage your subscription and billing</p>
        </div>

        {{-- Current Plan --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Current Plan</h2>
            
            @if($activeSubscription)
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $activeSubscription->plan->name }}</h3>
                            @if($activeSubscription->plan->is_featured)
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Most Popular</span>
                            @endif
                        </div>
                        <p class="text-gray-600 mt-1">{{ $activeSubscription->plan->description }}</p>
                        
                        <div class="mt-4 space-y-2">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Status:</span>
                                <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $activeSubscription->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($activeSubscription->status) }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Expires on:</span> {{ $activeSubscription->ends_at->format('d M Y') }}
                                <span class="text-gray-500">({{ $activeSubscription->days_remaining }} days remaining)</span>
                            </p>
                            @if($activeSubscription->auto_renew)
                                <p class="text-sm text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i> Auto-renewal is enabled
                                </p>
                            @else
                                <p class="text-sm text-yellow-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Auto-renewal is disabled
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900">৳{{ number_format($activeSubscription->plan->price, 0) }}</p>
                        <p class="text-gray-600">per month</p>
                        
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('subscription.plans') }}" class="block w-full bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Change Plan
                            </a>
                            @if(!$activeSubscription->plan->is_free && !$activeSubscription->isCancelled())
                                <form action="{{ route('subscription.cancel') }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your subscription?')">
                                    @csrf
                                    <button type="submit" class="w-full bg-white text-red-600 border border-red-600 px-4 py-2 rounded-lg hover:bg-red-50 transition">
                                        Cancel Subscription
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Features --}}
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-medium text-gray-900 mb-3">Included Features:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($activeSubscription->plan->features as $feature)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                {{ $feature->name }}
                                @if($feature->pivot->value && $feature->pivot->value !== 'true')
                                    <span class="ml-1 font-medium">({{ $feature->pivot->value }})</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-user-slash text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 mb-4">You don't have an active subscription</p>
                    <a href="{{ route('subscription.plans') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        View Plans
                    </a>
                </div>
            @endif
        </div>

        {{-- Usage Statistics --}}
        @if($activeSubscription)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Usage This Month</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Mock Tests Taken</p>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-900">{{ $user->tests_taken_this_month }}</span>
                        @php
                            $testLimit = $user->getFeatureLimit('mock_tests_per_month');
                        @endphp
                        @if($testLimit !== 'unlimited')
                            <span class="text-gray-600 ml-1">/ {{ $testLimit }}</span>
                        @else
                            <span class="text-green-600 ml-2 text-sm">Unlimited</span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600 mb-1">AI Evaluations Used</p>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-900">{{ $user->ai_evaluations_used }}</span>
                        @if($user->hasFeature('ai_writing_evaluation') || $user->hasFeature('ai_speaking_evaluation'))
                            <span class="text-green-600 ml-2 text-sm">Available</span>
                        @else
                            <span class="text-gray-400 ml-2 text-sm">Not available</span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600 mb-1">Days Until Renewal</p>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-900">{{ $activeSubscription->days_remaining }}</span>
                        <span class="text-gray-600 ml-1">days</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Recent Transactions --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Recent Transactions</h2>
                <a href="#" class="text-blue-600 hover:text-blue-700 text-sm">View All</a>
            </div>
            
            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2 text-sm font-medium text-gray-700">Date</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-700">Description</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-700">Method</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-700">Amount</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-700">Status</th>
                                <th class="text-left py-2 text-sm font-medium text-gray-700">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr class="border-b">
                                <td class="py-3 text-sm text-gray-900">{{ $transaction->created_at->format('d M Y') }}</td>
                                <td class="py-3 text-sm text-gray-900">
                                    @if($transaction->subscription)
                                        {{ $transaction->subscription->plan->name }} Plan
                                    @else
                                        Subscription Payment
                                    @endif
                                </td>
                                <td class="py-3 text-sm text-gray-900">
                                    <span class="capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</span>
                                </td>
                                <td class="py-3 text-sm font-medium text-gray-900">৳{{ number_format($transaction->amount, 0) }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($transaction->status === 'completed') bg-green-100 text-green-800
                                        @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($transaction->isSuccessful())
                                        <a href="{{ route('subscription.invoice', $transaction) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center py-4">No transactions yet</p>
            @endif
        </div>

        {{-- Subscription History --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-4">Subscription History</h2>
            
            @if($subscriptionHistory->count() > 0)
                <div class="space-y-4">
                    @foreach($subscriptionHistory as $subscription)
                    <div class="border rounded-lg p-4 {{ $subscription->isActive() ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $subscription->plan->name }} Plan</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $subscription->starts_at->format('d M Y') }} - {{ $subscription->ends_at->format('d M Y') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Status: 
                                    <span class="font-medium {{ $subscription->isActive() ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                    @if($subscription->cancelled_at)
                                        <span class="text-red-600 ml-2">(Cancelled on {{ $subscription->cancelled_at->format('d M Y') }})</span>
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">৳{{ number_format($subscription->plan->price, 0) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $subscriptionHistory->links() }}
                </div>
            @else
                <p class="text-gray-600 text-center py-4">No subscription history</p>
            @endif
        </div>
    </div>
</div>
@endsection