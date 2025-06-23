<x-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
            <p class="text-xl text-gray-600">Select the perfect plan for your IELTS preparation journey</p>
        </div>

        {{-- Plans Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            @foreach($plans as $plan)
            <div class="bg-white rounded-lg shadow-lg border-2 {{ $plan->is_featured ? 'border-blue-500' : 'border-gray-200' }} relative">
                @if($plan->is_featured)
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                </div>
                @endif
                
                <div class="p-8">
                    {{-- Plan Name & Price --}}
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                    <p class="text-gray-600 mb-6">{{ $plan->description }}</p>
                    
                    <div class="mb-6">
                        @if($plan->discount_price)
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold text-gray-900">৳{{ number_format($plan->discount_price, 0) }}</span>
                            <span class="text-xl text-gray-500 line-through ml-2">৳{{ number_format($plan->price, 0) }}</span>
                            <span class="text-sm text-green-600 ml-2">Save {{ $plan->discount_percentage }}%</span>
                        </div>
                        @else
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold text-gray-900">
                                @if($plan->is_free)
                                    Free
                                @else
                                    ৳{{ number_format($plan->price, 0) }}
                                @endif
                            </span>
                            @if(!$plan->is_free)
                            <span class="text-gray-600 ml-2">/ month</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    {{-- Features --}}
                    <ul class="space-y-3 mb-8">
                        @foreach($plan->features as $feature)
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">
                                {{ $feature->name }}
                                @if($feature->pivot->value && $feature->pivot->value !== 'true')
                                    <span class="font-semibold">({{ $feature->pivot->value }})</span>
                                @endif
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    
                    {{-- Action Button --}}
                    @auth
                        @if($currentPlan && $currentPlan->id === $plan->id)
                        <button class="w-full bg-gray-100 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                            Current Plan
                        </button>
                        @else
                        <form action="{{ route('subscription.subscribe', $plan) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full {{ $plan->is_featured ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-800 hover:bg-gray-900' }} text-white px-6 py-3 rounded-lg font-semibold transition">
                                @if($plan->is_free)
                                    Switch to Free
                                @elseif($currentPlan && $currentPlan->price < $plan->price)
                                    Upgrade Now
                                @else
                                    Get Started
                                @endif
                            </button>
                        </form>
                        @endif
                    @else
                    <a href="{{ route('register') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Sign Up to Start
                    </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        {{-- FAQ Section --}}
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Frequently Asked Questions</h2>
            
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Can I change my plan anytime?</h3>
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes will take effect immediately, and you'll be charged or credited proportionally.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-600">We accept international cards through Stripe, and local payments through bKash and Nagad for your convenience.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                    <p class="text-gray-600">We offer a generous free plan that includes 3 mock tests per month. This allows you to experience our platform before upgrading.</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Can I cancel my subscription?</h3>
                    <p class="text-gray-600">Yes, you can cancel your subscription anytime. You'll continue to have access until the end of your billing period.</p>
                </div>
            </div>
        </div>

        {{-- Money Back Guarantee --}}
        <div class="mt-12 text-center">
            <div class="inline-flex items-center text-gray-600">
                <i class="fas fa-shield-alt text-2xl mr-3"></i>
                <div class="text-left">
                    <p class="font-semibold">30-Day Money Back Guarantee</p>
                    <p class="text-sm">Not satisfied? Get a full refund within 30 days.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout>