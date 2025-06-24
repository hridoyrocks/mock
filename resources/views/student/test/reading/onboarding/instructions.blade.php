<x-test-layout>
    <x-slot:title>Test Instructions - IELTS Reading Test</x-slot>
    
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
        
        <div class="max-w-4xl mx-auto my-8">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-black p-4 text-white">
                    <h2 class="text-xl font-medium">Test Instructions</h2>
                </div>
                
                <div class="p-8 bg-gray-100">
                    <h1 class="text-2xl font-bold mb-2">IELTS Reading</h1>
                    <p class="mb-6">Time: {{ $testSet->section->time_limit }} minutes</p>
                    
                    <h2 class="text-xl font-bold mb-4">INSTRUCTIONS TO CANDIDATES</h2>
                    <ul class="list-disc pl-8 mb-6 space-y-2">
                        <li>Answer <strong>all</strong> questions.</li>
                        <li>You can change your answers at any time during the test.</li>
                        <li>Use the scroll bar to move up and down the passage.</li>
                        <li>You can click on the Review button to flag questions and return to them later.</li>
                    </ul>
                    
                    <h2 class="text-xl font-bold mb-4">INFORMATION FOR CANDIDATES</h2>
                    <ul class="list-disc pl-8 mb-6 space-y-2">
                        <li>There are {{ $testSet->questions->where('question_type', '!=', 'passage')->count() }} questions in this test.</li>
                        <li>Each question carries one mark.</li>
                        <li>The test will have three passages with questions.</li>
                        <li>You should spend about 20 minutes on each passage.</li>
                        <li>The passages will increase in difficulty.</li>
                    </ul>
                    
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
                window.location.href = "{{ route('student.reading.start', $testSet) }}";
            });
        });
    </script>
    @endpush
</x-test-layout>