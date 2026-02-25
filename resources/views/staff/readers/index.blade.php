@extends('layouts.staff')

@section('header', 'Manajemen Akun Pembaca')

@section('content')
    <div class="mb-6">
        <p class="text-gray-600 text-sm">Daftar semua akun pembaca yang terdaftar di YouLibrary. Anda dapat melihat detail akun pembaca.</p>
    </div>

    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="px-6 py-4 border-b border-black flex items-center justify-between">
            <h3 class="text-lg font-bold text-black">Akun Pembaca ({{ $readers->count() }})</h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-black uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 font-bold text-black">No</th>
                    <th class="px-6 py-3 font-bold text-black">Nama</th>
                    <th class="px-6 py-3 font-bold text-black">Email</th>
                    <th class="px-6 py-3 font-bold text-black">Terdaftar Pada</th>
                    <th class="px-6 py-3 font-bold text-black">Lama Terdaftar</th>
                    <th class="px-6 py-3 font-bold text-black text-center">Total Pinjam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($readers as $index => $reader)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-black text-white flex items-center justify-center font-bold text-sm" style="border-radius: 50%;">
                                    {{ strtoupper(substr($reader->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-black">{{ $reader->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $reader->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">{{ $reader->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $reader->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $reader->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-white border border-black text-black text-xs font-bold px-2 py-1">
                                {{ $reader->borrows_count ?? 0 }} buku
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Belum ada akun pembaca yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
