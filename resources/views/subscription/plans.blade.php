<x-student-layout>
    <x-slot:title>Choose Your Plan</x-slot>
    
    <div x-data="{ 
        selectedDuration: 30,
        showAllFeatures: false,
        hoveredPlan: null
    }" x-init="() => { if (typeof darkMode === 'undefined') { darkMode = localStorage.getItem('darkMode') !== 'false'; } }">
    
    <!-- Floating Header with Gradient -->
    <section class="relative overflow-hidden py-8 mb-8">
        <div class="absolute inset-0 bg-gradient-to-br from-[#C8102E]/10 via-[#C8102E]/5 to-transparent"></div>
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#C8102E]/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#A00E27]/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Animated Badge -->
                <div class="flex justify-center mb-4">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full shadow-lg animate-pulse"
                         :class="darkMode ? 'glass border border-[#C8102E]/30' : 'bg-white/90 backdrop-blur border border-[#C8102E]/20'">
                        <i class="fas fa-fire text-[#C8102E] animate-bounce"></i>
                        <span class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">
                            Limited Time Offer - Save up to 20%
                        </span>
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-black text-center mb-3" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    Start Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#C8102E] to-[#A00E27] animate-gradient">IELTS Success</span> Journey
                </h1>
                <p class="text-center text-lg" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                    Join <span class="font-bold text-[#C8102E]">50,000+</span> students achieving their dream scores
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content with Visual Elements -->
    <section class="px-4 sm:px-6 lg:px-8 pb-16">
        <div class="max-w-6xl mx-auto">
            
            @php
                $plans = collect($plans);
                $plansByDuration = $plans->groupBy('duration_days');
                $availableDurations = $plansByDuration->keys()->sort()->values();
            @endphp

            <!-- Unique Duration Selector -->
            @if($availableDurations->count() > 1)
            <div class="flex justify-center mb-10">
                <div class="relative inline-flex p-1 rounded-2xl"
                     :class="darkMode ? 'bg-gray-800/50 backdrop-blur' : 'bg-gray-100'">
                    <!-- Sliding Background -->
                    <div class="absolute h-full rounded-xl bg-gradient-to-r from-[#C8102E] to-[#A00E27] transition-all duration-300 shadow-lg"
                         :style="{
                            width: '33.33%',
                            transform: `translateX(${selectedDuration === 30 ? '0%' : selectedDuration === 90 ? '100%' : '200%'})`
                         }">
                    </div>
                    
                    @foreach($availableDurations as $index => $duration)
                        <button @click="selectedDuration = {{ $duration }}" 
                                class="relative z-10 px-6 py-3 rounded-xl font-bold transition-all duration-300"
                                :class="selectedDuration === {{ $duration }} ? 'text-white' : (darkMode ? 'text-gray-400 hover:text-white' : 'text-gray-600 hover:text-gray-900')">
                            <span class="flex items-center gap-2">
                                @if($duration == 30)
                                    <i class="fas fa-calendar-day"></i>
                                    Monthly
                                @elseif($duration == 90)
                                    <i class="fas fa-calendar-week"></i>
                                    Quarterly
                                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">-10%</span>
                                @elseif($duration == 365)
                                    <i class="fas fa-calendar-alt"></i>
                                    Annual
                                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">-20%</span>
                                @else
                                    {{ $duration }} Days
                                @endif
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Unique Plans Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mb-12">
                @forelse($plans as $index => $plan)
                    <div x-show="selectedDuration === {{ $plan->duration_days }}"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         @mouseenter="hoveredPlan = {{ $index }}"
                         @mouseleave="hoveredPlan = null"
                         class="relative group">
                        
                        <!-- Glow Effect on Hover -->
                        <div class="absolute -inset-0.5 rounded-2xl opacity-0 group-hover:opacity-100 transition duration-300"
                             :class="'{{ $plan->is_featured ? 'bg-gradient-to-r from-[#C8102E] to-[#A00E27]' : 'bg-gradient-to-r from-gray-600 to-gray-700' }}' + ' blur-sm'">
                        </div>
                        
                        @if($plan->is_featured)
                        <!-- Featured Badge -->
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-[#C8102E] to-[#A00E27] blur animate-pulse"></div>
                                <div class="relative px-6 py-2 bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white text-sm font-black rounded-full shadow-xl flex items-center gap-2">
                                    <i class="fas fa-crown text-yellow-300 animate-bounce"></i>
                                    MOST POPULAR
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="relative h-full rounded-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-2"
                             :class="darkMode ? 
                                'bg-gray-800/50 backdrop-blur border {{ $plan->is_featured ? 'border-[#C8102E]/50' : 'border-gray-700' }}' : 
                                'bg-white border-2 {{ $plan->is_featured ? 'border-[#C8102E]/30 shadow-2xl' : 'border-gray-200' }} shadow-lg'">
                            
                            <!-- Plan Header with Unique Design -->
                            <div class="relative p-8 pb-6">
                                <!-- Background Pattern -->
                                <div class="absolute inset-0 opacity-5">
                                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23{{ $plan->is_featured ? 'C8102E' : '9CA3AF' }}" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                                </div>
                                
                                <!-- Plan Icon -->
                                <div class="relative mb-4">
                                    <div class="w-20 h-20 mx-auto rounded-2xl rotate-3 transform transition-transform group-hover:rotate-6 group-hover:scale-110
                                        {{ $plan->slug === 'pro' || $plan->slug === 'premium' ? 'bg-gradient-to-br from-[#C8102E] to-[#A00E27]' : 'bg-gradient-to-br from-gray-600 to-gray-700' }} 
                                        shadow-2xl flex items-center justify-center">
                                        <i class="fas {{ $plan->slug === 'pro' ? 'fa-crown' : ($plan->slug === 'premium' ? 'fa-gem' : 'fa-user') }} text-3xl text-white"></i>
                                    </div>
                                </div>
                                
                                <!-- Plan Name -->
                                <h3 class="text-2xl font-black text-center mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                    {{ $plan->name }}
                                </h3>
                                <p class="text-center text-sm mb-6" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                    {{ $plan->description }}
                                </p>
                                
                                <!-- Price with Animation -->
                                <div class="text-center mb-6">
                                    @if($plan->is_free)
                                        <div class="text-5xl font-black" :class="darkMode ? 'text-white' : 'text-gray-900'">FREE</div>
                                        <p class="text-sm mt-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Forever</p>
                                    @else
                                        <div class="relative">
                                            @if($plan->discount_price && $plan->discount_price < $plan->price)
                                                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                                                    <span class="text-sm line-through" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">
                                                        ৳{{ number_format($plan->price, 0) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="flex items-center justify-center gap-1">
                                                <span class="text-3xl font-black text-[#C8102E]">৳</span>
                                                <span class="text-5xl font-black bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-transparent bg-clip-text">
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
                            
                            <!-- Features with Icons -->
                            <div class="px-8 pb-6">
                                <div class="space-y-3">
                                    @if($plan->relationLoaded('features') && $plan->features->count() > 0)
                                        @foreach($plan->features->take(5) as $feature)
                                            <div class="flex items-start gap-3 group/item">
                                                <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0 group-hover/item:scale-125 transition-transform">
                                                    <i class="fas fa-check text-green-500 text-xs"></i>
                                                </div>
                                                <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    <span class="font-semibold">{{ $feature->name }}</span>
                                                    @if($feature->pivot && $feature->pivot->value && $feature->pivot->value !== 'true')
                                                        <span class="text-[#C8102E] font-bold ml-1">({{ $feature->pivot->value }})</span>
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
                                            class="mt-3 text-sm text-[#C8102E] hover:text-[#A00E27] font-semibold hover:underline">
                                        <i class="fas fa-plus-circle mr-1"></i>View all {{ $plan->features->count() }} features
                                    </button>
                                @endif
                            </div>
                            
                            <!-- CTA Section -->
                            <div class="p-6 bg-gradient-to-t from-black/5 to-transparent">
                                @auth
                                    @if($currentPlan && $currentPlan->id === $plan->id)
                                        <button class="w-full py-4 rounded-xl font-bold cursor-not-allowed transition-all text-base relative overflow-hidden group/btn"
                                                :class="darkMode ? 'bg-gray-700/50 text-gray-400 border border-gray-600' : 'bg-gray-100 text-gray-400 border border-gray-300'">
                                            <i class="fas fa-check-circle mr-2"></i>Your Current Plan
                                        </button>
                                    @else
                                        <form action="{{ route('subscription.subscribe', $plan) }}" method="POST" id="subscribeForm-{{ $plan->id }}">
                                            @csrf
                                            <input type="hidden" name="coupon_code" id="couponInput-{{ $plan->id }}">
                                            <button type="submit" 
                                                    class="relative w-full py-4 rounded-xl font-black text-base transition-all transform hover:scale-105 hover:shadow-2xl overflow-hidden group/btn
                                                    {{ $plan->is_featured 
                                                        ? 'bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white shadow-lg' 
                                                        : '' }}"
                                                    :class="!{{ $plan->is_featured ? 'true' : 'false' }} && (darkMode ? 'bg-gray-700 text-white hover:bg-gray-600 border border-gray-600' : 'bg-white text-[#C8102E] hover:bg-gray-50 border-2 border-[#C8102E]/30')">
                                                
                                                <!-- Animated Background -->
                                                <span class="absolute inset-0 bg-white opacity-0 group-hover/btn:opacity-20 transition-opacity"></span>
                                                
                                                <!-- Button Content -->
                                                <span class="relative flex items-center justify-center gap-2">
                                                    @if($plan->is_free)
                                                        <i class="fas fa-rocket"></i>
                                                        START FREE
                                                    @elseif($currentPlan && $currentPlan->price < $plan->price)
                                                        <i class="fas fa-arrow-up"></i>
                                                        UPGRADE NOW
                                                    @else
                                                        <i class="fas fa-bolt"></i>
                                                        GET STARTED
                                                    @endif
                                                </span>
                                            </button>
                                        </form>
                                        
                                        @if(!$plan->is_free)
                                            <button onclick="openCouponModal({{ $plan->id }})" 
                                                    class="w-full mt-3 py-2 text-xs font-semibold text-[#C8102E] hover:text-[#A00E27] transition-all hover:bg-[#C8102E]/5 rounded-lg">
                                                <i class="fas fa-tag mr-1 animate-pulse"></i>Have a discount code?
                                            </button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('register') }}" 
                                       class="block relative w-full text-center py-4 rounded-xl font-black text-base bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white transition-all transform hover:scale-105 hover:shadow-2xl overflow-hidden group/btn">
                                        <span class="absolute inset-0 bg-white opacity-0 group-hover/btn:opacity-20 transition-opacity"></span>
                                        <span class="relative flex items-center justify-center gap-2">
                                            <i class="fas fa-user-plus"></i>
                                            SIGN UP TO START
                                        </span>
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

            <!-- Visual Feature Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
                <div class="relative group cursor-pointer">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl blur opacity-25 group-hover:opacity-40 transition"></div>
                    <div class="relative p-6 rounded-2xl transition-all transform hover:scale-105"
                         :class="darkMode ? 'bg-gray-800/50 backdrop-blur' : 'bg-white shadow-lg'">
                        <i class="fas fa-infinity text-3xl text-blue-500 mb-3"></i>
                        <h3 class="font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Unlimited Tests</h3>
                        <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Practice anytime</p>
                    </div>
                </div>
                
                <div class="relative group cursor-pointer">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-400 rounded-2xl blur opacity-25 group-hover:opacity-40 transition"></div>
                    <div class="relative p-6 rounded-2xl transition-all transform hover:scale-105"
                         :class="darkMode ? 'bg-gray-800/50 backdrop-blur' : 'bg-white shadow-lg'">
                        <i class="fas fa-brain text-3xl text-purple-500 mb-3"></i>
                        <h3 class="font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">AI Powered</h3>
                        <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Smart evaluation</p>
                    </div>
                </div>
                
                <div class="relative group cursor-pointer">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-400 rounded-2xl blur opacity-25 group-hover:opacity-40 transition"></div>
                    <div class="relative p-6 rounded-2xl transition-all transform hover:scale-105"
                         :class="darkMode ? 'bg-gray-800/50 backdrop-blur' : 'bg-white shadow-lg'">
                        <i class="fas fa-chart-line text-3xl text-green-500 mb-3"></i>
                        <h3 class="font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Track Progress</h3>
                        <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Real insights</p>
                    </div>
                </div>
                
                <div class="relative group cursor-pointer">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-600 to-amber-400 rounded-2xl blur opacity-25 group-hover:opacity-40 transition"></div>
                    <div class="relative p-6 rounded-2xl transition-all transform hover:scale-105"
                         :class="darkMode ? 'bg-gray-800/50 backdrop-blur' : 'bg-white shadow-lg'">
                        <i class="fas fa-award text-3xl text-amber-500 mb-3"></i>
                        <h3 class="font-bold mb-1" :class="darkMode ? 'text-white' : 'text-gray-900'">Get Certified</h3>
                        <p class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Score guarantee</p>
                    </div>
                </div>
            </div>

            <!-- Trust Section with Stats -->
            <div class="relative rounded-3xl overflow-hidden mb-12"
                 :class="darkMode ? 'bg-gradient-to-r from-gray-800 to-gray-900' : 'bg-gradient-to-r from-gray-50 to-gray-100'">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239CA3AF" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                </div>
                
                <div class="relative p-8 lg:p-12">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-center">
                        <div class="lg:col-span-1 text-center lg:text-left">
                            <i class="fas fa-shield-check text-5xl text-green-500 mb-4"></i>
                            <h3 class="text-2xl font-black mb-2" :class="darkMode ? 'text-white' : 'text-gray-900'">
                                100% Guaranteed
                            </h3>
                            <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                30-day money back guarantee
                            </p>
                        </div>
                        
                        <div class="lg:col-span-3 grid grid-cols-3 gap-4 lg:gap-8">
                            <div class="text-center">
                                <div class="text-3xl lg:text-4xl font-black text-[#C8102E] mb-1">50K+</div>
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Active Students</p>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl lg:text-4xl font-black text-[#C8102E] mb-1">8.5</div>
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Avg. Score</p>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl lg:text-4xl font-black text-[#C8102E] mb-1">98%</div>
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">Success Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating FAQ Button -->
            <div class="fixed bottom-6 right-6 z-40">
                <a href="#" 
                   class="flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] text-white shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all">
                    <i class="fas fa-headset text-xl"></i>
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
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 3s ease infinite;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slideUp {
            animation: slideUp 0.5s ease-out;
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
