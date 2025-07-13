<x-student-layout>
    <x-slot:title>AI Evaluation in Progress</x-slot>
    
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="text-center max-w-lg">
            {{-- AI Animation --}}
            <div class="relative mb-8">
                <div class="w-32 h-32 mx-auto">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full animate-pulse"></div>
                    <div class="absolute inset-2 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-4xl text-purple-600 animate-bounce"></i>
                    </div>
                </div>
            </div>

            {{-- Status Text --}}
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                AI is Evaluating Your {{ ucfirst($type) }}...
            </h2>
            
            <p id="status-message" class="text-gray-600 mb-8">
                This may take 30-60 seconds. Please don't close this window.
            </p>

            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 rounded-full h-3 mb-8 overflow-hidden">
                <div id="progress-bar" 
                     class="bg-gradient-to-r from-purple-600 to-blue-600 h-full rounded-full transition-all duration-500 ease-out"
                     style="width: 0%">
                </div>
            </div>

            {{-- Progress Steps --}}
            <div class="max-w-md mx-auto space-y-4 text-left">
                <div class="flex items-center space-x-3" data-step="1" data-min-progress="0">
                    <div class="step-icon w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center">
                        <i class="fas fa-check text-sm hidden"></i>
                        <div class="spinner hidden"></div>
                    </div>
                    <span class="text-gray-700">Analyzing your response...</span>
                </div>
                
                <div class="flex items-center space-x-3" data-step="2" data-min-progress="25">
                    <div class="step-icon w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center">
                        <i class="fas fa-check text-sm hidden"></i>
                        <div class="spinner hidden"></div>
                    </div>
                    <span class="text-gray-700">Evaluating against IELTS criteria...</span>
                </div>
                
                <div class="flex items-center space-x-3" data-step="3" data-min-progress="50">
                    <div class="step-icon w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center">
                        <i class="fas fa-check text-sm hidden"></i>
                        <div class="spinner hidden"></div>
                    </div>
                    <span class="text-gray-700">Generating detailed feedback...</span>
                </div>
                
                <div class="flex items-center space-x-3" data-step="4" data-min-progress="80">
                    <div class="step-icon w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center">
                        <i class="fas fa-check text-sm hidden"></i>
                        <div class="spinner hidden"></div>
                    </div>
                    <span class="text-gray-700">Calculating band score...</span>
                </div>
            </div>

            {{-- Error Message --}}
            <div id="error-message" class="mt-8 p-4 bg-red-50 rounded-lg hidden">
                <p class="text-red-800">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="error-text"></span>
                </p>
                <button onclick="retryEvaluation()" class="mt-4 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                    Try Again
                </button>
            </div>

            {{-- Manual Check Button --}}
            <div class="mt-8">
                <a href="{{ route('ai.evaluation.get', $attempt->id) }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg inline-block hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Check Results
                </a>
            </div>

            {{-- Tip --}}
            <div class="mt-12 p-4 bg-blue-50 rounded-lg max-w-md mx-auto">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-lightbulb mr-2"></i>
                    <span class="font-semibold">Did you know?</span> 
                    Our AI evaluator analyzes your response using the same criteria as real IELTS examiners.
                </p>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid #f3f4f6;
        border-top: 2px solid #9333ea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
    @endpush

    @push('scripts')
    <script>
    const attemptId = {{ $attempt->id }};
    let checkInterval;
    let timeoutTimer;
    let checkCount = 0;

    function updateProgress(progress, message) {
        // Update progress bar
        document.getElementById('progress-bar').style.width = progress + '%';
        
        // Update message
        if (message) {
            document.getElementById('status-message').textContent = message;
        }
        
        // Update steps
        document.querySelectorAll('[data-step]').forEach(step => {
            const minProgress = parseInt(step.dataset.minProgress);
            const icon = step.querySelector('.step-icon');
            const checkIcon = icon.querySelector('.fa-check');
            const spinner = icon.querySelector('.spinner');
            
            if (progress > minProgress) {
                icon.classList.remove('bg-gray-300');
                icon.classList.add('bg-purple-600');
                
                if (progress >= minProgress + 25) {
                    checkIcon.classList.remove('hidden');
                    spinner.classList.add('hidden');
                } else {
                    checkIcon.classList.add('hidden');
                    spinner.classList.remove('hidden');
                }
            }
        });
    }

    function checkStatus() {
        checkCount++;
        console.log(`Status check #${checkCount}`);
        
        fetch(`/ai/evaluation/status/${attemptId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Status update:', data);
                
                if (data.status === 'completed') {
                    clearInterval(checkInterval);
                    clearTimeout(timeoutTimer);
                    updateProgress(100, 'Evaluation completed! Redirecting...');
                    
                    // Force redirect
                    console.log('Redirecting to:', data.redirect_url);
                    if (data.redirect_url) {
                        window.location.replace(data.redirect_url);
                    } else {
                        window.location.href = `/ai/evaluation/${attemptId}`;
                    }
                    
                } else if (data.status === 'failed') {
                    clearInterval(checkInterval);
                    clearTimeout(timeoutTimer);
                    showError(data.error || 'Evaluation failed. Please try again.');
                    
                } else if (data.status === 'processing') {
                    updateProgress(data.progress || 50, data.message);
                }
            })
            .catch(error => {
                console.error('Status check error:', error);
                // Don't stop on errors
            });
    }

    function showError(message) {
        document.getElementById('error-text').textContent = message;
        document.getElementById('error-message').classList.remove('hidden');
        document.getElementById('status-message').textContent = 'Evaluation failed';
    }

    function retryEvaluation() {
        window.location.reload();
    }

    // Check immediately on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, starting status checks...');
        
        // First check
        checkStatus();
        
        // Start regular checks
        checkInterval = setInterval(checkStatus, 2000);
        
        // Set timeout
        timeoutTimer = setTimeout(() => {
            clearInterval(checkInterval);
            console.log('Timeout reached, redirecting...');
            window.location.href = `/ai/evaluation/${attemptId}`;
        }, 30000); // 30 seconds timeout
    });
    </script>
    @endpush
</x-student-layout>