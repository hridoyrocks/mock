<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired - CD IELTS</title>
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
        
        .hourglass {
            animation: rotate 2s ease-in-out infinite;
        }
        
        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }
        
        .sand {
            animation: sand-flow 2s ease-in-out infinite;
        }
        
        @keyframes sand-flow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(10px); }
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen flex items-center justify-center px-4">
        <div class="text-center z-10">
            <!-- 419 Illustration -->
            <div class="mb-8">
                <div class="relative inline-block">
                    <div class="w-32 h-32 md:w-48 md:h-48 rounded-full bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center mx-auto hourglass">
                        <i class="fas fa-hourglass-half text-6xl md:text-8xl text-amber-500"></i>
                    </div>
                    <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2">
                        <span class="text-6xl md:text-8xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">
                            419
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="glass rounded-2xl p-8 md:p-12 max-w-2xl mx-auto mt-12">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Session Expired
                </h1>
                <p class="text-gray-300 text-lg mb-8">
                    Your session has expired due to inactivity. This is a security measure to protect your account. Please refresh the page to continue.
                </p>
                
                <!-- Info Box -->
                <div class="glass rounded-xl p-6 mb-8 border border-amber-500/30">
                    <div class="flex items-center justify-center space-x-3 text-amber-500">
                        <i class="fas fa-info-circle text-2xl"></i>
                        <p class="text-sm">Sessions automatically expire after {{ config('session.lifetime', 120) }} minutes of inactivity</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="window.location.reload()" 
                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh Page
                    </button>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 rounded-xl glass text-white font-medium hover:bg-white/10 transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login Again
                    </a>
                </div>
                
                <!-- Security Tip -->
                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                        Security tip: Always log out when you're done to protect your account
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
