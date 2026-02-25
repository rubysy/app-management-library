@extends('layouts.admin')

@section('header', 'Tambah Petugas Baru')

@section('content')
    <div class="max-w-xl">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-white text-black font-bold border border-black hover:bg-gray-100 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="bg-white border text-black border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] p-8">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block font-bold mb-2 uppercase text-sm tracking-widest">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full border-black bg-white p-3 focus:outline-none focus:ring-2 focus:ring-[#FF3B30] focus:border-transparent transition-all">
                    @error('name')
                        <p class="text-[#FF3B30] text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-bold mb-2 uppercase text-sm tracking-widest">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full border-black bg-white p-3 focus:outline-none focus:ring-2 focus:ring-[#FF3B30] focus:border-transparent transition-all">
                    @error('email')
                        <p class="text-[#FF3B30] text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block font-bold mb-2 uppercase text-sm tracking-widest">Kata Sandi</label>
                    <input type="password" name="password" id="password" required
                           class="w-full border-black bg-white p-3 focus:outline-none focus:ring-2 focus:ring-[#FF3B30] focus:border-transparent transition-all">
                    @error('password')
                        <p class="text-[#FF3B30] text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block font-bold mb-2 uppercase text-sm tracking-widest">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full border-black bg-white p-3 focus:outline-none focus:ring-2 focus:ring-[#FF3B30] focus:border-transparent transition-all">
                </div>

                <button type="submit" class="w-full bg-[#FF3B30] text-black border border-black font-bold uppercase tracking-widest py-3 hover:bg-black hover:text-white transition-all duration-300">
                    Buat Akun Petugas
                </button>
            </form>
        </div>
    </div>
@endsection
