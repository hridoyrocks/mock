<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found - CD IELTS</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #0f0f23;
            overflow: hidden;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
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
            10% { opacity: 0.5; }
            90% { opacity: 0.5; }
            to {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="antialiased">
    <!-- Particle Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="particle w-1 h-1 bg-white/20 rounded-full" style="left: 10%; animation-duration: 15s;"></div>
        <div class="particle w-1 h-1 bg-gray-400 rounded-full" style="left: 20%; animation-duration: 20s; animation-delay: 2s;"></div>
        <div class="particle w-1 h-1 bg-white/30 rounded-full" style="left: 30%; animation-duration: 18s; animation-delay: 4s;"></div>
        <div class="particle w-1 h-1 bg-gray-500 rounded-full" style="left: 40%; animation-duration: 22s; animation-delay: 6s;"></div>
        <div class="particle w-1 h-1 bg-white/25 rounded-full" style="left: 50%; animation-duration: 17s; animation-delay: 8s;"></div>
        <div class="particle w-1 h-1 bg-gray-400 rounded-full" style="left: 60%; animation-duration: 25s; animation-delay: 10s;"></div>
        <div class="particle w-1 h-1 bg-white/20 rounded-full" style="left: 70%; animation-duration: 19s; animation-delay: 12s;"></div>
        <div class="particle w-1 h-1 bg-gray-500 rounded-full" style="left: 80%; animation-duration: 21s; animation-delay: 14s;"></div>
        <div class="particle w-1 h-1 bg-white/30 rounded-full" style="left: 90%; animation-duration: 16s; animation-delay: 16s;"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4">
        <div class="text-center z-10">
            <!-- 404 Illustration -->
            <div class="mb-8 float-animation">
                <div class="relative inline-block">
                    <div class="text-[200px] md:text-[300px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#C8102E] to-[#A00E27] leading-none">
                        404
                    </div>
                    <div class="absolute inset-0 blur-3xl opacity-30">
                        <div class="text-[200px] md:text-[300px] font-bold text-[#C8102E] leading-none">
                            404
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="glass rounded-2xl p-8 md:p-12 max-w-2xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Oops! Page Not Found
                </h1>
                <p class="text-gray-300 text-lg mb-8">
                    The page you're looking for seems to have wandered off. Don't worry, even the best explorers get lost sometimes!
                </p>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ url('/') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-home mr-2"></i>
                        Go Home
                    </a>
                    <button onclick="history.back()" 
                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl glass text-white font-medium hover:bg-white/10 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Go Back
                    </button>
                </div>
                
                <!-- Search Suggestion -->
                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-gray-400 mb-4">Or try searching for what you need:</p>
                    <form action="{{ route('home') }}" method="GET" class="max-w-md mx-auto">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Search for tests, resources..."
                                   class="w-full px-4 py-3 pl-12 rounded-xl glass bg-transparent text-white placeholder-gray-400 focus:outline-none focus:border-[#C8102E]/50 border border-white/10">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Fun Facts -->
            <div class="mt-8 text-gray-500 text-sm">
                <p>Fun fact: This error code was named after the HTTP status code for "Not Found"</p>
            </div>
        </div>
    </div>
</body>
</html>
