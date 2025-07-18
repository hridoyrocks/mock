<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $settings = \App\Models\WebsiteSetting::getSettings();
    @endphp

    <title>{{ $settings->site_name }} - Unlock Your Global Future</title>
    
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Modern Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(2deg); }
            66% { transform: translateY(-10px) rotate(-2deg); }
        }
        
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.5); }
            50% { box-shadow: 0 0 40px rgba(239, 68, 68, 0.8); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-slide {
            animation: slide 20s linear infinite;
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }
        
        /* Parallax Effect */
        .parallax {
            transform-style: preserve-3d;
        }
        
        .parallax-layer {
            position: absolute;
            inset: 0;
        }
        
        /* Noise Texture */
        .noise {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' /%3E%3C/filter%3E%3Crect width='100' height='100' filter='url(%23noise)' opacity='0.02' /%3E%3C/svg%3E");
        }
        
        /* Modern Button */
        .btn-modern {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }
        
        /* Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Top Banner Ad Area -->
    <div class="bg-gradient-to-r from-yellow-400 to-orange-400 text-center py-2 px-4">
        <p class="text-sm font-medium text-gray-900">
            🎯 Limited Time: Get 50% OFF on Premium Access - Your Success Journey Starts Today!
            <a href="#" class="underline ml-2 font-bold">Claim Now</a>
        </p>
    </div>

    <!-- Modern Navigation -->
    <nav class="sticky top-0 w-full bg-white/80 backdrop-blur-lg shadow-sm z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-8">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                            CD
                        </div>
                        <span class="text-xl font-bold text-gray-900">IELTS</span>
                    </a>
                    
                    <!-- Desktop Nav -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <a href="#journey" class="text-gray-600 hover:text-red-500 transition-colors font-medium">Your Journey</a>
                        <a href="#success" class="text-gray-600 hover:text-red-500 transition-colors font-medium">Success Path</a>
                        <a href="#transform" class="text-gray-600 hover:text-red-500 transition-colors font-medium">Transform</a>
                        <a href="#achieve" class="text-gray-600 hover:text-red-500 transition-colors font-medium">Achieve More</a>
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:block text-gray-700 hover:text-red-500 transition-colors font-medium">
                            My Progress
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-500 transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-500 transition-colors font-medium">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="btn-modern px-6 py-2.5 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-full font-medium hover:shadow-lg transform hover:scale-105 transition-all">
                            Start Your Journey
                        </a>
                    @endauth
                    
                    <!-- Mobile Menu -->
                    <button class="lg:hidden text-gray-700" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Modern Asymmetric Design -->
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-red-50 via-orange-50 to-pink-50"></div>
        <div class="absolute inset-0 noise opacity-20"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-gradient-to-br from-red-400 to-orange-400 rounded-full blur-3xl opacity-20 animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full blur-3xl opacity-20 animate-float" style="animation-delay: 3s;"></div>
        
        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8">
                    <!-- Badge -->
                    <div class="inline-flex items-center space-x-2 px-4 py-2 bg-red-100 rounded-full">
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                        <span class="text-red-700 font-medium text-sm">50,000+ Dreams Achieved</span>
                    </div>
                    
                    <!-- Heading -->
                    <h1 class="text-5xl lg:text-7xl font-black leading-tight">
                        Your <span class="gradient-text">Band 8+</span><br/>
                        Journey Starts<br/>
                        <span class="relative">
                            Today
                            <svg class="absolute -bottom-2 left-0 w-full" height="8" viewBox="0 0 200 8">
                                <path d="M0 4 Q50 0 100 4 T200 4" stroke="url(#gradient)" stroke-width="3" fill="none"/>
                                <defs>
                                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#ef4444"/>
                                        <stop offset="100%" stop-color="#f97316"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </span>
                    </h1>
                    
                    <!-- Description -->
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Transform your IELTS preparation with AI-powered insights. Join thousands who've unlocked their potential and achieved their dream scores.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4">
                        @guest
                            <a href="{{ route('register') }}" class="group relative px-8 py-4 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all overflow-hidden">
                                <span class="relative z-10">Unlock Your Potential</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </a>
                            <a href="#" class="px-8 py-4 bg-white text-gray-900 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                See Success Stories
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="group relative px-8 py-4 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                                Continue Your Journey
                            </a>
                        @endguest
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="flex items-center space-x-6 pt-4">
                        <div class="flex -space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img={{ $i }}" alt="User">
                            @endfor
                        </div>
                        <div>
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-600">Rated 4.9/5 by learners</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Visual -->
                <div class="relative lg:block hidden">
                    <div class="relative w-full h-[600px]">
                        <!-- Mockup Cards -->
                        <div class="absolute top-0 right-0 w-72 bg-white rounded-2xl shadow-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform animate-float">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-medium text-gray-500">Your Progress</span>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Live</span>
                            </div>
                            <div class="text-4xl font-bold text-gray-900 mb-2">Band 7.5</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-500 to-orange-500 rounded-full" style="width: 75%"></div>
                                </div>
                                <span class="text-sm text-gray-600">75%</span>
                            </div>
                        </div>
                        
                        <div class="absolute bottom-20 left-0 w-64 bg-white rounded-2xl shadow-2xl p-6 transform -rotate-3 hover:rotate-0 transition-transform animate-float" style="animation-delay: 1s;">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="font-semibold text-gray-900">Achievement Unlocked!</span>
                            </div>
                            <p class="text-sm text-gray-600">Completed 50 Practice Tests</p>
                        </div>
                        
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-gradient-to-br from-red-500 to-orange-500 rounded-3xl opacity-20 blur-2xl animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <div class="w-6 h-10 border-2 border-gray-400 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-gray-400 rounded-full mt-2 animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Motivation Banner -->
    <section class="bg-gradient-to-r from-gray-900 to-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-center space-x-8 text-white">
                <div class="hidden md:block">
                    <i class="fas fa-quote-left text-3xl opacity-50"></i>
                </div>
                <p class="text-lg md:text-xl font-medium text-center italic">
                    "Success is not final, failure is not fatal: it is the courage to continue that counts."
                </p>
                <div class="hidden md:block">
                    <i class="fas fa-quote-right text-3xl opacity-50"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Journey Section - Bento Grid Style -->
    <section id="journey" class="py-24 bg-white relative overflow-hidden reveal">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-red-100 to-orange-100 rounded-full blur-3xl opacity-50"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-red-500 font-semibold text-sm uppercase tracking-wider">Your Journey</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mt-2">
                    Every Champion Was Once a <span class="gradient-text">Beginner</span>
                </h2>
            </div>
            
            <!-- Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Large Card -->
                <div class="lg:col-span-2 lg:row-span-2 group relative bg-gradient-to-br from-red-500 to-orange-500 rounded-3xl p-8 text-white overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-rocket text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold mb-4">Begin Your Transformation</h3>
                        <p class="text-lg opacity-90 mb-6">
                            Start with a personalized assessment that identifies your strengths and opportunities for growth.
                        </p>
                        <a href="#" class="inline-flex items-center space-x-2 text-white font-semibold hover:underline">
                            <span>Take Free Assessment</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Medium Cards -->
                <div class="group relative bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl p-6 hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-brain text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">AI-Powered Learning</h4>
                    <p class="text-gray-600">Adaptive technology that learns your patterns</p>
                </div>
                
                <div class="group relative bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-6 hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Track Progress</h4>
                    <p class="text-gray-600">Visual insights into your improvement journey</p>
                </div>
                
                <!-- Wide Card -->
                <div class="lg:col-span-2 group relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-3xl p-6 hover:shadow-xl transition-all">
                    <div class="flex items-center space-x-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-trophy text-white text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900 mb-1">Celebrate Every Victory</h4>
                            <p class="text-gray-600">From your first practice test to your final exam - we're with you</p>
                        </div>
                    </div>
                </div>
                
                <!-- Small Cards -->
                <div class="group relative bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl p-6 hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Community Support</h4>
                    <p class="text-gray-600">Learn with 50,000+ motivated peers</p>
                </div>
                
                <div class="group relative bg-gradient-to-br from-rose-50 to-pink-50 rounded-3xl p-6 hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-certificate text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Certified Success</h4>
                    <p class="text-gray-600">Proven methods, guaranteed results</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Path Section -->
    <section id="success" class="py-24 bg-gray-50 relative overflow-hidden reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-red-500 font-semibold text-sm uppercase tracking-wider">Success Path</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mt-2">
                    Your Roadmap to <span class="gradient-text">Excellence</span>
                </h2>
            </div>
            
            <!-- Timeline -->
            <div class="relative">
                <!-- Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-red-500 to-orange-500 hidden lg:block"></div>
                
                <!-- Steps -->
                <div class="space-y-12">
                    <!-- Step 1 -->
                    <div class="flex flex-col lg:flex-row items-center gap-8">
                        <div class="flex-1 text-right">
                            <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Week 1-2: Foundation</h3>
                                <p class="text-gray-600">Master the basics with our comprehensive introduction modules</p>
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl z-10 animate-glow">
                            1
                        </div>
                        <div class="flex-1"></div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col lg:flex-row items-center gap-8">
                        <div class="flex-1"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-500 rounded-full flex items-center justify-center text-white font-bold text-xl z-10 animate-glow">
                            2
                        </div>
                        <div class="flex-1 text-left">
                            <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Week 3-6: Momentum</h3>
                                <p class="text-gray-600">Build confidence with targeted practice and real-time feedback</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col lg:flex-row items-center gap-8">
                        <div class="flex-1 text-right">
                            <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Week 7-10: Mastery</h3>
                                <p class="text-gray-600">Perfect your skills with advanced strategies and mock tests</p>
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-xl z-10 animate-glow">
                            3
                        </div>
                        <div class="flex-1"></div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="flex flex-col lg:flex-row items-center gap-8">
                        <div class="flex-1"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xl z-10 animate-glow">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-1 text-left">
                            <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl shadow-xl p-6 text-white">
                                <h3 class="text-2xl font-bold mb-2">Success Achieved!</h3>
                                <p class="opacity-90">Celebrate your Band 8+ achievement and unlock global opportunities</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Transform Section -->
    <section id="transform" class="py-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white relative overflow-hidden reveal">
        <div class="absolute inset-0 noise opacity-10"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-red-400 font-semibold text-sm uppercase tracking-wider">Transform Your Future</span>
                    <h2 class="text-4xl md:text-5xl font-black mt-2 mb-6">
                        More Than Just a Test.<br/>
                        It's Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-400">Gateway to Dreams</span>
                    </h2>
                    <p class="text-lg text-gray-300 mb-8">
                        Every module, every practice session, every feedback loop is designed to unlock your potential. 
                        We don't just prepare you for IELTS - we prepare you for success.
                    </p>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">98%</div>
                            <p class="text-gray-400">Success Rate</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">2M+</div>
                            <p class="text-gray-400">Tests Completed</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">50K+</div>
                            <p class="text-gray-400">Happy Achievers</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">24/7</div>
                            <p class="text-gray-400">AI Support</p>
                        </div>
                    </div>
                </div>
                
                <!-- Visual -->
                <div class="relative">
                    <div class="relative h-[500px] rounded-3xl overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500/20 to-orange-500/20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center space-y-4">
                                <div class="w-32 h-32 mx-auto bg-white/10 backdrop-blur-lg rounded-full flex items-center justify-center animate-pulse">
                                    <i class="fas fa-play text-4xl text-white"></i>
                                </div>
                                <p class="text-white font-semibold">Watch Success Stories</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achieve More Section -->
    <section id="achieve" class="py-24 bg-white relative overflow-hidden reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-red-500 font-semibold text-sm uppercase tracking-wider">Achieve More</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mt-2">
                    Tools That <span class="gradient-text">Empower</span> Your Success
                </h2>
            </div>
            
            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-orange-500 rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-microphone-alt text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Speaking Excellence</h3>
                        <p class="text-gray-600 mb-4">
                            Real-time pronunciation analysis and fluency tracking to perfect your speaking skills
                        </p>
                        <a href="#" class="inline-flex items-center text-red-500 font-semibold hover:text-red-600">
                            <span>Explore Feature</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-amber-500 rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-500 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-pen-fancy text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Writing Mastery</h3>
                        <p class="text-gray-600 mb-4">
                            AI-powered grammar checking and vocabulary enhancement for impressive essays
                        </p>
                        <a href="#" class="inline-flex items-center text-orange-500 font-semibold hover:text-orange-600">
                            <span>Explore Feature</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-500 to-pink-500 rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all">
                        <div class="w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-book-reader text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Reading Speed</h3>
                        <p class="text-gray-600 mb-4">
                            Advanced techniques and time management strategies for efficient comprehension
                        </p>
                        <a href="#" class="inline-flex items-center text-rose-500 font-semibold hover:text-rose-600">
                            <span>Explore Feature</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Banner Ad Section -->
    <section class="py-12 bg-gradient-to-r from-gray-100 to-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <div class="p-12">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">
                            Ready to Begin Your Success Story?
                        </h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Join thousands who've transformed their dreams into reality. Your journey to Band 8+ starts with a single click.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition-all">
                                Start Free Today
                            </a>
                            <a href="#" class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all">
                                View Success Stories
                            </a>
                        </div>
                    </div>
                    <div class="relative bg-gradient-to-br from-red-500 to-orange-500 p-12 flex items-center justify-center">
                        <div class="text-center text-white">
                            <div class="text-6xl font-black mb-2">50% OFF</div>
                            <p class="text-xl">Limited Time Offer</p>
                            <p class="text-sm opacity-80 mt-2">Use code: SUCCESS2024</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-gradient-to-br from-red-600 via-orange-600 to-amber-600 relative overflow-hidden">
        <div class="absolute inset-0 noise opacity-10"></div>
        
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-float" style="animation-delay: 3s;"></div>
        </div>
        
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4">
            <h2 class="text-5xl md:text-6xl font-black text-white mb-6">
                Your Future is Calling
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Every minute you wait is a minute away from your dreams. Take the first step towards your Band 8+ score today.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                @guest
                    <a href="{{ route('register') }}" class="group relative px-10 py-5 bg-white text-red-600 rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                        <span class="relative z-10">Begin Your Journey Now</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="group relative px-10 py-5 bg-white text-red-600 rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                        <span class="relative z-10">Continue to Dashboard</span>
                    </a>
                @endguest
            </div>
            
            <p class="text-white/80 text-sm">
                <i class="fas fa-shield-alt mr-2"></i>
                30-day money-back guarantee • No credit card required
            </p>
        </div>
    </section>

    <!-- Modern Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                            CD
                        </div>
                        <span class="text-xl font-bold text-white">IELTS</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        Empowering dreams through intelligent test preparation. Your success is our mission.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-red-500 transition-colors">Success Stories</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Study Resources</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Mock Tests</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Community</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-red-500 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} CD IELTS. Empowering dreams, one test at a time.
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-black/50 z-50 hidden">
        <div class="fixed right-0 top-0 h-full w-80 bg-white shadow-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-bold">Menu</h3>
                    <button onclick="toggleMobileMenu()" class="text-gray-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="space-y-4">
                    <a href="#journey" class="block py-2 text-gray-700 hover:text-red-500 font-medium">Your Journey</a>
                    <a href="#success" class="block py-2 text-gray-700 hover:text-red-500 font-medium">Success Path</a>
                    <a href="#transform" class="block py-2 text-gray-700 hover:text-red-500 font-medium">Transform</a>
                    <a href="#achieve" class="block py-2 text-gray-700 hover:text-red-500 font-medium">Achieve More</a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Mobile Menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Reveal Animation
        const revealElements = document.querySelectorAll('.reveal');
        
        function reveal() {
            revealElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', reveal);
        reveal();
    </script>
</body>
</html>