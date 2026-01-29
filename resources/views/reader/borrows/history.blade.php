@extends('layouts.reader')

@section('header', 'Riwayat Peminjaman')

@section('content')
    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <ul class="divide-y divide-black">
            @forelse($borrows as $borrow)
                <li class="p-4 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <!-- Book Cover Thumb -->
                            <div class="h-16 w-12 bg-gray-200 border border-black object-cover overflow-hidden">
                                @if($borrow->book->cover_path)
                                    <img src="{{ asset('storage/' . $borrow->book->cover_path) }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            
                            <!-- Info -->
                            <div>
                                <h4 class="text-lg font-bold text-black">{{ $borrow->book->title }}</h4>
                                <div class="text-sm text-gray-600">
                                    Pinjam: {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}
                                    <span class="mx-2">•</span>
                                    Tenggat: {{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}
                                </div>
                                @if($borrow->borrower_name)
                                    <div class="text-xs text-gray-500">
                                        Nama: {{ $borrow->borrower_name }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @if($borrow->status == 'active')
                                <span class="bg-white border border-black text-black text-xs font-bold uppercase px-3 py-1">Dipinjam</span>
                            @elseif($borrow->status == 'returned')
                                <span class="bg-black text-white text-xs font-bold uppercase px-3 py-1">Dikembalikan</span>
                            @else
                                <span class="bg-[#FF3B30] text-black text-xs font-bold uppercase px-3 py-1">Terlambat</span>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-12 text-center text-gray-500">
                    Belum ada riwayat peminjaman.
                    <br>
                    <a href="{{ route('dashboard') }}" class="text-[#FF3B30] hover:underline mt-2 inline-block font-bold">Mulai meminjam buku</a>
                </li>
            @endforelse
        </ul>
    </div>
@endsection
