@extends('layouts.reader')

@section('header', 'Koleksi Buku')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Search Bar + Filter -->
        <div class="mb-6">
            <form action="{{ route('dashboard') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Cari buku berdasarkan judul, penulis, atau genre..." value="{{ request('search') }}" 
                       class="w-full rounded-none border-black bg-white text-black focus:border-[#FF3B30] focus:ring-[#FF3B30]">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <!-- Filter Button -->
                <div class="relative" id="filterDropdown">
                    <button type="button" onclick="toggleFilter()" class="px-4 py-2 bg-white border border-black font-bold hover:bg-gray-100 transition flex items-center gap-2 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span class="text-sm hidden sm:inline">Filter</span>
                        @if(request('category'))
                            <span style="width:8px;height:8px;border-radius:50%;background:#FF3B30;position:absolute;top:6px;right:6px;"></span>
                        @endif
                    </button>

                    <!-- Filter Dropdown -->
                    <div id="filterMenu" class="hidden absolute right-0 mt-2 w-56 bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] z-50 max-h-72 overflow-y-auto">
                        <a href="{{ route('dashboard', request()->only('search')) }}" 
                           class="block px-4 py-2.5 text-sm font-bold transition {{ !request('category') ? 'bg-black text-white' : 'text-black hover:bg-gray-100' }}">
                            Semua Kategori
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('dashboard', array_merge(request()->only('search'), ['category' => $cat->id])) }}" 
                               class="block px-4 py-2.5 text-sm transition border-t border-gray-200 {{ request('category') == $cat->id ? 'bg-black text-white font-bold' : 'text-black hover:bg-gray-100' }}">
                                {{ $cat->name }} <span class="text-xs opacity-60">({{ $cat->books_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="px-6 py-2 bg-[#FF3B30] text-black border border-black font-bold uppercase tracking-widest hover:bg-black hover:text-white transition">
                    Cari
                </button>
                @if(request('search') || request('category'))
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white text-black border border-black font-bold hover:bg-gray-100 transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>

            @if(request('category'))
                @php $activeCat = $categories->firstWhere('id', request('category')); @endphp
                @if($activeCat)
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-xs text-gray-500">Filter aktif:</span>
                        <span class="px-3 py-1 bg-black text-white text-xs font-bold border border-black">
                            {{ $activeCat->name }}
                        </span>
                        <a href="{{ route('dashboard', request()->only('search')) }}" class="text-[#FF3B30] hover:text-black text-xs font-bold">✕ Hapus</a>
                    </div>
                @endif
            @endif
        </div>

        <!-- Book Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($books as $book)
                <div class="book-card bg-white rounded-none border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden hover:translate-x-1 hover:-translate-y-1 transition-transform duration-300 cursor-pointer"
                     data-book='@json($book->load("categories"))'
                     data-avg-rating="{{ number_format($book->averageRating(), 1) }}"
                     data-user-rating="{{ $userRatings[$book->id] ?? 0 }}"
                     data-rating-count="{{ $book->ratings->count() }}"
                     onclick="openBookModal(this)">
                    <div class="h-48 bg-gray-200 w-full object-cover">
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-bold text-black truncate">{{ $book->title }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $book->author }}</p>
                        <!-- Average Rating Stars -->
                        <div class="mt-1 flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= round($book->averageRating()) ? 'text-[#FF3B30]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-[10px] text-gray-500">({{ $book->ratings->count() }})</span>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-1">
                            @foreach($book->categories->take(2) as $cat)
                                <span class="border border-black text-black text-[9px] uppercase font-bold px-1.5 py-0.5">{{ $cat->name }}</span>
                            @endforeach
                            @if($book->categories->count() > 2)
                                <span class="text-[9px] text-gray-500">+{{ $book->categories->count() - 2 }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-500 font-bold">Belum ada buku yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Book Detail Modal -->
    <div id="bookModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        
        <!-- Modal Content Container -->
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
                                <h2 id="modalTitle" class="text-2xl font-bold text-black"></h2>
                                <p id="modalAuthor" class="text-sm text-gray-500"></p>
                            </div>
                            
                            <!-- Average Rating Display -->
                            <div class="mb-4 flex items-center space-x-2">
                                <div id="modalAvgStars" class="flex"></div>
                                <span id="modalAvgRating" class="text-sm font-bold text-black"></span>
                                <span id="modalRatingCount" class="text-xs text-gray-500"></span>
                            </div>
                            
                            <dl class="grid grid-cols-2 gap-x-4 gap-y-4 text-sm mb-4">
                                <div>
                                    <dt class="text-gray-500">ISBN</dt>
                                    <dd id="modalIsbn" class="font-bold text-black"></dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">Kategori</dt>
                                    <dd id="modalCategories" class="font-bold text-black"></dd>
                                </div>
                                <div class="col-span-2">
                                    <dt class="text-gray-500">Deskripsi</dt>
                                    <dd id="modalDescription" class="mt-1 text-gray-700 max-h-24 overflow-y-auto"></dd>
                                </div>
                            </dl>

                            <!-- Rating Form -->
                            <div class="mb-4 p-3 border border-black bg-gray-50">
                                <p class="text-xs font-bold text-gray-600 mb-2">BERI RATING:</p>
                                <form method="POST" action="{{ route('ratings.store') }}" class="flex items-center space-x-3">
                                    @csrf
                                    <input type="hidden" name="book_id" id="ratingBookId">
                                    <div id="ratingStars" class="flex space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" onclick="setRating({{ $i }})" class="rating-star text-gray-300 hover:text-[#FF3B30] transition" data-value="{{ $i }}">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="ratingValue" value="0">
                                    <button type="submit" id="submitRating" class="px-3 py-1 bg-[#FF3B30] text-black text-xs font-bold border border-black hover:bg-black hover:text-white transition hidden">
                                        Kirim
                                    </button>
                                </form>
                            </div>

                            <div class="flex space-x-3">
                                <!-- Borrow Button -->
                                <button type="button" id="openBorrowFormBtn"
                                    class="w-full bg-[#FF3B30] border border-black rounded-none py-3 px-4 flex items-center justify-center text-base font-bold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none transition-all duration-300 cursor-pointer">
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
                    <h3 class="text-xl font-bold text-black mb-4 border-b border-black pb-2">Konfirmasi Peminjaman</h3>
                    
                    <form method="POST" action="{{ route('borrows.store') }}" id="borrowForm">
                        @csrf
                        <input type="hidden" name="book_id" id="borrowFormBookId">
                        
                        <!-- Book Info -->
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
        let selectedRating = 0;

        // Filter Dropdown
        function toggleFilter() {
            document.getElementById('filterMenu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const fd = document.getElementById('filterDropdown');
            if (fd && !fd.contains(e.target)) {
                document.getElementById('filterMenu').classList.add('hidden');
            }
        });

        // Rating Functions
        function setRating(value) {
            selectedRating = value;
            document.getElementById('ratingValue').value = value;
            document.getElementById('submitRating').classList.remove('hidden');
            
            document.querySelectorAll('.rating-star').forEach(star => {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= value) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-[#FF3B30]');
                } else {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-[#FF3B30]');
                }
            });
        }

        // Book Modal Functions
        function openBookModal(element) {
            currentBook = JSON.parse(element.getAttribute('data-book'));
            const avgRating = parseFloat(element.getAttribute('data-avg-rating'));
            const userRating = parseInt(element.getAttribute('data-user-rating'));
            const ratingCount = parseInt(element.getAttribute('data-rating-count'));
            
            document.getElementById('modalTitle').textContent = currentBook.title;
            document.getElementById('modalAuthor').textContent = 'oleh ' + currentBook.author;
            document.getElementById('modalIsbn').textContent = currentBook.isbn || '-';
            document.getElementById('modalDescription').textContent = currentBook.description || 'Tidak ada deskripsi.';
            document.getElementById('bookmarkBookId').value = currentBook.id;
            document.getElementById('ratingBookId').value = currentBook.id;

            // Show categories
            const cats = currentBook.categories || [];
            document.getElementById('modalCategories').textContent = cats.map(c => c.name).join(', ') || '-';

            // Average rating stars
            let avgStarsHtml = '';
            for (let i = 1; i <= 5; i++) {
                avgStarsHtml += `<svg class="w-4 h-4 ${i <= Math.round(avgRating) ? 'text-[#FF3B30]' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
            }
            document.getElementById('modalAvgStars').innerHTML = avgStarsHtml;
            document.getElementById('modalAvgRating').textContent = avgRating > 0 ? avgRating.toFixed(1) : '-';
            document.getElementById('modalRatingCount').textContent = `(${ratingCount} ulasan)`;

            // Set user's existing rating
            if (userRating > 0) {
                setRating(userRating);
            } else {
                selectedRating = 0;
                document.getElementById('ratingValue').value = 0;
                document.getElementById('submitRating').classList.add('hidden');
                document.querySelectorAll('.rating-star').forEach(star => {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-[#FF3B30]');
                });
            }

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
            document.getElementById('borrowFormBookAuthor').textContent = 'oleh ' + currentBook.author;
            
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
                            <p><strong>Penulis:</strong> ${receipt.author}</p>
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

            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK',
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
