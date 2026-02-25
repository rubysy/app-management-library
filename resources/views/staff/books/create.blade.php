@extends('layouts.staff')

@section('header', 'Tambah Buku Baru')

@section('content')
    <div class="max-w-4xl mx-auto bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="px-6 py-4 border-b border-black bg-gray-50">
            <h3 class="text-lg font-bold text-black">Detail Buku</h3>
        </div>
        
        <form action="{{ route('staff.books.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-black mb-2">Judul Buku *</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]" required>
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Author -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Penulis *</label>
                    <input type="text" name="author" value="{{ old('author') }}" class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]" required>
                    @error('author') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- ISBN -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">ISBN *</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]" required>
                    @error('isbn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Categories -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-black mb-2">Kategori</label>
                    <div class="border border-black p-4 max-h-48 overflow-y-auto">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-1">
                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" 
                                           {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 border-black text-[#FF3B30] focus:ring-[#FF3B30]">
                                    <span class="text-sm text-black">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if($categories->isEmpty())
                            <p class="text-gray-500 text-sm text-center py-2">Belum ada kategori.</p>
                        @endif
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Jumlah Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}" min="0" class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]">
                </div>

                <!-- Description -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-black mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]">{{ old('description') }}</textarea>
                </div>

                <!-- Cover -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-black mb-2">Cover Buku (Opsional)</label>
                    <input type="file" name="cover" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border file:border-black file:text-sm file:font-bold file:bg-white file:text-black hover:file:bg-black hover:file:text-white">
                    <p class="mt-1 text-xs text-gray-500">Format: PNG, JPG (max 2MB)</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('staff.books.index') }}" class="px-6 py-2 border border-black text-black font-bold hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#FF3B30] text-black font-bold border border-black hover:bg-black hover:text-white transition-colors">
                    Simpan Buku
                </button>
            </div>
        </form>
    </div>
@endsection
