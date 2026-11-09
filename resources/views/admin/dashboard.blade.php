@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 1200px; margin: 24px auto 60px; padding: 0 20px;">
    {{-- Stat Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 30px;">
        {{-- Card 1 --}}
        <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px var(--theme-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-size: 0.85rem; font-weight: 700; color: var(--theme-muted); text-transform: uppercase;">Modul Pembelajaran</span>
                <span style="background: rgba(40, 123, 237, 0.12); color: #287bed; padding: 6px; border-radius: 8px;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </span>
            </div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--theme-heading); line-height: 1;">{{ $stats['total_modules'] }}</div>
            <div style="margin-top: 10px;">
                <a href="{{ route('admin.modules.create') }}" style="font-size: 0.85rem; color: #287bed; font-weight: 600; text-decoration: underline;">+ Tambah Modul Baru</a>
            </div>
        </div>

        {{-- Card 2 --}}
        <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px var(--theme-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-size: 0.85rem; font-weight: 700; color: var(--theme-muted); text-transform: uppercase;">Skenario Simulator</span>
                <span style="background: rgba(32, 180, 203, 0.12); color: #20b4cb; padding: 6px; border-radius: 8px;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </span>
            </div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--theme-heading); line-height: 1;">{{ $stats['total_scenarios'] }}</div>
            <div style="margin-top: 10px;">
                <a href="{{ route('admin.simulator.create') }}" style="font-size: 0.85rem; color: #20b4cb; font-weight: 600; text-decoration: underline;">+ Tambah Skenario Baru</a>
            </div>
        </div>

        {{-- Card 3 --}}
        <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px var(--theme-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-size: 0.85rem; font-weight: 700; color: var(--theme-muted); text-transform: uppercase;">Total Pengguna</span>
                <span style="background: rgba(168, 85, 247, 0.12); color: #a855f7; padding: 6px; border-radius: 8px;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
            </div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--theme-heading); line-height: 1;">{{ $stats['total_users'] }}</div>
            <div style="margin-top: 10px; font-size: 0.85rem; color: var(--theme-muted);">
                Termasuk <strong>{{ $stats['total_admins'] }}</strong> Akun Admin
            </div>
        </div>

        {{-- Card 4 --}}
        <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px var(--theme-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span style="font-size: 0.85rem; font-weight: 700; color: var(--theme-muted); text-transform: uppercase;">Pemeriksaan Scam</span>
                <span style="background: rgba(239, 68, 68, 0.12); color: #ef4444; padding: 6px; border-radius: 8px;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </span>
            </div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--theme-heading); line-height: 1;">{{ $stats['total_scans'] }}</div>
            <div style="margin-top: 10px; font-size: 0.85rem; color: var(--theme-muted);">
                {{ $stats['total_attempts'] }} penyelesaian simulator
            </div>
        </div>
    </div>

    {{-- Quick Action Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        {{-- Skenario Simulator Terkini --}}
        <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px var(--theme-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--theme-heading);">Skenario Scam Simulator</h3>
                <a href="{{ route('admin.simulator.index') }}" style="font-size: 0.85rem; color: #20b4cb; font-weight: 700;">Lihat Semua &rarr;</a>
            </div>

            @forelse($recentScenarios as $scen)
                <div style="padding: 12px 0; border-bottom: 1px solid var(--theme-border); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; color: var(--theme-heading); font-size: 0.95rem;">{{ $scen->title }}</div>
                        <div style="font-size: 0.8rem; color: var(--theme-muted);">Topik #{{ $scen->topic_id }} &bull; {{ ucfirst($scen->difficulty) }}</div>
                    </div>
                    <div>
                        @if($scen->is_active)
                            <span style="background: rgba(34, 197, 94, 0.15); color: #16a34a; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">Aktif</span>
                        @else
                            <span style="background: rgba(148, 163, 184, 0.15); color: #64748b; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">Draft</span>
                        @endif
                    </div>
                </div>
            @empty
                <p style="color: var(--theme-muted); font-size: 0.9rem;">Belum ada skenario.</p>
            @endforelse

            <div style="margin-top: 18px;">
                <a href="{{ route('admin.simulator.create') }}" style="display: block; text-align: center; background: #20b4cb; color: #fff; padding: 10px; border-radius: 10px; font-weight: 700; font-size: 0.9rem;">
                    + Buat Skenario Simulator Baru
                </a>
            </div>
        </div>

        {{-- Deteksi Scam Terkini --}}
        <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px var(--theme-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--theme-heading);">Deteksi Scam Terkini</h3>
                <a href="{{ route('admin.scans.index') }}" style="font-size: 0.85rem; color: #20b4cb; font-weight: 700;">Lihat Semua &rarr;</a>
            </div>

            @forelse($recentScans as $scan)
                <div style="padding: 12px 0; border-bottom: 1px solid var(--theme-border); display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                    <div style="min-width: 0; flex: 1;">
                        <div style="font-weight: 600; color: var(--theme-heading); font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $scan->content }}
                        </div>
                        <div style="font-size: 0.78rem; color: var(--theme-muted);">
                            {{ $scan->user ? $scan->user->name : 'Tamu' }} &bull; {{ $scan->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div>
                        @if($scan->status === 'danger')
                            <span style="background: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">Bahaya</span>
                        @elseif($scan->status === 'warning')
                            <span style="background: rgba(245, 158, 11, 0.15); color: #d97706; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">Waspada</span>
                        @else
                            <span style="background: rgba(34, 197, 94, 0.15); color: #16a34a; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">Aman</span>
                        @endif
                    </div>
                </div>
            @empty
                <p style="color: var(--theme-muted); font-size: 0.9rem;">Belum ada data deteksi.</p>
            @endforelse

            <div style="margin-top: 18px;">
                <a href="{{ route('admin.modules.create') }}" style="display: block; text-align: center; background: var(--theme-card-soft); border: 1px solid var(--theme-border); color: var(--theme-heading); padding: 10px; border-radius: 10px; font-weight: 700; font-size: 0.9rem;">
                    + Buat Modul Pembelajaran Baru
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

