<x-admin-layout>
    <x-slot:title>Coupon Details</x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.coupons.index') }}" 
                       class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Coupon: {{ $coupon->code }}</h1>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" 
                          method="POST" 
                          class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="px-4 py-2 bg-{{ $coupon->is_active ? 'orange' : 'green' }}-100 text-{{ $coupon->is_active ? 'orange' : 'green' }}-700 rounded-lg hover:bg-{{ $coupon->is_active ? 'orange' : 'green' }}-200">
                            <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <!-- Coupon Details Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Coupon Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Code</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $coupon->code }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="mt-1">
                                @if($coupon->isValid())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Active & Valid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Inactive
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Discount Type</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $coupon->discount_type)) }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Discount Value</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $coupon->formatted_discount }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Applicable Plan</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $coupon->plan->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Created By</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $coupon->creator->name }}</p>
                        </div>
                        
                        @if($coupon->description)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Description</p>
                            <p class="text-gray-900">{{ $coupon->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Usage History -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Usage History</h2>
                    
                    @if($coupon->redemptions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Final Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Redeemed At</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($coupon->redemptions as $redemption)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $redemption->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $redemption->user->email }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                ৳{{ number_format($redemption->original_price, 0) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-red-600">
                                                -৳{{ number_format($redemption->discount_amount, 0) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                ৳{{ number_format($redemption->final_price, 0) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ $redemption->redeemed_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($redemption->subscription && $redemption->subscription->isActive())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Expired
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">No redemptions yet</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Statistics -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Total Redemptions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_redemptions'] }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Total Discount Given</p>
                            <p class="text-2xl font-bold text-red-600">৳{{ number_format($stats['total_discount_given'], 0) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Active Subscriptions</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['active_subscriptions'] }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Usage Rate</p>
                            <div class="mt-2">
                                @php
                                    $usageRate = $coupon->usage_limit ? ($coupon->used_count / $coupon->usage_limit) * 100 : 0;
                                @endphp
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-primary h-3 rounded-full" style="width: {{ min($usageRate, 100) }}%"></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }} uses
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validity Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Validity Period</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Valid From</p>
                            <p class="text-gray-900">
                                {{ $coupon->valid_from ? $coupon->valid_from->format('M d, Y H:i') : 'Immediately' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Valid Until</p>
                            <p class="text-gray-900">
                                {{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y H:i') : 'No expiry' }}
                            </p>
                        </div>
                        
                        @if($coupon->valid_until)
                        <div>
                            <p class="text-sm text-gray-600">Time Remaining</p>
                            <p class="text-gray-900">
                                @if($coupon->valid_until->isFuture())
                                    {{ $coupon->valid_until->diffForHumans() }}
                                @else
                                    <span class="text-red-600">Expired</span>
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>