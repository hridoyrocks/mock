<x-student-layout>
    <x-slot:title>Choose Your Plan</x-slot>
    
    <!-- Header Section (Smaller) -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-3xl lg:text-4xl font-bold text-white mb-3">
                    Choose Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Perfect Plan</span>
                </h1>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Select the ideal plan for your IELTS preparation journey
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-20 -mt-4">
        <div class="max-w-7xl mx-auto">
            <!-- Billing Period Toggle -->
            <div class="flex justify-center mb-8">
                <div class="glass rounded-xl p-1 inline-flex flex-wrap justify-center gap-1">
                    <button onclick="setPeriod('monthly')" id="monthly-btn" class="px-3 sm:px-6 py-2 sm:py-3 rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium transition-all text-sm sm:text-base">
                        Monthly
                    </button>
                    <button onclick="setPeriod('quarterly')" id="quarterly-btn" class="px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-gray-400 hover:text-white transition-all relative text-sm sm:text-base pr-8 sm:pr-10">
                        3 Months
                        <span class="absolute -top-1 right-1 px-1 py-0.5 text-[10px] sm:text-xs bg-green-500 text-white rounded-full whitespace-nowrap">Save 10%</span>
                    </button>
                    <button onclick="setPeriod('yearly')" id="yearly-btn" class="px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-gray-400 hover:text-white transition-all relative text-sm sm:text-base pr-8 sm:pr-10">
                        Yearly
                        <span class="absolute -top-1 right-1 px-1 py-0.5 text-[10px] sm:text-xs bg-green-500 text-white rounded-full whitespace-nowrap">Save 20%</span>
                    </button>
                </div>
            </div>

            <!-- Plans Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16">
                @forelse($plans as $plan)
                <div class="relative group transform transition-all duration-300 {{ $plan->is_featured ? 'lg:scale-105' : 'hover:scale-105' }}">
                    @if($plan->is_featured)
                    <div class="absolute -top-3 sm:-top-5 left-1/2 transform -translate-x-1/2 z-20">
                        <span class="px-2 sm:px-4 py-1 sm:py-2 rounded-full text-[10px] sm:text-sm font-semibold bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg whitespace-nowrap">
                            <i class="fas fa-star mr-0.5 sm:mr-1 text-[10px] sm:text-sm"></i>Recommended
                        </span>
                    </div>
                    @endif
                    
                    <div class="glass rounded-2xl h-full border {{ $plan->is_featured ? 'border-purple-500/50 shadow-xl shadow-purple-500/20' : 'border-white/10' }} hover:border-purple-500/30 transition-all duration-300">
                        <!-- Plan Header -->
                        <div class="p-6 pb-0">
                            <!-- Plan Icon -->
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br 
                                {{ $plan->slug === 'pro' ? 'from-purple-600 to-pink-600' : ($plan->slug === 'premium' ? 'from-blue-600 to-cyan-600' : 'from-gray-600 to-gray-700') }} 
                                p-0.5 mx-auto mb-4">
                                <div class="w-full h-full rounded-2xl bg-slate-900 flex items-center justify-center">
                                    <i class="fas {{ $plan->slug === 'pro' ? 'fa-crown' : ($plan->slug === 'premium' ? 'fa-gem' : 'fa-user') }} text-2xl text-white"></i>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                                <p class="text-gray-400 text-sm mb-4">{{ $plan->description }}</p>
                            </div>
                            
                            <!-- Price (Dynamic based on period) -->
                            <div class="text-center mb-6">
                                @if($plan->is_free)
                                    <div class="text-5xl font-bold text-white">Free</div>
                                    <p class="text-gray-400 text-sm mt-2">Forever</p>
                                @else
                                    <!-- Monthly Price -->
                                    <div class="monthly-price">
                                        <div class="text-5xl font-bold text-white">৳{{ number_format($plan->discount_price ?? $plan->price, 0) }}</div>
                                        <p class="text-gray-400 text-sm mt-2">/month</p>
                                    </div>
                                    <!-- Quarterly Price -->
                                    <div class="quarterly-price hidden">
                                        <div class="text-5xl font-bold text-white">৳{{ number_format(($plan->discount_price ?? $plan->price) * 2.7, 0) }}</div>
                                        <p class="text-gray-400 text-sm mt-2">for 3 months</p>
                                        <p class="text-green-400 text-xs">৳{{ number_format(($plan->discount_price ?? $plan->price) * 0.9, 0) }}/month</p>
                                    </div>
                                    <!-- Yearly Price -->
                                    <div class="yearly-price hidden">
                                        <div class="text-5xl font-bold text-white">৳{{ number_format(($plan->discount_price ?? $plan->price) * 9.6, 0) }}</div>
                                        <p class="text-gray-400 text-sm mt-2">for 12 months</p>
                                        <p class="text-green-400 text-xs">৳{{ number_format(($plan->discount_price ?? $plan->price) * 0.8, 0) }}/month</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- CTA Button -->
                            <div class="mb-6">
                                @auth
                                    @if($currentPlan && $currentPlan->id === $plan->id)
                                        <button class="w-full glass text-gray-400 px-6 py-3 rounded-xl font-semibold cursor-not-allowed border border-gray-600">
                                            <i class="fas fa-check-circle mr-2"></i>Current Plan
                                        </button>
                                    @else
                                        <form action="{{ route('subscription.subscribe', $plan) }}" method="POST" id="subscribeForm-{{ $plan->id }}">
                                            @csrf
                                            <input type="hidden" name="coupon_code" id="couponInput-{{ $plan->id }}">
                                            <input type="hidden" name="billing_period" id="billingPeriod-{{ $plan->id }}" value="monthly">
                                            <button type="submit" class="w-full px-6 py-3 rounded-xl font-semibold transition-all
                                                {{ $plan->is_featured 
                                                    ? 'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white shadow-lg hover:shadow-xl' 
                                                    : 'glass text-white hover:border-purple-500/50 border border-white/20' }}">
                                                @if($plan->is_free)
                                                    <i class="fas fa-arrow-down mr-2"></i>Get Started Free
                                                @elseif($currentPlan && $currentPlan->price < $plan->price)
                                                    <i class="fas fa-rocket mr-2"></i>Upgrade Now
                                                @else
                                                    <i class="fas fa-bolt mr-2"></i>Get Started
                                                @endif
                                            </button>
                                        </form>
                                        
                                        @if(!$plan->is_free)
                                            <button onclick="openCouponModal({{ $plan->id }})" 
                                                    class="w-full mt-3 text-sm text-purple-400 hover:text-purple-300 transition-all">
                                                <i class="fas fa-tag mr-1"></i>Have a promo code?
                                            </button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all shadow-lg hover:shadow-xl">
                                        <i class="fas fa-user-plus mr-2"></i>Sign Up to Start
                                    </a>
                                @endauth
                            </div>
                        </div>
                        
                        <!-- Features (Compact) -->
                        <div class="px-6 pb-6">
                            <div class="pt-4 border-t border-white/10">
                                <ul class="space-y-2">
                                    @php $featureCount = 0; @endphp
                                    
                                    @if($plan->features)
                                        @foreach($plan->features as $feature)
                                            @if($featureCount < 5)
                                                <li class="flex items-center">
                                                    <i class="fas fa-check text-green-400 mr-2 text-sm"></i>
                                                    <span class="text-gray-300 text-sm">
                                                        {{ $feature->name }}
                                                        @if($feature->pivot->value && $feature->pivot->value !== 'true')
                                                            <span class="text-purple-400 font-medium">({{ $feature->pivot->value }})</span>
                                                        @endif
                                                    </span>
                                                </li>
                                                @php $featureCount++; @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-400">No subscription plans available at the moment.</p>
                </div>
                @endforelse
            </div>

            <!-- Feature Icons Grid -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-white text-center mb-8">Everything You Get</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-clipboard-check text-blue-400 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-1">Mock Tests</h3>
                        <p class="text-sm text-gray-400">Full-length practice tests</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-robot text-purple-400 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-1">AI Evaluation</h3>
                        <p class="text-sm text-gray-400">Instant feedback</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-user-check text-green-400 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-1">Human Review</h3>
                        <p class="text-sm text-gray-400">Expert teachers</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-chart-line text-amber-400 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-1">Progress Tracking</h3>
                        <p class="text-sm text-gray-400">Detailed analytics</p>
                    </div>
                </div>
            </div>

            <!-- Comparison Table (Dynamic) -->
            @if($plans->count() > 0)
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-white text-center mb-8">Compare Plans</h2>
                
                <div class="glass rounded-xl overflow-hidden border border-white/10">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-4 px-4 text-gray-400 font-medium">Features</th>
                                    @foreach($plans as $plan)
                                    <th class="text-center py-4 px-4 {{ $plan->is_featured ? 'bg-purple-500/10' : '' }}">
                                        <div class="font-semibold text-white">{{ $plan->name }}</div>
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allFeatures = \App\Models\SubscriptionFeature::orderBy('id')->get();
                                    $rowIndex = 0;
                                @endphp
                                
                                @foreach($allFeatures as $feature)
                                <tr class="border-b border-white/5 {{ $rowIndex % 2 == 1 ? 'bg-white/2' : '' }}">
                                    <td class="py-3 px-4 text-gray-300">{{ $feature->name }}</td>
                                    @foreach($plans as $plan)
                                        @php
                                            $planFeature = $plan && $plan->features ? $plan->features->where('id', $feature->id)->first() : null;
                                        @endphp
                                        <td class="text-center py-3 px-4 {{ $plan && $plan->is_featured ? 'bg-purple-500/10' : '' }}">
                                            @if($planFeature && $planFeature->pivot)
                                                @if($planFeature->pivot->value === 'true' || $planFeature->pivot->value === '1')
                                                    <i class="fas fa-check text-green-400"></i>
                                                @elseif($planFeature->pivot->value === 'false' || $planFeature->pivot->value === '0' || !$planFeature->pivot->value)
                                                    <i class="fas fa-times text-gray-500"></i>
                                                @else
                                                    <span class="text-white font-medium">{{ $planFeature->pivot->value }}</span>
                                                @endif
                                            @else
                                                <i class="fas fa-times text-gray-500"></i>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @php $rowIndex++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Money Back Guarantee -->
            <div class="text-center">
                <div class="inline-flex items-center glass rounded-xl px-6 py-4 border border-green-500/30">
                    <i class="fas fa-shield-check text-3xl text-green-400 mr-3"></i>
                    <div class="text-left">
                        <p class="font-bold text-white">30-Day Money Back Guarantee</p>
                        <p class="text-sm text-gray-400">Not satisfied? Get a full refund.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coupon Modal (Same as before) -->
    <div id="couponModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div onclick="closeCouponModal()" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative glass rounded-2xl w-full max-w-md p-8 transform transition-all">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white">Apply Promo Code</h3>
                    <button onclick="closeCouponModal()" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="couponForm" onsubmit="validateCoupon(event)">
                    <input type="hidden" id="selectedPlanId" value="">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Enter your promo code</label>
                        <input type="text" 
                               id="couponCode"
                               placeholder="e.g., SAVE20"
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
                                    Promo code applied!
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
                                    <span class="text-white font-medium">You Pay:</span>
                                    <span class="text-2xl font-bold text-white" id="finalPrice"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" 
                                id="validateBtn"
                                class="flex-1 py-3 rounded-xl font-semibold bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all">
                            <span id="validateBtnText">Apply Code</span>
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
                    
                    <!-- Continue Button -->
                    <button type="button" 
                            id="continueBtn"
                            onclick="proceedWithCoupon()"
                            class="hidden w-full mt-3 py-3 rounded-xl font-semibold bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white transition-all">
                        <i class="fas fa-arrow-right mr-2"></i>Continue to Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentPeriod = 'monthly';
        let validatedCoupon = null;

        function setPeriod(period) {
            currentPeriod = period;
            
            // Update button styles
            document.querySelectorAll('#monthly-btn, #quarterly-btn, #yearly-btn').forEach(btn => {
                btn.classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white');
                btn.classList.add('text-gray-400');
            });
            
            document.getElementById(period + '-btn').classList.remove('text-gray-400');
            document.getElementById(period + '-btn').classList.add('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white');
            
            // Update prices
            document.querySelectorAll('.monthly-price, .quarterly-price, .yearly-price').forEach(el => {
                el.classList.add('hidden');
            });
            
            document.querySelectorAll('.' + period + '-price').forEach(el => {
                el.classList.remove('hidden');
            });
            
            // Update hidden inputs
            document.querySelectorAll('[id^="billingPeriod-"]').forEach(input => {
                input.value = period;
            });
        }

        function openCouponModal(planId) {
            document.getElementById('selectedPlanId').value = planId;
            document.getElementById('couponCode').value = '';
            document.getElementById('couponError').classList.add('hidden');
            document.getElementById('couponResult').classList.add('hidden');
            document.getElementById('continueBtn').classList.add('hidden');
            document.getElementById('validateBtn').style.display = 'block';
            document.getElementById('couponModal').classList.remove('hidden');
            
            setTimeout(() => {
                document.getElementById('couponCode').focus();
            }, 100);
        }

        function closeCouponModal() {
            document.getElementById('couponModal').classList.add('hidden');
            document.getElementById('couponForm').reset();
            validatedCoupon = null;
        }

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
                    validatedCoupon = data;
                    
                    document.getElementById('discountBadge').textContent = data.coupon.formatted_discount;
                    document.getElementById('originalPrice').textContent = '৳' + data.pricing.original_price.toFixed(0);
                    document.getElementById('discountAmount').textContent = '-৳' + data.pricing.discount_amount.toFixed(0);
                    document.getElementById('finalPrice').textContent = '৳' + data.pricing.final_price.toFixed(0);
                    
                    resultDiv.classList.remove('hidden');
                    validateBtn.style.display = 'none';
                    document.getElementById('continueBtn').classList.remove('hidden');
                    
                    document.getElementById('couponInput-' + planId).value = code;
                    
                } else {
                    errorDiv.textContent = data.message || 'Invalid promo code';
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
    @endpush
</x-student-layout>