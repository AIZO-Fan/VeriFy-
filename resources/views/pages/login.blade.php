@extends('layouts.app')

@section('title', 'Login')

@section('content')

<section
    class="verify-login"
    aria-labelledby="verify-login-title">

    <div class="verify-login-container">

        {{-- =========================================
             PENGANTAR KIRI
             ========================================= --}}
        <div class="verify-login-intro">

            <span class="verify-login-eyebrow">
                <span aria-hidden="true"></span>
                SELAMAT DATANG KEMBALI
            </span>

            <h1 id="verify-login-title">
                Langkah kecil hari ini.
                <span>Lebih bijak di dunia digital.</span>
            </h1>

            <p class="verify-login-intro-description">
                Masuk ke akun VeriFy dan lanjutkan perjalanan
                belajarmu mengenali penipuan digital.
            </p>

            <div class="verify-login-message">

                <span
                    class="verify-login-message-icon"
                    aria-hidden="true">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 3L4 6V12C4 17 12 21 12 21S20 17 20 12V6Z" />
                        <path d="M8 12L11 15L16 9" />
                    </svg>

                </span>

                <div>

                    <h2>Sebelum percaya, periksa dahulu.</h2>

                    <p>
                        Luangkan waktu untuk memahami situasi
                        sebelum membagikan data atau mengambil tindakan.
                    </p>

                </div>

            </div>

            <p class="verify-login-intro-note">
                Belajar. Berlatih. Lebih waspada.
            </p>

        </div>


        {{-- =========================================
             KARTU LOGIN KANAN
             ========================================= --}}
        <div class="verify-login-card">

            <div class="verify-login-card-heading">

                <span class="verify-login-card-label">
                    AKUN VERIFY
                </span>

                <h2>Login</h2>

                <p>
                    Masukkan email dan password akunmu.
                </p>

            </div>


            {{--
                Flash success ditampilkan oleh layouts.app.
                Jangan ditampilkan dua kali di halaman ini.
            --}}


            {{-- Error selain email dan password --}}
            @foreach ($errors->getMessages() as $field => $messages)
                @if (!in_array($field, ['email', 'password']))
                    @foreach ($messages as $message)
                        <div
                            class="verify-login-general-error"
                            role="alert">
                            {{ $message }}
                        </div>
                    @endforeach
                @endif
            @endforeach


            <form
                id="verifyLoginForm"
                class="verify-login-form"
                action="{{ route('login') }}"
                method="POST">

                @csrf


                {{-- EMAIL --}}
                <div class="verify-login-field">

                    <label for="login-email">
                        Email
                    </label>

                    <input
                        class="verify-login-input @error('email') is-invalid @enderror"
                        type="email"
                        name="email"
                        id="login-email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="username"
                        autocapitalize="none"
                        spellcheck="false"
                        required
                        @error('email')
                            aria-invalid="true"
                            aria-describedby="login-email-error"
                        @enderror>

                    <p
                        id="login-email-error"
                        class="verify-login-field-error"
                        aria-live="polite"
                        @unless($errors->has('email')) hidden @endunless>
                        {{ $errors->first('email') }}
                    </p>

                </div>


                {{-- PASSWORD --}}
                <div class="verify-login-field">

                    <label for="login-password">
                        Password
                    </label>

                    <div class="verify-login-password-wrap">

                        <input
                            class="verify-login-input @error('password') is-invalid @enderror"
                            type="password"
                            name="password"
                            id="login-password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                            @error('password')
                                aria-invalid="true"
                                aria-describedby="login-password-error"
                            @enderror>

                        <button
                            class="verify-login-password-toggle"
                            id="verifyLoginPasswordToggle"
                            type="button"
                            aria-controls="login-password"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                            hidden>

                            <svg
                                data-login-password-icon="show"
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

                            <svg
                                data-login-password-icon="hide"
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
                        id="login-password-error"
                        class="verify-login-field-error"
                        aria-live="polite"
                        @unless($errors->has('password')) hidden @endunless>
                        {{ $errors->first('password') }}
                    </p>

                </div>


                {{-- SUBMIT --}}
                <button
                    id="verifyLoginSubmit"
                    class="verify-login-submit"
                    type="submit">

                    <span
                        class="verify-login-spinner"
                        aria-hidden="true"
                        hidden></span>

                    <span data-login-submit-label>
                        Login
                    </span>

                    <svg
                        data-login-submit-arrow
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
                    id="verifyLoginStatus"
                    class="verify-login-form-status"
                    role="status"
                    aria-live="polite">
                </p>

            </form>


            <p class="verify-login-register">

                Belum punya akun?

                <a
                    href="{{ request('from') === 'simulator'
                        ? route('register', ['from' => 'simulator'])
                        : route('register') }}">
                    Buat akun
                </a>

            </p>

        </div>

    </div>

</section>

<script
    src="{{ asset('js/verify-login.js') }}"
    defer></script>

@endsection