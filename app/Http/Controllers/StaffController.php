<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;

use App\Models\Warning;

class StaffController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $activeBorrows = Borrow::where('status', 'active')->count();
        $overdueBooks = Borrow::where('status', 'active')
                              ->where('return_date', '<', now())
                              ->count();
        
        $recentBorrows = Borrow::with(['user', 'book'])
                               ->latest()
                               ->take(5)
                               ->get();

        return view('staff.dashboard', compact('totalBooks', 'totalUsers', 'activeBorrows', 'overdueBooks', 'recentBorrows'));
    }

    public function borrows()
    {
        $borrows = Borrow::with(['user', 'book'])->latest()->paginate(10);
        return view('staff.borrows.index', compact('borrows'));
    }

    public function markReturned($id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrow->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);
        
        $borrow->book->increment('stock');

        return back()->with('success', 'Buku berhasil ditandai sebagai dikembalikan.');
    }

    public function reports()
    {
        $borrows = Borrow::with(['user', 'book'])->get();
        return view('staff.reports.index', compact('borrows'));
    }

    // Reader Account Management - view only, no delete/role change
    public function readers()
    {
        $readers = User::where('role', 'reader')
                       ->withCount('borrows')
                       ->orderBy('created_at', 'desc')
                       ->get();
        return view('staff.readers.index', compact('readers'));
    }

    public function warnUser($userId)
    {
        $user = User::findOrFail($userId);
        
        $overdueBooks = Borrow::where('user_id', $userId)
            ->whereIn('status', ['active', 'late'])
            ->where('return_date', '<', now())
            ->with('book')
            ->get();

        if ($overdueBooks->isEmpty()) {
            return back()->with('error', 'User ini tidak memiliki pinjaman yang terlambat.');
        }

        $bookList = $overdueBooks->map(function ($borrow, $index) {
            return ($index + 1) . '. "' . $borrow->book->title . '" (Tenggat: ' . \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') . ')';
        })->implode("\n");

        $message = "Untuk pelanggan terhormat {$user->name},\n\n"
            . "Kami menginformasikan bahwa Anda memiliki " . $overdueBooks->count() . " buku yang BELUM dikembalikan dan sudah melewati batas waktu pengembalian:\n\n"
            . $bookList . "\n\n"
            . "⚠️ PERINGATAN: Apabila buku-buku tersebut tidak segera dikembalikan dalam waktu 3 hari kerja, akun Anda akan dimasukkan ke dalam DAFTAR HITAM (blacklist) dan tidak dapat melakukan peminjaman buku lagi di YouLibrary.\n\n"
            . "Segera kembalikan buku-buku tersebut ke perpustakaan.\n\n"
            . "Hormat kami,\nTim Perpustakaan YouLibrary";

        Warning::create([
            'user_id' => $userId,
            'warned_by' => auth()->id(),
            'message' => $message,
        ]);

        return back()->with('success', 'Peringatan berhasil dikirim ke ' . $user->name);
    }
}
