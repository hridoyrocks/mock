<x-student-layout>
    <x-slot:title>Purchase Evaluation Tokens</x-slot>
    
    <!-- Header Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">
                <!-- Balance Badge - Top Right -->
                <div class="absolute top-8 right-8 glass rounded-2xl px-6 py-4 border-yellow-500/30 hover:border-yellow-500/50 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-500 flex items-center justify-center">
                            <i class="fas fa-wallet text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Balance</p>
                            <p class="text-2xl font-bold text-white">{{ $tokenBalance->available_tokens }}</p>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white mb-3">
                        <i class="fas fa-coins text-yellow-400 mr-3"></i>
                        Token Packages
                    </h1>
                    <p class="text-gray-400">Choose a package that suits your needs</p>
                    
                    <!-- Info Button -->
                    <button onclick="openInfoModal()" 
                            class="mt-4 inline-flex items-center text-sm text-purple-400 hover:text-purple-300 transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>
                        How it works
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="px-4 sm:px-6 lg:px-8 pb-12 -mt-4">
        <div class="max-w-6xl mx-auto">
            <!-- Token Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($packages as $package)
                <div class="glass rounded-2xl p-6 {{ $package->is_popular ? 'border-yellow-500/50 ring-2 ring-yellow-500/20' : 'border-white/10' }} hover:border-purple-500/50 transition-all duration-300 transform hover:-translate-y-1">
                    @if($package->is_popular)
                    <div class="flex justify-center mb-4">
                        <span class="px-4 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-yellow-500 to-amber-500 text-white">
                            <i class="fas fa-star mr-1"></i>POPULAR
                        </span>
                    </div>
                    @endif
                    
                    <!-- Package Icon -->
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br 
                            {{ $loop->first ? 'from-blue-500 to-cyan-500' : ($loop->last ? 'from-purple-500 to-pink-500' : 'from-yellow-500 to-amber-500') }} 
                            flex items-center justify-center transform hover:scale-110 transition-transform">
                            <i class="fas {{ $loop->first ? 'fa-feather' : ($loop->last ? 'fa-crown' : 'fa-star') }} text-2xl text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Package Name -->
                    <h3 class="text-xl font-bold text-white text-center mb-4">{{ $package->name }}</h3>
                    
                    <!-- Token Amount -->
                    <div class="text-center mb-4">
                        <div class="flex items-baseline justify-center">
                            <span class="text-5xl font-bold text-white">{{ $package->total_tokens }}</span>
                            <span class="text-gray-400 ml-2">tokens</span>
                        </div>
                        @if($package->bonus_tokens > 0)
                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-green-500/20 border border-green-500/30">
                            <i class="fas fa-gift text-green-400 mr-1 text-sm"></i>
                            <span class="text-xs text-green-400 font-medium">+{{ $package->bonus_tokens }} bonus</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Price -->
                    <div class="text-center mb-6">
                        <div class="flex items-baseline justify-center">
                            <span class="text-gray-400">৳</span>
                            <span class="text-3xl font-bold text-white mx-1">{{ number_format($package->price, 0) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            ৳{{ number_format($package->price / $package->total_tokens, 1) }}/token
                        </p>
                    </div>
                    
                    <!-- Purchase Button -->
                    <button onclick="selectPackage({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})" 
                            class="w-full py-3 rounded-xl font-medium transition-all
                            {{ $package->is_popular 
                                ? 'bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white' 
                                : 'glass text-white hover:border-purple-500/50' }}">
                        Purchase
                    </button>
                </div>
                @endforeach
            </div>

            <!-- Recent Activity - Optional -->
            @if($recentTransactions && count($recentTransactions) > 0)
            <div class="mt-12">
                <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
                <div class="glass rounded-xl overflow-hidden">
                    <table class="w-full">
                        <tbody>
                            @foreach($recentTransactions->take(5) as $transaction)
                            <tr class="border-b border-white/5">
                                <td class="p-4 text-sm text-gray-400">{{ \Carbon\Carbon::parse($transaction->created_at)->diffForHumans() }}</td>
                                <td class="p-4">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $transaction->type === 'purchase' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="p-4 text-sm font-medium {{ $transaction->amount > 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                </td>
                                <td class="p-4 text-sm text-gray-500">{{ $transaction->reason }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </section>
    
    <!-- Info Modal -->
    <div id="infoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div onclick="closeInfoModal()" class="fixed inset-0 bg-black bg-opacity-80 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative glass rounded-2xl w-full max-w-2xl p-8 transform transition-all">
                <button onclick="closeInfoModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <h3 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    How Token System Works
                </h3>
                
                <!-- Steps -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-purple-400 font-bold">1</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Purchase Tokens</h4>
                            <p class="text-sm text-gray-400">Buy a token package that fits your evaluation needs</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-400 font-bold">2</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Complete Your Test</h4>
                            <p class="text-sm text-gray-400">Take a Writing or Speaking test</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-green-400 font-bold">3</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Choose a Teacher</h4>
                            <p class="text-sm text-gray-400">Select an expert IELTS teacher for evaluation</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center flex-shrink-0">
                            <span class="text-yellow-400 font-bold">4</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-white mb-1">Get Results</h4>
                            <p class="text-sm text-gray-400">Receive detailed feedback and band score</p>
                        </div>
                    </div>
                </div>
                
                <!-- Token Usage -->
                <div class="glass rounded-xl p-6">
                    <h4 class="font-medium text-white mb-4">Token Requirements</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Writing (Normal)</span>
                            <span class="text-white font-medium">10-15 tokens</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Writing (Urgent)</span>
                            <span class="text-white font-medium">15-20 tokens</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Speaking (Normal)</span>
                            <span class="text-white font-medium">8-12 tokens</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Speaking (Urgent)</span>
                            <span class="text-white font-medium">12-17 tokens</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <button onclick="closeInfoModal()" 
                            class="px-6 py-2 rounded-lg glass text-white hover:border-purple-500/50 transition-all">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div onclick="closePaymentModal()" class="fixed inset-0 bg-black bg-opacity-80 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative glass rounded-2xl w-full max-w-md p-6 transform transition-all">
                <button onclick="closePaymentModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
                
                <h3 class="text-xl font-bold text-white mb-6">Complete Purchase</h3>
                
                <form id="paymentForm" action="{{ route('student.tokens.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package_id" id="selectedPackageId">
                    
                    <!-- Package Summary -->
                    <div class="glass rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-400">Package</p>
                                <p id="packageName" class="font-medium text-white"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-white">৳<span id="packagePrice"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="space-y-3 mb-6">
                        <!-- Stripe -->
                        <label class="flex items-center p-3 rounded-lg glass cursor-pointer hover:border-purple-500/50 transition-all payment-method-option">
                            <input type="radio" name="payment_method" value="stripe" class="sr-only" onchange="selectPaymentMethod('stripe')">
                            <i class="fas fa-credit-card text-blue-400 text-lg mr-3"></i>
                            <span class="text-white flex-1">Credit/Debit Card</span>
                            <i class="fas fa-check-circle text-green-400 hidden payment-check"></i>
                        </label>
                        
                        <!-- bKash -->
                        <label class="flex items-center p-3 rounded-lg glass cursor-pointer hover:border-purple-500/50 transition-all payment-method-option">
                            <input type="radio" name="payment_method" value="bkash" class="sr-only" onchange="selectPaymentMethod('bkash')">
                            <div class="w-8 h-8 rounded bg-pink-600 flex items-center justify-center mr-3">
                                <span class="text-white text-xs font-bold">bK</span>
                            </div>
                            <span class="text-white flex-1">bKash</span>
                            <i class="fas fa-check-circle text-green-400 hidden payment-check"></i>
                        </label>
                        
                        <!-- Nagad -->
                        <label class="flex items-center p-3 rounded-lg glass cursor-pointer hover:border-purple-500/50 transition-all payment-method-option">
                            <input type="radio" name="payment_method" value="nagad" class="sr-only" onchange="selectPaymentMethod('nagad')">
                            <div class="w-8 h-8 rounded bg-orange-600 flex items-center justify-center mr-3">
                                <span class="text-white text-xs font-bold">N</span>
                            </div>
                            <span class="text-white flex-1">Nagad</span>
                            <i class="fas fa-check-circle text-green-400 hidden payment-check"></i>
                        </label>
                    </div>
                    
                    <!-- Stripe Card Details -->
                    <div id="stripeCardDetails" class="hidden mb-6">
                        <div class="glass rounded-lg p-3">
                            <div id="card-element" class="p-3"></div>
                            <div id="card-errors" class="text-red-400 text-sm mt-2"></div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitPayment"
                            disabled
                            class="w-full py-3 rounded-lg font-medium bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Complete Purchase</span>
                        <span id="submitLoading" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    
    <script>
        let stripe = null;
        let elements = null;
        let cardElement = null;
        let selectedPaymentMethod = null;
        
        // Initialize Stripe
        document.addEventListener('DOMContentLoaded', function() {
            @if(config('services.stripe.key'))
                stripe = Stripe('{{ config('services.stripe.key') }}');
                elements = stripe.elements();
                
                const style = {
                    base: {
                        color: '#ffffff',
                        fontFamily: '"Plus Jakarta Sans", sans-serif',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#6b7280'
                        }
                    },
                    invalid: {
                        color: '#ef4444'
                    }
                };
                
                cardElement = elements.create('card', { style: style });
            @endif
        });
        
        function openInfoModal() {
            document.getElementById('infoModal').classList.remove('hidden');
        }
        
        function closeInfoModal() {
            document.getElementById('infoModal').classList.add('hidden');
        }
        
        function selectPackage(packageId, packageName, packagePrice) {
            document.getElementById('selectedPackageId').value = packageId;
            document.getElementById('packageName').textContent = packageName;
            document.getElementById('packagePrice').textContent = packagePrice.toLocaleString();
            document.getElementById('paymentModal').classList.remove('hidden');
        }
        
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentForm').reset();
            selectedPaymentMethod = null;
            document.getElementById('submitPayment').disabled = true;
            document.querySelectorAll('.payment-check').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.payment-method-option').forEach(el => {
                el.classList.remove('border-purple-500', 'bg-white/5');
            });
            document.getElementById('stripeCardDetails').classList.add('hidden');
            if (cardElement) {
                cardElement.unmount();
            }
        }
        
        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            document.querySelectorAll('.payment-method-option').forEach(el => {
                el.classList.remove('border-purple-500', 'bg-white/5');
                el.querySelector('.payment-check').classList.add('hidden');
            });
            
            const selectedOption = document.querySelector(`input[value="${method}"]`).closest('.payment-method-option');
            selectedOption.classList.add('border-purple-500', 'bg-white/5');
            selectedOption.querySelector('.payment-check').classList.remove('hidden');
            
            if (method === 'stripe' && cardElement) {
                document.getElementById('stripeCardDetails').classList.remove('hidden');
                cardElement.mount('#card-element');
            } else {
                document.getElementById('stripeCardDetails').classList.add('hidden');
                if (cardElement) {
                    cardElement.unmount();
                }
            }
            
            document.getElementById('submitPayment').disabled = false;
        }
        
        // Handle form submission
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = document.getElementById('submitPayment');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            
            submitButton.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            
            try {
                if (selectedPaymentMethod === 'stripe' && stripe) {
                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                    });
                    
                    if (error) {
                        document.getElementById('card-errors').textContent = error.message;
                        submitButton.disabled = false;
                        submitText.classList.remove('hidden');
                        submitLoading.classList.add('hidden');
                        return;
                    }
                    
                    const response = await fetch(document.getElementById('paymentForm').action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            package_id: document.getElementById('selectedPackageId').value,
                            payment_method: 'stripe',
                            payment_method_id: paymentMethod.id
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.requires_action && stripe) {
                        const { error: confirmError } = await stripe.confirmCardPayment(result.client_secret);
                        
                        if (confirmError) {
                            throw new Error(confirmError.message);
                        }
                        
                        window.location.href = result.redirect || '{{ route("student.tokens.purchase") }}';
                    } else if (result.redirect) {
                        window.location.href = result.redirect;
                    } else if (result.success) {
                        window.location.href = '{{ route("student.tokens.purchase") }}';
                    } else {
                        throw new Error(result.error || 'Payment failed');
                    }
                } else {
                    document.getElementById('paymentForm').submit();
                }
            } catch (error) {
                alert('Payment failed: ' + error.message);
                submitButton.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        });
        
        // Click outside to close modals
        window.onclick = function(event) {
            if (event.target.id === 'infoModal') {
                closeInfoModal();
            }
            if (event.target.id === 'paymentModal') {
                closePaymentModal();
            }
        }
    </script>
    @endpush
</x-student-layout>
