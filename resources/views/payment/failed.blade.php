<x-student-layout>
    <x-slot:title>Payment Failed</x-slot>
    
    <div class="min-h-[60vh] flex items-center justify-center px-4">
        <div class="text-center max-w-md mx-auto">
            <!-- Error Icon -->
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-times text-4xl text-white"></i>
            </div>
            
            <!-- Message -->
            <h1 class="text-3xl font-bold text-white mb-4">Payment Failed</h1>
            <p class="text-gray-300 mb-8">
                {{ session('error') ?? 'Your payment could not be processed. Please try again or use a different payment method.' }}
            </p>
            
            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ route('student.tokens.purchase') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold hover:from-purple-700 hover:to-pink-700 transition-all">
                    <i class="fas fa-redo mr-2"></i>
                    Try Again
                </a>
                
                <div>
                    <a href="{{ route('student.dashboard') }}" 
                       class="text-gray-400 hover:text-white transition-colors">
                        Return to Dashboard
                    </a>
                </div>
            </div>
            
            <!-- Support Info -->
            <div class="mt-12 glass rounded-xl p-6">
                <p class="text-sm text-gray-400 mb-2">Need help?</p>
                <p class="text-white">
                    Contact our support team at 
                    <a href="mailto:support@example.com" class="text-purple-400 hover:text-purple-300">
                        support@example.com
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-student-layout>