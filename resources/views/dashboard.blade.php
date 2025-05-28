<x-layout>
    <x-slot:title>Dashboard - IELTS Mock Test</x-slot>
    
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome to IELTS Mock Test Platform</h3>
                    
                    @if(auth()->user()->is_admin)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Test Management</h4>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('admin.sections.index') }}" class="text-blue-600 hover:underline">
                                            Manage Test Sections
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.test-sets.index') }}" class="text-blue-600 hover:underline">
                                            Manage Test Sets
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.questions.index') }}" class="text-blue-600 hover:underline">
                                            Manage Questions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Student Management</h4>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('admin.attempts.index') }}" class="text-green-600 hover:underline">
                                            View Student Attempts
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.attempts.index', ['status' => 'completed']) }}" class="text-green-600 hover:underline">
                                            Evaluate Completed Tests
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Take a Test</h4>
                                <p class="text-sm text-gray-600 mb-4">Select any section to practice:</p>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('student.listening.index') }}" class="bg-white border border-blue-200 rounded p-3 text-center hover:bg-blue-100 transition duration-200">
                                        <span class="block text-lg mb-1">🎧</span>
                                        <span class="font-medium">Listening</span>
                                    </a>
                                    
                                    <a href="{{ route('student.reading.index') }}" class="bg-white border border-blue-200 rounded p-3 text-center hover:bg-blue-100 transition duration-200">
                                        <span class="block text-lg mb-1">📖</span>
                                        <span class="font-medium">Reading</span>
                                    </a>
                                    
                                    <a href="{{ route('student.writing.index') }}" class="bg-white border border-blue-200 rounded p-3 text-center hover:bg-blue-100 transition duration-200">
                                        <span class="block text-lg mb-1">✍️</span>
                                        <span class="font-medium">Writing</span>
                                    </a>
                                    
                                    <a href="{{ route('student.speaking.index') }}" class="bg-white border border-blue-200 rounded p-3 text-center hover:bg-blue-100 transition duration-200">
                                        <span class="block text-lg mb-1">🎤</span>
                                        <span class="font-medium">Speaking</span>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Recent Results</h4>
                                
                                @php
                                    $recentAttempts = auth()->user()->attempts()->latest()->take(3)->get();
                                @endphp
                                
                                @if($recentAttempts->isNotEmpty())
                                    <ul class="space-y-2">
                                        @foreach($recentAttempts as $attempt)
                                            <li class="bg-white p-2 rounded border border-green-200">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <span class="font-medium">{{ $attempt->testSet->title }}</span>
                                                        <span class="text-xs text-gray-500 block">{{ $attempt->created_at->format('M d, Y') }}</span>
                                                    </div>
                                                    <div>
                                                        @if($attempt->band_score)
                                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                                Band: {{ $attempt->band_score }}
                                                            </span>
                                                        @else
                                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                                {{ ucfirst($attempt->status) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('student.results') }}" class="text-sm text-green-600 hover:underline">
                                            View all results →
                                        </a>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">You haven't taken any tests yet.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
