@extends('layouts.app')

@section('title', 'Scam Simulator')

@section('content')

@php
/*
* Keterangan tampilan untuk enam topik dari paket awal.
* Data judul dan ikon tetap berasal dari $modules.
*/
$simulatorDescriptions = [
1 => 'Kenali tanda awal penipuan digital dan tentukan respons yang tepat.',
2 => 'Hadapi upaya manipulasi yang memanfaatkan rasa percaya dan kepanikan.',
3 => 'Cermati pesan dan tautan mencurigakan sebelum mengambil tindakan.',
4 => 'Tinjau penawaran belanja dan investasi yang terlihat terlalu menggiurkan.',
5 => 'Latih keputusan untuk menjaga akses akun dan informasi pribadimu.',
6 => 'Pelajari langkah awal saat menemukan atau mengalami dugaan penipuan.',
];
@endphp

<section
    class="verify-simulator"
    aria-labelledby="verify-simulator-title">
    <div class="verify-simulator-container">

        {{-- HEADING SIMULATOR --}}
        <div class="verify-simulator-heading">

            <span class="verify-simulator-eyebrow">
                <span aria-hidden="true"></span>
                RUANG LATIHAN DIGITAL
            </span>

            <h1 id="verify-simulator-title">
                Scam <span>Simulator.</span>
            </h1>

            <p>
                Kalau pesan ini masuk ke ponselmu,
                apa yang akan kamu lakukan?
                Pilih topik dan coba tentukan langkahmu.
            </p>

            <div class="verify-simulator-heading-meta">

                <span>
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="2" />
                        <rect x="14" y="3" width="7" height="7" rx="2" />
                        <rect x="3" y="14" width="7" height="7" rx="2" />
                        <rect x="14" y="14" width="7" height="7" rx="2" />
                    </svg>

                    {{ count($modules) }} topik latihan
                </span>

                <span class="verify-simulator-meta-divider" aria-hidden="true"></span>

                <span>
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M12 3L4 6V12C4 17 12 21 12 21S20 17 20 12V6Z" />
                        <path d="M8 12L11 15L16 9" />
                    </svg>

                    Skenario fiktif
                </span>

            </div>

        </div>


        {{-- DAFTAR SIMULATOR --}}
        <div class="verify-simulator-grid">

            @forelse($modules as $module)

            <article class="verify-simulator-card">

                <div class="verify-simulator-card-top">

                    {{-- Menggunakan $loop->iteration agar nomor urut tampil rapi (01, 02, 03...) --}}
                    <span class="verify-simulator-badge">
                        SIMULATOR
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>

                    <span
                        class="verify-simulator-card-number"
                        aria-hidden="true">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>

                </div>

                {{-- Ilustrasi lokal CSS/SVG --}}
                <div
                    class="verify-simulator-art"
                    aria-hidden="true">
                    <div class="verify-simulator-art-grid"></div>

                    <div class="verify-simulator-orbit"></div>

                    <div class="verify-simulator-art-icon">
                        <x-icon :name="$module['icon']" />
                    </div>

                    <span class="verify-simulator-art-plus">+</span>

                    <span class="verify-simulator-art-dot"></span>
                </div>

                <div class="verify-simulator-card-content">

                    <h2>{{ $module['title'] }}</h2>

                    <p>
                        {{ $simulatorDescriptions[$module['id']]
                                ?? 'Cermati skenario dan latih kemampuanmu mengenali dugaan penipuan.' }}
                    </p>

                </div>

                <div class="verify-simulator-card-bottom">

                    <span class="verify-simulator-exercise-label">
                        Latihan berbasis kasus
                    </span>

                    <button
                        class="verify-simulator-start"
                        type="button"
                        data-story-sim="{{ $module['id'] }}"
                        data-story-title="{{ $module['title'] }}"
                        aria-haspopup="dialog"
                        aria-controls="verify-story-dialog"
                        aria-label="Mulai simulasi {{ $module['title'] }}">
                        <span>Mulai Simulasi</span>

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M5 12H19M13 6L19 12L13 18" />
                        </svg>
                    </button>

                </div>

            </article>

            @empty

            <div class="verify-simulator-empty">
                <h2>Belum ada topik latihan</h2>
                <p>Topik simulasi akan ditampilkan di sini.</p>
            </div>

            @endforelse

        </div>


        {{-- CATATAN --}}
        <p class="verify-simulator-note">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 11V16M12 7.5V8" />
            </svg>

            <span>
                Seluruh skenario merupakan latihan fiktif.
                Tidak ada pesan, tautan, atau transaksi nyata.
            </span>

        </p>

    </div>
