@extends('layouts.staff')

@section('header', 'Kelola Buku')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div class="relative w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" class="w-full pl-10 pr-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]" placeholder="Cari buku...">
        </div>
        <a href="{{ route('staff.books.create') }}" class="px-4 py-2 bg-[#FF3B30] text-black font-bold border border-black hover:bg-black hover:text-white transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Buku
        </a>
    </div>

    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-black uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 font-bold text-black">Buku</th>
                    <th class="px-6 py-3 font-bold text-black">Info</th>
                    <th class="px-6 py-3 font-bold text-black">Stok</th>
                    <th class="px-6 py-3 text-right font-bold text-black">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($books as $book)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($book->cover_path)
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="h-12 w-8 object-cover border border-black mr-4">
                                @else
                                    <div class="h-12 w-8 bg-gray-200 border border-black mr-4 flex items-center justify-center text-gray-400">?</div>
                                @endif
                                <div>
                                    <div class="font-bold text-black">{{ $book->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $book->author }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">
                                <span class="block text-xs text-gray-500">ISBN: {{ $book->isbn }}</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($book->categories as $cat)
                                        <span class="bg-white border border-black text-black text-[10px] font-bold px-2 py-0.5">
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold {{ $book->stock > 0 ? 'text-black' : 'text-[#FF3B30]' }}">
                                {{ $book->stock }} tersedia
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('staff.books.edit', $book) }}" class="text-black hover:text-[#FF3B30] font-bold">Edit</a>
                            <form action="{{ route('staff.books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#FF3B30] hover:text-black font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            Belum ada buku. <a href="{{ route('staff.books.create') }}" class="text-[#FF3B30] font-bold">Tambah buku pertama!</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-black">
            {{ $books->links() }}
        </div>
    </div>
@endsection
