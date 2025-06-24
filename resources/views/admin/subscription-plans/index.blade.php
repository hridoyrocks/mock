<x-admin-layout>
    <x-slot:title>Subscription Plans</x-slot>
    
    <x-slot:header>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Subscription Plans
                </h1>
                
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.subscription-features.index') }}" 
                   class="group relative inline-flex items-center px-5 py-2.5 bg-white border-2 border-purple-200 rounded-xl hover:border-purple-400 transition-all duration-300 shadow-sm hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <span class="font-semibold text-gray-700">Manage Features</span>
                </a>
                <a href="{{ route('admin.subscription-plans.create') }}" 
                   class="group relative inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="font-semibold">Create New Plan</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-8">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $totalPlans = $plans->count();
                $activePlans = $plans->where('is_active', true)->count();
                $totalSubscribers = 0;
                $monthlyRevenue = 0;
                foreach($plans as $plan) {
                    $activeCount = $plan->subscriptions ? $plan->subscriptions->where('status', 'active')->count() : 0;
                    $totalSubscribers += $activeCount;
                    $monthlyRevenue += $activeCount * $plan->current_price;
                }
            @endphp
            
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Plans</p>
                        <p class="text-3xl font-bold mt-2">{{ $totalPlans }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Active Plans</p>
                        <p class="text-3xl font-bold mt-2">{{ $activePlans }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Subscribers</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($totalSubscribers) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Monthly Revenue</p>
                        <p class="text-3xl font-bold mt-2">৳{{ number_format($monthlyRevenue) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Plans Table with Modern Design --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6">
                <h2 class="text-xl font-bold text-white">Manage Your Plans</h2>
                <p class="text-purple-100 text-sm mt-1">Drag to reorder plans</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Plan Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pricing</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Features</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Subscribers</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 sortable-plans">
                        @foreach($plans as $plan)
                        <tr data-id="{{ $plan->id }}" class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="cursor-move text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                    </svg>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-base font-bold text-gray-900">{{ $plan->name }}</div>
                                    <div class="text-sm text-gray-500 font-mono">{{ $plan->slug }}</div>
                                    @if($plan->is_featured)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-purple-500 to-pink-500 text-white mt-2">
                                            FEATURED
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    @if($plan->discount_price)
                                        <div class="flex items-center space-x-2">
                                            <span class="text-gray-400 line-through">৳{{ number_format($plan->price, 0) }}</span>
                                            <span class="text-2xl font-bold text-green-600">৳{{ number_format($plan->discount_price, 0) }}</span>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                            {{ $plan->discount_percentage }}% OFF
                                        </span>
                                    @else
                                        <span class="text-2xl font-bold text-gray-900">৳{{ number_format($plan->price, 0) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">{{ $plan->duration_days }} days</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs space-y-1">
                                    @if($plan->features && count($plan->features) > 0)
                                        @php
                                            $featuresList = $plan->features->toArray();
                                            $displayFeatures = array_slice($featuresList, 0, 3);
                                        @endphp
                                        @foreach($displayFeatures as $feature)
                                            <div class="flex items-center text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>{{ $feature['name'] }}</span>
                                                @if(isset($feature['pivot']['value']) && $feature['pivot']['value'])
                                                    <span class="font-bold text-purple-600 ml-1">({{ $feature['pivot']['value'] }})</span>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if(count($featuresList) > 3)
                                            <div class="text-purple-600 font-medium">+{{ count($featuresList) - 3 }} more features</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">No features</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.subscription-plans.toggle-status', $plan) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="group relative">
                                        <div class="flex items-center justify-center w-20 h-8 bg-gray-200 rounded-full p-1 {{ $plan->is_active ? 'bg-green-500' : 'bg-gray-300' }} transition-colors duration-300">
                                            <div class="bg-white w-6 h-6 rounded-full shadow-md transform transition-transform duration-300 {{ $plan->is_active ? 'translate-x-6' : 'translate-x-0' }}"></div>
                                        </div>
                                        <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $activeSubscriptions = $plan->subscriptions ? $plan->subscriptions->where('status', 'active')->count() : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $activeSubscriptions }}</div>
                                    <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-3 justify-end">
                                    <a href="{{ route('admin.subscription-plans.edit', $plan) }}" 
                                       class="text-purple-600 hover:text-purple-900 transition-colors transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($activeSubscriptions == 0)
                                    <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors transform hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Plan Comparison with Modern Design --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-pink-600 to-purple-600 p-6">
                <h2 class="text-xl font-bold text-white">Feature Comparison Matrix</h2>
                <p class="text-pink-100 text-sm mt-1">Compare features across all plans</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left py-4 px-6 font-bold text-gray-700">Features</th>
                            @foreach($plans as $plan)
                                <th class="text-center py-4 px-6 min-w-[150px]">
                                    <div class="font-bold text-gray-900">{{ $plan->name }}</div>
                                    @if($plan->is_featured)
                                        <span class="text-xs text-purple-600 font-normal">Most Popular</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $allFeatures = \App\Models\SubscriptionFeature::all();
                        @endphp
                        @foreach($allFeatures as $feature)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    @if($feature->icon)
                                        <i class="{{ $feature->icon }} text-purple-600 mr-3"></i>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $feature->name }}</div>
                                        @if($feature->description)
                                            <div class="text-xs text-gray-500">{{ $feature->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                                @php
                                    $planFeature = null;
                                    if ($plan->features) {
                                        $planFeature = $plan->features->firstWhere('id', $feature->id);
                                    }
                                @endphp
                                <td class="py-4 px-6 text-center">
                                    @if($planFeature)
                                        @if($planFeature->pivot->value == 'true' || !$planFeature->pivot->value)
                                            <div class="inline-flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-purple-100 text-purple-800">
                                                {{ $planFeature->pivot->value }}
                                            </span>
                                        @endif
                                    @else
                                        <div class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
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

    @push('styles')
    <style>
        .sortable-plans tr {
            cursor: move;
        }
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f3f4f6;
        }
        .sortable-drag {
            background-color: white !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: rotate(2deg);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // Sortable plans with animation
        const el = document.querySelector('.sortable-plans');
        if (el) {
            Sortable.create(el, {
                handle: '.cursor-move',
                animation: 300,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function (evt) {
                    const plans = [];
                    document.querySelectorAll('.sortable-plans tr').forEach((row, index) => {
                        plans.push({
                            id: row.dataset.id,
                            order: index
                        });
                    });
                    
                    fetch('{{ route("admin.subscription-plans.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ plans })
                    }).then(response => {
                        if (response.ok) {
                            // Add success animation
                            evt.item.style.backgroundColor = '#10b981';
                            setTimeout(() => {
                                evt.item.style.backgroundColor = '';
                            }, 500);
                        }
                    });
                }
            });
        }
    </script>
    @endpush
</x-admin-layout>