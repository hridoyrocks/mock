<x-student-layout>
    <x-slot:title>Complete Your Purchase</x-slot>
    
    <div x-data="{ 
        showCouponInput: false,
        paymentMethod: 'stripe'
    }" x-init="() => { if (typeof darkMode === 'undefined') { darkMode = localStorage.getItem('darkMode') !== 'false'; } }">
    
    <!-- Enhanced Header with Animation -->
    <section class="relative overflow-hidden py-8 mb-6">
        <div class="absolute inset-0 bg-gradient-to-br from-[#C8102E]/10 via-[#C8102E]/5 to-transparent"></div>
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#C8102E]/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#A00E27]/10 rounded-full blur-3xl animate-pulse"></div>
        </div>
        
        <div class="relative px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <!-- Security Badge -->
                <div class="flex justify-center mb-4">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full shadow-lg"
                         :class="darkMode ? 'glass border border-green-500/30' : 'bg-white/90 backdrop-blur border border-green-500/20'">
                        <i class="fas fa-lock text-green-500 animate-pulse"></i>
                        <span class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            Secure Checkout
                        </span>
                        <i class="fas fa-shield-check text-green-500"></i>
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-black text-center mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Complete Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#C8102E] to-[#A00E27]">Order</span>
                </h1>
                <p class="text-center text-lg" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                    You're one step away from unlocking premium features
                </p>
                
                <!-- Enhanced Progress Bar -->
                <div class="flex items-center justify-center mt-6 relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="h-1 w-full max-w-md bg-gray-700/30 rounded-full"></div>
                    </div>
                    <div class="relative flex items-center justify-between w-full max-w-md">
                        <!-- Step 1 -->
                        <div class="relative flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white shadow-lg shadow-green-500/30">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-xs mt-2 font-semibold" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Plan Selected</span>
                        </div>
                        
                        <!-- Progress Line -->
                        <div class="flex-1 h-1 bg-gradient-to-r from-green-500 to-[#C8102E] mx-2"></div>
                        
                        <!-- Step 2 -->
                        <div class="relative flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] flex items-center justify-center text-white shadow-lg shadow-[#C8102E]/30 animate-bounce">
                                <span class="font-bold">2</span>
                            </div>
                            <span class="text-xs mt-2 font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">Payment</span>
                        </div>
                        
                        <!-- Progress Line -->
                        <div class="flex-1 h-1 bg-gray-700/30 mx-2"></div>
                        
                        <!-- Step 3 -->
                        <div class="relative flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full bg-gray-700/50 flex items-center justify-center text-gray-400">
                                <span class="font-bold">3</span>
                            </div>
                            <span class="text-xs mt-2 font-semibold" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content with Visual Enhancement -->
    <section class="px-4 sm:px-6 lg:px-8 pb-16">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form (Enhanced) -->
                <div class="lg:col-span-2">
                    <div class="rounded-3xl p-8 shadow-2xl relative overflow-hidden"
                         :class="darkMode ? 'bg-gray-800/50 backdrop-blur border border-gray-700' : 'bg-white border border-gray-200'">
                        
                        @php
                            $couponDetails = session('applied_coupon_details');
                            $finalAmount = session('subscription_amount', $plan->current_price);
                            $isFree = $finalAmount == 0;
                        @endphp

                        <h2 class="text-2xl font-black mb-6 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-[#C8102E] to-[#A00E27] flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
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
                                <!-- Free with Coupon Celebration -->
                                <div class="rounded-2xl p-8 mb-8 text-center relative overflow-hidden"
                                     :class="darkMode ? 'bg-gradient-to-br from-green-900/30 to-green-800/30 border border-green-500/30' : 'bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200'">
                                    <div class="absolute inset-0 opacity-10">
                                        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%2310B981" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                                    </div>
                                    
                                    <div class="relative">
                                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center mx-auto mb-4 animate-bounce">
                                            <i class="fas fa-gift text-3xl text-white"></i>
                                        </div>
                                        <h3 class="text-2xl font-black mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">Congratulations! 🎉</h3>
                                        <p class="text-lg mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                            Your coupon gives you <span class="text-green-500 font-black text-2xl">100% OFF</span>
                                        </p>
                                        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                            No payment required - just click below to activate!
                                        </p>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="payment_method" value="free">
                            @else
                                <!-- Payment Methods with Visual Enhancement -->
                                <div class="space-y-4 mb-8">
                                    <!-- Stripe Option -->
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="payment_method" value="stripe" class="sr-only peer" 
                                               x-model="paymentMethod" checked>
                                        <div class="rounded-2xl p-6 border-2 transition-all duration-300 relative overflow-hidden peer-checked:scale-[1.02]"
                                             :class="darkMode ? 
                                                'border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-900/20' : 
                                                'border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50'">
                                            
                                            <!-- Selected Indicator -->
                                            <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 items-center justify-center hidden peer-checked:flex">
                                                <i class="fas fa-check text-white text-sm"></i>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center mr-4">
                                                        <i class="fab fa-cc-stripe text-3xl text-blue-500"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                                            Credit/Debit Card
                                                        </p>
                                                        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                            Secure payment via Stripe
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="hidden sm:flex items-center space-x-2">
                                                    <i class="fab fa-cc-visa text-3xl" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"></i>
                                                    <i class="fab fa-cc-mastercard text-3xl" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"></i>
                                                    <i class="fab fa-cc-amex text-3xl" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- bKash Option -->
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="payment_method" value="bkash" class="sr-only peer"
                                               x-model="paymentMethod">
                                        <div class="rounded-2xl p-6 border-2 transition-all duration-300 relative overflow-hidden peer-checked:scale-[1.02]"
                                             :class="darkMode ? 
                                                'border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-900/20' : 
                                                'border-gray-200 peer-checked:border-pink-500 peer-checked:bg-pink-50'">
                                            
                                            <!-- Selected Indicator -->
                                            <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gradient-to-r from-pink-500 to-pink-600 items-center justify-center hidden peer-checked:flex">
                                                <i class="fas fa-check text-white text-sm"></i>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500/20 to-pink-600/20 flex items-center justify-center mr-4">
                                                    <span class="text-pink-500 font-black text-2xl">bK</span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-gray-900'">
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
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="payment_method" value="nagad" class="sr-only peer"
                                               x-model="paymentMethod">
                                        <div class="rounded-2xl p-6 border-2 transition-all duration-300 relative overflow-hidden peer-checked:scale-[1.02]"
                                             :class="darkMode ? 
                                                'border-gray-700 peer-checked:border-orange-500 peer-checked:bg-orange-900/20' : 
                                                'border-gray-200 peer-checked:border-orange-500 peer-checked:bg-orange-50'">
                                            
                                            <!-- Selected Indicator -->
                                            <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 items-center justify-center hidden peer-checked:flex">
                                                <i class="fas fa-check text-white text-sm"></i>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500/20 to-orange-600/20 flex items-center justify-center mr-4">
                                                    <span class="text-orange-500 font-black text-2xl">N</span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-gray-900'">
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
                                
                                <!-- Card Details (Enhanced) -->
                                <div x-show="paymentMethod === 'stripe'" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform translate-y-4"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     class="mb-8 p-6 rounded-2xl"
                                     :class="darkMode ? 'bg-gray-900/50 border border-gray-700' : 'bg-gray-50 border border-gray-200'">
                                    
                                    <h3 class="font-bold text-lg mb-4 flex items-center" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        <i class="fas fa-credit-card text-blue-500 mr-3"></i>
                                        Card Information
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                Card Number
                                            </label>
                                            <div id="card-number" class="rounded-xl px-4 py-3 transition-all border-2"
                                                 :class="darkMode ? 'bg-gray-800 border-gray-700 focus-within:border-blue-500' : 'bg-white border-gray-300 focus-within:border-blue-500'">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    Expiry Date
                                                </label>
                                                <div id="card-expiry" class="rounded-xl px-4 py-3 transition-all border-2"
                                                     :class="darkMode ? 'bg-gray-800 border-gray-700 focus-within:border-blue-500' : 'bg-white border-gray-300 focus-within:border-blue-500'">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    CVC
                                                </label>
                                                <div id="card-cvc" class="rounded-xl px-4 py-3 transition-all border-2"
                                                     :class="darkMode ? 'bg-gray-800 border-gray-700 focus-within:border-blue-500' : 'bg-white border-gray-300 focus-within:border-blue-500'">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="card-errors" class="text-red-500 text-sm mt-3 flex items-center gap-2"></div>
                                </div>
                            @endif
                            
                            <!-- Enhanced Terms -->
                            <div class="mb-8 p-4 rounded-xl"
                                 :class="darkMode ? 'bg-gray-800/30 border border-gray-700' : 'bg-gray-50 border border-gray-200'">
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" name="terms" required 
                                           class="mt-1 mr-3 w-5 h-5 rounded border-2 text-[#C8102E] focus:ring-[#C8102E] focus:ring-offset-0"
                                           :class="darkMode ? 'bg-gray-800 border-gray-600' : 'bg-white border-gray-300'">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        I agree to the <a href="#" class="text-[#C8102E] hover:text-[#A00E27] underline font-semibold">Terms of Service</a> 
                                        and <a href="#" class="text-[#C8102E] hover:text-[#A00E27] underline font-semibold">Privacy Policy</a>
                                    </span>
                                </label>
                            </div>
                            
                            <!-- Enhanced Submit Button -->
                            <button type="submit" id="submit-button" 
                                    class="relative w-full py-4 rounded-xl font-black text-lg bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white transition-all transform hover:scale-105 hover:shadow-2xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden group">
                                <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                <span id="button-text" class="relative flex items-center justify-center">
                                    @if($isFree)
                                        <i class="fas fa-gift mr-3"></i>
                                        ACTIVATE FREE SUBSCRIPTION
                                    @else
                                        <i class="fas fa-lock mr-3"></i>
                                        COMPLETE PURCHASE • ৳{{ number_format($finalAmount, 0) }}
                                    @endif
                                </span>
                                <span id="button-loading" class="hidden relative flex items-center justify-center">
                                    <i class="fas fa-spinner fa-spin mr-3"></i> 
                                    Processing Your Order...
                                </span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Enhanced Security Badges -->
                    <div class="mt-8 p-6 rounded-2xl text-center"
                         :class="darkMode ? 'bg-gray-800/30' : 'bg-gray-50'">
                        <div class="flex flex-wrap items-center justify-center gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                                    <i class="fas fa-shield-check text-green-500"></i>
                                </div>
                                <span class="text-sm font-semibold" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                    256-bit SSL Encrypted
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-lock text-blue-500"></i>
                                </div>
                                <span class="text-sm font-semibold" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                    PCI DSS Compliant
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                    <i class="fas fa-user-shield text-purple-500"></i>
                                </div>
                                <span class="text-sm font-semibold" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                    Secure & Private
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Order Summary -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-4 space-y-6">
                        <!-- Summary Card -->
                        <div class="rounded-3xl p-6 shadow-2xl relative overflow-hidden"
                             :class="darkMode ? 'bg-gradient-to-br from-gray-800 to-gray-900 border border-[#C8102E]/30' : 'bg-gradient-to-br from-white to-gray-50 border-2 border-[#C8102E]/20'">
                            
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23C8102E" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                            </div>
                            
                            <h3 class="font-black text-xl mb-6 flex items-center relative" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                <i class="fas fa-shopping-cart text-[#C8102E] mr-3"></i>
                                Order Summary
                            </h3>
                            
                            <!-- Plan Details Card -->
                            <div class="rounded-2xl p-4 mb-6 relative"
                                 :class="darkMode ? 'bg-gray-800/50 border border-gray-700' : 'bg-white border border-gray-200 shadow-sm'">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#C8102E] to-[#A00E27] flex items-center justify-center shadow-lg">
                                        <i class="fas fa-crown text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                            {{ $plan->name }} Plan
                                        </h4>
                                        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                            {{ $plan->duration_days }} days access
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price Breakdown -->
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Subtotal</span>
                                    <span class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        ৳{{ number_format($plan->price, 0) }}
                                    </span>
                                </div>
                                
                                @if($plan->discount_price && !$couponDetails)
                                    <div class="flex justify-between items-center text-green-500">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-tag"></i>
                                            Plan Discount
                                        </span>
                                        <span class="font-semibold">-৳{{ number_format($plan->price - $plan->discount_price, 0) }}</span>
                                    </div>
                                @endif
                                
                                @if($couponDetails)
                                    <div class="flex justify-between items-center text-green-500">
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-ticket"></i>
                                            Coupon ({{ $couponDetails['code'] }})
                                        </span>
                                        <span class="font-semibold">{{ $couponDetails['formatted_discount'] }}</span>
                                    </div>
                                @endif
                                
                                <div class="pt-4 border-t-2" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                            Total
                                        </span>
                                        <span class="text-2xl font-black text-[#C8102E]">
                                            ৳{{ number_format($finalAmount, 0) }}
                                        </span>
                                    </div>
                                    @if($plan->duration_days > 1)
                                        <p class="text-xs mt-1 text-right" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                            ≈ ৳{{ number_format($finalAmount / $plan->duration_days, 0) }}/day
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Coupon Input -->
                            @if(!$couponDetails && !$isFree)
                            <div class="mb-6">
                                <button @click="showCouponInput = !showCouponInput" 
                                        class="w-full py-3 rounded-xl font-semibold transition-all border-2"
                                        :class="darkMode ? 
                                            'border-[#C8102E]/30 text-[#C8102E] hover:bg-[#C8102E]/10' : 
                                            'border-[#C8102E]/30 text-[#C8102E] hover:bg-[#C8102E]/5'">
                                    <i class="fas fa-tag mr-2"></i>
                                    Have a promo code?
                                </button>
                                
                                <div x-show="showCouponInput" x-collapse class="mt-4">
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               id="checkout-coupon-code"
                                               placeholder="Enter code"
                                               class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold uppercase"
                                               :class="darkMode ? 'bg-gray-800 text-white border border-gray-700' : 'bg-white text-gray-900 border border-gray-300'">
                                        <button onclick="applyCouponInCheckout()" 
                                                class="px-4 py-2 rounded-lg bg-[#C8102E] text-white font-semibold text-sm hover:bg-[#A00E27] transition-all">
                                            Apply
                                        </button>
                                    </div>
                                    <p id="checkout-coupon-error" class="text-red-500 text-xs mt-2 hidden"></p>
                                    <p id="checkout-coupon-success" class="text-green-500 text-xs mt-2 hidden"></p>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Billing Info -->
                            <div class="rounded-xl p-4 mb-6"
                                 :class="darkMode ? 'bg-blue-900/20 border border-blue-500/30' : 'bg-blue-50 border border-blue-200'">
                                <p class="text-sm flex items-start gap-2" :class="darkMode ? 'text-blue-300' : 'text-blue-700'">
                                    <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                                    <span>
                                        @if($isFree)
                                            You're getting this plan completely FREE!
                                        @else
                                            ৳{{ number_format($finalAmount, 0) }} will be charged today
                                        @endif
                                    </span>
                                </p>
                            </div>
                            
                            <!-- Benefits List -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-bolt text-green-500"></i>
                                    </div>
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        Instant access after payment
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-undo text-purple-500"></i>
                                    </div>
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        30-day money back guarantee
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-headset text-amber-500"></i>
                                    </div>
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                        24/7 customer support
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Support Card -->
                        <div class="rounded-2xl p-6 text-center"
                             :class="darkMode ? 'bg-gray-800/50 border border-gray-700' : 'bg-white border border-gray-200 shadow-lg'">
                            <i class="fas fa-question-circle text-3xl text-[#C8102E] mb-3"></i>
                            <p class="font-bold mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                Need Help?
                            </p>
                            <p class="text-sm mb-4" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                Our support team is here to help
                            </p>
                            <a href="#" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold text-sm transition-all"
                               :class="darkMode ? 
                                'bg-gray-700 text-white hover:bg-gray-600' : 
                                'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                                <i class="fas fa-comments"></i>
                                Live Chat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    </div>

    @push('styles')
    <style>
        /* Stripe Elements Custom Styling */
        .StripeElement {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Apply coupon in checkout
        async function applyCouponInCheckout() {
            const code = document.getElementById('checkout-coupon-code').value.trim();
            const planId = {{ $plan->id }};
            const errorDiv = document.getElementById('checkout-coupon-error');
            const successDiv = document.getElementById('checkout-coupon-success');
            
            if (!code) {
                errorDiv.textContent = 'Please enter a coupon code';
                errorDiv.classList.remove('hidden');
                return;
            }
            
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
            }
        }

        @if(!session('subscription_amount') || session('subscription_amount') > 0)
        // Initialize Stripe
        @if(config('services.stripe.key'))
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements({
            fonts: [{
                cssSrc: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
            }]
        });
        
        // Custom styling for Stripe elements
        const style = {
            base: {
                color: localStorage.getItem('darkMode') !== 'false' ? '#ffffff' : '#1f2937',
                fontFamily: '"Inter", sans-serif',
                fontSize: '16px',
                fontWeight: '500',
                '::placeholder': {
                    color: localStorage.getItem('darkMode') !== 'false' ? '#9ca3af' : '#6b7280'
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
                displayError.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + event.error.message;
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
            
            // Submit form
            this.submit();
        });
    </script>
    @endpush
</x-student-layout>