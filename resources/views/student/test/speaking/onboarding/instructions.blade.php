<x-test-layout>
    <x-slot:title>Test Instructions - IELTS Speaking Test</x-slot>
    
    <div class="min-h-screen bg-blue-50">
      
        <!-- Dark navbar -->
        <div class="bg-gray-800 py-2">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Empty space for consistency with the IELTS interface -->
            </div>
        </div>
        
        <div class="max-w-4xl mx-auto my-8">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-black p-4 text-white">
                    <h2 class="text-xl font-medium">Test Instructions</h2>
                </div>
                
                <div class="p-8 bg-gray-100">
                    <h1 class="text-2xl font-bold mb-2">IELTS Speaking</h1>
                    <p class="mb-6">Time: {{ $testSet->section->time_limit }} minutes</p>
                    
                    <h2 class="text-xl font-bold mb-4">INSTRUCTIONS TO CANDIDATES</h2>
                    <ul class="list-disc pl-8 mb-6 space-y-2">
                        <li>Answer <strong>all</strong> parts of the test.</li>
                        <li>Use the record button to capture your responses.</li>
                        <li>You can re-record your answers if needed.</li>
                        <li>Speak clearly and at a natural pace.</li>
                    </ul>
                    
                    <h2 class="text-xl font-bold mb-4">INFORMATION FOR CANDIDATES</h2>
                    <ul class="list-disc pl-8 mb-6 space-y-2">
                        <li>There are <strong>3 parts</strong> in this test.</li>
                        <li><strong>Part 1:</strong> Introduction and interview (1-2 minutes)</li>
                        <li><strong>Part 2:</strong> Long turn with cue card (3-4 minutes)</li>
                        <li><strong>Part 3:</strong> Discussion (4-5 minutes)</li>
                        <li>Your responses will be recorded and evaluated by qualified IELTS examiners.</li>
                        <li>This is a simulated speaking test to help you practice.</li>
                    </ul>
                    
                    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-md mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-medium text-yellow-800">Important:</p>
                                <p class="text-yellow-700 text-sm mt-1">Make sure your microphone is working properly before starting the test. You will need to record your voice for all three parts.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center text-blue-600 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p>When you are ready to begin, click 'Start test'.</p>
                    </div>
                    
                    <div class="flex justify-center">
                        <button id="start-test-button" data-testset="{{ $testSet->id }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-md border shadow-sm">
                            Start test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startButton = document.getElementById('start-test-button');
            
            startButton.addEventListener('click', function() {
                window.location.href = "{{ route('student.speaking.start', $testSet) }}";
            });
        });
    </script>
    @endpush
</x-test-layout>