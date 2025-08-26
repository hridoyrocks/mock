<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $settings = \App\Models\WebsiteSetting::first();
        $siteName = $settings ? $settings->site_title : 'CD IELTS';
        $favicon = $settings && $settings->favicon_path ? Storage::url($settings->favicon_path) : null;
    @endphp

    <title>{{ $siteName }} - Master IELTS with AI-Powered Practice Tests</title>
    
    <!-- Favicon -->
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Professional Color Palette */
        :root {
            --primary-red: #EF4444;
            --primary-dark: #DC2626;
            --primary-light: #FCA5A5;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --bg-light: #F9FAFB;
            --white: #FFFFFF;
            --success: #10B981;
            --warning: #F59E0B;
            --info: #3B82F6;
        }
        
        /* Glass Morphism Effects */
        .glass {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }
        
        .glass-red {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.9) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(239, 68, 68, 0.2);
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(239, 68, 68, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #EF4444;
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            border: 2px solid #EF4444;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }
        
        .btn-secondary:hover {
            background: #FEF2F2;
            transform: translateY(-2px);
        }
        
        /* Card Hover Effects */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }
        
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        
        /* Section Spacing */
        .section-padding {
            padding: 80px 0;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #F3F4F6;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #EF4444, #DC2626);
            border-radius: 4px;
        }
        
        /* Typography */
        h1 { 
            font-size: 3.5rem; 
            font-weight: 800; 
            line-height: 1.2;
            color: var(--text-dark);
        }
        
        h2 { 
            font-size: 2.5rem; 
            font-weight: 700; 
            color: var(--text-dark);
        }
        
        h3 { 
            font-size: 1.5rem; 
            font-weight: 600; 
            color: var(--text-dark);
        }
        
        p {
            color: var(--text-light);
            line-height: 1.8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            h1 { font-size: 2.5rem; }
            h2 { font-size: 2rem; }
            .section-padding { padding: 60px 0; }
        }
    </style>
</head>
<body class="bg-white">

