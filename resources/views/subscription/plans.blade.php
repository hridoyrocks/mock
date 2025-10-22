<x-student-layout>
    <x-slot:title>Choose Your Plan</x-slot>
    
    <div x-data="{ 
        showAllFeatures: false,
        hoveredPlan: null
    }" x-init="() => { if (typeof darkMode === 'undefined') { darkMode = localStorage.getItem('darkMode') !== 'false'; } }">
    
    <!-- Clean Header -->
    <section class="py-12 mb-8">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Choose Your Perfect Plan
                </h1>
                <p class="text-lg lg:text-xl mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                    Start preparing for your IELTS exam with confidence
                </p>
                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                    <i class="fas fa-users mr-2 text-[#C8102E]"></i>Join 50,000+ successful students
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content with Visual Elements -->
    <section class="px-4 sm:px-6 lg:px-8 pb-16">
        <div class="max-w-6xl mx-auto">

            <!-- Unique Plans Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mb-12">
                @forelse($plans as $index => $plan)
                    <div @mouseenter="hoveredPlan = {{ $index }}"
                         @mouseleave="hoveredPlan = null"
                         class="relative group">
                        
                        <!-- Glow Effect on Hover -->
                        <div class="absolute -inset-0.5 rounded-2xl opacity-0 group-hover:opacity-100 transition duration-300"
                             :class="'{{ $plan->is_featured ? 'bg-gradient-to-r from-[#C8102E] to-[#A00E27]' : 'bg-gradient-to-r from-gray-600 to-gray-700' }}' + ' blur-sm'">
                        </div>
                        
                        @if($plan->is_featured)
                        <!-- Featured Badge - Simplified -->
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 z-20">
                            <div class="px-4 py-1.5 bg-[#C8102E] text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1.5">
                                <i class="fas fa-star text-yellow-300"></i>
                                MOST POPULAR
                            </div>
                        </div>
                        @endif
                        
                        <div class="relative h-full rounded-xl overflow-hidden transition-all duration-300 transform hover:shadow-xl"
                             :class="darkMode ? 
                                'bg-gray-800 border-2 {{ $plan->is_featured ? 'border-[#C8102E]' : 'border-gray-700' }}' : 
                                'bg-white border-2 {{ $plan->is_featured ? 'border-[#C8102E]' : 'border-gray-200' }} shadow-md'">
                            
                            <!-- Plan Header - Simplified -->
                            <div class="relative p-6 {{ $plan->is_featured ? 'bg-gradient-to-b from-[#C8102E]/5 to-transparent' : '' }}">
                                
                                <!-- Plan Icon - Simplified -->
                                <div class="w-16 h-16 mx-auto mb-4 rounded-lg
                                    {{ $plan->is_featured ? 'bg-[#C8102E]' : 'bg-gray-600' }} 
                                    flex items-center justify-center shadow-lg">
                                    <i class="fas {{ $plan->slug === 'pro' ? 'fa-crown' : ($plan->slug === 'premium' ? 'fa-gem' : 'fa-user') }} text-2xl text-white"></i>
                                </div>
                                
                                <!-- Plan Name -->
                                <h3 class="text-2xl font-bold text-center mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                    {{ $plan->name }}
                                </h3>
                                <p class="text-center text-sm mb-6" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                    {{ $plan->description }}
                                </p>
                                
                                <!-- Price - Clean Design -->
                                <div class="text-center mb-6">
                                    @if($plan->is_free)
                                        <div class="text-4xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">FREE</div>
                                        <p class="text-sm mt-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Forever</p>
                                    @else
                                        <div class="relative">
                                            @if($plan->discount_price && $plan->discount_price < $plan->price)
                                                <div class="mb-1">
                                                    <span class="text-sm line-through" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">
                                                        ৳{{ number_format($plan->price, 0) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="flex items-baseline justify-center gap-1">
                                                <span class="text-3xl font-bold text-[#C8102E]">৳</span>
                                                <span class="text-5xl font-bold text-[#C8102E]">
                                                    {{ number_format($plan->current_price, 0) }}
                                                </span>
                                            </div>
                                            <p class="text-sm mt-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                                @if($plan->duration_days == 30)
                                                    per month
                                                @elseif($plan->duration_days == 90)
                                                    every 3 months
                                                @elseif($plan->duration_days == 365)
                                                    per year
                                                @else
                                                    for {{ $plan->duration_days }} days
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Features with Simple Icons -->
                            <div class="px-6 pb-6">
                                <div class="space-y-2.5">
                                    @if($plan->relationLoaded('features') && $plan->features->count() > 0)
                                        @foreach($plan->features->take(5) as $feature)
                                            <div class="flex items-start gap-2.5">
                                                <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                                </div>
                                                <span class="text-sm leading-relaxed" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    {{ $feature->name }}
                                                    @if($feature->pivot && $feature->pivot->value && $feature->pivot->value !== 'true')
                                                        <span class="text-[#C8102E] font-semibold ml-1">({{ $feature->pivot->value }})</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-center py-4" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                            No features configured
                                        </p>
                                    @endif
                                </div>
                                
                                @if($plan->relationLoaded('features') && $plan->features->count() > 5)
                                    <button @click="showAllFeatures = true" 
                                            class="mt-4 text-sm text-[#C8102E] hover:text-[#A00E27] font-medium">
                                        + View all {{ $plan->features->count() }} features
                                    </button>
                                @endif
                            </div>
                            
                            <!-- CTA Section - Clean Design -->
                            <div class="p-6 border-t" :class="darkMode ? 'border-gray-700' : 'border-gray-100'">
                                @auth
                                    @if($currentPlan && $currentPlan->id === $plan->id)
                                        <button class="w-full py-3.5 rounded-lg font-semibold cursor-not-allowed transition-all"
                                                :class="darkMode ? 'bg-gray-700 text-gray-400' : 'bg-gray-100 text-gray-500'">
                                            <i class="fas fa-check-circle mr-2"></i>Current Plan
                                        </button>
                                    @else
                                        <form action="{{ route('subscription.subscribe', $plan) }}" method="POST" id="subscribeForm-{{ $plan->id }}">
                                            @csrf
                                            <input type="hidden" name="coupon_code" id="couponInput-{{ $plan->id }}">
                                            <button type="submit" 
                                                    class="w-full py-3.5 rounded-lg font-semibold transition-all
                                                    {{ $plan->is_featured 
                                                        ? 'bg-[#C8102E] hover:bg-[#A00E27] text-white shadow-md hover:shadow-lg' 
                                                        : '' }}"
                                                    :class="!{{ $plan->is_featured ? 'true' : 'false' }} && (darkMode ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'border-2 border-gray-300 hover:border-[#C8102E] text-gray-700 hover:text-[#C8102E] bg-white')">
                                                
                                                @if($plan->is_free)
                                                    <i class="fas fa-arrow-right mr-2"></i>Start Free
                                                @elseif($currentPlan && $currentPlan->price < $plan->price)
                                                    <i class="fas fa-arrow-up mr-2"></i>Upgrade Plan
                                                @else
                                                    <i class="fas fa-check mr-2"></i>Choose Plan
                                                @endif
                                            </button>
                                        </form>
                                        
                                        @if(!$plan->is_free)
                                            <button onclick="openCouponModal({{ $plan->id }})" 
                                                    class="w-full mt-2.5 py-2 text-xs font-medium hover:underline transition-all"
                                                    :class="darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-[#C8102E]'">
                                                <i class="fas fa-tag mr-1.5"></i>Have a discount code?
                                            </button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('register') }}" 
                                       class="block w-full text-center py-3.5 rounded-lg font-semibold bg-[#C8102E] hover:bg-[#A00E27] text-white transition-all shadow-md hover:shadow-lg">
                                        <i class="fas fa-user-plus mr-2"></i>Sign Up to Start
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16">
                        <i class="fas fa-box-open text-6xl mb-4" :class="darkMode ? 'text-gray-600' : 'text-gray-300'"></i>
                        <p class="text-lg" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">No plans available</p>
                    </div>
                @endforelse
            </div>

            <!-- Why Choose Us - Simple Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
                <div class="p-6 rounded-lg text-center transition-all hover:shadow-lg"
                     :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-infinity text-xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Unlimited Tests</h3>
                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Practice anytime you want</p>
                </div>
                
                <div class="p-6 rounded-lg text-center transition-all hover:shadow-lg"
                     :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-brain text-xl text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">AI Evaluation</h3>
                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Smart feedback system</p>
                </div>
                
                <div class="p-6 rounded-lg text-center transition-all hover:shadow-lg"
                     :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-chart-line text-xl text-green-600"></i>
                    </div>
                    <h3 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Track Progress</h3>
                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Monitor improvement</p>
                </div>
                
                <div class="p-6 rounded-lg text-center transition-all hover:shadow-lg"
                     :class="darkMode ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-certificate text-xl text-amber-600"></i>
                    </div>
                    <h3 class="font-semibold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Get Certified</h3>
                    <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Achieve your goals</p>
                </div>
            </div>

            <!-- Help Button -->
            <div class="fixed bottom-6 right-6 z-40">
                <a href="#" 
                   class="flex items-center justify-center w-14 h-14 rounded-full bg-[#C8102E] hover:bg-[#A00E27] text-white shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-question text-lg"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- All Features Modal -->
    <div x-show="showAllFeatures" 
         x-cloak
         @click.away="showAllFeatures = false"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="showAllFeatures = false" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
            
            <div class="relative rounded-2xl w-full max-w-4xl p-8 max-h-[80vh] overflow-y-auto"
                 :class="darkMode ? 'bg-gray-900' : 'bg-white shadow-2xl'">
                <button @click="showAllFeatures = false" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <h3 class="text-2xl font-black mb-6" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Compare All Features
                </h3>
                
                <!-- Features Comparison Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                                <th class="text-left py-3 px-4 font-bold" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">Features</th>
                                @foreach($plans->unique('name') as $plan)
                                    <th class="text-center py-3 px-4 font-bold {{ $plan->is_featured ? 'text-[#C8102E]' : '' }}"
                                        :class="darkMode ? 'text-white' : 'text-gray-900'">
                                        {{ $plan->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allFeatures = \App\Models\SubscriptionFeature::orderBy('id')->get();
                            @endphp
                            
                            @foreach($allFeatures as $feature)
                            <tr class="border-b" :class="darkMode ? 'border-gray-800' : 'border-gray-100'">
                                <td class="py-3 px-4" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                    {{ $feature->name }}
                                </td>
                                @foreach($plans->unique('name') as $plan)
                                    @php
                                        $planFeature = $plan->relationLoaded('features') 
                                            ? $plan->features->where('id', $feature->id)->first()
                                            : null;
                                    @endphp
                                    <td class="text-center py-3 px-4">
                                        @if($planFeature)
                                            @if($planFeature->pivot && $planFeature->pivot->value === 'true' || $planFeature->pivot && $planFeature->pivot->value === '1')
                                                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                            @elseif($planFeature->pivot && ($planFeature->pivot->value === 'false' || $planFeature->pivot->value === '0' || !$planFeature->pivot->value))
                                                <i class="fas fa-times-circle text-gray-400 text-lg"></i>
                                            @elseif($planFeature->pivot && $planFeature->pivot->value)
                                                <span class="font-bold text-[#C8102E]">
                                                    {{ $planFeature->pivot->value }}
                                                </span>
                                            @else
                                                <i class="fas fa-times-circle text-gray-400 text-lg"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-times-circle text-gray-400 text-lg"></i>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Coupon Modal -->
    <div id="couponModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div onclick="closeCouponModal()" class="fixed inset-0 bg-black bg-opacity-75 backdrop-blur"></div>
            
            <div class="relative rounded-3xl w-full max-w-md p-8 transform transition-all animate-slideUp"
                 :class="darkMode ? 'bg-gray-900' : 'bg-white shadow-2xl'">
                
                <!-- Decorative Elements -->
                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] flex items-center justify-center shadow-2xl">
                        <i class="fas fa-percentage text-white text-3xl"></i>
                    </div>
                </div>
                
                <button onclick="closeCouponModal()" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <div class="mt-8 mb-6 text-center">
                    <h3 class="text-2xl font-black mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                        Apply Discount Code
                    </h3>
                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                        Enter your code to unlock special pricing
                    </p>
                </div>
                
                <form id="couponForm" onsubmit="validateCoupon(event)">
                    <input type="hidden" id="selectedPlanId" value="">
                    
                    <div class="relative mb-6">
                        <i class="fas fa-ticket-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="couponCode"
                               placeholder="Enter code (e.g., SAVE20)"
                               class="w-full pl-12 pr-4 py-4 rounded-xl text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-[#C8102E]/20 uppercase transition-all"
                               :class="darkMode ? 'bg-gray-800 text-white placeholder-gray-500' : 'bg-gray-100 text-gray-900 placeholder-gray-400'"
                               required>
                        <p id="couponError" class="mt-2 text-sm text-red-500 hidden flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </p>
                    </div>
                    
                    <!-- Success Result -->
                    <div id="couponResult" class="hidden mb-6">
                        <div class="rounded-xl p-6 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border-2 border-green-500/30">
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-green-600 font-bold flex items-center gap-2">
                                    <i class="fas fa-check-circle text-xl"></i>
                                    Discount Applied!
                                </p>
                                <span id="discountBadge" class="text-2xl font-black text-green-600"></span>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Original Price:</span>
                                    <span class="line-through text-lg" id="originalPrice"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Your Discount:</span>
                                    <span class="text-green-600 font-bold" id="discountAmount"></span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t-2" 
                                     :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                                    <span class="font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Final Price:</span>
                                    <span class="text-3xl font-black text-[#C8102E]" id="finalPrice"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" 
                                id="validateBtn"
                                class="flex-1 py-4 rounded-xl font-bold bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white transition-all transform hover:scale-105 hover:shadow-xl">
                            <span id="validateBtnText">Apply Code</span>
                            <span id="validateBtnLoading" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Validating...
                            </span>
                        </button>
                    </div>
                    
                    <!-- Continue Button -->
                    <button type="button" 
                            id="continueBtn"
                            onclick="proceedWithCoupon()"
                            class="hidden w-full mt-3 py-4 rounded-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 text-white transition-all transform hover:scale-105 hover:shadow-xl">
                        <i class="fas fa-arrow-right mr-2"></i>Continue with Discount
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    </div>

    @push('styles')
    <style>
        /* Simple smooth transitions */
        * {
            transition: all 0.2s ease;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        let validatedCoupon = null;

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
                    errorDiv.querySelector('span').textContent = data.message || 'Invalid promo code';
                    errorDiv.classList.remove('hidden');
                }
                
            } catch (error) {
                console.error('Error validating coupon:', error);
                errorDiv.querySelector('span').textContent = 'An error occurred. Please try again.';
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
