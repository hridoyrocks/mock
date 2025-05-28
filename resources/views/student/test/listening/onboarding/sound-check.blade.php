{{-- resources/views/student/test/listening/onboarding/sound-check.blade.php --}}
<x-layout>
    <x-slot:title>Sound Check - IELTS Test</x-slot>
    
    <div class="min-h-screen bg-blue-50">
        <!-- Header with IELTS logos -->
        <div class="bg-white py-2 border-b">
            <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                <img src="{{ asset('images/ielts-logo.png') }}" alt="IELTS" class="h-10">
                <div class="flex space-x-6">
                    <img src="{{ asset('images/british-council.png') }}" alt="British Council" class="h-10">
                    <img src="{{ asset('images/idp.png') }}" alt="IDP" class="h-10">
                    <img src="{{ asset('images/cambridge.png') }}" alt="Cambridge Assessment English" class="h-10">
                </div>
            </div>
        </div>
        
        <!-- Dark navbar -->
        <div class="bg-gray-800 py-2">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Empty space for consistency with the IELTS interface -->
            </div>
        </div>
        
        <div class="max-w-3xl mx-auto my-12">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-black p-4 flex items-center text-white">
                    <span class="text-2xl mr-2">🎧</span>
                    <h2 class="text-xl font-medium">Sound Check</h2>
                </div>
                
                <div class="p-8 bg-gray-100 text-center">
                    <p class="text-lg mb-6">Put on your headphones and click on the Play Sound button to play a sample sound.</p>
                    
                    <button id="play-sound-button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md border shadow-sm mb-6 mx-auto">
                        Play Sound
                    </button>
                    
                    <div class="flex items-center justify-center text-red-600 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p>If you cannot hear the sound clearly, please check your audio settings.</p>
                    </div>
                    
                    <div class="flex justify-center">
                        <button id="continue-button" data-testset="{{ $testSet->id }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-md border shadow-sm">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const playButton = document.getElementById('play-sound-button');
            const continueButton = document.getElementById('continue-button');
            const audioElement = new Audio('/audio/sample.mp3');
            
            playButton.addEventListener('click', function() {
                audioElement.play();
            });
            
            continueButton.addEventListener('click', function() {
                window.location.href = "{{ route('student.listening.onboarding.instructions', $testSet) }}";
            });
        });
    </script>
    @endpush
</x-layout>