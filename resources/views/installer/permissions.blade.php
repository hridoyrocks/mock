@extends('installer.layout')

@section('step1', 'bg-green-600')
@section('step2', 'bg-green-600')
@section('step3', 'bg-blue-600')

@section('content')
<h2 class="text-2xl font-bold mb-6">Folder Permissions</h2>

<div class="space-y-3 mb-6">
    @foreach($permissions as $folder => $permission)
        <div class="bg-gray-50 p-4 rounded">
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-medium">{{ $folder }}</span>
                    <span class="text-sm text-gray-600 ml-2">({{ $permission['required'] }})</span>
                </div>
                @if($permission['isWritable'])
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path>
                    </svg>
                @endif
            </div>
        </div>
    @endforeach
</div>

@if(!$satisfied)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <p class="text-red-800">Please fix the folder permissions before proceeding.</p>
        <p class="text-sm text-red-600 mt-2">Run: <code class="bg-red-100 px-2 py-1 rounded">chmod -R 775 storage bootstrap/cache</code></p>
    </div>
@endif

<!-- Actions -->
<div class="flex justify-between">
    <a href="{{ route('installer.requirements') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg">
        Back
    </a>
    @if($satisfied)
        <a href="{{ route('installer.database') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
            Next
            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    @else
        <button disabled class="px-6 py-3 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed">
            Fix Permissions First
        </button>
    @endif
</div>
@endsection