<!-- Navigation Header -->
<header class="fixed w-full top-0 z-50 glass bg-white/95">
    <nav class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                @php
                    $logo = $settings && $settings->logo_path ? Storage::url($settings->logo_path) : null;
                @endphp
                
                @if($logo)
                    <img src="{{ $logo }}" alt="{{ $siteName }}" class="h-10 w-auto">
                @else
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">CD</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">CD IELTS</span>
                    </div>
                @endif
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-700 hover:text-red-500 font-medium transition">Features</a>
                <a href="#how-it-works" class="text-gray-700 hover:text-red-500 font-medium transition">How It Works</a>
                <a href="#pricing" class="text-gray-700 hover:text-red-500 font-medium transition">Pricing</a>
                <a href="#reviews" class="text-gray-700 hover:text-red-500 font-medium transition">Reviews</a>
                <a href="#faq" class="text-gray-700 hover:text-red-500 font-medium transition">FAQ</a>
            </div>
            
            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-red-500 font-medium">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary text-center">
                        Start Free Trial
                    </a>
                @endauth
            </div>
            
            <!-- Mobile Menu -->
            <button class="md:hidden text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="animate-fade-in-up">
                <!-- Trust Badge -->
                <div class="inline-flex items-center bg-red-50 rounded-full px-4 py-2 mb-8">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm font-medium text-gray-700">Trusted by 50,000+ Students Worldwide</span>
                </div>
                
                <!-- Main Heading -->
                <h1 class="mb-6">
                    Your Journey to
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-600"> Band 8+</span><br>
                    Starts Here
                </h1>
                
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Join thousands of successful students who achieved their dream scores. 
                    Practice with confidence, learn from experts, and unlock opportunities worldwide.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-10">
                    <button onclick="openDemoModal()" class="btn-primary inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Try Free Demo Test</span>
                    </button>
                    <a href="{{ route('register') }}" class="btn-secondary inline-flex items-center justify-center">
                        <span>Get Started - It's Free</span>
                    </a>
                </div>
                
                <!-- Social Proof -->
                <div class="flex items-center space-x-6">
                    <div class="flex -space-x-3">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Student" class="w-12 h-12 rounded-full border-3 border-white shadow-lg">
                        <img src="https://randomuser.me/api/portraits/men/44.jpg" alt="Student" class="w-12 h-12 rounded-full border-3 border-white shadow-lg">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Student" class="w-12 h-12 rounded-full border-3 border-white shadow-lg">
                        <img src="https://randomuser.me/api/portraits/men/29.jpg" alt="Student" class="w-12 h-12 rounded-full border-3 border-white shadow-lg">
                        <div class="w-12 h-12 rounded-full border-3 border-white bg-gray-900 flex items-center justify-center text-white text-sm font-bold shadow-lg">
                            +2k
                        </div>
                    </div>
                    <div>
                        <div class="flex text-yellow-400 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm font-medium text-gray-600">4.9/5 from 2000+ reviews</p>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Test Interface Preview -->
            <div class="relative animate-float">
                <div class="bg-white rounded-3xl shadow-2xl p-8">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">IELTS MOCK TEST</span>
                            <span class="flex items-center text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                Live
                            </span>
                        </div>
                    </div>
                    
                    <!-- Test Modules Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <!-- Listening -->
                        <div class="bg-blue-50 p-6 rounded-2xl text-center card-hover cursor-pointer">
                            <div class="w-16 h-16 mx-auto mb-3 bg-blue-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Listening</h4>
                            <p class="text-xs text-gray-500">40 questions</p>
                        </div>
                        
                        <!-- Reading -->
                        <div class="bg-green-50 p-6 rounded-2xl text-center card-hover cursor-pointer">
                            <div class="w-16 h-16 mx-auto mb-3 bg-green-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Reading</h4>
                            <p class="text-xs text-gray-500">40 questions</p>
                        </div>
                        
                        <!-- Writing -->
                        <div class="bg-purple-50 p-6 rounded-2xl text-center card-hover cursor-pointer">
                            <div class="w-16 h-16 mx-auto mb-3 bg-purple-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Writing</h4>
                            <p class="text-xs text-gray-500">2 tasks</p>
                        </div>
                        
                        <!-- Speaking -->
                        <div class="bg-orange-50 p-6 rounded-2xl text-center card-hover cursor-pointer">
                            <div class="w-16 h-16 mx-auto mb-3 bg-orange-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Speaking</h4>
                            <p class="text-xs text-gray-500">3 parts</p>
                        </div>
                    </div>
                    
                    <!-- AI Score Preview -->
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-2xl">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-semibold text-gray-700">AI Band Score</span>
                            <span class="text-3xl font-bold text-red-500">7.5</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-red-500 to-red-600 h-3 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Badges -->
                <div class="absolute -top-4 -right-4 bg-red-500 text-white px-4 py-2 rounded-xl font-bold shadow-xl">
                    Band 8+ Guaranteed
                </div>
                <div class="absolute -bottom-4 -left-4 bg-green-500 text-white px-4 py-2 rounded-xl font-bold shadow-xl">
                    Expert Verified
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gradient-to-r from-red-500 to-red-600">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <div class="text-4xl font-bold text-white mb-2">50K+</div>
                    <div class="text-white/80 font-medium">Active Students</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <div class="text-4xl font-bold text-white mb-2">1M+</div>
                    <div class="text-white/80 font-medium">Tests Completed</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <div class="text-4xl font-bold text-white mb-2">95%</div>
                    <div class="text-white/80 font-medium">Success Rate</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <div class="text-4xl font-bold text-white mb-2">24/7</div>
                    <div class="text-white/80 font-medium">AI Support</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Motivational Success Section -->
