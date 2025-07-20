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
                    <p class="text-gray-300 text-lg">Manage your subscription, usage statistics and billing</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Quick Stats Overview -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-line text-purple-400 mr-2"></i>
                    Quick Overview
                </h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Active Plan -->
                    <div class="glass rounded-xl p-4 border border-white/10 hover:border-purple-500/30 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-400">Current Plan</span>
                            <i class="fas fa-crown text-purple-400"></i>
                        </div>
                        <p class="text-2xl font-bold text-white">
                            {{ $activeSubscription ? $activeSubscription->plan->name : 'No Active Plan' }}
                        </p>
                    </div>
                    
                    <!-- Token Balance -->
                    <div class="glass rounded-xl p-4 border border-white/10 hover:border-amber-500/30 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-400">Token Balance</span>
                            <i class="fas fa-coins text-amber-400"></i>
                        </div>
                        <p class="text-2xl font-bold text-white">{{ $tokenBalance }}</p>
                    </div>
                    
                    <!-- Tests This Month -->
                    <div class="glass rounded-xl p-4 border border-white/10 hover:border-blue-500/30 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-400">Tests This Month</span>
                            <i class="fas fa-clipboard-check text-blue-400"></i>
                        </div>
                        <p class="text-2xl font-bold text-white">{{ $user->tests_taken_this_month }}</p>
                    </div>
                    
                    <!-- Referral Balance -->
                    <div class="glass rounded-xl p-4 border border-white/10 hover:border-pink-500/30 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-400">Referral Earnings</span>
                            <i class="fas fa-gift text-pink-400"></i>
                        </div>
                        <p class="text-2xl font-bold text-white">৳{{ number_format($user->referral_balance, 0) }}</p>
                    </div>
                </div>
            </div>

            @if($activeSubscription)
            <!-- Current Subscription Details -->
            <div class="glass rounded-2xl p-8 mb-8 border border-purple-500/30 relative overflow-hidden">
                <!-- Background Effects -->
                <div class="absolute inset-0">
                    <div class="absolute w-64 h-64 -top-32 -right-32 bg-purple-500 rounded-full opacity-10 blur-3xl"></div>
                    <div class="absolute w-48 h-48 -bottom-24 -left-24 bg-pink-500 rounded-full opacity-10 blur-3xl"></div>
                </div>
                
                <div class="relative">
                    <div class="flex flex-col lg:flex-row justify-between gap-8">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-4">
                                <h2 class="text-3xl font-bold text-white">{{ $activeSubscription->plan->name }} Plan</h2>
                                @if($activeSubscription->plan->is_featured)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                        Most Popular
                                    </span>
                                @endif
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    {{ $activeSubscription->isActive() 
                                        ? 'bg-green-500/20 text-green-400 border border-green-500/50' 
                                        : 'bg-red-500/20 text-red-400 border border-red-500/50' }}">
                                    {{ ucfirst($activeSubscription->status) }}
                                </span>
                            </div>
                            
                            <p class="text-gray-300 mb-6">{{ $activeSubscription->plan->description }}</p>
                            
                            <!-- Subscription Info Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="glass rounded-lg p-4 border border-white/5">
                                    <span class="text-sm text-gray-400">Valid Until</span>
                                    <p class="text-white font-medium">{{ $activeSubscription->ends_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $activeSubscription->days_remaining }} days remaining</p>
                                </div>
                                
                                <div class="glass rounded-lg p-4 border border-white/5">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-sm text-gray-400">Auto Renewal</span>
                                            <p class="text-white font-medium flex items-center">
                                                @if($activeSubscription->auto_renew)
                                                    <i class="fas fa-check-circle text-green-400 mr-2"></i>Enabled
                                                @else
                                                    <i class="fas fa-times-circle text-yellow-400 mr-2"></i>Disabled
                                                @endif
                                            </p>
                                        </div>
                                        @if($activeSubscription->auto_renew && !$activeSubscription->plan->is_free)
                                            <form action="{{ route('subscription.toggle-auto-renew') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 text-xs bg-red-500/20 text-red-400 border border-red-500/50 rounded-lg hover:bg-red-500/30 transition-all">
                                                    Turn Off
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-400">Subscription Progress</span>
                                    <span class="text-gray-400">{{ $activeSubscription->days_remaining }} days left</span>
                                </div>
                                <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden">
                                    @php
                                        $totalDays = $activeSubscription->starts_at->diffInDays($activeSubscription->ends_at);
                                        $daysUsed = $activeSubscription->starts_at->diffInDays(now());
                                        $percentageUsed = ($daysUsed / $totalDays) * 100;
                                    @endphp
                                    <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-500" 
                                         style="width: {{ min($percentageUsed, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing and Actions -->
                        <div class="lg:w-80">
                            <div class="glass rounded-xl p-6 border border-white/10 text-center">
                                <p class="text-sm text-gray-400 mb-2">Monthly Price</p>
                                <p class="text-5xl font-bold text-white mb-6">৳{{ number_format($activeSubscription->plan->price, 0) }}</p>
                                
                                <div class="space-y-3">
                                    <a href="{{ route('subscription.plans') }}" 
                                       class="block w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl neon-purple">
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
                    </div>
                    
                    <!-- Features List -->
                    <div class="mt-8 pt-8 border-t border-white/10">
                        <h3 class="font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            Included Features
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($activeSubscription->plan->features as $feature)
                                <div class="flex items-center text-gray-300">
                                    <i class="fas fa-check-circle text-green-400 mr-3 flex-shrink-0"></i>
                                    <span>{{ $feature->name }}</span>
                                    @if($feature->pivot->value && $feature->pivot->value !== 'true')
                                        <span class="ml-2 text-purple-400 font-medium">({{ $feature->pivot->value }})</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-blue-400 mr-2"></i>
                    Usage Statistics
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Mock Tests -->
                    <div class="glass rounded-2xl p-6 border border-white/10 hover:border-blue-500/30 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-clipboard-check text-blue-400 text-2xl"></i>
                            @php
                                $testLimit = $user->getFeatureLimit('mock_tests_per_month');
                                $percentage = $testLimit === 'unlimited' ? 0 : ($user->tests_taken_this_month / $testLimit) * 100;
                            @endphp
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">{{ $user->tests_taken_this_month }}</p>
                        <p class="text-sm text-gray-400">Mock Tests Taken</p>
                        @if($testLimit !== 'unlimited')
                            <p class="text-xs text-gray-500 mt-1">of {{ $testLimit }} monthly limit</p>
                            <div class="mt-3 w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" 
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                        @else
                            <p class="text-xs text-green-400 mt-1">Unlimited</p>
                        @endif
                    </div>
                    
                    <!-- AI Evaluations -->
                    <div class="glass rounded-2xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-robot text-purple-400 text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">{{ $user->ai_evaluations_used }}</p>
                        <p class="text-sm text-gray-400">AI Evaluations</p>
                        <p class="text-xs mt-1
                            {{ $user->hasFeature('ai_writing_evaluation') || $user->hasFeature('ai_speaking_evaluation') 
                                ? 'text-green-400' 
                                : 'text-gray-500' }}">
                            {{ $user->hasFeature('ai_writing_evaluation') || $user->hasFeature('ai_speaking_evaluation') 
                                ? 'Available in your plan' 
                                : 'Not available' }}
                        </p>
                    </div>
                    
                    <!-- Human Evaluations -->
                    <div class="glass rounded-2xl p-6 border border-white/10 hover:border-green-500/30 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-user-check text-green-400 text-2xl"></i>
                        </div>
                        @php
                            $humanEvaluations = \App\Models\HumanEvaluationRequest::where('student_id', $user->id)
                                ->whereHas('humanEvaluation')
                                ->count();
                        @endphp
                        <p class="text-3xl font-bold text-white mb-1">{{ $humanEvaluations }}</p>
                        <p class="text-sm text-gray-400">Human Evaluations</p>
                        <p class="text-xs text-gray-500 mt-1">Completed by teachers</p>
                    </div>
                    
                    <!-- Tokens Available -->
                    <div class="glass rounded-2xl p-6 border border-white/10 hover:border-amber-500/30 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-coins text-amber-400 text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">{{ $tokenBalance }}</p>
                        <p class="text-sm text-gray-400">Evaluation Tokens</p>
                        <a href="{{ route('student.tokens.purchase') }}" class="text-xs text-amber-400 hover:text-amber-300 mt-1 inline-block">
                            <i class="fas fa-plus-circle mr-1"></i>Buy More
                        </a>
                    </div>
                </div>
            </div>
            
            @else
            <!-- No Active Subscription -->
            <div class="glass rounded-2xl p-12 mb-8 border border-white/10 text-center">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-crown text-5xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-4">No Active Subscription</h2>
                <p class="text-gray-400 mb-8 max-w-md mx-auto">
                    Choose a subscription plan to unlock all features and start your IELTS preparation journey.
                </p>
                <a href="{{ route('subscription.plans') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl neon-purple">
                    <i class="fas fa-rocket mr-2"></i>View Available Plans
                </a>
            </div>
            @endif

            <!-- Billing History -->
            <div class="glass rounded-2xl p-6 border border-white/10">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-invoice-dollar text-green-400 mr-2"></i>
                        Billing History
                    </h2>
                </div>
                
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Description</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Payment Method</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Amount</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Status</th>
                                    <th class="text-center py-3 px-4 text-sm font-medium text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-4 text-sm text-gray-300">{{ $transaction->created_at->format('d M Y') }}</td>
                                    <td class="py-4 px-4 text-sm text-white font-medium">
                                        {{ $transaction->subscription && $transaction->subscription->plan 
                                            ? $transaction->subscription->plan->name . ' Plan' 
                                            : 'Subscription Payment' }}
                                    </td>
                                    <td class="py-4 px-4 text-sm text-gray-300">
                                        @if($transaction->payment_method === 'bkash')
                                            <span class="inline-flex items-center">
                                                <img src="/images/payment/bkash.png" alt="bKash" class="h-4 mr-1">
                                                bKash
                                            </span>
                                        @elseif($transaction->payment_method === 'nagad')
                                            <span class="inline-flex items-center">
                                                <img src="/images/payment/nagad.png" alt="Nagad" class="h-4 mr-1">
                                                Nagad
                                            </span>
                                        @elseif($transaction->payment_method === 'stripe')
                                            <span class="inline-flex items-center">
                                                <i class="fab fa-stripe text-purple-400 mr-1"></i>
                                                Stripe
                                            </span>
                                        @elseif($transaction->payment_method === 'free')
                                            <span class="text-green-400">
                                                <i class="fas fa-gift mr-1"></i>
                                                Free
                                            </span>
                                        @else
                                            <span class="capitalize">{{ str_replace('_', ' ', $transaction->payment_method ?? 'N/A') }}</span>
                                        @endif
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
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('subscription.invoice.download', $transaction) }}" 
                                                   class="text-purple-400 hover:text-purple-300 text-sm"
                                                   title="Download Invoice">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('subscription.invoice', $transaction) }}" 
                                                   target="_blank"
                                                   class="text-blue-400 hover:text-blue-300 text-sm"
                                                   title="View Invoice">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-3xl text-gray-600 mb-3"></i>
                        <p class="text-gray-400">No billing history yet</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-student-layout>