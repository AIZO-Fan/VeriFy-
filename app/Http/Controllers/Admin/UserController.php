<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount(['scanHistories', 'simulationAttempts']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        // Don't let admin demote themselves to prevent lock out
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->withErrors(['error' => 'Anda tidak dapat mencabut akses admin dari akun Anda sendiri!']);
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role untuk pengguna {$user->name} berhasil diubah menjadi {$request->role}!");
    }
}

