@extends('layouts.reader')

@section('header', 'Pesan & Peringatan')

@section('content')
    <div class="max-w-3xl mx-auto">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-lg font-bold text-black">Kotak Masuk ({{ $warnings->count() }})</h2>
            @if($unreadCount > 0)
                <form action="{{ route('warnings.markAllRead') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm text-gray-500 hover:text-black font-medium transition">
                        Tandai semua sudah dibaca
                    </button>
                </form>
            @endif
        </div>

        @forelse($warnings as $warning)
            <div class="mb-4 bg-white border-2 {{ $warning->is_read ? 'border-gray-200' : 'border-[#FF3B30]' }} shadow-[3px_3px_0px_0px_rgba(0,0,0,{{ $warning->is_read ? '0.1' : '1' }})] overflow-hidden" x-data="{ open: false }">
                <!-- Header -->
                <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition text-left">
                    <div class="flex items-center gap-3">
                        <!-- Mail Icon -->
                        <div class="flex-shrink-0">
                            @if(!$warning->is_read)
                                <div class="relative">
                                    <svg class="w-6 h-6 text-[#FF3B30]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                    </svg>
                                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-[#FF3B30] rounded-full animate-pulse"></span>
                                </div>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-black text-sm {{ !$warning->is_read ? '' : 'font-medium text-gray-700' }}">
                                    ⚠️ Peringatan Keterlambatan
                                </span>
                                @if(!$warning->is_read)
                                    <span class="bg-[#FF3B30] text-white text-[10px] font-bold px-1.5 py-0.5 uppercase">Baru</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ $warning->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Message Body -->
                <div x-show="open" x-collapse class="px-6 py-4 border-t {{ $warning->is_read ? 'border-gray-200' : 'border-[#FF3B30]' }} bg-gray-50">
                    <div class="whitespace-pre-line text-sm text-gray-800 leading-relaxed">{{ $warning->message }}</div>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-gray-400">
                            Dikirim pada {{ $warning->created_at->format('d M Y, H:i') }}
                        </span>
                        @if(!$warning->is_read)
                            <form action="{{ route('warnings.markRead', $warning->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs text-black font-bold border border-black px-3 py-1 hover:bg-black hover:text-white transition-colors">
                                    Tandai Sudah Dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 font-medium">Tidak ada pesan.</p>
                <p class="text-gray-400 text-sm mt-1">Kotak masuk Anda kosong.</p>
            </div>
        @endforelse
    </div>
@endsection
