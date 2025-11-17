<x-admin-layout>
    <x-slot:title>Subscription Plans</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Subscription Plans</h1>
                <p class="mt-2 text-gray-600">Manage pricing plans and features for your platform</p>
            </div>
            <div class="mt-4 flex items-center space-x-3 sm:mt-0">
                <a href="{{ route('admin.subscription-features.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Manage Features
                </a>
                <a href="{{ route('admin.subscription-plans.create') }}" 
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create New Plan
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
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
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Plans</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalPlans }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active Plans</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $activePlans }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Subscribers</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalSubscribers) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="metric-card rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">৳{{ number_format($monthlyRevenue) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans Table -->
    <div class="mb-8 overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Manage Your Plans</h2>
            <p class="mt-1 text-sm text-gray-600">Drag to reorder plans</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Plan Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Pricing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Features</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Subscribers</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white sortable-plans">
                    @foreach($plans as $plan)
                    <tr data-id="{{ $plan->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="whitespace-nowrap px-6 py-4">
                            <button class="cursor-move text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                </svg>
                            </button>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $plan->slug }}</div>
                                <div class="mt-1 flex gap-2">
                                    @if($plan->is_featured)
                                        <span class="inline-flex rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800">
                                            Featured
                                        </span>
                                    @endif
                                    @if($plan->is_institute_only)
                                        <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Institute Only
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm">
                                @if($plan->discount_price)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-400 line-through">৳{{ number_format($plan->price, 0) }}</span>
                                        <span class="text-lg font-semibold text-green-600">৳{{ number_format($plan->discount_price, 0) }}</span>
                                    </div>
                                    <span class="mt-1 inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                        {{ $plan->discount_percentage }}% OFF
                                    </span>
                                @else
                                    <span class="text-lg font-semibold text-gray-900">৳{{ number_format($plan->price, 0) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center text-sm text-gray-900">
                                <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $plan->duration_days }} days
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs space-y-1 text-xs">
                                @php
                                    $planFeatures = $plan->features()->get();
                                @endphp
                                @if($planFeatures && count($planFeatures) > 0)
                                    @php
                                        $displayFeatures = $planFeatures->take(2);
                                    @endphp
                                    @foreach($displayFeatures as $feature)
                                        <div class="flex items-center text-gray-600">
                                            <svg class="mr-1 h-3 w-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ $feature->name }}</span>
                                            @if(isset($feature->pivot->value) && $feature->pivot->value !== 'true')
                                                <span class="ml-1 font-semibold text-indigo-600">({{ $feature->pivot->value }})</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($planFeatures->count() > 2)
                                        <div class="font-medium text-indigo-600">+{{ $planFeatures->count() - 2 }} more</div>
                                    @endif
                                @else
                                    <span class="italic text-gray-400">No features</span>
                                @endif
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <form action="{{ route('admin.subscription-plans.toggle-status', $plan) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                                    {{ $plan->is_active ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                        {{ $plan->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @php
                                $activeSubscriptions = $plan->subscriptions ? $plan->subscriptions->where('status', 'active')->count() : 0;
                            @endphp
                            <div class="flex items-center">
                                <span class="text-lg font-semibold text-gray-900">{{ $activeSubscriptions }}</span>
                                <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.subscription-plans.edit', $plan) }}" 
                                   class="rounded-lg bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700 transition-colors">
                                    Edit
                                </a>
                                @if($activeSubscriptions == 0)
                                <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                        Delete
                                    </button>
                                </form>
                                @else
                                <button disabled 
                                        class="cursor-not-allowed rounded-lg bg-gray-300 px-3 py-1 text-xs font-medium text-gray-500">
                                    Delete
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Feature Comparison Matrix -->
    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Feature Comparison Matrix</h2>
            <p class="mt-1 text-sm text-gray-600">Compare features across all plans</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Features</th>
                        @foreach($plans as $plan)
                            <th class="min-w-[150px] px-6 py-3 text-center">
                                <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                @if($plan->is_featured)
                                    <span class="text-xs font-normal text-indigo-600">Most Popular</span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $allFeatures = \App\Models\SubscriptionFeature::all();
                    @endphp
                    @foreach($allFeatures as $feature)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($feature->key === 'mock_tests_per_month')
                                    <svg class="mr-3 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                @elseif($feature->key === 'ai_writing_evaluation')
                                    <svg class="mr-3 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                @elseif($feature->key === 'ai_speaking_evaluation')
                                    <svg class="mr-3 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                    </svg>
                                @elseif($feature->key === 'detailed_analytics')
                                    <svg class="mr-3 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                @elseif($feature->key === 'priority_support')
                                    <svg class="mr-3 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                @else
                                    <svg class="mr-3 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $feature->name }}</div>
                                    @if($feature->description)
                                        <div class="text-xs text-gray-500">{{ $feature->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        @foreach($plans as $plan)
                            @php
                                $planFeature = $plan->features()->where('subscription_features.id', $feature->id)->first();
                            @endphp
                            <td class="px-6 py-4 text-center">
                                @if($planFeature)
                                    @if($planFeature->pivot->value == 'true' || !$planFeature->pivot->value)
                                        <div class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-100">
                                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @else
                                        <span class="inline-flex rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-800">
                                            {{ $planFeature->pivot->value }}
                                        </span>
                                    @endif
                                @else
                                    <div class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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

    @push('styles')
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f9fafb;
        }
        .sortable-drag {
            background-color: white !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // Sortable plans
        const el = document.querySelector('.sortable-plans');
        if (el) {
            Sortable.create(el, {
                handle: '.cursor-move',
                animation: 150,
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
                            showToast('Plan order updated successfully', 'success');
                        }
                    });
                }
            });
        }
    </script>
    @endpush
</x-admin-layout>