@extends('layouts.staff')

@section('header', 'Laporan')

@section('content')
    <div class="mb-6 flex justify-end">
        <button onclick="window.print()" class="px-4 py-2 bg-[#FF3B30] text-black font-bold border border-black hover:bg-black hover:text-white transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Cetak / Download PDF
        </button>
    </div>

    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] p-6" id="printableArea">
        <div class="text-center mb-8 pb-6 border-b border-black">
            <h2 class="text-2xl font-black text-black uppercase tracking-wider">Laporan Perpustakaan YouLibrary</h2>
            <p class="text-gray-600 mt-2">Dibuat pada {{ now()->format('d M Y, H:i') }}</p>
        </div>

        <div class="mb-8 grid grid-cols-2 gap-6">
            <div class="p-6 bg-white border border-black">
                <p class="text-sm font-bold text-gray-600 uppercase">Total Transaksi Peminjaman</p>
                <p class="text-3xl font-black text-black mt-2">{{ $borrows->count() }}</p>
            </div>
            <div class="p-6 bg-white border border-black">
                <p class="text-sm font-bold text-gray-600 uppercase">Buku Sedang Dipinjam</p>
                <p class="text-3xl font-black text-[#FF3B30] mt-2">{{ $borrows->where('status', 'active')->count() }}</p>
            </div>
        </div>

        <div class="border border-black overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="px-4 py-3 font-bold text-sm uppercase">Tanggal</th>
                        <th class="px-4 py-3 font-bold text-sm uppercase">Peminjam</th>
                        <th class="px-4 py-3 font-bold text-sm uppercase">Judul Buku</th>
                        <th class="px-4 py-3 font-bold text-sm uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($borrows as $borrow)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-black">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm text-black font-bold">{{ $borrow->borrower_name ?? $borrow->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $borrow->book->title }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($borrow->status == 'active')
                                    <span class="bg-white border border-black text-black text-xs font-bold uppercase px-2 py-1">Dipinjam</span>
                                @elseif($borrow->status == 'returned')
                                    <span class="bg-black text-white text-xs font-bold uppercase px-2 py-1">Dikembalikan</span>
                                @else
                                    <span class="bg-[#FF3B30] text-black text-xs font-bold uppercase px-2 py-1">Terlambat</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endsection
