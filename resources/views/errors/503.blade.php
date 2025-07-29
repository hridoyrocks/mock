<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance Mode - IELTS Mock Platform</title>
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
        
        .gear {
            animation: spin 4s linear infinite;
        }
        
        .gear-reverse {
            animation: spin-reverse 3s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        
        .progress-bar {
            animation: progress 2s ease-in-out infinite;
        }
        
        @keyframes progress {
            0%, 100% { width: 20%; }
            50% { width: 80%; }
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen flex items-center justify-center px-4">
        <div class="text-center z-10">
            <!-- Maintenance Illustration -->
            <div class="mb-8">
                <div class="relative inline-block">
                    <!-- Gears Animation -->
                    <div class="relative w-48 h-48 md:w-64 md:h-64 mx-auto">
                        <div class="absolute top-0 left-0 w-24 h-24 md:w-32 md:h-32">
                            <i class="fas fa-cog text-6xl md:text-8xl text-[#C8102E] gear"></i>
                        </div>
                        <div class="absolute bottom-0 right-0 w-32 h-32 md:w-40 md:h-40">
                            <i class="fas fa-cog text-8xl md:text-9xl text-[#A00E27] gear-reverse opacity-50"></i>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <i class="fas fa-wrench text-4xl md:text-6xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="glass rounded-2xl p-8 md:p-12 max-w-2xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    We'll Be Right Back!
                </h1>
                <p class="text-gray-300 text-lg mb-8">
                    We're currently performing scheduled maintenance to improve your experience. This won't take long!
                </p>
                
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex justify-between text-sm text-gray-400 mb-2">
                        <span>Progress</span>
                        <span>Estimated time: 30 minutes</span>
                    </div>
                    <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#C8102E] to-[#A00E27] rounded-full progress-bar"></div>
                    </div>
                </div>
                
                <!-- What's Being Updated -->
                <div class="glass rounded-xl p-6 mb-8 text-left max-w-md mx-auto">
                    <p class="text-gray-400 mb-3 font-semibold">What we're working on:</p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Performance improvements</span>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-spinner fa-spin text-[#C8102E] mr-2"></i>
                            <span>New features deployment</span>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-circle text-gray-500 mr-2 text-xs"></i>
                            <span>Security updates</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="window.location.reload()" 
                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#C8102E] text-white font-medium hover:bg-[#A00E27] transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-redo mr-2"></i>
                        Check Again
                    </button>
                    <a href="https://twitter.com/ieltsmock" 
                       target="_blank"
                       class="inline-flex items-center justify-center px-6 py-3 rounded-xl glass text-white font-medium hover:bg-white/10 transition-all">
                        <i class="fab fa-twitter mr-2"></i>
                        Get Updates
                    </a>
                </div>
                
                <!-- Contact Info -->
                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-gray-400 mb-2">Need urgent assistance?</p>
                    <a href="mailto:support@ieltsmock.com" class="text-[#C8102E] hover:text-[#A00E27] transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        support@ieltsmock.com
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Auto Refresh Script -->
    <script>
        // Auto refresh every 30 seconds
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
