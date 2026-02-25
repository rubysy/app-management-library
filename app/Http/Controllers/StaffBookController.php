<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class StaffBookController extends Controller
{
    public function index()
    {
        $books = Book::with('categories')->latest()->paginate(10);
        return view('staff.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('staff.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books',
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|file|mimes:jpeg,png,jpg,gif,avif,webp|mimetypes:image/jpeg,image/png,image/gif,image/avif,image/webp|max:2048',
            'categories' => 'nullable|array',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $book = Book::create($validated);

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        return redirect()->route('staff.books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        $book->load('categories');
        return view('staff.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $book->id,
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|file|mimes:jpeg,png,jpg,gif,avif,webp|mimetypes:image/jpeg,image/png,image/gif,image/avif,image/webp|max:2048',
            'categories' => 'nullable|array',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($validated);

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        return redirect()->route('staff.books.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('staff.books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
