
@extends('layouts.app')

@section('title', 'Detect Scam')

@section('content')

<div class="detect-page container">

    {{-- HEADER DI LUAR CARD --}}
    <div class="detect-header">

        <div class="detect-eyebrow">
            <span class="detect-dot"></span>
            KENALI RISIKONYA, LINDUNGI DIRIMU
        </div>

        <h1 class="detect-title">
            Detect scam
        </h1>

        <p class="detect-description">
            Kenali pesan dan tautan mencurigakan sebelum menjadi
            korban penipuan digital.
        </p>

    </div>

    {{-- KEDUA CARD --}}
    <section class="detect-grid">

        {{-- BAGIAN FORM DETEKSI --}}
        <div class="detect-form-area">

            <p class="detect-subtitle">
                Masukkan pesan atau tautan yang mencurigakan
                untuk mendapatkan analisis awal.
            </p>

                <textarea
                    id="content"
                    name="content"
                    maxlength="5000"
                    required
                    placeholder="Tempelkan pesan atau tautan mencurigakan di sini..."
                >{{ old('content', request('content')) }}</textarea>

                @error('content')
                    <p class="error">{{ $message }}</p>
                @enderror

                @error('mode')
                    <p class="error">{{ $message }}</p>
                @enderror

                <button class="button detect-submit" type="submit">
                    Periksa Sekarang
                    <span>›</span>
                </button>
            </form>

            <p class="fine-print">
                Demo berbasis aturan. Jangan masukkan OTP, kata sandi,
                atau data pribadi. Tautan tidak dibuka oleh sistem.
            </p>

        </div>

        {{-- BAGIAN HASIL ANALISIS --}}
        <aside class="analysis detect-analysis">

            <div class="analysis-heading">
                <div>
                    <h2>Hasil Analisis</h2>
                </div>
            </div>

            @if($result)

                <div class="notice {{ count($result['flags']) ? 'danger' : '' }}">
                    <strong>{{ $result['status'] }}</strong>
                    <small>
                        Hasil ini bukan kepastian bahwa pesan merupakan
                        penipuan atau aman.
                    </small>
                </div>

                <div class="info-box">
                    <h3>Indikator yang ditemukan</h3>

                    <ul>
                        @forelse($result['flags'] as $flag)
                            <li>{{ $flag }}</li>
                        @empty
                            <li>Belum ditemukan indikator dari aturan demo.</li>
                        @endforelse
                    </ul>
                </div>

            @else

                <div class="empty-state">
                    <div class="empty-icon">
                        <x-icon name="search" />
                    </div>

                    <h3>Siap membantu memeriksa</h3>

                    <p>
                        Hasil analisis awal akan muncul setelah kamu
                        mengirim pesan atau URL.
                    </p>
                </div>

            @endif

            <div class="info-box recommendation">
                <h3>Rekomendasi</h3>

                <p>
                    Jangan klik tautan atau membagikan informasi pribadi
                    sebelum melakukan verifikasi melalui saluran resmi.
                </p>
            </div>

        </aside>

    </section>

</div>

@endsection
