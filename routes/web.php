<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Top rated: books with most 4-5 star ratings from readers
    $topRated = \App\Models\Book::withCount(['ratings as high_rating_count' => function ($q) {
        $q->whereIn('rating', [4, 5]);
    }])->with('categories')
      ->having('high_rating_count', '>', 0)
      ->orderByDesc('high_rating_count')
      ->take(5)
      ->get();

    // Most borrowed
    $mostBorrowed = \App\Models\Book::withCount('borrows')
        ->with('categories')
        ->having('borrows_count', '>', 0)
        ->orderByDesc('borrows_count')
        ->take(5)
        ->get();

    return view('welcome', compact('topRated', 'mostBorrowed'));
});

// Automatic Redirect based on Role
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin' || $user->role === 'staff') {
        return redirect()->route('admin.dashboard');
    }

    $query = \App\Models\Book::with('categories')->where('stock', '>', 0);
    
    if (request('search')) {
        $query->where(function($q) {
            $q->where('title', 'like', '%' . request('search') . '%')
              ->orWhere('author', 'like', '%' . request('search') . '%')
              ->orWhere('genre', 'like', '%' . request('search') . '%');
        });
    }

    if (request('category')) {
        $categoryId = request('category');
        $query->whereHas('categories', function($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    $books = $query->latest()->get();
    $categories = \App\Models\Category::withCount('books')->get();
    
    // Load ratings for each book
    $books->load('ratings');
    
    // Get current user's ratings
    $userRatings = \App\Models\Rating::where('user_id', $user->id)->pluck('rating', 'book_id');
    
    return view('dashboard', compact('books', 'categories', 'userRatings'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin & Staff Routes
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Books Management (Admin & Staff)
    Route::resource('books', BookController::class);
    
    // Borrow Management (Admin & Staff)
    Route::get('/borrows', [AdminController::class, 'borrows'])->name('admin.borrows');
    Route::patch('/borrows/{id}/return', [AdminController::class, 'markReturned'])->name('borrows.return');

    // Reports (Admin & Staff)
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    
    // Admin-Only Routes
    Route::middleware('role:admin')->group(function () {
        // User/Role Management
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::patch('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.updateRole');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Category Management
        Route::resource('categories', CategoryController::class);
    });
});

// Reader Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Reader Actions
    Route::post('/borrows', [\App\Http\Controllers\BorrowController::class, 'store'])->name('borrows.store');
    Route::get('/history', [\App\Http\Controllers\BorrowController::class, 'history'])->name('borrows.history');

    // Bookmarks
    Route::resource('bookmarks', \App\Http\Controllers\BookmarkController::class)->only(['index', 'store', 'destroy']);
    
    // Ratings
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
});

require __DIR__.'/auth.php';
