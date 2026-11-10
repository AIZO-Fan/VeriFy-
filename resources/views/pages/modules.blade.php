@extends('layouts.app')

@section('title', 'Modul Pembelajaran')

@section('content')
    <section class="container module-section">

        <header class="verify-modules-banner">
            <div class="verify-modules-banner-copy">

                <span class="verify-modules-banner-eyebrow">
                    <span aria-hidden="true"></span>
                    RUANG BELAJAR VERIFY
                </span>

                <h1>
                    Kenali modusnya.<br>
                    <span>Bangun kebiasaan digital yang lebih aman.</span>
                </h1>

                <p>
                    Pelajari cara mengenali penipuan, melindungi data,
                    dan mengambil keputusan dengan lebih hati-hati.
                </p>

                <div class="verify-modules-banner-note">
                    <span class="verify-modules-note-line" aria-hidden="true"></span>
                    Mulai dari satu modul, lanjutkan sesuai waktumu.
                </div>
            </div>

            <div class="verify-modules-banner-art" aria-hidden="true">
                <span class="verify-modules-art-ring"></span>

                <div class="verify-modules-art-icon">
                    <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M32 17c-7-5-15-6-24-3v34c9-3 17-2 24 3 7-5 15-6 24-3V14c-9-3-17-2-24 3Z" />
                        <path d="M32 17v34M15 23c4-1 7 0 10 1M15 31c4-1 7 0 10 1M39 24c3-1 6-2 10-1M39 32c3-1 6-2 10-1" />
                    </svg>
                </div>

                <span class="verify-modules-art-dot"></span>
            </div>
        </header>

        {{-- Module Section --}}
        {{-- JUDUL DAFTAR MODUL --}}
        <h1 class="ribbon" id="verify-modules-list">
            <x-icon name="report" />
            Modul Pembelajaran
        </h1>

        <p class="section-description">
            Pilih topik yang ingin kamu pelajari, lalu lanjutkan
            sesuai urutan atau kebutuhanmu.
        </p>

        {{-- GRID DAFTAR MODUL --}}
        <div class="module-grid">
            @forelse ($modules as $module)
                <article class="module-card {{ $module['color'] }}">

                    <span class="badge">
                        Modul {{ $module['display_number'] }}
                    </span>

                    <div class="module-icon">
                        <x-icon :name="$module['icon']" />
                    </div>

                    <h2>{{ $module['title'] }}</h2>

                    <p>{{ $module['description'] }}</p>

                    <a class="mini-button" href="{{ $module['detail_url'] }}">
                        Explore Module
                        <span aria-hidden="true">›</span>
                    </a>

                    <span class="card-number" aria-hidden="true">
                        {{ $module['display_number'] }}
                    </span>

                    <div class="card-decoration" aria-hidden="true"></div>

                </article>
            @empty
                <div class="verify-modules-empty">
                    <h2>
                        {{ $modules->total() > 0 ? 'Halaman ini tidak berisi modul' : 'Belum ada modul pembelajaran' }}
                    </h2>

                    <p>
                        {{ $modules->total() > 0
                            ? 'Kembali ke halaman pertama untuk melihat modul yang tersedia.'
                            : 'Modul yang sudah dipublikasikan akan ditampilkan di sini.' }}
                    </p>

                    @if ($modules->total() > 0)
                        <a href="{{ $modules->url(1) }}">
                            Kembali ke halaman pertama
                        </a>
                    @endif
                </div>
            @endforelse
        </div>


        {{-- PAGINATION --}}
        @if ($modules->hasPages())
            <nav class="verify-modules-pagination" aria-label="Halaman daftar modul">
                {{-- INFORMASI HALAMAN DI KIRI --}}
                <p class="verify-modules-pagination-info">
                    Halaman
                    <span>{{ $modules->currentPage() }}</span>
                    dari
                    <span>{{ $modules->lastPage() }}</span>
                </p>

                {{-- TOMBOL NAVIGASI DI KANAN --}}
                <div class="verify-modules-pagination-controls">

                    @if ($modules->onFirstPage())
                        <span class="verify-modules-page-button is-disabled" aria-disabled="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 12H5" />
                                <path d="M11 6L5 12L11 18" />
                            </svg>

                            Sebelumnya
                        </span>
                    @else
                        <a class="verify-modules-page-button" href="{{ $modules->previousPageUrl() }}" rel="prev">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 12H5" />
                                <path d="M11 6L5 12L11 18" />
                            </svg>

                            Sebelumnya
                        </a>
                    @endif

                    @if ($modules->hasMorePages())
                        <a class="verify-modules-page-button verify-modules-page-button--next"
                            href="{{ $modules->nextPageUrl() }}" rel="next">
                            Berikutnya

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12H19" />
                                <path d="M13 6L19 12L13 18" />
                            </svg>
                        </a>
                    @else
                        <span class="verify-modules-page-button is-disabled" aria-disabled="true">
                            Berikutnya

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12H19" />
                                <path d="M13 6L19 12L13 18" />
                            </svg>
                        </span>
                    @endif

                </div>
            </nav>
        @endif

    </section>
@endsection
