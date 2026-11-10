@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 1200px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 0 0 6px;">Manajemen Pengguna</h1>
            <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Daftar seluruh akun terdaftar dan hak akses (role) pengguna.</p>
        </div>

        {{-- Filter & Search Form --}}
        <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                   style="padding: 10px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card); color: var(--theme-text); font-size: 0.9rem;">
            <select name="role" style="padding: 10px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card); color: var(--theme-text); font-size: 0.9rem;">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            <button type="submit" style="padding: 10px 16px; border-radius: 10px; background: #20b4cb; color: #fff; border: none; font-weight: 700; cursor: pointer;">
                Cari
            </button>
        </form>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px var(--theme-shadow);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--theme-card-soft); border-bottom: 1px solid var(--theme-border); font-size: 0.85rem; color: var(--theme-muted); text-transform: uppercase;">
                    <th style="padding: 14px 20px;">Pengguna</th>
                    <th style="padding: 14px 20px;">Email</th>
                    <th style="padding: 14px 20px;">Aktivitas</th>
                    <th style="padding: 14px 20px;">Terdaftar</th>
                    <th style="padding: 14px 20px;">Role</th>
                    <th style="padding: 14px 20px; text-align: right;">Ubah Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid var(--theme-border);">
                        <td style="padding: 16px 20px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span style="width: 36px; height: 36px; border-radius: 50%; background: #20b4cb; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </span>
                                <div>
                                    <div style="font-weight: 700; color: var(--theme-heading); font-size: 0.95rem;">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span style="font-size: 0.75rem; color: #20b4cb; font-weight: 600;">(Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 20px; color: var(--theme-text); font-size: 0.9rem;">
                            {{ $user->email }}
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.85rem; color: var(--theme-muted);">
                            <div>{{ $user->scan_histories_count }}x Scan</div>
                            <div>{{ $user->simulation_attempts_count }}x Simulasi</div>
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.85rem; color: var(--theme-muted);">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </td>
                        <td style="padding: 16px 20px;">
                            @if($user->role === 'admin')
                                <span style="background: rgba(32, 180, 203, 0.15); color: #20b4cb; font-weight: 700; font-size: 0.8rem; padding: 4px 10px; border-radius: 8px;">
                                    ADMIN
                                </span>
                            @else
                                <span style="background: rgba(148, 163, 184, 0.15); color: #64748b; font-weight: 700; font-size: 0.8rem; padding: 4px 10px; border-radius: 8px;">
                                    USER
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px 20px; text-align: right;">
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" style="margin: 0; display: inline-flex; align-items: center; gap: 8px;">
                                @csrf
                                <select name="role" style="padding: 6px 8px; border-radius: 8px; border: 1px solid var(--theme-border); background: var(--theme-card-soft); color: var(--theme-text); font-size: 0.82rem;">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" style="padding: 6px 10px; border-radius: 8px; background: var(--theme-card-soft); border: 1px solid var(--theme-border); color: var(--theme-heading); font-size: 0.82rem; font-weight: 600; cursor: pointer;">
                                    Simpan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--theme-muted);">
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--theme-border);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

