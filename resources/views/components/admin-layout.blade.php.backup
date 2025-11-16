<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ \App\Models\WebsiteSetting::getSettings()->site_name }} Admin</title>

    @php
        $settings = \App\Models\WebsiteSetting::getSettings();
    @endphp
    
    <!-- Favicon -->
    @if($settings->favicon)
        <link rel="icon" type="image/png" href="{{ $settings->favicon_url }}">
    @endif
    
    <!-- Meta Tags -->
    @if($settings->meta_tags)
        @if($settings->meta_tags['description'] ?? null)
            <meta name="description" content="{{ $settings->meta_tags['description'] }}">
        @endif
        @if($settings->meta_tags['keywords'] ?? null)
            <meta name="keywords" content="{{ $settings->meta_tags['keywords'] }}">
        @endif
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
        }
        
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
        
        *::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        *::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        *::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
            border: 2px solid #f1f5f9;
        }
        
        *::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        [x-cloak] { display: none !important; }
        
        /* Fix for sidebar scroll */
        aside {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        
        aside nav {
            flex: 1;
            min-height: 0;
        }
        
        .sidebar-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: #6366f1;
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }
        
        .sidebar-link.active::before {
            height: 32px;
        }
        
        .sidebar-link:hover {
            background-color: #f8fafc;
        }
        
        .sidebar-link.active {
            background-color: #eef2ff;
            color: #6366f1;
        }
        
        .metric-card {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: #6366f1;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse-custom {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div x-data="{ 
        sidebarOpen: false,
        profileDropdown: false,
        notificationOpen: false,
        theme: localStorage.getItem('theme') || 'light'
    }" class="flex h-screen overflow-hidden bg-gray-50">
        
        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 flex flex-col w-[var(--sidebar-width)] transform bg-white shadow-xl transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:shadow-none">
            
            <!-- Logo -->
            <div class="flex h-[var(--header-height)] items-center justify-between border-b border-gray-200 px-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    @php
                        $settings = \App\Models\WebsiteSetting::getSettings();
                    @endphp
                    @if($settings->site_logo)
                        <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_title }}" class="h-10 w-auto">
                    @else
                        <div class="flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg">
                                <!-- Book Icon -->
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">{{ $settings->site_title }}</h1>
                                <p class="text-xs text-gray-500">Admin Panel</p>
                            </div>
                        </div>
                    @endif
                </a>
                <button @click="sidebarOpen = false" class="rounded-lg p-2 hover:bg-gray-100 lg:hidden">
                    <!-- X Icon -->
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto px-4 py-6">
                <!-- Main Menu -->
                @php
                    $user = auth()->user();
                    $showMainMenu = $user->is_admin || $user->hasPermission('dashboard.view') || $user->hasPermission('questions.view') || $user->hasPermission('test-sets.view') || $user->hasPermission('full-tests.view') || $user->hasPermission('test-categories.view') || $user->hasPermission('attempts.view');
                @endphp

                @if($showMainMenu)
                <div class="mb-8">
                    <h3 class="mb-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Main Menu</h3>

                    @if($user->is_admin || $user->hasPermission('dashboard.view'))
                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-700' }}">
                        <!-- Dashboard Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                        <span class="ml-auto rounded-full bg-indigo-600 px-2 py-0.5 text-xs text-white">New</span>
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('questions.view'))
                    <a href="{{ route('admin.questions.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.questions.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Question Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Questions</span>
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('test-sets.view'))
                    <a href="{{ route('admin.test-sets.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.test-sets.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Collection Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Test Sets</span>
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('full-tests.view'))
                    <a href="{{ route('admin.full-tests.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.full-tests.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Document Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Full Tests</span>
                        <span class="ml-auto rounded-full bg-indigo-600 px-2 py-0.5 text-xs text-white">New</span>
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('test-categories.view'))
                    <a href="{{ route('admin.test-categories.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.test-categories.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Folder Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        <span>Test Categories</span>
                        @if($categoryCount = \App\Models\TestCategory::count())
                            <span class="ml-auto bg-purple-500 text-white text-xs rounded-full px-2 py-0.5">{{ $categoryCount }}</span>
                        @endif
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('attempts.view'))
                    <a href="{{ route('admin.attempts.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.attempts.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Clipboard Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>Student Results</span>
                        @if($pendingCount = \App\Models\StudentAttempt::where('status', 'completed')->whereNull('band_score')->count())
                            <span class="ml-auto flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                        @endif
                    </a>
                    @endif
                </div>
                @endif

                <!-- Subscription Management -->
                @php
                    $showSubscriptions = $user->is_admin || $user->hasPermission('subscriptions.view') || $user->hasPermission('coupons.view');
                @endphp

                @if($showSubscriptions)
                <div class="mb-8">
                    <h3 class="mb-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Subscriptions</h3>

                    @if($user->is_admin || $user->hasPermission('subscriptions.view'))
                    <a href="{{ route('admin.subscriptions.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.subscriptions.index') ? 'active' : 'text-gray-700' }}">
                        <!-- Chart Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span>Analytics</span>
                    </a>

                    <a href="{{ route('admin.subscriptions.users') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.subscriptions.users') ? 'active' : 'text-gray-700' }}">
                        <!-- Users Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Users</span>
                    </a>

                    <a href="{{ route('admin.subscriptions.transactions') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.subscriptions.transactions') ? 'active' : 'text-gray-700' }}">
                        <!-- Credit Card Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Transactions</span>
                    </a>

                    <a href="{{ route('admin.subscription-plans.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.subscription-plans.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Package Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Plans</span>
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('coupons.view'))
                    <a href="{{ route('admin.coupons.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.coupons.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Tag Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>Coupons</span>
                        @if($activeCoupons = \App\Models\Coupon::active()->valid()->count())
                            <span class="ml-auto bg-green-500 text-white text-xs rounded-full px-2 py-0.5">{{ $activeCoupons }}</span>
                        @endif
                    </a>
                    @endif
                </div>
                @endif

                <!-- Human Evaluation Management -->
                @php
                    $showHumanEval = $user->is_admin || $user->hasPermission('teachers.view') || $user->hasPermission('token-packages.view') || $user->hasPermission('user-tokens.view');
                @endphp

                @if($showHumanEval)
                <div class="mb-8">
                    <h3 class="mb-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Human Evaluation</h3>

                    @if($user->is_admin || $user->hasPermission('teachers.view'))
                    <a href="{{ route('admin.teachers.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.teachers.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Teacher Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                        </svg>
                        <span>Teachers</span>
                        @if($teacherCount = \App\Models\Teacher::where('is_available', true)->count())
                            <span class="ml-auto bg-green-500 text-white text-xs rounded-full px-2 py-0.5">{{ $teacherCount }}</span>
                        @endif
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('token-packages.view'))
                    <a href="{{ route('admin.token-packages.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.token-packages.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Coins Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Token Packages</span>
                        @if($activePackages = \App\Models\TokenPackage::where('is_active', true)->count())
                            <span class="ml-auto bg-purple-500 text-white text-xs rounded-full px-2 py-0.5">{{ $activePackages }}</span>
                        @endif
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('user-tokens.view'))
                    <a href="{{ route('admin.user-tokens.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.user-tokens.*') ? 'active' : 'text-gray-700' }}">
                        <!-- User Coins Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>User Tokens</span>
                    </a>
                    @endif
                </div>
                @endif

                <!-- User Management -->
                @php
                    $showUserManagement = $user->is_admin || $user->hasPermission('users.view');
                @endphp

                @if($showUserManagement)
                <div class="mb-8">
                    <h3 class="mb-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">User Management</h3>

                    @if($user->is_admin)
                    <a href="{{ route('admin.roles.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.roles.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Shield Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Roles & Permissions</span>
                        <span class="ml-auto rounded-full bg-indigo-600 px-2 py-0.5 text-xs text-white">New</span>
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('users.view'))
                    <a href="{{ route('admin.users.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.users.*') && !request()->routeIs('admin.ban-appeals.*') && !request()->routeIs('admin.users.system') ? 'active' : 'text-gray-700' }}">
                        <!-- Users Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>All Users</span>
                        @if($totalUsers = \App\Models\User::count())
                            <span class="ml-auto bg-blue-500 text-white text-xs rounded-full px-2 py-0.5">{{ $totalUsers }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.users.system') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.users.system') ? 'active' : 'text-gray-700' }}">
                        <!-- System Users Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                        <span>System Users</span>
                        @if($systemUsers = \App\Models\User::where(function($q) { $q->where('is_admin', true)->orWhereHas('teacher'); })->count())
                            <span class="ml-auto bg-purple-500 text-white text-xs rounded-full px-2 py-0.5">{{ $systemUsers }}</span>
                        @endif
                    </a>
                    @endif

                    @if($user->is_admin || $user->hasPermission('users.view'))
                    <a href="{{ route('admin.ban-appeals.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.ban-appeals.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Ban Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <span>Ban Appeals</span>
                        @if($pendingAppeals = \App\Models\BanAppeal::where('status', 'pending')->count())
                            <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ $pendingAppeals }}</span>
                        @endif
                    </a>
                    @endif
                </div>
                @endif

                <!-- Marketing & Communication -->
                @php
                    $showMarketing = $user->is_admin || $user->hasPermission('announcements.view');
                @endphp

                @if($showMarketing)
                <div class="mb-8">
                    <h3 class="mb-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Marketing</h3>

                    @if($user->is_admin || $user->hasPermission('announcements.view'))
                    <a href="{{ route('admin.announcements.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.announcements.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Megaphone Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                        @if($activeAnnouncements = \App\Models\Announcement::active()->count())
                            <span class="ml-auto bg-purple-500 text-white text-xs rounded-full px-2 py-0.5">{{ $activeAnnouncements }}</span>
                        @endif
                    </a>
                    @endif
                </div>
                @endif

                <!-- Settings -->
                @php
                    $showSettings = $user->is_admin || $user->hasPermission('settings.view');
                @endphp

                @if($showSettings)
                <div class="mb-8">
                    <h3 class="mb-4 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Settings</h3>

                    @if($user->is_admin || $user->hasPermission('settings.view'))
                    <a href="{{ route('admin.settings.website') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Settings Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Website Settings</span>
                    </a>

                    <a href="{{ route('admin.maintenance.index') }}"
                       class="sidebar-link mb-2 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.maintenance.*') ? 'active' : 'text-gray-700' }}">
                        <!-- Tools Icon -->
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                        </svg>
                        <span>Maintenance Mode</span>
                    </a>
                    @endif
                </div>
                @endif
            </nav>
            
            <!-- Footer Actions -->
            <div class="p-4 border-t border-gray-200 mt-auto">
                <div class="space-y-2">
                    <a href="{{ route('admin.profile.edit') }}"
                       class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Profile
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Header -->
            <header class="relative z-30 flex h-[var(--header-height)] items-center border-b border-gray-200 bg-white px-4 shadow-sm lg:px-8">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = true" class="rounded-lg p-2 hover:bg-gray-100 lg:hidden">
                    <!-- Menu Icon -->
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Search -->
                <div class="ml-4 flex-1 lg:ml-0">
                    <div class="relative max-w-md">
                        <input type="search" 
                               placeholder="Search anything..." 
                               class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-10 pr-4 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all">
                        <!-- Search Icon -->
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Right side items -->
                <div class="ml-4 flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button @click="theme = theme === 'light' ? 'dark' : 'light'; localStorage.setItem('theme', theme)"
                            class="rounded-lg p-2 hover:bg-gray-100 transition-colors">
                        <!-- Sun Icon -->
                        <svg x-show="theme === 'light'" class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <!-- Moon Icon -->
                        <svg x-show="theme === 'dark'" x-cloak class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative rounded-lg p-2 hover:bg-gray-100 transition-colors">
                            <!-- Bell Icon -->
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-red-500 animate-pulse-custom"></span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-gray-200">
                            <div class="border-b border-gray-200 p-4">
                                <h3 class="font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <div class="p-4">
                                    <p class="text-sm text-gray-500">No new notifications</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="hidden items-center space-x-2 lg:flex">
                        <a href="{{ route('admin.questions.create') }}" 
                           class="flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                            <!-- Plus Icon -->
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>New Question</span>
                        </a>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center rounded-lg p-2 hover:bg-gray-100 transition-colors">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                                <span class="text-xs font-semibold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                            </div>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-gray-200">
                            <div class="p-1">
                                <a href="{{ route('admin.profile.edit') }}" class="flex items-center rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <!-- User Icon -->
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Profile</span>
                                </a>
                                <a href="#" class="flex items-center rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <!-- Settings Icon -->
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Settings</span>
                                    
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <!-- Logout Icon -->
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col overflow-hidden">
                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto bg-gray-50">
                    <div class="p-4 lg:p-8">
                        <div class="fade-in">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
                
                <!-- Fixed Footer -->
                <footer class="bg-white border-t border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-center md:text-left mb-2 md:mb-0">
                                <p class="text-gray-500 text-sm">
                                    {{ $settings->copyright_text ?? '© ' . date('Y') . ' ' . $settings->site_name . '. All rights reserved.' }}
                                </p>
                                @if($settings->footer_text)
                                    <p class="text-gray-400 text-xs mt-1">{{ $settings->footer_text }}</p>
                                @endif
                            </div>
                            @if($settings->hasSocialLinks())
                                <div class="flex space-x-4">
                                    @foreach($settings->social_links as $social)
                                        <a href="{{ $social['url'] }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <i class="{{ $social['icon'] }}"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    @stack('scripts')
    
    <script>
        // Toast notification function
        function showToast(message, type = 'info') {
            const colors = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500'
            };
            
            const toast = document.createElement('div');
            toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            toast.textContent = message;
            
            document.getElementById('toast-container').appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>