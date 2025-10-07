<x-student-layout>
    <x-slot:title>Evaluation Status</x-slot>
    
    <div class="min-h-screen py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            
            
            <!-- Main Status Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                <!-- Status Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white">Human Evaluation Status</h1>
                            <p class="text-blue-100 mt-1">{{ $attempt->testSet->title }} - {{ ucfirst($attempt->testSet->section->name) }} Section</p>
                        </div>
                        <div class="text-center">
                            @if($evaluationRequest->status === 'completed')
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                    <i class="fas fa-check-circle text-white text-3xl"></i>
                                    <p class="text-white text-sm mt-1 font-medium">Completed</p>
                                </div>
                            @elseif($evaluationRequest->status === 'in_progress')
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                    <i class="fas fa-spinner fa-pulse text-white text-3xl"></i>
                                    <p class="text-white text-sm mt-1 font-medium">In Progress</p>
                                </div>
                            @else
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                                    <i class="fas fa-clock text-white text-3xl"></i>
                                    <p class="text-white text-sm mt-1 font-medium">Pending</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Progress Timeline -->
                <div class="px-8 py-6 bg-gray-50">
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <!-- Step 1: Requested -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white relative z-10">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                                <p class="text-xs font-medium text-gray-700 mt-2">Requested</p>
                                <p class="text-xs text-gray-500">{{ $evaluationRequest->requested_at->format('M d, h:i A') }}</p>
                            </div>
                            
                            <!-- Step 2: Assigned -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 {{ $evaluationRequest->status != 'pending' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white relative z-10">
                                    @if($evaluationRequest->status != 'pending')
                                        <i class="fas fa-check text-sm"></i>
                                    @else
                                        <i class="fas fa-user text-sm"></i>
                                    @endif
                                </div>
                                <p class="text-xs font-medium text-gray-700 mt-2">Assigned</p>
                                @if($evaluationRequest->assigned_at)
                                    <p class="text-xs text-gray-500">{{ $evaluationRequest->assigned_at->format('M d, h:i A') }}</p>
                                @endif
                            </div>
                            
                            <!-- Step 3: In Progress -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 {{ $evaluationRequest->status === 'in_progress' || $evaluationRequest->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white relative z-10">
                                    @if($evaluationRequest->status === 'in_progress')
                                        <i class="fas fa-spinner fa-pulse text-sm"></i>
                                    @elseif($evaluationRequest->status === 'completed')
                                        <i class="fas fa-check text-sm"></i>
                                    @else
                                        <i class="fas fa-edit text-sm"></i>
                                    @endif
                                </div>
                                <p class="text-xs font-medium text-gray-700 mt-2">Evaluating</p>
                            </div>
                            
                            <!-- Step 4: Completed -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 {{ $evaluationRequest->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white relative z-10">
                                    @if($evaluationRequest->status === 'completed')
                                        <i class="fas fa-check text-sm"></i>
                                    @else
                                        <i class="fas fa-flag text-sm"></i>
                                    @endif
                                </div>
                                <p class="text-xs font-medium text-gray-700 mt-2">Completed</p>
                                @if($evaluationRequest->completed_at)
                                    <p class="text-xs text-gray-500">{{ $evaluationRequest->completed_at->format('M d, h:i A') }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Progress Line -->
                        <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-300" style="z-index: 1;">
                            <div class="h-full bg-green-500 transition-all duration-500" 
                                 style="width: {{ $evaluationRequest->status === 'completed' ? '100%' : ($evaluationRequest->status === 'in_progress' ? '66%' : ($evaluationRequest->status === 'assigned' ? '33%' : '0%')) }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Teacher Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                Assigned Evaluator
                            </h3>
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex items-start space-x-4">
                                    @if($evaluationRequest->teacher->user->avatar_url)
                                        <img src="{{ $evaluationRequest->teacher->user->avatar_url }}" 
                                             alt="{{ $evaluationRequest->teacher->user->name }}" 
                                             class="w-20 h-20 rounded-xl object-cover">
                                    @else
                                        <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold text-2xl">
                                            {{ substr($evaluationRequest->teacher->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="text-gray-900 font-semibold text-lg">{{ $evaluationRequest->teacher->user->name }}</h4>
                                        
                                        <!-- Rating -->
                                        <div class="flex items-center mt-2">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $evaluationRequest->teacher->rating ? '' : 'text-gray-300' }} text-sm"></i>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600 ml-2">{{ number_format($evaluationRequest->teacher->rating, 1) }} rating</span>
                                        </div>
                                        
                                        <!-- Stats -->
                                        <div class="grid grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <p class="text-xs text-gray-500">Evaluations</p>
                                                <p class="font-semibold text-gray-900">{{ number_format($evaluationRequest->teacher->total_evaluations_done) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Avg. Time</p>
                                                <p class="font-semibold text-gray-900">{{ $evaluationRequest->teacher->average_turnaround_hours }}h</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Qualifications -->
                                        @if($evaluationRequest->teacher->qualifications && count($evaluationRequest->teacher->qualifications) > 0)
                                            <div class="mt-4">
                                                <p class="text-xs text-gray-500 mb-2">Qualifications</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach(array_slice($evaluationRequest->teacher->qualifications, 0, 3) as $qual)
                                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $qual }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Evaluation Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Evaluation Details
                            </h3>
                            <div class="space-y-4">
                                <!-- Priority & Tokens -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Priority Level</p>
                                        <p class="font-semibold text-lg {{ $evaluationRequest->priority === 'urgent' ? 'text-orange-600' : 'text-gray-900' }}">
                                            <i class="fas fa-{{ $evaluationRequest->priority === 'urgent' ? 'fire' : 'clock' }} mr-1"></i>
                                            {{ ucfirst($evaluationRequest->priority) }}
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm text-gray-600 mb-1">Tokens Used</p>
                                        <p class="font-semibold text-lg text-gray-900">
                                            <i class="fas fa-coins text-yellow-500 mr-1"></i>
                                            {{ $evaluationRequest->tokens_used }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Deadline -->
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-sm text-gray-600 mb-1">Deadline</p>
                                    <p class="font-semibold text-gray-900">{{ $evaluationRequest->deadline_at->format('M d, Y h:i A') }}</p>
                                    @if($evaluationRequest->status !== 'completed')
                                        @if($evaluationRequest->deadline_at->isPast())
                                            <p class="text-sm text-red-600 mt-1">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Overdue by {{ $evaluationRequest->deadline_at->diffForHumans(null, true) }}
                                            </p>
                                        @else
                                            <div class="mt-2">
                                                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                                    <span>Time Remaining</span>
                                                    <span>{{ $evaluationRequest->deadline_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    @php
                                                        $totalHours = $evaluationRequest->requested_at->diffInHours($evaluationRequest->deadline_at);
                                                        $passedHours = $evaluationRequest->requested_at->diffInHours(now());
                                                        $progress = min(100, ($passedHours / $totalHours) * 100);
                                                    @endphp
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                
                                @if($evaluationRequest->status === 'completed' && $evaluationRequest->humanEvaluation)
                                    <!-- Evaluation Result -->
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <p class="text-green-700 font-semibold text-lg">Evaluation Complete!</p>
                                                <p class="text-sm text-green-600 mt-1">
                                                    Completed {{ $evaluationRequest->completed_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-sm text-gray-600">Overall Band</p>
                                                <p class="text-3xl font-bold text-green-700">
                                                    {{ number_format($evaluationRequest->humanEvaluation->overall_band_score, 1) }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <a href="{{ route('student.evaluation.result', $attempt) }}" 
                                           class="block w-full text-center py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Detailed Evaluation
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($evaluationRequest->status !== 'completed')
                        <!-- What to Expect -->
                        <div class="mt-8 bg-blue-50 rounded-xl p-6">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                                What to Expect
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mr-3">
                                        <i class="fas fa-search text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Detailed Review</p>
                                        <p class="text-xs text-gray-600 mt-1">Your teacher will carefully review each task</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mr-3">
                                        <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Band Scores</p>
                                        <p class="text-xs text-gray-600 mt-1">Individual scores for each assessment criteria</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mr-3">
                                        <i class="fas fa-comments text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Personalized Feedback</p>
                                        <p class="text-xs text-gray-600 mt-1">Specific suggestions for improvement</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="text-center">
                <p class="text-gray-400 mb-4">Need assistance with your evaluation?</p>
                <div class="flex items-center justify-center space-x-4">
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-question-circle mr-2"></i>
                        FAQs
                    </a>
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-headset mr-2"></i>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>
