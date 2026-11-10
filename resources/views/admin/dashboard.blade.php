@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    @include('admin.partials.nav')

    <section
        class="va-shell va-dashboard"
        aria-labelledby="va-dashboard-title"
    >
        <header class="va-page-heading">
            <span class="va-eyebrow">RINGKASAN PLATFORM</span>

            <h1 id="va-dashboard-title">
                Kelola VeriFy dengan lebih mudah.
            </h1>

            <p>
                Pantau konten pembelajaran, pengguna, dan aktivitas pemeriksaan.
            </p>
        </header>

        {{-- KARTU STATISTIK --}}
        <div class="va-stats">

            <article class="va-stat">
                <div class="va-stat-top">
                    <h2>Modul pembelajaran</h2>

                    <span class="va-icon" aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                            <path d="M6.5 2H20V22H6.5A2.5 2.5 0 0 1 4 19.5V4.5A2.5 2.5 0 0 1 6.5 2Z" />
                        </svg>
                    </span>
                </div>

                <p class="va-stat-value">
                    {{ $stats['total_modules'] }}
                </p>

                <div class="va-stat-footer">
                    <a
                        class="va-text-link"
                        href="{{ route('admin.modules.create') }}"
                    >
                        Tambah modul baru
                        <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </article>

            <article class="va-stat">
                <div class="va-stat-top">
                    <h2>Skenario simulator</h2>

                    <span class="va-icon" aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="3" y="4" width="18" height="13" rx="2" />
                            <path d="M8 21H16M12 17V21" />
                        </svg>
                    </span>
                </div>

                <p class="va-stat-value">
                    {{ $stats['total_scenarios'] }}
                </p>

                <div class="va-stat-footer">
                    <a
                        class="va-text-link"
                        href="{{ route('admin.simulator.create') }}"
                    >
                        Tambah skenario baru
                        <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </article>

            <article class="va-stat">
                <div class="va-stat-top">
                    <h2>Total pengguna</h2>

                    <span class="va-icon" aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="9" cy="7" r="4" />
                            <path d="M2 21V19A4 4 0 0 1 6 15H12A4 4 0 0 1 16 19V21" />
                            <path d="M17 3.2A4 4 0 0 1 17 10.8M22 21V19A4 4 0 0 0 19 15.1" />
                        </svg>
                    </span>
                </div>

                <p class="va-stat-value">
                    {{ $stats['total_users'] }}
                </p>

                <div class="va-stat-footer">
                    Termasuk {{ $stats['total_admins'] }} akun admin
                </div>
            </article>

            <article class="va-stat">
                <div class="va-stat-top">
                    <h2>Pemeriksaan scam</h2>

                    <span class="va-icon" aria-hidden="true">
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
                </div>

                <p class="va-stat-value">
                    {{ $stats['total_scans'] }}
                </p>

                <div class="va-stat-footer">
                    {{ $stats['total_attempts'] }} penyelesaian simulator
                </div>
            </article>

        </div>

        {{-- PANEL DATA --}}
        <div class="va-panels">

            <section
                class="va-panel"
                aria-labelledby="va-scenarios-title"
            >
                <header class="va-panel-heading">
                    <h2 id="va-scenarios-title">
                        Skenario simulator
                    </h2>

                    <a
                        class="va-text-link"
                        href="{{ route('admin.simulator.index') }}"
                    >
                        Lihat semua
                        <span aria-hidden="true">→</span>
                    </a>
                </header>

                <div class="va-list">
                    @forelse($recentScenarios as $scen)
                        <div class="va-row">
                            <div class="va-row-copy">
                                <h3>{{ $scen->title }}</h3>

                                <p>
                                    Topik #{{ $scen->topic_id }}
                                    &bull;
                                    {{ ucfirst($scen->difficulty) }}
                                </p>
                            </div>

                            @if($scen->is_active)
                                <span class="va-badge va-badge--success">
                                    Aktif
                                </span>
                            @else
                                <span class="va-badge">Draft</span>
                            @endif
                        </div>
                    @empty
                        <div class="va-empty">
                            <h3>Belum ada skenario</h3>

                            <p>
                                Skenario simulator akan ditampilkan
                                di sini setelah ditambahkan.
                            </p>
                        </div>
                    @endforelse
                </div>

                <footer class="va-panel-footer">
                    <a
                        class="va-button va-button--primary"
                        href="{{ route('admin.simulator.create') }}"
                    >
                        <span aria-hidden="true">+</span>
                        Buat skenario baru
                    </a>
                </footer>
            </section>

            <section
                class="va-panel"
                aria-labelledby="va-scans-title"
            >
                <header class="va-panel-heading">
                    <h2 id="va-scans-title">
                        Pemeriksaan terkini
                    </h2>

                    <a
                        class="va-text-link"
                        href="{{ route('admin.scans.index') }}"
                    >
                        Lihat semua
                        <span aria-hidden="true">→</span>
                    </a>
                </header>

                <div class="va-list">
                    @forelse($recentScans as $scan)
                        <div class="va-row">
                            <div class="va-row-copy">
                                <h3 class="va-truncate">
                                    {{ $scan->content }}
                                </h3>

                                <p>
                                    {{ $scan->user ? $scan->user->name : 'Tamu' }}
                                    &bull;
                                    {{ $scan->created_at->diffForHumans() }}
                                </p>
                            </div>

                            @if($scan->status === 'danger')
                                <span class="va-badge va-badge--danger">
                                    Bahaya
                                </span>
                            @elseif($scan->status === 'warning')
                                <span class="va-badge va-badge--warning">
                                    Waspada
                                </span>
                            @else
                                <span class="va-badge va-badge--success">
                                    Aman
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="va-empty">
                            <h3>Belum ada data pemeriksaan</h3>

                            <p>
                                Aktivitas pemeriksaan scam akan muncul
                                di sini saat tersedia.
                            </p>
                        </div>
                    @endforelse
                </div>

                <footer class="va-panel-footer">
                    <a
                        class="va-button"
                        href="{{ route('admin.modules.create') }}"
                    >
                        <span aria-hidden="true">+</span>
                        Buat modul pembelajaran
                    </a>
                </footer>
            </section>

        </div>
    </section>
@endsection