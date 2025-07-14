{{-- resources/views/student/test/listening/index.blade.php --}}
<x-student-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Minimal Header -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Listening Tests</h1>
                        <p class="text-sm text-gray-500 mt-1">Practice your listening skills</p>
                    </div>
                    <a href="{{ route('student.index') }}" 
                       class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tests Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if ($testSets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($testSets as $testSet)
                        @php
                            $attemptCount = \App\Models\StudentAttempt::where('test_set_id', $testSet->id)->count();
                            $userCompleted = auth()->user()->attempts()
                                ->where('test_set_id', $testSet->id)
                                ->where('status', 'completed')
                                ->exists();
                        @endphp
                        
                        <div class="bg-white rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                            <!-- Test Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">
                                        {{ $testSet->title }}
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            30 min
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ $attemptCount }} students
                                        </span>
                                    </div>
                                </div>
                                @if($userCompleted)
                                    <span class="flex items-center gap-1 text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Done
                                    </span>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="mt-6">
                                @if($userCompleted)
                                    @php
                                        $attempt = auth()->user()->attempts()
                                            ->where('test_set_id', $testSet->id)
                                            ->where('status', 'completed')
                                            ->first();
                                    @endphp
                                    <a href="{{ route('student.results.show', $attempt) }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                        View Results
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <button onclick="window.location.href='{{ route('student.listening.onboarding.confirm-details', $testSet) }}'"
                                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                        Start Test
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tests available</h3>
                    <p class="text-gray-500">Check back later for new listening tests.</p>
                </div>
            @endif
        </div>
    </div>
</x-student-layout>