@extends('layouts.app')

@section('title', 'Manajemen Scam Simulator')

@section('content')
@include('admin.partials.nav')

<section class="container" style="max-width: 1200px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 0 0 6px;">Scam Simulator</h1>
            <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Kelola skenario percakapan interaktif simulasi penipuan digital.</p>
        </div>
        <a href="{{ route('admin.simulator.create') }}" style="background: #20b4cb; color: #fff; padding: 10px 20px; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Skenario Baru
        </a>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px var(--theme-shadow);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--theme-card-soft); border-bottom: 1px solid var(--theme-border); font-size: 0.85rem; color: var(--theme-muted); text-transform: uppercase;">
                    <th style="padding: 14px 20px;">Topik</th>
                    <th style="padding: 14px 20px;">Judul & Slug</th>
                    <th style="padding: 14px 20px;">Tingkat Kesulitan</th>
                    <th style="padding: 14px 20px;">Dimainkan</th>
                    <th style="padding: 14px 20px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scenarios as $scen)
                    <tr style="border-bottom: 1px solid var(--theme-border);">
                        <td style="padding: 16px 20px;">
                            <span style="background: rgba(32, 180, 203, 0.15); color: #20b4cb; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.85rem;">
                                Topik {{ $scen->topic_id }}
                            </span>
                        </td>
                        <td style="padding: 16px 20px;">
                            <div style="font-weight: 700; color: var(--theme-heading); font-size: 1rem;">
                                {{ $scen->title }}
                            </div>
                            <div style="font-size: 0.8rem; color: var(--theme-muted); font-family: monospace;">
                                /{{ $scen->slug }} &bull; v{{ $scen->version }}
                            </div>
                        </td>
                        <td style="padding: 16px 20px;">
                            @php
                                $diffColors = [
                                    'beginner' => ['bg' => 'rgba(34, 197, 94, 0.15)', 'text' => '#16a34a'],
                                    'intermediate' => ['bg' => 'rgba(245, 158, 11, 0.15)', 'text' => '#d97706'],
                                    'advanced' => ['bg' => 'rgba(239, 68, 68, 0.15)', 'text' => '#dc2626'],
                                ];
                                $c = $diffColors[$scen->difficulty] ?? ['bg' => 'rgba(148, 163, 184, 0.2)', 'text' => '#64748b'];
                            @endphp
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-transform: capitalize; background: {{ $c['bg'] }}; color: {{ $c['text'] }};">
                                {{ $scen->difficulty }}
                            </span>
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.95rem; font-weight: 600; color: var(--theme-heading);">
                            {{ $scen->attempts_count }} kali
                        </td>
                        <td style="padding: 16px 20px;">
                            <form action="{{ route('admin.simulator.toggle', $scen) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="border: none; cursor: pointer; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; {{ $scen->is_active ? 'background: rgba(34, 197, 94, 0.15); color: #16a34a;' : 'background: rgba(148, 163, 184, 0.2); color: #64748b;' }}">
                                    {{ $scen->is_active ? 'Aktif' : 'Draft' }}
                                </button>
                            </form>
                        </td>
                        <td style="padding: 16px 20px; text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 8px;">
                                <a href="{{ route('admin.simulator.edit', $scen) }}" style="padding: 6px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: rgba(40, 123, 237, 0.12); color: #287bed;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.simulator.destroy', $scen) }}" method="POST" onsubmit="return confirm('Hapus skenario {{ $scen->title }}?');" style="margin: 0;">
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
                            Belum ada skenario simulator yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

