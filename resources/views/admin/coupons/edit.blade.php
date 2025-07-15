<x-admin-layout>
    <x-slot:title>Edit Coupon</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.coupons.index') }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Coupon</h1>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Coupon Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="code" 
                               value="{{ old('code', $coupon->code) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
                               required>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <input type="text" 
                               name="description" 
                               value="{{ old('description', $coupon->description) }}"
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
                                <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>
                                    Percentage (%)
                                </option>
                                <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>
                                    Fixed Amount (৳)
                                </option>
                                <option value="full_access" {{ old('discount_type', $coupon->discount_type) === 'full_access' ? 'selected' : '' }}>
                                    Full Access (100% Free)
                                </option>
                                <option value="trial" {{ old('discount_type', $coupon->discount_type) === 'trial' ? 'selected' : '' }}>
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
                                   value="{{ old('discount_value', $coupon->discount_value) }}"
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
                                   value="{{ old('duration_days', $coupon->duration_days) }}"
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
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('plan_id', $coupon->plan_id) == $plan->id ? 'selected' : '' }}>
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
                                   value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                   min="1"
                                   placeholder="Leave empty for unlimited"
                                   class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            <p class="mt-1 text-sm text-gray-500">
                                Currently used: {{ $coupon->used_count }} times
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
                                   value="{{ old('valid_from', $coupon->valid_from?->format('Y-m-d\TH:i')) }}"
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
                                   value="{{ old('valid_until', $coupon->valid_until?->format('Y-m-d\TH:i')) }}"
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
                               {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-700">Active (Coupon can be used immediately)</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.coupons.show', $coupon) }}" 
                       class="px-4 py-2 text-gray-700 hover:text-gray-900">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        Update Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
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
            } else if (type === 'fixed') {
                valueField.style.display = 'block';
                durationField.style.display = 'none';
                discountHelp.textContent = 'Enter fixed amount in ৳';
                discountInput.removeAttribute('max');
            } else if (type === 'full_access') {
                valueField.style.display = 'none';
                durationField.style.display = 'none';
            } else if (type === 'trial') {
                valueField.style.display = 'none';
                durationField.style.display = 'block';
            }
        }

        // Initialize on page load
        toggleDiscountFields();
    </script>
    @endpush
</x-admin-layout>