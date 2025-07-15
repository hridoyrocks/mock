<x-student-layout>
    <x-slot:title>Complete Your Purchase</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-2xl mx-auto text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-4">
                    <i class="fas fa-lock text-green-400 mr-2 sm:mr-3"></i>
                    Secure Checkout
                </h1>
                <p class="text-sm sm:text-base text-gray-300">You're one step away from unlocking premium features</p>
                
                <!-- Progress Bar -->
                <div class="flex items-center justify-center mt-6 space-x-1 sm:space-x-2 px-4">
                    <div class="flex items-center">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white text-xs sm:text-sm font-bold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm text-gray-400 ml-1 sm:ml-2 hidden sm:inline">Plan Selected</span>
                    </div>
                    <div class="w-8 sm:w-16 h-0.5 bg-purple-500"></div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white text-xs sm:text-sm font-bold animate-pulse">
                            2
                        </div>
                        <span class="text-xs sm:text-sm text-white ml-1 sm:ml-2">Payment</span>
                    </div>
                    <div class="w-8 sm:w-16 h-0.5 bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 text-xs sm:text-sm font-bold">
                            3
                        </div>
                        <span class="text-xs sm:text-sm text-gray-500 ml-1 sm:ml-2 hidden sm:inline">Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div class="glass rounded-2xl p-6 sm:p-8 border border-white/10">
                        @php
                            $couponDetails = session('applied_coupon_details');
                            $finalAmount = session('subscription_amount', $plan->current_price);
                            $isFree = $finalAmount == 0;
                        @endphp

                        <h2 class="text-xl sm:text-2xl font-bold text-white mb-6 flex items-center">
                            <i class="fas fa-credit-card text-purple-400 mr-2 sm:mr-3"></i>
                            @if($isFree)
                                Confirm Your Free Subscription
                            @else
                                Choose Payment Method
                            @endif
                        </h2>
                        
                        <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            
                            @if($isFree)
                                <!-- Free with Coupon Message -->
                                <div class="glass rounded-xl p-6 mb-8 border border-green-500/30 bg-green-500/5 text-center">
                                    <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-gift text-3xl text-green-400"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-white mb-2">Congratulations! 🎉</h3>
                                    <p class="text-gray-300">Your coupon gives you <span class="text-green-400 font-bold">100% OFF</span> this plan!</p>
                                    <p class="text-sm text-gray-400 mt-2">No payment required - just click below to activate your subscription.</p>
                                </div>
                                
                                <!-- Hidden default payment method for form submission -->
                                <input type="hidden" name="payment_method" value="free">
                            @else
                                <!-- Payment Methods Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-3 sm:gap-4 mb-8">
                                    <!-- Stripe Option -->
                                    <label class="payment-method-option cursor-pointer relative">
                                        <input type="radio" name="payment_method" value="stripe" class="sr-only peer" checked>
                                        <div class="glass rounded-xl p-4 sm:p-5 border-2 border-white/10 peer-checked:border-purple-500 peer-checked:shadow-lg peer-checked:shadow-purple-500/25 peer-checked:bg-purple-500/10 hover:border-purple-500/30 transition-all relative overflow-hidden">
                                            <!-- Selected Badge -->
                                            <div class="absolute top-0 right-0 bg-purple-600 text-white text-xs px-2 py-1 rounded-bl-lg hidden peer-checked:block">
                                                <i class="fas fa-check mr-1"></i>Selected
                                            </div>
                                            <div class="flex flex-col sm:flex-row items-center sm:justify-between">
                                                <div class="flex items-center mb-2 sm:mb-0">
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center mr-3 sm:mr-4 peer-checked:from-blue-500/30 peer-checked:to-blue-600/30">
                                                        <i class="fab fa-cc-stripe text-xl sm:text-2xl text-blue-400"></i>
                                                    </div>
                                                    <div class="text-center sm:text-left">
                                                        <p class="font-semibold text-white text-sm sm:text-base">Credit/Debit Card</p>
                                                        <p class="text-xs sm:text-sm text-gray-400 hidden sm:block">Secure payment via Stripe</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-1 sm:space-x-2 text-gray-500">
                                                    <i class="fab fa-cc-visa text-lg sm:text-2xl"></i>
                                                    <i class="fab fa-cc-mastercard text-lg sm:text-2xl"></i>
                                                    <i class="fab fa-cc-amex text-lg sm:text-2xl hidden sm:inline"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- bKash Option -->
                                    <label class="payment-method-option cursor-pointer relative">
                                        <input type="radio" name="payment_method" value="bkash" class="sr-only peer">
                                        <div class="glass rounded-xl p-4 sm:p-5 border-2 border-white/10 peer-checked:border-pink-500 peer-checked:shadow-lg peer-checked:shadow-pink-500/25 peer-checked:bg-pink-500/10 hover:border-pink-500/30 transition-all relative overflow-hidden">
                                            <!-- Selected Badge -->
                                            <div class="absolute top-0 right-0 bg-pink-600 text-white text-xs px-2 py-1 rounded-bl-lg hidden peer-checked:block">
                                                <i class="fas fa-check mr-1"></i>Selected
                                            </div>
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-pink-500/20 to-pink-600/20 flex items-center justify-center mr-3 sm:mr-4 peer-checked:from-pink-500/30 peer-checked:to-pink-600/30">
                                                    <span class="text-pink-400 font-bold text-base sm:text-lg">bK</span>
                                                </div>
                                                <div class="text-center sm:text-left">
                                                    <p class="font-semibold text-white text-sm sm:text-base">bKash</p>
                                                    <p class="text-xs sm:text-sm text-gray-400 hidden sm:block">Pay with your bKash account</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- Nagad Option -->
                                    <label class="payment-method-option cursor-pointer relative">
                                        <input type="radio" name="payment_method" value="nagad" class="sr-only peer">
                                        <div class="glass rounded-xl p-4 sm:p-5 border-2 border-white/10 peer-checked:border-orange-500 peer-checked:shadow-lg peer-checked:shadow-orange-500/25 peer-checked:bg-orange-500/10 hover:border-orange-500/30 transition-all relative overflow-hidden">
                                            <!-- Selected Badge -->
                                            <div class="absolute top-0 right-0 bg-orange-600 text-white text-xs px-2 py-1 rounded-bl-lg hidden peer-checked:block">
                                                <i class="fas fa-check mr-1"></i>Selected
                                            </div>
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-orange-500/20 to-orange-600/20 flex items-center justify-center mr-3 sm:mr-4 peer-checked:from-orange-500/30 peer-checked:to-orange-600/30">
                                                    <span class="text-orange-400 font-bold text-base sm:text-lg">N</span>
                                                </div>
                                                <div class="text-center sm:text-left">
                                                    <p class="font-semibold text-white text-sm sm:text-base">Nagad</p>
                                                    <p class="text-xs sm:text-sm text-gray-400 hidden sm:block">Pay with your Nagad account</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Card Details (for Stripe) -->
                                <div id="card-details" class="mb-8 space-y-6">
                                    <h3 class="font-semibold text-white mb-4 flex items-center text-sm sm:text-base">
                                        <i class="fas fa-credit-card text-blue-400 mr-2"></i>
                                        Card Information
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">Card Number</label>
                                            <div id="card-number" class="glass rounded-lg px-3 sm:px-4 py-3 border border-white/20 focus-within:border-purple-500/50 transition-colors"></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                            <div>
                                                <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">Expiry Date</label>
                                                <div id="card-expiry" class="glass rounded-lg px-3 sm:px-4 py-3 border border-white/20 focus-within:border-purple-500/50 transition-colors"></div>
                                            </div>
                                            <div>
                                                <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-2">CVC</label>
                                                <div id="card-cvc" class="glass rounded-lg px-3 sm:px-4 py-3 border border-white/20 focus-within:border-purple-500/50 transition-colors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="card-errors" class="text-red-400 text-xs sm:text-sm mt-2"></div>
                                </div>
                            @endif
                            
                            <!-- Terms -->
                            <div class="mb-6 sm:mb-8">
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" name="terms" required class="mt-1 mr-2 sm:mr-3 w-4 h-4 rounded border-gray-600 bg-transparent text-purple-600 focus:ring-purple-500 focus:ring-offset-0">
                                    <span class="text-xs sm:text-sm text-gray-400 group-hover:text-gray-300">
                                        I agree to the <a href="#" class="text-purple-400 hover:text-purple-300 underline">Terms of Service</a> 
                                        and <a href="#" class="text-purple-400 hover:text-purple-300 underline">Privacy Policy</a>
                                    </span>
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" id="submit-button" 
                                    class="w-full py-3 sm:py-4 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all shadow-lg hover:shadow-xl neon-purple disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
                                <span id="button-text" class="flex items-center justify-center">
                                    @if($isFree)
                                        <i class="fas fa-gift mr-2"></i>
                                        Activate Free Subscription
                                    @else
                                        <i class="fas fa-lock mr-2"></i>
                                        Complete Purchase - ৳{{ number_format($finalAmount, 0) }}
                                    @endif
                                </span>
                                <span id="button-loading" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                                </span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Security Badges -->
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-gray-400 text-xs sm:text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-400 mr-1 sm:mr-2"></i>
                            <span>256-bit SSL</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-lock text-purple-400 mr-1 sm:mr-2"></i>
                            <span>Secure Payment</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-shield text-blue-400 mr-1 sm:mr-2"></i>
                            <span>PCI Compliant</span>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1 order-1 lg:order-2">
                    <div class="glass rounded-2xl p-5 sm:p-6 border border-purple-500/30 lg:sticky lg:top-4 mb-6 lg:mb-0">
                        <h3 class="font-bold text-white text-lg sm:text-xl mb-4 sm:mb-6 flex items-center">
                            <i class="fas fa-shopping-cart text-purple-400 mr-2 sm:mr-3"></i>
                            Order Summary
                        </h3>
                        
                        <!-- Plan Details -->
                        <div class="glass rounded-xl p-3 sm:p-4 mb-4 sm:mb-6 border border-white/10">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                                    <i class="fas fa-crown text-white text-base sm:text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white text-sm sm:text-base">{{ $plan->name }} Plan</h4>
                                    <p class="text-xs text-gray-400">{{ $plan->duration_days }} days subscription</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-4 sm:mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-white font-medium">৳{{ number_format($plan->price, 0) }}</span>
                            </div>
                            
                            {{-- Plan Discount (if any) --}}
                            @if($plan->discount_price && !$couponDetails)
                                <div class="flex justify-between text-green-400 text-sm">
                                    <span class="flex items-center">
                                        <i class="fas fa-tag mr-2 text-xs"></i>
                                        Discount
                                    </span>
                                    <span>-৳{{ number_format($plan->price - $plan->discount_price, 0) }}</span>
                                </div>
                            @endif
                            
                            
                        
                        <!-- Billing Info -->
                        <div class="glass rounded-xl p-3 sm:p-4 mb-4 sm:mb-6 border border-blue-500/30 bg-blue-500/10">
                            <p class="text-xs sm:text-sm text-blue-300 flex items-start">
                                <i class="fas fa-info-circle mr-1.5 sm:mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>
                                    @if($isFree)
                                        You're getting this plan for FREE with your coupon!
                                    @else
                                        You'll be charged ৳{{ number_format($finalAmount, 0) }} today and every {{ $plan->duration_days }} days until you cancel.
                                    @endif
                                </span>
                            </p>
                        </div>
                        
                        <!-- Benefits -->
                        <div class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm">
                            <div class="flex items-center text-gray-300">
                                <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-green-500/20 flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-check text-green-400 text-xs"></i>
                                </div>
                                <span>Instant access after payment</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-green-500/20 flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-check text-green-400 text-xs"></i>
                                </div>
                                <span>Cancel anytime, no hidden fees</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-green-500/20 flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <i class="fas fa-check text-green-400 text-xs"></i>
                                </div>
                                <span>30-day money back guarantee</span>
                            </div>
                        </div>
                        
                        <!-- Need Help -->
                        <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-white/10">
                            <p class="text-xs sm:text-sm text-gray-400 mb-1 sm:mb-2">Need help?</p>
                            <a href="#" class="text-purple-400 hover:text-purple-300 text-xs sm:text-sm flex items-center">
                                <i class="fas fa-headset mr-1.5 sm:mr-2"></i>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Remove coupon function
        function removeCoupon() {
            if (confirm('Are you sure you want to remove this coupon?')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('coupon.remove') }}';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Toggle coupon input in summary
        function toggleSummaryCoupon() {
            const section = document.getElementById('summary-coupon-section');
            const chevron = document.getElementById('coupon-chevron');
            
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
                document.getElementById('summary-coupon-code').focus();
            } else {
                section.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Apply coupon from summary
        async function applyCouponFromSummary() {
            const code = document.getElementById('summary-coupon-code').value.trim();
            const planId = {{ $plan->id }};
            const applyBtn = document.getElementById('summary-apply-btn');
            const btnText = document.getElementById('summary-btn-text');
            const btnLoading = document.getElementById('summary-btn-loading');
            const errorDiv = document.getElementById('summary-coupon-error');
            
            if (!code) {
                errorDiv.textContent = 'Please enter a coupon code';
                errorDiv.classList.remove('hidden');
                return;
            }
            
            // Show loading
            applyBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
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
                    // Apply coupon
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
                        // Reload page to reflect changes
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
            } finally {
                applyBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        }

        // Toggle coupon input
        function toggleCouponInput() {
            const section = document.getElementById('coupon-input-section');
            const toggleText = document.getElementById('coupon-toggle-text');
            
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                toggleText.textContent = 'Cancel';
                document.getElementById('checkout-coupon-code').focus();
            } else {
                section.classList.add('hidden');
                toggleText.textContent = 'Apply';
            }
        }

        // Apply coupon in checkout
        async function applyCouponInCheckout() {
            const code = document.getElementById('checkout-coupon-code').value.trim();
            const planId = {{ $plan->id }};
            const applyBtn = document.getElementById('apply-coupon-btn');
            const btnText = document.getElementById('apply-btn-text');
            const btnLoading = document.getElementById('apply-btn-loading');
            const errorDiv = document.getElementById('checkout-coupon-error');
            const successDiv = document.getElementById('checkout-coupon-success');
            
            if (!code) {
                errorDiv.textContent = 'Please enter a coupon code';
                errorDiv.classList.remove('hidden');
                return;
            }
            
            // Show loading
            applyBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            
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
                    // Apply coupon
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
                        successDiv.textContent = 'Coupon applied! Reloading...';
                        successDiv.classList.remove('hidden');
                        
                        // Reload page to reflect changes
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                } else {
                    errorDiv.textContent = data.message || 'Invalid coupon code';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error applying coupon:', error);
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            } finally {
                applyBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        }

        @if(!session('subscription_amount') || session('subscription_amount') > 0)
        // Payment method selection
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'stripe') {
                    document.getElementById('card-details').style.display = 'block';
                } else {
                    document.getElementById('card-details').style.display = 'none';
                }
            });
        });
        
        // Initialize Stripe
        @if(config('services.stripe.key'))
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements({
            fonts: [{
                cssSrc: 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap',
            }]
        });
        
        // Custom styling for Stripe elements
        const style = {
            base: {
                color: '#ffffff',
                fontFamily: '"Plus Jakarta Sans", sans-serif',
                fontSize: '16px',
                '::placeholder': {
                    color: '#9ca3af'
                }
            },
            invalid: {
                color: '#ef4444',
                iconColor: '#ef4444'
            }
        };
        
        // Create card elements
        const cardNumber = elements.create('cardNumber', { style });
        const cardExpiry = elements.create('cardExpiry', { style });
        const cardCvc = elements.create('cardCvc', { style });
        
        cardNumber.mount('#card-number');
        cardExpiry.mount('#card-expiry');
        cardCvc.mount('#card-cvc');
        
        // Handle card errors
        function handleCardError(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
                displayError.classList.remove('hidden');
            } else {
                displayError.textContent = '';
                displayError.classList.add('hidden');
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
            
            // Disable button and show loading
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoading.classList.remove('hidden');
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'free';
            
            if (paymentMethod === 'stripe' && typeof stripe !== 'undefined') {
                // Handle Stripe payment
                try {
                    // Create payment method
                    const { error, paymentMethod } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardNumber,
                    });
                    
                    if (error) {
                        // Show error to customer
                        handleCardError({ error });
                        submitButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                        return;
                    }
                    
                    // Add payment method ID to form
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
            
            // Submit form
            this.submit();
        });
    </script>
    <style>
        /* Animation for selected payment method */
        .payment-method-option input:checked + div {
            animation: selectPulse 0.3s ease-out;
        }
        
        @keyframes selectPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
    @endpush
</x-student-layout>