<section class="section-padding bg-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-white opacity-50"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-red-500 font-bold text-sm uppercase tracking-wider">YOUR SUCCESS STORY</span>
                <h2 class="mt-4 mb-6">From Dreams to Reality</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Every year, thousands of our students achieve their target scores and unlock life-changing opportunities:
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Study Abroad Dreams</h3>
                            <p class="text-gray-600">85% of our students successfully enrolled in their dream universities worldwide</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Career Advancement</h3>
                            <p class="text-gray-600">92% landed better jobs with higher salaries after achieving their target scores</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Immigration Success</h3>
                            <p class="text-gray-600">78% achieved their immigration goals with our comprehensive preparation</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('register') }}" class="btn-primary inline-flex items-center justify-center gap-2">
                        <span>Start Your Success Story</span>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-gradient-to-br from-red-100 to-pink-50 rounded-3xl p-8">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Success Rate by Goal</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-gray-700">Band 6.5 Target</span>
                                <span class="font-bold text-green-600">98%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-gray-700">Band 7.0 Target</span>
                                <span class="font-bold text-green-600">95%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-gray-700">Band 7.5 Target</span>
                                <span class="font-bold text-blue-600">89%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full" style="width: 89%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="font-semibold text-gray-700">Band 8.0+ Target</span>
                                <span class="font-bold text-purple-600">82%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-purple-500 h-3 rounded-full" style="width: 82%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 bg-white rounded-xl">
                        <p class="text-center text-sm text-gray-600">
                            <span class="text-3xl font-bold text-red-500 block mb-2">43,827</span>
                            Students achieved their target scores last year
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section-padding bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-red-500 font-bold text-sm uppercase tracking-wider">FEATURES</span>
            <h2 class="mt-4 mb-6">Everything You Need to Succeed</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Our comprehensive platform provides all the tools and resources for IELTS excellence
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature Cards -->
            @php
                $features = [
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Instant AI Evaluation', 'desc' => 'Get detailed feedback and band scores within seconds using advanced AI technology', 'color' => 'red'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Progress Analytics', 'desc' => 'Track your improvement with detailed performance metrics and insights', 'color' => 'blue'],
                    ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Real Exam Questions', 'desc' => 'Practice with authentic IELTS questions from recent exams', 'color' => 'green'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Expert Teachers', 'desc' => 'Get human evaluation from certified IELTS instructors when you need it', 'color' => 'purple'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Flexible Schedule', 'desc' => 'Practice anytime, anywhere with 24/7 access to all tests and materials', 'color' => 'orange'],
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Score Guarantee', 'desc' => 'We guarantee score improvement or your money back', 'color' => 'green'],
                ];
            @endphp
            
            @foreach($features as $feature)
                <div class="bg-white rounded-2xl p-8 card-hover">
                    <div class="w-14 h-14 bg-{{ $feature['color'] }}-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-{{ $feature['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="mb-3">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 mb-4">{{ $feature['desc'] }}</p>
                    <a href="#" class="text-red-500 font-semibold hover:text-red-600 inline-flex items-center gap-1">
                        <span>Learn more</span>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Detailed Testimonials Section -->
