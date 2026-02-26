<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_address' => 'required|string',
            'borrow_days' => 'required|integer|in:3,7,14',
        ]);

        $book = Book::find($request->book_id);

        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku habis.');
        }

        // Create Borrow Record with PENDING status (no stock change yet)
        Borrow::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrower_name' => $request->borrower_name,
            'borrower_address' => $request->borrower_address,
            'borrow_days' => (int) $request->borrow_days,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('borrow_pending', true);
    }

    public function requestReturn($id)
    {
        $borrow = Borrow::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $borrow->update(['status' => 'pending_return']);

        return back()->with('return_requested', true);
    }

    public function history()
    {
        $borrows = Borrow::where('user_id', Auth::id())->with('book')->latest()->get();
        return view('reader.borrows.history', compact('borrows'));
    }
}
