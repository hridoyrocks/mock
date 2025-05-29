<x-layout>
    <x-slot:title>Microphone Check - IELTS Speaking Test</x-slot>
    
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
                    <span class="text-2xl mr-2">🎤</span>
                    <h2 class="text-xl font-medium">Microphone Check</h2>
                </div>
                
                <div class="p-8 bg-gray-100 text-center">
                    <p class="text-lg mb-6">Please test your microphone to ensure it's working properly for the speaking test.</p>
                    
                    <div class="mb-6">
                        <button id="test-microphone-button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md border shadow-sm mb-4">
                            Test Microphone
                        </button>
                        
                        <div id="microphone-status" class="text-sm text-gray-600 mb-4"></div>
                        
                        <audio id="test-audio" controls class="hidden w-full mb-4"></audio>
                    </div>
                    
                    <div class="flex items-center justify-center text-red-600 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p>If you cannot use the microphone properly, please inform the invigilator.</p>
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
            const testButton = document.getElementById('test-microphone-button');
            const continueButton = document.getElementById('continue-button');
            const status = document.getElementById('microphone-status');
            const testAudio = document.getElementById('test-audio');
            
            let mediaRecorder;
            let chunks = [];
            let isRecording = false;
            
            testButton.addEventListener('click', async function() {
                if (!isRecording) {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        mediaRecorder = new MediaRecorder(stream);
                        
                        mediaRecorder.ondataavailable = function(e) {
                            chunks.push(e.data);
                        };
                        
                        mediaRecorder.onstop = function() {
                            const blob = new Blob(chunks, { type: 'audio/webm' });
                            chunks = [];
                            
                            const audioURL = URL.createObjectURL(blob);
                            testAudio.src = audioURL;
                            testAudio.classList.remove('hidden');
                            
                            status.innerHTML = '<span style="color: #10b981;">✓ Recording complete. Play the audio above to test.</span>';
                        };
                        
                        mediaRecorder.start();
                        isRecording = true;
                        
                        testButton.textContent = 'Stop Recording';
                        testButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        testButton.classList.add('bg-red-600', 'hover:bg-red-700');
                        status.innerHTML = '<span style="color: #ef4444;">● Recording... Speak for a few seconds</span>';
                        
                        // Auto-stop after 5 seconds
                        setTimeout(() => {
                            if (isRecording) {
                                testButton.click();
                            }
                        }, 5000);
                        
                    } catch (error) {
                        console.error('Error accessing microphone:', error);
                        status.innerHTML = '<span style="color: #ef4444;">Could not access microphone. Please check permissions.</span>';
                    }
                } else {
                    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                        mediaRecorder.stop();
                        
                        // Stop all tracks
                        const tracks = mediaRecorder.stream.getTracks();
                        tracks.forEach(track => track.stop());
                    }
                    
                    isRecording = false;
                    testButton.textContent = 'Test Microphone';
                    testButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                    testButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }
            });
            
            continueButton.addEventListener('click', function() {
                window.location.href = "{{ route('student.speaking.onboarding.instructions', $testSet) }}";
            });
        });
    </script>
    @endpush
</x-layout>