<section id="testimonials" class="section-padding bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-red-500 font-bold text-sm uppercase tracking-wider">SUCCESS STORIES</span>
            <h2 class="mt-4 mb-6">Real Students, Real Results</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Don't just take our word for it - hear from students who transformed their lives with CD IELTS
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <!-- Testimonial 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-xl relative">
                <div class="absolute -top-4 left-8 bg-red-500 text-white px-4 py-1 rounded-full text-sm font-bold">
                    Band 8.5 Achieved
                </div>
                
                <div class="flex mb-4 mt-4">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                
                <p class="text-gray-700 mb-6 italic">
                    "CD IELTS completely changed my preparation strategy. The practice tests were exactly like the real exam, 
                    and the feedback helped me improve from Band 6.5 to 8.5 in just 8 weeks! Now I'm studying at Oxford University."
                </p>
                
                <div class="flex items-center">
                    <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Sarah Johnson" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <div class="font-bold text-gray-900">Sarah Johnson</div>
                        <div class="text-sm text-gray-600">Oxford University, UK</div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-xl relative">
                <div class="absolute -top-4 left-8 bg-green-500 text-white px-4 py-1 rounded-full text-sm font-bold">
                    From 5.5 to 7.5
                </div>
                
                <div class="flex mb-4 mt-4">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                
                <p class="text-gray-700 mb-6 italic">
                    "I struggled with IELTS for years. CD IELTS's structured approach and expert guidance helped me jump 
                    from 5.5 to 7.5! The speaking practice with instant feedback was a game-changer. Got my Canadian PR!"
                </p>
                
                <div class="flex items-center">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Raj Kumar" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <div class="font-bold text-gray-900">Raj Kumar</div>
                        <div class="text-sm text-gray-600">Software Engineer, Canada</div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-xl relative">
                <div class="absolute -top-4 left-8 bg-purple-500 text-white px-4 py-1 rounded-full text-sm font-bold">
                    Dream Job Achieved
                </div>
                
                <div class="flex mb-4 mt-4">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                
                <p class="text-gray-700 mb-6 italic">
                    "Thanks to CD IELTS, I achieved Band 8.0 and landed my dream job at Google! The writing templates 
                    and vocabulary resources were exceptional. Worth every penny!"
                </p>
                
                <div class="flex items-center">
                    <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Maria Zhang" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <div class="font-bold text-gray-900">Maria Zhang</div>
                        <div class="text-sm text-gray-600">Product Manager, Google</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- More Testimonials Grid -->
        <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-red-500">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-bold text-gray-900">Ahmed Hassan</div>
                        <div class="text-sm text-gray-600">Medical Student, Australia</div>
                    </div>
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-semibold">Band 7.5</span>
                </div>
                <p class="text-gray-700 text-sm italic">
                    "The mock tests were incredibly accurate. My actual test score was exactly what CD IELTS predicted!"
                </p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-bold text-gray-900">Priya Sharma</div>
                        <div class="text-sm text-gray-600">Nurse, New Zealand</div>
                    </div>
                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-semibold">Band 7.0</span>
                </div>
                <p class="text-gray-700 text-sm italic">
                    "Finally passed with the score I needed! The listening practice was exactly like the real test."
                </p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-blue-500">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-bold text-gray-900">Tom Wilson</div>
                        <div class="text-sm text-gray-600">MBA Student, USA</div>
                    </div>
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">Band 8.0</span>
                </div>
                <p class="text-gray-700 text-sm italic">
                    "Best investment for IELTS prep! Got into Harvard Business School with my score."
                </p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-purple-500">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-bold text-gray-900">Li Wei</div>
                        <div class="text-sm text-gray-600">Immigration Success</div>
                    </div>
                    <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-semibold">Band 6.5</span>
                </div>
                <p class="text-gray-700 text-sm italic">
                    "Achieved my immigration requirement in first attempt! CD IELTS made it possible."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="section-padding bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-red-500 font-bold text-sm uppercase tracking-wider">PRICING</span>
            <h2 class="mt-4 mb-6">Choose Your Plan</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Start free and upgrade when you're ready
            </p>
        </div>
        
        @php
            $plans = \App\Models\SubscriptionPlan::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('price')
                ->get();
        @endphp
        
        <div class="grid md:grid-cols-{{ min(3, max($plans->count(), 3)) }} gap-8 max-w-6xl mx-auto">
            @forelse($plans as $plan)
                <div class="bg-white rounded-2xl {{ $plan->is_featured ? 'shadow-2xl border-2 border-red-500 transform scale-105' : 'shadow-xl' }} p-8">
                    @if($plan->is_featured)
                        <div class="bg-red-500 text-white text-center py-2 px-4 rounded-full text-sm font-bold mb-4">
                            MOST POPULAR
                        </div>
                    @endif
                    
                    <h3 class="text-center mb-2">{{ $plan->name }}</h3>
                    <p class="text-center text-gray-600 mb-6">{{ $plan->description }}</p>
                    
                    <div class="text-center mb-8">
                        @if($plan->price == 0)
                            <span class="text-5xl font-bold text-gray-900">Free</span>
                        @else
                            <span class="text-5xl font-bold text-gray-900">${{ number_format($plan->price) }}</span>
                            <span class="text-gray-600">/month</span>
                        @endif
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        @foreach($plan->features as $feature => $enabled)
                            @if($enabled)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $feature)) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    
                    @if($plan->is_featured)
                        <a href="{{ route('register') }}" class="btn-primary w-full">Start Free Trial</a>
                    @elseif($plan->price == 0)
                        <a href="{{ route('register') }}" class="btn-secondary w-full">Get Started</a>
                    @else
                        <a href="{{ route('register') }}" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition text-center block">
                            Choose Plan
                        </a>
                    @endif
                </div>
            @empty
                <!-- Default Plans -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-center mb-2">Free</h3>
                    <p class="text-center text-gray-600 mb-6">Perfect for getting started</p>
                    <div class="text-center mb-8">
                        <span class="text-5xl font-bold text-gray-900">$0</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                    <a href="{{ route('register') }}" class="btn-secondary w-full">Get Started</a>
                </div>
                
                <div class="bg-white rounded-2xl shadow-2xl border-2 border-red-500 transform scale-105 p-8">
                    <div class="bg-red-500 text-white text-center py-2 px-4 rounded-full text-sm font-bold mb-4">
                        MOST POPULAR
                    </div>
                    <h3 class="text-center mb-2">Pro</h3>
                    <p class="text-center text-gray-600 mb-6">Ideal for serious candidates</p>
                    <div class="text-center mb-8">
                        <span class="text-5xl font-bold text-gray-900">$19</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                    <a href="{{ route('register') }}" class="btn-primary w-full">Start Free Trial</a>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-center mb-2">Premium</h3>
                    <p class="text-center text-gray-600 mb-6">Complete preparation package</p>
                    <div class="text-center mb-8">
                        <span class="text-5xl font-bold text-gray-900">$39</span>
                        <span class="text-gray-600">/month</span>
                    </div>
                    <a href="{{ route('register') }}" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition text-center block">
                        Choose Plan
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding bg-gradient-to-r from-red-500 to-red-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-white mb-6">Ready to Start Your IELTS Journey?</h2>
        <p class="text-xl text-white mb-10 max-w-3xl mx-auto">
            Join 50,000+ students who are already improving their scores with CD IELTS
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="openDemoModal()" class="bg-white text-red-500 px-8 py-4 rounded-lg font-semibold hover:shadow-xl transition inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Try Free Demo</span>
            </button>
            <a href="{{ route('register') }}" class="bg-transparent text-white border-2 border-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-red-500 transition inline-flex items-center justify-center">
                <span>Get Started Free</span>
            </a>
        </div>
    </div>
