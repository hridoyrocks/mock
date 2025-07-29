<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied - CD IELTS</title>
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
        
        .lock-animation {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        
        .scan-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #C8102E, transparent);
            animation: scan 3s linear infinite;
        }
        
        @keyframes scan {
            0% { transform: translateY(-100px); }
            100% { transform: translateY(100px); }
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen flex items-center justify-center px-4">
        <div class="text-center z-10">
            <!-- 403 Illustration -->
            <div class="mb-8">
                <div class="relative inline-block">
                    <div class="w-32 h-32 md:w-48 md:h-48 rounded-full bg-gradient-to-br from-[#C8102E]/20 to-[#A00E27]/20 flex items-center justify-center mx-auto relative overflow-hidden lock-animation">
                        <i class="fas fa-lock text-6xl md:text-8xl text-[#C8102E]"></i>
                        <div class="scan-line"></div>
                    </div>
                    <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2">
                        <span class="text-6xl md:text-8xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#C8102E] to-[#A00E27]">
                            403
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="glass rounded-2xl p-8 md:p-12 max-w-2xl mx-auto mt-12">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Access Denied
                </h1>
                <p class="text-gray-300 text-lg mb-8">
                    Sorry, you don't have permission to access this page. This area requires special authorization.
                </p>
                
                <!-- Reasons -->
                <div class="glass rounded-xl p-6 mb-8 text-left max-w-md mx-auto">
                    <p class="text-gray-400 mb-3">This might be because:</p>
                    <ul class="space-y-2">
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check-circle text-[#C8102E] mr-2 mt-1 flex-shrink-0"></i>
                            <span>You need to be logged in to access this page</span>
                        </li>
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check-circle text-[#C8102E] mr-2 mt-1 flex-shrink-0"></i>
                            <span>Your account doesn't have the required permissions</span>
                        </li>
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-check-circle text-[#C8102E] mr-2 mt-1 flex-shrink-0"></i>
                            <span>This feature requires a premium subscription</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all shadow-lg hover:shadow-xl">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </a>
                    @else
                        <a href="{{ route('subscription.plans') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all shadow-lg hover:shadow-xl">
                            <i class="fas fa-crown mr-2"></i>
                            Upgrade Plan
                        </a>
                    @endguest
                    <a href="{{ url('/') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 rounded-xl glass text-white font-medium hover:bg-white/10 transition-all">
                        <i class="fas fa-home mr-2"></i>
                        Go Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
