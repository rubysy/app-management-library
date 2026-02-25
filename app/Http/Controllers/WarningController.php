<?php

namespace App\Http\Controllers;

use App\Models\Warning;
use Illuminate\Http\Request;

class WarningController extends Controller
{
    public function index()
    {
        $warnings = Warning::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $warnings->where('is_read', false)->count();

        return view('reader.warnings.index', compact('warnings', 'unreadCount'));
    }

    public function markRead($id)
    {
        $warning = Warning::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $warning->update(['is_read' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    public function markAllRead()
    {
        Warning::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua pesan ditandai sudah dibaca.');
    }
}
