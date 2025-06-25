{{-- resources/views/student/test/speaking/index.blade.php --}}
<x-student-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-rose-600 to-pink-700 text-white">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">IELTS Speaking Practice</h1>
                        <p class="text-rose-100">Build confidence with interactive speaking practice sessions</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-32 h-32 text-rose-400 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="bg-white border-b shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Test Duration: <span class="font-semibold">11-14 minutes</span></span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Total Tests: <span class="font-semibold">{{ $testSets->count() }}</span></span>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Computer-Delivered IELTS</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Tests Section -->
                <div class="lg:col-span-2">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-1">Available Tests</h2>
                        <p class="text-gray-600">Select a test to begin your speaking practice</p>
                    </div>

                    @if ($testSets->count() > 0)
                        <div class="space-y-4">
                            @foreach ($testSets as $testSet)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $testSet->title }}</h3>
                                                    <p class="text-sm text-gray-600 mb-3">Complete all three parts to test your speaking fluency and coherence</p>
                                                    
                                                    <div class="flex flex-wrap gap-4 text-sm">
                                                        <div class="flex items-center text-gray-500">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            {{ $testSet->questions->count() }} questions
                                                        </div>
                                                        <div class="flex items-center text-gray-500">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            11-14 minutes
                                                        </div>
                                                        <div class="flex items-center text-gray-500">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                            </svg>
                                                            3 parts
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-shrink-0 ml-4">
                                                @php
                                                    $completedAttempt = auth()->user()->attempts()
                                                        ->where('test_set_id', $testSet->id)
                                                        ->where('status', 'completed')
                                                        ->first();
                                                @endphp
                                                
                                                @if($completedAttempt)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                        Completed
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex items-center justify-between">
                                            <button onclick="startTest('{{ route('student.speaking.onboarding.confirm-details', $testSet) }}')"
                                                class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Start Test
                                            </button>
                                            
                                            @if($completedAttempt)
                                                <a href="{{ route('student.results.show', $completedAttempt) }}" class="text-sm text-rose-600 hover:text-rose-800">
                                                    View Results →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-yellow-700">No speaking tests are available at the moment. Please check back later.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Test Format Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Format</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-sm font-semibold">1</span>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Part 1 (4-5 mins)</p>
                                    <p class="text-sm text-gray-600">Introduction & interview about familiar topics</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-sm font-semibold">2</span>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Part 2 (3-4 mins)</p>
                                    <p class="text-sm text-gray-600">Long turn - speak on a topic for 1-2 minutes</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-sm font-semibold">3</span>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Part 3 (4-5 mins)</p>
                                    <p class="text-sm text-gray-600">Two-way discussion on abstract topics</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessment Criteria -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Assessment Focus</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-rose-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Fluency & Coherence</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-rose-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Lexical Resource</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-rose-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Grammar Range & Accuracy</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-rose-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Pronunciation</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-rose-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Quick Tips
                        </h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-rose-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Speak naturally and fluently</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-rose-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Extend your answers with examples</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-rose-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Use a range of vocabulary</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-rose-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">Don't memorize answers</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Back Navigation -->
            <div class="mt-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4 text-center">
                <!-- Animated Spinner -->
                <div class="mb-6">
                    <svg class="animate-spin h-16 w-16 text-rose-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                <!-- Loading Text -->
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Preparing Your Test</h3>
                <p class="text-gray-600 mb-4">Hold on a moment...</p>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div id="progressBar" class="bg-rose-600 h-full rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>
                
                <!-- Status Messages -->
                <div id="loadingStatus" class="mt-4 text-sm text-gray-500">
                    Initializing test environment...
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function startTest(url) {
            const overlay = document.getElementById('loadingOverlay');
            const progressBar = document.getElementById('progressBar');
            const status = document.getElementById('loadingStatus');
            
            // Show loading overlay
            overlay.classList.remove('hidden');
            
            // Animate progress bar
            let progress = 0;
            const messages = [
                'Initializing test environment...',
                'Setting up microphone access...',
                'Preparing speaking questions...',
                'Almost ready...'
            ];
            
            let messageIndex = 0;
            
            // Update progress every 500ms
            const progressInterval = setInterval(() => {
                progress += 25;
                progressBar.style.width = progress + '%';
                
                // Update status message
                if (messageIndex < messages.length - 1) {
                    messageIndex++;
                    status.textContent = messages[messageIndex];
                }
                
                // When complete, redirect
                if (progress >= 100) {
                    clearInterval(progressInterval);
                    status.textContent = 'Redirecting to test...';
                    
                    // Small delay before redirect
                    setTimeout(() => {
                        window.location.href = url;
                    }, 500);
                }
            }, 700);
        }
    </script>
    @endpush
</x-student-layout>