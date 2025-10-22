<x-student-layout>
    <x-slot:title>Purchase Evaluation Tokens</x-slot>
    
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mx-4 sm:mx-6 lg:mx-8 mt-6 max-w-6xl mx-auto">
        <div class="rounded-lg p-4 flex items-start gap-3" 
             :class="darkMode ? 'bg-green-900/30 border border-green-700' : 'bg-green-50 border border-green-200'">
            <i class="fas fa-check-circle text-green-600 text-xl mt-0.5"></i>
            <div class="flex-1">
                <h4 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Success!</h4>
                <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="mx-4 sm:mx-6 lg:mx-8 mt-6 max-w-6xl mx-auto">
        <div class="rounded-lg p-4 flex items-start gap-3"
             :class="darkMode ? 'bg-red-900/30 border border-red-700' : 'bg-red-50 border border-red-200'">
            <i class="fas fa-exclamation-circle text-red-600 text-xl mt-0.5"></i>
            <div class="flex-1">
                <h4 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Error</h4>
                <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Clean Header -->
    <section class="py-8 mb-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Balance Badge - Top Right -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold mb-2" 
                            :class="darkMode ? 'text-white' : 'text-gray-900'">
                            Token Packages
                        </h1>
                        <p class="text-lg" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                            Choose a package for AI evaluation
                        </p>
                    </div>
                    
                    <!-- Balance Card -->
                    <div class="rounded-lg p-4 border-2" 
                         :class="darkMode ? 'bg-gray-800 border-yellow-500/30' : 'bg-yellow-50 border-yellow-200'">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center">
                                <i class="fas fa-coins text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Your Balance</p>
                                <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                    {{ $tokenBalance->available_tokens }} tokens
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Button -->
                <button onclick="openInfoModal()" 
                        class="inline-flex items-center text-sm font-medium transition-colors"
                        :class="darkMode ? 'text-blue-400 hover:text-blue-300' : 'text-blue-600 hover:text-blue-700'">
                    <i class="fas fa-info-circle mr-2"></i>
                    How token system works
                </button>
            </div>
        </div>
    </section>

    <!-- Token Packages -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($packages as $package)
                <div class="rounded-lg p-6 transition-all hover:shadow-lg {{ $package->is_popular ? 'ring-2 ring-yellow-500' : '' }}"
                     :class="darkMode ? 'bg-gray-800 border-2 {{ $package->is_popular ? 'border-yellow-500' : 'border-gray-700' }}' : 'bg-white border-2 {{ $package->is_popular ? 'border-yellow-500' : 'border-gray-200' }} shadow-md'">
                    
                    @if($package->is_popular)
                    <div class="flex justify-center mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-white">
                            <i class="fas fa-star mr-1"></i>POPULAR
                        </span>
                    </div>
                    @endif
                    
                    <!-- Icon -->
                    <div class="flex justify-center mb-4">
                        <div class="w-14 h-14 rounded-lg flex items-center justify-center
                            {{ $loop->first ? 'bg-blue-500' : ($loop->last ? 'bg-purple-500' : 'bg-yellow-500') }}">
                            <i class="fas {{ $loop->first ? 'fa-bolt' : ($loop->last ? 'fa-crown' : 'fa-star') }} text-2xl text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Name -->
                    <h3 class="text-xl font-bold text-center mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">
                        {{ $package->name }}
                    </h3>
                    
                    <!-- Token Amount -->
                    <div class="text-center mb-4">
                        <div class="flex items-baseline justify-center">
                            <span class="text-4xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                {{ $package->total_tokens }}
                            </span>
                            <span class="text-sm ml-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">tokens</span>
                        </div>
                        @if($package->bonus_tokens > 0)
                        <div class="mt-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                             :class="darkMode ? 'bg-green-900/30 text-green-400 border border-green-700' : 'bg-green-100 text-green-700 border border-green-300'">
                            <i class="fas fa-gift mr-1"></i>
                            +{{ $package->bonus_tokens }} bonus
                        </div>
                        @endif
                    </div>
                    
                    <!-- Price -->
                    <div class="text-center mb-6">
                        <div class="flex items-baseline justify-center">
                            <span class="text-3xl font-bold text-[#C8102E]">৳{{ number_format($package->price, 0) }}</span>
                        </div>
                        <p class="text-xs mt-1" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                            ≈ ৳{{ number_format($package->price / $package->total_tokens, 1) }} per token
                        </p>
                    </div>
                    
                    <!-- Button -->
                    <button onclick="selectPackage({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})" 
                            class="w-full py-3 rounded-lg font-semibold transition-all
                            {{ $package->is_popular 
                                ? 'bg-yellow-500 hover:bg-yellow-600 text-white' 
                                : '' }}"
                            :class="!{{ $package->is_popular ? 'true' : 'false' }} && (darkMode ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'border-2 border-gray-300 hover:border-[#C8102E] text-gray-700 hover:text-[#C8102E]')">
                        Purchase Now
                    </button>
                </div>
                @endforeach
            </div>

            <!-- Recent Activity -->
            @if($recentTransactions && count($recentTransactions) > 0)
            <div class="mt-12">
                <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Recent Activity
                </h3>
                <div class="rounded-lg overflow-hidden" :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
                    <table class="w-full">
                        <tbody>
                            @foreach($recentTransactions->take(5) as $transaction)
                            <tr :class="darkMode ? 'border-gray-700' : 'border-gray-100'" class="border-b">
                                <td class="p-3 text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->diffForHumans() }}
                                </td>
                                <td class="p-3">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $transaction->type === 'purchase' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm font-semibold {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                </td>
                                <td class="p-3 text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                    {{ $transaction->reason }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </section>
    
    <!-- Info Modal -->
    <div id="infoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div onclick="closeInfoModal()" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
            
            <div class="relative rounded-lg w-full max-w-2xl p-6 transform transition-all"
                 :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white shadow-xl'">
                <button onclick="closeInfoModal()" 
                        class="absolute top-4 right-4 hover:text-gray-900 transition-colors"
                        :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-400'">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <h3 class="text-2xl font-bold mb-6" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    How Token System Works
                </h3>
                
                <!-- Steps -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-purple-600 font-semibold text-sm">1</span>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Purchase Tokens</h4>
                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Buy a token package that fits your needs
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-semibold text-sm">2</span>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Complete Test</h4>
                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Take a Writing or Speaking test
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-green-600 font-semibold text-sm">3</span>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Request Evaluation</h4>
                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Select a teacher and use tokens for AI evaluation
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-yellow-600 font-semibold text-sm">4</span>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Get Results</h4>
                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Receive detailed feedback and band score
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Token Requirements -->
                <div class="rounded-lg p-4" :class="darkMode ? 'bg-gray-900 border border-gray-700' : 'bg-gray-50 border border-gray-200'">
                    <h4 class="font-semibold mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">Token Requirements</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex justify-between">
                            <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Writing (Normal)</span>
                            <span class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">10-15</span>
                        </div>
                        <div class="flex justify-between">
                            <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Writing (Urgent)</span>
                            <span class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">15-20</span>
                        </div>
                        <div class="flex justify-between">
                            <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Speaking (Normal)</span>
                            <span class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">8-12</span>
                        </div>
                        <div class="flex justify-between">
                            <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Speaking (Urgent)</span>
                            <span class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">12-17</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <button onclick="closeInfoModal()" 
                            class="px-6 py-2.5 rounded-lg font-medium transition-all"
                            :class="darkMode ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div onclick="closePaymentModal()" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
            
            <div class="relative rounded-lg w-full max-w-md p-6"
                 :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white shadow-xl'">
                <button onclick="closePaymentModal()" 
                        class="absolute top-4 right-4 hover:text-gray-900"
                        :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-400'">
                    <i class="fas fa-times"></i>
                </button>
                
                <h3 class="text-xl font-bold mb-6" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Complete Purchase
                </h3>
                
                <form id="paymentForm" action="{{ route('student.tokens.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package_id" id="selectedPackageId">
                    
                    <!-- Package Summary -->
                    <div class="rounded-lg p-4 mb-6" :class="darkMode ? 'bg-gray-900' : 'bg-gray-50'">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Package</p>
                                <p id="packageName" class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-[#C8102E]">৳<span id="packagePrice"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="space-y-3 mb-6">
                        <!-- Stripe -->
                        <label class="flex items-center p-4 rounded-lg border-2 cursor-pointer transition-all payment-method-stripe"
                               :class="darkMode ? 'border-gray-700 hover:border-blue-500' : 'border-gray-200 hover:border-blue-500'">
                            <input type="radio" name="payment_method" value="stripe" class="sr-only" onchange="selectPaymentMethod('stripe')">
                            
                            <!-- Radio Indicator -->
                            <div class="w-5 h-5 rounded-full border-2 mr-3 flex items-center justify-center flex-shrink-0 radio-indicator-stripe"
                                 :class="darkMode ? 'border-gray-600' : 'border-gray-300'">
                                <div class="w-2.5 h-2.5 rounded-full bg-blue-600 hidden radio-dot-stripe"></div>
                            </div>
                            
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fab fa-cc-stripe text-blue-600 text-lg"></i>
                            </div>
                            <span class="flex-1 font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">Credit/Debit Card</span>
                        </label>
                        
                        <!-- bKash -->
                        <label class="flex items-center p-4 rounded-lg border-2 cursor-pointer transition-all payment-method-bkash"
                               :class="darkMode ? 'border-gray-700 hover:border-pink-500' : 'border-gray-200 hover:border-pink-500'">
                            <input type="radio" name="payment_method" value="bkash" class="sr-only" onchange="selectPaymentMethod('bkash')">
                            
                            <!-- Radio Indicator -->
                            <div class="w-5 h-5 rounded-full border-2 mr-3 flex items-center justify-center flex-shrink-0 radio-indicator-bkash"
                                 :class="darkMode ? 'border-gray-600' : 'border-gray-300'">
                                <div class="w-2.5 h-2.5 rounded-full bg-pink-600 hidden radio-dot-bkash"></div>
                            </div>
                            
                            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center mr-3 p-1.5">
                                <img src="https://mohammadalinijhoom.com/wp-content/uploads/2024/07/bKash-Logo.png" alt="bKash" class="w-full h-full object-contain">
                            </div>
                            <span class="flex-1 font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">bKash</span>
                        </label>
                        
                        <!-- Nagad -->
                        <label class="flex items-center p-4 rounded-lg border-2 cursor-not-allowed opacity-60"
                               :class="darkMode ? 'border-gray-700 bg-gray-900' : 'border-gray-200 bg-gray-50'">
                            <input type="radio" name="payment_method" value="nagad" class="sr-only" disabled>
                            
                            <!-- Radio Indicator (Disabled) -->
                            <div class="w-5 h-5 rounded-full border-2 mr-3 flex items-center justify-center flex-shrink-0"
                                 :class="darkMode ? 'border-gray-700' : 'border-gray-300'">
                            </div>
                            
                            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center mr-3 p-1.5">
                                <img src="https://freelogopng.com/images/all_img/1679248787Nagad-Logo.png" alt="Nagad" class="w-full h-full object-contain">
                            </div>
                            <span class="flex-1 font-medium" :class="darkMode ? 'text-white' : 'text-gray-900'">Nagad</span>
                            <span class="text-xs px-2 py-1 rounded-full"
                                  :class="darkMode ? 'bg-yellow-900/30 text-yellow-400' : 'bg-yellow-100 text-yellow-700'">
                                Coming Soon
                            </span>
                        </label>
                    </div>
                    
                    <!-- Stripe Card Details -->
                    <div id="stripeCardDetails" class="hidden mb-6">
                        <div class="rounded-lg p-3 border" :class="darkMode ? 'bg-gray-900 border-gray-700' : 'bg-gray-50 border-gray-200'">
                            <div id="card-element" class="p-2"></div>
                            <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitPayment"
                            disabled
                            class="w-full py-3 rounded-lg font-semibold bg-[#C8102E] hover:bg-[#A00E27] text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Complete Purchase</span>
                        <span id="submitLoading" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    @push('styles')
    <style>
        /* Thick border for selected payment method */
        .border-3 {
            border-width: 3px !important;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    
    <script>
        let stripe = null;
        let elements = null;
        let cardElement = null;
        let selectedPaymentMethod = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            @if(config('services.stripe.key'))
                stripe = Stripe('{{ config('services.stripe.key') }}');
                elements = stripe.elements();
                
                const style = {
                    base: {
                        color: localStorage.getItem('darkMode') !== 'false' ? '#ffffff' : '#1f2937',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#9ca3af'
                        }
                    },
                    invalid: {
                        color: '#ef4444'
                    }
                };
                
                cardElement = elements.create('card', { style: style });
            @endif
        });
        
        function openInfoModal() {
            document.getElementById('infoModal').classList.remove('hidden');
        }
        
        function closeInfoModal() {
            document.getElementById('infoModal').classList.add('hidden');
        }
        
        function selectPackage(packageId, packageName, packagePrice) {
            document.getElementById('selectedPackageId').value = packageId;
            document.getElementById('packageName').textContent = packageName;
            document.getElementById('packagePrice').textContent = packagePrice.toLocaleString();
            document.getElementById('paymentModal').classList.remove('hidden');
        }
        
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentForm').reset();
            selectedPaymentMethod = null;
            document.getElementById('submitPayment').disabled = true;
            
            // Reset all payment methods to default state
            const isDark = localStorage.getItem('darkMode') !== 'false';
            
            document.querySelector('.payment-method-stripe').className = 'flex items-center p-4 rounded-lg border-2 cursor-pointer transition-all payment-method-stripe ' + (isDark ? 'border-gray-700 hover:border-blue-500' : 'border-gray-200 hover:border-blue-500');
            document.querySelector('.payment-method-bkash').className = 'flex items-center p-4 rounded-lg border-2 cursor-pointer transition-all payment-method-bkash ' + (isDark ? 'border-gray-700 hover:border-pink-500' : 'border-gray-200 hover:border-pink-500');
            
            document.querySelector('.radio-dot-stripe').classList.add('hidden');
            document.querySelector('.radio-dot-bkash').classList.add('hidden');
            
            document.getElementById('stripeCardDetails').classList.add('hidden');
            if (cardElement) {
                cardElement.unmount();
            }
        }
        
        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            // Reset all payment methods to default border
            const isDark = localStorage.getItem('darkMode') !== 'false';
            
            document.querySelector('.payment-method-stripe').className = 'flex items-center p-4 rounded-lg border-2 cursor-pointer transition-all payment-method-stripe ' + (isDark ? 'border-gray-700 hover:border-blue-500' : 'border-gray-200 hover:border-blue-500');
            document.querySelector('.payment-method-bkash').className = 'flex items-center p-4 rounded-lg border-2 cursor-pointer transition-all payment-method-bkash ' + (isDark ? 'border-gray-700 hover:border-pink-500' : 'border-gray-200 hover:border-pink-500');
            
            document.querySelector('.radio-dot-stripe').classList.add('hidden');
            document.querySelector('.radio-dot-bkash').classList.add('hidden');
            
            if (method === 'stripe') {
                // Highlight stripe with thick colored border
                document.querySelector('.payment-method-stripe').className = 'flex items-center p-4 rounded-lg border-3 cursor-pointer transition-all payment-method-stripe border-blue-500 ' + (isDark ? '' : 'bg-blue-50');
                document.querySelector('.radio-dot-stripe').classList.remove('hidden');
                
                // Show card details
                document.getElementById('stripeCardDetails').classList.remove('hidden');
                if (cardElement) {
                    cardElement.mount('#card-element');
                }
            } else if (method === 'bkash') {
                // Highlight bkash with thick colored border
                document.querySelector('.payment-method-bkash').className = 'flex items-center p-4 rounded-lg border-3 cursor-pointer transition-all payment-method-bkash border-pink-500 ' + (isDark ? '' : 'bg-pink-50');
                document.querySelector('.radio-dot-bkash').classList.remove('hidden');
                
                // Hide card details
                document.getElementById('stripeCardDetails').classList.add('hidden');
                if (cardElement) {
                    cardElement.unmount();
                }
            }
            
            document.getElementById('submitPayment').disabled = false;
        }
        
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = document.getElementById('submitPayment');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            
            submitButton.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                // If bKash or Nagad, just submit the form directly
                if (selectedPaymentMethod === 'bkash' || selectedPaymentMethod === 'nagad') {
                    this.submit();
                    return;
                }
                
                // Stripe payment handling
                if (selectedPaymentMethod === 'stripe' && stripe) {
                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                    });
                    
                    if (error) {
                        document.getElementById('card-errors').textContent = error.message;
                        submitButton.disabled = false;
                        submitText.classList.remove('hidden');
                        submitLoading.classList.add('hidden');
                        return;
                    }
                    
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            package_id: document.getElementById('selectedPackageId').value,
                            payment_method: 'stripe',
                            payment_method_id: paymentMethod.id
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.requires_action && stripe) {
                        const { error: confirmError } = await stripe.confirmCardPayment(result.client_secret);
                        
                        if (confirmError) {
                            throw new Error(confirmError.message);
                        }
                        
                        window.location.href = result.redirect || '{{ route("student.tokens.purchase") }}';
                    } else if (result.redirect) {
                        window.location.href = result.redirect;
                    } else if (result.success) {
                        window.location.href = '{{ route("student.tokens.purchase") }}';
                    } else {
                        throw new Error(result.error || 'Payment failed');
                    }
                }
            } catch (error) {
                alert('Payment failed: ' + error.message);
                submitButton.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-student-layout>
