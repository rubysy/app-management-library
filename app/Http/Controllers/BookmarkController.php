<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())->with('book')->latest()->get();
        return view('reader.bookmarks.index', compact('bookmarks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Prevent duplicate bookmarks
        $exists = Bookmark::where('user_id', Auth::id())
                          ->where('book_id', $request->book_id)
                          ->exists();

        if ($exists) {
            return back()->with('error', 'Book is already bookmarked.');
        }

        Bookmark::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
        ]);

        return back()->with('success', 'Book added to bookmarks.');
    }

    public function destroy($id)
    {
        $bookmark = Bookmark::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $bookmark->delete();

        return back()->with('success', 'Book removed from bookmarks.');
    }
}
