@extends('installer.layout')

@section('step1', 'bg-green-600')
@section('step2', 'bg-green-600')
@section('step3', 'bg-green-600')
@section('step4', 'bg-green-600')
@section('step5', 'bg-green-600')
@section('step6', 'bg-green-600')

@section('content')
<div class="text-center">
    <svg class="w-20 h-20 text-green-600 mx-auto mb-6" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
    </svg>
    
    <h2 class="text-3xl font-bold text-gray-900 mb-4">Installation Complete!</h2>
    
    <p class="text-lg text-gray-600 mb-8">
        IELTS Mock Platform has been successfully installed. You can now login with your admin credentials.
    </p>

    <div class="bg-gray-50 p-6 rounded-lg mb-8 text-left">
        <h3 class="font-semibold text-gray-900 mb-3">What's Next?</h3>
        <ul class="space-y-2 text-gray-700">
            <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                </svg>
                <span>Create test sets and add questions</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                </svg>
                <span>Configure subscription plans and features</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                </svg>
                <span>Set up payment gateways (SSLCommerz/Stripe)</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                </svg>
                <span>Configure AI features (OpenAI API)</span>
            </li>
        </ul>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
        <p class="text-sm text-yellow-800">
            <strong>Security Note:</strong> For production use, make sure to update your .env file with proper 
            security settings and remove debug mode.
        </p>
    </div>

    <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-lg">
        Go to Admin Login
        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>
</div>
@endsection
