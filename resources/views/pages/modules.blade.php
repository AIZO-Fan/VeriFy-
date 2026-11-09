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
                <svg
                    viewBox="0 0 64 64"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M32 17c-7-5-15-6-24-3v34c9-3 17-2 24 3 7-5 15-6 24-3V14c-9-3-17-2-24 3Z" />
                    <path d="M32 17v34M15 23c4-1 7 0 10 1M15 31c4-1 7 0 10 1M39 24c3-1 6-2 10-1M39 32c3-1 6-2 10-1" />
                </svg>
            </div>

            <span class="verify-modules-art-dot"></span>
        </div>
    </header>

    {{-- Module Section --}}
    <h1 class="ribbon">
        <x-icon name="report" />
        Modul Pembelajaran
    </h1>

    <p class="section-description">
        Pilih topik yang ingin kamu pelajari, lalu lanjutkan sesuai urutan
        atau kebutuhanmu.
    </p>

    <div class="module-grid">
        @foreach ($modules as $module)

        <article class="module-card {{ $module['color'] }}">

            <span class="badge">
                Modul {{ str_pad($module['id'], 2, '0', STR_PAD_LEFT) }}
            </span>

            <div class="module-icon">
                <x-icon :name="$module['icon']" />
            </div>

            <h2>{{ $module['title'] }}</h2>

            <p>{{ $module['description'] }}</p>

            <a class="mini-button"
                href="{{ route('lesson', $module['id']) }}">
                Explore Module <span>›</span>
            </a>

            <span class="card-number">
                {{ str_pad($module['id'], 2, '0', STR_PAD_LEFT) }}
            </span>

            <div class="card-decoration"></div>

        </article>

        @endforeach
    </div>

</section>
@endsection