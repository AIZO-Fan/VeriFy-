<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="description"
        content="VeriFy — belajar mengenali penipuan digital."
    >

    <title>@yield('title', 'VeriFy') | VeriFy</title>

    {{-- Font wordmark --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Stylesheet aplikasi --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/siberguard.css') }}"
    >

    {{-- JavaScript aplikasi --}}
    <script
        src="{{ asset('js/siberguard.js') }}"
        defer
    ></script>

    {{-- Lucide, dipertahankan untuk halaman yang menggunakannya --}}
    <script
        src="https://unpkg.com/lucide@latest"
        defer
    ></script>
</head>

<body>

    {{-- =========================================
         NAVBAR VERIFY
         ========================================= --}}
    <header class="verify-header" id="verifyHeader">

        <div class="verify-navbar">

            {{-- Logo --}}
            <a
                class="verify-brand"
                href="{{ route('home') }}"
                aria-label="VeriFy — kembali ke Dashboard"
            >
                <svg
                    class="verify-brand-symbol"
                    viewBox="0 0 64 64"
                    fill="none"
                    aria-hidden="true"
                >
                    <path
                        d="M5 8H19L30 43H16L5 8Z"
                        fill="currentColor"
                    />

                    <path
                        d="M45 8H59L45 43H31L45 8Z"
                        fill="currentColor"
                    />

                    <circle
                        cx="31"
                        cy="55"
                        r="7"
                        fill="#20B4CB"
                    />
                </svg>

                <span class="verify-brand-name">
                    Veri<span>Fy</span>
                </span>
            </a>

            {{-- Tombol menu mobile --}}
            <button
                class="verify-menu-toggle"
                id="verifyMenuToggle"
                type="button"
                aria-label="Buka navigasi"
                aria-expanded="false"
                aria-controls="verifyNavigation"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    aria-hidden="true"
                >
                    <path d="M4 6H20M4 12H20M4 18H20" />
                </svg>
            </button>

            {{-- Navigasi utama --}}
            <nav
                class="verify-navigation"
                id="verifyNavigation"
                aria-label="Navigasi utama"
            >
                <a
                    href="{{ route('home') }}"
                    class="verify-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                    @if(request()->routeIs('home'))
                        aria-current="page"
                    @endif
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('detect') }}"
                    class="verify-nav-link {{ request()->routeIs('detect', 'analyze') ? 'is-active' : '' }}"
                    @if(request()->routeIs('detect', 'analyze'))
                        aria-current="page"
                    @endif
                >
                    Detect Scam
                </a>

                <a
                    href="{{ route('modules') }}"
                    class="verify-nav-link {{ request()->routeIs('modules', 'lesson') ? 'is-active' : '' }}"
                    @if(request()->routeIs('modules', 'lesson'))
                        aria-current="page"
                    @endif
                >
                    Modules
                </a>

                <a
                    href="{{ route('simulator') }}"
                    class="verify-nav-link {{ request()->routeIs('simulator') ? 'is-active' : '' }}"
                    @if(request()->routeIs('simulator'))
                        aria-current="page"
                    @endif
                >
                    Scam Simulator
                </a>

                {{-- Tombol akun dan Settings --}}
                <div class="verify-nav-actions">

                    @guest

                        @if(\Illuminate\Support\Facades\Route::has('register'))
                            <a
                                class="verify-register-button"
                                href="{{ route('register') }}"
                            >
                                Register
                            </a>
                        @endif

                        @if(\Illuminate\Support\Facades\Route::has('login'))
                            <a
                                class="verify-login-button"
                                href="{{ route('login') }}"
                            >
                                Login
                            </a>
                        @endif

                    @endguest

                    @auth

                        <span class="verify-navbar-user">

                            <span class="verify-avatar">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>

                            <span class="verify-profile-name">
                                {{ auth()->user()->name ?? 'Pengguna' }}
                            </span>

                        </span>

                    @endauth

                    @if(\Illuminate\Support\Facades\Route::has('settings'))

                        <a
                            class="verify-settings-button"
                            href="{{ route('settings') }}"
                            aria-label="Buka Settings"
                            title="Settings"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="
                                    M9.5 3H14.5L15.2 5.4
                                    L17.4 6.7L19.8 6.1
                                    L22.3 10.4L20.6 12.2
                                    V14.8L22.3 16.6
                                    L19.8 20.9L17.4 20.3
                                    L15.2 21.6L14.5 24
                                    H9.5L8.8 21.6
                                    L6.6 20.3L4.2 20.9
                                    L1.7 16.6L3.4 14.8
                                    V12.2L1.7 10.4
                                    L4.2 6.1L6.6 6.7
                                    L8.8 5.4Z
                                " transform="translate(1.2 -.4) scale(.9)" />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="3.2"
                                />
                            </svg>
                        </a>

                    @else

                        <button
                            class="verify-settings-button"
                            type="button"
                            disabled
                            aria-label="Settings belum tersedia"
                            title="Halaman Settings belum dibuat"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="3.2"
                                />

                                <path d="
                                    M12 2V5
                                    M12 19V22
                                    M2 12H5
                                    M19 12H22
                                    M5 5L7.1 7.1
                                    M16.9 16.9L19 19
                                    M5 19L7.1 16.9
                                    M16.9 7.1L19 5
                                " />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="7"
                                />
                            </svg>
                        </button>

                    @endif

                </div>

            </nav>

        </div>

    </header>


    {{-- =========================================
         KONTEN HALAMAN
         ========================================= --}}
    <main>
        @yield('content')
    </main>


    {{-- =========================================
         FOOTER VERIFY
         ========================================= --}}
    <footer class="verify-footer">

        <div class="verify-footer-container">

            <div class="verify-footer-main">

                {{-- Identitas --}}
                <div class="verify-footer-brand-column">

                    <a
                        class="verify-footer-brand"
                        href="{{ route('home') }}"
                        aria-label="VeriFy — kembali ke Dashboard"
                    >
                        <svg
                            class="verify-footer-symbol"
                            viewBox="0 0 64 64"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M5 8H19L30 43H16L5 8Z"
                                fill="currentColor"
                            />

                            <path
                                d="M45 8H59L45 43H31L45 8Z"
                                fill="currentColor"
                            />

                            <circle
                                cx="31"
                                cy="55"
                                r="7"
                                fill="#20B4CB"
                            />
                        </svg>

                        <span class="verify-footer-wordmark">
                            Veri<span>Fy</span>
                        </span>
                    </a>

                    <p class="verify-footer-tagline">
                        Kenali risikonya.Lebih bijak bertindak.
                    </p>

                    <p class="verify-footer-description">
                        Belajar mengenali penipuan dan membangun
                        kebiasaan digital yang lebih aman,
                        satu langkah setiap hari.
                    </p>

                </div>

                {{-- Pesan singkat --}}
                <div class="verify-footer-message">

                    <span class="verify-footer-message-label">
                        SEBELUM PERCAYA
                    </span>

                    <h2>
                        Berhenti sejenak. <span>Periksa lebih teliti.</span>
                    </h2>

                    <p>
                        Jangan terburu-buru mengikuti pesan
                        yang meminta data pribadi atau
                        mendesakmu mengambil tindakan.
                    </p>

                    <a
                        class="verify-footer-cta"
                        href="{{ route('detect') }}"
                    >
                        <span>Periksa pesan mencurigakan</span>

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M5 12H19M13 6L19 12L13 18" />
                        </svg>
                    </a>

                </div>

            </div>

            {{-- Copyright dan batasan --}}
            <div class="verify-footer-bottom">

                <p class="verify-footer-copyright">
                    &copy; {{ date('Y') }} VeriFy.

                    <span>
                        Belajar. Berlatih. Lebih waspada.
                    </span>
                </p>

                <p class="verify-footer-disclaimer">
                    Platform edukasi dan pemeriksaan awal.
                    Hasil deteksi bukan jaminan keamanan.
                </p>

            </div>

        </div>

    </footer>


    {{-- Inisialisasi Lucide --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>

</body>

</html>