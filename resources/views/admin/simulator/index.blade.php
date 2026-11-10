@extends('layouts.app')

@section('title', 'Manajemen Scam Simulator')

@section('content')
    @include('admin.partials.nav')

<style>
    .simulator-status-button {
        border: none;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .simulator-status-button.is-active {
        background: rgba(34, 197, 94, 0.15);
        color: #16a34a;
    }

    .simulator-status-button.is-draft {
        background: rgba(148, 163, 184, 0.2);
        color: #64748b;
    }

    .simulator-difficulty-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
        background: rgba(148, 163, 184, 0.2);
        color: #64748b;
    }

    .simulator-difficulty-beginner {
        background: rgba(34, 197, 94, 0.15);
        color: #16a34a;
    }

    .simulator-difficulty-intermediate {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
    }

    .simulator-difficulty-advanced {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
    }
</style>

<section class="container" style="max-width: 1200px; margin: 24px auto 60px; padding: 0 20px;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--theme-heading); margin: 0 0 6px;">Scam Simulator</h1>
        <p style="color: var(--theme-muted); margin: 0; font-size: 0.95rem;">Kelola skenario percakapan interaktif simulasi penipuan digital berdasarkan modul pembelajaran.</p>
    </div>

    <div style="background: var(--theme-card); border: 1px solid var(--theme-border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px var(--theme-shadow);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--theme-card-soft); border-bottom: 1px solid var(--theme-border); font-size: 0.85rem; color: var(--theme-muted); text-transform: uppercase;">
                    <th style="padding: 14px 20px;">Topik / Modul</th>
                    <th style="padding: 14px 20px;">Judul Skenario & Slug</th>
                    <th style="padding: 14px 20px;">Tingkat Kesulitan</th>
                    <th style="padding: 14px 20px;">Dimainkan</th>
                    <th style="padding: 14px 20px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($simulatorRows as $row)
                @php $scen = $row['scenario']; @endphp
                <tr style="border-bottom: 1px solid var(--theme-border);">
                    <td style="padding: 16px 20px;">
                        <span style="background: rgba(32, 180, 203, 0.15); color: #20b4cb; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.85rem;">
                            Topik {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div style="font-size: 0.8rem; color: var(--theme-muted); margin-top: 4px;">
                            Modul: {{ $row['module_title'] }}
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($scen)
                        <div style="font-weight: 700; color: var(--theme-heading); font-size: 1rem;">
                            {{ $scen->title }}
                        </div>
                        <div style="font-size: 0.8rem; color: var(--theme-muted); font-family: monospace;">
                            /{{ $scen->slug }} &bull; v{{ $scen->version }}
                        </div>
                        @else
                        <span style="color: var(--theme-muted); font-style: italic; font-size: 0.9rem;">Belum ada skenario untuk topik ini</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($scen)
                        <span class="simulator-difficulty-badge simulator-difficulty-{{ $scen->difficulty }}">
                            {{ $scen->difficulty }}
                        </span>
                        @else
                        -
                        @endif
                    </td>
                    <td style="padding: 16px 20px; font-size: 0.95rem; font-weight: 600; color: var(--theme-heading);">
                        {{ $scen ? $scen->attempts_count . ' kali' : '-' }}
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($scen)
                        <form action="{{ route('admin.simulator.toggle', $scen) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="simulator-status-button {{ $scen->is_active ? 'is-active' : 'is-draft' }}">
                                {{ $scen->is_active ? 'Aktif' : 'Draft' }}
                            </button>
                        </form>
                        @else
                        <span style="background: rgba(148, 163, 184, 0.15); color: var(--theme-muted); padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 600;">Kosong</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: inline-flex; align-items: center; gap: 8px;">
                            @if($scen)
                            <a href="{{ route('admin.simulator.edit', $scen) }}" style="padding: 6px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: rgba(40, 123, 237, 0.12); color: #287bed; text-decoration: none;">
                                Edit
                            </a>
                            <form action="{{ route('admin.simulator.destroy', $scen) }}" method="POST" onsubmit="return confirm('Hapus skenario {{ $scen->title }}?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: rgba(239, 68, 68, 0.12); color: #dc2626; border: none; cursor: pointer;">
                                    Hapus
                                </button>
                            </form>
                            @else
                            <a href="{{ route('admin.simulator.create', ['topic_id' => $row['topic_id']]) }}" style="padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: rgba(32, 180, 203, 0.15); color: #20b4cb; text-decoration: none;">
                                + Buat Skenario
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: var(--theme-muted);">
                        Belum ada modul pembelajaran yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection