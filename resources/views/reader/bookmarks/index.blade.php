@extends('layouts.reader')

@section('header', 'My Bookmarks')

@section('content')
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($bookmarks as $bookmark)
            <div class="bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] overflow-hidden hover:translate-x-1 hover:-translate-y-1 transition-transform duration-300 relative group">
                <!-- Delete Button -->
                <form action="{{ route('bookmarks.destroy', $bookmark->id) }}" method="POST" class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-[#FF3B30] text-white p-1 hover:bg-black shadow-md border border-black" title="Remove Bookmark">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </form>

                <div class="h-48 bg-gray-200 w-full object-cover">
                    @if($bookmark->book->cover_path)
                        <img src="{{ asset('storage/' . $bookmark->book->cover_path) }}" alt="{{ $bookmark->book->title }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400">
                             <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-black truncate">{{ $bookmark->book->title }}</h3>
                    <p class="text-sm text-gray-500 truncate">{{ $bookmark->book->author }}</p>
                    <div class="mt-4">
                        <span class="block text-center text-xs font-bold text-black bg-white border border-black py-1 uppercase">Saved</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-black">No bookmarks yet</h3>
                <p class="text-gray-500">Save books you're interested in here.</p>
                <a href="{{ route('dashboard') }}" class="mt-4 inline-block text-[#FF3B30] hover:text-black font-bold">Browse Books &rarr;</a>
            </div>
        @endforelse
    </div>
@endsection
