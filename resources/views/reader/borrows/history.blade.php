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

                        <!-- Status Badge & Action -->
                        <div class="flex items-center space-x-3">
                            @php
                                $receiptData = [
                                    "title" => $borrow->book->title,
                                    "author" => $borrow->book->author,
                                    "borrower_name" => $borrow->borrower_name,
                                    "borrower_address" => $borrow->borrower_address,
                                    "borrow_date" => \Carbon\Carbon::parse($borrow->borrow_date)->format("d M Y"),
                                    "return_date" => \Carbon\Carbon::parse($borrow->return_date)->format("d M Y")
                                ];
                            @endphp
                            <button type="button" 
                                onclick="showReceipt({{ json_encode($receiptData) }})"
                                class="bg-white border border-black text-black text-[10px] font-bold uppercase px-3 py-1 hover:bg-gray-100 transition whitespace-nowrap">
                                <svg class="w-3 h-3 inline-block -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Bukti
                            </button>

                            <div>
                                @if($borrow->status == 'active')
                                    <span class="inline-block bg-white border border-black text-black text-xs font-bold uppercase px-3 py-1">Dipinjam</span>
                                @elseif($borrow->status == 'returned')
                                    <span class="inline-block bg-black text-white text-xs font-bold uppercase px-3 py-1">Dikembalikan</span>
                                @else
                                    <span class="inline-block bg-[#FF3B30] text-black text-xs font-bold uppercase px-3 py-1">Terlambat</span>
                                @endif
                            </div>
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showReceipt(receipt) {
            Swal.fire({
                title: 'Bukti Peminjaman',
                html: `
                    <div class="text-left text-sm">
                        <p class="mb-2">Tunjukkan screenshot bukti peminjaman ini kepada petugas.</p>
                        <hr class="my-2 border-black">
                        <p><strong>Judul:</strong> ${receipt.title}</p>
                        <p><strong>Penulis:</strong> ${receipt.author}</p>
                        <p><strong>Nama Peminjam:</strong> ${receipt.borrower_name}</p>
                        <p><strong>Alamat:</strong> ${receipt.borrower_address}</p>
                        <p><strong>Tanggal Pinjam:</strong> ${receipt.borrow_date}</p>
                        <p><strong>Tenggat Kembali:</strong> ${receipt.return_date}</p>
                        <hr class="my-2 border-black">
                        <p class="text-xs text-gray-500">*Denda berlaku jika terlambat.</p>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#black',
                customClass: {
                    confirmButton: 'bg-black text-white px-4 py-2 border border-black font-bold uppercase tracking-widest'
                }
            });
        }
    </script>
@endsection
