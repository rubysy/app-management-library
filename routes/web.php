<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffBookController;
use App\Http\Controllers\WarningController;
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
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === 'staff') {
        return redirect()->route('staff.dashboard');
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
    
    // Get book IDs that the user has returned (for rating restriction)
    $userReturnedBookIds = \App\Models\Borrow::where('user_id', $user->id)
        ->where('status', 'returned')
        ->pluck('book_id')->unique()->toArray();
    
    return view('dashboard', compact('books', 'categories', 'userRatings', 'userReturnedBookIds'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes (admin only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Books Management
    Route::resource('books', BookController::class);
    
    // Borrow Management
    Route::get('/borrows', [AdminController::class, 'borrows'])->name('admin.borrows');
    Route::patch('/borrows/{id}/approve', [AdminController::class, 'approveBorrow'])->name('borrows.approve');
    Route::delete('/borrows/{id}/reject', [AdminController::class, 'rejectBorrow'])->name('borrows.reject');

    // Return Management
    Route::get('/returns', [AdminController::class, 'returns'])->name('admin.returns');
    Route::patch('/returns/{id}/approve', [AdminController::class, 'approveReturn'])->name('returns.approve');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    
    // User/Role Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Category Management
    Route::resource('categories', CategoryController::class);

    // Warn User
    Route::post('/warn/{userId}', [AdminController::class, 'warnUser'])->name('admin.warn');

    // Rating Management
    Route::delete('/ratings/{id}', [AdminController::class, 'destroyRating'])->name('ratings.destroy');
});

// Staff/Petugas Routes
Route::middleware(['auth', 'role:staff'])->prefix('petugas')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('staff.dashboard');
    
    // Books Management
    Route::resource('books', StaffBookController::class)->names([
        'index' => 'staff.books.index',
        'create' => 'staff.books.create',
        'store' => 'staff.books.store',
        'edit' => 'staff.books.edit',
        'update' => 'staff.books.update',
        'destroy' => 'staff.books.destroy',
    ]);
    
    // Borrow Management
    Route::get('/borrows', [StaffController::class, 'borrows'])->name('staff.borrows');
    Route::patch('/borrows/{id}/approve', [StaffController::class, 'approveBorrow'])->name('staff.borrows.approve');
    Route::delete('/borrows/{id}/reject', [StaffController::class, 'rejectBorrow'])->name('staff.borrows.reject');

    // Return Management
    Route::get('/returns', [StaffController::class, 'returns'])->name('staff.returns');
    Route::patch('/returns/{id}/approve', [StaffController::class, 'approveReturn'])->name('staff.returns.approve');

    // Reports
    Route::get('/reports', [StaffController::class, 'reports'])->name('staff.reports');
    
    // Reader Account Management (view only)
    Route::get('/readers', [StaffController::class, 'readers'])->name('staff.readers');

    // Warn User
    Route::post('/warn/{userId}', [StaffController::class, 'warnUser'])->name('staff.warn');
});

// Reader Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Reader Actions
    Route::post('/borrows', [\App\Http\Controllers\BorrowController::class, 'store'])->name('borrows.store');
    Route::get('/history', [\App\Http\Controllers\BorrowController::class, 'history'])->name('borrows.history');
    Route::patch('/borrows/{id}/request-return', [\App\Http\Controllers\BorrowController::class, 'requestReturn'])->name('borrows.requestReturn');

    // Bookmarks
    Route::resource('bookmarks', \App\Http\Controllers\BookmarkController::class)->only(['index', 'store', 'destroy']);
    
    // Ratings
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

    // Warnings
    Route::get('/warnings', [WarningController::class, 'index'])->name('warnings.index');
    Route::patch('/warnings/{id}/read', [WarningController::class, 'markRead'])->name('warnings.markRead');
    Route::patch('/warnings/mark-all-read', [WarningController::class, 'markAllRead'])->name('warnings.markAllRead');
});

require __DIR__.'/auth.php';
