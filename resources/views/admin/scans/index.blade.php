@extends('layouts.app')

@section('title', 'Monitoring Deteksi Scam')

@section('content')
    @include('admin.partials.nav')

    <section
        class="va-shell va-scans-page"
        aria-labelledby="va-scans-title"
    >
        {{-- HEADER HALAMAN --}}
        <header class="va-management-heading">
            <div class="va-management-heading-copy">
                <span class="va-eyebrow">
                    MONITORING AKTIVITAS
                </span>

                <h1 id="va-scans-title">
                    Monitoring Deteksi Scam
                </h1>

                <p>
                    Pantau aktivitas pemeriksaan tautan dan pesan scam oleh pengguna.
                </p>
            </div>
        </header>

        <div class="va-data-card">

            {{-- TOOLBAR FILTER --}}
            <div class="va-scans-toolbar">
                <div class="va-scans-toolbar-heading">
                    <h2 id="va-scans-list-title">
                        Riwayat pemeriksaan
                    </h2>

                    <p>
                        Telusuri hasil pemeriksaan berdasarkan konten,
                        status, atau mode.
                    </p>
                </div>

                <form
                    class="va-scans-filter"
                    action="{{ route('admin.scans.index') }}"
                    method="GET"
                    role="search"
                    aria-label="Cari dan filter riwayat pemeriksaan"
                >
                    <div class="va-scans-search">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            aria-hidden="true"
                        >
                            <circle cx="10.5" cy="10.5" r="6.5" />
                            <path d="M16 16L21 21" />
                        </svg>

                        <input
                            class="va-control va-scans-search-input"
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari konten teks atau URL..."
                            aria-label="Cari konten teks atau URL"
                        >
                    </div>

                    <select
                        class="va-control va-scans-status-filter"
                        name="status"
                        aria-label="Filter status pemeriksaan"
                    >
                        <option value="">Semua status</option>

                        <option
                            value="safe"
                            {{ request('status') == 'safe' ? 'selected' : '' }}
                        >
                            Aman
                        </option>

                        <option
                            value="warning"
                            {{ request('status') == 'warning' ? 'selected' : '' }}
                        >
                            Waspada
                        </option>

                        <option
                            value="danger"
                            {{ request('status') == 'danger' ? 'selected' : '' }}
                        >
                            Bahaya
                        </option>
                    </select>

                    <select
                        class="va-control va-scans-mode-filter"
                        name="mode"
                        aria-label="Filter mode pemeriksaan"
                    >
                        <option value="">Semua mode</option>

                        <option
                            value="text"
                            {{ request('mode') == 'text' ? 'selected' : '' }}
                        >
                            Teks
                        </option>

                        <option
                            value="url"
                            {{ request('mode') == 'url' ? 'selected' : '' }}
                        >
                            URL
                        </option>
                    </select>

                    <button
                        class="va-button va-button--primary"
                        type="submit"
                    >
                        Filter
                    </button>

                    @if(
                        request()->filled('search') ||
                        request()->filled('status') ||
                        request()->filled('mode')
                    )
                        <a
                            class="va-scans-reset"
                            href="{{ route('admin.scans.index') }}"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- TABEL --}}
            <div
                class="va-table-scroll"
                role="region"
                aria-labelledby="va-scans-list-title"
                tabindex="0"
            >
                <table class="va-table va-scans-table">
                    <thead>
                        <tr>
                            <th scope="col">Waktu</th>
                            <th scope="col">Pengguna</th>
                            <th scope="col">Mode &amp; skor</th>
                            <th scope="col">Konten yang diperiksa</th>
                            <th scope="col">Temuan</th>

                            <th
                                scope="col"
                                class="va-cell-actions"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($scans as $scan)
                            <tr>
                                <td>
                                    <div class="va-scan-time">
                                        <span class="va-scan-date">
                                            {{ $scan->created_at->format('d M Y') }}
                                        </span>

                                        <span class="va-scan-hour">
                                            {{ $scan->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="va-scan-user">
                                        {{ $scan->user
                                            ? $scan->user->name
                                            : 'Pengunjung Tamu' }}
                                    </span>

                                    <span class="va-scan-user-type">
                                        {{ $scan->user
                                            ? 'Akun terdaftar'
                                            : 'Tanpa akun' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="va-scan-result">
                                        <span class="va-badge va-scan-mode">
                                            {{ $scan->mode === 'text'
                                                ? 'Teks'
                                                : strtoupper($scan->mode) }}
                                        </span>

                                        @if($scan->risk_level === 'danger')
                                            <span class="va-badge va-badge--danger">
                                                {{ $scan->score }}% · {{ $scan->risk_label }}
                                            </span>
                                        @elseif($scan->risk_level === 'warning')
                                            <span class="va-badge va-badge--warning">
                                                {{ $scan->score }}% · {{ $scan->risk_label }}
                                            </span>
                                        @else
                                            <span class="va-badge va-badge--success">
                                                {{ $scan->score }}% · {{ $scan->risk_label }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="va-scan-content-cell">
                                    <details class="va-scan-content">
                                        <summary>
                                            <span class="va-scan-preview">
                                                {{ $scan->content }}
                                            </span>

                                            <span class="va-scan-content-toggle">
                                                <span class="va-scan-show-label">
                                                    Lihat konten
                                                </span>

                                                <span class="va-scan-hide-label">
                                                    Tutup konten
                                                </span>

                                                <span
                                                    class="va-scan-content-arrow"
                                                    aria-hidden="true"
                                                >
                                                    ↓
                                                </span>
                                            </span>
                                        </summary>

                                        <div class="va-scan-full-content">
                                            {{ $scan->content }}
                                        </div>
                                    </details>
                                </td>

                                <td>
                                    <div class="va-scan-findings">
                                        @if(!empty($scan->flags))
                                            <span class="va-scan-finding">
                                                {{ count($scan->flags) }}
                                                indikator ditemukan
                                            </span>
                                        @endif

                                        @if(!empty($scan->threat_urls))
                                            <span class="va-scan-finding">
                                                {{ count($scan->threat_urls) }}
                                                URL berbahaya
                                            </span>
                                        @endif

                                        @if(
                                            empty($scan->flags) &&
                                            empty($scan->threat_urls)
                                        )
                                            <span class="va-scan-no-findings">
                                                Tidak ada temuan tercatat
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="va-cell-actions">
                                    <form
                                        class="va-inline-form"
                                        action="{{ route('admin.scans.destroy', $scan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus log scan ini?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="va-action-button va-action-button--delete"
                                            type="submit"
                                            aria-label="Hapus log pemeriksaan {{ $scan->created_at->format('d M Y H:i') }}"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                aria-hidden="true"
                                            >
                                                <path d="M3 6H21M9 6V4H15V6" />
                                                <path d="M5 6L6 20H18L19 6" />
                                                <path d="M10 10V16M14 10V16" />
                                            </svg>

                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="va-table-empty-cell"
                                >
                                    <div class="va-table-empty">
                                        <span
                                            class="va-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M12 22S20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" />
                                                <path d="M8 12L11 15L16 9" />
                                            </svg>
                                        </span>

                                        @if(
                                            request()->filled('search') ||
                                            request()->filled('status') ||
                                            request()->filled('mode')
                                        )
                                            <h3>
                                                Tidak ada hasil yang sesuai
                                            </h3>

                                            <p>
                                                Coba kata kunci lain
                                                atau ubah filter pemeriksaan.
                                            </p>

                                            <a
                                                class="va-button"
                                                href="{{ route('admin.scans.index') }}"
                                            >
                                                Reset filter
                                            </a>
                                        @else
                                            <h3>
                                                Belum ada riwayat pemeriksaan
                                            </h3>

                                            <p>
                                                Pemeriksaan pesan dan URL
                                                akan ditampilkan di sini
                                                saat data tersedia.
                                            </p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($scans->hasPages())
                <div class="va-scans-pagination va-users-pagination">
                    {{ $scans->appends(
                        request()->only(['search', 'status', 'mode'])
                    )->links() }}
                </div>
            @endif

        </div>
    </section>
@endsection