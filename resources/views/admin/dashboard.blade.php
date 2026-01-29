@extends('layouts.admin')

@section('header', 'Admin Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Books -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Total Books</p>
                    <p class="text-lg font-semibold text-gray-700">{{ $totalBooks ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-lg font-semibold text-gray-700">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Active Borrows -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Active Borrows</p>
                    <p class="text-lg font-semibold text-gray-700">{{ $activeBorrows ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Late Returns -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Overdue Books</p>
                    <p class="text-lg font-semibold text-gray-700">{{ $overdueBooks ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <div class="px-6 py-4 border-b border-black">
            <h3 class="text-lg font-bold text-black">Recent Borrows</h3>
        </div>
        <div class="p-0">
            @if(isset($recentBorrows) && $recentBorrows->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-black">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Book</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Borrower</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Borrow Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Return Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentBorrows as $borrow)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-black">{{ $borrow->book->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $borrow->borrower_name ?? $borrow->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($borrow->status == 'active')
                                        <span class="bg-white border border-black text-black text-xs font-bold uppercase px-2 py-1">Active</span>
                                    @elseif($borrow->status == 'returned')
                                        <span class="bg-black text-white text-xs font-bold uppercase px-2 py-1">Returned</span>
                                    @else
                                        <span class="bg-[#FF3B30] text-black text-xs font-bold uppercase px-2 py-1">{{ ucfirst($borrow->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-gray-500 text-sm">
                    No recent activity to display yet.
                </div>
            @endif
        </div>
    </div>
@endsection
