<x-student-layout>
    <x-slot:title>Choose Your Plan</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                    Choose Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Perfect Plan</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Select the ideal plan for your IELTS preparation journey and achieve your dream band score
                </p>
                
                <!-- Trust Badges -->
                <div class="flex flex-wrap items-center justify-center gap-6 mt-8">
                    <div class="flex items-center space-x-2 text-gray-400">
                        <i class="fas fa-users"></i>
                        <span>10,000+ Students</span>
                    </div>
                    <div class="flex items-center space-x-2 text-gray-400">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span>4.8/5 Rating</span>
                    </div>
                    <div class="flex items-center space-x-2 text-gray-400">
                        <i class="fas fa-shield-alt text-green-400"></i>
                        <span>Money Back Guarantee</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans Section -->
    <section class="px-4 sm:px-6 lg:px-8 pb-20 -mt-8">
        <div class="max-w-7xl mx-auto">
            <!-- Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                @foreach($plans as $plan)
                <div class="relative group">
                    @if($plan->is_featured)
                    <div class="absolute -top-5 left-1/2 transform -translate-x-1/2 z-20">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg neon-purple">
                            <i class="fas fa-star mr-1"></i>Most Popular
                        </span>
                    </div>
                    @endif
                    
                    <div class="glass rounded-2xl p-8 h-full border {{ $plan->is_featured ? 'border-purple-500/50 shadow-lg shadow-purple-500/20' : 'border-white/10' }} hover:border-purple-500/30 transition-all duration-300 {{ $plan->is_featured ? 'scale-105' : '' }}">
                        <!-- Plan Header -->
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br 
                                {{ $plan->slug === 'pro' ? 'from-purple-600 to-pink-600' : ($plan->slug === 'premium' ? 'from-blue-600 to-cyan-600' : 'from-gray-600 to-gray-700') }} 
                                p-0.5 mx-auto mb-4">
                                <div class="w-full h-full rounded-2xl bg-slate-900 flex items-center justify-center">
                                    <i class="fas {{ $plan->slug === 'pro' ? 'fa-crown' : ($plan->slug === 'premium' ? 'fa-gem' : 'fa-user') }} text-3xl text-white"></i>
                                </div>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                            <p class="text-gray-400 text-sm">{{ $plan->description }}</p>
                        </div>
                        
                        <!-- Price -->
                        <div class="text-center mb-8">
                            @if($plan->discount_price)
                            <div class="flex items-baseline justify-center">
                                <span class="text-4xl font-bold text-white">৳{{ number_format($plan->discount_price, 0) }}</span>
                                <span class="text-xl text-gray-500 line-through ml-2">৳{{ number_format($plan->price, 0) }}</span>
                            </div>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/50">
                                Save {{ round((($plan->price - $plan->discount_price) / $plan->price) * 100) }}%
                            </span>
                            @else
                            <div class="flex items-baseline justify-center">
                                <span class="text-4xl font-bold text-white">
                                    @if($plan->is_free)
                                        Free
                                    @else
                                        ৳{{ number_format($plan->price, 0) }}
                                    @endif
                                </span>
                                @if(!$plan->is_free)
                                <span class="text-gray-400 ml-2">/ month</span>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <!-- Features -->
                        <ul class="space-y-4 mb-8">
                            @foreach($plan->features as $feature)
                            <li class="flex items-start">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-300 ml-3">
                                    {{ $feature->name }}
                                    @if($feature->pivot->value && $feature->pivot->value !== 'true')
                                        <span class="text-purple-400 font-medium">({{ $feature->pivot->value }})</span>
                                    @endif
                                </span>
                            </li>
                            @endforeach
                        </ul>
                        
                        <!-- Action Button -->
                        @auth
                            @if($currentPlan && $currentPlan->id === $plan->id)
                                <button class="w-full glass text-gray-400 px-6 py-3 rounded-xl font-semibold cursor-not-allowed border border-gray-600">
                                    <i class="fas fa-check-circle mr-2"></i>Current Plan
                                </button>
                            @else
                                <div class="space-y-3">
                                    <form action="{{ route('subscription.subscribe', $plan) }}" method="POST" id="subscribeForm-{{ $plan->id }}">
                                        @csrf
                                        <input type="hidden" name="coupon_code" id="couponInput-{{ $plan->id }}">
                                        <button type="submit" class="w-full px-6 py-3 rounded-xl font-semibold transition-all
                                            {{ $plan->is_featured 
                                                ? 'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white shadow-lg hover:shadow-xl neon-purple' 
                                                : 'glass text-white hover:border-purple-500/50 border border-white/20' }}">
                                            @if($plan->is_free)
                                                <i class="fas fa-arrow-down mr-2"></i>Switch to Free
                                            @elseif($currentPlan && $currentPlan->price < $plan->price)
                                                <i class="fas fa-rocket mr-2"></i>Upgrade Now
                                            @else
                                                <i class="fas fa-bolt mr-2"></i>Get Started
                                            @endif
                                        </button>
                                    </form>
                                    
                                    @if(!$plan->is_free)
                                        <button onclick="openCouponModal({{ $plan->id }})" 
                                                class="w-full glass text-purple-400 px-6 py-3 rounded-xl font-medium hover:text-purple-300 hover:border-purple-500/50 transition-all border border-purple-500/30">
                                            <i class="fas fa-tag mr-2"></i>Have a Coupon?
                                        </button>
                                    @endif
                                </div>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all shadow-lg hover:shadow-xl neon-purple">
                                <i class="fas fa-user-plus mr-2"></i>Sign Up to Start
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Comparison Table -->
            <div class="glass rounded-2xl p-8 mb-16 border border-white/10">
                <h3 class="text-2xl font-bold text-white mb-6 text-center">Compare Plans</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left py-4 px-4 text-gray-400 font-medium">Features</th>
                                <th class="text-center py-4 px-4 text-gray-400 font-medium">Free</th>
                                <th class="text-center py-4 px-4 text-purple-400 font-medium">Premium</th>
                                <th class="text-center py-4 px-4 text-pink-400 font-medium">Pro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-white/5">
                                <td class="py-4 px-4 text-gray-300">Mock Tests per Month</td>
                                <td class="text-center py-4 px-4 text-white">3</td>
                                <td class="text-center py-4 px-4 text-white">Unlimited</td>
                                <td class="text-center py-4 px-4 text-white">Unlimited</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-4 px-4 text-gray-300">AI Evaluation</td>
                                <td class="text-center py-4 px-4">
                                    <i class="fas fa-times text-gray-500"></i>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <i class="fas fa-check text-green-400"></i>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <i class="fas fa-check text-green-400"></i>
                                </td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="py-4 px-4 text-gray-300">Priority Support</td>
                                <td class="text-center py-4 px-4">
                                    <i class="fas fa-times text-gray-500"></i>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <i class="fas fa-check text-green-400"></i>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <i class="fas fa-check text-green-400"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-white mb-8 text-center">
                    <i class="fas fa-question-circle text-purple-400 mr-3"></i>
                    Frequently Asked Questions
                </h2>
                
                <div class="space-y-6">
                    <div class="glass rounded-xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                        <h3 class="font-semibold text-white mb-2 flex items-center">
                            <i class="fas fa-sync-alt text-purple-400 mr-3"></i>
                            Can I change my plan anytime?
                        </h3>
                        <p class="text-gray-400">Yes, you can upgrade or downgrade your plan at any time. Changes will take effect immediately, and you'll be charged or credited proportionally.</p>
                    </div>
                    
                    <div class="glass rounded-xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                        <h3 class="font-semibold text-white mb-2 flex items-center">
                            <i class="fas fa-credit-card text-blue-400 mr-3"></i>
                            What payment methods do you accept?
                        </h3>
                        <p class="text-gray-400">We accept international cards through Stripe, and local payments through bKash and Nagad for your convenience.</p>
                    </div>
                    
                    <div class="glass rounded-xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                        <h3 class="font-semibold text-white mb-2 flex items-center">
                            <i class="fas fa-gift text-green-400 mr-3"></i>
                            Is there a free trial?
                        </h3>
                        <p class="text-gray-400">We offer a generous free plan that includes 3 mock tests per month. This allows you to experience our platform before upgrading.</p>
                    </div>
                    
                    <div class="glass rounded-xl p-6 border border-white/10 hover:border-purple-500/30 transition-all">
                        <h3 class="font-semibold text-white mb-2 flex items-center">
                            <i class="fas fa-ban text-red-400 mr-3"></i>
                            Can I cancel my subscription?
                        </h3>
                        <p class="text-gray-400">Yes, you can cancel your subscription anytime. You'll continue to have access until the end of your billing period.</p>
                    </div>
                </div>
            </div>

            <!-- Money Back Guarantee -->
            <div class="text-center">
                <div class="inline-flex items-center glass rounded-2xl px-8 py-6 border border-green-500/30">
                    <i class="fas fa-shield-alt text-4xl text-green-400 mr-4"></i>
                    <div class="text-left">
                        <p class="font-bold text-white text-lg">30-Day Money Back Guarantee</p>
                        <p class="text-sm text-gray-400">Not satisfied? Get a full refund within 30 days, no questions asked.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Coupon Modal -->
