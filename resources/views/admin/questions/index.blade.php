<x-admin-layout>
    <x-slot:title>Questions Management</x-slot>
    
    <!-- Main Container -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="bg-white shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h1 class="text-2xl font-semibold text-gray-900">Question Bank</h1>
                    <div class="mt-3 sm:mt-0 flex flex-col sm:flex-row gap-2">
                        <button class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Import Questions
                        </button>
                        <a href="{{ route('admin.questions.create') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Question
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-4 sm:px-6 lg:px-8 py-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $stats = [
                        'total' => \App\Models\Question::count(),
                        'listening' => \App\Models\Question::whereHas('testSet.section', fn($q) => $q->where('name', 'listening'))->count(),
                        'reading' => \App\Models\Question::whereHas('testSet.section', fn($q) => $q->where('name', 'reading'))->count(),
                        'writing' => \App\Models\Question::whereHas('testSet.section', fn($q) => $q->where('name', 'writing'))->count(),
                        'speaking' => \App\Models\Question::whereHas('testSet.section', fn($q) => $q->where('name', 'speaking'))->count(),
                    ];
                @endphp
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-indigo-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Questions</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Listening</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['listening'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Reading</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['reading'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-purple-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Writing & Speaking</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['writing'] + $stats['speaking'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="lg:hidden fixed bottom-4 right-4 z-20 bg-indigo-600 text-white p-3 rounded-full shadow-lg">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Sidebar -->
            <div id="sidebar" class="hidden lg:flex lg:flex-shrink-0 fixed lg:relative inset-y-0 left-0 z-30 lg:z-0 w-64 lg:w-80 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
                <div class="flex flex-col w-full bg-white border-r border-gray-200 h-full">
                    <!-- Sidebar Header -->
                    <div class="flex items-center justify-between px-4 py-4 border-b lg:hidden">
                        <h2 class="text-lg font-semibold">Test Sets</h2>
                        <button id="close-sidebar" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Test Sets List -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="px-4 py-3 border-b">
                            <h3 class="text-sm font-medium text-gray-900">Test Sets</h3>
                            <p class="mt-1 text-xs text-gray-500">Click on a test set to view questions</p>
                        </div>

                        @php
                            $selectedTestSetId = request('test_set');
                            $groupedTestSets = $testSets->groupBy('section.name');
                        @endphp
                        
                        @foreach(['listening', 'reading', 'writing', 'speaking'] as $section)
                            @if(isset($groupedTestSets[$section]) && $groupedTestSets[$section]->count() > 0)
                                <div class="px-4 py-2 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ ucfirst($section) }}
                                </div>
                                @foreach($groupedTestSets[$section] as $testSet)
                                    <a href="#" 
                                       onclick="loadTestSetQuestions({{ $testSet->id }}); return false;"
                                       data-test-set-id="{{ $testSet->id }}"
                                       class="test-set-link block px-4 py-3 hover:bg-gray-50 {{ $selectedTestSetId == $testSet->id ? 'bg-indigo-50 border-l-4 border-indigo-400' : '' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $testSet->title }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $testSet->questions_count }} questions
                                                </p>
                                            </div>
                                            @if($selectedTestSetId == $testSet->id)
                                                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Questions Display Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <div id="questions-container" class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-4">
                    @if($selectedTestSetId && $questions->count() > 0)
                        <!-- Questions will be loaded here via AJAX -->
                        @include('admin.questions.partials.questions-list', [
                            'questions' => $questions,
                            'selectedTestSet' => $testSets->find($selectedTestSetId)
                        ])
                    @else
                        <!-- Empty State -->
                        <div class="bg-white shadow rounded-lg h-full">
                            <div class="px-4 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Select a test set</h3>
                                <p class="mt-1 text-sm text-gray-500">Choose a test set from the left panel to view and manage questions.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('close-sidebar');

        mobileMenuButton?.addEventListener('click', () => {
            sidebar.classList.remove('hidden');
            setTimeout(() => {
                sidebar.classList.remove('-translate-x-full');
            }, 10);
        });

        closeSidebar?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            setTimeout(() => {
                sidebar.classList.add('hidden');
            }, 300);
        });

        // Load test set questions via AJAX
        function loadTestSetQuestions(testSetId) {
            // Show loading
            document.getElementById('loading-spinner').classList.remove('hidden');
            
            // Update active state
            document.querySelectorAll('.test-set-link').forEach(link => {
                link.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-400');
                if (link.dataset.testSetId == testSetId) {
                    link.classList.add('bg-indigo-50', 'border-l-4', 'border-indigo-400');
                }
            });

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('test_set', testSetId);
            window.history.pushState({}, '', url);

            // Fetch questions
            fetch(`/admin/questions/ajax/test-set/${testSetId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('questions-container').innerHTML = html;
                    
                    // Hide loading
                    document.getElementById('loading-spinner').classList.add('hidden');
                    
                    // Close mobile sidebar
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        setTimeout(() => {
                            sidebar.classList.add('hidden');
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loading-spinner').classList.add('hidden');
                    alert('Error loading questions. Please try again.');
                });
        }

        // Handle browser back/forward
        window.addEventListener('popstate', function(event) {
            const params = new URLSearchParams(window.location.search);
            const testSetId = params.get('test_set');
            if (testSetId) {
                loadTestSetQuestions(testSetId);
            } else {
                location.reload();
            }
        });
    </script>
    @endpush

    <style>
        @media (max-width: 1023px) {
            #sidebar.hidden {
                display: flex !important;
            }
        }
    </style>
</x-admin-layout>