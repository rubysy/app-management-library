<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'YouLibrary') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-black">
    <div class="flex h-screen overflow-hidden text-gray-900">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-black hidden md:block">
            <div class="h-16 flex items-center justify-center border-b border-black">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="h-8 w-auto" />
                </a>
            </div>

            <nav class="mt-6 px-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-bold transition-colors {{ request()->routeIs('dashboard') ? 'bg-white border-l-4 border-[#FF3B30] text-[#FF3B30]' : 'text-black hover:bg-gray-100 border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Koleksi Buku
                </a>

                <!-- History -->
                <a href="{{ route('borrows.history') }}" class="flex items-center px-4 py-2.5 text-sm font-bold transition-colors {{ request()->routeIs('borrows.history') ? 'bg-white border-l-4 border-[#FF3B30] text-[#FF3B30]' : 'text-black hover:bg-gray-100 border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Peminjaman
                </a>

                <!-- Bookmarks -->
                <a href="{{ route('bookmarks.index') }}" class="flex items-center px-4 py-2.5 text-sm font-bold transition-colors {{ request()->routeIs('bookmarks.index') ? 'bg-white border-l-4 border-[#FF3B30] text-[#FF3B30]' : 'text-black hover:bg-gray-100 border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    Bookmark
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Navbar (Mobile) -->
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-black md:hidden">
                <x-application-logo class="h-6 w-auto" />
                <button class="text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </header>

            <!-- Top Navbar (Desktop) -->
            <header class="hidden md:flex h-16 items-center justify-between px-6 bg-white border-b border-black">
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('header', 'Library')
                </h1>
                
                <!-- Profile Dropdown -->
                <div class="relative" id="profileDropdown">
                    <button onclick="toggleProfileMenu()" class="flex items-center space-x-3 hover:opacity-80 transition focus:outline-none">
                        <!-- Avatar -->
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" 
                                 style="width:36px;height:36px;min-width:36px;min-height:36px;border-radius:50%;object-fit:cover;border:2px solid black;">
                        @else
                            <div style="width:36px;height:36px;min-width:36px;min-height:36px;border-radius:50%;background:#FF3B30;display:flex;align-items:center;justify-content:center;border:2px solid black;">
                                <span style="color:white;font-weight:bold;font-size:14px;">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileMenu" class="hidden absolute right-0 mt-2 w-56 bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] z-50">
                        <div class="px-4 py-3 border-b border-black">
                            <p class="text-sm font-bold text-black">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-black hover:bg-gray-100 transition">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Akun Saya
                        </a>
                        <div class="border-t border-black">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-4 py-3 text-sm text-[#FF3B30] font-bold hover:bg-red-50 transition">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleProfileMenu() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                document.getElementById('profileMenu').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
