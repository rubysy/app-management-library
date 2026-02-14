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
                            <td class="px-6 py-4">
                                <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="text-sm px-2 py-1 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]">
                                        <option value="reader" selected>Reader</option>
                                        <option value="staff">Staff</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </form>
                            </td>
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
        <h3 class="text-lg font-bold text-black mb-4">Daftar Petugas (Staff)</h3>
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
                            <td class="px-6 py-4">
                                <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="text-sm px-2 py-1 border border-black focus:outline-none focus:ring-2 focus:ring-[#FF3B30]">
                                        <option value="reader">Reader</option>
                                        <option value="staff" selected>Staff</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </form>
                            </td>
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
