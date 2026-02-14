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
        
        <div class="relative overflow-hidden" style="min-height: 400px;">
            <!-- Slide 0: Logo (default) -->
            <div class="hero-slide active flex flex-col items-center justify-center py-20 absolute inset-0 transition-opacity duration-1000" id="slide-0">
                <img src="{{ asset('image/OULIBRARY.png') }}" alt="YouLibrary Logo" class="h-32 md:h-48 w-auto mx-auto select-none">
                <p class="mt-12 text-lg md:text-xl tracking-widest lowercase">start reading.</p>
            </div>

            <!-- Slide 1: Library Interior -->
            <div class="hero-slide flex items-center justify-center absolute inset-0 transition-opacity duration-1000 opacity-0" id="slide-1">
                <div class="absolute inset-0">
                    <img src="{{ asset('image/hero_slide_1.jpg') }}" alt="Library" class="w-full h-full object-cover">
                    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.3));"></div>
                </div>
                <div class="relative z-10 text-center px-8" style="text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight">Jendela Dunia Ada<br>di Genggaman Anda</h2>
                    <p class="text-white text-lg md:text-xl">Ribuan koleksi buku menanti untuk dibaca. Mulai perjalanan literasi Anda hari ini.</p>
                </div>
            </div>

            <!-- Slide 2: Books Close-up -->
            <div class="hero-slide flex items-center justify-center absolute inset-0 transition-opacity duration-1000 opacity-0" id="slide-2">
                <div class="absolute inset-0">
                    <img src="{{ asset('image/hero_slide_2.jpg') }}" alt="Books" class="w-full h-full object-cover">
                    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.3));"></div>
                </div>
                <div class="relative z-10 text-center px-8" style="text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight">Pinjam, Baca,<br>Tumbuh Bersama</h2>
                    <p class="text-white text-lg md:text-xl">Perpustakaan digital modern untuk generasi yang haus pengetahuan.</p>
                </div>
            </div>
        </div>

        <div class="border-l border-black"></div>
    </div>

    <script>
        let currentSlide = 0;
        const totalSlides = 3;

        function nextSlide() {
            document.getElementById('slide-' + currentSlide).style.opacity = '0';
            currentSlide = (currentSlide + 1) % totalSlides;
            document.getElementById('slide-' + currentSlide).style.opacity = '1';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('slide-0').style.opacity = '1';
            setInterval(nextSlide, 7000);
        });
    </script>

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

    <!-- Section: Rating Tertinggi -->
    <div class="grid grid-cols-[80px_1fr_80px] md:grid-cols-[120px_1fr_120px] bg-white">
        <div class="border-r border-black"></div>
        
        <div class="py-20 px-8 md:px-20">
            <h2 class="text-3xl font-bold mb-2 uppercase tracking-tighter">Rating Tertinggi</h2>
            <p class="text-gray-500 mb-10 text-sm">Buku dengan rating terbaik dari para pembaca.</p>
            
            @if($topRated->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($topRated as $book)
                        <a href="{{ route('login') }}" class="group border border-black overflow-hidden hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-300">
                            <div class="h-48 bg-gray-100">
                                @if($book->cover_path)
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-black truncate group-hover:text-[#FF3B30] transition">{{ $book->title }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $book->author }}</p>
                                <div class="mt-2 flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= round($book->averageRating()) ? 'text-[#FF3B30]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="text-[10px] text-gray-400 ml-1">{{ $book->high_rating_count }} ulasan</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 border border-black">
                    <p class="text-gray-400 font-bold">Belum ada buku yang di-rating.</p>
                </div>
            @endif
        </div>

        <div class="border-l border-black"></div>
    </div>
    <div class="grid-line-h"></div>

    <!-- Section: Paling Sering Dipinjam -->
    <div class="grid grid-cols-[80px_1fr_80px] md:grid-cols-[120px_1fr_120px] bg-white">
        <div class="border-r border-black"></div>
        
        <div class="py-20 px-8 md:px-20">
            <h2 class="text-3xl font-bold mb-2 uppercase tracking-tighter">Paling Sering Dipinjam</h2>
            <p class="text-gray-500 mb-10 text-sm">Buku favorit yang paling banyak dipinjam oleh pembaca.</p>
            
            @if($mostBorrowed->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($mostBorrowed as $book)
                        <a href="{{ route('login') }}" class="group border border-black overflow-hidden hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-300">
                            <div class="h-48 bg-gray-100">
                                @if($book->cover_path)
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-black truncate group-hover:text-[#FF3B30] transition">{{ $book->title }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $book->author }}</p>
                                <div class="mt-2 flex items-center">
                                    <svg class="w-3.5 h-3.5 text-[#FF3B30] mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <span class="text-[10px] text-gray-400 font-bold">{{ $book->borrows_count }}x dipinjam</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 border border-black">
                    <p class="text-gray-400 font-bold">Belum ada buku yang dipinjam.</p>
                </div>
            @endif
        </div>

        <div class="border-l border-black"></div>
    </div>
    
    <!-- Footer -->
    <footer class="border-t-2 border-black bg-white">
        <div class="max-w-7xl mx-auto px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Logo & Tagline -->
                <div>
                    <h2 class="text-2xl font-black">YOU<span class="text-[#FF3B30]">LIBRARY</span></h2>
                    <p class="text-gray-600 mt-2">Sistem Manajemen Perpustakaan Digital dengan desain modern dan minimalis.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Menu Cepat</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ route('login') }}" class="hover:text-[#FF3B30] transition-colors">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[#FF3B30] transition-colors">Daftar</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>📍 Jl. Karanggan </li>
                        <li>📧 info@youlibrary.id</li>
                        <li>📞 08xx-xxxx-xxxx</li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-black flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm font-medium uppercase tracking-widest">Est. 2026</div>
                <div class="text-sm text-gray-500 mt-4 md:mt-0">made by rubysy</div>
                <div class="text-sm font-medium uppercase tracking-widest mt-4 md:mt-0">YouLibrary &copy; 2026</div>
            </div>
        </div>
    </footer>
    <div class="grid-line-h"></div>

</body>
</html>
