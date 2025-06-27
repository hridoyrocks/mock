<x-admin-layout>
    <x-slot:title>Test Sets - Admin</x-slot>
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">Test Sets Management</h1>
                        <p class="text-blue-100 text-sm mt-1">Manage all IELTS test sets across different sections</p>
                    </div>
                    <a href="{{ route('admin.test-sets.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-md text-sm font-medium hover:bg-blue-50 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Test Set
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Section Tabs -->
        <div class="mb-8 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Sections">
                @php
                    $currentSection = request('section', 'all');
                    $sectionData = [
                        'all' => ['name' => 'All Sections', 'icon' => 'M4 6h16M4 12h16M4 18h16', 'color' => 'gray'],
                        'reading' => ['name' => 'Reading', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'green'],
                        'listening' => ['name' => 'Listening', 'icon' => 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z', 'color' => 'purple'],
                        'writing' => ['name' => 'Writing', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'color' => 'indigo'],
                        'speaking' => ['name' => 'Speaking', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'color' => 'orange'],
                    ];
                @endphp
                
                @foreach($sectionData as $key => $section)
                    <a href="{{ route('admin.test-sets.index', $key === 'all' ? [] : ['section' => $key]) }}" 
                       class="group inline-flex items-center px-1 py-4 border-b-2 font-medium text-sm transition-all
                              {{ $currentSection === $key 
                                 ? 'border-' . $section['color'] . '-500 text-' . $section['color'] . '-600' 
                                 : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $currentSection === $key ? 'text-' . $section['color'] . '-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}"></path>
                        </svg>
                        {{ $section['name'] }}
                        @if($key !== 'all')
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $currentSection === $key ? 'bg-' . $section['color'] . '-100 text-' . $section['color'] . '-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $testSets->where('section.name', $key)->count() }}
                            </span>
                        @else
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $currentSection === $key ? 'bg-gray-100 text-gray-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $testSets->total() }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Section-specific Content -->
        @if($currentSection === 'reading')
            <!-- Reading Section Special Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Reading Stats -->
                <div class="lg:col-span-1 space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reading Section Stats</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-600">Total Sets</span>
                                    <span class="text-lg font-semibold text-green-600">{{ $testSets->where('section.name', 'reading')->count() }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($testSets->where('section.name', 'reading')->count() / max($testSets->total(), 1)) * 100 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Active Sets</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $testSets->where('section.name', 'reading')->where('active', true)->count() }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Question Types</p>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Multiple Choice</span>
                                        <span class="font-medium">45%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">True/False/NG</span>
                                        <span class="font-medium">30%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Fill in Blanks</span>
                                        <span class="font-medium">25%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                        <h4 class="text-sm font-semibold text-green-900 mb-3">Quick Actions</h4>
                        <div class="space-y-2">
                            <a href="{{ route('admin.test-sets.create', ['section' => 'reading']) }}" 
                               class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition-colors">
                                Create Reading Test
                            </a>
                            <button class="block w-full px-3 py-2 bg-white text-green-600 border border-green-300 rounded-md text-sm hover:bg-green-50 transition-colors">
                                Import Questions
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Reading Test Sets List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Reading Test Sets</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($testSets->where('section.name', 'reading') as $testSet)
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-base font-medium text-gray-900">{{ $testSet->title }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">Created {{ $testSet->created_at->diffForHumans() }}</p>
                                            <div class="flex items-center gap-4 mt-3">
                                                <span class="text-sm text-gray-600">
                                                    <span class="font-medium">3</span> Passages
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    <span class="font-medium">40</span> Questions
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    <span class="font-medium">60</span> Minutes
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            @if($testSet->active)
                                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                                            @endif
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                                                   class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.test-sets.edit', $testSet) }}" 
                                                   class="p-1.5 text-gray-400 hover:text-indigo-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($testSets->where('section.name', 'reading')->isEmpty())
                                <div class="p-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No reading tests yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Create your first reading test set.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
        @elseif($currentSection !== 'all' && in_array($currentSection, ['listening', 'writing', 'speaking']))
            <!-- Other Sections Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($testSets->where('section.name', $currentSection) as $testSet)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $testSet->title }}</h3>
                                @if($testSet->active)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                                @endif
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $testSet->created_at->format('M d, Y') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $testSet->section->time_limit }} minutes
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                                   class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                    View Details
                                </a>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.test-sets.edit', $testSet) }}" 
                                       class="p-1.5 text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.test-sets.destroy', $testSet) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($testSets->where('section.name', $currentSection)->isEmpty())
                    <div class="col-span-full">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No {{ $currentSection }} tests yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Create your first {{ $currentSection }} test set.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.test-sets.create', ['section' => $currentSection]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                                    Create Test Set
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
        @else
            <!-- All Sections Table View -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Test Set
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Section
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($testSets as $testSet)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $testSet->title }}</div>
                                        <div class="text-sm text-gray-500">ID: #{{ $testSet->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $sectionColors = [
                                                'listening' => 'purple',
                                                'reading' => 'green',
                                                'writing' => 'indigo',
                                                'speaking' => 'orange'
                                            ];
                                            $color = $sectionColors[$testSet->section->name] ?? 'gray';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                            {{ ucfirst($testSet->section->name) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($testSet->active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-1.5"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $testSet->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $testSet->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.test-sets.show', $testSet) }}" 
                                               class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                               title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.test-sets.edit', $testSet) }}" 
                                               class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.test-sets.destroy', $testSet) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this test set?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                                        title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if ($testSets->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No test sets found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new test set.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.test-sets.create') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                New Test Set
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if ($testSets->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $testSets->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-admin-layout>