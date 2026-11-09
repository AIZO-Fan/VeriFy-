@extends('layouts.app')

@section('title', 'Settings')

@section('content')

    @php
        $user = auth()->user();

        $initial = mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1));
    @endphp

    <section class="verify-settings" aria-labelledby="verify-settings-title">

        <div class="verify-settings-container">

            {{-- HEADING --}}
            <header class="verify-settings-heading">

                <span class="verify-settings-eyebrow">
                    <span aria-hidden="true"></span>
                    RUANG PERSONALMU
                </span>

                <h1 id="verify-settings-title">
                    Pengaturan <span>akun.</span>
                </h1>

                <p>
                    Lihat informasi profil dan kelola sesi akun VeriFy.
                </p>

            </header>


            <div class="verify-settings-layout">

                {{-- RINGKASAN PROFIL --}}
                <aside class="verify-settings-profile" aria-labelledby="verify-settings-profile-name">

                    <div class="verify-settings-avatar" aria-hidden="true">
                        {{ $initial }}
                    </div>

                    <h2 id="verify-settings-profile-name">
                        {{ $user->name }}
                    </h2>

                    <p class="verify-settings-profile-email">
                        {{ $user->email }}
                    </p>

                    <span class="verify-settings-account-badge">
                        Akun VeriFy
                    </span>

                    <div class="verify-settings-profile-divider"></div>

                    <p class="verify-settings-profile-note">
                        Belajar mengenali risikonya.
                        Berlatih mengambil keputusan yang lebih bijak.
                    </p>

                    <a class="verify-settings-dashboard-link" href="{{ route('home') }}">
                        Kembali ke Dashboard
                        <span aria-hidden="true">→</span>
                    </a>

                </aside>


                {{-- PENGATURAN --}}
                <div class="verify-settings-content">
                    {{-- MODE TAMPILAN --}}
                    <section class="verify-settings-card" aria-labelledby="verify-settings-theme-title">

                        <div class="verify-settings-card-heading">

                            <div class="verify-settings-card-title-group">

                                <span class="verify-settings-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                        stroke-linecap="round" stroke-linejoin="round">

                                        <circle cx="12" cy="12" r="4" />
                                        <path d="M12 2V4M12 20V22M4.93 4.93L6.34 6.34M17.66 17.66L19.07 19.07" />
                                        <path d="M2 12H4M20 12H22M4.93 19.07L6.34 17.66M17.66 6.34L19.07 4.93" />

                                    </svg>
                                </span>

                                <div>
                                    <h2 id="verify-settings-theme-title">
                                        Mode tampilan
                                    </h2>

                                    <p>
                                        Sesuaikan tampilan VeriFy dengan kenyamananmu.
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="verify-settings-theme-options">

                            <button type="button" class="verify-settings-theme-button" data-theme-option="light"
                                aria-pressed="false">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

                                    <circle cx="12" cy="12" r="4" />
                                    <path d="M12 2V4M12 20V22M4.93 4.93L6.34 6.34M17.66 17.66L19.07 19.07" />
                                    <path d="M2 12H4M20 12H22M4.93 19.07L6.34 17.66M17.66 6.34L19.07 4.93" />

                                </svg>

                                <span>Light Mode</span>

                                <span class="verify-settings-theme-check" aria-hidden="true">
                                    ✓
                                </span>

                            </button>


                            <button type="button" class="verify-settings-theme-button" data-theme-option="dark"
                                aria-pressed="false">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

                                    <path d="M20.9 13A9 9 0 0 1 11 3.1A9 9 0 1 0 20.9 13Z" />

                                </svg>

                                <span>Dark Mode</span>

                                <span class="verify-settings-theme-check" aria-hidden="true">
                                    ✓
                                </span>

                            </button>

                        </div>


                    </section>

                    {{-- INFORMASI PROFIL --}}
                    <section class="verify-settings-card" aria-labelledby="verify-settings-info-title">

                        <div class="verify-settings-card-heading">

                            <div class="verify-settings-card-title-group">

                                <span class="verify-settings-icon" aria-hidden="true">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="4" />
                                        <path d="M4 21V19C4 15.7 7.6 14 12 14S20 15.7 20 19V21" />
                                    </svg>

                                </span>

                                <div>

                                    <h2 id="verify-settings-info-title">
                                        Informasi profil
                                    </h2>

                                    <p>
                                        Identitas yang terhubung ke akunmu.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <dl class="verify-settings-details">

                            <div>
                                <dt>Nama lengkap</dt>
                                <dd>{{ $user->name }}</dd>
                            </div>

                            <div>
                                <dt>Email</dt>
                                <dd>{{ $user->email }}</dd>
                            </div>

                        </dl>


                        <div class="verify-settings-pending">

                            <span class="verify-settings-pending-badge">
                                Segera tersedia
                            </span>

                            <p>
                                Perubahan nama dan email belum tersedia
                                pada versi ini.
                            </p>

                        </div>

                    </section>


                    {{-- KEAMANAN --}}
                    <section class="verify-settings-card" aria-labelledby="verify-settings-security-title">

                        <div class="verify-settings-card-heading">

                            <div class="verify-settings-card-title-group">

                                <span class="verify-settings-icon" aria-hidden="true">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="5" y="10" width="14" height="11" rx="3" />
                                        <path d="M8 10V7A4 4 0 0 1 16 7V10" />
                                        <path d="M12 14V17" />
                                    </svg>

                                </span>

                                <div>

                                    <h2 id="verify-settings-security-title">
                                        Keamanan akun
                                    </h2>

                                    <p>
                                        Jaga akses akun tetap dalam kendalimu.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="verify-settings-security-tip">

                            <h3>Jangan bagikan password akunmu.</h3>

                            <p>
                                Gunakan password yang berbeda dari akun lain
                                dan hindari membagikannya kepada siapa pun.
                            </p>

                        </div>

                        <div class="verify-settings-pending">

                            <span class="verify-settings-pending-badge">
                                Segera tersedia
                            </span>

                            <p>
                                Fitur ganti password belum tersedia
                                pada versi ini.
                            </p>

                        </div>

                    </section>


                    {{-- SESI AKUN --}}
                    <section class="verify-settings-card verify-settings-session"
                        aria-labelledby="verify-settings-session-title">

                        <div class="verify-settings-session-content">

                            <h2 id="verify-settings-session-title">
                                Selesai menggunakan VeriFy?
                            </h2>

                            <p>
                                Keluar dari akun jika kamu menggunakan
                                perangkat bersama.
                            </p>

                        </div>

                        <form class="verify-settings-logout-form" action="{{ route('logout') }}" method="POST">

                            @csrf

                            <button class="verify-settings-logout" type="submit">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 4H5A2 2 0 0 0 3 6V18A2 2 0 0 0 5 20H9" />
                                    <path d="M10 12H21M17 8L21 12L17 16" />
                                </svg>

                                <span>Logout</span>

                            </button>

                        </form>

                    </section>

                </div>

            </div>

        </div>

    </section>

@endsection
