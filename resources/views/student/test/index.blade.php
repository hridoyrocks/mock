<x-layout>
    <x-slot:title>Test Sections - IELTS Mock Test</x-slot>
    
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Sections') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-6">Select a Section to Practice</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('student.listening.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                            <div class="flex items-center mb-2">
                                <span class="text-3xl mr-3">🎧</span>
                                <h5 class="text-xl font-bold tracking-tight text-gray-900">Listening Section</h5>
                            </div>
                            <p class="font-normal text-gray-700 mb-3">
                                Practice your listening skills with authentic IELTS-style audio and questions.
                            </p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                30 minutes
                            </div>
                        </a>
                        
                        <a href="{{ route('student.reading.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                            <div class="flex items-center mb-2">
                                <span class="text-3xl mr-3">📖</span>
                                <h5 class="text-xl font-bold tracking-tight text-gray-900">Reading Section</h5>
                            </div>
                            <p class="font-normal text-gray-700 mb-3">
                                Test your reading comprehension with IELTS-style passages and questions.
                            </p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                60 minutes
                            </div>
                        </a>
                        
                        <a href="{{ route('student.writing.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                            <div class="flex items-center mb-2">
                                <span class="text-3xl mr-3">✍️</span>
                                <h5 class="text-xl font-bold tracking-tight text-gray-900">Writing Section</h5>
                            </div>
                            <p class="font-normal text-gray-700 mb-3">
                                Practice Task 1 and Task 2 writing with realistic prompts and auto-save feature.
                            </p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                60 minutes
                            </div>
                        </a>
                        
                        <a href="{{ route('student.speaking.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                            <div class="flex items-center mb-2">
                                <span class="text-3xl mr-3">🎤</span>
                                <h5 class="text-xl font-bold tracking-tight text-gray-900">Speaking Section</h5>
                            </div>
                            <p class="font-normal text-gray-700 mb-3">
                                Practice speaking with cue cards and record your responses for evaluation.
                            </p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                15 minutes
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>