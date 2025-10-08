<x-student-layout>
    <x-slot:title>My Subscription</x-slot>
    
    <div x-data="{ 
        showBillingHistory: false,
        activeTab: 'overview'
    }" x-init="() => { if (typeof darkMode === 'undefined') { darkMode = localStorage.getItem('darkMode') !== 'false'; } }">
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0" :class="darkMode ? 'bg-black/20' : 'bg-gradient-to-br from-[#C8102E]/5 via-transparent to-[#C8102E]/10'"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center shadow-lg shadow-[#C8102E]/30">
                            <i class="fas fa-crown text-white text-2xl"></i>
                        </div>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">
                        My Subscription
                    </h1>
                    <p class="text-lg" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                        Manage your subscription, track usage, and view billing history
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-4">
        <div class="max-w-7xl mx-auto">
            
            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Current Plan -->
                <div class="rounded-xl shadow-lg p-4 lg:p-6 transition-all hover:-translate-y-1" 
                     :class="darkMode ? 'glass border border-white/10 hover:border-[#C8102E]/30' : 'bg-white border border-gray-200 hover:shadow-xl'">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Current Plan</span>
                        <div class="w-10 h-10 rounded-lg bg-[#C8102E]/10 flex items-center justify-center">
                            <i class="fas fa-crown text-[#C8102E]"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                        {{ $activeSubscription ? $activeSubscription->plan->name : 'No Active Plan' }}
                    </p>
                    @if($activeSubscription && $activeSubscription->plan->is_featured)
                        <span class="inline-flex items-center mt-2 px-2 py-1 text-xs rounded-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white">
                            <i class="fas fa-star mr-1"></i>Most Popular
                        </span>
                    @endif
                </div>
                
                <!-- Token Balance -->
                <div class="rounded-xl shadow-lg p-4 lg:p-6 transition-all hover:-translate-y-1" 
                     :class="darkMode ? 'glass border border-white/10 hover:border-amber-500/30' : 'bg-white border border-gray-200 hover:shadow-xl'">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Token Balance</span>
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center">
                            <i class="fas fa-coins text-amber-500"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $tokenBalance }}</p>
                    @if(Route::has('student.tokens.purchase'))
                        <a href="{{ route('student.tokens.purchase') }}" class="text-xs text-amber-500 hover:text-amber-600 mt-2 inline-block">
                            <i class="fas fa-plus-circle mr-1"></i>Buy More
                        </a>
                    @endif
                </div>
                
                <!-- Tests This Month -->
                <div class="rounded-xl shadow-lg p-4 lg:p-6 transition-all hover:-translate-y-1" 
                     :class="darkMode ? 'glass border border-white/10 hover:border-blue-500/30' : 'bg-white border border-gray-200 hover:shadow-xl'">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Tests This Month</span>
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-blue-500"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $user->tests_taken_this_month }}</p>
                    <p class="text-xs mt-2" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                        @php
                            $limit = $user->getFeatureLimit('mock_tests_per_month');
                        @endphp
                        @if($limit === 'unlimited')
                            Unlimited
                        @elseif(is_numeric($limit))
                            of {{ $limit }} available
                        @else
                            No limit set
                        @endif
                    </p>
                </div>
                
                <!-- Referral Earnings -->
                <div class="rounded-xl shadow-lg p-4 lg:p-6 transition-all hover:-translate-y-1" 
                     :class="darkMode ? 'glass border border-white/10 hover:border-green-500/30' : 'bg-white border border-gray-200 hover:shadow-xl'">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Referral Earnings</span>
                        <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <i class="fas fa-gift text-green-500"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">৳{{ number_format($user->referral_balance, 0) }}</p>
                    <a href="{{ route('student.referrals.index') }}" class="text-xs text-green-500 hover:text-green-600 mt-2 inline-block">
                        <i class="fas fa-share mr-1"></i>Refer & Earn
                    </a>
                </div>
            </div>

            @if($activeSubscription)
            <!-- Active Subscription Details -->
            <div class="rounded-2xl shadow-xl mb-8 relative overflow-hidden" 
                 :class="darkMode ? 'glass border border-[#C8102E]/30' : 'bg-white border border-gray-100'">
                
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute w-96 h-96 -top-48 -right-48 bg-[#C8102E] rounded-full blur-3xl"></div>
                    <div class="absolute w-64 h-64 -bottom-32 -left-32 bg-[#A00E27] rounded-full blur-3xl"></div>
                </div>
                
                <div class="relative p-6 lg:p-8">
                    <!-- Subscription Header -->
                    <div class="flex flex-col lg:flex-row justify-between gap-6 mb-8">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                    {{ $activeSubscription->plan->name }} Plan
                                </h2>
                                <span class="px-3 py-1 text-xs rounded-full font-medium"
                                      :class="{{ $activeSubscription->isActive() ? 'true' : 'false' }} 
                                        ? (darkMode ? 'glass text-green-400 border border-green-400/30' : 'bg-green-100 text-green-700 border border-green-200') 
                                        : (darkMode ? 'glass text-red-400 border border-red-400/30' : 'bg-red-100 text-red-700 border border-red-200')">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ ucfirst($activeSubscription->status) }}
                                </span>
                            </div>
                            
                            <p class="mb-6" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                                {{ $activeSubscription->plan->description }}
                            </p>
                            
                            <!-- Subscription Info Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Valid Until -->
                                <div class="rounded-lg p-4" :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-[#C8102E]/10 flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-[#C8102E]"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Valid Until</p>
                                            <p class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                {{ $activeSubscription->ends_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Days Remaining -->
                                <div class="rounded-lg p-4" :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                            <i class="fas fa-hourglass-half text-blue-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Time Remaining</p>
                                            <p class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                {{ $activeSubscription->days_remaining }} days
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Auto Renewal -->
                                <div class="rounded-lg p-4" :class="darkMode ? 'glass border border-white/10' : 'bg-gray-50 border border-gray-200'">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg {{ $activeSubscription->auto_renew ? 'bg-green-500/10' : 'bg-yellow-500/10' }} flex items-center justify-center">
                                                <i class="fas {{ $activeSubscription->auto_renew ? 'fa-sync' : 'fa-pause' }} {{ $activeSubscription->auto_renew ? 'text-green-500' : 'text-yellow-500' }}"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Auto Renewal</p>
                                                <p class="font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                    {{ $activeSubscription->auto_renew ? 'Enabled' : 'Disabled' }}
                                                </p>
                                            </div>
                                        </div>
                                        @if($activeSubscription->auto_renew && !$activeSubscription->plan->is_free)
                                            <form action="{{ route('subscription.toggle-auto-renew') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs px-2 py-1 rounded transition-all"
                                                        :class="darkMode ? 'text-red-400 hover:bg-red-400/10' : 'text-red-600 hover:bg-red-50'">
                                                    Turn Off
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mt-6">
                                @php
                                    $totalDays = $activeSubscription->starts_at->diffInDays($activeSubscription->ends_at);
                                    $daysUsed = $activeSubscription->starts_at->diffInDays(now());
                                    $percentageUsed = $totalDays > 0 ? ($daysUsed / $totalDays) * 100 : 0;
                                @endphp
                                <div class="flex justify-between text-sm mb-2">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Subscription Progress</span>
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ round($percentageUsed) }}% completed</span>
                                </div>
                                <div class="w-full h-3 rounded-full overflow-hidden" :class="darkMode ? 'bg-white/10' : 'bg-gray-200'">
                                    <div class="h-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full transition-all duration-500" 
                                         style="width: {{ min($percentageUsed, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing Card -->
                        <div class="lg:w-80">
                            <div class="rounded-xl p-6 text-center" 
                                 :class="darkMode ? 'glass border border-[#C8102E]/30' : 'bg-gradient-to-br from-[#C8102E]/5 to-[#C8102E]/10 border border-[#C8102E]/20'">
                                <p class="text-sm mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Monthly Price</p>
                                <div class="flex items-center justify-center mb-6">
                                    <span class="text-2xl font-bold" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">৳</span>
                                    <span class="text-5xl font-bold text-[#C8102E] mx-1">{{ number_format($activeSubscription->plan->price, 0) }}</span>
                                    <span class="text-sm self-end mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">/month</span>
                                </div>
                                
                                <div class="space-y-3">
                                    <a href="{{ route('subscription.plans') }}" 
                                       class="block w-full px-6 py-3 bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium rounded-lg hover:from-[#A00E27] hover:to-[#8A0C20] transition-all shadow-lg hover:shadow-xl">
                                        <i class="fas fa-exchange-alt mr-2"></i>Upgrade Plan
                                    </a>
                                    
                                    @if(!$activeSubscription->plan->is_free && !$activeSubscription->isCancelled())
                                        <form action="{{ route('subscription.cancel') }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to cancel your subscription? You\'ll still have access until {{ $activeSubscription->ends_at->format('d M Y') }}.')">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full px-6 py-3 rounded-lg font-medium transition-all"
                                                    :class="darkMode ? 'glass text-red-400 hover:bg-red-400/10 border border-red-400/30' : 'bg-white text-red-600 hover:bg-red-50 border border-red-200'">
                                                <i class="fas fa-times-circle mr-2"></i>Cancel Plan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Features Grid -->
                    <div class="pt-6 border-t" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                        <h3 class="font-semibold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Included Features
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @php
                                // Get features via direct query to avoid column/relationship conflict
                                $planFeatures = collect([]);
                                if ($activeSubscription && $activeSubscription->plan) {
                                    $planFeatures = \App\Models\SubscriptionFeature::query()
                                        ->join('plan_feature', 'subscription_features.id', '=', 'plan_feature.feature_id')
                                        ->where('plan_feature.plan_id', $activeSubscription->plan->id)
                                        ->select('subscription_features.*', 'plan_feature.value', 'plan_feature.limit')
                                        ->get();
                                }
                            @endphp
                            @if($planFeatures->count() > 0)
                                @foreach($planFeatures as $feature)
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-check text-green-500 mt-1 flex-shrink-0"></i>
                                        <div class="flex-1">
                                            <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                {{ $feature->name }}
                                                @if($feature->value && $feature->value !== 'true')
                                                    <span class="font-semibold text-[#C8102E]">({{ $feature->value }})</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-3 text-center py-4">
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        No features configured for this plan
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                    Usage Statistics
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Mock Tests -->
                    <div class="rounded-xl p-6 transition-all hover:scale-105" 
                         :class="darkMode ? 'glass border border-white/10 hover:border-blue-500/30' : 'bg-white border border-gray-200 hover:shadow-lg'">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                <i class="fas fa-clipboard-check text-blue-500 text-xl"></i>
                            </div>
                            @php
                                $testLimit = $user->getFeatureLimit('mock_tests_per_month');
                                $percentage = 0;
                                if (is_numeric($testLimit) && $testLimit > 0) {
                                    $percentage = ($user->tests_taken_this_month / $testLimit) * 100;
                                }
                            @endphp
                        </div>
                        <p class="text-2xl font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            {{ $user->tests_taken_this_month }}
                        </p>
                        <p class="text-sm mb-3" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Mock Tests Taken</p>
                        
                        @if(is_numeric($testLimit) && $testLimit > 0)
                            <div class="space-y-2">
                                <div class="w-full h-2 rounded-full overflow-hidden" :class="darkMode ? 'bg-white/10' : 'bg-gray-200'">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-500" 
                                         style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <p class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                                    {{ $testLimit - $user->tests_taken_this_month }} remaining this month
                                </p>
                            </div>
                        @elseif($testLimit === 'unlimited')
                            <p class="text-xs text-green-500 font-medium">
                                <i class="fas fa-infinity mr-1"></i>Unlimited
                            </p>
                        @endif
                    </div>
                    
                    <!-- AI Evaluations -->
                    <div class="rounded-xl p-6 transition-all hover:scale-105" 
                         :class="darkMode ? 'glass border border-white/10 hover:border-purple-500/30' : 'bg-white border border-gray-200 hover:shadow-lg'">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center">
                                <i class="fas fa-robot text-purple-500 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            {{ $user->ai_evaluations_used }}
                        </p>
                        <p class="text-sm mb-3" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">AI Evaluations</p>
                        <p class="text-xs font-medium"
                           :class="{{ $user->hasFeature('ai_writing_evaluation') || $user->hasFeature('ai_speaking_evaluation') ? 'true' : 'false' }}
                                ? 'text-green-500' 
                                : (darkMode ? 'text-gray-500' : 'text-gray-400')">
                            <i class="fas {{ $user->hasFeature('ai_writing_evaluation') || $user->hasFeature('ai_speaking_evaluation') ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $user->hasFeature('ai_writing_evaluation') || $user->hasFeature('ai_speaking_evaluation') 
                                ? 'Available' 
                                : 'Not in plan' }}
                        </p>
                    </div>
                    
                    <!-- Human Evaluations -->
                    <div class="rounded-xl p-6 transition-all hover:scale-105" 
                         :class="darkMode ? 'glass border border-white/10 hover:border-green-500/30' : 'bg-white border border-gray-200 hover:shadow-lg'">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                                <i class="fas fa-user-check text-green-500 text-xl"></i>
                            </div>
                        </div>
                        @php
                            $humanEvaluations = \App\Models\HumanEvaluationRequest::where('student_id', $user->id)
                                ->whereHas('humanEvaluation')
                                ->count();
                        @endphp
                        <p class="text-2xl font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            {{ $humanEvaluations }}
                        </p>
                        <p class="text-sm mb-3" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Human Evaluations</p>
                        <p class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                            By expert teachers
                        </p>
                    </div>
                    
                    <!-- Tokens -->
                    <div class="rounded-xl p-6 transition-all hover:scale-105" 
                         :class="darkMode ? 'glass border border-white/10 hover:border-amber-500/30' : 'bg-white border border-gray-200 hover:shadow-lg'">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg bg-amber-500/10 flex items-center justify-center">
                                <i class="fas fa-coins text-amber-500 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-2xl font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            {{ $tokenBalance }}
                        </p>
                        <p class="text-sm mb-3" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Evaluation Tokens</p>
                        @if(Route::has('student.tokens.purchase'))
                            <a href="{{ route('student.tokens.purchase') }}" class="text-xs text-amber-500 hover:text-amber-600 font-medium">
                                <i class="fas fa-plus-circle mr-1"></i>Buy More
                            </a>
                        @else
                            <span class="text-xs text-amber-500/50">
                                <i class="fas fa-info-circle mr-1"></i>Coming Soon
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            @else
            <!-- No Active Subscription -->
            <div class="rounded-2xl shadow-xl p-12 mb-8 text-center" 
                 :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-[#C8102E]/10 to-[#A00E27]/10 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-crown text-5xl text-[#C8102E]/50"></i>
                </div>
                <h2 class="text-2xl font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    No Active Subscription
                </h2>
                <p class="max-w-md mx-auto mb-8" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                    Unlock all features and accelerate your IELTS preparation with our premium subscription plans.
                </p>
                <a href="{{ route('subscription.plans') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white font-medium rounded-xl hover:from-[#A00E27] hover:to-[#8A0C20] transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-rocket mr-2"></i>View Available Plans
                </a>
            </div>
            @endif

            <!-- Billing History -->
            <div class="rounded-2xl shadow-xl" 
                 :class="darkMode ? 'glass border border-white/10' : 'bg-white border border-gray-200'">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <i class="fas fa-file-invoice-dollar text-green-500 mr-2"></i>
                            Billing History
                        </h2>
                        <button @click="showBillingHistory = !showBillingHistory" 
                                class="text-sm px-4 py-2 rounded-lg transition-all"
                                :class="darkMode ? 'glass text-gray-300 hover:text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                            <i class="fas" :class="showBillingHistory ? 'fa-chevron-up' : 'fa-chevron-down'" class="mr-2"></i>
                            <span x-text="showBillingHistory ? 'Hide' : 'Show'"></span>
                        </button>
                    </div>
                    
                    <!-- Billing Table -->
                    <div x-show="showBillingHistory" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="mt-6">
                        @if($transactions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                            <th class="text-left py-3 px-4 text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Date</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Description</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Method</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Amount</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Status</th>
                                            <th class="text-center py-3 px-4 text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr class="border-b transition-colors" 
                                            :class="darkMode ? 'border-white/5 hover:bg-white/5' : 'border-gray-100 hover:bg-gray-50'">
                                            <td class="py-4 px-4 text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                                                {{ $transaction->created_at->format('d M Y') }}
                                            </td>
                                            <td class="py-4 px-4 text-sm font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                {{ $transaction->subscription && $transaction->subscription->plan 
                                                    ? $transaction->subscription->plan->name . ' Plan' 
                                                    : 'Subscription Payment' }}
                                            </td>
                                            <td class="py-4 px-4 text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                                                @if($transaction->payment_method === 'bkash')
                                                    <span class="inline-flex items-center">
                                                        <span class="w-16 h-5 bg-[#e2136e] rounded px-1 text-white text-xs font-bold">bKash</span>
                                                    </span>
                                                @elseif($transaction->payment_method === 'nagad')
                                                    <span class="inline-flex items-center">
                                                        <span class="w-16 h-5 bg-[#f37021] rounded px-1 text-white text-xs font-bold">Nagad</span>
                                                    </span>
                                                @elseif($transaction->payment_method === 'stripe')
                                                    <span class="inline-flex items-center">
                                                        <i class="fab fa-stripe text-purple-600 mr-1"></i>Stripe
                                                    </span>
                                                @elseif($transaction->payment_method === 'free')
                                                    <span class="text-green-500">
                                                        <i class="fas fa-gift mr-1"></i>Free
                                                    </span>
                                                @else
                                                    <span class="capitalize">{{ str_replace('_', ' ', $transaction->payment_method ?? 'N/A') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-sm font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                ৳{{ number_format($transaction->amount, 0) }}
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="px-2 py-1 text-xs rounded-full font-medium inline-flex items-center"
                                                      :class="darkMode ? (
                                                        '{{ $transaction->status }}' === 'completed' ? 'glass text-green-400 border border-green-400/30' :
                                                        '{{ $transaction->status }}' === 'pending' ? 'glass text-yellow-400 border border-yellow-400/30' :
                                                        'glass text-red-400 border border-red-400/30'
                                                      ) : (
                                                        '{{ $transaction->status }}' === 'completed' ? 'bg-green-100 text-green-700 border border-green-200' :
                                                        '{{ $transaction->status }}' === 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' :
                                                        'bg-red-100 text-red-700 border border-red-200'
                                                      )">
                                                    <i class="fas fa-circle text-xs mr-1"></i>
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($transaction->isSuccessful())
                                                    <div class="flex items-center justify-center gap-3">
                                                        <a href="{{ route('subscription.invoice.download', $transaction) }}" 
                                                           class="text-blue-500 hover:text-blue-600 transition-colors"
                                                           title="Download Invoice">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <a href="{{ route('subscription.invoice', $transaction) }}" 
                                                           target="_blank"
                                                           class="text-[#C8102E] hover:text-[#A00E27] transition-colors"
                                                           title="View Invoice">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-center block">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-inbox text-4xl mb-4" :class="darkMode ? 'text-gray-600' : 'text-gray-300'"></i>
                                <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No billing history yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="mt-8 rounded-xl p-6 text-center" 
                 :class="darkMode ? 'glass border border-white/10' : 'bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200'">
                <h3 class="font-semibold mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Need Help with Your Subscription?
                </h3>
                <p class="text-sm mb-4" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                    Our support team is here to help you with any questions or issues.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="#" onclick="Tawk_API.maximize(); return false;" 
                       class="inline-flex items-center px-6 py-2 rounded-lg transition-all"
                       :class="darkMode ? 'glass text-white hover:bg-white/10' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'">
                        <i class="fas fa-comments mr-2"></i>Live Chat
                    </a>
                    <a href="mailto:support@ieltsjourney.com" 
                       class="inline-flex items-center px-6 py-2 rounded-lg bg-[#C8102E] text-white hover:bg-[#A00E27] transition-all">
                        <i class="fas fa-envelope mr-2"></i>Email Support
                    </a>
                </div>
            </div>
        </div>
    </section>
    </div>
    
    @push('scripts')
    <script>
        // You can add any additional JavaScript here if needed
    </script>
    @endpush
</x-student-layout>