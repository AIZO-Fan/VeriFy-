@extends('layouts.app')

@section('title', $module['title'])

@section('content')

@php
/*
* Data dummy frontend.
* Belum menyimpan posisi membaca atau status selesai ke database.
*/
$isDemoModule = (string) $module['id'] === '1';

$demoSections = [
[
'type' => 'text',
'title' => 'Apa yang akan kamu pelajari?',
'paragraphs' => [
'Pesan yang terlihat meyakinkan belum tentu berasal dari pihak yang kamu percaya. Dalam modul ini, kamu akan belajar mengambil jeda sebelum mengikuti permintaan yang datang lewat pesan digital.',
],
'items' => [
'Mengenali pola dasar penipuan digital.',
'Memahami tanda yang perlu diperiksa lebih lanjut.',
'Memilih langkah verifikasi sebelum bertindak.',
],
],
[
'type' => 'text',
'title' => 'Mengenal penipuan digital',
'paragraphs' => [
'Penipuan digital dapat dimulai dari pesan, email, atau situs yang menyamar sebagai pihak lain. Pengirim dapat mengaku sebagai layanan yang kamu gunakan agar permintaannya terasa masuk akal.',
'Salah satu bentuknya adalah phishing: pesan yang berusaha membuatmu membuka tautan, membuka lampiran, atau memberikan informasi dengan memakai alasan tertentu, misalnya masalah pada akun.',
'Nama pengirim dan tampilan yang terlihat familiar bukan alasan yang cukup untuk langsung mengikuti instruksinya. Periksa permintaannya dan pastikan identitas pengirim melalui saluran lain.',
],
'items' => [],
],
[
'type' => 'text',
'title' => 'Bagaimana pesan tersebut memengaruhi keputusanmu?',
'paragraphs' => [
'Bayangkan kamu menerima pesan tentang akun yang akan diblokir. Pesan itu meminta tindakan segera melalui sebuah tautan. Kekhawatiran kehilangan akses dapat membuatmu ingin menyelesaikannya secepat mungkin.',
'Pada situasi lain, pengirim menawarkan hadiah atau keuntungan. Ceritanya berbeda, tetapi kamu tetap perlu memeriksa apa yang diminta sebelum mengikuti instruksi.',
],
'items' => [
'Mengaku sebagai pihak yang kamu kenal atau percaya.',
'Menyampaikan masalah atau tawaran untuk menarik perhatian.',
'Mengarahkanmu melakukan tindakan melalui saluran yang disediakan pengirim.',
],
],
[
'type' => 'text',
'title' => 'Tanda yang perlu diperhatikan',
'paragraphs' => [
'Gunakan tanda berikut sebagai alasan untuk berhenti dan memeriksa, bukan sebagai penilaian tunggal bahwa sebuah pesan pasti penipuan.',
],
'items' => [
'Pesan tidak terduga yang meminta pembaruan informasi akun atau pembayaran.',
'Permintaan untuk membuka tautan atau lampiran yang belum kamu pastikan sumbernya.',
'Tekanan untuk segera bertindak sebelum kamu sempat melakukan verifikasi.',
'Permintaan informasi pribadi atau kredensial melalui percakapan yang tidak kamu mulai.',
],
],
[
'type' => 'example',
'title' => 'Contoh kasus: pemberitahuan akun',
'paragraphs' => [
'Bayangkan kamu menerima pesan berikut, padahal kamu tidak sedang mengurus pemulihan akun.',
],
'message' => 'Pemberitahuan: akun Anda akan dinonaktifkan dalam 30 menit. Segera lakukan verifikasi melalui tautan pada pesan ini agar akses tetap aktif.',
'items' => [
'Ancaman penonaktifan akun dan batas waktu dapat mendorong keputusan terburu-buru.',
'Pesan mengarahkan penerima ke tautan yang belum diverifikasi.',
'Informasi perlu diperiksa dengan membuka aplikasi resmi secara mandiri atau menghubungi layanan melalui kontak yang sudah diketahui.',
],
],
[
'type' => 'text',
'title' => 'Apa yang bisa kamu lakukan?',
'paragraphs' => [
'Kamu tidak harus menyelesaikan permintaan melalui pesan yang masuk. Ambil jeda dan pilih jalur pemeriksaan yang tidak bergantung pada informasi dari pengirim.',
],
'items' => [
'Jangan langsung membuka tautan atau lampiran dari pesan yang mencurigakan.',
'Buka aplikasi atau situs resmi melalui jalur yang sudah kamu ketahui.',
'Hubungi pihak terkait memakai nomor atau kontak resmi yang kamu cari secara mandiri.',
'Jangan memberikan kata sandi atau informasi sensitif kepada pengirim yang menghubungimu secara tidak terduga.',
],
],
[
'type' => 'summary',
'title' => 'Ingat sebelum melanjutkan',
'paragraphs' => [
'Fokus pada apa yang diminta, bukan hanya siapa yang diklaim sebagai pengirim.',
],
'items' => [
'Ambil jeda sebelum bertindak.',
'Periksa permintaan dan sumber informasinya.',
'Verifikasi melalui saluran terpisah yang sudah kamu percaya.',
],
],
];

