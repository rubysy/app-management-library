@extends('layouts.admin')

@section('header', 'Borrow Management')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Book</th>
                    <th class="px-6 py-3">Borrower</th>
                    <th class="px-6 py-3">Dates</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($borrows as $borrow)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $borrow->book->title }}</div>
                            <div class="text-xs text-gray-500">ISBN: {{ $borrow->book->isbn }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrow->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $borrow->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            <div>Out: {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</div>
                            <div class="text-red-500">Due: {{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($borrow->status == 'active')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Borrowed</span>
                            @elseif($borrow->status == 'returned')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Returned</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Overdue</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($borrow->status == 'active' || $borrow->status == 'late')
                                <form action="{{ route('borrows.return', $borrow->id) }}" method="POST" onsubmit="return confirm('Mark as returned?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-xs">Mark Returned</button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">Completed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No active borrows found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $borrows->links() }}
        </div>
    </div>
@endsection
