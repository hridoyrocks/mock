@extends('installer.layout')

@section('step1', 'bg-green-600')
@section('step2', 'bg-green-600')
@section('step3', 'bg-green-600')
@section('step4', 'bg-green-600')
@section('step5', 'bg-green-600')
@section('step6', 'bg-blue-600')

@section('content')
<h2 class="text-2xl font-bold mb-6">Create Admin Account</h2>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        @foreach($errors->all() as $error)
            <p class="text-red-800">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('installer.admin.save') }}">
    @csrf
    
    <div class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" id="password" name="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
    </div>

    <div class="bg-blue-50 p-4 rounded-lg mt-6">
        <p class="text-sm text-blue-800">
            <strong>Important:</strong> This will be the super admin account with full access to the platform. 
            Make sure to remember these credentials.
        </p>
    </div>

    <div class="mt-8 flex justify-end">
        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
            Create Admin & Finish Installation
        </button>
    </div>
</form>
@endsection
