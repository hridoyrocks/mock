<x-student-layout>
    <x-slot:title>Invoice #{{ $transaction->transaction_id }}</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-pink-600/20"></div>
        <div class="relative px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 mb-6 neon-purple">
                        <i class="fas fa-file-invoice text-white text-3xl"></i>
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4">
                        Invoice
                    </h1>
                    <p class="text-gray-300 text-lg">
                        Transaction ID: {{ $transaction->transaction_id }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4 sm:px-6 lg:px-8 -mt-8 pb-12">
        <div class="max-w-4xl mx-auto">
            <div id="invoice-content" class="glass rounded-2xl p-8 lg:p-12">
                {{-- Header --}}
                <div class="flex justify-between items-start mb-8 pb-8 border-b border-white/10">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">Payment Receipt</h2>
                        <div class="space-y-1">
                            <p class="text-gray-400 text-sm">Date: {{ $transaction->created_at->format('F d, Y') }}</p>
                            <p class="text-gray-400 text-sm">Time: {{ $transaction->created_at->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ ucfirst($transaction->status) }}
                        </div>
                    </div>
                </div>
                
                {{-- Billing Info --}}
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-purple-400 mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Billed To
                        </h3>
                        <div class="space-y-2">
                            <p class="text-white font-medium">{{ $user->name }}</p>
                            <p class="text-gray-400">{{ $user->email }}</p>
                            @if($user->phone_number)
                                <p class="text-gray-400">{{ $user->phone_number }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-purple-400 mb-4 flex items-center">
                            <i class="fas fa-credit-card mr-2"></i>
                            Payment Details
                        </h3>
                        <div class="space-y-2">
                            <p class="text-gray-400">
                                Method: <span class="text-white capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</span>
                            </p>
                            <p class="text-gray-400">
                                Currency: <span class="text-white">{{ $transaction->currency }}</span>
                            </p>
                            <p class="text-gray-400">
                                Reference: <span class="text-white text-sm">{{ $transaction->payment_reference ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Purchase Details --}}
                <div class="mb-8">
                    <h3 class="font-semibold text-purple-400 mb-6 flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Purchase Details
                    </h3>
                    <div class="glass rounded-xl overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-4 px-6 text-gray-400 font-medium">Item</th>
                                    <th class="text-center py-4 px-6 text-gray-400 font-medium">Duration</th>
                                    <th class="text-right py-4 px-6 text-gray-400 font-medium">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-white/5">
                                    <td class="py-6 px-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                                <i class="fas fa-crown text-white"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">{{ $plan->name }} Plan</p>
                                                <p class="text-sm text-gray-400">{{ $plan->description }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6 px-6 text-center text-gray-300">
                                        {{ $plan->duration_days }} days
                                    </td>
                                    <td class="py-6 px-6 text-right">
                                        <p class="text-2xl font-bold text-white">৳{{ number_format($transaction->amount, 2) }}</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-white/10">
                                    <td colspan="2" class="py-6 px-6 text-right">
                                        <p class="text-gray-400">Subtotal:</p>
                                        <p class="text-gray-400">Tax:</p>
                                        <p class="text-lg font-semibold text-white mt-2">Total:</p>
                                    </td>
                                    <td class="py-6 px-6 text-right">
                                        <p class="text-gray-300">৳{{ number_format($transaction->amount, 2) }}</p>
                                        <p class="text-gray-300">৳0.00</p>
                                        <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mt-2">
                                            ৳{{ number_format($transaction->amount, 2) }}
                                        </p>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                {{-- Footer --}}
                <div class="text-center pt-8 border-t border-white/10">
                    <div class="glass rounded-xl p-6 inline-block">
                        <p class="text-gray-300 mb-2">Thank you for your purchase!</p>
                        <p class="text-sm text-gray-400">
                            Questions? Contact us at 
                            <a href="mailto:pay-mock@banglayielts.com" class="text-purple-400 hover:text-purple-300">
                                pay-mock@banglayielts.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="mt-8 flex justify-center space-x-4">
                <a href="{{ route('subscription.invoice.download', $transaction) }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all neon-purple">
                    <i class="fas fa-download mr-2"></i>
                    Download PDF
                </a>
                
                
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        @media print {
            body {
                background: white !important;
            }
            .glass {
                background: white !important;
                color: black !important;
                border: 1px solid #e5e7eb !important;
            }
            .text-white, .text-gray-300, .text-gray-400, .text-purple-400 {
                color: black !important;
            }
            .bg-gradient-to-r, .bg-gradient-to-br {
                background: #f3f4f6 !important;
            }
            button, .mt-8, header, aside, nav {
                display: none !important;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadInvoice() {
            // Get the button that was clicked
            const btn = document.querySelector('button[onclick="downloadInvoice()"]');
            const originalText = btn.innerHTML;
            
            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';
            btn.disabled = true;
            
            // Clone the invoice content
            const element = document.getElementById('invoice-content').cloneNode(true);
            
            // Remove any scripts or unwanted elements from the clone
            const scripts = element.querySelectorAll('script');
            scripts.forEach(script => script.remove());
            
            const opt = {
                margin:       10,
                filename:     'invoice-{{ $transaction->transaction_id }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            // Generate PDF with error handling
            html2pdf().set(opt).from(element).save()
                .then(() => {
                    console.log('PDF generated successfully');
                    // Restore button
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                })
                .catch((error) => {
                    console.error('Error generating PDF:', error);
                    alert('Error generating PDF. Please try again or use the print option.');
                    // Restore button
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }
        
        // Alternative method using browser's print to PDF
        function printInvoice() {
            window.print();
        }
        
        // Check if html2pdf is loaded
        window.addEventListener('load', function() {
            if (typeof html2pdf === 'undefined') {
                console.error('html2pdf.js not loaded');
                // Hide download button and show only print
                const downloadBtn = document.querySelector('button[onclick="downloadInvoice()"]');
                if (downloadBtn) {
                    downloadBtn.style.display = 'none';
                }
            }
        });
    </script>
    @endpush
</x-student-layout>