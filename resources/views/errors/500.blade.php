<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error - CD IELTS</title>
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
        
        .glitch {
            position: relative;
            animation: glitch 2s infinite;
        }
        
        @keyframes glitch {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
        }
        
        .glitch::before,
        .glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .glitch::before {
            animation: glitch-1 0.5s infinite;
            color: #C8102E;
            z-index: -1;
        }
        
        .glitch::after {
            animation: glitch-2 0.5s infinite;
            color: #00FFFF;
            z-index: -2;
        }
        
        @keyframes glitch-1 {
            0%, 100% { clip-path: inset(0 0 0 0); }
            25% { clip-path: inset(0 0 70% 0); }
            50% { clip-path: inset(0 0 30% 0); }
            75% { clip-path: inset(50% 0 0 0); }
        }
        
        @keyframes glitch-2 {
            0%, 100% { clip-path: inset(0 0 0 0); }
            25% { clip-path: inset(0 0 60% 0); transform: translate(2px); }
            50% { clip-path: inset(0 0 20% 0); transform: translate(-2px); }
            75% { clip-path: inset(40% 0 0 0); transform: translate(1px); }
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
    </style>
</head>
<body class="antialiased">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute w-96 h-96 -top-48 -left-48 bg-[#C8102E] rounded-full opacity-10 blur-3xl pulse"></div>
        <div class="absolute w-96 h-96 -bottom-48 -right-48 bg-[#A00E27] rounded-full opacity-10 blur-3xl pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4">
        <div class="text-center z-10">
            <!-- 500 Illustration -->
            <div class="mb-8">
                <div class="relative inline-block">
                    <div class="text-[150px] md:text-[250px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#C8102E] to-[#A00E27] leading-none glitch" data-text="500">
                        500
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="glass rounded-2xl p-8 md:p-12 max-w-2xl mx-auto">
                <div class="w-20 h-20 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Oops! Something Went Wrong
                </h1>
                <p class="text-gray-300 text-lg mb-8">
                    We're experiencing some technical difficulties. Our team has been notified and is working on it. Please try again in a few moments.
                </p>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="window.location.reload()" 
                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-redo mr-2"></i>
                        Try Again
                    </button>
                    <a href="{{ url('/') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 rounded-xl glass text-white font-medium hover:bg-white/10 transition-all">
                        <i class="fas fa-home mr-2"></i>
                        Go Home
                    </a>
                </div>
                
                <!-- Support Info -->
                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-gray-400 mb-2">If the problem persists, please contact support:</p>
                    <a href="mailto:support@ieltsmock.com" class="text-[#C8102E] hover:text-[#A00E27] transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        support@cdielts.org
                    </a>
                </div>
                
                <!-- Error ID for debugging (only in development) -->
                @if(app()->environment('local'))
                    <div class="mt-6 text-xs text-gray-600">
                        Error ID: {{ Str::random(8) }}-{{ now()->timestamp }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
