<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Automatic Redirect based on Role
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin' || $user->role === 'staff') {
        return redirect()->route('admin.dashboard');
    }

    $query = \App\Models\Book::where('stock', '>', 0);
    
    if (request('search')) {
        $query->where(function($q) {
            $q->where('title', 'like', '%' . request('search') . '%')
              ->orWhere('author', 'like', '%' . request('search') . '%')
              ->orWhere('genre', 'like', '%' . request('search') . '%');
        });
    }

    $books = $query->latest()->get();
    return view('dashboard', compact('books'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin & Staff Routes
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Books Management
    Route::resource('books', BookController::class);
    
    // User/Role Management (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::patch('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.updateRole');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });

    // Borrow Management
    Route::get('/borrows', [AdminController::class, 'borrows'])->name('admin.borrows');
    Route::patch('/borrows/{id}/return', [AdminController::class, 'markReturned'])->name('borrows.return');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
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
});

require __DIR__.'/auth.php';
