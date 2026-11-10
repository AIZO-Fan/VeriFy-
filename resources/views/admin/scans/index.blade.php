@extends('layouts.app')

@section('title', 'Monitoring Deteksi Scam')

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 1200px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 0 0 6px;">Monitoring Deteksi Scam</h1>
            <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Pantau seluruh input pesan dan URL mencurigakan yang dianalisis oleh sistem.</p>
        </div>

        {{-- Filter & Search Form --}}
        <form action="{{ route('admin.scans.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari konten teks/URL..."
                   style="padding: 10px 14px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card); color: var(--theme-text); font-size: 0.9rem;">
            <select name="status" style="padding: 10px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card); color: var(--theme-text); font-size: 0.9rem;">
                <option value="">Semua Status</option>
                <option value="safe" {{ request('status') == 'safe' ? 'selected' : '' }}>Safe (Aman)</option>
                <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }}>Warning (Waspada)</option>
                <option value="danger" {{ request('status') == 'danger' ? 'selected' : '' }}>Danger (Bahaya)</option>
            </select>
            <select name="mode" style="padding: 10px; border-radius: 10px; border: 1px solid var(--theme-border); background: var(--theme-card); color: var(--theme-text); font-size: 0.9rem;">
                <option value="">Semua Mode</option>
                <option value="text" {{ request('mode') == 'text' ? 'selected' : '' }}>Teks</option>
                <option value="url" {{ request('mode') == 'url' ? 'selected' : '' }}>URL</option>
            </select>
            <button type="submit" style="padding: 10px 16px; border-radius: 10px; background: #20b4cb; color: #fff; border: none; font-weight: 700; cursor: pointer;">
                Filter
            </button>
        </form>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px var(--theme-shadow);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--theme-card-soft); border-bottom: 1px solid var(--theme-border); font-size: 0.85rem; color: var(--theme-muted); text-transform: uppercase;">
                    <th style="padding: 14px 20px;">Waktu</th>
                    <th style="padding: 14px 20px;">Pengguna</th>
                    <th style="padding: 14px 20px;">Mode & Skor</th>
                    <th style="padding: 14px 20px;">Konten yang Diperiksa</th>
                    <th style="padding: 14px 20px;">Temuan Ancaman</th>
                    <th style="padding: 14px 20px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                    <tr style="border-bottom: 1px solid var(--theme-border);">
                        <td style="padding: 16px 20px; font-size: 0.82rem; color: var(--theme-muted); white-space: nowrap;">
                            {{ $scan->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.9rem; font-weight: 600; color: var(--theme-heading);">
                            {{ $scan->user ? $scan->user->name : 'Pengunjung Tamu' }}
                        </td>
                        <td style="padding: 16px 20px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="text-transform: uppercase; font-size: 0.75rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; background: var(--theme-border);">
                                    {{ $scan->mode }}
                                </span>
                                @if($scan->status === 'danger')
                                    <span style="background: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 0.8rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">
                                        {{ $scan->score }}% Bahaya
                                    </span>
                                @elseif($scan->status === 'warning')
                                    <span style="background: rgba(245, 158, 11, 0.15); color: #d97706; font-size: 0.8rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">
                                        {{ $scan->score }}% Waspada
                                    </span>
                                @else
                                    <span style="background: rgba(34, 197, 94, 0.15); color: #16a34a; font-size: 0.8rem; font-weight: 700; padding: 4px 8px; border-radius: 6px;">
                                        {{ $scan->score }}% Aman
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 16px 20px; max-width: 320px;">
                            <div style="font-size: 0.88rem; color: var(--theme-text); word-break: break-all; max-height: 54px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                {{ $scan->content }}
                            </div>
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.82rem;">
                            @if(!empty($scan->flags))
                                <div style="color: #dc2626; font-weight: 600;">
                                    {{ count($scan->flags) }} Indikator Ditemukan
                                </div>
                            @elseif(!empty($scan->threat_urls))
                                <div style="color: #dc2626; font-weight: 600;">
                                    {{ count($scan->threat_urls) }} URL Berbahaya
                                </div>
                            @else
                                <span style="color: var(--theme-muted);">Tidak ada ancaman</span>
                            @endif
                        </td>
                        <td style="padding: 16px 20px; text-align: right;">
                            <form action="{{ route('admin.scans.destroy', $scan) }}" method="POST" onsubmit="return confirm('Hapus log scan ini?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 10px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; background: rgba(239, 68, 68, 0.12); color: #dc2626; border: none; cursor: pointer;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--theme-muted);">
                            Belum ada riwayat deteksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($scans->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--theme-border);">
                {{ $scans->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

