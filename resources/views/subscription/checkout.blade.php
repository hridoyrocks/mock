<x-student-layout>
    <x-slot:title>Complete Your Purchase</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-2xl mx-auto text-center">
                <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                    <i class="fas fa-lock text-green-400 mr-3"></i>
                    Secure Checkout
                </h1>
                <p class="text-gray-300">You're one step away from unlocking premium features</p>
                
                <!-- Progress Bar -->
                <div class="flex items-center justify-center mt-6 space-x-2">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white text-sm font-bold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm text-gray-400 ml-2">Plan Selected</span>
                    </div>
                    <div class="w-16 h-0.5 bg-purple-500"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white text-sm font-bold animate-pulse">
                            2
                        </div>
                        <span class="text-sm text-white ml-2">Payment</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 text-sm font-bold">
                            3
                        </div>
                        <span class="text-sm text-gray-500 ml-2">Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="glass rounded-2xl p-8 border border-white/10">
                        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                            <i class="fas fa-credit-card text-purple-400 mr-3"></i>
                            Choose Payment Method
                        </h2>
                        
                        <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            
                            <!-- Payment Methods -->
                            <div class="space-y-4 mb-8">
                                <!-- Stripe Option -->
                                <label class="payment-method-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="stripe" class="sr-only peer" checked>
                                    <div class="glass rounded-xl p-5 border-2 border-white/10 peer-checked:border-purple-500/50 peer-checked:bg-purple-500/10 hover:border-purple-500/30 transition-all">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center mr-4">
                                                    <i class="fab fa-cc-stripe text-2xl text-blue-400"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-white">Credit/Debit Card</p>
                                                    <p class="text-sm text-gray-400">Secure payment via Stripe</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 text-gray-500">
                                                <i class="fab fa-cc-visa text-2xl"></i>
                                                <i class="fab fa-cc-mastercard text-2xl"></i>
                                                <i class="fab fa-cc-amex text-2xl"></i>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                <!-- bKash Option -->
                                <label class="payment-method-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="bkash" class="sr-only peer">
                                    <div class="glass rounded-xl p-5 border-2 border-white/10 peer-checked:border-pink-500/50 peer-checked:bg-pink-500/10 hover:border-pink-500/30 transition-all">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-pink-500/20 to-pink-600/20 flex items-center justify-center mr-4">
                                                <span class="text-pink-400 font-bold text-lg">bK</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white">bKash</p>
                                                <p class="text-sm text-gray-400">Pay with your bKash account</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                <!-- Nagad Option -->
                                <label class="payment-method-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="nagad" class="sr-only peer">
                                    <div class="glass rounded-xl p-5 border-2 border-white/10 peer-checked:border-orange-500/50 peer-checked:bg-orange-500/10 hover:border-orange-500/30 transition-all">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500/20 to-orange-600/20 flex items-center justify-center mr-4">
                                                <span class="text-orange-400 font-bold text-lg">N</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white">Nagad</p>
                                                <p class="text-sm text-gray-400">Pay with your Nagad account</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Card Details (for Stripe) -->
                            <div id="card-details" class="mb-8 space-y-6">
                                <h3 class="font-semibold text-white mb-4 flex items-center">
                                    <i class="fas fa-credit-card text-blue-400 mr-2"></i>
                                    Card Information
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Card Number</label>
                                        <div id="card-number" class="glass rounded-lg px-4 py-3 border border-white/20 focus-within:border-purple-500/50 transition-colors"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Expiry Date</label>
                                            <div id="card-expiry" class="glass rounded-lg px-4 py-3 border border-white/20 focus-within:border-purple-500/50 transition-colors"></div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">CVC</label>
                                            <div id="card-cvc" class="glass rounded-lg px-4 py-3 border border-white/20 focus-within:border-purple-500/50 transition-colors"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="card-errors" class="text-red-400 text-sm mt-2"></div>
                            </div>
                            
                            <!-- Terms -->
                            <div class="mb-8">
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" name="terms" required class="mt-1 mr-3 w-4 h-4 rounded border-gray-600 bg-transparent text-purple-600 focus:ring-purple-500 focus:ring-offset-0">
                                    <span class="text-sm text-gray-400 group-hover:text-gray-300">
                                        I agree to the <a href="#" class="text-purple-400 hover:text-purple-300 underline">Terms of Service</a> 
                                        and <a href="#" class="text-purple-400 hover:text-purple-300 underline">Privacy Policy</a>
                                    </span>
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" id="submit-button" 
                                    class="w-full py-4 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all shadow-lg hover:shadow-xl neon-purple disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="button-text" class="flex items-center justify-center">
                                    <i class="fas fa-lock mr-2"></i>
                                    Complete Purchase - ৳{{ number_format($plan->current_price, 0) }}
                                </span>
                                <span id="button-loading" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> Processing Payment...
                                </span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Security Badges -->
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-6 text-gray-400 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-400 mr-2"></i>
                            <span>256-bit SSL Encryption</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-lock text-purple-400 mr-2"></i>
                            <span>Secure Payment Processing</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-shield text-blue-400 mr-2"></i>
                            <span>PCI Compliant</span>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="glass rounded-2xl p-6 border border-purple-500/30 sticky top-4">
                        <h3 class="font-bold text-white text-xl mb-6 flex items-center">
                            <i class="fas fa-shopping-cart text-purple-400 mr-3"></i>
                            Order Summary
                        </h3>
                        
                        <!-- Plan Details -->
                        <div class="glass rounded-xl p-4 mb-6 border border-white/10">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                                    <i class="fas fa-crown text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white">{{ $plan->name }} Plan</h4>
                                    <p class="text-xs text-gray-400">Monthly subscription</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-white font-medium">৳{{ number_format($plan->price, 0) }}</span>
                            </div>
                            @if($plan->discount_price)
                            <div class="flex justify-between text-green-400">
                                <span class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-sm"></i>
                                    Discount
                                </span>
                                <span>-৳{{ number_format($plan->price - $plan->discount_price, 0) }}</span>
                            </div>
                            @endif
                            <div class="border-t border-white/10 pt-3 flex justify-between">
                                <span class="font-bold text-white">Total Due Today</span>
                                <span class="font-bold text-2xl text-white">৳{{ number_format($plan->current_price, 0) }}</span>
                            </div>
                        </div>
                        
                        <!-- Billing Info -->
                        <div class="glass rounded-xl p-4 mb-6 border border-blue-500/30 bg-blue-500/10">
                            <p class="text-sm text-blue-300 flex items-start">
                                <i class="fas fa-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>You'll be charged ৳{{ number_format($plan->current_price, 0) }} today and every {{ $plan->duration_days }} days until you cancel.</span>
                            </p>
                        </div>
                        
                        <!-- Benefits -->
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center text-gray-300">
                                <div class="w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-400 text-xs"></i>
                                </div>
                                <span>Instant access after payment</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <div class="w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-400 text-xs"></i>
                                </div>
                                <span>Cancel anytime, no hidden fees</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <div class="w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-400 text-xs"></i>
                                </div>
                                <span>30-day money back guarantee</span>
                            </div>
                        </div>
                        
                        <!-- Need Help -->
                        <div class="mt-6 pt-6 border-t border-white/10">
                            <p class="text-sm text-gray-400 mb-2">Need help?</p>
                            <a href="#" class="text-purple-400 hover:text-purple-300 text-sm flex items-center">
                                <i class="fas fa-headset mr-2"></i>
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
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'stripe') {
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
    @endpush
</x-student-layout>