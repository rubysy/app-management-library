@extends('layouts.admin')

@section('header', 'Manage Books')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div class="relative w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Search books...">
        </div>
        <a href="{{ route('books.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Book
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Book</th>
                    <th class="px-6 py-3">Structure</th>
                    <th class="px-6 py-3">Stock</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($books as $book)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($book->cover_path)
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="h-12 w-8 object-cover rounded shadow mr-4">
                                @else
                                    <div class="h-12 w-8 bg-gray-200 rounded mr-4 flex items-center justify-center text-gray-400">?</div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $book->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $book->author }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="block">ISBN: {{ $book->isbn }}</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $book->genre }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                                {{ $book->stock }} Available
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            No books found. Start by adding one!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $books->links() }}
        </div>
    </div>
@endsection
