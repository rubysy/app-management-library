@extends('layouts.admin')

@section('header', 'Edit Kategori')

@section('content')
    <div class="max-w-2xl mx-auto bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="px-6 py-4 border-b border-black bg-gray-50">
            <h3 class="text-lg font-bold text-black">Edit Kategori: {{ $category->name }}</h3>
        </div>
        
        <form action="{{ route('categories.update', $category) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                           class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]" 
                           placeholder="Contoh: Fiksi, Sains, Sejarah" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-2 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]"
                              placeholder="Deskripsi singkat tentang kategori ini">{{ old('description', $category->description) }}</textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Info -->
                <div class="p-4 bg-gray-50 border border-gray-200">
                    <p class="text-sm text-gray-600">
                        <strong>Slug:</strong> {{ $category->slug }}<br>
                        <strong>Jumlah Buku:</strong> {{ $category->books()->count() }} buku
                    </p>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('categories.index') }}" class="px-6 py-2 border border-black text-black font-bold hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-[#FF3B30] text-black font-bold border border-black hover:bg-black hover:text-white transition-colors">
                    Update Kategori
                </button>
            </div>
        </form>
    </div>
@endsection
