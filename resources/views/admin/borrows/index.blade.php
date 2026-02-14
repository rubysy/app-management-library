@extends('layouts.admin')

@section('header', 'Manajemen Peminjaman')

@section('content')
    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-black uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 font-bold text-black">Buku</th>
                    <th class="px-6 py-3 font-bold text-black">Peminjam</th>
                    <th class="px-6 py-3 font-bold text-black">Tanggal</th>
                    <th class="px-6 py-3 font-bold text-black">Status</th>
                    <th class="px-6 py-3 text-right font-bold text-black">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($borrows as $borrow)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-black">{{ $borrow->book->title }}</div>
                            <div class="text-xs text-gray-500">ISBN: {{ $borrow->book->isbn }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-black">{{ $borrow->borrower_name ?? $borrow->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $borrow->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div>Pinjam: {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</div>
                            <div class="text-[#FF3B30] font-bold">Kembali: {{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($borrow->status == 'active')
                                <span class="bg-white border border-black text-black text-xs font-bold uppercase px-2 py-1">Dipinjam</span>
                            @elseif($borrow->status == 'returned')
                                <span class="bg-black text-white text-xs font-bold uppercase px-2 py-1">Dikembalikan</span>
                            @else
                                <span class="bg-[#FF3B30] text-black text-xs font-bold uppercase px-2 py-1">Terlambat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($borrow->status == 'active' || $borrow->status == 'late')
                                <form action="{{ route('borrows.return', $borrow->id) }}" method="POST" onsubmit="return confirm('Tandai sebagai dikembalikan?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-black text-white px-3 py-1 border border-black hover:bg-[#FF3B30] hover:text-black text-xs font-bold transition-colors">
                                        Sudah Kembali
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-black">
            {{ $borrows->links() }}
        </div>
    </div>
@endsection
