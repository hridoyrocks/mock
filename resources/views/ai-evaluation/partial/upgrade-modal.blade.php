{{-- Upgrade Modal Component --}}
<div id="upgradeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            {{-- Header --}}
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Unlock AI Evaluation</h3>
                <button onclick="closeUpgradeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            {{-- Content --}}
            <div class="mb-6">
                <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-4 mb-4">
                    <i class="fas fa-robot text-4xl text-purple-600 mb-2"></i>
                    <p class="text-gray-700">Get instant, detailed feedback on your IELTS writing and speaking with our advanced AI evaluator.</p>
                </div>
                
                <h4 class="font-semibold text-gray-900 mb-3">What you'll get:</h4>
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-gray-700">Instant band score prediction</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-gray-700">Detailed feedback on all IELTS criteria</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-gray-700">Personalized improvement suggestions</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                        <span class="text-gray-700">Track your progress over time</span>
                    </li>
                </ul>
            </div>
            
            {{-- CTA --}}
            <div class="flex space-x-4">
                <a href="{{ route('subscription.plans') }}" class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-700 transition">
                    Upgrade to Premium
                </a>
                <button onclick="closeUpgradeModal()" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Maybe Later
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showUpgradeModal() {
    document.getElementById('upgradeModal').classList.remove('hidden');
}

function closeUpgradeModal() {
    document.getElementById('upgradeModal').classList.add('hidden');
}

function startAIEvaluation(attemptId, type) {
    // Show loading state
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Starting evaluation...';
    
    // Submit form to start evaluation
    fetch(`/ai/evaluate/${type}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            attempt_id: attemptId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            alert(data.error || 'Failed to start evaluation');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-robot mr-2"></i> Get AI Evaluation';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-robot mr-2"></i> Get AI Evaluation';
    });
}
</script>