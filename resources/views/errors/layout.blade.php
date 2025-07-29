<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error') - IELTS Mock Platform</title>
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
        
        @yield('styles')
    </style>
</head>
<body class="antialiased">
    <!-- Particle Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        @for($i = 1; $i <= 9; $i++)
            <div class="particle w-1 h-1 bg-white/{{ rand(20, 30) }} rounded-full" 
                 style="left: {{ $i * 10 }}%; animation-duration: {{ rand(15, 25) }}s; animation-delay: {{ $i * 2 }}s;"></div>
        @endfor
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4">
        <div class="text-center z-10">
            @yield('content')
        </div>
    </div>
    
    @yield('scripts')
</body>
</html>
