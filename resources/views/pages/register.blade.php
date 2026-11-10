@extends('layouts.app')

@section('title', 'Register')

@section('content')

<section
    class="verify-register"
    aria-labelledby="verify-register-title">

    <div class="verify-register-container">

        {{-- =========================================
             PENGANTAR KIRI
             ========================================= --}}
        <div class="verify-register-intro">

            <span class="verify-register-eyebrow">
                <span aria-hidden="true"></span>
                MULAI LANGKAHMU
            </span>

            <h1 id="verify-register-title">
                Lebih kenal risikonya.
                <span>Lebih siap menjaganya.</span>
            </h1>

            <p class="verify-register-intro-description">
                Buat akun VeriFy dan mulai perjalanan belajarmu
                untuk mengenali penipuan digital.
            </p>

            <div class="verify-register-benefits">

                {{-- Manfaat pertama --}}
                <div class="verify-register-benefit">

                    <span
                        class="verify-register-benefit-icon"
                        aria-hidden="true">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 7V21" />
                            <path d="M3 4H8C10 4 12 5 12 7C12 5 14 4 16 4H21V18H16C14 18 12 19 12 21C12 19 10 18 8 18H3Z" />
                        </svg>

                    </span>

                    <div>

                        <h2>Kenali berbagai modus</h2>

                        <p>
                            Pelajari tanda yang perlu diperhatikan
                            melalui modul edukasi.
                        </p>

                    </div>

                </div>

                {{-- Manfaat kedua --}}
                <div class="verify-register-benefit">

                    <span
                        class="verify-register-benefit-icon"
                        aria-hidden="true">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M7 7H17C19 7 20 9 20.5 12L21 16C21.3 18 19 19 17.8 17.5L15.5 15H8.5L6.2 17.5C5 19 2.7 18 3 16L3.5 12C4 9 5 7 7 7Z" />
                            <path d="M7 10V14M5 12H9" />
                            <circle cx="16" cy="11" r=".8" />
                            <circle cx="18" cy="13" r=".8" />
                        </svg>

                    </span>

                    <div>

                        <h2>Latih keputusanmu</h2>

                        <p>
                            Hadapi cerita fiktif dan pahami
                            dampak dari pilihan yang kamu ambil.
                        </p>

                    </div>

                </div>

            </div>

            <p class="verify-register-intro-note">
                Belajar. Berlatih. Lebih waspada.
            </p>

        </div>


        {{-- =========================================
             KARTU REGISTER KANAN
             Judul, form, dan tautan Login tetap
             berada di dalam kartu yang sama.
             ========================================= --}}
        <div class="verify-register-card">

            <div class="verify-register-card-heading">

                <span class="verify-register-card-label">
                    SELAMAT DATANG DI VERIFY
                </span>

                <h2>Buat akun</h2>

                <p>
                    Isi informasi berikut untuk memulai.
                </p>

            </div>


            {{-- Error umum, tanpa mengulang error tiap kolom --}}
            @foreach ($errors->getMessages() as $field => $messages)
            @if (!in_array($field, ['name', 'email', 'password']))
            @foreach ($messages as $message)
            <div
                class="verify-register-general-error"
                role="alert">
                {{ $message }}
            </div>
            @endforeach
            @endif
            @endforeach


            {{-- FORM REGISTRASI --}}
            <form
                id="verifyRegisterForm"
                class="verify-register-form"
                action="{{ route('register') }}"
                method="POST">

                @csrf


                {{-- NAMA --}}
                <div class="verify-register-field">

                    <label for="name">
                        Nama lengkap
                    </label>

                    <input
                        class="verify-register-input @error('name') is-invalid @enderror"
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama kamu"
                        autocomplete="name"
                        maxlength="255"
                        required
                        @error('name')
                        aria-invalid="true"
                        aria-describedby="register-name-error"
                        @enderror>

                    {{-- Tetap ada agar bisa diisi JavaScript --}}
                    <p
                        id="register-name-error"
                        class="verify-register-field-error"
                        aria-live="polite"
                        @unless($errors->has('name')) hidden @endunless>
                        {{ $errors->first('name') }}
                    </p>

                </div>


                {{-- EMAIL --}}
                <div class="verify-register-field">

                    <label for="email">
                        Email
                    </label>

                    <input
                        class="verify-register-input @error('email') is-invalid @enderror"
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        autocapitalize="none"
                        spellcheck="false"
                        required
                        @error('email')
                        aria-invalid="true"
                        aria-describedby="register-email-error"
                        @enderror>

                    <p
                        id="register-email-error"
                        class="verify-register-field-error"
                        aria-live="polite"
                        @unless($errors->has('email')) hidden @endunless>
                        {{ $errors->first('email') }}
                    </p>

                </div>


                {{-- PASSWORD TANPA KONFIRMASI --}}
                <div class="verify-register-field">

                    <label for="password">
                        Password
                    </label>

                    <div class="verify-register-password-wrap">

                        <input
                            class="verify-register-input @error('password') is-invalid @enderror"
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Buat password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                            @error('password')
                            aria-invalid="true"
                            aria-describedby="register-password-error"
                            @enderror>

                        <button
                            class="verify-register-password-toggle"
                            id="verifyRegisterPasswordToggle"
                            type="button"
                            aria-controls="password"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                            hidden>

                            {{-- Ikon tampilkan password --}}
                            <svg
                                data-password-icon="show"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M2 12S5.5 5 12 5S22 12 22 12S18.5 19 12 19S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>

                            {{-- Ikon sembunyikan password --}}
                            <svg
                                data-password-icon="hide"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                                hidden>
                                <path d="M3 3L21 21" />
                                <path d="M10.5 5.1C11 5 11.5 5 12 5C18.5 5 22 12 22 12C21.1 13.6 20 15 18.6 16.2" />
                                <path d="M6.2 6.2C3.5 8.2 2 12 2 12S5.5 19 12 19C14 19 15.8 18.4 17.2 17.4" />
                                <path d="M10 10A3 3 0 0 0 14 14" />
                            </svg>

                        </button>

                    </div>

                    <p
                        id="register-password-error"
                        class="verify-register-field-error"
                        aria-live="polite"
                        @unless($errors->has('password')) hidden @endunless>
                        {{ $errors->first('password') }}
                    </p>

                </div>


                {{-- TOMBOL SUBMIT --}}
                <button
                    id="verifyRegisterSubmit"
                    class="verify-register-submit"
                    type="submit">

                    <span
                        class="verify-register-spinner"
                        aria-hidden="true"
                        hidden></span>

                    <span data-register-submit-label>
                        Buat akun
                    </span>

                    <svg
                        data-register-submit-arrow
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

                <p
                    id="verifyRegisterStatus"
                    class="verify-register-form-status"
                    role="status"
                    aria-live="polite">
                </p>

            </form>


            {{-- TAUTAN LOGIN --}}
            <p class="verify-register-login">

                Sudah punya akun?

                <a
                    href="{{ request('from') === 'simulator'
                        ? route('login', ['from' => 'simulator'])
                        : route('login') }}">
                    Login
                </a>

            </p>

        </div>

    </div>

</section>

<script
    src="{{ asset('js/verify-register.js') }}"
    defer></script>

@endsection