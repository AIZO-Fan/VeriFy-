@extends('layouts.app')

@section('title', $module['title'])

@section('content')
<section class="verify-lesson">
    <div class="verify-lesson-container">

        <a class="verify-lesson-back" href="{{ route('modules') }}">
            <svg
                aria-hidden="true"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>

            <span>Semua modul</span>
        </a>

        <header class="verify-lesson-heading">
            <span class="verify-lesson-eyebrow">
                <span aria-hidden="true"></span>
                MODUL {{ str_pad((string) $module['id'], 2, '0', STR_PAD_LEFT) }}
            </span>

            <h1>{{ $module['title'] }}</h1>

            <p>{{ $module['description'] }}</p>
        </header>

        <div class="verify-lesson-layout">

            {{-- MATERI --}}
            <article
                class="verify-lesson-material"
                aria-labelledby="verify-lesson-material-title">
                <div class="verify-lesson-material-heading">
                    <div>
                        <span class="verify-lesson-small-label">
                            PAHAMI DASARNYA
                        </span>

                        <h2 id="verify-lesson-material-title">
                            Hal yang perlu kamu pahami
                        </h2>
                    </div>

                    <span class="verify-lesson-count">
                        {{ count($module['lessons']) }} poin
                    </span>
                </div>

                <ol class="verify-lesson-points">
                    @foreach($module['lessons'] as $text)
                    <li>
                        <span
                            class="verify-lesson-point-number"
                            aria-hidden="true">
                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <p>{{ $text }}</p>
                    </li>
                    @endforeach
                </ol>

                <div class="verify-lesson-reflection">
                    <span>SEBELUM MELANJUTKAN</span>

                    <p>
                        Dari materi ini, apa satu hal yang ingin kamu
                        ingat saat menghadapi situasi serupa?
                    </p>
                </div>
            </article>

            {{-- LATIHAN --}}
            <aside class="verify-lesson-sidebar">
                <div class="verify-lesson-practice">
                    <span class="verify-lesson-practice-icon" aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z" />
                            <path d="m9 12 2 2 4-4" />
                        </svg>
                    </span>

                    <span class="verify-lesson-small-label">
                        DARI MATERI KE LATIHAN
                    </span>

                    <h2>Bagaimana kamu akan merespons?</h2>

                    <p>
                        Bawa pemahamanmu ke Scam Simulator.
                        Pilih tindakan dan lihat dampaknya
                        dalam cerita interaktif.
                    </p>

                    <a
                        class="verify-lesson-practice-link"
                        href="{{ route('simulator', ['topic' => $module['id']]) }}">
                        Latih pemahamanmu
                        <span aria-hidden="true">→</span>
                    </a>

                    <span class="verify-lesson-practice-caption">
                        Kamu bisa kembali membaca materi kapan saja.
                    </span>
                </div>

            </aside>

        </div>

        <p class="verify-lesson-disclaimer">
            Materi pengantar edukatif; bukan nasihat keamanan
            atau keuangan profesional.
        </p>

    </div>
</section>
@endsection