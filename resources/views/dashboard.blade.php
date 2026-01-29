@extends('layouts.reader')

@section('header', 'Koleksi Buku')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Search Bar -->
        <div class="mb-6">
            <form action="{{ route('dashboard') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Cari buku berdasarkan judul, penulis, atau genre..." value="{{ request('search') }}" 
                       class="w-full rounded-none border-black bg-white text-black focus:border-[#FF3B30] focus:ring-[#FF3B30]">
                <button type="submit" class="px-6 py-2 bg-[#FF3B30] text-black border border-black font-bold uppercase tracking-widest hover:bg-black hover:text-white transition">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Book Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($books as $book)
                <div class="book-card bg-white rounded-none border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden hover:translate-x-1 hover:-translate-y-1 transition-transform duration-300 cursor-pointer"
                     data-book='@json($book)'
                     onclick="openBookModal(this)">
                    <div class="h-48 bg-gray-200 w-full object-cover">
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-500 truncate">{{ $book->author }}</p>
                        <div class="mt-2">
                            <span class="border border-black text-black text-[10px] uppercase font-bold px-2 py-0.5">{{ $book->genre }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500">
                    No books available at the moment.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Book Detail Modal -->
    <div id="bookModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop (click to close) -->
        <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        
        <!-- Modal Content Container (scrollable) -->
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-none border-2 border-black overflow-hidden shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transform transition-all max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <button id="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 z-10">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-3">
                        <div id="modalImage" class="h-64 md:h-auto md:min-h-[300px] bg-gray-200"></div>
                        <div class="p-6 md:col-span-2">
                            <div class="mb-4">
                                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900"></h2>
                                <p id="modalAuthor" class="text-sm text-gray-500"></p>
                            </div>
                            
                            <dl class="grid grid-cols-2 gap-x-4 gap-y-4 text-sm mb-6">
                                <div>
                                    <dt class="text-gray-500">ISBN</dt>
                                    <dd id="modalIsbn" class="font-medium text-gray-900"></dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">Genre</dt>
                                    <dd id="modalGenre" class="font-medium text-gray-900"></dd>
                                </div>
                                <div class="col-span-2">
                                    <dt class="text-gray-500">Description</dt>
                                    <dd id="modalDescription" class="mt-1 text-gray-900 max-h-32 overflow-y-auto"></dd>
                                </div>
                            </dl>

                            <div class="flex space-x-3">
                                <!-- Borrow Button (Opens Confirmation Modal) -->
                                <button type="button" id="openBorrowFormBtn"
                                    class="w-full bg-[#FF3B30] border border-black rounded-none py-3 px-4 flex items-center justify-center text-base font-bold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 cursor-pointer">
                                    Pinjam Buku
                                </button>

                                <!-- Bookmark Form -->
                                <form method="POST" action="{{ route('bookmarks.store') }}" class="w-auto">
                                    @csrf
                                    <input type="hidden" name="book_id" id="bookmarkBookId">
                                    <button type="submit" class="bg-white border border-black rounded-none py-3 px-6 flex items-center justify-center text-base font-bold text-black uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                                        Bookmark
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Confirmation Modal -->
    <div id="borrowFormModal" class="fixed inset-0 z-[60] overflow-y-auto hidden">
        <div id="borrowFormBackdrop" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-none border-2 border-black overflow-hidden shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] transform transition-all max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-black pb-2">Konfirmasi Peminjaman</h3>
                    
                    <form method="POST" action="{{ route('borrows.store') }}" id="borrowForm">
                        @csrf
                        <input type="hidden" name="book_id" id="borrowFormBookId">
                        
                        <!-- Book Info Summary -->
                        <div class="mb-4 p-3 bg-gray-50 border border-black">
                            <p class="font-bold text-sm" id="borrowFormBookTitle"></p>
                            <p class="text-xs text-gray-500" id="borrowFormBookAuthor"></p>
                        </div>

                        <!-- Borrower Name -->
                        <div class="mb-4">
                            <label for="borrower_name" class="block text-sm font-bold text-black mb-1">Nama Peminjam</label>
                            <input type="text" name="borrower_name" id="borrower_name" value="{{ auth()->user()->name }}" required
                                   class="w-full rounded-none border-black bg-white text-black focus:border-[#FF3B30] focus:ring-[#FF3B30]">
                        </div>

                        <!-- Borrower Address -->
                        <div class="mb-4">
                            <label for="borrower_address" class="block text-sm font-bold text-black mb-1">Alamat</label>
                            <textarea name="borrower_address" id="borrower_address" rows="2" required
                                      class="w-full rounded-none border-black bg-white text-black focus:border-[#FF3B30] focus:ring-[#FF3B30]" placeholder="Masukkan alamat lengkap..."></textarea>
                        </div>

                        <!-- Borrow Duration -->
                        <div class="mb-6">
                            <label for="borrow_days" class="block text-sm font-bold text-black mb-1">Durasi Pinjam</label>
                            <select name="borrow_days" id="borrow_days" required
                                    class="w-full rounded-none border-black bg-white text-black focus:border-[#FF3B30] focus:ring-[#FF3B30]">
                                <option value="3">3 Hari</option>
                                <option value="7" selected>7 Hari (Standar)</option>
                                <option value="14">14 Hari</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <button type="submit"
                                    class="flex-1 bg-[#FF3B30] border border-black rounded-none py-3 px-4 flex items-center justify-center text-base font-bold text-black uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300">
                                Konfirmasi
                            </button>
                            <button type="button" onclick="closeBorrowForm()"
                                    class="flex-1 bg-white border border-black rounded-none py-3 px-4 flex items-center justify-center text-base font-bold text-black uppercase tracking-widest hover:bg-gray-100 transition-all duration-300">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentBook = null;

        // Book Modal Functions
        function openBookModal(element) {
            currentBook = JSON.parse(element.getAttribute('data-book'));
            
            document.getElementById('modalTitle').textContent = currentBook.title;
            document.getElementById('modalAuthor').textContent = 'by ' + currentBook.author;
            document.getElementById('modalIsbn').textContent = currentBook.isbn || '-';
            document.getElementById('modalGenre').textContent = currentBook.genre || '-';
            document.getElementById('modalDescription').textContent = currentBook.description || 'No description available.';
            document.getElementById('bookmarkBookId').value = currentBook.id;

            const imageContainer = document.getElementById('modalImage');
            if (currentBook.cover_path) {
                imageContainer.innerHTML = '<img src="/storage/' + currentBook.cover_path + '" class="w-full h-full object-cover">';
            } else {
                imageContainer.innerHTML = '<div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">No Image</div>';
            }

            document.getElementById('bookModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBookModal() {
            document.getElementById('bookModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Borrow Form Modal Functions
        function openBorrowForm() {
            if (!currentBook) return;
            
            document.getElementById('borrowFormBookId').value = currentBook.id;
            document.getElementById('borrowFormBookTitle').textContent = currentBook.title;
            document.getElementById('borrowFormBookAuthor').textContent = 'by ' + currentBook.author;
            
            // Close detail modal first, then show borrow form
            document.getElementById('bookModal').classList.add('hidden');
            document.getElementById('borrowFormModal').classList.remove('hidden');
        }

        function closeBorrowForm() {
            document.getElementById('borrowFormModal').classList.add('hidden');
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modalBackdrop').addEventListener('click', closeBookModal);
            document.getElementById('closeModal').addEventListener('click', closeBookModal);
            document.getElementById('borrowFormBackdrop').addEventListener('click', closeBorrowForm);
            
            // Borrow button click
            document.getElementById('openBorrowFormBtn').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openBorrowForm();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeBorrowForm();
                    closeBookModal();
                }
            });

            @if(session('borrow_receipt'))
                const receipt = @json(session('borrow_receipt'));
                Swal.fire({
                    title: 'Peminjaman Berhasil!',
                    icon: 'success',
                    html: `
                        <div class="text-left text-sm">
                            <p class="mb-2">Silahkan screenshot tanda peminjaman ini lalu serahkan ke petugas.</p>
                            <hr class="my-2">
                            <p><strong>Judul:</strong> ${receipt.title}</p>
                            <p><strong>Author:</strong> ${receipt.author}</p>
                            <p><strong>Nama Peminjam:</strong> ${receipt.borrower_name}</p>
                            <p><strong>Alamat:</strong> ${receipt.borrower_address}</p>
                            <p><strong>Tanggal Pinjam:</strong> ${receipt.borrow_date}</p>
                            <p><strong>Tenggat Kembali:</strong> ${receipt.return_date}</p>
                            <hr class="my-2">
                            <p class="text-xs text-gray-500">*Denda berlaku jika terlambat.</p>
                        </div>
                    `,
                    confirmButtonText: 'OK, Saya Mengerti',
                    confirmButtonColor: '#FF3B30'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endsection
