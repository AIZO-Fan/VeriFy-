@extends('layouts.app')

@section('title', 'Modul Pembelajaran')

@section('content')
<section class="container module-section">

    {{-- WELCOME HERO BANNER --}}
    <div class="welcome-section">
        <div>
            <span>RUANG BELAJAR VERIFY</span>
            <h2>Kenali modusnya.<br>Bangun kebiasaan digital yang lebih aman.</h2>
            <p>Pelajari cara mengenali penipuan, melindungi data, dan mengambil keputusan dengan lebih hati-hati.</p>
        </div>

        <i class="fas fa-graduation-cap"></i>
    </div>

    {{-- HEADING JUDUL --}}
    <h1 class="ribbon">
        <x-icon name="report" />
        Modul Pembelajaran
    </h1>

    <p class="module-description">
        Pilih topik yang ingin kamu pelajari, lalu lanjutkan sesuai urutan atau kebutuhanmu.
    </p>

    {{-- GRID DAFTAR MODUL --}}
    <div class="module-grid">
        @foreach ($modules as $module)
            <a class="module-card" href="{{ $module['detail_url'] }}">

                <div class="module-card-top">
                    <span class="badge">
                        Modul {{ $module['display_number'] }}
                    </span>

                    <div class="module-icon">
                        <x-icon :name="$module['icon']" />
                    </div>
                </div>

                <h2>{{ $module['title'] }}</h2>

                <p>{{ $module['description'] }}</p>

                <div class="mini-button">
                    Explore Module <span>›</span>
                </div>

            </a>
        @endforeach
    </div>

</section>
@endsection