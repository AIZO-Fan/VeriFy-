@extends('layouts.app')

@section('title', 'Manajemen Modul Pembelajaran')

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 1200px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 0 0 6px;">Modul Pembelajaran</h1>
            <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Kelola seluruh materi edukasi dan modul yang tampil kepada pengguna.</p>
        </div>
        <a href="{{ route('admin.modules.create') }}" style="background: #20b4cb; color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Tambah Modul Baru
        </a>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px var(--theme-shadow);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--theme-card-soft); border-bottom: 1px solid var(--theme-border); font-size: 0.85rem; color: var(--theme-muted); text-transform: uppercase;">
                    <th style="padding: 14px 20px; width: 60px;">Urutan</th>
                    <th style="padding: 14px 20px;">Modul</th>
                    <th style="padding: 14px 20px;">Warna & Ikon</th>
                    <th style="padding: 14px 20px;">Jumlah Materi</th>
                    <th style="padding: 14px 20px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modules as $mod)
                <tr style="border-bottom: 1px solid var(--theme-border);">
                    <td style="padding: 16px 20px; font-weight: 700; color: var(--theme-muted);">
                        #{{ $mod->sort_order ?? $mod->id }}
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700; color: var(--theme-heading); font-size: 1rem;">
                            {{ $mod->title }}
                        </div>
                        <div style="font-size: 0.85rem; color: var(--theme-muted); max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $mod->description }}
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-transform: capitalize; background: rgba(32, 180, 203, 0.12); color: #20b4cb;">
                            {{ $mod->color ?? 'blue' }} &bull; {{ $mod->icon ?? 'shield' }}
                        </span>
                    </td>
                    <td style="padding: 16px 20px; font-size: 0.95rem; font-weight: 600; color: var(--theme-heading);">
                        {{ $mod->sections_count ?? $mod->sections->count() }} Poin Materi
                    </td>
                    <td style="padding: 16px 20px;">
                        <form action="{{ route('admin.modules.toggle', $mod) }}" method="POST" style="margin: 0;">
                            @csrf
                            @if($mod->status === 'published' || $mod->is_active)
                            <button type="submit" style="border: none; cursor: pointer; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; background: rgba(34, 197, 94, 0.15); color: #16a34a;">
                                Aktif
                            </button>
                            @else
                            <button type="submit" style="border: none; cursor: pointer; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; background: rgba(148, 163, 184, 0.2); color: #64748b;">
                                Nonaktif
                            </button>
                            @endif
                        </form>
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: inline-flex; align-items: center; gap: 8px;">
                            <a href="{{ route('lesson', $mod->id) }}" target="_blank" style="padding: 6px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: var(--theme-card-soft); border: 1px solid var(--theme-border); color: var(--theme-text);">
                                Lihat
                            </a>
                            <a href="{{ route('admin.modules.edit', $mod) }}" style="padding: 6px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: rgba(40, 123, 237, 0.12); color: #287bed;">
                                Edit
                            </a>
                            <form action="{{ route('admin.modules.destroy', $mod) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus modul {{ $mod->title }}?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: rgba(239, 68, 68, 0.12); color: #dc2626; border: none; cursor: pointer;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: var(--theme-muted);">
                        Belum ada modul yang terdaftar. Silakan tambah modul pertama!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection