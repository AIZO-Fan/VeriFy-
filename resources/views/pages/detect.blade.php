@extends('layouts.app')

@section('title', 'Detect Scam')

@section('content')
@php
$analysisResult = $result ?? null;
$flags          = $analysisResult['flags'] ?? [];
$hasFlags       = count($flags) > 0;
$score          = $analysisResult['score'] ?? 0;
$threatUrls     = $analysisResult['threat_urls'] ?? [];
$urlsChecked    = $analysisResult['urls_checked'] ?? 0;
$hasThreat      = count($threatUrls) > 0;

// Tentukan warna skor
$scoreClass = match(true) {
    $score >= 60 => 'is-danger',
    $score >= 30 => 'is-warning',
    $score > 0   => 'is-caution',
    default      => 'is-neutral',
};
@endphp

<section class="verify-detect" aria-labelledby="detect-page-title">
    <div class="verify-detect-container">

        <header class="verify-detect-header">
            <div class="verify-detect-eyebrow">
                <span aria-hidden="true"></span>
                SEBELUM PERCAYA, PERIKSA DULU
            </div>

            <h1 id="detect-page-title">
                Detect <span>Scam</span>
            </h1>

            <p>
                Kenali tanda mencurigakan pada pesan dan tautan.
                Ambil waktu untuk memeriksa sebelum bertindak.
            </p>
        </header>

        <div class="verify-detect-workspace">

            {{-- INPUT --}}
            <section
                class="verify-detect-card verify-detect-input"
                aria-labelledby="detect-input-title">
                <div class="verify-detect-card-heading">
                    <span class="verify-detect-step" aria-hidden="true">01</span>

                    <div>
                        <h2 id="detect-input-title">Periksa pesan mencurigakan</h2>
                        <p>Tempel pesan atau tautan untuk mendapatkan analisis awal.</p>
                    </div>
                </div>

                <form
                    id="verify-detect-form"
                    action="{{ route('analyze') }}"
                    method="POST"
                    data-has-result="{{ $analysisResult ? 'true' : 'false' }}">
                    @csrf
                    <input type="hidden" name="mode" id="verify-detect-mode" value="text">

                    <div class="verify-detect-field-heading">
                        <label for="content">Pesan atau tautan</label>
                        <span id="verify-detect-character-count">
                            0 / 5.000
                        </span>
                    </div>

                    <textarea
                        id="content"
                        name="content"
                        maxlength="5000"
                        required
                        placeholder="Tempel pesan atau tautan yang ingin kamu periksa…"
                        aria-describedby="verify-detect-privacy{{ $errors->has('content') ? ' verify-detect-content-error' : '' }}"
                        @error('content') aria-invalid="true" @enderror>{{ old('content', request('content')) }}</textarea>

                    @error('content')
                    <p
                        id="verify-detect-content-error"
                        class="verify-detect-error"
                        role="alert">{{ $message }}</p>
                    @enderror

                    @error('mode')
                    <p class="verify-detect-error" role="alert">
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="verify-detect-examples">
                        <span>Belum punya pesan? Coba contoh</span>

                        <div class="verify-detect-example-list">
                            <button type="button" data-detect-example="prize">
                                Pesan hadiah
                            </button>

                            <button type="button" data-detect-example="otp">
                                Permintaan OTP
                            </button>

                            <button type="button" data-detect-example="link">
                                Tautan login
                            </button>
                        </div>
                    </div>

                    <div class="verify-detect-form-actions">
                        <button
                            class="verify-detect-submit"
                            type="submit">
                            <span>Periksa sekarang</span>

                            <svg
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>

                        <button
                            class="verify-detect-clear"
                            id="verify-detect-clear"
                            type="button">
                            Kosongkan
                        </button>
                    </div>

                    <div
                        id="verify-detect-privacy"
                        class="verify-detect-privacy">
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <rect x="5" y="10" width="14" height="11" rx="3" />
                            <path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3" />
                        </svg>

                        <p>
                            Jangan masukkan OTP, PIN, kata sandi,
                            atau data pribadi. Gunakan contoh fiktif
                            jika pesan memuat informasi sensitif.
                        </p>
                    </div>
                </form>
            </section>

            {{-- HASIL --}}
            <section
                id="verify-detect-result"
                class="verify-detect-card verify-detect-result"
                aria-labelledby="detect-result-title">
                <div class="verify-detect-card-heading">
                    <span class="verify-detect-step" aria-hidden="true">02</span>

                    <div>
                        <h2 id="detect-result-title">Hasil pemeriksaan</h2>
                        <p>Pahami tandanya, lalu tentukan langkah berikutnya.</p>
                    </div>
                </div>

                <div
                    id="verify-detect-stale"
                    class="verify-detect-stale"
                    role="status"
                    hidden>
                    <span class="verify-detect-stale-mark" aria-hidden="true">!</span>

                    <div>
                        <strong>Pesan sudah berubah</strong>
                        <p>
                            Hasil di bawah berasal dari pesan sebelumnya.
                            Pilih “Periksa sekarang” untuk memperbaruinya.
                        </p>
                    </div>
                </div>

                <div
                    id="verify-detect-loading"
                    class="verify-detect-loading"
                    role="status"
                    tabindex="-1"
                    hidden>
                    <span class="verify-detect-spinner" aria-hidden="true"></span>

                    <h3>Sedang memeriksa pesan…</h3>

                    <p>
                        Menyiapkan hasil pemeriksaan.
                        Mohon tunggu sebentar.
                    </p>
                </div>

                <div id="verify-detect-result-body" class="verify-detect-result-body">

                    @if($analysisResult)

                    {{-- STATUS + SKOR --}}
                    <div class="verify-detect-status {{ $hasThreat ? 'is-danger' : ($hasFlags ? 'is-warning' : 'is-neutral') }}">
                        <span class="verify-detect-status-label">
                            @if($hasThreat)
                                ⚠ ANCAMAN TERDETEKSI
                            @elseif($hasFlags)
                                PERLU DIPERHATIKAN
                            @else
                                PEMERIKSAAN AWAL SELESAI
                            @endif
                        </span>

                        <h3>{{ $analysisResult['status'] }}</h3>

                        {{-- Skor Risiko --}}
                        <div class="verify-detect-score-bar" aria-label="Skor risiko: {{ $score }} dari 100">
                            <div class="verify-detect-score-track">
                                <div
                                    class="verify-detect-score-fill {{ $scoreClass }}"
                                    style="width: {{ $score }}%"
                                    role="progressbar"
                                    aria-valuenow="{{ $score }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <span class="verify-detect-score-label">Skor risiko: <strong>{{ $score }}/100</strong></span>
                        </div>

                        @if($urlsChecked > 0)
                        <p class="verify-detect-api-note">
                            {{ $urlsChecked }} tautan diperiksa via Google Safe Browsing.
                            Hasil ini bukan kepastian bahwa pesan merupakan penipuan atau aman.
                        </p>
                        @else
                        <p>
                            Hasil ini bukan kepastian bahwa pesan
                            merupakan penipuan atau aman.
                        </p>
                        @endif
                    </div>

                    {{-- SAFE BROWSING ALERT --}}
                    @if($hasThreat)
                    <div class="verify-detect-threat-alert" role="alert">
                        <span class="verify-detect-threat-icon" aria-hidden="true">🛡</span>
                        <div>
                            <strong>Google Safe Browsing mendeteksi ancaman pada tautan berikut:</strong>
                            <ul class="verify-detect-threat-list">
                                @foreach($threatUrls as $tUrl)
                                <li>{{ $tUrl }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <div class="verify-detect-indicators">
                        <div class="verify-detect-section-heading">
                            <h3>Indikator yang ditemukan</h3>
                            <span>{{ count($flags) }}</span>
                        </div>

                        @if($hasFlags)
                        <ol class="verify-detect-findings">
                            @foreach($flags as $flag)
                            <li class="verify-detect-finding">
                                <span class="verify-detect-finding-number" aria-hidden="true">
                                    {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <p>{{ $flag }}</p>
                            </li>
                            @endforeach
                        </ol>
                        @else
                        <p class="verify-detect-no-flags">
                            Belum ditemukan indikator berdasarkan
                            pemeriksaan ini. Tetap periksa identitas
                            pengirim dan sumber informasi secara terpisah.
                        </p>
                        @endif
                    </div>

                    @else

                    <div class="verify-detect-waiting">
                        <div class="verify-detect-waiting-heading">
                            <span class="verify-detect-waiting-icon" aria-hidden="true">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6l-8-3Z" />
                                    <path d="m8.5 12 2.3 2.3 4.7-4.8" />
                                </svg>
                            </span>

                            <div>
                                <span class="verify-detect-waiting-label">
                                    MENUNGGU PEMERIKSAAN
                                </span>

                                <h3>Kenali tandanya sebelum percaya.</h3>
                            </div>
                        </div>

                        <p class="verify-detect-waiting-description">
                            Masukkan pesan atau tautan, lalu pilih “Periksa sekarang”.
                            Ringkasan dan indikator yang ditemukan akan tampil di sini.
                        </p>

                        <div class="verify-detect-preview">
                            <span class="verify-detect-preview-label">
                                Fokus pemeriksaan
                            </span>

                            <div class="verify-detect-preview-items">
                                <span>Isi pesan</span>
                                <span>Pola permintaan</span>
                                <span>Tautan dalam pesan</span>
                            </div>
                        </div>

                        <div class="verify-detect-waiting-note">
                            <span>Cara membaca hasil</span>

                            <p>
                                Baca indikatornya, bukan hanya label hasilnya.
                                Pemeriksaan awal bukan jaminan keamanan.
                            </p>
                        </div>

                    </div>

                    @endif

                    <div class="verify-detect-recommendation">
                        <span class="verify-detect-small-label">
                            LANGKAH YANG DISARANKAN
                        </span>

                        <h3>Verifikasi melalui sumber terpisah.</h3>

                        <p>
                            Jangan langsung mengikuti instruksi pengirim.
                            Periksa informasi melalui aplikasi atau saluran
                            resmi yang kamu akses secara mandiri.
                        </p>
                    </div>

                </div> {{-- penutup verify-detect-result-body --}}

            </section>

        </div>

        <p class="verify-detect-disclaimer">
            Demo berbasis aturan, bukan jaminan keamanan.
            Tautan yang dimasukkan tidak dibuka oleh sistem.
        </p>

    </div>
</section>
@endsection