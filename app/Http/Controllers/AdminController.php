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
        $borrows = \App\Models\Borrow::with(['user', 'book'])->latest()->paginate(10);
        return view('admin.borrows.index', compact('borrows'));
    }

    public function markReturned($id)
    {
        $borrow = \App\Models\Borrow::findOrFail($id);
        $borrow->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);
        
        // Restore stock
        $borrow->book->increment('stock');

        return back()->with('success', 'Book marked as returned.');
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

    public function updateUserRole(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'User role updated.');
    }

    public function destroyUser($id)
    {
        \App\Models\User::destroy($id);
        return back()->with('success', 'User deleted.');
    }
}
