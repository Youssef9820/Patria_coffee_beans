<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Patria Coffee') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .brand-font { font-family: 'Playfair Display', serif; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<!-- FORCE 100% VIEWPORT HEIGHT via STYLE -->
<body class="bg-[#FAF9F6] antialiased text-gray-800" 
      style="height: 100vh; overflow: hidden; margin: 0;"
      x-data="{ sidebarOpen: true }">
    
    <!-- MASTER CONTAINER -->
    <div class="flex w-full" style="height: 100vh;">

        <!-- 1. SIDEBAR WRAPPER -->
        <!-- Forced Green Background via Style -->
        <aside class="flex-shrink-0 transition-all duration-300 ease-in-out z-20 flex flex-col"
               :class="sidebarOpen ? 'w-64' : 'w-0'"
               style="background-color: #556B2F; height: 100vh; overflow-y: auto;">
            
            <!-- Inner container to prevent text wrapping when width is 0 -->
            <div style="width: 16rem;" :class="sidebarOpen ? 'block' : 'hidden'">
                @include('layouts.sidebar')
            </div>
        
        </aside>

        <!-- 2. MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 relative" style="height: 100vh;">
            
            <!-- TOP BAR -->
            @include('layouts.topbar')

            <!-- SCROLLABLE PAGE AREA -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6" style="background-color: #FAF9F6;">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>