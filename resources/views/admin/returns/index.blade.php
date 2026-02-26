@extends('layouts.admin')

@section('header', 'Pengajuan Pengembalian')

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
                    <tr class="hover:bg-gray-50 transition bg-orange-50">
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
                            <div>Tenggat: {{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-orange-100 border border-orange-500 text-orange-700 text-xs font-bold uppercase px-2 py-1">Menunggu Pengembalian</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('returns.approve', $borrow->id) }}" method="POST" onsubmit="return confirm('Setujui pengembalian buku ini?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 text-black px-3 py-1 border border-black hover:bg-green-600 text-xs font-bold transition-colors">
                                    Setujui Pengembalian
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada pengajuan pengembalian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
