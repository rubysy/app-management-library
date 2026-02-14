@extends('layouts.admin')

@section('header', 'Tambah Kategori Baru')

@section('content')
    <div class="max-w-2xl mx-auto bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="px-6 py-4 border-b border-black bg-gray-50">
            <h3 class="text-lg font-bold text-black">Detail Kategori</h3>
        </div>
        
        <form action="{{ route('categories.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]" 
                           placeholder="Contoh: Fiksi, Sains, Sejarah" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]"
                              placeholder="Deskripsi singkat tentang kategori ini">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('categories.index') }}" class="px-6 py-2 border border-black text-black font-bold hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#FF3B30] text-black font-bold border border-black hover:bg-black hover:text-white transition-colors">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
@endsection
