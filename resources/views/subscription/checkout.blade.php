<x-student-layout>
    <x-slot:title>Complete Your Purchase</x-slot>
    
    <div x-data="{ 
        showCouponInput: false,
        paymentMethod: 'stripe'
    }" x-init="() => { if (typeof darkMode === 'undefined') { darkMode = localStorage.getItem('darkMode') !== 'false'; } }">
    
    <!-- Clean Header -->
    <section class="py-8 mb-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto text-center">
                <h1 class="text-3xl lg:text-4xl font-bold mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Complete Your Order
                </h1>
                <p class="text-lg" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                    One step away from starting your IELTS preparation
                </p>
                
                <!-- Simple Progress Steps -->
                <div class="flex items-center justify-center mt-6 gap-4 max-w-md mx-auto">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-xs mt-1.5" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Plan</span>
                    </div>
                    <div class="w-16 h-0.5 bg-green-600"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-[#C8102E] flex items-center justify-center text-white font-semibold">
                            2
                        </div>
                        <span class="text-xs mt-1.5 font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">Payment</span>
                    </div>
                    <div class="w-16 h-0.5" :class="darkMode ? 'bg-gray-700' : 'bg-gray-300'"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold"
                             :class="darkMode ? 'bg-gray-700 text-gray-400' : 'bg-gray-200 text-gray-500'">
                            3
                        </div>
                        <span class="text-xs mt-1.5" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Done</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-16">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="rounded-lg p-6 shadow-md"
                         :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
                        
                        @php
                            $couponDetails = session('applied_coupon_details');
                            $finalAmount = session('subscription_amount', $plan->current_price);
                            $isFree = $finalAmount == 0;
                        @endphp

                        <h2 class="text-xl font-bold mb-6 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <i class="fas fa-credit-card mr-3 text-[#C8102E]"></i>
                            @if($isFree)
                                Confirm Your Free Subscription
                            @else
                                Payment Method
                            @endif
                        </h2>
                        
                        <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            
                            @if($isFree)
                                <!-- Free Subscription Message -->
                                <div class="rounded-lg p-6 mb-6 text-center"
                                     :class="darkMode ? 'bg-green-900/20 border border-green-700' : 'bg-green-50 border border-green-200'">
                                    <div class="w-16 h-16 rounded-full bg-green-600 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-gift text-2xl text-white"></i>
                                    </div>
                                    <h3 class="text-xl font-bold mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">Congratulations! 🎉</h3>
                                    <p class="text-lg mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        Your coupon gives you <span class="text-green-600 font-bold">100% OFF</span>
                                    </p>
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        No payment required - just click below to activate
                                    </p>
                                </div>
                                
                                <input type="hidden" name="payment_method" value="free">
                            @else
                                <!-- Payment Methods -->
                                <div class="space-y-3 mb-6">
                                    <!-- Stripe Option -->
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="payment_method" value="stripe" class="sr-only peer" 
                                               x-model="paymentMethod" checked>
                                        <div class="rounded-lg p-4 border-2 transition-all"
                                             :class="darkMode ? 
                                                'border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-900/10' : 
                                                'border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50'">
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                                        <i class="fab fa-cc-stripe text-2xl text-blue-600"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                            Credit/Debit Card
                                                        </p>
                                                        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                            Secure payment via Stripe
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="hidden sm:flex items-center space-x-2">
                                                    <i class="fab fa-cc-visa text-2xl" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"></i>
                                                    <i class="fab fa-cc-mastercard text-2xl" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- bKash Option -->
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="payment_method" value="bkash" class="sr-only peer"
                                               x-model="paymentMethod">
                                        <div class="rounded-lg p-4 border-2 transition-all"
                                             :class="darkMode ? 
                                                'border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-900/10' : 
                                                'border-gray-200 peer-checked:border-pink-500 peer-checked:bg-pink-50'">
                                            
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center mr-3 p-1.5">
                                                    <img src="https://mohammadalinijhoom.com/wp-content/uploads/2024/07/bKash-Logo.png" 
                                                         alt="bKash" 
                                                         class="w-full h-full object-contain">
                                                </div>
                                                <div>
                                                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                        bKash
                                                    </p>
                                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        Pay with your bKash account
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- Nagad Option -->
                                    <label class="cursor-not-allowed block opacity-60">
                                        <input type="radio" name="payment_method" value="nagad" class="sr-only peer" disabled>
                                        <div class="rounded-lg p-4 border-2 relative"
                                             :class="darkMode ? 'border-gray-700 bg-gray-800/50' : 'border-gray-200 bg-gray-50'">
                                            
                                            <!-- Coming Soon Badge -->
                                            <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full text-xs font-semibold"
                                                 :class="darkMode ? 'bg-yellow-900/30 text-yellow-400 border border-yellow-700' : 'bg-yellow-100 text-yellow-700 border border-yellow-300'">
                                                Coming Soon
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center mr-3 p-1.5">
                                                    <img src="https://freelogopng.com/images/all_img/1679248787Nagad-Logo.png" 
                                                         alt="Nagad" 
                                                         class="w-full h-full object-contain">
                                                </div>
                                                <div>
                                                    <p class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                        Nagad
                                                    </p>
                                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                        Pay with your Nagad account
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Card Details -->
                                <div x-show="paymentMethod === 'stripe'" 
                                     x-transition
                                     class="mb-6 p-4 rounded-lg"
                                     :class="darkMode ? 'bg-gray-900 border border-gray-700' : 'bg-gray-50 border border-gray-200'">
                                    
                                    <h3 class="font-semibold mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                                        Card Information
                                    </h3>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium mb-1.5" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                Card Number
                                            </label>
                                            <div id="card-number" class="rounded-lg px-3 py-2.5 border"
                                                 :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-300'">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-sm font-medium mb-1.5" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    Expiry Date
                                                </label>
                                                <div id="card-expiry" class="rounded-lg px-3 py-2.5 border"
                                                     :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-300'">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium mb-1.5" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    CVC
                                                </label>
                                                <div id="card-cvc" class="rounded-lg px-3 py-2.5 border"
                                                     :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-300'">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                                </div>
                            @endif
                            
                            <!-- Terms -->
                            <div class="mb-6">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" name="terms" required 
                                           class="mt-1 mr-2.5 w-4 h-4 rounded text-[#C8102E]">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        I agree to the <a href="#" class="text-[#C8102E] hover:underline">Terms</a> 
                                        and <a href="#" class="text-[#C8102E] hover:underline">Privacy Policy</a>
                                    </span>
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" id="submit-button" 
                                    class="w-full py-3.5 rounded-lg font-semibold bg-[#C8102E] hover:bg-[#A00E27] text-white transition-all shadow-md disabled:opacity-50">
                                <span id="button-text">
                                    @if($isFree)
                                        <i class="fas fa-check-circle mr-2"></i>Activate Free Subscription
                                    @else
                                        <i class="fas fa-lock mr-2"></i>Complete Payment • ৳{{ number_format($finalAmount, 0) }}
                                    @endif
                                </span>
                                <span id="button-loading" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                                </span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Security Badges -->
                    <div class="mt-6 p-4 rounded-lg text-center"
                         :class="darkMode ? 'bg-gray-800' : 'bg-gray-50'">
                        <div class="flex flex-wrap items-center justify-center gap-4 text-sm">
                            <div class="flex items-center gap-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                <i class="fas fa-lock text-green-600"></i>
                                <span class="font-medium">Secure Checkout</span>
                            </div>
                            <div class="w-px h-4" :class="darkMode ? 'bg-gray-700' : 'bg-gray-300'"></div>
                            <div class="flex items-center gap-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                <i class="fas fa-shield-check text-green-600"></i>
                                <span>SSL Encrypted</span>
                            </div>
                            <div class="w-px h-4" :class="darkMode ? 'bg-gray-700' : 'bg-gray-300'"></div>
                            <div class="flex items-center gap-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                <i class="fas fa-check-circle text-blue-600"></i>
                                <span>PCI Compliant</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-4 rounded-lg p-6 shadow-md"
                         :class="darkMode ? 'bg-gray-800 border-2 border-[#C8102E]/30' : 'bg-white border-2 border-[#C8102E]/20'">
                        
                        <h3 class="font-bold text-lg mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <i class="fas fa-file-invoice text-[#C8102E] mr-2"></i>
                            Order Summary
                        </h3>
                        
                        <!-- Plan Details -->
                        <div class="rounded-lg p-3 mb-4"
                             :class="darkMode ? 'bg-gray-900' : 'bg-gray-50'">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#C8102E] flex items-center justify-center">
                                    <i class="fas fa-crown text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        {{ $plan->name }} Plan
                                    </h4>
                                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                        {{ $plan->duration_days }} days access
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="space-y-2.5 mb-4">
                            <div class="flex justify-between text-sm">
                                <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Subtotal</span>
                                <span :class="darkMode ? 'text-white' : 'text-gray-900'">৳{{ number_format($plan->price, 0) }}</span>
                            </div>
                            
                            @if($plan->discount_price && !$couponDetails)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span><i class="fas fa-tag mr-1"></i>Plan Discount</span>
                                    <span>-৳{{ number_format($plan->price - $plan->discount_price, 0) }}</span>
                                </div>
                            @endif
                            
                            @if($couponDetails)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span><i class="fas fa-ticket mr-1"></i>Coupon ({{ $couponDetails['code'] }})</span>
                                    <span>{{ $couponDetails['formatted_discount'] }}</span>
                                </div>
                            @endif
                            
                            <div class="pt-3 border-t" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        Total
                                    </span>
                                    <span class="text-2xl font-bold text-[#C8102E]">
                                        ৳{{ number_format($finalAmount, 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Coupon Input -->
                        @if(!$couponDetails && !$isFree)
                        <div class="mb-4">
                            <button @click="showCouponInput = !showCouponInput" 
                                    class="w-full py-2.5 rounded-lg font-medium text-sm border transition-all"
                                    :class="darkMode ? 
                                        'border-gray-700 text-gray-300 hover:bg-gray-700' : 
                                        'border-gray-300 text-gray-700 hover:bg-gray-50'">
                                <i class="fas fa-tag mr-2"></i>Have a promo code?
                            </button>
                            
                            <div x-show="showCouponInput" x-collapse class="mt-3">
                                <div class="flex gap-2">
                                    <input type="text" 
                                           id="checkout-coupon-code"
                                           placeholder="Enter code"
                                           class="flex-1 px-3 py-2 rounded-lg text-sm font-semibold uppercase"
                                           :class="darkMode ? 'bg-gray-900 text-white border border-gray-700' : 'bg-white text-gray-900 border border-gray-300'">
                                    <button onclick="applyCouponInCheckout()" 
                                            class="px-4 py-2 rounded-lg bg-[#C8102E] text-white font-medium text-sm hover:bg-[#A00E27]">
                                        Apply
                                    </button>
                                </div>
                                <p id="checkout-coupon-error" class="text-red-500 text-xs mt-2 hidden"></p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Benefits -->
                        <div class="space-y-2.5 pt-4 border-t" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                            <div class="flex items-center gap-2.5 text-sm">
                                <i class="fas fa-bolt text-green-600"></i>
                                <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Instant access</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-sm">
                                <i class="fas fa-headset text-blue-600"></i>
                                <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'">24/7 support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Apply coupon in checkout
        async function applyCouponInCheckout() {
            const code = document.getElementById('checkout-coupon-code').value.trim();
            const planId = {{ $plan->id }};
            const errorDiv = document.getElementById('checkout-coupon-error');
            
            if (!code) {
                errorDiv.textContent = 'Please enter a coupon code';
                errorDiv.classList.remove('hidden');
                return;
            }
            
            errorDiv.classList.add('hidden');
            
            try {
                const response = await fetch('{{ route('coupon.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: code.toUpperCase(),
                        plan_id: planId
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.valid) {
                    const applyResponse = await fetch('{{ route('coupon.apply') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            code: code.toUpperCase(),
                            plan_id: planId
                        })
                    });
                    
                    if (applyResponse.ok) {
                        window.location.reload();
                    }
                } else {
                    errorDiv.textContent = data.message || 'Invalid coupon code';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error applying coupon:', error);
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            }
        }

        @if(!session('subscription_amount') || session('subscription_amount') > 0)
        // Initialize Stripe
        @if(config('services.stripe.key'))
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        
        const style = {
            base: {
                color: localStorage.getItem('darkMode') !== 'false' ? '#ffffff' : '#1f2937',
                fontSize: '16px',
                '::placeholder': {
                    color: localStorage.getItem('darkMode') !== 'false' ? '#9ca3af' : '#6b7280'
                }
            },
            invalid: {
                color: '#ef4444'
            }
        };
        
        const cardNumber = elements.create('cardNumber', { style });
        const cardExpiry = elements.create('cardExpiry', { style });
        const cardCvc = elements.create('cardCvc', { style });
        
        cardNumber.mount('#card-number');
        cardExpiry.mount('#card-expiry');
        cardCvc.mount('#card-cvc');
        
        function handleCardError(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        }
        
        cardNumber.on('change', handleCardError);
        cardExpiry.on('change', handleCardError);
        cardCvc.on('change', handleCardError);
        @endif
        @endif
        
        // Form submission
        document.getElementById('payment-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const buttonLoading = document.getElementById('button-loading');
            
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoading.classList.remove('hidden');
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'free';
            
            if (paymentMethod === 'stripe' && typeof stripe !== 'undefined') {
                try {
                    const { error, paymentMethod } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardNumber,
                    });
                    
                    if (error) {
                        handleCardError({ error });
                        submitButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                        return;
                    }
                    
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'payment_method_id';
                    hiddenInput.value = paymentMethod.id;
                    this.appendChild(hiddenInput);
                } catch (error) {
                    console.error(error);
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonLoading.classList.add('hidden');
                    return;
                }
            }
            
            this.submit();
        });
    </script>
    @endpush
</x-student-layout>
