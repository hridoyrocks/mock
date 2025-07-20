<x-student-layout>
    <x-slot:title>Evaluation Status</x-slot>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('student.results.show', $attempt) }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Results
            </a>
        </div>
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Human Evaluation Status</h1>
            <p class="text-gray-400">{{ ucfirst($attempt->testSet->section->name) }} Test - {{ $attempt->testSet->title }}</p>
        </div>
        
        <!-- Status Card -->
        <div class="max-w-3xl mx-auto">
            <div class="glass rounded-2xl p-8">
                <!-- Status Badge -->
                <div class="text-center mb-8">
                    @if($evaluationRequest->status === 'completed')
                        <div class="inline-flex items-center px-6 py-3 rounded-full bg-green-500/20 border border-green-500/50">
                            <i class="fas fa-check-circle text-green-400 text-2xl mr-3"></i>
                            <span class="text-xl font-semibold text-green-400">Evaluation Completed</span>
                        </div>
                    @elseif($evaluationRequest->status === 'in_progress')
                        <div class="inline-flex items-center px-6 py-3 rounded-full bg-blue-500/20 border border-blue-500/50">
                            <i class="fas fa-spinner fa-pulse text-blue-400 text-2xl mr-3"></i>
                            <span class="text-xl font-semibold text-blue-400">Evaluation In Progress</span>
                        </div>
                    @else
                        <div class="inline-flex items-center px-6 py-3 rounded-full bg-yellow-500/20 border border-yellow-500/50">
                            <i class="fas fa-clock text-yellow-400 text-2xl mr-3"></i>
                            <span class="text-xl font-semibold text-yellow-400">Evaluation Pending</span>
                        </div>
                    @endif
                </div>
                
                <!-- Teacher Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-white mb-4">Assigned Teacher</h3>
                    <div class="glass rounded-xl p-6">
                        <div class="flex items-center space-x-4">
                            @if($evaluationRequest->teacher->user->avatar_url)
                                <img src="{{ $evaluationRequest->teacher->user->avatar_url }}" 
                                     alt="{{ $evaluationRequest->teacher->user->name }}" 
                                     class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xl">
                                    {{ substr($evaluationRequest->teacher->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-white font-semibold">{{ $evaluationRequest->teacher->user->name }}</h4>
                                <div class="flex items-center space-x-4 mt-1">
                                    <div class="flex items-center">
                                        <span class="text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $evaluationRequest->teacher->rating ? '' : 'opacity-30' }} text-sm"></i>
                                            @endfor
                                        </span>
                                        <span class="text-sm text-gray-400 ml-1">{{ number_format($evaluationRequest->teacher->rating, 1) }}</span>
                                    </div>
                                    <span class="text-sm text-gray-400">
                                        {{ $evaluationRequest->teacher->total_evaluations_done }} evaluations completed
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="glass rounded-xl p-6">
                        <p class="text-sm text-gray-400 mb-1">Requested At</p>
                        <p class="text-white font-medium">{{ $evaluationRequest->requested_at->format('M d, Y h:i A') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $evaluationRequest->requested_at->diffForHumans() }}</p>
                    </div>
                    
                    <div class="glass rounded-xl p-6">
                        <p class="text-sm text-gray-400 mb-1">Deadline</p>
                        <p class="text-white font-medium">{{ $evaluationRequest->deadline_at->format('M d, Y h:i A') }}</p>
                        @if($evaluationRequest->deadline_at->isPast())
                            <p class="text-xs text-red-400 mt-1">Overdue</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">{{ $evaluationRequest->deadline_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="text-center">
                        <p class="text-sm text-gray-400 mb-1">Priority</p>
                        <p class="font-semibold {{ $evaluationRequest->priority === 'urgent' ? 'text-red-400' : 'text-white' }}">
                            {{ ucfirst($evaluationRequest->priority) }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-400 mb-1">Tokens Used</p>
                        <p class="font-semibold text-white">
                            <i class="fas fa-coins text-yellow-400 mr-1"></i>
                            {{ $evaluationRequest->tokens_used }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-400 mb-1">Average Turnaround</p>
                        <p class="font-semibold text-white">{{ $evaluationRequest->teacher->average_turnaround_hours }}h</p>
                    </div>
                </div>
                
                @if($evaluationRequest->status === 'completed')
                    <!-- Completed Status -->
                    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-green-400 font-semibold">Evaluation Complete!</p>
                                <p class="text-sm text-gray-300 mt-1">
                                    Completed on {{ $evaluationRequest->completed_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            @if($evaluationRequest->humanEvaluation)
                                <div class="text-right">
                                    <p class="text-sm text-gray-400">Band Score</p>
                                    <p class="text-3xl font-bold text-white">{{ $evaluationRequest->humanEvaluation->overall_band_score }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('student.evaluation.result', $attempt) }}" 
                           class="block w-full text-center py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold hover:from-green-700 hover:to-emerald-700 transition-all">
                            <i class="fas fa-eye mr-2"></i>View Detailed Evaluation
                        </a>
                    </div>
                @else
                    <!-- Progress Information -->
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-6">
                        <p class="text-blue-400 font-semibold mb-2">What happens next?</p>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-0.5 mr-2"></i>
                                <span>Your test has been assigned to the teacher</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-{{ $evaluationRequest->status === 'in_progress' ? 'spinner fa-pulse text-blue-400' : 'clock text-gray-400' }} mt-0.5 mr-2"></i>
                                <span>Teacher is {{ $evaluationRequest->status === 'in_progress' ? 'currently evaluating' : 'reviewing' }} your responses</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-bell text-gray-400 mt-0.5 mr-2"></i>
                                <span>You'll be notified once evaluation is complete</span>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
            
            <!-- Help Section -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-400 mb-2">Need help?</p>
                <a href="#" class="text-purple-400 hover:text-purple-300 transition-colors">
                    <i class="fas fa-headset mr-2"></i>Contact Support
                </a>
            </div>
        </div>
    </div>
</x-student-layout>