</section>


{{-- =========================================
     SIMULATOR CERITA INTERAKTIF
     Seluruh identitas dan transaksi fiktif.
     ========================================= --}}

<dialog
    id="verify-story-dialog"
    class="verify-story-dialog"
    aria-labelledby="verify-story-title"
    aria-describedby="verify-story-disclaimer"
    data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    data-user-id="{{ auth()->id() ?? '' }}"
    data-login-url="{{ \Illuminate\Support\Facades\Route::has('login') ? route('login') : '' }}">

    <div class="verify-story-shell">

        {{-- HEADER SIMULATOR --}}
        <header class="verify-story-header">

            <div class="verify-story-heading">

                <span class="verify-story-eyebrow">
                    <span
                        class="verify-story-status-dot"
                        aria-hidden="true"></span>

                    CERITA INTERAKTIF
                </span>

                <h2
                    id="verify-story-title"
                    class="verify-story-title">
                    Simulasi penipuan digital
                </h2>

            </div>

            <button
                class="verify-story-close"
                type="button"
                data-story-close
                aria-label="Tutup simulator">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    aria-hidden="true">
                    <path d="M6 6L18 18M18 6L6 18" />
                </svg>

            </button>

        </header>


        {{-- BATASAN SIMULASI --}}
        <p
            id="verify-story-disclaimer"
            class="verify-story-disclaimer">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true">
                <path d="M12 3L4 6V12C4 17 12 21 12 21S20 17 20 12V6Z" />
                <path d="M8 12L11 15L16 9" />
            </svg>

            <span>
                Simulasi edukasi. Seluruh identitas, pesan, dan pembayaran
                bersifat fiktif. Jangan masukkan data pribadi.
            </span>

        </p>


        {{-- POSISI DALAM CERITA --}}
        <div
            class="verify-story-progress"
            aria-label="Tahapan simulasi">

            <ol class="verify-story-stages">

                <li
                    class="verify-story-stage is-active"
                    data-story-stage="situation"
                    aria-current="step">

                    <span
                        class="verify-story-stage-number"
                        aria-hidden="true">
                        1
                    </span>

                    <span>Situasi</span>

                </li>

                <li
                    class="verify-story-stage"
                    data-story-stage="decision">

                    <span
                        class="verify-story-stage-number"
                        aria-hidden="true">
                        2
                    </span>

                    <span>Keputusan</span>

                </li>

                <li
                    class="verify-story-stage"
                    data-story-stage="consequence">

                    <span
                        class="verify-story-stage-number"
                        aria-hidden="true">
                        3
                    </span>

                    <span>Dampak</span>

                </li>

                <li
                    class="verify-story-stage"
                    data-story-stage="reflection">

                    <span
                        class="verify-story-stage-number"
                        aria-hidden="true">
                        4
                    </span>

                    <span>Pelajaran</span>

                </li>

            </ol>

        </div>


        {{-- AREA UTAMA --}}
        <div class="verify-story-body">

            {{-- KONTEKS AWAL --}}
            <section
                id="verify-story-context"
                class="verify-story-context"
                aria-labelledby="verify-story-context-title">

                <span class="verify-story-section-label">
                    BAYANGKAN SITUASI INI
                </span>

                <h3
                    id="verify-story-context-title"
                    tabindex="-1">
                    Pesan baru masuk ke ponselmu
                </h3>

                <p id="verify-story-context-description">
                    Baca percakapannya dan tentukan tindakanmu.
                    Pilihanmu akan memengaruhi kelanjutan cerita.
                </p>

            </section>


            {{-- PERCAKAPAN --}}
            <section
                id="verify-story-conversation"
                class="verify-story-conversation"
                aria-labelledby="verify-story-contact-name">

                {{-- IDENTITAS PENGIRIM FIKTIF --}}
                <div class="verify-story-chat-header">

                    <span
                        id="verify-story-contact-avatar"
                        class="verify-story-contact-avatar"
                        aria-hidden="true">
                        A
                    </span>

                    <div class="verify-story-contact-details">

                        <h3 id="verify-story-contact-name">
                            Pengirim fiktif
                        </h3>

                        <p id="verify-story-contact-status">
                            Nomor tidak dikenal · Simulasi
                        </p>

                    </div>

                    <span class="verify-story-chat-badge">
                        FIKTIF
                    </span>

                </div>


                {{-- PESAN DIISI OLEH JAVASCRIPT --}}
                <div class="verify-story-chat-content">

                    <div
                        class="verify-story-chat-date"
                        aria-hidden="true">
                        Hari ini
                    </div>

                    <div
                        id="verify-story-messages"
                        class="verify-story-messages"
                        role="log"
                        aria-label="Percakapan simulasi"
                        aria-live="polite"
                        aria-relevant="additions text"
                        aria-atomic="false">
                    </div>


                    {{-- INDIKATOR MENGETIK --}}
                    <div
                        id="verify-story-typing"
                        class="verify-story-typing"
                        hidden
                        aria-hidden="true">

                        <span></span>
                        <span></span>
                        <span></span>

                    </div>

                </div>

            </section>


            {{-- PILIHAN TINDAKAN --}}
            <section
                id="verify-story-decision"
                class="verify-story-decision"
                aria-labelledby="verify-story-decision-title"
                hidden>

                <div class="verify-story-decision-heading">

                    <span class="verify-story-section-label">
                        GILIRANMU
                    </span>

                    <h3
                        id="verify-story-decision-title"
                        tabindex="-1">
                        Apa yang ingin kamu lakukan?
                    </h3>

                    <p id="verify-story-decision-description">
                        Pilih tindakan untuk melanjutkan cerita.
                    </p>

                </div>

                {{-- TOMBOL PILIHAN DIBUAT OLEH JAVASCRIPT --}}
                <div
                    id="verify-story-choices"
                    class="verify-story-choices">
                </div>

            </section>


            {{-- PANEL PEMBAYARAN FIKTIF --}}
            <section
                id="verify-story-payment"
                class="verify-story-payment"
                aria-labelledby="verify-story-payment-title"
                hidden>

                <div class="verify-story-payment-heading">

                    <span class="verify-story-payment-icon" aria-hidden="true">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect
                                x="3"
                                y="5"
                                width="18"
                                height="14"
                                rx="3" />
                            <path d="M3 10H21M7 15H10" />
                        </svg>

                    </span>

                    <div>

                        <span class="verify-story-section-label">
                            BUKAN TRANSAKSI NYATA
                        </span>

                        <h3
                            id="verify-story-payment-title"
                            tabindex="-1">
                            Konfirmasi pembayaran simulasi
                        </h3>

                    </div>

                </div>

                <p class="verify-story-payment-description">
                    Tidak ada uang yang dipindahkan.
                    Kamu masih bisa membatalkan dan memeriksa dahulu.
                </p>

                <dl class="verify-story-payment-details">

                    <div>
                        <dt>Tujuan pembayaran</dt>
                        <dd id="verify-story-payment-purpose">
                            Biaya administrasi hadiah
                        </dd>
                    </div>

                    <div>
                        <dt>Penerima</dt>
                        <dd id="verify-story-payment-recipient">
                            Penerima Fiktif
                        </dd>
                    </div>

                    <div class="verify-story-payment-total">
                        <dt>Nominal simulasi</dt>
                        <dd id="verify-story-payment-amount">
                            Rp25.000
                        </dd>
                    </div>

                </dl>

                <div
                    id="verify-story-payment-processing"
                    class="verify-story-payment-processing"
                    role="status"
                    hidden>

                    <span
                        class="verify-story-spinner"
                        aria-hidden="true"></span>

                    <span>Memproses pembayaran fiktif...</span>

                </div>

                <div
                    id="verify-story-payment-actions"
                    class="verify-story-payment-actions">

                    <button
                        class="verify-story-button verify-story-button-primary"
                        type="button"
                        data-story-payment="confirm">
                        Lanjutkan pembayaran simulasi
                    </button>

                    <button
                        class="verify-story-button verify-story-button-secondary"
                        type="button"
                        data-story-payment="cancel">
                        Batalkan dan periksa dahulu
                    </button>

                </div>

            </section>


            {{-- KONSEKUENSI PILIHAN --}}
            <section
                id="verify-story-consequence"
                class="verify-story-consequence"
                aria-labelledby="verify-story-consequence-title"
                hidden>

                <span class="verify-story-section-label">
                    DAMPAK PILIHANMU
                </span>

                <h3
                    id="verify-story-consequence-title"
                    tabindex="-1">
                    Apa yang terjadi selanjutnya?
                </h3>

                <p id="verify-story-consequence-description"></p>

                <div
                    id="verify-story-impact"
                    class="verify-story-impact"
                    hidden>

                    <span id="verify-story-impact-label">
                        Dampak dalam simulasi
                    </span>

                    <strong id="verify-story-impact-value"></strong>

                </div>

                <p class="verify-story-consequence-note">
                    Ini adalah hasil dari jalur cerita yang kamu pilih,
                    bukan prediksi pasti untuk setiap kejadian nyata.
                </p>

                <button
                    class="verify-story-button verify-story-button-primary"
                    type="button"
                    data-story-show-reflection>
                    Pahami keputusan ini
                </button>

            </section>


            {{-- PEMBAHASAN --}}
            <section
                id="verify-story-reflection"
                class="verify-story-reflection"
                aria-labelledby="verify-story-reflection-title"
                hidden>

                <div class="verify-story-reflection-heading">

                    <span class="verify-story-section-label">
                        BERHENTI SEJENAK, PAHAMI PILIHANMU
                    </span>

                    <h3
                        id="verify-story-reflection-title"
                        tabindex="-1">
                        Apa yang bisa dipelajari?
                    </h3>

                    <p id="verify-story-reflection-summary"></p>

                </div>


                <div class="verify-story-learning-grid">

                    <article class="verify-story-learning-card">

                        <span
                            class="verify-story-learning-number"
                            aria-hidden="true">
                            01
                        </span>

                        <h4>Apa yang terjadi?</h4>

                        <p id="verify-story-learning-what"></p>

                    </article>

                    <article class="verify-story-learning-card">

                        <span
                            class="verify-story-learning-number"
                            aria-hidden="true">
                            02
                        </span>

                        <h4>Mengapa keputusan ini penting?</h4>

                        <p id="verify-story-learning-why"></p>

                    </article>

                    <article class="verify-story-learning-card">

                        <span
                            class="verify-story-learning-number"
                            aria-hidden="true">
                            03
                        </span>

                        <h4>Alternatif yang lebih aman</h4>

                        <p id="verify-story-learning-alternative"></p>

                    </article>

                </div>


                {{-- TANDA YANG DITEMUI DALAM CERITA --}}
                <div
                    id="verify-story-signals"
                    class="verify-story-signals"
                    hidden>

                    <h4>Tanda yang perlu diperhatikan</h4>

                    <ul id="verify-story-signals-list"></ul>

                </div>


                {{-- RIWAYAT KEPUTUSAN PENGGUNA --}}
                <details class="verify-story-history">

                    <summary>
                        Lihat perjalanan keputusanmu
                    </summary>

                    <ol id="verify-story-history-list"></ol>

                </details>

                {{-- SKOR LATIHAN --}}
                <section
                    id="verify-story-score"
                    class="verify-story-score"
                    aria-labelledby="verify-story-score-title"
                    hidden>

                    <div class="verify-story-score-heading">

                        <div>
                            <span class="verify-story-section-label">
                                RINGKASAN LATIHAN
                            </span>

                            <h4 id="verify-story-score-title">
                                Skor keputusanmu
                            </h4>
                        </div>

                        <p class="verify-story-score-number">
                            <span id="verify-story-score-value">0</span>
                            <span class="verify-story-score-divider">/</span>
                            <span id="verify-story-score-max">100</span>
                        </p>

                    </div>

                    <progress
                        id="verify-story-score-progress"
                        class="verify-story-score-progress"
                        value="0"
                        max="100"
                        aria-label="Skor latihan">
                    </progress>

                    <ul
                        id="verify-story-score-breakdown"
                        class="verify-story-score-breakdown">
                    </ul>

                    <p class="verify-story-score-note">
                        Skor menggambarkan keputusan pada cerita ini,
                        bukan jaminan kemampuan menghadapi semua situasi nyata.
                    </p>

                </section>

                <div class="verify-story-result-actions">

                    <button
                        class="verify-story-button verify-story-button-secondary"
                        type="button"
                        data-story-restart>
                        Coba keputusan lain
                    </button>

                    <button
                        class="verify-story-button verify-story-button-primary"
                        type="button"
                        data-story-finish>
                        Selesai
                    </button>

                </div>

            </section>

            {{-- KONFIRMASI SIMPAN — MASIH DI DALAM DIALOG YANG SAMA --}}
            <section
                id="verify-story-save-confirm"
                class="verify-story-save-confirm"
                aria-labelledby="verify-story-save-title"
                hidden>

                <div class="verify-story-save-symbol" aria-hidden="true">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M6 3H18V21L12 17L6 21V3Z" />
                        <path d="M9 8H15M9 11H13" />
                    </svg>

                </div>

                <span class="verify-story-section-label">
                    RIWAYAT BELAJARMU
                </span>

                <h3
                    id="verify-story-save-title"
                    tabindex="-1">
                    Simpan hasil latihan ini?
                </h3>

                <p
                    id="verify-story-save-description"
                    class="verify-story-save-description">
                </p>

                <div class="verify-story-save-actions">

                    <button
                        id="verify-story-save-button"
                        class="verify-story-button verify-story-button-primary"
                        type="button">
                        {{ auth()->check() ? 'Simpan & selesai' : 'Login & simpan' }}
                    </button>

                    <button
                        id="verify-story-save-later"
                        class="verify-story-button verify-story-button-secondary"
                        type="button">
                        Selesai tanpa menyimpan
                    </button>

                </div>

                <p
                    id="verify-story-save-status"
                    class="verify-story-save-status"
                    role="status"
                    aria-live="polite">
                </p>

                <button
                    class="verify-story-back-result"
                    type="button"
                    data-story-back-result>
                    ← Kembali ke hasil
                </button>

            </section>


            {{-- STATUS RINGKAS UNTUK PEMBACA LAYAR --}}
            <p
                id="verify-story-announcement"
                class="sr-only"
                role="status"
                aria-live="polite"
                aria-atomic="true">
            </p>

        </div>


        {{-- FOOTER DIALOG --}}
        <footer class="verify-story-footer">

            <p>
                Tidak perlu terburu-buru. Kamu boleh berhenti
                dan mempertimbangkan kembali pilihanmu.
            </p>

            <button
                class="verify-story-exit"
                type="button"
                data-story-close>
                Keluar dari simulasi
            </button>

        </footer>

    </div>

</dialog>

<script
    src="{{ asset('js/verify-story-data.js') }}"
    defer></script>

<script
    src="{{ asset('js/verify-story.js') }}"
    defer></script>

{{-- Skrip otomatis untuk membuka pop-up simulator sesuai parameter ?topic=X --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const topicId = urlParams.get('topic');

        if (topicId) {
            setTimeout(() => {
                const targetButton = document.querySelector(`[data-story-sim="${topicId}"]`);
                if (targetButton) {
                    targetButton.click();
                }
            }, 400);
        }
    });
</script>

@endsection