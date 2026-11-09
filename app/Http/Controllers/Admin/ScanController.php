<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controllers;
use App\Models\ScanHistory;
use Illuminate\Http\Request;

class ScanController extends Controllers
{
    public function index(Request $request)
    {
        $query = ScanHistory::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('content', 'like', "%{$search}%");
        }

        $scans = $query->latest()->paginate(15);

        return view('admin.scans.index', compact('scans'));
    }

    public function destroy(ScanHistory $scan)
    {
        $scan->delete();
        return back()->with('success', 'Riwayat pemindaian berhasil dihapus.');
    }
}

