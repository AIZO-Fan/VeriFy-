<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VeriFy — belajar mengenali penipuan digital.">
    <title>@yield('title', 'VeriFy') | VeriFy</title>
    <link rel="stylesheet" href="{{ asset('css/siberguard.css') }}">
    <script src="{{ asset('js/siberguard.js') }}" defer></script>
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>

<body>
   
<header class="header">
    <a class="brand" href="{{ route('home') }}">
        <svg class="brand-logo" viewBox="0 0 100 100"
            xmlns="http://www.w3.org/2000/svg">

            <path d="M12 15 H32 L50 60 H30 Z" fill="#174E9B"/>
            <path d="M68 15 H88 L70 60 H50 Z" fill="#174E9B"/>
            <circle cx="50" cy="78" r="9" fill="#20B9D0"/>

        </svg>
        VeriFy
    </a>

    <button class="menu-button"
        aria-label="Buka navigasi"
        aria-expanded="false"
        aria-controls="navigation">
        ☰
    </button>

    <nav id="navigation" aria-label="Navigasi utama">
        @foreach (['home' => 'Dashboard', 'detect' => 'Detect Scam', 'modules' => 'Modules', 'simulator' => 'Scam Simulator'] as $route => $label)
            <a class="{{ request()->routeIs($route, $route === 'modules' ? 'lesson' : $route) ? 'active' : '' }}"
                href="{{ route($route) }}">{{ $label }}</a>
        @endforeach
    </nav>

    <div class="header-actions">

        <a class="settings-link" href="{{ route('settings') }}" aria-label="Settings" title="Settings">
    <i data-lucide="settings"></i>
</a>

        @guest
            <a href="{{ route('login') }}" class="login-button">
                Login
            </a>

            <a href="{{ route('register') }}" class="register-button">
                Register
            </a>
        @else
            <div class="user-menu">
                <button type="button" class="user-button">
                    <i data-lucide="user-circle"></i>
                    <span>{{ Auth::user()->name }}</span>
                    <i data-lucide="chevron-down"></i>
                </button>

                <div class="user-dropdown">
                    <a href="{{ route('profile') }}">My Profile</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        @endguest

    </div>
</header>
    <main>@yield('content')</main>
  
<footer class="footer">
    <div class="footer-main">
        <div class="footer-brand">
            <div class="footer-logo">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15 H32 L50 60 H30 Z" fill="currentColor"/>
                    <path d="M68 15 H88 L70 60 H50 Z" fill="currentColor"/>
                    <circle cx="50" cy="78" r="9" fill="#1DB6CF"/>
                </svg>
                <span>VERI<span class="footer-cyan">FY</span></span>
            </div>

            <p>Belajar. Berlatih. Melindungi.</p>
        </div>

        <span class="footer-tag">DIGITAL SAFETY</span>
    </div>

    <div class="footer-description">
        <h3>Stay Smart, Stay Safe.</h3>
        <p>
            Kenali ancaman digital, lindungi dirimu,
            dan bangun kebiasaan online yang lebih aman.
        </p>
    </div>

    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} VeriFy. All rights reserved.</span>
        <span>Your Digital Safety Partner</span>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>

</body>

</html>
