<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> CD IELTS - Professional Test Preparation Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-lg shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-gray-900">
                        CD<span class="text-indigo-600">IELTS</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#pricing" class="text-gray-700 hover:text-indigo-600 transition-colors">Pricing</a>
                    <a href="#testimonials" class="text-gray-700 hover:text-indigo-600 transition-colors">Testimonials</a>
                    <a href="#contact" class="text-gray-700 hover:text-indigo-600 transition-colors">Contact</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 transition-colors">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-indigo-600 transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900 pt-16">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        </div>
        
        <!-- Gradient Orbs -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-4000"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <!-- Premium Badge -->
                <div class="inline-flex items-center px-6 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-indigo-500/10 to-purple-500/10 text-indigo-300 border border-indigo-500/20 backdrop-blur-sm mb-8">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Trusted by 50,000+ IELTS Test Takers Globally
                </div>

                <!-- Main Heading -->
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 tracking-tight leading-tight">
                    CD IELTS with
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">
                        AI-Powered Excellence
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Experience authentic computer-delivered IELTS practice with instant AI evaluations, 
                    personalized feedback, and proven strategies to achieve your target band score
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                    @auth
                        <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-indigo-500/25">
                            <span class="relative z-10">Access Dashboard</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-indigo-500/25">
                            <span class="relative z-10">Start Free Trial</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                        <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-medium text-indigo-300 bg-white/10 border border-white/20 rounded-xl backdrop-blur-sm transition-all duration-300 hover:bg-white/20 hover:scale-105 hover:shadow-xl">
                            <span>Sign In</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">98%</div>
                        <div class="text-sm text-gray-400">Success Rate</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">50K+</div>
                        <div class="text-sm text-gray-400">Active Users</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">1M+</div>
                        <div class="text-sm text-gray-400">Tests Completed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">4.9</div>
                        <div class="text-sm text-gray-400">User Rating</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-50 to-white"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Comprehensive Test Preparation
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Master each IELTS module with targeted practice and intelligent feedback
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Listening -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-8 z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors">Listening</h3>
                        <p class="text-gray-600 mb-4 group-hover:text-gray-100 transition-colors">
                            Practice with authentic audio materials and diverse accents
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Real exam interface
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Instant scoring
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Detailed explanations
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Reading -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-8 z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors">Reading</h3>
                        <p class="text-gray-600 mb-4 group-hover:text-gray-100 transition-colors">
                            Academic and general training passages with varied topics
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Time management tools
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Highlighting feature
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Strategy tips
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Writing -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-8 z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors">Writing</h3>
                        <p class="text-gray-600 mb-4 group-hover:text-gray-100 transition-colors">
                            Task 1 & 2 practice with AI-powered evaluation
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                AI band scoring
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Grammar analysis
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Model answers
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Speaking -->
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-8 z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors">Speaking</h3>
                        <p class="text-gray-600 mb-4 group-hover:text-gray-100 transition-colors">
                            Interactive practice with pronunciation feedback
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Voice recording
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Fluency analysis
                            </li>
                            <li class="flex items-center text-gray-600 group-hover:text-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-green-500 group-hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Vocabulary tips
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Technology Section -->
    <section class="py-24 bg-gradient-to-br from-gray-900 to-indigo-900 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath d="M0 15h15v15H0V15z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 backdrop-blur-sm mb-6">
                        AI-Powered Technology
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Advanced AI Evaluation System
                    </h2>
                    <p class="text-xl text-gray-300 mb-8">
                        Our cutting-edge AI technology provides instant, accurate band score predictions and personalized feedback for Writing and Speaking modules.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white mb-2">Instant Evaluation</h3>
                                <p class="text-gray-400">Get your band score prediction within seconds of submission</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white mb-2">Accurate Scoring</h3>
                                <p class="text-gray-400">95% accuracy compared to human examiners</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-pink-500 to-orange-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white mb-2">Smart Insights</h3>
                                <p class="text-gray-400">Detailed feedback on grammar, vocabulary, and coherence</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="relative bg-gradient-to-br from-indigo-600/20 to-purple-600/20 backdrop-blur-xl rounded-2xl p-8 border border-white/10">
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-pink-500 to-orange-500 rounded-full blur-2xl opacity-50"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full blur-2xl opacity-50"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-sm font-medium text-gray-400">AI Analysis</span>
                                <span class="text-xs px-2 py-1 bg-green-500/20 text-green-300 rounded-full">Live</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-300">Task Achievement</span>
                                        <span class="text-sm font-bold text-white">8.5</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-300">Coherence & Cohesion</span>
                                        <span class="text-sm font-bold text-white">7.5</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: 75%"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-300">Lexical Resource</span>
                                        <span class="text-sm font-bold text-white">8.0</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-pink-500 to-orange-500 h-2 rounded-full" style="width: 80%"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-300">Grammatical Range</span>
                                        <span class="text-sm font-bold text-white">7.0</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full" style="width: 70%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg text-gray-300">Overall Band Score</span>
                                    <span class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">7.5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Choose Your Path to Success
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Flexible pricing plans designed to match your preparation needs
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Free Plan -->
                <div class="relative bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Free</h3>
                        <p class="text-gray-600">Perfect for getting started</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold text-gray-900">$0</span>
                            <span class="text-gray-600 ml-2">/month</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">5 practice tests per month</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Basic score analysis</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Limited question bank</span>
                        </li>
                    </ul>
                    
                    <button class="w-full py-3 px-6 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Current Plan
                    </button>
                </div>

                <!-- Premium Plan -->
                <div class="relative bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-2xl p-8 transform scale-105">
                    <div class="absolute -top-5 left-1/2 transform -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-orange-500 to-pink-500 text-white text-sm font-semibold rounded-full">
                        MOST POPULAR
                    </div>
                    
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-white mb-2">Premium</h3>
                        <p class="text-indigo-100">For serious test takers</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold text-white">$29</span>
                            <span class="text-indigo-100 ml-2">/month</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-white">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Unlimited practice tests</span>
                        </li>
                        <li class="flex items-center text-white">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>AI Writing evaluation</span>
                        </li>
                        <li class="flex items-center text-white">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Detailed performance analytics</span>
                        </li>
                        <li class="flex items-center text-white">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Priority support</span>
                        </li>
                    </ul>
                    
                    <button class="w-full py-3 px-6 bg-white text-indigo-600 rounded-xl font-medium hover:bg-gray-100 transition-all hover:scale-105 hover:shadow-lg">
                        Upgrade Now
                    </button>
                </div>

                <!-- Pro Plan -->
                <div class="relative bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Pro</h3>
                        <p class="text-gray-600">Complete preparation suite</p>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold text-gray-900">$49</span>
                            <span class="text-gray-600 ml-2">/month</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Everything in Premium</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">AI Speaking evaluation</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">1-on-1 tutor sessions</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Personalized study plan</span>
                        </li>
                    </ul>
                    
                    <button class="w-full py-3 px-6 bg-gray-900 text-white rounded-xl font-medium hover:bg-gray-800 transition-colors">
                        Upgrade to Pro
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Success Stories from Our Students
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Join thousands who achieved their target band scores
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        "The AI feedback was incredibly detailed and helped me improve from 6.5 to 8.0 in just 2 months. The practice tests are exactly like the real exam!"
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            SR
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Sarah Rahman</p>
                            <p class="text-sm text-gray-600">Band 8.0 | Canada PR</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        "Best platform for IELTS preparation! The speaking module with AI feedback helped me overcome my fear of the test. Scored 7.5 overall!"
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold">
                            MK
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Michael Kim</p>
                            <p class="text-sm text-gray-600">Band 7.5 | UK Student Visa</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        "The personalized study plan and progress tracking kept me motivated. Achieved my target score of 8.5 for Australian PR. Highly recommended!"
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold">
                            PS
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Priya Sharma</p>
                            <p class="text-sm text-gray-600">Band 8.5 | Australia PR</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
        </div>
        
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Achieve Your Target Band Score?
            </h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                Join over 50,000 successful test takers who achieved their dreams with our platform. Start your journey today!
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-medium text-indigo-900 bg-white rounded-xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                        <span class="relative z-10">Start Free Trial</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-8 py-4 text-lg font-medium text-white border-2 border-white/30 rounded-xl backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:border-white/50">
                        Watch Demo
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-medium text-indigo-900 bg-white rounded-xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                        <span class="relative z-10">Go to Dashboard</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                @endguest
            </div>
            
            <div class="mt-12 flex items-center justify-center space-x-8 text-white/60">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Secure Payment
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    No Credit Card Required
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Cancel Anytime
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-white text-2xl font-bold mb-4">IELTS Master</h3>
                    <p class="text-gray-400 mb-6">
                        Your trusted partner in achieving IELTS success with AI-powered practice and expert guidance.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Test Sections</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">AI Evaluation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Success Stories</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Resources</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-white transition-colors">IELTS Guide</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Study Tips</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Band Calculator</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Refund Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} cdielts.org | All rights reserved.
                </p>
                
                <div class="flex items-center mt-4 md:mt-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Mastercard_2019_logo.svg" alt="Mastercard" class="h-8 mr-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" class="h-8 mr-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-8 mr-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Payoneer_logo.svg" alt="Payoneer" class="h-8">
                </div>
            </div>
        </div>
    </footer>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>