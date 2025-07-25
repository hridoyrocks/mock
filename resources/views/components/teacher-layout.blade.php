{{-- resources/views/components/teacher-layout.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Teacher Dashboard' }} - {{ \App\Models\WebsiteSetting::getSettings()->site_name }}</title>

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
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #0f0f23;
        }
        
        [x-cloak] { display: none !important; }
        
        /* Modern Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glass-dark {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Neon Glow Effects for Teacher Theme */
        .neon-emerald {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.5),
                        0 0 40px rgba(16, 185, 129, 0.3),
                        0 0 60px rgba(16, 185, 129, 0.1);
        }
        
        .neon-teal {
            box-shadow: 0 0 20px rgba(20, 184, 166, 0.5),
                        0 0 40px rgba(20, 184, 166, 0.3),
                        0 0 60px rgba(20, 184, 166, 0.1);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #10b981, #14b8a6);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #059669, #0d9488);
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased overflow-hidden">
    <div x-data="layoutData()" 
         class="relative z-10 flex h-screen bg-gradient-to-br from-slate-900 via-emerald-900 to-slate-900 overflow-hidden">
        
        <!-- Mobile Menu Overlay -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 backdrop-blur-sm lg:hidden"></div>

        <!-- Teacher Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
             class="fixed inset-y-0 left-0 z-50 w-72 h-full glass-dark transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:h-screen flex flex-col flex-shrink-0">
            
            <!-- Logo Section -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center">
                        @if($settings->site_logo)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->site_title }}" class="h-12 w-auto">
                        @else
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center neon-emerald">
                                    <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Teacher Portal</h2>
                                    <p class="text-xs text-gray-400">{{ $settings->site_title }}</p>
                                </div>
                            </div>
                        @endif
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Teacher Stats -->
            <div class="p-6 border-b border-white/10">
                @php
                    $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first();
                @endphp
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" 
                                 class="w-16 h-16 rounded-xl object-cover border-2 border-emerald-500">
                        @else
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white font-bold text-xl">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        @if($teacher && $teacher->is_available)
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-slate-900"></div>
                        @else
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-gray-500 rounded-full border-2 border-slate-900"></div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-white font-semibold">{{ auth()->user()->name }}</h3>
                        <div class="flex items-center space-x-2 mt-1">
                            @if($teacher)
                                <div class="flex items-center">
                                    <span class="text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $teacher->rating ? '' : 'opacity-30' }} text-xs"></i>
                                        @endfor
                                    </span>
                                    <span class="text-xs text-gray-400 ml-1">{{ number_format($teacher->rating, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                @if($teacher)
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <div class="glass rounded-lg p-2 text-center">
                        <p class="text-xs text-gray-400">Completed</p>
                        <p class="text-lg font-bold text-white">{{ $teacher->total_evaluations_done }}</p>
                    </div>
                    <div class="glass rounded-lg p-2 text-center">
                        <p class="text-xs text-gray-400">Avg Time</p>
                        <p class="text-lg font-bold text-white">{{ $teacher->average_turnaround_hours }}h</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto py-4">
                <!-- Main Section -->
                <div class="px-4 mb-6">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Main</h4>
                    
                    <a href="{{ route('teacher.dashboard') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('teacher.dashboard') ? 'glass bg-gradient-to-r from-emerald-600/20 to-teal-600/20 border-emerald-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center
                                    {{ request()->routeIs('teacher.dashboard') ? 'neon-emerald' : '' }}">
                            <i class="fas fa-home text-white"></i>
                        </div>
                        <span class="text-white font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('teacher.evaluations.pending') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('teacher.evaluations.pending') ? 'glass bg-gradient-to-r from-amber-600/20 to-orange-600/20 border-amber-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-white font-medium block">Pending</span>
                            <span class="text-xs text-gray-400">Evaluations to complete</span>
                        </div>
                        @if($teacher && $teacher->evaluationRequests()->where('status', 'assigned')->count() > 0)
                            <span class="px-2 py-1 bg-amber-500 text-white text-xs rounded-full">
                                {{ $teacher->evaluationRequests()->where('status', 'assigned')->count() }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('teacher.evaluations.completed') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('teacher.evaluations.completed') ? 'glass bg-gradient-to-r from-green-600/20 to-emerald-600/20 border-green-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <span class="text-white font-medium">Completed</span>
                    </a>
                </div>

                <!-- Resources -->
                <div class="px-4 mb-6">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Resources</h4>
                    
                    <a href="#" class="group flex items-center space-x-3 px-4 py-3 rounded-xl hover:glass transition-all duration-200 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <span class="text-white font-medium">Evaluation Guide</span>
                    </a>

                    <a href="#" class="group flex items-center space-x-3 px-4 py-3 rounded-xl hover:glass transition-all duration-200 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <span class="text-white font-medium">Statistics</span>
                    </a>
                </div>

                <!-- Settings -->
                <div class="px-4 mb-6">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Settings</h4>
                    
                    <a href="{{ route('profile.edit') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl hover:glass transition-all duration-200 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center">
                            <i class="fas fa-user-cog text-white"></i>
                        </div>
                        <span class="text-white font-medium">Profile Settings</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/10">
                @if($teacher)
                <div class="glass rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-400">Availability Status</span>
                        <form action="{{ route('teacher.toggle-availability') }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                {{ $teacher->is_available ? 'bg-emerald-600' : 'bg-gray-600' }}">
                                <span class="sr-only">Toggle availability</span>
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                    {{ $teacher->is_available ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-gray-300">
                        {{ $teacher->is_available ? 'Available for new evaluations' : 'Not accepting new evaluations' }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col relative z-20">
            <!-- Top Bar -->
            <header class="glass-dark border-b border-white/10 z-30 flex-shrink-0 relative">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Left Section -->
                        <div class="flex items-center space-x-4">
                            <!-- Mobile Menu Button -->
                            <button @click="sidebarOpen = true" class="lg:hidden text-white hover:text-emerald-400 transition-colors">
                                <i class="fas fa-bars text-xl"></i>
                            </button>

                            <!-- Page Title -->
                            @if(isset($header))
                                {{ $header }}
                            @else
                                <h1 class="text-xl font-semibold text-white">Teacher Dashboard</h1>
                            @endif
                        </div>

                        <!-- Right Section -->
                        <div class="flex items-center space-x-3">
                            <!-- Notifications -->
                            <div class="relative" @click.outside="notificationOpen = false">
                                <button @click="notificationOpen = !notificationOpen" 
                                        class="relative w-10 h-10 rounded-lg glass flex items-center justify-center text-gray-400 hover:text-white hover:border-emerald-500/50 transition-all duration-200">
                                    <i class="fas fa-bell"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full flex items-center justify-center text-xs text-white">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>

                                <!-- Notification Dropdown -->
                                <div x-show="notificationOpen"
                                     x-cloak
                                     x-transition
                                     class="absolute right-0 mt-2 w-80 glass rounded-xl overflow-hidden">
                                    <div class="p-4 border-b border-white/10">
                                        <h3 class="text-white font-semibold">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                            <div class="p-4 hover:bg-white/5 transition-colors cursor-pointer border-b border-white/5">
                                                <p class="text-sm text-white">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        @empty
                                            <div class="p-8 text-center">
                                                <i class="fas fa-bell-slash text-4xl text-gray-600 mb-3"></i>
                                                <p class="text-gray-400">No new notifications</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Dropdown -->
                            <div class="relative" @click.outside="profileDropdown = false">
                                <button @click="profileDropdown = !profileDropdown" 
                                        class="flex items-center space-x-3 px-3 py-2 rounded-lg glass hover:border-emerald-500/50 transition-all duration-200">
                                    @if(auth()->user()->avatar_url)
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" 
                                             class="w-8 h-8 rounded-lg object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </button>

                                <!-- Profile Menu -->
                                <div x-show="profileDropdown"
                                     x-cloak
                                     x-transition
                                     class="absolute right-0 mt-2 w-56 glass rounded-xl overflow-hidden">
                                    <div class="p-4 border-b border-white/10">
                                        <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="p-2">
                                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors">
                                            <i class="fas fa-user text-gray-400 w-4"></i>
                                            <span class="text-white text-sm">Profile Settings</span>
                                        </a>
                                        <hr class="my-2 border-white/10">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-left">
                                                <i class="fas fa-sign-out-alt text-gray-400 w-4"></i>
                                                <span class="text-white text-sm">Sign Out</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden">
                <div class="min-h-full flex flex-col">
                    <!-- Page Content -->
                    <div class="flex-1">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <footer class="glass-dark border-t border-white/10 mt-12">
                        <div class="px-4 sm:px-6 lg:px-8 py-8">
                            <div class="text-center">
                                <p class="text-gray-400 text-sm">
                                    {{ $settings->copyright_text ?? '© ' . date('Y') . ' ' . $settings->site_name . '. All rights reserved.' }}
                                </p>
                            </div>
                        </div>
                    </footer>
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition
             class="fixed bottom-4 right-4 z-50">
            <div class="glass rounded-xl p-4 flex items-center space-x-3 min-w-[300px] border-emerald-500/50">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-white font-medium">Success!</p>
                    <p class="text-sm text-gray-300">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition
             class="fixed bottom-4 right-4 z-50">
            <div class="glass rounded-xl p-4 flex items-center space-x-3 min-w-[300px] border-red-500/50">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center">
                    <i class="fas fa-exclamation text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-white font-medium">Error!</p>
                    <p class="text-sm text-gray-300">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @stack('scripts')
    
    <script>
        function layoutData() {
            return {
                sidebarOpen: false,
                profileDropdown: false,
                notificationOpen: false
            }
        }
    </script>
</body>
</html>