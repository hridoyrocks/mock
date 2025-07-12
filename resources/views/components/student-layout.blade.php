{{-- resources/views/components/student-layout.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Student Dashboard' }} - Banglay IELTS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Logo Animation */
        .logo-animate {
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: false, profileDropdown: false }" class="flex h-screen overflow-hidden">
        
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"></div>

        <!-- Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
             class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            
            <!-- Sidebar Header with Logo -->
            <div class="flex items-center justify-center h-20 bg-gradient-to-r from-indigo-600 to-purple-600 relative overflow-hidden">
             
                
                <!-- Logo -->
                <div>
                    <a href="{{ route('student.dashboard') }}">
                        <img src="{{ asset('https://www.nasa.gov/wp-content/uploads/2018/07/s75-31690.jpeg') }}" alt="Banglay IELTS Logo" class="h-10 w-auto">
                    </a>
                </div>
                
                <!-- Mobile close button -->
                <button @click="sidebarOpen = false" class="absolute right-4 lg:hidden text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- User Profile Section -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-semibold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</h4>
                        <div class="flex items-center mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if(auth()->user()->subscription_status === 'pro') bg-purple-100 text-purple-800
                                @elseif(auth()->user()->subscription_status === 'premium') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-700
                                @endif">
                                <i class="fas fa-crown mr-1"></i>
                                {{ ucfirst(auth()->user()->subscription_status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Achievement Points Display -->
                <div class="mt-3 flex items-center justify-between text-xs">
                    <span class="text-gray-600">Achievement Points</span>
                    <span class="font-bold text-indigo-600">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        {{ auth()->user()->achievement_points ?? 0 }}
                    </span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto sidebar-scroll">
                <!-- Main Menu -->
                <div class="mb-8">
                    <h3 class="px-2 mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</h3>
                    
                    <a href="{{ route('student.dashboard') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.dashboard') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('student.dashboard') ? 'bg-indigo-100' : 'bg-gray-100' }}">
                            <i class="fas fa-home {{ request()->routeIs('student.dashboard') ? 'text-indigo-600' : 'text-gray-500' }}"></i>
                        </div>
                        Dashboard
                    </a>

                    <a href="{{ route('student.index') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.index') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('student.index') ? 'bg-indigo-100' : 'bg-gray-100' }}">
                            <i class="fas fa-book {{ request()->routeIs('student.index') ? 'text-indigo-600' : 'text-gray-500' }}"></i>
                        </div>
                        All Tests
                    </a>

                    <a href="{{ route('student.results') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.results*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('student.results*') ? 'bg-indigo-100' : 'bg-gray-100' }}">
                            <i class="fas fa-chart-line {{ request()->routeIs('student.results*') ? 'text-indigo-600' : 'text-gray-500' }}"></i>
                        </div>
                        My Results
                        @if($recentResults ?? 0)
                            <span class="ml-auto bg-red-100 text-red-600 text-xs font-medium px-2 py-1 rounded-full">{{ $recentResults }}</span>
                        @endif
                    </a>
                </div>

                <!-- Practice Tests -->
                <div class="mb-8">
                    <h3 class="px-2 mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Practice Tests</h3>
                    
                    <a href="{{ route('student.listening.index') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.listening.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('student.listening.*') ? 'bg-blue-100' : 'bg-gray-100' }}">
                            <i class="fas fa-headphones {{ request()->routeIs('student.listening.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        </div>
                        Listening
                        <span class="ml-auto text-xs text-gray-500">30 min</span>
                    </a>

                    <a href="{{ route('student.reading.index') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.reading.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('student.reading.*') ? 'bg-green-100' : 'bg-gray-100' }}">
                            <i class="fas fa-book-open {{ request()->routeIs('student.reading.*') ? 'text-green-600' : 'text-gray-500' }}"></i>
                        </div>
                        Reading
                        <span class="ml-auto text-xs text-gray-500">60 min</span>
                    </a>

                    <a href="{{ route('student.writing.index') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.writing.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('student.writing.*') ? 'bg-yellow-100' : 'bg-gray-100' }}">
                            <i class="fas fa-pen {{ request()->routeIs('student.writing.*') ? 'text-yellow-600' : 'text-gray-500' }}"></i>
                        </div>
                        Writing
                        <span class="ml-auto text-xs text-gray-500">60 min</span>
                    </a>

                    <a href="{{ route('student.speaking.index') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.speaking.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('student.speaking.*') ? 'bg-purple-100' : 'bg-gray-100' }}">
                            <i class="fas fa-microphone {{ request()->routeIs('student.speaking.*') ? 'text-purple-600' : 'text-gray-500' }}"></i>
                        </div>
                        Speaking
                        <span class="ml-auto text-xs text-gray-500">15 min</span>
                    </a>
                </div>

                <!-- Resources -->
                <div class="mb-8">
                    <h3 class="px-2 mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Resources</h3>
                    
                    <a href="#" class="flex items-center px-3 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-book-reader text-gray-500"></i>
                        </div>
                        Study Materials
                    </a>

                    <a href="#" class="flex items-center px-3 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-video text-gray-500"></i>
                        </div>
                        Video Tutorials
                    </a>

                    <a href="#" class="flex items-center px-3 py-3 mb-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-lightbulb text-gray-500"></i>
                        </div>
                        Tips & Strategies
                    </a>
                </div>

                <!-- Account -->
                <div>
                    <h3 class="px-2 mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</h3>
                    
                    <a href="{{ route('subscription.index') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('subscription.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('subscription.*') ? 'bg-indigo-100' : 'bg-gray-100' }}">
                            <i class="fas fa-credit-card {{ request()->routeIs('subscription.*') ? 'text-indigo-600' : 'text-gray-500' }}"></i>
                        </div>
                        My Subscription
                    </a>

                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center px-3 py-3 mb-2 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
                                    {{ request()->routeIs('profile.*') ? 'bg-indigo-100' : 'bg-gray-100' }}">
                            <i class="fas fa-user-cog {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-500' }}"></i>
                        </div>
                        Profile Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-sign-out-alt text-red-500"></i>
                            </div>
                            Sign Out
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-600 font-medium">Monthly Tests</span>
                    <span class="text-xs font-bold text-gray-700">
                        {{ auth()->user()->tests_taken_this_month }} / 
                        @php
                            $limit = auth()->user()->getFeatureLimit('mock_tests_per_month');
                        @endphp
                        {{ $limit === 'unlimited' ? '∞' : $limit }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    @php
                        $percentage = $limit === 'unlimited' ? 0 : (auth()->user()->tests_taken_this_month / $limit) * 100;
                    @endphp
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ min($percentage, 100) }}%"></div>
                </div>
                
                @if(auth()->user()->subscription_status === 'free')
                    <a href="{{ route('subscription.plans') }}" 
                       class="mt-3 w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-2.5 rounded-lg text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 inline-block shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-rocket mr-1"></i> Upgrade to Premium
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 z-30">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div class="flex-1 flex items-center">
                        @if(isset($header))
                            {{ $header }}
                        @else
                            <h1 class="text-xl font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                        @endif
                    </div>

                    <!-- Right side items -->
                    <div class="flex items-center space-x-4">
                        <!-- Study Streak Counter -->
                        <div class="hidden md:flex items-center bg-orange-50 px-3 py-1.5 rounded-lg">
                            <i class="fas fa-fire text-orange-500 mr-1"></i>
                            <span class="text-sm font-medium text-orange-700">{{ auth()->user()->study_streak_days ?? 0 }} days</span>
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            @endif
                        </button>

                        <!-- Help -->
                        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative" @click.outside="profileDropdown = false">
                            <button @click="profileDropdown = !profileDropdown" 
                                    class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="profileDropdown"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-100">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> Profile
                                </a>
                                <a href="{{ route('subscription.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-crown mr-2"></i> Subscription
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @stack('scripts')
</body>
</html>