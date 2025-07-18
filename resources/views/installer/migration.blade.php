@extends('installer.layout')

@section('step1', 'bg-green-600')
@section('step2', 'bg-green-600')
@section('step3', 'bg-green-600')
@section('step4', 'bg-green-600')
@section('step5', 'bg-blue-600')

@section('content')
<h2 class="text-2xl font-bold mb-6">Running Database Migrations</h2>

<div id="migration-status" class="mb-6">
    <div class="bg-gray-50 p-6 rounded-lg text-center">
        <div class="inline-flex items-center">
            <svg class="animate-spin h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-lg font-medium text-gray-700">Running migrations...</span>
        </div>
        <p class="text-sm text-gray-600 mt-2">This may take a few moments. Please do not close this window.</p>
    </div>
</div>

<div id="migration-success" class="hidden">
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center mb-6">
        <svg class="w-12 h-12 text-green-600 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-green-900 mb-2">Database Setup Complete!</h3>
        <p class="text-green-700">All tables have been created successfully.</p>
    </div>
    
    <div class="flex justify-end">
        <a href="{{ route('installer.admin') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
            Continue to Admin Setup
            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>

<div id="migration-error" class="hidden">
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-red-900 mb-2">Migration Failed</h3>
        <p class="text-red-700" id="error-message"></p>
    </div>
    
    <div class="flex justify-between">
        <a href="{{ route('installer.database') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg">
            Back to Database Settings
        </a>
        <button onclick="runMigration()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
            Retry Migration
        </button>
    </div>
</div>

<script>
function runMigration() {
    document.getElementById('migration-status').classList.remove('hidden');
    document.getElementById('migration-success').classList.add('hidden');
    document.getElementById('migration-error').classList.add('hidden');
    
    fetch('{{ route('installer.migration.run') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('migration-status').classList.add('hidden');
        
        if (data.success) {
            document.getElementById('migration-success').classList.remove('hidden');
        } else {
            document.getElementById('error-message').textContent = data.message || 'An error occurred during migration.';
            document.getElementById('migration-error').classList.remove('hidden');
        }
    })
    .catch(error => {
        document.getElementById('migration-status').classList.add('hidden');
        document.getElementById('error-message').textContent = 'Network error: ' + error.message;
        document.getElementById('migration-error').classList.remove('hidden');
    });
}

// Auto-run migration on page load
document.addEventListener('DOMContentLoaded', function() {
    runMigration();
});
</script>
@endsection