<div id="couponModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div onclick="closeCouponModal()" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        
        <div class="relative glass rounded-2xl w-full max-w-md p-6 lg:p-8 transform transition-all">
            
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-white">
                    <i class="fas fa-tag text-purple-400 mr-2"></i>
                    Have a Coupon?
                </h3>
                <button onclick="closeCouponModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="couponForm" onsubmit="validateCoupon(event)">
                <input type="hidden" id="selectedPlanId" value="">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Enter Coupon Code</label>
                    <input type="text" 
                           id="couponCode"
                           placeholder="e.g., SAVE50"
                           class="w-full glass bg-transparent text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 uppercase"
                           required>
                    <p id="couponError" class="mt-2 text-sm text-red-400 hidden"></p>
                </div>
                
                <!-- Coupon Result -->
                <div id="couponResult" class="hidden mb-6">
                    <div class="glass rounded-xl p-4 border border-green-500/30 bg-green-500/10">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-green-400 font-medium">
                                <i class="fas fa-check-circle mr-2"></i>
                                Coupon Applied!
                            </p>
                            <span id="discountBadge" class="text-xl font-bold text-green-400"></span>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Original Price:</span>
                                <span class="text-white line-through" id="originalPrice"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Discount:</span>
                                <span class="text-green-400" id="discountAmount"></span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-white/10">
                                <span class="text-white font-medium">Final Price:</span>
                                <span class="text-2xl font-bold text-white" id="finalPrice"></span>
                            </div>
                        </div>
                        
                        <p id="couponDescription" class="mt-3 text-xs text-gray-400"></p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" 
                            id="validateBtn"
                            class="flex-1 py-3 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all shadow-lg hover:shadow-xl">
                        <span id="validateBtnText">
                            <i class="fas fa-check mr-2"></i>Apply Coupon
                        </span>
                        <span id="validateBtnLoading" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Validating...
                        </span>
                    </button>
                    <button type="button" 
                            onclick="closeCouponModal()" 
                            class="px-6 py-3 rounded-xl glass text-white hover:border-gray-500/50 transition-all">
                        Cancel
                    </button>
                </div>
                
                <!-- Continue Button (shown after successful validation) -->
                <button type="button" 
                        id="continueBtn"
                        onclick="proceedWithCoupon()"
                        class="hidden w-full mt-3 py-3 rounded-xl font-semibold bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-right mr-2"></i>Continue to Checkout
                </button>
            </form>
        </div>
    </div>
