@extends('layouts.app')

@section('title', 'Jelajah Dunia Digital dengan Aman')

@section('content')

{{-- HERO --}}
<section class="hero verify-hero">

    {{-- Teks dan statistik --}}
    <div class="hero-copy">

        <span class="verify-hero-label">
            <span
                class="verify-status-dot"
                aria-hidden="true"></span>

            KENALI RISIKONYA, LINDUNGI DIRIMU
        </span>

        <h1>
            Jelajah Dunia Digital<br>
            dengan <em>Aman!</em>
        </h1>

        <p class="verify-hero-description">
            Kenali modus penipuan, periksa pesan mencurigakan,
            dan bangun kebiasaan digital yang lebih aman
            bersama <span>VeriFy.</span>
        </p>

        {{-- Statistik mini --}}
        <div class="verify-hero-summary">

            <div
                class="verify-mini-stats"
                role="group"
                aria-label="Ringkasan platform VeriFy">
                {{-- Pemeriksaan URL --}}
                <div class="verify-mini-stat">

                    <span class="verify-mini-stat-value">
                        @if(isset($urlChecksCount))
                        <span
                            data-verify-counter="{{ (int) $urlChecksCount }}">
                            {{ number_format((int) $urlChecksCount, 0, ',', '.') }}
                        </span>
                        @else
                        <span aria-label="Data belum tersedia">
                            —
                        </span>
                        @endif
                    </span>

                    <span class="verify-mini-stat-label">
                        Pemeriksaan URL
                    </span>

                </div>

                {{-- Modul --}}
                <a
                    class="verify-mini-stat"
                    href="{{ route('modules') }}">
                    <span class="verify-mini-stat-value">
                        <span data-verify-counter="6">6</span>
                    </span>

                    <span class="verify-mini-stat-label">
                        Modul belajar
                    </span>
                </a>

                {{-- Simulator --}}
                <a
                    class="verify-mini-stat"
                    href="{{ route('simulator') }}">
                    <span class="verify-mini-stat-value">
                        <span data-verify-counter="6">6</span>
                    </span>

                    <span class="verify-mini-stat-label">
                        Topik simulasi
                    </span>
                </a>

            </div>

            @unless(isset($urlChecksCount))
            <p class="verify-mini-stats-note">
                Statistik pemeriksaan URL belum tersedia.
            </p>
            @endunless

        </div>

    </div>
    {{-- Akhir hero-copy --}}


    {{-- Ilustrasi ponsel dan kartu melayang --}}
    <div
        class="hero-art"
        role="group"
        aria-label="Ilustrasi edukasi perlindungan digital">

        {{-- Kartu pertama --}}
        <div class="float-card card-a">
            <x-icon name="lock" />
            <span>Lindungi data</span>
        </div>

        {{-- Ponsel --}}
        <div class="phone">

            <div class="notch"></div>

            <div
                class="phone-body verify-phone-demo"
                data-phone-demo>

                <span class="verify-demo-label">
                    ILUSTRASI EDUKASI
                </span>

                {{-- Tampilan pertama --}}
                <div
                    class="verify-demo-scene is-visible"
                    data-phone-scene>
                    <div class="phone-shield">
                        <x-icon name="shield" />
                    </div>

                    <h3>
                        Stay safe.<br>
                        Stay smart.
                    </h3>

                    <p>
                        Keamanan digital<br>
                        dimulai dari kamu.
                    </p>

                    <div class="phone-check">
                        ✓ Kenali • Cek • Lindungi
                    </div>
                </div>

                {{-- Tampilan kedua: contoh pesan fiktif --}}
                <div
                    class="verify-demo-scene"
                    data-phone-scene
                    hidden>
                    <div class="verify-demo-message">

                        <span class="verify-demo-sender">
                            Pengirim tidak dikenal
                        </span>

                        <p>
                            “Selamat! Kamu mendapat hadiah.
                            Kirim biaya admin sekarang!”
                        </p>

                        <span class="verify-demo-message-note">
                            Contoh pesan fiktif
                        </span>

                    </div>

                    <div class="verify-demo-reminder">

                        <span
                            class="verify-demo-reminder-icon"
                            aria-hidden="true">
                            !
                        </span>

                        <span>
                            Jangan terburu-buru.<br>
                            Periksa sebelum percaya.
                        </span>

                    </div>
                </div>

                {{-- Tampilan ketiga: langkah verifikasi --}}
                <div
                    class="verify-demo-scene"
                    data-phone-scene
                    hidden>
                    <div
                        class="verify-demo-check-icon"
                        aria-hidden="true">
                        <x-icon name="shield" />
                    </div>

                    <h3 class="verify-demo-check-title">
                        Berhenti sejenak.<br>
                        Cek lebih teliti.
                    </h3>

                    <ul class="verify-demo-checklist">
                        <li>Periksa identitas pengirim</li>
                        <li>Jangan bagikan OTP</li>
                        <li>Verifikasi lewat kanal resmi</li>
                    </ul>
                </div>

                {{-- Kontrol titik, tanpa tombol Jeda --}}
                <div class="verify-demo-controls">

                    <div
                        class="verify-demo-dots"
                        role="group"
                        aria-label="Pilih tampilan ilustrasi">
                        <button
                            class="verify-demo-dot is-active"
                            type="button"
                            data-phone-dot="0"
                            aria-label="Tampilan 1: pengantar"
                            aria-pressed="true"></button>

                        <button
                            class="verify-demo-dot"
                            type="button"
                            data-phone-dot="1"
                            aria-label="Tampilan 2: contoh pesan"
                            aria-pressed="false"></button>

                        <button
                            class="verify-demo-dot"
                            type="button"
                            data-phone-dot="2"
                            aria-label="Tampilan 3: langkah verifikasi"
                            aria-pressed="false"></button>
                    </div>

                </div>

            </div>
            {{-- Akhir phone-body --}}

        </div>
        {{-- Akhir phone --}}

        {{-- Kartu kedua --}}
        <div class="float-card card-b">
            <x-icon name="link" />
            <span>Periksa tautan</span>
        </div>

        {{-- Kartu ketiga --}}
        <div class="float-card card-c">
            <x-icon name="shield" />
            <span>Kenali penipuan</span>
        </div>

    </div>
    {{-- Akhir hero-art --}}

