<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $websiteSettings->site_title }} - {{ $title ?? 'Home' }}</title>

    <!-- Favicon -->
    @if($websiteSettings->favicon_url)
    <link rel="icon" type="image/x-icon" href="{{ $websiteSettings->favicon_url }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #ef4444;
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #dc2626;
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Apply Outfit font to everything */
        * {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Body background */
        body {
            background: linear-gradient(to bottom right, #ffffff, #fef2f2, #ffffff);
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
        }
    </style>
    
    {{ $head ?? '' }}
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        <!-- Guest Header -->
        <x-guest-header />
        
        <!-- Page Content -->
        <main class="pt-20">
            {{ $slot }}
        </main>
        
        <!-- Guest Footer -->
        <x-guest-footer />
    </div>
    
    <!-- Scripts -->
    {{ $scripts ?? '' }}
</body>
</html>