</div>

   @push('scripts')
<script>
    let validatedCoupon = null;

    function openCouponModal(planId) {
        console.log('Opening coupon modal for plan:', planId); // Debug
        
        document.getElementById('selectedPlanId').value = planId;
        document.getElementById('couponCode').value = '';
        document.getElementById('couponError').classList.add('hidden');
        document.getElementById('couponResult').classList.add('hidden');
        document.getElementById('continueBtn').classList.add('hidden');
        document.getElementById('validateBtn').style.display = 'block';
        
        // Show modal
        const modal = document.getElementById('couponModal');
        modal.classList.remove('hidden');
        
        // Focus on input
        setTimeout(() => {
            document.getElementById('couponCode').focus();
        }, 100);
    }

    function closeCouponModal() {
        const modal = document.getElementById('couponModal');
        modal.classList.add('hidden');
        
        // Reset form
        document.getElementById('couponForm').reset();
        validatedCoupon = null;
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCouponModal();
        }
    });

    async function validateCoupon(event) {
        event.preventDefault();
        
        const planId = document.getElementById('selectedPlanId').value;
        const code = document.getElementById('couponCode').value.toUpperCase();
        const validateBtn = document.getElementById('validateBtn');
        const validateBtnText = document.getElementById('validateBtnText');
        const validateBtnLoading = document.getElementById('validateBtnLoading');
        const errorDiv = document.getElementById('couponError');
        const resultDiv = document.getElementById('couponResult');
        
        // Show loading
        validateBtn.disabled = true;
        validateBtnText.classList.add('hidden');
        validateBtnLoading.classList.remove('hidden');
        errorDiv.classList.add('hidden');
        
        try {
            const response = await fetch('{{ route('coupon.validate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    plan_id: planId
                })
            });
            
            const data = await response.json();
            
            if (response.ok && data.valid) {
                // Success
                validatedCoupon = data;
                
                // Show result
                document.getElementById('discountBadge').textContent = data.coupon.formatted_discount;
                document.getElementById('originalPrice').textContent = '৳' + data.pricing.original_price.toFixed(0);
                document.getElementById('discountAmount').textContent = '-৳' + data.pricing.discount_amount.toFixed(0);
                document.getElementById('finalPrice').textContent = '৳' + data.pricing.final_price.toFixed(0);
                
                if (data.coupon.description) {
                    document.getElementById('couponDescription').textContent = data.coupon.description;
                }
                
                resultDiv.classList.remove('hidden');
                validateBtn.style.display = 'none';
                document.getElementById('continueBtn').classList.remove('hidden');
                
                // Store coupon code
                document.getElementById('couponInput-' + planId).value = code;
                
            } else {
                // Error
                errorDiv.textContent = data.message || 'Invalid coupon code';
                errorDiv.classList.remove('hidden');
            }
            
        } catch (error) {
            console.error('Error validating coupon:', error);
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
        } finally {
            validateBtn.disabled = false;
            validateBtnText.classList.remove('hidden');
            validateBtnLoading.classList.add('hidden');
        }
    }

    function proceedWithCoupon() {
        if (validatedCoupon) {
            const planId = document.getElementById('selectedPlanId').value;
            document.getElementById('subscribeForm-' + planId).submit();
        }
    }
</script>

<style>
    /* Modal animations */
    #couponModal {
        animation: fadeIn 0.3s ease-out;
    }
    
    #couponModal > div > div:last-child {
        animation: slideUp 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endpush
</x-student-layout>