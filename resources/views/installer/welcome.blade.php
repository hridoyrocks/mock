@extends('installer.layout')

@section('step1', 'bg-blue-600')

@section('content')
<div class="text-center">
    <h2 class="text-2xl font-bold mb-4">Welcome to IELTS Mock Platform</h2>
    <p class="text-gray-600 mb-8">
        This wizard will guide you through the installation process. 
        Please make sure you have the database credentials ready before proceeding.
    </p>
    
    <div class="bg-blue-50 p-4 rounded-lg mb-8">
        <h3 class="font-semibold text-blue-900 mb-2">Before you begin, please ensure you have:</h3>
        <ul class="text-left text-blue-800 space-y-1">
            <li>✓ Database name, username, and password</li>
            <li>✓ Write permissions on folders</li>
            <li>✓ PHP 8.2 or higher</li>
            <li>✓ MySQL 5.7 or higher</li>
        </ul>
    </div>
    
    <a href="{{ route('installer.requirements') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
        Let's Get Started
        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>
</div>
@endsection
