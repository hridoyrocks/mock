@extends('installer.layout')

@section('step1', 'bg-green-600')
@section('step2', 'bg-green-600')
@section('step3', 'bg-green-600')
@section('step4', 'bg-blue-600')

@section('content')
<h2 class="text-2xl font-bold mb-6">Database Configuration</h2>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        @foreach($errors->all() as $error)
            <p class="text-red-800">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('installer.database.save') }}">
    @csrf
    
    <div class="space-y-4">
        <div>
            <label for="database_hostname" class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
            <input type="text" id="database_hostname" name="database_hostname" value="{{ old('database_hostname', 'localhost') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="database_port" class="block text-sm font-medium text-gray-700 mb-1">Database Port</label>
            <input type="text" id="database_port" name="database_port" value="{{ old('database_port', '3306') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="database_name" class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
            <input type="text" id="database_name" name="database_name" value="{{ old('database_name') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="database_username" class="block text-sm font-medium text-gray-700 mb-1">Database Username</label>
            <input type="text" id="database_username" name="database_username" value="{{ old('database_username') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="database_password" class="block text-sm font-medium text-gray-700 mb-1">Database Password</label>
            <input type="password" id="database_password" name="database_password" value="" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">Leave blank if your database has no password</p>
        </div>
    </div>

    <div class="mt-8 flex justify-between">
        <a href="{{ route('installer.permissions') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg">
            Back
        </a>
        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
            Test Connection & Continue
        </button>
    </div>
</form>
@endsection
