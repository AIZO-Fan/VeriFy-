@extends('layouts.app')

@section('title', 'Modul Pembelajaran')

@section('content')
<section class="container module-section">

  <!-- WELCOME SECTION -->
<div class="welcome-section">
    <div>
        <span>WELCOME TO YOUR LEARNING SPACE 👋</span>

        <h2>Let's Learn Something New Today!</h2>

        <p>
            Tingkatkan pengetahuan dan bangun kebiasaan
            digital yang lebih aman melalui pembelajaran interaktif.
        </p>
    </div>

    <i class="fas fa-graduation-cap"></i>
</div>


<!-- LEARNING REMINDER -->
<div class="learning-reminder">
    <i class="fas fa-lightbulb"></i>

    <div>
        <h4>Learning Reminder</h4>
        <p>
            Jangan lupa luangkan waktu setiap hari untuk belajar.
            Satu modul kecil hari ini bisa membawa perubahan besar
            untuk masa depan digitalmu!
        </p>
    </div>
</div>

    {{-- Module Section --}}
    <h1 class="ribbon">
        <x-icon name="report" />
        Modul Pembelajaran
    </h1>

    <p class="module-description">
    Bangun kebiasaan digital yang lebih aman,
    satu modul setiap hari. Pelajari berbagai materi
    menarik untuk meningkatkan pengetahuan dan
    kesadaranmu dalam menjaga keamanan digital.
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
