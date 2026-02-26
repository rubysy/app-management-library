<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Borrow;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Check if user has returned this book (completed borrow cycle)
        $hasReturned = Borrow::where('user_id', auth()->id())
            ->where('book_id', $validated['book_id'])
            ->where('status', 'returned')
            ->exists();

        if (!$hasReturned) {
            return back()->with('error', 'Anda harus meminjam dan mengembalikan buku ini terlebih dahulu sebelum memberikan rating.');
        }

        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'book_id' => $validated['book_id'],
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]
        );

        return back()->with('success', 'Rating berhasil disimpan!');
    }
}
