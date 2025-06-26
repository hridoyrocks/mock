{{-- resources/views/student/test/reading/onboarding/confirm-details.blade.php --}}
<x-test-layout>
    <x-slot:title>Confirm Your Details - IELTS Reading Test</x-slot>
    
    <div class="min-h-screen">
        <!-- Main Header with Logos -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex justify-between">
                <div>
                    <img src="{{ asset('images/ielts-logo.png') }}" alt="IELTS" class="h-10">
                </div>
                <div class="flex space-x-8 items-center">
                    <img src="{{ asset('images/british-council.png') }}" alt="British Council" class="h-10">
                    <img src="{{ asset('images/idp.png') }}" alt="IDP" class="h-10">
                    <img src="{{ asset('images/cambridge.png') }}" alt="Cambridge Assessment English" class="h-10">
                </div>
            </div>
        </div>
        
        <!-- Dark navbar -->
        <div class="bg-gray-700 py-3">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Empty space for consistency with the IELTS interface -->
            </div>
        </div>
        
        <!-- Main Content - Light Blue Background -->
        <div class="bg-blue-50 min-h-screen py-8">
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
                                    <p>Date of birth:</p>
                                </div>
                                <div class="text-gray-800 font-medium">
                                    <p>{{ now()->format('d-m-Y') }}</p>
                                </div>
                                
                                <div class="text-gray-800">
                                    <p>Candidate Number:</p>
                                </div>
                                <div class="text-gray-800 font-medium">
                                    <p>{{ 'BI-' . str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</p>
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
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const confirmButton = document.getElementById('confirm-button');
            
            confirmButton.addEventListener('click', function() {
                window.location.href = "{{ route('student.reading.onboarding.instructions', $testSet) }}";
            });
        });
    </script>
    @endpush
</x-test-layout>