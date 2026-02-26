<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // TODO: Replace with real counts once models are ready and migrations run
        $totalBooks = \App\Models\Book::count();
        $totalUsers = \App\Models\User::count();
        $activeBorrows = \App\Models\Borrow::where('status', 'active')->count();
        $overdueBooks = \App\Models\Borrow::where('status', 'active')
                                          ->where('return_date', '<', now())
                                          ->count();
        
        // Recent Borrows for dashboard
        $recentBorrows = \App\Models\Borrow::with(['user', 'book'])
                                           ->latest()
                                           ->take(5)
                                           ->get();

        return view('admin.dashboard', compact('totalBooks', 'totalUsers', 'activeBorrows', 'overdueBooks', 'recentBorrows'));
    }

    public function borrows()
    {
        $borrows = \App\Models\Borrow::with(['user', 'book'])->latest()->paginate(20);
        return view('admin.borrows.index', compact('borrows'));
    }

    public function approveBorrow($id)
    {
        $borrow = \App\Models\Borrow::findOrFail($id);
        
        if ($borrow->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $book = $borrow->book;
        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku habis, tidak bisa menyetujui peminjaman.');
        }

        $borrow->update([
            'status' => 'active',
            'borrow_date' => now(),
            'return_date' => now()->addDays($borrow->borrow_days ?: 7),
        ]);

        $book->decrement('stock');

        return back()->with('success', 'Peminjaman disetujui untuk ' . ($borrow->borrower_name ?? $borrow->user->name) . '.');
    }

    public function rejectBorrow($id)
    {
        $borrow = \App\Models\Borrow::findOrFail($id);

        if ($borrow->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $borrow->delete();

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function returns()
    {
        $borrows = \App\Models\Borrow::with(['user', 'book'])
            ->where('status', 'pending_return')
            ->latest()
            ->get();
        return view('admin.returns.index', compact('borrows'));
    }

    public function approveReturn($id)
    {
        $borrow = \App\Models\Borrow::findOrFail($id);

        if ($borrow->status !== 'pending_return') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $borrow->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        $borrow->book->increment('stock');

        return back()->with('success', 'Pengembalian disetujui. Buku "' . $borrow->book->title . '" berhasil dikembalikan.');
    }

    public function reports()
    {
        $borrows = \App\Models\Borrow::with(['user', 'book'])->get();
        return view('admin.reports.index', compact('borrows'));
    }

    public function users()
    {
        $readers = \App\Models\User::where('role', 'reader')->get();
        $staffs = \App\Models\User::where('role', 'staff')->get();
        return view('admin.users.index', compact('readers', 'staffs'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'staff',
        ]);

        return redirect()->route('admin.users')->with('success', 'Akun petugas berhasil dibuat.');
    }

    public function destroyUser($id)
    {
        \App\Models\User::destroy($id);
        return back()->with('success', 'User deleted.');
    }

    public function warnUser($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        // Get all overdue borrows for this user
        $overdueBooks = \App\Models\Borrow::where('user_id', $userId)
            ->whereIn('status', ['active', 'late'])
            ->where('return_date', '<', now())
            ->with('book')
            ->get();

        if ($overdueBooks->isEmpty()) {
            return back()->with('error', 'User ini tidak memiliki pinjaman yang terlambat.');
        }

        // Build book list
        $bookList = $overdueBooks->map(function ($borrow, $index) {
            return ($index + 1) . '. "' . $borrow->book->title . '" (Tenggat: ' . \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') . ')';
        })->implode("\n");

        $message = "Untuk pelanggan terhormat {$user->name},\n\n"
            . "Kami menginformasikan bahwa Anda memiliki " . $overdueBooks->count() . " buku yang BELUM dikembalikan dan sudah melewati batas waktu pengembalian:\n\n"
            . $bookList . "\n\n"
            . "⚠️ PERINGATAN: Apabila buku-buku tersebut tidak segera dikembalikan dalam waktu 3 hari kerja, akun Anda akan dimasukkan ke dalam DAFTAR HITAM (blacklist) dan tidak dapat melakukan peminjaman buku lagi di YouLibrary.\n\n"
            . "Segera kembalikan buku-buku tersebut ke perpustakaan.\n\n"
            . "Hormat kami,\nTim Perpustakaan YouLibrary";

        \App\Models\Warning::create([
            'user_id' => $userId,
            'warned_by' => auth()->id(),
            'message' => $message,
        ]);

        return back()->with('success', 'Peringatan berhasil dikirim ke ' . $user->name);
    }

    public function destroyRating($id)
    {
        $rating = \App\Models\Rating::findOrFail($id);
        $bookId = $rating->book_id;
        $rating->delete();

        return redirect()->route('books.show', $bookId)->with('success', 'Ulasan berhasil dihapus.');
    }
}
