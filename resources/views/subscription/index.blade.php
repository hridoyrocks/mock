<x-student-layout>
    <x-slot:title>My Subscription</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white mb-4">
                        <i class="fas fa-crown text-yellow-400 mr-3"></i>
                        My Subscription
                    </h1>
                    <p class="text-gray-300 text-lg">Manage your subscription and billing</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-8">
        <div class="max-w-6xl mx-auto">
            <!-- Current Plan -->
            <div class="glass rounded-2xl p-8 mb-8 border border-purple-500/30 relative overflow-hidden">
                <!-- Background Effects -->
                <div class="absolute inset-0">
                    <div class="absolute w-64 h-64 -top-32 -right-32 bg-purple-500 rounded-full opacity-10 blur-3xl"></div>
                    <div class="absolute w-48 h-48 -bottom-24 -left-24 bg-pink-500 rounded-full opacity-10 blur-3xl"></div>
                </div>
                
                <div class="relative">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-star text-purple-400 mr-3"></i>
                        Current Plan
                    </h2>
                    
                    @if($activeSubscription)
                        <div class="flex flex-col lg:flex-row justify-between gap-8">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <h3 class="text-3xl font-bold text-white">{{ $activeSubscription->plan->name }}</h3>
                                    @if($activeSubscription->plan->is_featured)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                            Most Popular
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-300 mb-6">{{ $activeSubscription->plan->description }}</p>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-400">Status:</span>
                                        <span class="px-3 py-1 text-xs rounded-full font-medium
                                            {{ $activeSubscription->isActive() 
                                                ? 'bg-green-500/20 text-green-400 border border-green-500/50' 
                                                : 'bg-red-500/20 text-red-400 border border-red-500/50' }}">
                                            {{ ucfirst($activeSubscription->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-400">Expires on:</span>
                                        <span class="text-white font-medium">{{ $activeSubscription->ends_at->format('d M Y') }}</span>
                                        <span class="text-gray-500">({{ $activeSubscription->days_remaining }} days remaining)</span>
                                    </div>
                                    
                                    @if($activeSubscription->auto_renew)
                                        <div class="flex items-center text-green-400">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            <span>Auto-renewal is enabled</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-yellow-400">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            <span>Auto-renewal is disabled</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-center lg:text-right">
                                <div class="mb-6">
                                    <p class="text-4xl font-bold text-white">৳{{ number_format($activeSubscription->plan->price, 0) }}</p>
                                    <p class="text-gray-400">per month</p>
                                </div>
                                
                                <div class="space-y-3">
                                    <a href="{{ route('subscription.plans') }}" 
                                       class="block w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl neon-purple text-center">
                                        <i class="fas fa-exchange-alt mr-2"></i>Change Plan
                                    </a>
                                    
                                    @if(!$activeSubscription->plan->is_free && !$activeSubscription->isCancelled())
                                        <form action="{{ route('subscription.cancel') }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your subscription?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full glass text-red-400 px-6 py-3 rounded-xl hover:border-red-500/50 transition-all border border-red-500/30">
                                                <i class="fas fa-times-circle mr-2"></i>Cancel Subscription
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Features Grid -->
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <h4 class="font-medium text-white mb-4 flex items-center">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Included Features
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($activeSubscription->plan->features as $feature)
                                    <div class="glass rounded-lg p-3 border border-white/5 hover:border-purple-500/30 transition-all">
                                        <div class="flex items-center">
                                            <i class="fas fa-check text-green-400 mr-3"></i>
                                            <span class="text-gray-300">{{ $feature->name }}</span>
                                            @if($feature->pivot->value && $feature->pivot->value !== 'true')
                                                <span class="ml-2 text-purple-400 font-medium">({{ $feature->pivot->value }})</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-user-slash text-4xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-400 mb-6">You don't have an active subscription</p>
                            <a href="{{ route('subscription.plans') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl neon-purple">
                                <i class="fas fa-rocket mr-2"></i>View Plans
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usage Statistics -->
            @if($activeSubscription)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Mock Tests Card -->
                <div class="glass rounded-2xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-blue-400 text-xl"></i>
                        </div>
                        @php
                            $testLimit = $user->getFeatureLimit('mock_tests_per_month');
                            $percentage = $testLimit === 'unlimited' ? 0 : ($user->tests_taken_this_month / $testLimit) * 100;
                        @endphp
                    </div>
                    <p class="text-sm text-gray-400 mb-2">Mock Tests Taken</p>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-white">{{ $user->tests_taken_this_month }}</span>
                        @if($testLimit !== 'unlimited')
                            <span class="text-gray-400 ml-1">/ {{ $testLimit }}</span>
                        @else
                            <span class="text-green-400 ml-2 text-sm">Unlimited</span>
                        @endif
                    </div>
                    @if($testLimit !== 'unlimited')
                        <div class="mt-3 w-full h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full transition-all duration-500" 
                                 style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                    @endif
                </div>
                
                <!-- AI Evaluations Card -->
                <div class="glass rounded-2xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                            <i class="fas fa-robot text-purple-400 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-2">AI Evaluations Used</p>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-white">{{ $user->ai_evaluations_used }}</span>
                        @if($user->hasFeature('ai_writing_evaluation') || $user->hasFeature('ai_speaking_evaluation'))
                            <span class="text-green-400 ml-2 text-sm flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>Available
                            </span>
                        @else
                            <span class="text-gray-500 ml-2 text-sm flex items-center">
                                <i class="fas fa-lock mr-1"></i>Not available
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Days Until Renewal Card -->
                <div class="glass rounded-2xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-amber-400 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-2">Days Until Renewal</p>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-white">{{ $activeSubscription->days_remaining }}</span>
                        <span class="text-gray-400 ml-2">days</span>
                    </div>
                    <div class="mt-3 w-full h-2 bg-white/10 rounded-full overflow-hidden">
                        @php
                            $totalDays = $activeSubscription->starts_at->diffInDays($activeSubscription->ends_at);
                            $daysUsed = $activeSubscription->starts_at->diffInDays(now());
                            $percentageUsed = ($daysUsed / $totalDays) * 100;
                        @endphp
                        <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full transition-all duration-500" 
                             style="width: {{ min($percentageUsed, 100) }}%"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Transactions -->
            <div class="glass rounded-2xl p-8 mb-8 border border-white/10">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-receipt text-green-400 mr-3"></i>
                        Recent Transactions
                    </h2>
                    <a href="#" class="text-purple-400 hover:text-purple-300 text-sm flex items-center">
                        View All <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Description</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Method</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Amount</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Status</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-4 text-sm text-gray-300">{{ $transaction->created_at->format('d M Y') }}</td>
                                    <td class="py-4 px-4 text-sm text-white font-medium">
                                        @if($transaction->subscription)
                                            {{ $transaction->subscription->plan->name }} Plan
                                        @else
                                            Subscription Payment
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-sm text-gray-300">
                                        <span class="capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-sm font-bold text-white">৳{{ number_format($transaction->amount, 0) }}</td>
                                    <td class="py-4 px-4">
                                        <span class="px-3 py-1 text-xs rounded-full font-medium
                                            @if($transaction->status === 'completed') bg-green-500/20 text-green-400 border border-green-500/50
                                            @elseif($transaction->status === 'pending') bg-yellow-500/20 text-yellow-400 border border-yellow-500/50
                                            @else bg-red-500/20 text-red-400 border border-red-500/50
                                            @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($transaction->isSuccessful())
                                            <a href="{{ route('subscription.invoice', $transaction) }}" 
                                               class="text-purple-400 hover:text-purple-300 text-sm flex items-center">
                                                <i class="fas fa-download mr-2"></i>Download
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400">No transactions yet</p>
                    </div>
                @endif
            </div>

            <!-- Subscription History -->
            <div class="glass rounded-2xl p-8 border border-white/10">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-history text-blue-400 mr-3"></i>
                    Subscription History
                </h2>
                
                @if($subscriptionHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($subscriptionHistory as $subscription)
                        <div class="glass rounded-xl p-6 border {{ $subscription->isActive() ? 'border-purple-500/50 bg-purple-500/10' : 'border-white/10' }} hover:border-purple-500/30 transition-all">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-white text-lg">{{ $subscription->plan->name }} Plan</h3>
                                    <p class="text-sm text-gray-400 mt-2">
                                        <i class="far fa-calendar mr-2"></i>
                                        {{ $subscription->starts_at->format('d M Y') }} - {{ $subscription->ends_at->format('d M Y') }}
                                    </p>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-sm">Status:</span>
                                        <span class="px-3 py-1 text-xs rounded-full font-medium
                                            {{ $subscription->isActive() 
                                                ? 'bg-green-500/20 text-green-400 border border-green-500/50' 
                                                : 'bg-gray-500/20 text-gray-400 border border-gray-500/50' }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                        @if($subscription->cancelled_at)
                                            <span class="text-red-400 text-sm">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Cancelled on {{ $subscription->cancelled_at->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-white text-xl">৳{{ number_format($subscription->plan->price, 0) }}</p>
                                    <p class="text-gray-400 text-sm">per month</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $subscriptionHistory->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-history text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400">No subscription history</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-student-layout>