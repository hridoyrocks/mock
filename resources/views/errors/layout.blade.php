<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #fafafa;
            color: #1a1a1a;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .error-container {
            text-align: center;
            padding: 2rem;
            max-width: 500px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .error-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            opacity: 0.2;
        }
        
        .error-code {
            font-size: 5rem;
            font-weight: 100;
            letter-spacing: -0.03em;
            margin-bottom: 0.25rem;
            color: #000;
            line-height: 1;
        }
        
        .error-divider {
            width: 40px;
            height: 1px;
            background: #e5e5e5;
            margin: 1.5rem auto;
        }
        
        .error-title {
            font-size: 1.25rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            color: #000;
            letter-spacing: -0.01em;
        }
        
        .error-message {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        .error-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .button {
            display: inline-block;
            padding: 0.625rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }
        
        .button-primary {
            background: #000;
            color: #fff;
            border-color: #000;
        }
        
        .button-primary:hover {
            background: #1a1a1a;
            border-color: #1a1a1a;
        }
        
        .button-secondary {
            background: #fff;
            color: #666;
            border-color: #e5e5e5;
        }
        
        .button-secondary:hover {
            background: #f5f5f5;
            color: #000;
            border-color: #d4d4d4;
        }
        
        /* Error specific styles */
        .error-404 .error-icon { stroke: #666; }
        .error-403 .error-icon { stroke: #ea580c; }
        .error-500 .error-icon { stroke: #dc2626; }
        .error-503 .error-icon { stroke: #0891b2; }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background: #0a0a0a;
                color: #fafafa;
            }
            
            .error-container {
                background: #1a1a1a;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            }
            
            .error-code {
                color: #fff;
            }
            
            .error-title {
                color: #fff;
            }
            
            .error-message {
                color: #a3a3a3;
            }
            
            .error-divider {
                background: #333;
            }
            
            .button-primary {
                background: #fff;
                color: #000;
                border-color: #fff;
            }
            
            .button-primary:hover {
                background: #f5f5f5;
                border-color: #f5f5f5;
            }
            
            .button-secondary {
                background: #1a1a1a;
                color: #a3a3a3;
                border-color: #333;
            }
            
            .button-secondary:hover {
                background: #262626;
                color: #fff;
                border-color: #404040;
            }
        }
        
        @media (max-width: 640px) {
            .error-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .error-code {
                font-size: 3.5rem;
            }
            
            .error-icon {
                width: 48px;
                height: 48px;
            }
            
            .error-title {
                font-size: 1.125rem;
            }
            
            .error-message {
                font-size: 0.875rem;
            }
            
            .error-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .button {
                width: 100%;
                text-align: center;
            }
        }
        
        /* Subtle animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-container {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('error-class', '')">
    <div class="error-container">
        @yield('icon')
        
        <h1 class="error-code">@yield('code')</h1>
        
        <div class="error-divider"></div>
        
        <h2 class="error-title">@yield('title')</h2>
        
        <p class="error-message">
            @yield('message')
        </p>
        
        <div class="error-actions">
            @yield('actions')
        </div>
    </div>
    
    <script>
        function goBack() {
            // Check if there's a referrer and it's not the current page
            if (document.referrer && document.referrer !== window.location.href) {
                window.location.href = document.referrer;
            } else if (window.history.length > 1) {
                // If there's history, go back
                window.history.back();
            } else {
                // Otherwise, go to home
                window.location.href = '{{ url('/') }}';
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
