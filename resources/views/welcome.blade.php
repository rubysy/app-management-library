<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'YouLibrary') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        .grid-line-v {
            width: 1px;
            background-color: black;
            height: 100%;
        }
        .grid-line-h {
            height: 1px;
            background-color: black;
            width: 100%;
        }
    </style>
</head>
<body class="antialiased bg-white text-black min-h-screen flex flex-col">
    
    <!-- Header/Topbar grid line -->
    <div class="grid-line-h"></div>

    <!-- Main Hero Section -->
    <div class="flex-1 grid grid-cols-[80px_1fr_80px] md:grid-cols-[120px_1fr_120px] relative">
        <!-- Vertical Grid Lines -->
        <div class="border-r border-black"></div>
        
        <div class="flex flex-col items-center justify-center py-20 relative">
            <!-- Central Logo -->
            <div class="text-center px-4">
                <img src="{{ asset('image/OULIBRARY.png') }}" alt="YouLibrary Logo" class="h-32 md:h-48 w-auto mx-auto select-none">
                <p class="mt-12 text-lg md:text-xl tracking-widest lowercase">start reading.</p>
            </div>
        </div>

        <div class="border-l border-black"></div>
    </div>

    <!-- Auth Grid Section -->
    <div class="grid-line-h"></div>
    <div class="grid grid-cols-[80px_1fr_1fr_80px] md:grid-cols-[120px_1fr_1fr_120px]">
        <div class="border-r border-black"></div>
        
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="col-span-2 py-8 flex items-center justify-center text-xl font-bold bg-[#FF3B30] text-black hover:bg-black hover:text-white transition-all duration-300">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="py-8 flex items-center justify-center text-xl font-bold bg-[#FF3B30] text-black border-r border-black hover:bg-black hover:text-white transition-all duration-300">
                    Register
                </a>
                <a href="{{ route('login') }}" class="py-8 flex items-center justify-center text-xl font-bold bg-[#FF3B30] text-black hover:bg-black hover:text-white transition-all duration-300">
                    Login
                </a>
            @endauth
        @endif

        <div class="border-l border-black"></div>
    </div>
    <div class="grid-line-h"></div>

    <!-- Section 2: Collections/Features (The one I add myself) -->
    <div class="grid grid-cols-[80px_1fr_80px] md:grid-cols-[120px_1fr_120px] bg-white">
        <div class="border-r border-black"></div>
        
        <div class="py-20 px-8 md:px-20">
            <h2 class="text-3xl font-bold mb-12 uppercase tracking-tighter">Current Collections</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="p-6 border border-black group hover:bg-black transition-colors duration-300">
                    <h3 class="text-xl font-bold mb-4 group-hover:text-white">Digital Science</h3>
                    <p class="text-gray-600 group-hover:text-gray-300">Explore the latest in technology and scientific discovery.</p>
                </div>
                <div class="p-6 border border-black group hover:bg-black transition-colors duration-300">
                    <h3 class="text-xl font-bold mb-4 group-hover:text-white">Classic Literature</h3>
                    <p class="text-gray-600 group-hover:text-gray-300">Timeless stories that shaped the world of reading.</p>
                </div>
                <div class="p-6 border border-black group hover:bg-black transition-colors duration-300">
                    <h3 class="text-xl font-bold mb-4 group-hover:text-white">Modern Fiction</h3>
                    <p class="text-gray-600 group-hover:text-gray-300">New voices and contemporary tales for the modern reader.</p>
                </div>
            </div>
            
            <div class="mt-20 flex justify-between items-center border-t border-black pt-8">
                <div class="text-sm font-medium uppercase tracking-widest">Est. 2024</div>
                <div class="text-sm font-medium uppercase tracking-widest">YouLibrary &copy;</div>
            </div>
        </div>

        <div class="border-l border-black"></div>
    </div>
    <div class="grid-line-h"></div>

</body>
</html>
