<x-admin-layout>
    <x-slot:title>Coupon Management</x-slot>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Coupons</h1>
                <p class="mt-2 text-gray-600">Manage discount coupons and promotional codes</p>
            </div>
            <div class="mt-4 flex space-x-3 sm:mt-0">
                <a href="{{ route('admin.coupons.create') }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-dark">
                    <i class="fas fa-plus mr-2"></i>Create Coupon
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by code or description..." 
                       class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>
            <select name="status" class="rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <select name="plan_id" class="rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                <option value="">All Plans</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'plan_id']))
                <a href="{{ route('admin.coupons.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Coupons</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $coupons->total() }}</p>
                </div>
                <div class="rounded-lg bg-blue-100 p-3">
                    <i class="fas fa-tags text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Coupons</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ \App\Models\Coupon::active()->valid()->count() }}
                    </p>
                </div>
                <div class="rounded-lg bg-green-100 p-3">
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Redemptions</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ \App\Models\CouponRedemption::count() }}
                    </p>
                </div>
                <div class="rounded-lg bg-purple-100 p-3">
                    <i class="fas fa-shopping-cart text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Discounts</p>
                    <p class="text-2xl font-bold text-gray-900">
                        ৳{{ number_format(\App\Models\CouponRedemption::sum('discount_amount'), 0) }}
                    </p>
                </div>
                <div class="rounded-lg bg-orange-100 p-3">
                    <i class="fas fa-percent text-xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Generated Codes Alert -->
    @if(session('generated_codes'))
        <div class="mb-6 rounded-lg bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Coupons Generated Successfully!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p class="mb-2">Generated Codes:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(session('generated_codes') as $code)
                                <code class="rounded bg-green-100 px-2 py-1 text-xs">{{ $code }}</code>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Coupons Table -->
    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Code
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Discount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Plan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usage
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valid Until
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $coupon->code }}
                                        </div>
                                        @if($coupon->description)
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($coupon->description, 30) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($coupon->discount_type === 'percentage') bg-blue-100 text-blue-800
                                    @elseif($coupon->discount_type === 'fixed') bg-green-100 text-green-800
                                    @elseif($coupon->discount_type === 'full_access') bg-purple-100 text-purple-800
                                    @else bg-orange-100 text-orange-800
                                    @endif">
                                    {{ $coupon->formatted_discount }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $coupon->plan->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $coupon->used_count }}
                                @if($coupon->usage_limit)
                                    / {{ $coupon->usage_limit }}
                                @else
                                    <span class="text-gray-500">/ ∞</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($coupon->valid_until)
                                    {{ $coupon->valid_until->format('M d, Y') }}
                                    @if($coupon->valid_until->isPast())
                                        <span class="text-red-500 text-xs">(Expired)</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">No expiry</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($coupon->isValid())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.coupons.show', $coupon) }}" 
                                       class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" 
                                          method="POST" 
                                          class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-{{ $coupon->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $coupon->is_active ? 'orange' : 'green' }}-900">
                                            <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    @if($coupon->used_count === 0)
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No coupons found. <a href="{{ route('admin.coupons.create') }}" class="text-primary hover:underline">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($coupons->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $coupons->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>