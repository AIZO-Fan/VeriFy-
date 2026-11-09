<!DOCTYPE html>
<html lang="id">

<head>
    <style>
        /* Custom Modal VeriFy */
        .verify-custom-modal {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .verify-custom-modal.is-open {
            opacity: 1;
            visibility: visible;
        }

        .verify-custom-modal-box {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(10px);
            transition: transform 0.3s ease;
            text-align: left;
            font-family: inherit;
        }

        .verify-custom-modal.is-open .verify-custom-modal-box {
            transform: translateY(0);
        }

        .verify-custom-modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .verify-custom-modal-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .verify-custom-modal-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .verify-custom-modal-body {
            font-size: 0.950rem;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .verify-custom-modal-footer {
            display: flex;
            justify-content: flex-end;
        }

        .verify-custom-modal-btn {
            background-color: #0f172a;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .verify-custom-modal-btn:hover {
            background-color: #1e293b;
        }
    </style>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <meta
        name="description"
        content="VeriFy — belajar mengenali penipuan digital.">

    <title>@yield('title', 'VeriFy') | VeriFy</title>

    {{-- Font wordmark --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet">

    {{-- DARK MODE: terapkan tema sebelum CSS dimuat --}}
<script>
    (function () {
        let savedTheme = null;

        try {
            savedTheme = localStorage.getItem('verify-theme');
        } catch (error) {
            savedTheme = null;
        }

        const prefersDark = window.matchMedia(
            '(prefers-color-scheme: dark)'
        ).matches;

        const theme = savedTheme === 'light' || savedTheme === 'dark'
            ? savedTheme
            : (prefersDark ? 'dark' : 'light');

        document.documentElement.setAttribute('data-theme', theme);
    })();
</script>

    <link
        rel="stylesheet"
        href="{{ asset('css/verify.css') }}">

    <script
        src="{{ asset('js/verify.js') }}"
        defer></script>

    {{-- Lucide, dipertahankan untuk halaman yang menggunakannya --}}
    <script
        src="https://unpkg.com/lucide@latest"
        defer></script>
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
                aria-label="VeriFy — kembali ke Dashboard">
                <svg
                    class="verify-brand-symbol"
                    viewBox="0 0 64 64"
                    fill="none"
                    aria-hidden="true">
                    <path
                        d="M5 8H19L30 43H16L5 8Z"
                        fill="currentColor" />

                    <path
                        d="M45 8H59L45 43H31L45 8Z"
                        fill="currentColor" />

                    <circle
                        cx="31"
                        cy="55"
                        r="7"
                        fill="#20B4CB" />
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
                aria-controls="verifyNavigation">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    aria-hidden="true">
                    <path d="M4 6H20M4 12H20M4 18H20" />
                </svg>
            </button>

            {{-- Navigasi utama --}}
            <nav
                class="verify-navigation"
                id="verifyNavigation"
                aria-label="Navigasi utama">
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
                href="{{ route('register') }}">
                Register
            </a>
        @endif

        @if(\Illuminate\Support\Facades\Route::has('login'))
            <a
                class="verify-login-button"
                href="{{ route('login') }}">
                Login
            </a>
        @endif

    @endguest

    @auth

        {{-- Profil: klik untuk membuka Settings --}}
        <a
            class="verify-navbar-user"
            href="{{ \Illuminate\Support\Facades\Route::has('settings') ? route('settings') : '#' }}"
            aria-label="Buka pengaturan akun"
            title="Settings">

            <span class="verify-avatar">
                {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </span>

            <span class="verify-profile-name">
                {{ auth()->user()->name ?? 'Pengguna' }}
            </span>

        </a>

    @endauth

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
                        aria-label="VeriFy — kembali ke Dashboard">
                        <svg
                            class="verify-footer-symbol"
                            viewBox="0 0 64 64"
                            fill="none"
                            aria-hidden="true">
                            <path
                                d="M5 8H19L30 43H16L5 8Z"
                                fill="currentColor" />

                            <path
                                d="M45 8H59L45 43H31L45 8Z"
                                fill="currentColor" />

                            <circle
                                cx="31"
                                cy="55"
                                r="7"
                                fill="#20B4CB" />
                        </svg>

                        <span class="verify-footer-wordmark">
                            Veri<span>Fy</span>
                        </span>
                    </a>

                    <p class="verify-footer-tagline">
                        Kenali risikonya. Lebih bijak bertindak.
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
                        href="{{ route('detect') }}">
                        <span>Periksa pesan mencurigakan</span>

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
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>

    {{-- SCRIPT AUTO-SAVE SIMULATOR UNTUK USER YANG BARU LOGIN --}}
    @auth
    {{-- HTML CUSTOM MODAL --}}
    <div id="verifyAlertModal" class="verify-custom-modal">
        <div class="verify-custom-modal-box">
            <div class="verify-custom-modal-header">
                <div class="verify-custom-modal-icon">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3 class="verify-custom-modal-title">Berhasil Disimpan!</h3>
            </div>
            <div class="verify-custom-modal-body" id="verifyAlertMessage">
                <!-- Pesan akan masuk sini secara dinamis -->
            </div>
            <div class="verify-custom-modal-footer">
                <button type="button" class="verify-custom-modal-btn" onclick="closeVerifyModal()">Mengerti</button>
            </div>
        </div>
    </div>

    <script>
        function showVerifyModal(message) {
            document.getElementById('verifyAlertMessage').textContent = message;
            document.getElementById('verifyAlertModal').classList.add('is-open');
        }

        function closeVerifyModal() {
            document.getElementById('verifyAlertModal').classList.remove('is-open');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const pendingKey = "verify.pendingSimulationResult.v1";
            const pendingData = sessionStorage.getItem(pendingKey);

            if (pendingData) {
                const data = JSON.parse(pendingData);

                if (data.api_payload) {
                    data.api_payload.user_id = "{{ auth()->id() }}";

                    fetch('/api/simulator/attempts', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data.api_payload)
                    }).then(response => {
                        if (response.ok) {
                            sessionStorage.removeItem(pendingKey);
                            // Mengganti alert() bawaan browser dengan custom modal kita
                            showVerifyModal('Selamat datang! Hasil simulasi Scam Simulator Anda sebelumnya telah otomatis disimpan ke riwayat akun.');
                        }
                    }).catch(err => console.error("Gagal auto-save:", err));
                }
            }
        });
    </script>
    @endauth

</body>

</html>