<x-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-gray-100 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <h1 class="text-2xl font-medium text-gray-900">Reading Tests</h1>
                <div class="flex justify-end">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">IELTS Computer-Delivered</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Available Reading Tests</h2>

                    @if ($testSets->count() > 0)
                        @foreach ($testSets as $testSet)
                            <div class="mb-6 border border-gray-200 rounded-md overflow-hidden">
                                <div class="p-4">
                                    <div class="flex items-center mb-2">
                                        <span class="text-blue-600 mr-2">📖</span>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $testSet->title }}</h3>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-500">Time: {{ $testSet->section->time_limit }} minutes</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-500">Questions: {{ $testSet->questions->where('question_type', '!=', 'passage')->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4">
                                        Practice your reading skills with this IELTS-style test.<br>
                                        Read passages and answer comprehension questions to receive your band score.
                                    </p>
                                    
                                    <a href="{{ route('student.reading.onboarding.confirm-details', $testSet) }}" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Start Test
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                        
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-4 bg-yellow-50 rounded-md">
                            <p class="text-yellow-700">No reading tests are available at the moment. Please check back later.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- IELTS Tips Section -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">IELTS Reading Tips</h3>
                    <ul class="space-y-2">
                        <li class="flex">
                            <span class="text-blue-600 mr-2">•</span>
                            <span class="text-gray-600">Skim through the passage first to get a general idea</span>
                        </li>
                        <li class="flex">
                            <span class="text-blue-600 mr-2">•</span>
                            <span class="text-gray-600">Read the questions carefully before looking for answers</span>
                        </li>
                        <li class="flex">
                            <span class="text-blue-600 mr-2">•</span>
                            <span class="text-gray-600">Look for keywords and synonyms in the passage</span>
                        </li>
                        <li class="flex">
                            <span class="text-blue-600 mr-2">•</span>
                            <span class="text-gray-600">Manage your time carefully - spend about 20 minutes per passage</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Back to Dashboard Link -->
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-layout>