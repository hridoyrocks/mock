<x-layout>
    <x-slot:title>My Results - IELTS Mock Test</x-slot>
    
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Test Results') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-6">Your Test Attempts</h3>
                    
                    @if ($attempts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Band Score</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($attempts as $attempt)
                                        <tr>
                                            <td class="py-4 px-6 text-sm text-gray-900">{{ $attempt->testSet->title }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-900">{{ ucfirst($attempt->testSet->section->name) }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-500">{{ $attempt->created_at->format('M d, Y, g:i a') }}</td>
                                            <td class="py-4 px-6 text-sm">
                                                @if ($attempt->status === 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Completed
                                                    </span>
                                                @elseif ($attempt->status === 'in_progress')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        In Progress
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Abandoned
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-900">
                                                @if ($attempt->band_score)
                                                    <span class="font-medium">{{ $attempt->band_score }}</span>
                                                @else
                                                    <span class="text-gray-500">Pending</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-sm">
                                                <a href="{{ route('student.results.show', $attempt) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $attempts->links() }}
                        </div>
                    @else
                        <div class="bg-yellow-50 p-4 rounded-md">
                            <p class="text-yellow-700">You haven't taken any tests yet. Go to the dashboard to start a test.</p>
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