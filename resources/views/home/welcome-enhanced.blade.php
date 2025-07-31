<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IELTS Mock Platform') }} - Transform Your IELTS Journey</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;700;900&display=swap" rel="stylesheet">
    
    <style>
        /* Font Styles */
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        
        /* Gradient Text Effects */
        .text-gradient-fire {
            background: linear-gradient(135deg, #ff006e 0%, #ff4500 25%, #ff8c00 50%, #ffd700 75%, #ff006e 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fire-gradient 4s ease infinite;
        }
        
        @keyframes fire-gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* 3D Text Effect */
        .text-3d {
            text-shadow: 
                1px 1px 0 #ff006e,
                2px 2px 0 #ff1744,
                3px 3px 0 #ff5252,
                4px 4px 0 #ff6b6b,
                5px 5px 0 #ff8787,
                6px 6px 