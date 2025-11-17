<x-admin-layout>
    <x-slot:title>Assign Subscription - {{ $user->name }}</x-slot>

    <x-slot:header>
        <div class="flex items-center">
            <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Assign Subscription to {{ $user->name }}</h1>
        </div>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        {{-- User Info Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                </div>
            </div>

            @if($user->activeSubscription())
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-900">Active Subscription Found</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Current Plan: <strong>{{ $user->activeSubscription()->plan->name }}</strong> (Expires: {{ $user->activeSubscription()->ends_at->format('M d, Y') }})
                            </p>
                            <p class="text-sm text-amber-600 mt-1">
                                Assigning a new subscription will cancel the current one.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Assignment Form --}}
        <form action="{{ route('admin.users.assign-subscription', $user) }}" method="POST">
            @csrf

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Subscription Plan</h3>

                <div class="space-y-4">
                    {{-- Plan Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Choose Plan</label>
                        <select name="plan_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="plan-select">
                            <option value="">Select a plan...</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" data-duration="{{ $plan->duration_days }}" data-price="{{ $plan->current_price }}">
                                    {{ $plan->name }}
                                    @if($plan->is_institute_only)
                                        🔒 (Institute Only)
                                    @endif
                                    - ৳{{ number_format($plan->current_price, 0) }} / {{ $plan->duration_days }} days
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Custom Duration --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Duration (Days)
                            <span class="text-gray-500 font-normal">- Optional, leave empty to use plan default</span>
                        </label>
                        <input type="number" name="duration_days" id="duration-input" min="1"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Will use plan default duration">
                        <p class="text-sm text-gray-500 mt-1">Default: <span id="default-duration">-</span> days</p>
                        @error('duration_days')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Admin Note --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Note (Optional)</label>
                        <textarea name="admin_note" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Add any notes about this manual subscription assignment..."></textarea>
                        <p class="text-sm text-gray-500 mt-1">This note will be stored with the subscription record.</p>
                    </div>
                </div>

                {{-- Summary Box --}}
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg" id="summary-box" style="display: none;">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Assignment Summary</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li><strong>User:</strong> {{ $user->name }} ({{ $user->email }})</li>
                        <li><strong>Plan:</strong> <span id="summary-plan">-</span></li>
                        <li><strong>Duration:</strong> <span id="summary-duration">-</span> days</li>
                        <li><strong>Expires On:</strong> <span id="summary-expiry">-</span></li>
                    </ul>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Assign Subscription
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planSelect = document.getElementById('plan-select');
            const durationInput = document.getElementById('duration-input');
            const defaultDuration = document.getElementById('default-duration');
            const summaryBox = document.getElementById('summary-box');
            const summaryPlan = document.getElementById('summary-plan');
            const summaryDuration = document.getElementById('summary-duration');
            const summaryExpiry = document.getElementById('summary-expiry');

            planSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const duration = selectedOption.dataset.duration;

                if (duration) {
                    defaultDuration.textContent = duration;
                    durationInput.placeholder = `Default: ${duration} days`;
                    updateSummary();
                } else {
                    defaultDuration.textContent = '-';
                    summaryBox.style.display = 'none';
                }
            });

            durationInput.addEventListener('input', updateSummary);

            function updateSummary() {
                const selectedOption = planSelect.options[planSelect.selectedIndex];
                const planName = selectedOption.textContent.trim();
                const defaultDays = parseInt(selectedOption.dataset.duration) || 0;
                const customDays = parseInt(durationInput.value) || 0;
                const finalDays = customDays > 0 ? customDays : defaultDays;

                if (planSelect.value && finalDays > 0) {
                    summaryBox.style.display = 'block';
                    summaryPlan.textContent = planName.split('-')[0].trim();
                    summaryDuration.textContent = finalDays;

                    // Calculate expiry date
                    const expiryDate = new Date();
                    expiryDate.setDate(expiryDate.getDate() + finalDays);
                    summaryExpiry.textContent = expiryDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                } else {
                    summaryBox.style.display = 'none';
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
