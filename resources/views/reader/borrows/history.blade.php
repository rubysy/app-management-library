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
                                    @if($borrow->borrow_date)
                                        Pinjam: {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}
                                        <span class="mx-2">•</span>
                                        Tenggat: {{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}
                                    @else
                                        Diajukan: {{ $borrow->created_at->format('d M Y') }}
                                        <span class="mx-2">•</span>
                                        Durasi: {{ $borrow->borrow_days }} hari
                                    @endif
                                </div>
                                @if($borrow->borrower_name)
                                    <div class="text-xs text-gray-500">
                                        Nama: {{ $borrow->borrower_name }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Status Badge & Actions -->
                        <div class="flex items-center space-x-2">
                            @if($borrow->status == 'pending')
                                {{-- PENDING: Waiting for approval --}}
                                <span class="inline-block bg-yellow-100 border border-yellow-500 text-yellow-700 text-xs font-bold uppercase px-3 py-1">Menunggu</span>

                            @elseif($borrow->status == 'active')
                                {{-- ACTIVE: Approved, show receipt + return request button --}}
                                @php
                                    $receiptData = [
                                        "title" => $borrow->book->title,
                                        "author" => $borrow->book->author,
                                        "isbn" => $borrow->book->isbn,
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
                                <button type="button"
                                    onclick="requestReturn({{ $borrow->id }})"
                                    class="bg-[#FF3B30] border border-black text-black text-[10px] font-bold uppercase px-3 py-1 hover:bg-black hover:text-white transition whitespace-nowrap">
                                    Ajukan Pengembalian
                                </button>
                                <span class="inline-block bg-white border border-black text-black text-xs font-bold uppercase px-3 py-1">Dipinjam</span>

                            @elseif($borrow->status == 'pending_return')
                                {{-- PENDING RETURN: Waiting for return approval --}}
                                @php
                                    $receiptData = [
                                        "title" => $borrow->book->title,
                                        "author" => $borrow->book->author,
                                        "isbn" => $borrow->book->isbn,
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
                                <span class="inline-block bg-orange-100 border border-orange-500 text-orange-700 text-xs font-bold uppercase px-3 py-1">Menunggu Pengembalian</span>

                            @elseif($borrow->status == 'returned')
                                {{-- RETURNED: Show receipt + rate button --}}
                                @php
                                    $receiptData = [
                                        "title" => $borrow->book->title,
                                        "author" => $borrow->book->author,
                                        "isbn" => $borrow->book->isbn,
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
                                <a href="{{ route('dashboard') }}" class="bg-green-500 border border-black text-black text-[10px] font-bold uppercase px-3 py-1 hover:bg-black hover:text-white transition whitespace-nowrap inline-block">
                                    Beri Rating
                                </a>
                                <span class="inline-block bg-black text-white text-xs font-bold uppercase px-3 py-1">Dikembalikan</span>
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
                        <p><strong>ISBN:</strong> ${receipt.isbn}</p>
                        <p><strong>Nama Peminjam:</strong> ${receipt.borrower_name}</p>
                        <p><strong>Alamat:</strong> ${receipt.borrower_address}</p>
                        <p><strong>Tanggal Pinjam:</strong> ${receipt.borrow_date}</p>
                        <p><strong>Tenggat Kembali:</strong> ${receipt.return_date}</p>
                        <hr class="my-2 border-black">
                        <p class="text-xs text-gray-500">*Denda berlaku jika terlambat.</p>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#000000'
            });
        }

        function requestReturn(borrowId) {
            Swal.fire({
                title: 'Ajukan Pengembalian?',
                html: `
                    <div class="text-left text-sm">
                        <p>Anda akan mengajukan pengembalian buku ini.</p>
                        <hr class="my-2">
                        <p class="text-xs text-gray-500">Setelah pengajuan dikirim, mohon untuk datang ke perpustakaan YouLibrary untuk menyerahkan buku fisiknya sambil menunggu persetujuan petugas.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ajukan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#FF3B30',
                cancelButtonColor: '#000000'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/borrows/${borrowId}/request-return`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrf);

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'PATCH';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    @if(session('return_requested'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Pengajuan Pengembalian Berhasil!',
                    html: `
                        <div class="text-left text-sm">
                            <p class="mb-2">Pengajuan pengembalian buku Anda telah dikirim.</p>
                            <hr class="my-2">
                            <p>Mohon untuk <strong>datang ke perpustakaan YouLibrary</strong> untuk menyerahkan buku fisiknya.</p>
                            <p class="mt-2">Status akan berubah setelah petugas menyetujui pengembalian.</p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'OK, Mengerti',
                    confirmButtonColor: '#FF3B30'
                });
            });
        </script>
    @endif
@endsection
