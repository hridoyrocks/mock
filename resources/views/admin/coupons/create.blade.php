<x-admin-layout>
    <x-slot:title>Create Coupon</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.coupons.index') }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Create Coupon</h1>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
           <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm" novalidate>

                @csrf
                
                <!-- Generation Type -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Coupon Generation Type
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="generate_code" 
                                   value="0" 
                                   checked
                                   class="mr-3 text-primary focus:ring-primary"
                                   onchange="toggleGenerationType(false)">
                            <span>Single Coupon</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="generate_code" 
                                   value="1" 
                                   class="mr-3 text-primary focus:ring-primary"
                                   onchange="toggleGenerationType(true)">
                            <span>Bulk Generate</span>
                        </label>
                    </div>
                </div>

                <!-- Single Code Input -->
                <div id="singleCodeSection" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Coupon Code
                        </label>
                        <input type="text" 
                               name="code" 
                               value="{{ old('code') }}"
                               placeholder="Leave empty for auto-generation"
                               class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <p class="mt-1 text-sm text-gray-500">
                            Use uppercase letters and numbers only
                        </p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bulk Generation -->
                <div id="bulkGenerationSection" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" style="display: none;">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Code Prefix
                        </label>
                        <input type="text" 
                               name="code_prefix" 
                               value="{{ old('code_prefix', 'CD') }}"
                               maxlength="10"
                               class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        @error('code_prefix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Number of Codes
                        </label>
                        <input type="number" 
                               name="code_count" 
                               value="{{ old('code_count', 10) }}"
                               min="1"
                               max="100"
                               class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        @error('code_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <input type="text" 
                               name="description" 
                               value="{{ old('description') }}"
                               placeholder="e.g., New Year Special - 50% OFF"
                               class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Discount Configuration -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Discount Configuration</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Discount Type <span class="text-red-500">*</span>
                            </label>
                            <select name="discount_type" 
                                    id="discountType"
                                    class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
                                    onchange="toggleDiscountFields()"
                                    required>
                                <option value="">Select type</option>
                                <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                                    Percentage (%)
                                </option>
                                <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>
                                    Fixed Amount (৳)
                                </option>
                                <option value="full_access" {{ old('discount_type') === 'full_access' ? 'selected' : '' }}>
                                    Full Access (100% Free)
                                </option>
                                <option value="trial" {{ old('discount_type') === 'trial' ? 'selected' : '' }}>
                                    Free Trial
                                </option>
                            </select>
                            @error('discount_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="discountValueField">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Discount Value
                            </label>
                            <input type="number" 
                                   name="discount_value" 
                                   value="{{ old('discount_value') }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            <p class="mt-1 text-sm text-gray-500" id="discountHelp">
                                Enter percentage or fixed amount
                            </p>
                            @error('discount_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="durationField" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Trial Duration (Days)
                            </label>
                            <input type="number" 
                                   name="duration_days" 
                                   value="{{ old('duration_days', 7) }}"
                                   min="1"
                                   class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            @error('duration_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Plan Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Applicable Plan <span class="text-red-500">*</span>
                    </label>
                    <select name="plan_id" 
                            class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
                            required>
                        <option value="">Select plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} - ৳{{ number_format($plan->current_price, 0) }}/month
                            </option>
                        @endforeach
                    </select>
                    @error('plan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Usage Limits -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Usage Limits & Validity</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Usage Limit
                            </label>
                            <input type="number" 
                                   name="usage_limit" 
                                   value="{{ old('usage_limit') }}"
                                   min="1"
                                   placeholder="Leave empty for unlimited"
                                   class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            <p class="mt-1 text-sm text-gray-500">
                                Max redemptions allowed
                            </p>
                            @error('usage_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Valid From
                            </label>
                            <input type="datetime-local" 
                                   name="valid_from" 
                                   value="{{ old('valid_from') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            @error('valid_from')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Valid Until
                            </label>
                            <input type="datetime-local" 
                                   name="valid_until" 
                                   value="{{ old('valid_until') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            @error('valid_until')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-700">Active (Coupon can be used immediately)</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.coupons.index') }}" 
                       class="px-4 py-2 text-gray-700 hover:text-gray-900">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        Create Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
<script>
    function toggleGenerationType(isBulk) {
        document.getElementById('singleCodeSection').style.display = isBulk ? 'none' : 'grid';
        document.getElementById('bulkGenerationSection').style.display = isBulk ? 'grid' : 'none';
        
        // Clear validation errors
        document.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden'));
    }

    function toggleDiscountFields() {
        const type = document.getElementById('discountType').value;
        const valueField = document.getElementById('discountValueField');
        const durationField = document.getElementById('durationField');
        const discountHelp = document.getElementById('discountHelp');
        const discountInput = valueField.querySelector('input');

        if (type === 'percentage') {
            valueField.style.display = 'block';
            durationField.style.display = 'none';
            discountHelp.textContent = 'Enter percentage (0-100)';
            discountInput.setAttribute('max', '100');
            discountInput.setAttribute('required', 'required');
        } else if (type === 'fixed') {
            valueField.style.display = 'block';
            durationField.style.display = 'none';
            discountHelp.textContent = 'Enter fixed amount in ৳';
            discountInput.removeAttribute('max');
            discountInput.setAttribute('required', 'required');
        } else if (type === 'full_access') {
            valueField.style.display = 'none';
            durationField.style.display = 'none';
            discountInput.removeAttribute('required');
            discountInput.value = '0';
        } else if (type === 'trial') {
            valueField.style.display = 'none';
            durationField.style.display = 'block';
            discountInput.removeAttribute('required');
            discountInput.value = '0';
        }
    }

    // Form validation
    document.getElementById('couponForm').addEventListener('submit', function(e) {
        const discountType = document.getElementById('discountType').value;
        const discountValue = document.querySelector('input[name="discount_value"]').value;
        
        // Validate discount value based on type
        if ((discountType === 'percentage' || discountType === 'fixed') && !discountValue) {
            e.preventDefault();
            alert('Please enter a discount value');
            return false;
        }
        
        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleDiscountFields();
    });
</script>
@endpush
</x-admin-layout>