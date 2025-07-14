{{-- resources/views/maintenance.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, #e0e7ff 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, #fce7f3 0%, transparent 50%);
            opacity: 0.4;
        }
        
        /* Grid Pattern */
        body::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(#e2e8f0 1px, transparent 1px),
                linear-gradient(90deg, #e2e8f0 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.1;
        }
        
        /* Floating Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 200px;
            height: 200px;
            background: #6366f1;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 150px;
            height: 150px;
            background: #ec4899;
            bottom: 20%;
            right: 15%;
            animation-delay: 5s;
        }
        
        .shape-3 {
            width: 100px;
            height: 100px;
            background: #8b5cf6;
            top: 50%;
            right: 20%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -30px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }
        
        .maintenance-box {
            background: white;
            width: 90%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            overflow: hidden;
            position: relative;
            z-index: 10;
        }
        
        @auth
        .user-bar {
            background: #1e293b;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #475569;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-text {
            color: white;
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 600;
        }
        
        .user-email {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .logout-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 8px;
            transition: color 0.2s;
        }
        
        .logout-btn:hover {
            color: white;
        }
        @endauth
        
        .content {
            padding: 48px 32px;
            text-align: center;
        }
        
        .icon {
            width: 64px;
            height: 64px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        
        .icon i {
            font-size: 28px;
            color: #475569;
        }
        
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        
        p {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        
        @if($maintenance && $maintenance->expected_end_at)
        .time-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 32px;
        }
        
        .time-label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .time-value {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
        }
        @endif
        
        .refresh-btn {
            background: #0f172a;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .refresh-btn:hover {
            background: #1e293b;
        }
        
        .support {
            margin-top: 24px;
            font-size: 13px;
            color: #94a3b8;
        }
        
        .support a {
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
        }
        
        .support a:hover {
            text-decoration: underline;
        }
        
        /* Status indicator */
        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #dc2626;
            background: #fef2f2;
            padding: 6px 12px;
            border-radius: 100px;
            margin-bottom: 24px;
        }
        
        .status-dot {
            width: 6px;
            height: 6px;
            background: #dc2626;
            border-radius: 50%;
            animation: blink 2s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
</head>
<body>
    <!-- Floating Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    
    <div class="maintenance-box">
        @auth
        <div class="user-bar">
            <div class="user-info">
                <div class="avatar">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="user-text">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
        @endauth
        
        <div class="content">
            <div class="status">
                <span class="status-dot"></span>
                Maintenance Mode
            </div>
            
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
            
            <h1>{{ $maintenance->title ?? 'We\'ll be back soon' }}</h1>
            
            <p>
                {{ $maintenance->message ?? 'We\'re performing scheduled maintenance. Thank you for your patience.' }}
            </p>
            
            @if($maintenance && $maintenance->expected_end_at)
            <div class="time-box">
                <div class="time-label">Expected completion</div>
                <div class="time-value">{{ $maintenance->expected_end_at->format('M d, Y - h:i A') }}</div>
            </div>
            @endif
            
            <button onclick="location.reload()" class="refresh-btn">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </button>
            
            <div class="support">
                Need help? <a href="mailto:support@cdieltsmaster.com">Contact Support</a>
            </div>
        </div>
    </div>
    
    <script>
        setInterval(() => location.reload(), 60000);
    </script>
</body>
</html>