/*
* Modul pertama memakai materi dummy lengkap.
* Modul lainnya tetap memakai materi dari controller.
*/
$readerSections = $isDemoModule
? $demoSections
: collect($module['lessons'] ?? [])
->values()
->map(function ($text, $index) {
return [
'type' => 'text',
'title' => 'Materi ' . ($index + 1),
'paragraphs' => [$text],
'items' => [],
];
})
->all();

$sectionCount = count($readerSections);
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
                MODUL {{ str_pad((string) $module['id'], 2, '0', STR_PAD_LEFT) }}
            </span>

            <h1>{{ $module['title'] }}</h1>

            <p>{{ $module['description'] }}</p>
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
                        style="width: {{ 100 / $sectionCount }}%;"></span>
                </div>

                {{-- BAGIAN MATERI --}}
                <div class="vl-reader-content">
                    @foreach($readerSections as $section)
                    <section
                        class="vl-reader-page"
                        data-reader-page
                        aria-labelledby="reader-title-{{ $loop->index }}"
                        @if(!$loop->first) hidden @endif
                        >
                        <span class="vl-reader-page-label">
                            BAGIAN {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <h2
                            id="reader-title-{{ $loop->index }}"
                            class="vl-reader-title"
                            tabindex="-1">
                            {{ $section['title'] }}
                        </h2>

                        <div class="vl-reader-copy vl-reader-copy--{{ $section['type'] }}">

                            @foreach($section['paragraphs'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                            @endforeach

                            @if($section['type'] === 'example')
                            <figure class="verify-lesson-message">
                                <figcaption>
                                    CONTOH PESAN FIKTIF
                                </figcaption>

                                <blockquote>
                                    {{ $section['message'] ?? '' }}
                                </blockquote>
                            </figure>

                            <h3>Apa yang perlu diperiksa?</h3>
                            @endif

                            @if(!empty($section['items']))
                            <ul>
                                @foreach($section['items'] as $item)
                                <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            @endif

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

                    <a
                        class="verify-lesson-practice-link"
                        href="{{ route('simulator', ['topic' => $module['id']]) }}">
                        Latih pemahamanmu
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                <p class="vl-reader-demo-note">
                    Preview frontend — posisi membaca dan status
                    selesai belum disimpan ke akun.
                </p>

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
                                @foreach($readerSections as $section)
                                <li>
                                    <button
                                        class="vl-reader-outline-link"
                                        type="button"
                                        data-reader-jump="{{ $loop->index }}"
                                        @if($loop->first) aria-current="step" @endif
                                        >
                                        <span class="vl-reader-outline-number">
                                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <span>
                                            {{ $section['title'] }}
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
                'Materi ditandai selesai dalam preview. Progres belum disimpan ke akun.';

            finished.querySelector('a').focus();
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