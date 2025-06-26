<x-student-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            {{-- Header --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
                    <p class="text-gray-600">Transaction ID: {{ $transaction->transaction_id }}</p>
                </div>
                <div class="text-right">
                    <img src="https://banglayielts.com/wp-content/uploads/2023/11/logo-2-2.png" alt="Logo" class="h-12 mb-2">
                    <p class="text-sm text-gray-600">Date: {{ $transaction->created_at->format('d M Y') }}</p>
                </div>
            </div>
            
            {{-- Billing Info --}}
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Bill To:</h3>
                    <p class="text-gray-600">{{ $user->name }}</p>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Payment Method:</h3>
                    <p class="text-gray-600 capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</p>
                    <p class="text-gray-600">Status: <span class="text-green-600 font-medium">{{ ucfirst($transaction->status) }}</span></p>
                </div>
            </div>
            
            {{-- Items --}}
            <div class="mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-2 text-gray-700">Description</th>
                            <th class="text-center py-2 text-gray-700">Duration</th>
                            <th class="text-right py-2 text-gray-700">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-4">
                                <p class="font-medium">{{ $plan->name }} Plan Subscription</p>
                                <p class="text-sm text-gray-600">{{ $plan->description }}</p>
                            </td>
                            <td class="py-4 text-center">{{ $plan->duration_days }} days</td>
                            <td class="py-4 text-right font-medium">৳{{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td colspan="2" class="py-4 text-right font-semibold">Total:</td>
                            <td class="py-4 text-right font-bold text-xl">৳{{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            {{-- Footer --}}
            <div class="text-center text-gray-600 text-sm">
                <p>Thank you for your purchase!</p>
                <p class="mt-2">If you have any questions, please contact <b> pay-mock@banglayielts.com </b></p>
            </div>
            
            {{-- Actions --}}
            <div class="mt-8 flex justify-center space-x-4">
                <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-print mr-2"></i> Print Invoice
                </button>
                <a href="{{ route('subscription.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    Back to Subscription
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .bg-white, .bg-white * {
            visibility: visible;
        }
        .bg-white {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button, a {
            display: none !important;
        }
    }
</style>
@endpush
</x-student-layout>