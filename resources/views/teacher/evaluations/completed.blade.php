<x-teacher-layout>
    <x-slot:title>Completed Evaluations</x-slot>
    
    <x-slot:header>
        <h1 class="text-xl font-semibold text-white">Completed Evaluations</h1>
    </x-slot>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Completed</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $evaluations->total() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ $evaluations->filter(function($e) { 
                                return $e->completed_at && $e->completed_at->isCurrentMonth(); 
                            })->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tokens Earned</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ $evaluations->sum('tokens_used') }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-coins text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Avg. Band Score</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">
                            {{ number_format($evaluations->avg(function($e) { 
                                return $e->humanEvaluation->overall_band_score ?? 0; 
                            }), 1) }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Evaluations Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Evaluation History</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Set</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Band Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turnaround</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tokens</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($evaluations as $evaluation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $evaluation->student->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $evaluation->student->email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($evaluation->studentAttempt->testSet->section->name) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $evaluation->studentAttempt->testSet->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($evaluation->humanEvaluation)
                                        <span class="text-lg font-bold text-gray-900">
                                            {{ number_format($evaluation->humanEvaluation->overall_band_score, 1) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $evaluation->completed_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $evaluation->completed_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $evaluation->requested_at->diffInHours($evaluation->completed_at) }}h
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-coins text-yellow-500 mr-1"></i>
                                        {{ $evaluation->tokens_used }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('teacher.evaluations.show', $evaluation) }}" 
                                       class="text-emerald-600 hover:text-emerald-900">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-folder-open text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">No completed evaluations yet</p>
                                        <p class="text-sm mt-1">Your completed evaluations will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($evaluations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $evaluations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-teacher-layout>