</section>
{{-- Akhir HERO --}}


{{-- EVERYTHING YOU NEED --}}
{{-- RINGKASAN FITUR VERIFY --}}
<section
    class="verify-features"
    aria-labelledby="verify-features-title">
    <div class="verify-features-container">

        {{-- Judul section --}}
        <div class="verify-features-heading">

            <div class="verify-features-heading-main">
                <span class="verify-section-label">
                    <span aria-hidden="true"></span>
                    FITUR VERIFY
                </span>

                <h2 id="verify-features-title">
                    Kenali risikonya.<br>
                    <span>Lebih bijak bertindak.</span>
                </h2>
            </div>

        </div>

        {{-- Daftar fitur --}}
        <div class="verify-features-grid">

            {{-- 1. Detect Scam --}}
            <article class="verify-feature-card">

                <div class="verify-feature-top">
                    <span
                        class="verify-feature-icon"
                        aria-hidden="true">
                        {{-- Kaca pembesar dengan simbol tautan --}}
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="10.5" cy="10.5" r="7.5" />

                            <path d="M16 16L21 21" />

                            <path d="
            M9.3 9.2L10.7 7.8
            A1.9 1.9 0 0 1 13.4 10.5
            L12 11.9
        " />

                            <path d="
            M11.7 11.8L10.3 13.2
            A1.9 1.9 0 0 1 7.6 10.5
            L9 9.1
        " />

                            <path d="M9.2 11.8L11.8 9.2" />
                        </svg>
                    </span>
                    <span class="verify-feature-number">
                        01
                    </span>
                </div>

                <span class="verify-feature-category">
                    PERIKSA
                </span>

                <h3>
                    Detect Scam
                </h3>

                <p>
                    Tinjau indikator mencurigakan pada pesan
                    dan tautan melalui pemeriksaan awal
                    berbasis aturan.
                </p>

                <div class="verify-feature-bottom">
                    <a
                        class="verify-feature-link"
                        href="{{ route('detect') }}">
                        <span>Periksa pesan atau tautan</span>

                        <span
                            class="verify-feature-arrow"
                            aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12H19M13 6L19 12L13 18" />
                            </svg>
                        </span>
                    </a>
                </div>

            </article>

            {{-- 2. Learning Modules --}}
            <article class="verify-feature-card">

                <div class="verify-feature-top">
                    <span
                        class="verify-feature-icon"
                        aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 5V21" />
                            <path d="
                                M12 5C9 3 5 3 2 4V19
                                C5 18 9 18 12 21
                                C15 18 19 18 22 19V4
                                C19 3 15 3 12 5Z
                            " />
                        </svg>
                    </span>

                    <span class="verify-feature-number">
                        02
                    </span>
                </div>

                <span class="verify-feature-category">
                    PELAJARI
                </span>

                <h3>
                    Learning Modules
                </h3>

                <p>
                    Pahami modus penipuan digital melalui
                    enam modul pembelajaran yang terstruktur
                    dan mudah diikuti.
                </p>

                <div class="verify-feature-bottom">
                    <a
                        class="verify-feature-link"
                        href="{{ route('modules') }}">
                        <span>Jelajahi modul</span>

                        <span
                            class="verify-feature-arrow"
                            aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12H19M13 6L19 12L13 18" />
                            </svg>
                        </span>
                    </a>
                </div>

            </article>

            {{-- 3. Scam Simulator --}}
            <article class="verify-feature-card">

                <div class="verify-feature-top">
                    <span
                        class="verify-feature-icon"
                        aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="
                                M7 6H17
                                C19 6 20 8 20.5 10
                                L22 17
                                C22.5 20 20 21 18 19
                                L15 16H9L6 19
                                C4 21 1.5 20 2 17
                                L3.5 10
                                C4 8 5 6 7 6Z
                            " />
                            <path d="M7 9V13M5 11H9" />
                            <circle cx="16" cy="10" r=".8" />
                            <circle cx="18" cy="12" r=".8" />
                        </svg>
                    </span>

                    <span class="verify-feature-number">
                        03
                    </span>
                </div>

                <span class="verify-feature-category">
                    BERLATIH
                </span>

                <h3>
                    Scam Simulator
                </h3>

                <p>
                    Uji pilihanmu melalui skenario fiktif.
                    Pelajari alasan di balik tindakan
                    yang disarankan dalam setiap latihan.
                </p>

                <div class="verify-feature-bottom">
                    <a
                        class="verify-feature-link"
                        href="{{ route('simulator') }}">
                        <span>Coba simulasi</span>

                        <span
                            class="verify-feature-arrow"
                            aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12H19M13 6L19 12L13 18" />
                            </svg>
                        </span>
                    </a>
                </div>

            </article>

            {{-- 4. Security Tips: bagian dari materi modul --}}
            <article class="verify-feature-card">

                <div class="verify-feature-top">
                    <span
                        class="verify-feature-icon"
                        aria-hidden="true">
                        <x-icon name="shield" />
                    </span>

                    <span class="verify-feature-number">
                        04
                    </span>
                </div>

                <span class="verify-feature-category">
                    LINDUNGI
                </span>

                <h3>
                    Security Tips
                </h3>

                <p>
                    Temukan panduan menjaga data pribadi,
                    melindungi akun, dan membangun kebiasaan
                    digital yang lebih aman di dalam modul.
                </p>

                <div class="verify-feature-bottom">
                    <a
                        class="verify-feature-link"
                        href="{{ route('lesson', ['id' => 5]) }}">
                        <span>Pelajari perlindungan akun</span>

                        <span
                            class="verify-feature-arrow"
                            aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12H19M13 6L19 12L13 18" />
                            </svg>
                        </span>
                    </a>
                </div>

            </article>

        </div>

        {{-- Catatan batasan fitur --}}
        <p class="verify-features-note">
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
                VeriFy membantu proses belajar dan pemeriksaan awal.
                Hasil deteksi bukan jaminan bahwa pesan atau tautan aman.
            </span>
        </p>

    </div>
</section>
@endsection