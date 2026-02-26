@extends('layouts.admin')

@section('header', 'Manajemen Peminjaman')

@section('content')
    @if(session('success'))
        
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700">
            {{ session('error') }}
        </div>
    @endif

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
                    <tr class="hover:bg-gray-50 transition {{ $borrow->status == 'pending' ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-bold text-black">{{ $borrow->book->title }}</div>
                            <div class="text-xs text-gray-500">ISBN: {{ $borrow->book->isbn }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-black">{{ $borrow->borrower_name ?? $borrow->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $borrow->user->email }}</div>
                            @if($borrow->borrower_address)
                                <div class="text-xs text-gray-400">{{ $borrow->borrower_address }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($borrow->status == 'pending')
                                <div>Diajukan: {{ $borrow->created_at->format('d M Y') }}</div>
                                <div class="text-gray-500">Durasi: {{ $borrow->borrow_days }} hari</div>
                            @else
                                <div>Pinjam: {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</div>
                                <div class="{{ ($borrow->status == 'active' && \Carbon\Carbon::parse($borrow->return_date)->isPast()) ? 'text-[#FF3B30] font-bold' : '' }}">
                                    Kembali: {{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($borrow->status == 'pending')
                                <span class="bg-yellow-100 border border-yellow-500 text-yellow-700 text-xs font-bold uppercase px-2 py-1">Menunggu</span>
                            @elseif($borrow->status == 'active' && \Carbon\Carbon::parse($borrow->return_date)->isPast())
                                <span class="bg-[#FF3B30] text-black text-xs font-bold uppercase px-2 py-1">Terlambat</span>
                            @elseif($borrow->status == 'active')
                                <span class="bg-white border border-black text-black text-xs font-bold uppercase px-2 py-1">Dipinjam</span>
                            @elseif($borrow->status == 'pending_return')
                                <span class="bg-orange-100 border border-orange-500 text-orange-700 text-xs font-bold uppercase px-2 py-1">Menunggu Pengembalian</span>
                            @elseif($borrow->status == 'returned')
                                <span class="bg-black text-white text-xs font-bold uppercase px-2 py-1">Dikembalikan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($borrow->status == 'pending')
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('borrows.approve', $borrow->id) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-500 text-black px-3 py-1 border border-black hover:bg-green-600 text-xs font-bold transition-colors">
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('borrows.reject', $borrow->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan peminjaman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-[#FF3B30] text-black px-3 py-1 border border-black hover:bg-black hover:text-white text-xs font-bold transition-colors">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            @elseif($borrow->status == 'active' || $borrow->status == 'pending_return')
                                <div class="flex items-center justify-end gap-2">
                                    @if(\Carbon\Carbon::parse($borrow->return_date)->isPast() && $borrow->status == 'active')
                                        <form action="{{ route('admin.warn', $borrow->user_id) }}" method="POST" onsubmit="return confirm('Kirim peringatan ke {{ $borrow->user->name }}?');">
                                            @csrf
                                            <button type="submit" class="bg-[#FF3B30] text-black px-3 py-1 border border-black hover:bg-black hover:text-white text-xs font-bold transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                Peringatkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
