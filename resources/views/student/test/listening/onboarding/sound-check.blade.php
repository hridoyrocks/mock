{{-- resources/views/student/test/listening/onboarding/sound-check.blade.php --}}
<x-test-layout>
    <x-slot:title>Sound Check - IELTS Test</x-slot>
    
    <div class="min-h-screen overflow-hidden fixed inset-0">
        
        <!-- Dark navbar with Volume Control -->
        <div class="bg-gray-800 py-2">
            <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
                <!-- User Info -->
                <div class="text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ auth()->user()->name }} - CD {{ str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <!-- Volume Control - Right side -->
                <div class="flex items-center space-x-3">
                    <button id="volume-down" class="text-white hover:text-gray-300 p-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div class="relative">
                        <div class="w-32 bg-gray-600 rounded-full h-2">
                            <div id="volume-progress" class="bg-white h-2 rounded-full transition-all duration-200" style="width: 50%"></div>
                        </div>
                        <input type="range" id="volume-slider" min="0" max="100" value="50" 
                               class="absolute top-0 left-0 w-full h-2 opacity-0 cursor-pointer">
                    </div>
                    
                    <button id="volume-up" class="text-white hover:text-gray-300 p-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <span id="volume-value" class="text-white text-sm ml-1">50%</span>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="bg-blue-50 min-h-screen pt-20">
            <div class="max-w-3xl mx-auto px-4">
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
            const playButton = document.getElementById('play-sound-button');
            const continueButton = document.getElementById('continue-button');
            const audioElement = new Audio('{{ asset("audio/cd-audio-check.mp3") }}');
            
            // Volume control elements
            const volumeSlider = document.getElementById('volume-slider');
            const volumeValue = document.getElementById('volume-value');
            const volumeProgress = document.getElementById('volume-progress');
            const volumeDown = document.getElementById('volume-down');
            const volumeUp = document.getElementById('volume-up');
            
            // Set initial volume
            audioElement.volume = 0.5;
            
            // Volume slider event
            volumeSlider.addEventListener('input', function() {
                const volume = this.value / 100;
                audioElement.volume = volume;
                volumeValue.textContent = this.value + '%';
                volumeProgress.style.width = this.value + '%';
            });
            
            // Volume down button
            volumeDown.addEventListener('click', function() {
                const currentVolume = parseInt(volumeSlider.value);
                const newVolume = Math.max(0, currentVolume - 10);
                volumeSlider.value = newVolume;
                volumeSlider.dispatchEvent(new Event('input'));
            });
            
            // Volume up button
            volumeUp.addEventListener('click', function() {
                const currentVolume = parseInt(volumeSlider.value);
                const newVolume = Math.min(100, currentVolume + 10);
                volumeSlider.value = newVolume;
                volumeSlider.dispatchEvent(new Event('input'));
            });
            
            playButton.addEventListener('click', function() {
                audioElement.play();
            });
            
            continueButton.addEventListener('click', function() {
                window.location.href = "{{ route('student.listening.onboarding.instructions', $testSet) }}";
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