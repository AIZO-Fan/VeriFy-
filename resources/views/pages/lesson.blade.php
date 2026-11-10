@extends('layouts.app')

@section('title', $module->title)

@section('content')

@php
// Menggunakan data asli dari database
$readerSections = $module->sections;
$sectionCount = $readerSections->count();
@endphp

<section class="verify-lesson">
    <div class="verify-lesson-container">

        {{-- KEMBALI KE MODUL --}}
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

        {{-- HEADER MODUL --}}
        <header class="verify-lesson-heading">
            <span class="verify-lesson-eyebrow">
                <span aria-hidden="true"></span>
                MODUL {{ str_pad((string) ($module->sort_order ?? $module->id), 2, '0', STR_PAD_LEFT) }}
            </span>

            <h1>{{ $module->title }}</h1>

            <p>{{ $module->description }}</p>
        </header>

        @if($sectionCount > 0)
        <div class="vl-reader" data-lesson-reader>

            {{-- KARTU MATERI --}}
            <article class="vl-reader-card">

                <div class="vl-reader-top">
                    <span class="verify-lesson-small-label">
                        MATERI PEMBELAJARAN
                    </span>

                    <span
                        class="vl-reader-position"
                        data-reader-position>
                        Bagian 1 dari {{ $sectionCount }}
                    </span>
                </div>

                {{-- INDIKATOR POSISI BAGIAN --}}
                <div class="vl-reader-meter" aria-hidden="true">
                    <span
                        data-reader-meter
                        @style(['width'=> (100 / $sectionCount) . '%'])></span>
                </div>

                {{-- BAGIAN MATERI --}}
                <div class="vl-reader-content">
                    @foreach($readerSections as $index => $section)
                    <section
                        class="vl-reader-page"
                        data-reader-page
                        aria-labelledby="reader-title-{{ $index }}"
                        @if(!$loop->first) hidden @endif
                        >
                        <span class="vl-reader-page-label">
                            BAGIAN {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <h2
                            id="reader-title-{{ $index }}"
                            class="vl-reader-title"
                            tabindex="-1">
                            @if($section->type === 'example') 💡
                            @elseif($section->type === 'summary') 📌
                            @endif
                            {{ $section->title }}
                        </h2>

                        <div class="vl-reader-copy vl-reader-copy--{{ $section->type }}">
                            {{-- Karena format content sudah berupa string HTML tersanitasi dari database --}}
                            {!! $section->content !!}
                        </div>
                    </section>
                    @endforeach
                </div>

                {{-- NAVIGASI ANTARBAGIAN --}}
                <nav
                    class="vl-reader-navigation"
                    aria-label="Navigasi bagian materi">
                    <button
                        class="vl-reader-button vl-reader-button--previous"
                        type="button"
                        data-reader-previous
                        disabled>
                        <span aria-hidden="true">←</span>
                        Sebelumnya
                    </button>

                    <span
                        class="vl-reader-pagination"
                        data-reader-pagination>
                        1 / {{ $sectionCount }}
                    </span>

                    <button
                        class="vl-reader-button vl-reader-button--next"
                        type="button"
                        data-reader-next
                        @if($sectionCount===1) hidden @endif>
                        Selanjutnya
                        <span aria-hidden="true">→</span>
                    </button>

                    <button
                        class="vl-reader-button vl-reader-button--next"
                        type="button"
                        data-reader-complete
                        @if($sectionCount !==1) hidden @endif>
                        Tandai selesai dibaca
                    </button>
                </nav>

                {{-- TAMPIL SETELAH DITANDAI SELESAI --}}
                <div
                    class="vl-reader-finished"
                    data-reader-finished
                    hidden>
                    <span class="verify-lesson-small-label">
                        MATERI SELESAI DIBACA
                    </span>

                    <h3>Siap mencoba dalam sebuah situasi?</h3>

                    <p>
                        Bawa pemahamanmu ke Scam Simulator.
                        Pilih tindakan dan lihat dampaknya
                        dalam cerita interaktif.
                    </p>

                    @if($practice['available'])
                    <a
                        class="verify-lesson-practice-link"
                        href="{{ $practice['url'] }}">
                        Latih pemahamanmu
                        <span aria-hidden="true">→</span>
                    </a>
                    @else
                    <p style="color: #64748b; font-size: 0.9em; margin-top: 10px;">Latihan simulasi untuk modul ini belum tersedia.</p>
                    @endif
                </div>

                <p
                    class="vl-reader-sr-only"
                    data-reader-announcement
                    role="status"
                    aria-live="polite"></p>

            </article>

            {{-- DAFTAR ISI --}}
            <aside class="vl-reader-sidebar">
                <details
                    class="vl-reader-outline"
                    data-reader-outline
                    open>
                    <summary>
                        <span>Daftar isi modul</span>

                        <span
                            class="vl-reader-outline-toggle"
                            aria-hidden="true">
                            ⌄
                        </span>
                    </summary>

                    <div class="vl-reader-outline-body">
                        <p>
                            {{ $sectionCount }} bagian untuk dipelajari
                        </p>

                        <nav aria-label="Daftar isi modul">
                            <ol>
                                @foreach($readerSections as $index => $section)
                                <li>
                                    <button
                                        class="vl-reader-outline-link"
                                        type="button"
                                        data-reader-jump="{{ $index }}"
                                        @if($loop->first) aria-current="step" @endif
                                        >
                                        <span class="vl-reader-outline-number">
                                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <span>
                                            {{ $section->title }}
                                        </span>
                                    </button>
                                </li>
                                @endforeach
                            </ol>
                        </nav>

                        <div class="vl-reader-outline-note">
                            Baca sesuai urutan atau pilih bagian
                            yang ingin kamu kunjungi kembali.
                        </div>
                    </div>
                </details>
            </aside>

        </div>
        @else
        <div class="vl-reader-card vl-reader-empty">
            Materi modul ini belum tersedia.
        </div>
        @endif

        <p class="verify-lesson-disclaimer">
            Materi pengantar edukatif; bukan nasihat keamanan
            atau keuangan profesional.
        </p>

    </div>
</section>

<script>
    (() => {
        const reader = document.querySelector('[data-lesson-reader]');

        if (!reader) return;

        const pages = [...reader.querySelectorAll('[data-reader-page]')];
        const jumps = [...reader.querySelectorAll('[data-reader-jump]')];

        const previous = reader.querySelector('[data-reader-previous]');
        const next = reader.querySelector('[data-reader-next]');
        const complete = reader.querySelector('[data-reader-complete]');
        const finished = reader.querySelector('[data-reader-finished]');

        const position = reader.querySelector('[data-reader-position]');
        const pagination = reader.querySelector('[data-reader-pagination]');
        const meter = reader.querySelector('[data-reader-meter]');
        const announcement = reader.querySelector('[data-reader-announcement]');
        const outline = reader.querySelector('[data-reader-outline]');

        const mobile = window.matchMedia('(max-width: 850px)');

        let current = 0;
        let isComplete = false;

        function render(moveFocus = false) {
            const isLastPage = current === pages.length - 1;

            pages.forEach((page, index) => {
                page.hidden = index !== current;
            });

            jumps.forEach((button, index) => {
                if (index === current) {
                    button.setAttribute('aria-current', 'step');
                } else {
                    button.removeAttribute('aria-current');
                }
            });

            previous.disabled = current === 0;
            next.hidden = isLastPage;

            complete.hidden = !isLastPage || isComplete;
            finished.hidden = !isLastPage || !isComplete;

            position.textContent =
                `Bagian ${current + 1} dari ${pages.length}`;

            pagination.textContent =
                `${current + 1} / ${pages.length}`;

            meter.style.width =
                `${((current + 1) / pages.length) * 100}%`;

            if (moveFocus) {
                const title = pages[current].querySelector('.vl-reader-title');

                title.focus({
                    preventScroll: true
                });

                reader.scrollIntoView({
                    block: 'start',
                    behavior: 'auto'
                });
            }
        }

        function goTo(index) {
            if (index < 0 || index >= pages.length) return;

            current = index;

            if (mobile.matches) {
                outline.open = false;
            }

            render(true);
        }

        previous.addEventListener('click', () => {
            goTo(current - 1);
        });

        next.addEventListener('click', () => {
            goTo(current + 1);
        });

        jumps.forEach(button => {
            button.addEventListener('click', () => {
                goTo(Number(button.dataset.readerJump));
            });
        });

        complete.addEventListener('click', () => {
            isComplete = true;

            render();

            announcement.textContent =
                'Materi ditandai selesai.';

            const finishedLink = finished.querySelector('a');
            if (finishedLink) finishedLink.focus();
        });

        function syncOutline() {
            outline.open = !mobile.matches;
        }

        mobile.addEventListener('change', syncOutline);

        syncOutline();
        render();
    })();
</script>
@endsection