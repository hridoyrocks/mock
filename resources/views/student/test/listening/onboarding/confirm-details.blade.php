{{-- resources/views/student/test/listening/onboarding/confirm-details.blade.php --}}
<x-test-layout>
    <x-slot:title>Confirm Your Details - IELTS Test</x-slot>
    
    <div class="min-h-screen overflow-hidden fixed inset-0">
        
        
        <!-- Dark navbar -->
        <div class="bg-gray-700 py-3">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Empty space for consistency with the IELTS interface -->
            </div>
        </div>
        
        <!-- Main Content - Light Blue Background -->
        <div class="bg-blue-50 min-h-screen pt-20">
            <div class="max-w-3xl mx-auto px-4">
                <!-- Confirm Details Box -->
                <div class="bg-white shadow-md rounded-md overflow-hidden">
                    <!-- Header with user icon -->
                    <div class="bg-black p-3 flex items-center text-white">
                        <div class="flex items-center">
                            <div class="bg-gray-200 p-1 rounded-md mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="bg-green-600 p-1 rounded-md mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h2 class="text-base font-medium">Confirm your details</h2>
                        </div>
                    </div>
                    
                    <!-- Candidate information - Gray background -->
                    <div class="p-6 bg-gray-100">
                        <!-- User Details -->
                        <div class="mb-6">
                            <div class="grid grid-cols-2 gap-y-4">
                                <div class="text-gray-800">
                                    <p>Name:</p>
                                </div>
                                <div class="text-gray-800 font-medium">
                                    <p>{{ auth()->user()->name }}</p>
                                </div>
                                
                                <div class="text-gray-800">
                                    <p>Date of Test:</p>
                                </div>
                                <div class="text-gray-800 font-medium">
                                    <p>{{ now()->format('d-m-Y') }}</p>
                                </div>
                                
                                <div class="text-gray-800">
                                    <p>Candidate Number:</p>
                                </div>
                                <div class="text-gray-800 font-medium">
                                    <p>{{ 'CD-' . str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Information Message -->
                        <div class="flex items-center text-blue-600 mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <p>If your details are not correct, please inform the invigilator.</p>
                        </div>
                        
                        @if(isset($attempts) && $attempts->count() > 0)
                            <!-- Previous Attempts Section -->
                            <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200">
                                <h3 class="font-medium text-gray-800 mb-3">Previous Attempts</h3>
                                <div class="space-y-2">
                                    @foreach($attempts as $previousAttempt)
                                        <div class="flex items-center justify-between text-sm">
                                            <div>
                                                <span class="text-gray-600">Attempt {{ $previousAttempt->attempt_number }}:</span>
                                                <span class="font-medium text-gray-800">{{ $previousAttempt->created_at->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                @if($previousAttempt->band_score)
                                                    <span class="font-medium text-gray-800">Band: {{ $previousAttempt->band_score }}</span>
                                                @endif
                                                <a href="{{ route('student.results.show', $previousAttempt) }}" 
                                                   class="text-blue-600 hover:text-blue-800 underline">
                                                    View Result
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($canRetake && $attempts->first()->status === 'completed')
                                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                        <p class="text-sm text-yellow-800">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            You are about to start a retake of this test (Attempt {{ $attempts->first()->attempt_number + 1 }}).
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Button -->
                        <div class="flex justify-center">
                            <button id="confirm-button" data-testset="{{ $testSet->id }}" 
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded border border-gray-300 shadow-sm">
                                My details are correct
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        /* Disable scrolling */
        body {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const confirmButton = document.getElementById('confirm-button');
            
            confirmButton.addEventListener('click', function() {
                window.location.href = "{{ route('student.listening.onboarding.sound-check', $testSet) }}";
            });
            
            // Ensure body doesn't scroll
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            document.body.style.height = '100%';
        });
    </script>
    @endpush
</x-test-layout>