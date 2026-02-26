@extends('layouts.admin')

@section('header', 'Detail Buku')

@section('content')
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="px-4 py-2 bg-white text-black font-bold border border-black hover:bg-gray-100 transition-colors inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-black text-black text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Book Detail Card -->
    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4">
            <!-- Cover -->
            <div class="bg-gray-200 md:col-span-1 flex items-center justify-center p-6">
                @if($book->cover_path)
                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="w-full max-w-[200px] object-cover border border-black">
                @else
                    <div class="w-full max-w-[200px] h-64 bg-gray-100 border border-black flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="md:col-span-3 p-6">
                <h2 class="text-2xl font-bold text-black mb-1">{{ $book->title }}</h2>
                <p class="text-sm text-gray-500 mb-4">oleh {{ $book->author }}</p>

                <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm mb-4">
                    <div>
                        <dt class="text-gray-500 uppercase text-xs font-bold">ISBN</dt>
                        <dd class="font-bold text-black">{{ $book->isbn }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 uppercase text-xs font-bold">Stok</dt>
                        <dd class="font-bold {{ $book->stock > 0 ? 'text-black' : 'text-[#FF3B30]' }}">{{ $book->stock }} tersedia</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 uppercase text-xs font-bold">Kategori</dt>
                        <dd class="flex flex-wrap gap-1 mt-1">
                            @forelse($book->categories as $cat)
                                <span class="bg-white border border-black text-black text-[10px] font-bold px-1.5 py-0.5 uppercase">{{ $cat->name }}</span>
                            @empty
                                <span class="text-gray-400 text-xs">-</span>
                            @endforelse
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 uppercase text-xs font-bold">Rating Rata-rata</dt>
                        <dd class="flex items-center space-x-1 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($book->averageRating()) ? 'text-[#FF3B30]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-sm font-bold text-black ml-1">{{ number_format($book->averageRating(), 1) }}</span>
                            <span class="text-xs text-gray-500">({{ $book->ratings->count() }} ulasan)</span>
                        </dd>
                    </div>
                </dl>

                @if($book->description)
                    <div>
                        <dt class="text-gray-500 uppercase text-xs font-bold mb-1">Deskripsi</dt>
                        <dd class="text-sm text-gray-700">{{ $book->description }}</dd>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="px-6 py-4 border-b border-black flex justify-between items-center">
            <h3 class="text-lg font-bold text-black">Daftar Ulasan ({{ $book->ratings->count() }})</h3>
        </div>

        @if($book->ratings->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($book->ratings->sortByDesc('created_at') as $rating)
                    <li class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-1">
                                    <!-- User Avatar -->
                                    <div style="width:28px;height:28px;min-width:28px;border-radius:50%;background:#FF3B30;display:flex;align-items:center;justify-content:center;border:2px solid black;">
                                        <span style="color:white;font-weight:bold;font-size:11px;">{{ substr($rating->user->name ?? '?', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-bold text-black text-sm">{{ $rating->user->name ?? 'Unknown' }}</span>
                                        <span class="text-xs text-gray-400 ml-2">{{ $rating->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Stars -->
                                <div class="flex items-center space-x-0.5 mb-1 ml-10">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $rating->rating ? 'text-[#FF3B30]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="text-xs text-gray-500 ml-1">({{ $rating->rating }}/5)</span>
                                </div>

                                @if($rating->review)
                                    <p class="text-sm text-gray-600 ml-10">{{ $rating->review }}</p>
                                @endif
                            </div>

                            <!-- Delete Button -->
                            <form action="{{ route('ratings.destroy', $rating->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#FF3B30] hover:text-black font-bold text-xs uppercase px-2 py-1 border border-transparent hover:border-black transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                Belum ada ulasan untuk buku ini.
            </div>
        @endif
    </div>
@endsection
