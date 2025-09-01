<x-student-layout>
    <x-slot:title>Referral Program</x-slot>

    <div x-data="{ 
        activeTab: 'referrals',
        loading: false
    }" x-init="() => { if (typeof darkMode === 'undefined') { darkMode = localStorage.getItem('darkMode') !== 'false'; } }">
    <div x-cloak>
        
        <!-- Header Section -->
        <section class="relative overflow-hidden">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#C8102E]/5 via-transparent to-[#C8102E]/5 dark:from-[#C8102E]/10 dark:to-[#C8102E]/10"></div>
            
            <div class="relative px-4 sm:px-6 lg:px-8 py-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Title Section -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center shadow-lg shadow-[#C8102E]/30">
                                <i class="fas fa-gift text-white text-xl"></i>
                            </div>
                            <h1 class="text-3xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                Referral Program
                            </h1>
                        </div>
                        
                        <!-- Referral Link Section -->
                        <div class="rounded-xl p-6 border"
                             :class="darkMode ? 'glass border-white/20' : 'bg-white border-gray-200 shadow-sm'">
                            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                                <div class="flex-1">
                                    <h2 class="text-lg font-semibold mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        Earn ৳100 for Each Friend!
                                    </h2>
                                    <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        Share your referral link and earn rewards when your friends complete their first test.
                                    </p>
                                    
                                    <!-- Referral Link -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Your Referral Link</label>
                                        <div class="flex gap-2">
                                            <input 
                                                type="text" 
                                                id="referral-link"
                                                value="{{ $stats['referral_link'] }}" 
                                                readonly 
                                                class="flex-1 px-4 py-2 rounded-lg border transition-colors"
                                                :class="darkMode ? 'glass border-white/20 text-white bg-transparent' : 'bg-gray-50 border-gray-300 text-gray-900'"
                                            >
                                            <button 
                                                onclick="copyReferralLink()" 
                                                class="px-4 py-2 bg-[#C8102E] text-white rounded-lg hover:bg-[#A00E27] transition-all font-medium"
                                            >
                                                <i class="fas fa-copy mr-2"></i>
                                                <span id="copy-btn-text">Copy</span>
                                            </button>
                                        </div>
                                        <p class="text-sm mt-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                            Referral Code: <span class="font-mono font-bold text-[#C8102E]">{{ $stats['referral_code'] }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Balance Display -->
                                <div class="text-center lg:text-right">
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Current Balance</p>
                                    <p class="text-4xl font-bold text-[#C8102E]">৳{{ number_format($stats['current_balance'], 0) }}</p>
                                    @if($stats['can_redeem'])
                                        <p class="text-xs text-green-500 mt-1">Available for redemption</p>
                                    @else
                                        <p class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'" class="mt-1">Min. ৳{{ $stats['min_redemption_amount'] }} to redeem</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <!-- Current Balance -->
                        <div class="rounded-lg border p-4 transition-all hover:shadow-md"
                             :class="darkMode ? 'glass border-white/10 hover:border-[#C8102E]/50' : 'bg-white border-gray-200'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Current Balance</p>
                                    <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">৳{{ number_format($stats['current_balance'], 2) }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                                    <i class="fas fa-wallet text-green-500"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Referrals -->
                        <div class="rounded-lg border p-4 transition-all hover:shadow-md"
                             :class="darkMode ? 'glass border-white/10 hover:border-[#C8102E]/50' : 'bg-white border-gray-200'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Total Referrals</p>
                                    <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $stats['total_referrals'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-users text-blue-500"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Successful -->
                        <div class="rounded-lg border p-4 transition-all hover:shadow-md"
                             :class="darkMode ? 'glass border-white/10 hover:border-[#C8102E]/50' : 'bg-white border-gray-200'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Successful</p>
                                    <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $stats['successful_referrals'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Pending -->
                        <div class="rounded-lg border p-4 transition-all hover:shadow-md"
                             :class="darkMode ? 'glass border-white/10 hover:border-[#C8102E]/50' : 'bg-white border-gray-200'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Pending</p>
                                    <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $stats['pending_referrals'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Redemption Options -->
        <section class="px-4 sm:px-6 lg:px-8 pb-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Redeem for Tokens -->
                    <div class="rounded-xl border overflow-hidden"
                         :class="darkMode ? 'glass border-white/10' : 'bg-white border-gray-200'">
                        <div class="p-6 border-b" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                            <h3 class="text-lg font-semibold flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                <i class="fas fa-coins text-blue-500 mr-2"></i>
                                Redeem for Evaluation Tokens
                            </h3>
                            <p class="text-sm mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Convert balance to tokens for human evaluation
                            </p>
                        </div>
                        <div class="p-6">
                            <div class="rounded-lg p-4 mb-4" :class="darkMode ? 'glass' : 'bg-gray-50'">
                                <div class="flex items-center justify-between">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Conversion Rate</span>
                                    <span class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">৳1 = {{ $stats['tokens_per_taka'] }} Tokens</span>
                                </div>
                            </div>
                            
                            <form id="token-redeem-form">
                                @csrf
                                <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Amount (৳)</label>
                                <div class="flex gap-2 mb-3">
                                    <input 
                                        type="number" 
                                        name="amount"
                                        id="token-redeem-amount"
                                        min="{{ $stats['min_redemption_amount'] }}"
                                        max="{{ $stats['current_balance'] }}"
                                        step="10"
                                        value="{{ $stats['min_redemption_amount'] }}"
                                        class="flex-1 px-3 py-2 rounded-lg border"
                                        :class="darkMode ? 'glass border-white/20 text-white bg-transparent' : 'bg-white border-gray-300 text-gray-900'"
                                        onchange="updateTokenCalculation()"
                                    >
                                    <button 
                                        type="button"
                                        onclick="setMaxTokenAmount()" 
                                        class="px-3 py-2 rounded-lg border transition-all"
                                        :class="darkMode ? 'glass border-white/20 text-white hover:bg-white/10' : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
                                    >
                                        Max
                                    </button>
                                </div>
                                <div class="rounded-lg p-3 mb-4" :class="darkMode ? 'glass border border-blue-500/30' : 'bg-blue-50 border border-blue-200'">
                                    <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        You will receive: <span class="font-bold text-blue-500" id="token-calculation">0 tokens</span>
                                    </p>
                                </div>
                            </form>

                            <button 
                                type="button"
                                onclick="redeemForTokens()"
                                @if(!$stats['can_redeem']) disabled @endif
                                class="w-full px-4 py-2 bg-[#C8102E] text-white rounded-lg hover:bg-[#A00E27] transition-all font-medium disabled:bg-gray-500 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-exchange-alt mr-2"></i>
                                Redeem for Tokens
                            </button>
                            
                            <p class="text-xs text-center mt-3" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                                Minimum redemption: ৳{{ $stats['min_redemption_amount'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Redeem for Subscription -->
                    <div class="rounded-xl border overflow-hidden"
                         :class="darkMode ? 'glass border-white/10' : 'bg-white border-gray-200'">
                        <div class="p-6 border-b" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                            <h3 class="text-lg font-semibold flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                <i class="fas fa-crown text-purple-500 mr-2"></i>
                                Redeem for Premium
                            </h3>
                            <p class="text-sm mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Use balance for premium features
                            </p>
                        </div>
                        <div class="p-6">
                            <form id="subscription-redeem-form">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Select Plan</label>
                                    <select 
                                        name="plan_id"
                                        id="selected-plan"
                                        class="w-full px-3 py-2 rounded-lg border"
                                        :class="darkMode ? 'glass border-white/20 text-white bg-transparent' : 'bg-white border-gray-300 text-gray-900'"
                                        onchange="updateSubscriptionCalculation()"
                                    >
                                        <option value="">Choose a plan</option>
                                        @foreach($subscriptionPlans as $plan)
                                            <option value="{{ $plan['id'] }}" data-daily-price="{{ $plan['daily_price'] }}">
                                                {{ $plan['name'] }} - ৳{{ $plan['daily_price'] }}/day
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4" id="subscription-days-container" style="display: none;">
                                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Number of Days</label>
                                    <div class="flex gap-2 mb-3">
                                        <input 
                                            type="number"
                                            name="days" 
                                            id="subscription-days"
                                            min="1"
                                            max="365"
                                            value="30"
                                            class="flex-1 px-3 py-2 rounded-lg border"
                                            :class="darkMode ? 'glass border-white/20 text-white bg-transparent' : 'bg-white border-gray-300 text-gray-900'"
                                            onchange="updateSubscriptionCalculation()"
                                        >
                                        <div class="flex gap-1">
                                            <button 
                                                type="button"
                                                onclick="setSubscriptionDays(30)" 
                                                class="px-3 py-2 rounded-lg border text-sm transition-all"
                                                :class="darkMode ? 'glass border-white/20 text-white hover:bg-white/10' : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
                                            >
                                                30d
                                            </button>
                                            <button 
                                                type="button"
                                                onclick="setSubscriptionDays(90)" 
                                                class="px-3 py-2 rounded-lg border text-sm transition-all"
                                                :class="darkMode ? 'glass border-white/20 text-white hover:bg-white/10' : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
                                            >
                                                90d
                                            </button>
                                        </div>
                                    </div>
                                    <div class="rounded-lg p-3" :class="darkMode ? 'glass border border-purple-500/30' : 'bg-purple-50 border border-purple-200'">
                                        <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                            Total cost: <span class="font-bold text-purple-500" id="subscription-cost">৳0.00</span>
                                        </p>
                                    </div>
                                </div>
                            </form>

                            <button 
                                type="button"
                                onclick="redeemForSubscription()"
                                @if(!$stats['can_redeem']) disabled @endif
                                class="w-full px-4 py-2 bg-[#C8102E] text-white rounded-lg hover:bg-[#A00E27] transition-all font-medium disabled:bg-gray-500 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-star mr-2"></i>
                                Redeem for Subscription
                            </button>
                            
                            <p class="text-xs text-center mt-3" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                                Choose a plan and duration
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- History Section -->
        <section class="px-4 sm:px-6 lg:px-8 pb-12">
            <div class="max-w-7xl mx-auto">
                <div class="rounded-xl border overflow-hidden"
                     :class="darkMode ? 'glass border-white/10' : 'bg-white border-gray-200'">
                    <!-- Tabs -->
                    <div class="border-b" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                        <div class="flex">
                            <button 
                                @click="activeTab = 'referrals'"
                                class="flex-1 px-6 py-4 font-medium transition-colors border-b-2"
                                :class="activeTab === 'referrals' ? 'text-[#C8102E] border-[#C8102E]' : (darkMode ? 'text-gray-400 border-transparent hover:text-white' : 'text-gray-600 border-transparent hover:text-gray-900')"
                            >
                                <i class="fas fa-users mr-2"></i>
                                Referral History
                            </button>
                            <button 
                                @click="activeTab = 'redemptions'"
                                class="flex-1 px-6 py-4 font-medium transition-colors border-b-2"
                                :class="activeTab === 'redemptions' ? 'text-[#C8102E] border-[#C8102E]' : (darkMode ? 'text-gray-400 border-transparent hover:text-white' : 'text-gray-600 border-transparent hover:text-gray-900')"
                            >
                                <i class="fas fa-history mr-2"></i>
                                Redemption History
                            </button>
                        </div>
                    </div>

                    <!-- Referral History -->
                    <div x-show="activeTab === 'referrals'" class="p-6">
                        @if($referralHistory['referrals']->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Friend</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Joined</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Reward</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y" :class="darkMode ? 'divide-white/5' : 'divide-gray-200'">
                                        @foreach($referralHistory['referrals'] as $referral)
                                            <tr>
                                                <td class="px-4 py-4">
                                                    <div>
                                                        <div class="text-sm font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $referral['referred_user']['name'] }}</div>
                                                        <div class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">{{ $referral['referred_user']['email'] }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    {{ $referral['referred_user']['joined_at'] }}
                                                </td>
                                                <td class="px-4 py-4">
                                                    @if($referral['status'] === 'completed')
                                                        <span class="px-2 py-1 inline-flex text-xs font-medium rounded-md bg-green-50 text-green-700 dark:bg-green-500/20 dark:text-green-400">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Completed
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 inline-flex text-xs font-medium rounded-md bg-yellow-50 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 text-sm font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                    ৳{{ $referral['reward_amount'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-user-friends text-5xl mb-4" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"></i>
                                <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No referrals yet</p>
                                <p class="text-sm mt-2" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">Start sharing your referral link!</p>
                            </div>
                        @endif
                    </div>

                    <!-- Redemption History -->
                    <div x-show="activeTab === 'redemptions'" class="p-6">
                        @if($redemptionHistory['redemptions']->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b" :class="darkMode ? 'border-white/10' : 'border-gray-200'">
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Type</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Details</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y" :class="darkMode ? 'divide-white/5' : 'divide-gray-200'">
                                        @foreach($redemptionHistory['redemptions'] as $redemption)
                                            <tr>
                                                <td class="px-4 py-4 text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    {{ $redemption['created_at'] }}
                                                </td>
                                                <td class="px-4 py-4">
                                                    @if($redemption['type'] === 'tokens')
                                                        <span class="px-2 py-1 inline-flex text-xs font-medium rounded-md bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                                            <i class="fas fa-coins mr-1"></i>
                                                            Tokens
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 inline-flex text-xs font-medium rounded-md bg-purple-50 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400">
                                                            <i class="fas fa-crown mr-1"></i>
                                                            Subscription
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 text-sm" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                    {{ $redemption['details'] }}
                                                </td>
                                                <td class="px-4 py-4 text-sm font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                    {{ $redemption['formatted_amount'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-exchange-alt text-5xl mb-4" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"></i>
                                <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No redemptions yet</p>
                                <p class="text-sm mt-2" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">Start redeeming your rewards!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
    </div>

    @push('scripts')
    <script>
        const stats = @json($stats);
        const subscriptionPlans = @json($subscriptionPlans);
        
        function copyReferralLink() {
            const input = document.getElementById('referral-link');
            input.select();
            document.execCommand('copy');
            
            const btnText = document.getElementById('copy-btn-text');
            const originalText = btnText.textContent;
            btnText.textContent = 'Copied!';
            
            setTimeout(() => {
                btnText.textContent = originalText;
            }, 2000);
        }
        
        function updateTokenCalculation() {
            const amount = parseFloat(document.getElementById('token-redeem-amount').value) || 0;
            const tokens = Math.floor(amount * stats.tokens_per_taka);
            document.getElementById('token-calculation').textContent = tokens + ' tokens';
        }
        
        function setMaxTokenAmount() {
            document.getElementById('token-redeem-amount').value = stats.current_balance;
            updateTokenCalculation();
        }
        
        function updateSubscriptionCalculation() {
            const planSelect = document.getElementById('selected-plan');
            const daysContainer = document.getElementById('subscription-days-container');
            const selectedOption = planSelect.options[planSelect.selectedIndex];
            
            if (planSelect.value) {
                daysContainer.style.display = 'block';
                const dailyPrice = parseFloat(selectedOption.dataset.dailyPrice);
                const days = parseInt(document.getElementById('subscription-days').value) || 0;
                const cost = dailyPrice * days;
                document.getElementById('subscription-cost').textContent = '৳' + cost.toFixed(2);
            } else {
                daysContainer.style.display = 'none';
            }
        }
        
        function setSubscriptionDays(days) {
            document.getElementById('subscription-days').value = days;
            updateSubscriptionCalculation();
        }
        
        function redeemForTokens() {
            const amount = parseFloat(document.getElementById('token-redeem-amount').value);
            
            if (amount < stats.min_redemption_amount) {
                alert('Amount must be at least ৳' + stats.min_redemption_amount);
                return;
            }
            
            if (amount > stats.current_balance) {
                alert('Insufficient balance');
                return;
            }
            
            if (confirm('Are you sure you want to redeem ৳' + amount + ' for ' + Math.floor(amount * stats.tokens_per_taka) + ' tokens?')) {
                fetch('{{ route("student.referrals.redeem.tokens") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ amount: amount })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    alert('An error occurred');
                    console.error(error);
                });
            }
        }
        
        function redeemForSubscription() {
            const planId = document.getElementById('selected-plan').value;
            const days = parseInt(document.getElementById('subscription-days').value);
            
            if (!planId) {
                alert('Please select a plan');
                return;
            }
            
            if (!days || days < 1) {
                alert('Please enter valid number of days');
                return;
            }
            
            const selectedOption = document.getElementById('selected-plan').options[document.getElementById('selected-plan').selectedIndex];
            const dailyPrice = parseFloat(selectedOption.dataset.dailyPrice);
            const cost = dailyPrice * days;
            
            if (cost > stats.current_balance) {
                alert('Insufficient balance');
                return;
            }
            
            if (confirm('Are you sure you want to redeem ৳' + cost.toFixed(2) + ' for ' + days + ' days of ' + selectedOption.text.split(' - ')[0] + '?')) {
                fetch('{{ route("student.referrals.redeem.subscription") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        plan_id: planId,
                        days: days 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    alert('An error occurred');
                    console.error(error);
                });
            }
        }
        
        // Initialize calculations
        document.addEventListener('DOMContentLoaded', function() {
            updateTokenCalculation();
        });
    </script>
    @endpush
</x-student-layout>