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
            return back()->with('error', 'Book is out of stock.');
        }

        // Create Borrow Record
        $borrow = Borrow::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrower_name' => $request->borrower_name,
            'borrower_address' => $request->borrower_address,
            'borrow_date' => Carbon::now(),
            'return_date' => Carbon::now()->addDays((int) $request->borrow_days),
            'status' => 'active',
        ]);

        // Decrease Stock
        $book->decrement('stock');

        // Prepare Receipt Data for SweetAlert
        $receipt = [
            'id' => $borrow->id,
            'title' => $book->title,
            'author' => $book->author,
            'borrower_name' => $borrow->borrower_name,
            'borrower_address' => $borrow->borrower_address,
            'borrow_date' => $borrow->borrow_date->format('d M Y'),
            'return_date' => $borrow->return_date->format('d M Y'),
        ];

        return redirect()->route('dashboard')->with('borrow_receipt', $receipt);
    }

    public function history()
    {
        $borrows = Borrow::where('user_id', Auth::id())->with('book')->latest()->get();
        return view('reader.borrows.history', compact('borrows'));
    }
}