</section>

<!-- Enhanced Footer -->
<footer class="bg-gray-900 text-white">
    <div class="container mx-auto px-6 py-16">
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            <!-- Company Info -->
            <div class="md:col-span-1">
                @if($logo)
                    <img src="{{ $logo }}" alt="{{ $siteName }}" class="h-12 w-auto mb-4 brightness-0 invert">
                @else
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                            <span class="text-red-500 font-bold text-2xl">CD</span>
                        </div>
                        <span class="text-2xl font-bold">CD IELTS</span>
                    </div>
                @endif
                <p class="text-gray-400 mb-6">
                    Your trusted partner for IELTS success. Join thousands of students achieving their dream scores.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-500 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-500 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-500 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121L8.32 13.617l-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-red-500 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white">Quick Links</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition">About Us</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">How It Works</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Success Stories</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Pricing Plans</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Blog & Tips</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Contact Us</a></li>
                </ul>
            </div>
            
            <!-- Test Modules -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white">Test Modules</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('student.listening.index') }}" class="text-gray-400 hover:text-white transition">Listening Practice</a></li>
                    <li><a href="{{ route('student.reading.index') }}" class="text-gray-400 hover:text-white transition">Reading Practice</a></li>
                    <li><a href="{{ route('student.writing.index') }}" class="text-gray-400 hover:text-white transition">Writing Practice</a></li>
                    <li><a href="{{ route('student.speaking.index') }}" class="text-gray-400 hover:text-white transition">Speaking Practice</a></li>
                    <li><a href="{{ route('student.full-test.index') }}" class="text-gray-400 hover:text-white transition">Full Mock Tests</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Study Materials</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white">Get in Touch</h3>
                <ul class="space-y-4">
                    
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:support@cdielts.com" class="text-gray-400 hover:text-white">support@cdielts.com</a>
                    </li>
                    
                </ul>
                
                <!-- Newsletter -->
                <div class="mt-8">
                    <h4 class="text-sm font-bold mb-3 text-white">Subscribe to Newsletter</h4>
                    <form class="flex">
                        <input type="email" placeholder="Enter your email" class="bg-gray-800 text-white px-4 py-2 rounded-l-lg flex-1 focus:outline-none focus:bg-gray-700">
                        <button type="submit" class="bg-red-500 px-4 py-2 rounded-r-lg hover:bg-red-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 pt-8">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="text-center md:text-left">
                    <p class="text-gray-400">© {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                </div>
                <div class="text-center md:text-right">
                    <a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white transition mx-3">Privacy Policy</a>
                    <a href="{{ route('terms-of-service') }}" class="text-gray-400 hover:text-white transition mx-3">Terms of Service</a>
                    <a href="{{ route('cookie-policy') }}" class="text-gray-400 hover:text-white transition mx-3">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script>
    function openDemoModal() {
        alert('Demo test will be available soon!');
    }
</script>

</body>
</html>