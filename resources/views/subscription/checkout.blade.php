@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Purchase</h1>
            <p class="text-gray-600">You're one step away from unlocking premium features</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Payment Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold mb-6">Choose Payment Method</h2>
                    
                    <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        
                        {{-- Payment Methods --}}
                        <div class="space-y-4 mb-6">
                            <label class="payment-method-option">
                                <input type="radio" name="payment_method" value="stripe" class="sr-only" checked>
                                <div class="border-2 rounded-lg p-4 cursor-pointer transition hover:border-blue-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fab fa-cc-stripe text-2xl text-blue-600 mr-3"></i>
                                            <div>
                                                <p class="font-medium">Credit/Debit Card</p>
                                                <p class="text-sm text-gray-600">Secure payment via Stripe</p>
                                            </div>
                                        </div>
                                        <div class="text-gray-400">
                                            <i class="fab fa-cc-visa text-2xl mr-2"></i>
                                            <i class="fab fa-cc-mastercard text-2xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-method-option">
                                <input type="radio" name="payment_method" value="bkash" class="sr-only">
                                <div class="border-2 rounded-lg p-4 cursor-pointer transition hover:border-pink-500">
                                    <div class="flex items-center">
                                        <img src="/images/bkash-logo.png" alt="bKash" class="h-8 mr-3">
                                        <div>
                                            <p class="font-medium">bKash</p>
                                            <p class="text-sm text-gray-600">Pay with your bKash account</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-method-option">
                                <input type="radio" name="payment_method" value="nagad" class="sr-only">
                                <div class="border-2 rounded-lg p-4 cursor-pointer transition hover:border-orange-500">
                                    <div class="flex items-center">
                                        <img src="/images/nagad-logo.png" alt="Nagad" class="h-8 mr-3">
                                        <div>
                                            <p class="font-medium">Nagad</p>
                                            <p class="text-sm text-gray-600">Pay with your Nagad account</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        {{-- Card Details (for Stripe) --}}
                        <div id="card-details" class="mb-6">
                            <h3 class="font-medium mb-4">Card Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                    <div id="card-number" class="border rounded-lg px-3 py-2"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                        <div id="card-expiry" class="border rounded-lg px-3 py-2"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                                        <div id="card-cvc" class="border rounded-lg px-3 py-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="card-errors" class="text-red-600 text-sm mt-2"></div>
                        </div>
                        
                        {{-- Terms --}}
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" name="terms" required class="mt-1 mr-2">
                                <span class="text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> 
                                    and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                                </span>
                            </label>
                        </div>
                        
                        {{-- Submit Button --}}
                        <button type="submit" id="submit-button" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="button-text">Complete Purchase</span>
                            <span id="button-loading" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                            </span>
                        </button>
                    </form>
                </div>
                
                {{-- Security Badge --}}
                <div class="mt-6 flex items-center justify-center text-gray-600">
                    <i class="fas fa-lock mr-2"></i>
                    <span class="text-sm">Your payment information is secure and encrypted</span>
                </div>
            </div>
            
            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-6 sticky top-4">
                    <h3 class="font-semibold text-gray-900 mb-4">Order Summary</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $plan->name }} Plan</span>
                            <span class="font-medium">৳{{ number_format($plan->price, 0) }}</span>
                        </div>
                        @if($plan->discount_price)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span>-৳{{ number_format($plan->price - $plan->discount_price, 0) }}</span>
                        </div>
                        @endif
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-semibold">Total</span>
                            <span class="font-semibold text-xl">৳{{ number_format($plan->current_price, 0) }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            You'll be charged ৳{{ number_format($plan->current_price, 0) }} today and every {{ $plan->duration_days }} days until you cancel.
                        </p>
                    </div>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><i class="fas fa-check text-green-500 mr-2"></i> Instant access after payment</p>
                        <p><i class="fas fa-check text-green-500 mr-2"></i> Cancel anytime</p>
                        <p><i class="fas fa-check text-green-500 mr-2"></i> 30-day money back guarantee</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method-option > div').forEach(div => {
                div.classList.remove('border-blue-500', 'border-pink-500', 'border-orange-500');
                div.classList.add('border-gray-200');
            });
            
            this.nextElementSibling.classList.remove('border-gray-200');
            if (this.value === 'stripe') {
                this.nextElementSibling.classList.add('border-blue-500');
                document.getElementById('card-details').style.display = 'block';
            } else if (this.value === 'bkash') {
                this.nextElementSibling.classList.add('border-pink-500');
                document.getElementById('card-details').style.display = 'none';
            } else if (this.value === 'nagad') {
                this.nextElementSibling.classList.add('border-orange-500');
                document.getElementById('card-details').style.display = 'none';
            }
        });
    });
    
    // Initialize Stripe
    @if(config('services.stripe.key'))
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    
    // Create card elements
    const cardNumber = elements.create('cardNumber');
    const cardExpiry = elements.create('cardExpiry');
    const cardCvc = elements.create('cardCvc');
    
    cardNumber.mount('#card-number');
    cardExpiry.mount('#card-expiry');
    cardCvc.mount('#card-cvc');
    
    // Handle card errors
    cardNumber.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
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
            // Add Stripe payment logic here
        }
        
        // Submit form
        this.submit();
    });
</script>
@endpush
@endsection