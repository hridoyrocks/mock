<x-test-layout>
    <x-slot:title>Microphone Check - IELTS Speaking Test</x-slot>
    
    <div class="min-h-screen bg-blue-50">
     
        
        <!-- Dark navbar -->
        <div class="bg-gray-800 py-2">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Empty space for consistency with the IELTS interface -->
            </div>
        </div>
        
        <div class="max-w-3xl mx-auto my-12">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-black p-4 flex items-center text-white">
                    <i class="fas fa-microphone text-2xl mr-3"></i>
                    <h2 class="text-xl font-medium">Microphone Check</h2>
                </div>
                
                <div class="p-8 bg-gray-100 text-center">
                    <p class="text-lg mb-6 font-medium">Please test your microphone to ensure it's working properly for the speaking test.</p>

                    <div class="mb-6">
                        <button id="test-microphone-button" class="bg-black hover:bg-gray-800 text-white font-semibold py-3 px-8 rounded-md border shadow-sm mb-4 transition-colors inline-flex items-center justify-center gap-2">
                            <i class="fas fa-microphone"></i>
                            <span>Test Microphone</span>
                        </button>

                        <div id="microphone-status" class="text-sm font-medium mb-4 min-h-[24px]"></div>

                        <audio id="test-audio" controls class="hidden w-full mb-2 rounded"></audio>
                    </div>
                    
                    <div class="flex items-center justify-center text-red-600 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p>If you cannot use the microphone properly, please inform the invigilator.</p>
                    </div>
                    
                    <div class="flex justify-center">
                        <button id="continue-button" data-testset="{{ $testSet->id }}" disabled class="bg-gray-300 text-gray-500 font-semibold py-2 px-6 rounded-md border shadow-sm cursor-not-allowed">
                            Continue
                        </button>
                    </div>

                    <p id="validation-message" class="text-sm text-red-600 mt-3 hidden flex items-center justify-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Please test your microphone and verify it's working before continuing</span>
                    </p>
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
            const validationMessage = document.getElementById('validation-message');

            let mediaRecorder;
            let chunks = [];
            let isRecording = false;
            let microphoneTested = false;
            let audioPlayed = false;

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

                            microphoneTested = true;
                            status.innerHTML = '<span class="inline-flex items-center gap-2 text-green-600 font-semibold"><i class="fas fa-check-circle"></i><span>Recording saved! Click play button above to verify your microphone.</span></span>';

                            // Check if we can enable continue button
                            checkContinueButton();
                        };

                        mediaRecorder.start();
                        isRecording = true;

                        testButton.innerHTML = '<i class="fas fa-stop-circle"></i><span>Stop Recording</span>';
                        testButton.classList.remove('bg-black', 'hover:bg-gray-800');
                        testButton.classList.add('bg-red-600', 'hover:bg-red-700');
                        status.innerHTML = '<span class="inline-flex items-center gap-2 text-red-600 font-semibold"><span class="w-2 h-2 bg-red-600 rounded-full animate-pulse"></span><span>Recording in progress... Please speak now!</span></span>';

                        // Auto-stop after 5 seconds
                        setTimeout(() => {
                            if (isRecording) {
                                testButton.click();
                            }
                        }, 5000);

                    } catch (error) {
                        console.error('Error accessing microphone:', error);

                        let errorMessage = 'Could not access microphone.';

                        if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                            errorMessage = 'Microphone access denied.';
                        } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                            errorMessage = 'No microphone detected.';
                        } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                            errorMessage = 'Microphone is being used by another application.';
                        }

                        status.innerHTML = '<span class="inline-flex items-center gap-2 text-red-600 font-semibold"><i class="fas fa-exclamation-circle"></i><span>' + errorMessage + '</span></span>';

                        microphoneTested = false;
                    }
                } else {
                    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                        mediaRecorder.stop();

                        // Stop all tracks
                        const tracks = mediaRecorder.stream.getTracks();
                        tracks.forEach(track => track.stop());
                    }

                    isRecording = false;
                    testButton.innerHTML = '<i class="fas fa-microphone"></i><span>Test Microphone</span>';
                    testButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                    testButton.classList.add('bg-black', 'hover:bg-gray-800');
                }
            });

            // Listen for audio playback
            testAudio.addEventListener('play', function() {
                if (!audioPlayed) {
                    audioPlayed = true;
                    status.innerHTML = '<span class="inline-flex items-center gap-2 text-green-600 font-semibold"><i class="fas fa-check-circle"></i><span>Microphone verified successfully! You can now continue.</span></span>';
                    checkContinueButton();
                }
            });

            // Check if continue button should be enabled
            function checkContinueButton() {
                if (microphoneTested && audioPlayed) {
                    continueButton.disabled = false;
                    continueButton.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                    continueButton.classList.add('bg-green-600', 'hover:bg-green-700', 'text-white', 'cursor-pointer', 'transform', 'transition-all', 'inline-flex', 'items-center', 'gap-2');
                    continueButton.innerHTML = '<i class="fas fa-check-circle"></i><span>Continue to Instructions</span>';
                    validationMessage.classList.add('hidden');

                    // Add pulse animation to draw attention
                    continueButton.style.animation = 'pulse 2s infinite';
                }
            }

            // Handle continue button click with validation
            continueButton.addEventListener('click', function(e) {
                if (!microphoneTested || !audioPlayed) {
                    e.preventDefault();
                    validationMessage.classList.remove('hidden');

                    // Shake animation for validation message
                    validationMessage.classList.add('animate-shake');
                    setTimeout(() => {
                        validationMessage.classList.remove('animate-shake');
                    }, 500);

                    // Also highlight what needs to be done
                    if (!microphoneTested) {
                        testButton.classList.add('ring-4', 'ring-red-300');
                        setTimeout(() => {
                            testButton.classList.remove('ring-4', 'ring-red-300');
                        }, 2000);
                    } else if (!audioPlayed) {
                        testAudio.classList.add('ring-4', 'ring-red-300');
                        setTimeout(() => {
                            testAudio.classList.remove('ring-4', 'ring-red-300');
                        }, 2000);
                    }

                    return;
                }

                // All checks passed, proceed to next step
                continueButton.disabled = true;
                continueButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading...</span>';
                window.location.href = "{{ route('student.speaking.onboarding.instructions', $testSet) }}";
            });
        });
    </script>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
        }

        .animate-shake {
            animation: shake 0.5s;
        }

        /* Recording indicator pulse */
        @keyframes recording-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
    @endpush
</x-test-layout>