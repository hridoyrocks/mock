<x-student-layout>
    <x-slot:title>Referral Program</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-yellow-600/20 via-transparent to-amber-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="glass rounded-2xl p-8 lg:p-12 mb-8 bg-gradient-to-r from-yellow-600/10 to-amber-600/10 border-yellow-500/30">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                        <div class="flex-1 text-center lg:text-left">
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                                <i class="fas fa-gift text-yellow-400 mr-3"></i>
                                Earn Rewards by Referring Friends!
                            </h1>
                            <p class="text-gray-300 text-lg mb-6">
                                Share your referral link and earn <span class="text-yellow-400 font-bold">৳100</span> for each friend who completes their first test.
                            </p>
                            
                            <!-- Referral Link -->
                            <div class="glass rounded-xl p-4 border-yellow-500/30">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Your Referral Link</label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        id="referral-link"
                                        value="{{ $stats['referral_link'] }}" 
                                        readonly 
                                        class="flex-1 px-4 py-2 glass bg-transparent text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                    >
                                    <button 
                                        onclick="copyReferralLink()" 
                                        class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg hover:from-yellow-600 hover:to-amber-600 transition-all font-medium"
                                    >
                                        <i class="fas fa-copy mr-2"></i>
                                        <span id="copy-btn-text">Copy</span>
                                    </button>
                                </div>
                                <p class="text-sm mt-3 text-gray-300">
                                    Referral Code: <span class="font-mono font-bold text-yellow-400 text-lg">{{ $stats['referral_code'] }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Visual Element -->
                        <div class="relative">
                            <div class="w-64 h-64 relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/20 to-amber-500/20 rounded-full blur-3xl animate-pulse"></div>
                                <div class="relative glass rounded-full w-full h-full flex items-center justify-center border-yellow-500/30">
                                    <div class="text-center">
                                        <p class="text-5xl font-bold text-white mb-2">৳{{ number_format($stats['current_balance'], 0) }}</p>
                                        <p class="text-gray-300">Current Balance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Current Balance -->
                    <div class="glass rounded-xl p-6 hover:border-green-500/50 transition-all hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-400">Current Balance</h3>
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                                <i class="fas fa-wallet text-white"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white">৳{{ number_format($stats['current_balance'], 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Available for redemption</p>
                    </div>

                    <!-- Total Referrals -->
                    <div class="glass rounded-xl p-6 hover:border-blue-500/50 transition-all hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-400">Total Referrals</h3>
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white">{{ $stats['total_referrals'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Friends referred</p>
                    </div>

                    <!-- Successful Referrals -->
                    <div class="glass rounded-xl p-6 hover:border-green-500/50 transition-all hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-400">Successful</h3>
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white">{{ $stats['successful_referrals'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Completed referrals</p>
                    </div>

                    <!-- Pending Referrals -->
                    <div class="glass rounded-xl p-6 hover:border-yellow-500/50 transition-all hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-400">Pending</h3>
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-amber-500 flex items-center justify-center">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white">{{ $stats['pending_referrals'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Awaiting completion</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-7xl mx-auto">
            <!-- Redemption Options -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Redeem for Tokens -->
                <div class="glass rounded-2xl overflow-hidden hover:border-blue-500/50 transition-all">
                    <div class="bg-gradient-to-r from-blue-600/20 to-cyan-600/20 p-6 border-b border-white/10">
                        <h3 class="text-xl font-bold text-white mb-2">
                            <i class="fas fa-coins text-blue-400 mr-2"></i>
                            Redeem for Evaluation Tokens
                        </h3>
                        <p class="text-gray-300">Convert your balance to tokens for human evaluation</p>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <div class="flex items-center justify-between p-4 glass rounded-lg mb-4">
                                <span class="text-gray-300">Conversion Rate</span>
                                <span class="text-2xl font-bold text-white">৳1 = {{ $stats['tokens_per_taka'] }} Tokens</span>
                            </div>
                        </div>
                        
                        <form id="token-redeem-form" class="mb-6">
                            @csrf
                            <label class="block text-sm font-medium text-gray-300 mb-2">Amount to Redeem (৳)</label>
                            <div class="flex gap-2">
                                <input 
                                    type="number" 
                                    name="amount"
                                    id="token-redeem-amount"
                                    min="{{ $stats['min_redemption_amount'] }}"
                                    max="{{ $stats['current_balance'] }}"
                                    step="10"
                                    value="{{ $stats['min_redemption_amount'] }}"
                                    class="flex-1 px-4 py-3 glass bg-transparent text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter amount"
                                    onchange="updateTokenCalculation()"
                                >
                                <button 
                                    type="button"
                                    onclick="setMaxTokenAmount()" 
                                    class="px-4 py-3 glass rounded-lg hover:border-blue-500/50 transition-all text-white font-medium"
                                >
                                    Max
                                </button>
                            </div>
                            <div class="mt-3 p-3 glass rounded-lg border-blue-500/30">
                                <p class="text-sm text-gray-300">
                                    You will receive: <span class="text-2xl font-bold text-blue-400 ml-2" id="token-calculation">0 tokens</span>
                                </p>
                            </div>
                        </form>

                        <button 
                            type="button"
                            onclick="redeemForTokens()"
                            @if(!$stats['can_redeem'])
                                disabled
                            @endif
                            class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all font-medium disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed"
                        >
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Redeem for Tokens
                        </button>

                        <p class="text-xs text-gray-500 mt-3 text-center">
                            Minimum redemption: ৳{{ $stats['min_redemption_amount'] }}
                        </p>
                    </div>
                </div>

                <!-- Redeem for Subscription -->
                <div class="glass rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all">
                    <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 p-6 border-b border-white/10">
                        <h3 class="text-xl font-bold text-white mb-2">
                            <i class="fas fa-crown text-purple-400 mr-2"></i>
                            Redeem for Premium Subscription
                        </h3>
                        <p class="text-gray-300">Use your balance to get premium features</p>
                    </div>
                    <div class="p-6">
                        <form id="subscription-redeem-form">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Select Plan</label>
                                <select 
                                    name="plan_id"
                                    id="selected-plan"
                                    class="w-full px-4 py-3 glass bg-transparent text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
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

                            <div class="mb-6" id="subscription-days-container" style="display: none;">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Number of Days</label>
                                <div class="flex gap-2">
                                    <input 
                                        type="number"
                                        name="days" 
                                        id="subscription-days"
                                        min="1"
                                        max="365"
                                        value="30"
                                        class="flex-1 px-4 py-3 glass bg-transparent text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                        placeholder="Enter days"
                                        onchange="updateSubscriptionCalculation()"
                                    >
                                    <div class="flex gap-1">
                                        <button 
                                            type="button"
                                            onclick="setSubscriptionDays(30)" 
                                            class="px-3 py-3 glass rounded-lg hover:border-purple-500/50 transition-all text-white text-sm font-medium"
                                        >
                                            30d
                                        </button>
                                        <button 
                                            type="button"
                                            onclick="setSubscriptionDays(90)" 
                                            class="px-3 py-3 glass rounded-lg hover:border-purple-500/50 transition-all text-white text-sm font-medium"
                                        >
                                            90d
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3 p-3 glass rounded-lg border-purple-500/30">
                                    <p class="text-sm text-gray-300">
                                        Total cost: <span class="text-2xl font-bold text-purple-400 ml-2" id="subscription-cost">৳0.00</span>
                                    </p>
                                </div>
                            </div>
                        </form>

                        <button 
                            type="button"
                            onclick="redeemForSubscription()"
                            @if(!$stats['can_redeem'])
                                disabled
                            @endif
                            class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all font-medium disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed"
                        >
                            <i class="fas fa-star mr-2"></i>
                            Redeem for Subscription
                        </button>

                        <p class="text-xs text-gray-500 mt-3 text-center">
                            Choose a plan and duration to continue
                        </p>
                    </div>
                </div>
            </div>

            <!-- History Tabs -->
            <div class="glass rounded-2xl overflow-hidden">
                <div class="border-b border-white/10">
                    <div class="flex">
                        <button 
                            onclick="showTab('referrals')" 
                            id="referrals-tab"
                            class="flex-1 px-6 py-4 text-white font-medium hover:bg-white/5 transition-colors border-b-2 border-yellow-500"
                        >
                            <i class="fas fa-users mr-2"></i>
                            Referral History
                        </button>
                        <button 
                            onclick="showTab('redemptions')" 
                            id="redemptions-tab"
                            class="flex-1 px-6 py-4 text-gray-400 font-medium hover:bg-white/5 transition-colors border-b-2 border-transparent"
                        >
                            <i class="fas fa-history mr-2"></i>
                            Redemption History
                        </button>
                    </div>
                </div>

                <!-- Referral History Tab -->
                <div id="referrals-content" class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Friend</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Joined</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Reward</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($referralHistory['referrals'] as $referral)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-white">{{ $referral['referred_user']['name'] }}</div>
                                                <div class="text-sm text-gray-400">{{ $referral['referred_user']['email'] }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $referral['referred_user']['joined_at'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($referral['status'] === 'completed')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Completed
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-white">
                                            ৳{{ $referral['reward_amount'] }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <i class="fas fa-user-friends text-6xl text-gray-600 mb-4"></i>
                                            <p class="text-gray-400">No referrals yet</p>
                                            <p class="text-sm text-gray-500 mt-2">Start sharing your referral link to earn rewards!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Redemption History Tab -->
                <div id="redemptions-content" class="p-6 hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($redemptionHistory['redemptions'] as $redemption)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $redemption['created_at'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($redemption['type'] === 'tokens')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                                    <i class="fas fa-coins mr-1"></i>
                                                    Tokens
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-500/20 text-purple-400 border border-purple-500/30">
                                                    <i class="fas fa-crown mr-1"></i>
                                                    Subscription
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                            {{ $redemption['details'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-white">
                                            {{ $redemption['formatted_amount'] }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <i class="fas fa-exchange-alt text-6xl text-gray-600 mb-4"></i>
                                            <p class="text-gray-400">No redemptions yet</p>
                                            <p class="text-sm text-gray-500 mt-2">Start redeeming your rewards!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    const stats = @json($stats);
    const subscriptionPlans = @json($subscriptionPlans);
    
    // Tab switching
    function showTab(tab) {
        if (tab === 'referrals') {
            document.getElementById('referrals-content').classList.remove('hidden');
            document.getElementById('redemptions-content').classList.add('hidden');
            document.getElementById('referrals-tab').classList.add('text-white', 'border-yellow-500');
            document.getElementById('referrals-tab').classList.remove('text-gray-400', 'border-transparent');
            document.getElementById('redemptions-tab').classList.add('text-gray-400', 'border-transparent');
            document.getElementById('redemptions-tab').classList.remove('text-white', 'border-yellow-500');
        } else {
            document.getElementById('referrals-content').classList.add('hidden');
            document.getElementById('redemptions-content').classList.remove('hidden');
            document.getElementById('redemptions-tab').classList.add('text-white', 'border-yellow-500');
            document.getElementById('redemptions-tab').classList.remove('text-gray-400', 'border-transparent');
            document.getElementById('referrals-tab').classList.add('text-gray-400', 'border-transparent');
            document.getElementById('referrals-tab').classList.remove('text-white', 'border-yellow-500');
        }
    }
    
    function copyReferralLink() {
        const input = document.getElementById('referral-link');
        input.select();
        document.execCommand('copy');
        
        const btnText = document.getElementById('copy-btn-text');
        const originalText = btnText.textContent;
        btnText.textContent = 'Copied!';
        
        // Show success effect
        const button = btnText.parentElement;
        button.classList.add('from-green-500', 'to-emerald-500');
        button.classList.remove('from-yellow-500', 'to-amber-500');
        
        setTimeout(() => {
            btnText.textContent = originalText;
            button.classList.remove('from-green-500', 'to-emerald-500');
            button.classList.add('from-yellow-500', 'to-amber-500');
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
</x-student-layout>
