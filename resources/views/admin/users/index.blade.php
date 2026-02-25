@extends('layouts.admin')

@section('header', 'Manajemen User')

@section('content')
    <!-- Readers Section -->
    <div class="mb-8">
        <h3 class="text-lg font-bold text-black mb-4">Daftar Pembaca</h3>
        <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-black uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 font-bold text-black">Nama</th>
                        <th class="px-6 py-3 font-bold text-black">Email</th>
                        <th class="px-6 py-3 font-bold text-black">Role</th>
                        <th class="px-6 py-3 text-right font-bold text-black">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($readers as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-black">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm font-bold uppercase">{{ $user->role }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-[#FF3B30] hover:text-black font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada pembaca.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Staff Section -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-black">Daftar Petugas (Staff)</h3>
            <a href="{{ route('users.create') }}" class="px-4 py-2 bg-[#FF3B30] text-black font-bold border border-black hover:bg-black hover:text-white transition-colors flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Petugas
            </a>
        </div>
        <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-black uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 font-bold text-black">Nama</th>
                        <th class="px-6 py-3 font-bold text-black">Email</th>
                        <th class="px-6 py-3 font-bold text-black">Role</th>
                        <th class="px-6 py-3 text-right font-bold text-black">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($staffs as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-black">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm font-bold uppercase">{{ $user->role }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus staff ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-[#FF3B30] hover:text-black font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada staff.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
