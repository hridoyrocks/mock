{{-- resources/views/components/student-layout.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'IELTS Journey' }} - CD IELTS Master</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
        
        /* Neon Glow Effects */
        .neon-purple {
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.5),
                        0 0 40px rgba(168, 85, 247, 0.3),
                        0 0 60px rgba(168, 85, 247, 0.1);
        }
        
        .neon-blue {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5),
                        0 0 40px rgba(59, 130, 246, 0.3),
                        0 0 60px rgba(59, 130, 246, 0.1);
        }
        
        .neon-pink {
            box-shadow: 0 0 20px rgba(236, 72, 153, 0.5),
                        0 0 40px rgba(236, 72, 153, 0.3),
                        0 0 60px rgba(236, 72, 153, 0.1);
        }
        
        /* Gradient Animations */
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .animated-gradient {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }
        
        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
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
            background: linear-gradient(to bottom, #a855f7, #3b82f6);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #9333ea, #2563eb);
        }
        
        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
        }
        
        /* Pulse Animation */
        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(168, 85, 247, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(168, 85, 247, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(168, 85, 247, 0);
            }
        }
        
        .pulse-animation {
            animation: pulse-glow 2s infinite;
        }
        
        /* Particle Background */
        .particle {
            position: fixed;
            pointer-events: none;
            opacity: 0.5;
            animation: float-particle linear infinite;
        }
        
        @keyframes float-particle {
            from {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.5;
            }
            to {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased">
    <!-- Particle Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="particle w-1 h-1 bg-purple-500 rounded-full" style="left: 10%; animation-duration: 15s; animation-delay: 0s;"></div>
        <div class="particle w-1 h-1 bg-blue-500 rounded-full" style="left: 20%; animation-duration: 20s; animation-delay: 2s;"></div>
        <div class="particle w-1 h-1 bg-pink-500 rounded-full" style="left: 30%; animation-duration: 18s; animation-delay: 4s;"></div>
        <div class="particle w-1 h-1 bg-green-500 rounded-full" style="left: 40%; animation-duration: 22s; animation-delay: 6s;"></div>
        <div class="particle w-1 h-1 bg-yellow-500 rounded-full" style="left: 50%; animation-duration: 17s; animation-delay: 8s;"></div>
        <div class="particle w-1 h-1 bg-purple-500 rounded-full" style="left: 60%; animation-duration: 25s; animation-delay: 10s;"></div>
        <div class="particle w-1 h-1 bg-blue-500 rounded-full" style="left: 70%; animation-duration: 19s; animation-delay: 12s;"></div>
        <div class="particle w-1 h-1 bg-pink-500 rounded-full" style="left: 80%; animation-duration: 21s; animation-delay: 14s;"></div>
        <div class="particle w-1 h-1 bg-green-500 rounded-full" style="left: 90%; animation-duration: 16s; animation-delay: 16s;"></div>
    </div>

    <div x-data="{ 
        sidebarOpen: false, 
        profileDropdown: false,
        notificationOpen: false,
        searchOpen: false,
        currentTime: new Date().toLocaleTimeString(),
        greeting: getGreeting()
    }" 
    x-init="
        setInterval(() => currentTime = new Date().toLocaleTimeString(), 1000);
        function getGreeting() {
            const hour = new Date().getHours();
            if (hour < 12) return 'Good Morning';
            if (hour < 17) return 'Good Afternoon';
            return 'Good Evening';
        }
    "
    class="flex min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        
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

        <!-- Modern Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
             class="fixed inset-y-0 left-0 z-50 w-72 h-screen glass-dark transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:relative lg:inset-auto flex flex-col">
            
            <!-- Logo Section -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center neon-purple">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">CD IELTS</h2>
                            <p class="text-xs text-gray-400">Master Your Journey</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- User Quick Stats -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center text-white font-bold text-xl">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-slate-900"></div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-white font-semibold">{{ auth()->user()->name }}</h3>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="text-xs px-2 py-1 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                                {{ ucfirst(auth()->user()->subscription_status) }}
                            </span>
                            <span class="text-xs text-gray-400">
                                <i class="fas fa-fire text-orange-500"></i> {{ auth()->user()->study_streak_days ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Mini Progress Bar -->
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Daily Goal</span>
                        <span class="text-white">75%</span>
                    </div>
                    <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto py-4">
                <!-- Journey Section -->
                <div class="px-4 mb-6">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Your Journey</h4>
                    
                    <a href="{{ route('student.dashboard') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('student.dashboard') ? 'glass bg-gradient-to-r from-purple-600/20 to-pink-600/20 border-purple-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center
                                    {{ request()->routeIs('student.dashboard') ? 'neon-purple' : '' }}">
                            <i class="fas fa-compass text-white"></i>
                        </div>
                        <span class="text-white font-medium">Dashboard</span>
                        @if(request()->routeIs('student.dashboard'))
                            <i class="fas fa-chevron-right text-purple-400 ml-auto"></i>
                        @endif
                    </a>

                    <a href="{{ route('student.results') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('student.results*') ? 'glass bg-gradient-to-r from-blue-600/20 to-cyan-600/20 border-blue-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center
                                    {{ request()->routeIs('student.results*') ? 'neon-blue' : '' }}">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <span class="text-white font-medium">My Progress</span>
                        @if(request()->routeIs('student.results*'))
                            <i class="fas fa-chevron-right text-blue-400 ml-auto"></i>
                        @endif
                    </a>
                </div>

                <!-- Practice Tests -->
                <div class="px-4 mb-6">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Practice Arena</h4>
                    
                    <a href="{{ route('student.listening.index') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('student.listening.*') ? 'glass bg-gradient-to-r from-violet-600/20 to-purple-600/20 border-violet-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center">
                            <i class="fas fa-headphones text-white"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-white font-medium block">Listening</span>
                            <span class="text-xs text-gray-400">4 parts • 30 min</span>
                        </div>
                    </a>

                    <a href="{{ route('student.reading.index') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('student.reading.*') ? 'glass bg-gradient-to-r from-emerald-600/20 to-green-600/20 border-emerald-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center">
                            <i class="fas fa-book-open text-white"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-white font-medium block">Reading</span>
                            <span class="text-xs text-gray-400">3 passages • 60 min</span>
                        </div>
                    </a>

                    <a href="{{ route('student.writing.index') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('student.writing.*') ? 'glass bg-gradient-to-r from-amber-600/20 to-orange-600/20 border-amber-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center">
                            <i class="fas fa-pen-fancy text-white"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-white font-medium block">Writing</span>
                            <span class="text-xs text-gray-400">2 tasks • 60 min</span>
                        </div>
                    </a>

                    <a href="{{ route('student.speaking.index') }}" 
                       class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 mb-2
                              {{ request()->routeIs('student.speaking.*') ? 'glass bg-gradient-to-r from-rose-600/20 to-pink-600/20 border-rose-500/50' : 'hover:glass' }}">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center">
                            <i class="fas fa-microphone text-white"></i>
                        </div>
                        <div class="flex-1">
                            <span class="text-white font-medium block">Speaking</span>
                            <span class="text-xs text-gray-400">3 parts • 15 min</span>
                        </div>
                    </a>
                </div>

                <!-- Resources -->
                <div class="px-4 mb-6">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Resources</h4>
                    
                    <a href="#" class="group flex items-center space-x-3 px-4 py-3 rounded-xl hover:glass transition-all duration-200 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-book-reader text-white"></i>
                        </div>
                        <span class="text-white font-medium">Study Hub</span>
                    </a>

                    <a href="#" class="group flex items-center space-x-3 px-4 py-3 rounded-xl hover:glass transition-all duration-200 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <span class="text-white font-medium">Community</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/10">
                <div class="glass rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-400">Monthly Tests</span>
                        <span class="text-xs font-bold text-white">
                            {{ auth()->user()->tests_taken_this_month }} / 
                            {{ auth()->user()->getFeatureLimit('mock_tests_per_month') === 'unlimited' ? '∞' : auth()->user()->getFeatureLimit('mock_tests_per_month') }}
                        </span>
                    </div>
                    <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                        @php
                            $limit = auth()->user()->getFeatureLimit('mock_tests_per_month');
                            $percentage = $limit === 'unlimited' ? 0 : (auth()->user()->tests_taken_this_month / $limit) * 100;
                        @endphp
                        <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-500" 
                             style="width: {{ min($percentage, 100) }}%"></div>
                    </div>
                </div>
                
                @if(auth()->user()->subscription_status === 'free')
                    <a href="{{ route('subscription.plans') }}" 
                       class="block w-full text-center py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium hover:from-purple-700 hover:to-pink-700 transition-all duration-200 neon-purple">
                        <i class="fas fa-rocket mr-2"></i>Upgrade to Pro
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Modern Top Bar -->
            <header class="glass-dark border-b border-white/10 z-30">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Left Section -->
                        <div class="flex items-center space-x-4">
                            <!-- Mobile Menu Button -->
                            <button @click="sidebarOpen = true" class="lg:hidden text-white hover:text-purple-400 transition-colors">
                                <i class="fas fa-bars text-xl"></i>
                            </button>

                            <!-- Greeting & Time -->
                            <div class="hidden sm:block">
                                <h1 class="text-xl font-semibold text-white">
                                    Good {{ now()->format('A') === 'AM' ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                                </h1>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ now()->format('h:i A') }} • {{ now()->format('l, F j') }}
                                </p>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="flex items-center space-x-3">
                            <!-- Search Button -->
                            <button @click="searchOpen = true" 
                                    class="w-10 h-10 rounded-lg glass flex items-center justify-center text-gray-400 hover:text-white hover:border-purple-500/50 transition-all duration-200">
                                <i class="fas fa-search"></i>
                            </button>

                            <!-- Notifications -->
                            <div class="relative" @click.outside="notificationOpen = false">
                                <button @click="notificationOpen = !notificationOpen" 
                                        class="relative w-10 h-10 rounded-lg glass flex items-center justify-center text-gray-400 hover:text-white hover:border-purple-500/50 transition-all duration-200">
                                    <i class="fas fa-bell"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full flex items-center justify-center text-xs text-white pulse-animation">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>

                                <!-- Notification Dropdown -->
                                <div x-show="notificationOpen"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
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
                                        class="flex items-center space-x-3 px-3 py-2 rounded-lg glass hover:border-purple-500/50 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </button>

                                <!-- Profile Menu -->
                                <div x-show="profileDropdown"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
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
                                        <a href="{{ route('subscription.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors">
                                            <i class="fas fa-crown text-gray-400 w-4"></i>
                                            <span class="text-white text-sm">Subscription</span>
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
            <main class="flex-1 overflow-y-auto">
                <div class="min-h-screen">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Search Modal -->
        <div x-show="searchOpen" 
             x-cloak
             @keydown.escape.window="searchOpen = false"
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-start justify-center min-h-screen pt-20 px-4">
                <div @click="searchOpen = false" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
                
                <div x-show="searchOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative glass rounded-2xl w-full max-w-2xl">
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-search text-purple-400 text-xl"></i>
                            <input type="text" 
                                   placeholder="Search tests, topics, or resources..." 
                                   class="flex-1 bg-transparent border-none outline-none text-white placeholder-gray-400 text-lg"
                                   autofocus>
                            <button @click="searchOpen = false" class="text-gray-400 hover:text-white">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="border-t border-white/10 p-4">
                        <p class="text-xs text-gray-400 mb-3">Quick Links</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('student.listening.index') }}" class="p-3 rounded-lg hover:bg-white/10 transition-colors">
                                <i class="fas fa-headphones text-purple-400 mr-2"></i>
                                <span class="text-white text-sm">Listening Tests</span>
                            </a>
                            <a href="{{ route('student.reading.index') }}" class="p-3 rounded-lg hover:bg-white/10 transition-colors">
                                <i class="fas fa-book-open text-blue-400 mr-2"></i>
                                <span class="text-white text-sm">Reading Tests</span>
                            </a>
                            <a href="{{ route('student.writing.index') }}" class="p-3 rounded-lg hover:bg-white/10 transition-colors">
                                <i class="fas fa-pen-fancy text-green-400 mr-2"></i>
                                <span class="text-white text-sm">Writing Tests</span>
                            </a>
                            <a href="{{ route('student.speaking.index') }}" class="p-3 rounded-lg hover:bg-white/10 transition-colors">
                                <i class="fas fa-microphone text-pink-400 mr-2"></i>
                                <span class="text-white text-sm">Speaking Tests</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-full"
             class="fixed bottom-4 right-4 z-50">
            <div class="glass rounded-xl p-4 flex items-center space-x-3 min-w-[300px] border-green-500/50">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
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
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-full"
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
</body>
</html>