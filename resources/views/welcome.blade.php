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

    <title>{{ $siteName }} - Your Path to IELTS Success</title>
    
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        :root {
            --primary: #C8102E;
            --primary-dark: #A00E27;
            --primary-light: #E8244A;
            --secondary: #1E40AF;
            --accent: #F59E0B;
            --success: #10B981;
            --dark: #111827;
            --light: #F9FAFB;
        }
        
        body {
            background: #FAFBFC;
        }
        
        /* Modern Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .glass-dark {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-mesh {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(at 47% 33%, hsl(340, 80%, 95%) 0, transparent 59%),
                radial-gradient(at 82% 65%, hsl(220, 80%, 95%) 0, transparent 55%);
        }
        
        /* Smooth Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Modern Button */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(200, 16, 46, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(200, 16, 46, 0.3);
        }
        
        /* Cards */
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
        }
        
        /* Progress Circle */
        .progress-circle {
            transform: rotate(-90deg);
            transition: stroke-dashoffset 1s ease;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #F3F4F6;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }
        
        /* Feature Cards */
        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }
        
        /* Section Icons */
        .section-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            font-size: 24px;
        }
        
        /* Testimonial Card */
        .testimonial-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(200, 16, 46, 0.1);
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="fixed w-full top-0 z-50 glass">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                @php
                    $logo = $settings && $settings->logo_path ? Storage::url($settings->logo_path) : null;
                @endphp
                
                @if($logo)
                    <img src="{{ $logo }}" alt="{{ $siteName }}" class="h-10 w-auto">
                @else
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">CD</span>
                    </div>
                    <span class="text-xl font-bold text-gray-800">CD IELTS</span>
                @endif
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-700 hover:text-primary transition font-medium">Features</a>
                <a href="#how-it-works" class="text-gray-700 hover:text-primary transition font-medium">How It Works</a>
                <a href="#testimonials" class="text-gray-700 hover:text-primary transition font-medium">Success Stories</a>
                <a href="#pricing" class="text-gray-700 hover:text-primary transition font-medium">Pricing</a>
            </div>
            
            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">
                        <i class="fas fa-rocket mr-2"></i>
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary font-medium transition">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">
                        <i class="fas fa-star mr-2"></i>
                        Start Free
                    </a>
                @endauth
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="md:hidden text-gray-700">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="pt-32 pb-20 relative overflow-hidden">
    <div class="gradient-mesh"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <!-- Motivational Badge -->
            <div class="inline-flex items-center bg-red-50 rounded-full px-6 py-3 mb-8">
                <i class="fas fa-fire text-red-500 mr-2"></i>
                <span class="text-sm font-semibold text-gray-700">Join 50,000+ Successful Students</span>
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Transform Your Future with
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-600">
                    AI-Powered IELTS
                </span>
            </h1>
            
            <!-- Motivational Quote -->
            <p class="text-xl text-gray-600 mb-4 italic">
                "The expert in anything was once a beginner"
            </p>
            
            <p class="text-lg text-gray-600 mb-10 max-w-3xl mx-auto">
                Your journey to Band 8+ starts here. Practice with real exam questions, 
                get instant AI feedback, and achieve your dreams faster than ever before.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="{{ route('register') }}" class="btn-primary text-lg inline-flex items-center justify-center">
                    <i class="fas fa-play-circle mr-2"></i>
                    Start Your Journey Free
                </a>
                <button onclick="watchDemo()" class="px-8 py-4 bg-white rounded-xl font-semibold text-gray-700 hover:shadow-lg transition inline-flex items-center justify-center">
                    <i class="fas fa-video mr-2"></i>
                    Watch Success Stories
                </button>
            </div>
            
            <!-- Trust Indicators -->
            <div class="flex flex-wrap justify-center gap-8 items-center">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900">50K+</div>
                    <div class="text-sm text-gray-600">Active Students</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900">95%</div>
                    <div class="text-sm text-gray-600">Success Rate</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900">1M+</div>
                    <div class="text-sm text-gray-600">Tests Completed</div>
                </div>
                <div class="text-center">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="text-sm text-gray-600">4.9/5 Rating</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Motivational Evolution Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Your Evolution Journey</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Every master was once a disaster. Your transformation starts with a single step.
            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <!-- Timeline -->
            <div class="relative">
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-gray-300 via-red-300 to-green-300"></div>
                
                <!-- Step 1 -->
                <div class="relative flex items-center mb-12">
                    <div class="w-full md:w-1/2 pr-8 text-right">
                        <div class="bg-white rounded-2xl p-6 shadow-lg inline-block text-left">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-user-graduate text-gray-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Where You Are</h3>
                            </div>
                            <p class="text-gray-600">Feeling overwhelmed, unsure where to start, worried about the test</p>
                        </div>
                    </div>
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-8 h-8 bg-white border-4 border-gray-400 rounded-full"></div>
                    <div class="w-full md:w-1/2 pl-8"></div>
                </div>
                
                <!-- Step 2 -->
                <div class="relative flex items-center mb-12">
                    <div class="w-full md:w-1/2 pr-8"></div>
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-8 h-8 bg-white border-4 border-red-400 rounded-full">
                        <div class="absolute inset-0 bg-red-400 rounded-full animate-ping"></div>
                    </div>
                    <div class="w-full md:w-1/2 pl-8">
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-rocket text-red-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Your Journey Begins</h3>
                            </div>
                            <p class="text-gray-600">AI analyzes your level, creates personalized path, builds confidence daily</p>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="relative flex items-center">
                    <div class="w-full md:w-1/2 pr-8 text-right">
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 shadow-lg inline-block text-left border border-green-200">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-trophy text-green-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Where You'll Be</h3>
                            </div>
                            <p class="text-gray-600">Confident speaker, Band 8+ achiever, living your dream abroad</p>
                        </div>
                    </div>
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-8 h-8 bg-white border-4 border-green-400 rounded-full">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                    <div class="w-full md:w-1/2 pl-8"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Features Section -->
<section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                AI That Understands You
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Our AI doesn't just evaluate—it mentors, guides, and evolves with you
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Feature 1 -->
            <div class="feature-card rounded-2xl p-8 card-hover">
                <div class="section-icon bg-gradient-to-br from-red-100 to-red-50 text-red-600 mb-6">
                    <i class="fas fa-brain"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Learning Path</h3>
                <p class="text-gray-600 mb-4">
                    AI analyzes your strengths and weaknesses to create a personalized study plan that adapts as you improve
                </p>
                <div class="flex items-center text-red-600 font-medium">
                    <span>Learn more</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="feature-card rounded-2xl p-8 card-hover">
                <div class="section-icon bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 mb-6">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Instant Feedback</h3>
                <p class="text-gray-600 mb-4">
                    Get detailed explanations for every answer, understand your mistakes, and learn the right approach instantly
                </p>
                <div class="flex items-center text-blue-600 font-medium">
                    <span>Learn more</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="feature-card rounded-2xl p-8 card-hover">
                <div class="section-icon bg-gradient-to-br from-green-100 to-green-50 text-green-600 mb-6">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Progress Analytics</h3>
                <p class="text-gray-600 mb-4">
                    Track your improvement with beautiful visualizations, celebrate milestones, and stay motivated every day
                </p>
                <div class="flex items-center text-green-600 font-medium">
                    <span>Learn more</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="feature-card rounded-2xl p-8 card-hover">
                <div class="section-icon bg-gradient-to-br from-purple-100 to-purple-50 text-purple-600 mb-6">
                    <i class="fas fa-microphone"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Speaking Practice</h3>
                <p class="text-gray-600 mb-4">
                    Practice speaking with AI that understands accents, provides pronunciation tips, and builds your confidence
                </p>
                <div class="flex items-center text-purple-600 font-medium">
                    <span>Learn more</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
            
            <!-- Feature 5 -->
            <div class="feature-card rounded-2xl p-8 card-hover">
                <div class="section-icon bg-gradient-to-br from-yellow-100 to-yellow-50 text-yellow-600 mb-6">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Flexible Schedule</h3>
                <p class="text-gray-600 mb-4">
                    Study anytime, anywhere. Our platform works around your life, not the other way around
                </p>
                <div class="flex items-center text-yellow-600 font-medium">
                    <span>Learn more</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
            
            <!-- Feature 6 -->
            <div class="feature-card rounded-2xl p-8 card-hover">
                <div class="section-icon bg-gradient-to-br from-pink-100 to-pink-50 text-pink-600 mb-6">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Community Support</h3>
                <p class="text-gray-600 mb-4">
                    Join thousands of students on the same journey. Share tips, celebrate wins, and never feel alone
                </p>
                <div class="flex items-center text-pink-600 font-medium">
                    <span>Learn more</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how-it-works" class="py-20 bg-gradient-to-br from-red-50 to-pink-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Your Success in 4 Simple Steps</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                No complicated process. Just simple, effective learning that works.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-5xl mx-auto">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-3xl font-bold text-red-600">1</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Take a Free Test</h3>
                <p class="text-gray-600">Start with a diagnostic test to understand your current level</p>
            </div>
            
            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-3xl font-bold text-red-600">2</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Get Your AI Plan</h3>
                <p class="text-gray-600">Receive a personalized study plan based on your goals</p>
            </div>
            
            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-3xl font-bold text-red-600">3</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Practice Daily</h3>
                <p class="text-gray-600">Study with real exam questions and instant feedback</p>
            </div>
            
            <!-- Step 4 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-3xl font-bold text-red-600">4</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Achieve Your Dream</h3>
                <p class="text-gray-600">Get your target score and unlock new opportunities</p>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('register') }}" class="btn-primary text-lg">
                <i class="fas fa-rocket mr-2"></i>
                Start Step 1 Now - It's Free
            </a>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section id="testimonials" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Real Students, Real Success</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Every day, our students achieve their dreams. You could be next.
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-12">
            <!-- Testimonial 1 -->
            <div class="testimonial-card rounded-2xl p-8 card-hover">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900">Sarah Ahmed</h4>
                        <p class="text-sm text-gray-600">Band 8.5 Achiever</p>
                    </div>
                </div>
                <div class="flex text-yellow-400 mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 italic mb-4">
                    "I was scared of speaking, but CD IELTS AI made me confident. From Band 6 to 8.5 in just 2 months! 
                    Now I'm studying at Cambridge University. Dreams do come true!"
                </p>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Cambridge University, UK
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="testimonial-card rounded-2xl p-8 card-hover">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/44.jpg" alt="Ravi" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900">Ravi Patel</h4>
                        <p class="text-sm text-gray-600">Band 8.0 Achiever</p>
                    </div>
                </div>
                <div class="flex text-yellow-400 mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 italic mb-4">
                    "The AI feedback is incredible! It's like having a personal tutor 24/7. 
                    Got my Canadian PR with Band 8. Best investment of my life!"
                </p>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-briefcase mr-2"></i>
                    Software Engineer, Toronto
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="testimonial-card rounded-2xl p-8 card-hover">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Maria" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900">Maria Chen</h4>
                        <p class="text-sm text-gray-600">Band 9.0 Achiever</p>
                    </div>
                </div>
                <div class="flex text-yellow-400 mb-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 italic mb-4">
                    "From struggling with 5.5 to achieving perfect 9.0! The personalized study plan 
                    and daily motivation kept me going. Now working at Google!"
                </p>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-building mr-2"></i>
                    Product Manager, Google
                </div>
            </div>
        </div>
        
        <!-- Success Stats -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-3xl p-8 md:p-12 text-white max-w-4xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">15,234</div>
                    <div class="text-red-100">Students Abroad</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">8,456</div>
                    <div class="text-red-100">Got PR/Immigration</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">12,789</div>
                    <div class="text-red-100">Better Jobs</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="text-red-100">Achieved Target</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Success Plan</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Start free, upgrade when you're ready. No hidden fees, no surprises.
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Free Plan -->
            <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                <p class="text-gray-600 mb-6">Perfect for trying out</p>
                <div class="mb-8">
                    <span class="text-4xl font-bold text-gray-900">Free</span>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">5 AI evaluations per month</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">Basic progress tracking</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">Community access</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-times mr-3"></i>
                        <span>Personalized study plan</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-times mr-3"></i>
                        <span>Speaking practice</span>
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition">
                    Get Started
                </a>
            </div>
            
            <!-- Pro Plan -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-3xl p-8 shadow-xl transform scale-105 text-white">
                <div class="bg-white text-red-600 px-4 py-1 rounded-full inline-block mb-4 text-sm font-semibold">
                    MOST POPULAR
                </div>
                <h3 class="text-2xl font-bold mb-2">Pro</h3>
                <p class="text-red-100 mb-6">For serious students</p>
                <div class="mb-8">
                    <span class="text-4xl font-bold">৳999</span>
                    <span class="text-red-100">/month</span>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center">
                        <i class="fas fa-check text-white mr-3"></i>
                        <span>Unlimited AI evaluations</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-white mr-3"></i>
                        <span>Personalized study plan</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-white mr-3"></i>
                        <span>Advanced analytics</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-white mr-3"></i>
                        <span>Speaking practice with AI</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-white mr-3"></i>
                        <span>Priority support</span>
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-white text-red-600 rounded-xl font-semibold hover:bg-gray-100 transition">
                    Start 7-Day Free Trial
                </a>
            </div>
            
            <!-- Premium Plan -->
            <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Premium</h3>
                <p class="text-gray-600 mb-6">Maximum results</p>
                <div class="mb-8">
                    <span class="text-4xl font-bold text-gray-900">৳2499</span>
                    <span class="text-gray-600">/month</span>
                </div>
                
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">Everything in Pro</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">1-on-1 expert sessions</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">Writing correction by experts</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">Score guarantee</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">VIP support 24/7</span>
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-gray-900 text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                    Get Premium
                </a>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <p class="text-gray-600">
                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                30-day money-back guarantee • Cancel anytime • Secure payment
            </p>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Common Questions</h2>
                <p class="text-lg text-gray-600">Everything you need to know to get started</p>
            </div>
            
            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="flex justify-between items-center cursor-pointer" onclick="toggleFAQ(1)">
                        <h3 class="text-lg font-semibold text-gray-900">How is CD IELTS different from other platforms?</h3>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform" id="faq-icon-1"></i>
                    </div>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-1">
                        We use advanced AI that provides instant, personalized feedback on all four IELTS sections. 
                        Our AI understands context, not just grammar, giving you human-like guidance 24/7.
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="flex justify-between items-center cursor-pointer" onclick="toggleFAQ(2)">
                        <h3 class="text-lg font-semibold text-gray-900">Can I really improve my band score?</h3>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform" id="faq-icon-2"></i>
                    </div>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-2">
                        Yes! 95% of our students improve by at least 1 band score within 2 months. 
                        Our AI creates a personalized path based on your weaknesses, ensuring maximum improvement.
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="flex justify-between items-center cursor-pointer" onclick="toggleFAQ(3)">
                        <h3 class="text-lg font-semibold text-gray-900">Is the AI evaluation accurate?</h3>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform" id="faq-icon-3"></i>
                    </div>
                    <div class="mt-4 text-gray-600 hidden" id="faq-content-3">
                        Our AI is trained on millions of real IELTS responses and verified by certified examiners. 
                        It provides band scores within 0.5 bands of actual test results.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-20 bg-gradient-to-br from-red-500 to-red-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Your Dream Score is Waiting
        </h2>
        <p class="text-xl text-red-100 mb-10 max-w-2xl mx-auto">
            Join 50,000+ students who transformed their lives with CD IELTS. 
            Start your success story today.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-red-600 rounded-xl font-bold text-lg hover:shadow-xl transition inline-flex items-center justify-center">
                <i class="fas fa-rocket mr-2"></i>
                Start Free Now
            </a>
            <button onclick="watchDemo()" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-semibold text-lg hover:bg-white hover:text-red-600 transition inline-flex items-center justify-center">
                <i class="fas fa-play-circle mr-2"></i>
                Watch Demo
            </button>
        </div>
        
        <div class="mt-12 flex flex-wrap justify-center gap-8 text-white">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                No credit card required
            </div>
            <div class="flex items-center">
                <i class="fas fa-clock mr-2"></i>
                Start in 30 seconds
            </div>
            <div class="flex items-center">
                <i class="fas fa-users mr-2"></i>
                Join 50,000+ students
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <!-- Company Info -->
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold">CD</span>
                    </div>
                    <span class="text-xl font-bold">CD IELTS</span>
                </div>
                <p class="text-gray-400 mb-4">
                    Empowering students worldwide to achieve their IELTS dreams through AI-powered learning.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-youtube text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4">Practice Tests</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('student.listening.index') }}" class="text-gray-400 hover:text-white transition">Listening Practice</a></li>
                    <li><a href="{{ route('student.reading.index') }}" class="text-gray-400 hover:text-white transition">Reading Practice</a></li>
                    <li><a href="{{ route('student.writing.index') }}" class="text-gray-400 hover:text-white transition">Writing Practice</a></li>
                    <li><a href="{{ route('student.speaking.index') }}" class="text-gray-400 hover:text-white transition">Speaking Practice</a></li>
                </ul>
            </div>
            
            <!-- Resources -->
            <div>
                <h4 class="font-semibold mb-4">Resources</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Study Guide</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Success Stories</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Blog</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h4 class="font-semibold mb-4">Get in Touch</h4>
                <ul class="space-y-2">
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-envelope mr-2"></i>
                        support@cdielts.com
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-phone mr-2"></i>
                        +880 1234-567890
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Dhaka, Bangladesh
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-800 pt-8 text-center">
            <p class="text-gray-400">
                © {{ date('Y') }} CD IELTS. All rights reserved. | 
                <a href="{{ route('privacy-policy') }}" class="hover:text-white transition">Privacy</a> | 
                <a href="{{ route('terms-of-service') }}" class="hover:text-white transition">Terms</a>
            </p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script>
    // FAQ Toggle
    function toggleFAQ(id) {
        const content = document.getElementById(`faq-content-${id}`);
        const icon = document.getElementById(`faq-icon-${id}`);
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
    
    // Demo Functions
    function watchDemo() {
        alert('Demo video coming soon! For now, sign up for free to explore.');
    }
    
    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Add floating animation to images
    window.addEventListener('load', () => {
        const images = document.querySelectorAll('.float-animation');
        images.forEach((img, index) => {
            img.style.animationDelay = `${index * 0.5}s`;
        });
    });
</script>

</body>
</html>