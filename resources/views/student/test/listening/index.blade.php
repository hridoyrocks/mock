{{-- resources/views/student/test/listening/index.blade.php --}}
<x-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute transform rotate-45 -top-24 -right-24 w-96 h-96 bg-white rounded-full"></div>
                <div class="absolute transform rotate-45 -bottom-24 -left-24 w-96 h-96 bg-white rounded-full"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 py-16">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur rounded-2xl mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-4">IELTS Listening Practice</h1>
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                        Master your listening skills with authentic audio materials and comprehensive question types
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $testSets->count() }}</div>
                        <div class="text-sm text-gray-600">Available Tests</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">30 min</div>
                        <div class="text-sm text-gray-600">Test Duration</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">40</div>
                        <div class="text-sm text-gray-600">Questions</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">4 Parts</div>
                        <div class="text-sm text-gray-600">Test Format</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Tests Section -->
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Tests</h2>

                    @if ($testSets->count() > 0)
                        <div class="space-y-6">
                            @foreach ($testSets as $testSet)
                                <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">
                                    <div class="p-8">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-4">
                                                    <div class="p-2 bg-blue-50 rounded-lg mr-4">
                                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-xl font-semibold text-gray-900">{{ $testSet->title }}</h3>
                                                        <p class="text-sm text-gray-500 mt-1">Complete all 4 parts to receive your band score</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex flex-wrap gap-4 mb-6">
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $testSet->questions->count() }} questions
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        30 minutes
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                        </svg>
                                                        4 parts
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @php
                                                $completedAttempt = auth()->user()->attempts()
                                                    ->where('test_set_id', $testSet->id)
                                                    ->where('status', 'completed')
                                                    ->first();
                                            @endphp
                                            
                                            @if($completedAttempt)
                                                <span class="px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-full">
                                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    Completed
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <button onclick="startTest('{{ route('student.listening.onboarding.confirm-details', $testSet) }}')"
                                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 group">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Start Practice
                                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                            
                                            @if($completedAttempt)
                                                <a href="{{ route('student.results.show', $completedAttempt) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                    View Results →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-amber-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-amber-800">No listening tests are available at the moment. Please check back later.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Test Format Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Format</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-semibold">1</div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Social Conversation</p>
                                    <p class="text-xs text-gray-500">Two speakers in everyday context</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-semibold">2</div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Everyday Monologue</p>
                                    <p class="text-xs text-gray-500">One speaker on general topic</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-semibold">3</div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Academic Discussion</p>
                                    <p class="text-xs text-gray-500">2-4 speakers in educational context</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-semibold">4</div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Academic Monologue</p>
                                    <p class="text-xs text-gray-500">University lecture or presentation</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pro Tips</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-700">Read questions during the preparation time</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-700">Listen for signpost words and phrases</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-700">Write answers while listening</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-700">Check spelling carefully</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Back Navigation -->
            <div class="mt-12 text-center">
                <a href="{{ route('student.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Tests
                </a>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 text-center shadow-2xl">
                <div class="mb-6">
                    <div class="relative">
                        <svg class="animate-spin h-20 w-20 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">Preparing Your Test</h3>
                <p class="text-gray-600 mb-6">Setting up your listening environment...</p>
                
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div id="progressBar" class="bg-gradient-to-r from-blue-600 to-blue-700 h-full rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>
                
                <div id="loadingStatus" class="mt-4 text-sm text-gray-500">
                    Initializing audio system...
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
            
            overlay.classList.remove('hidden');
            
            let progress = 0;
            const messages = [
                'Initializing audio system...',
                'Loading test materials...',
                'Preparing your workspace...',
                'Almost ready...'
            ];
            
            let messageIndex = 0;
            
            const progressInterval = setInterval(() => {
                progress += 25;
                progressBar.style.width = progress + '%';
                
                if (messageIndex < messages.length - 1) {
                    messageIndex++;
                    status.textContent = messages[messageIndex];
                }
                
                if (progress >= 100) {
                    clearInterval(progressInterval);
                    setTimeout(() => {
                        window.location.href = url;
                    }, 500);
                }
            }, 700);
        }
    </script>
    @endpush
</x-layout>