<x-layout>
    <x-slot:title>Speaking Tests - IELTS Mock Test</x-slot>
    
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Speaking Tests') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-6">Available Speaking Tests</h3>
                    
                    @if ($testSets->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($testSets as $testSet)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">{{ $testSet->title }}</h5>
                                        <div class="mb-3 text-sm text-gray-500">
                                            <p>Time: {{ $testSet->section->time_limit }} minutes</p>
                                            <p>Questions: {{ $testSet->questions->count() }}</p>
                                        </div>
                                        <p class="mb-3 font-normal text-gray-700">
                                            Practice your speaking skills with this IELTS-style test.
                                        </p>
                                        <a href="{{ route('student.speaking.onboarding.confirm-details', $testSet) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                            Start Test
                                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 p-4 rounded-md">
                            <p class="text-yellow-700">No speaking tests are available at the moment. Please check back later.</p>
                        </div>
                    @endif
                    
                    <div class="mt-8">
                        <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline">
                            &larr; Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>