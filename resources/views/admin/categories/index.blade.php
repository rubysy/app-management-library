@extends('layouts.admin')

@section('header', 'Manajemen Kategori')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">Kelola kategori buku perpustakaan</p>
        <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-[#FF3B30] text-black font-bold border border-black hover:bg-black hover:text-white transition-colors">
            + Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-black">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Jumlah Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-black uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-bold text-black">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-white border border-black text-black text-xs font-bold px-2 py-1">
                                {{ $category->books_count }} buku
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($category->description, 50) ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 font-bold mr-3">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Belum ada kategori. <a href="{{ route('categories.create') }}" class="text-[#FF3B30] font-bold">Tambah kategori pertama</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
