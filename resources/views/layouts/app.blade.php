<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VeriFy — belajar mengenali penipuan digital.">
    <title>@yield('title', 'VeriFy') | VeriFy</title>
    <link rel="stylesheet" href="{{ asset('css/siberguard.css') }}">
    <script src="{{ asset('js/siberguard.js') }}" defer></script>
</head>

<body>
    <header class="header"><a class="brand" href="{{ route('home') }}"><span class="brand-shield">S</span> VeriFy</a><button class="menu-button" aria-label="Buka navigasi" aria-expanded="false" aria-controls="navigation">☰</button>
        <nav id="navigation" aria-label="Navigasi utama">@foreach(['home'=>'Dashboard','detect'=>'Detect Scam','how'=>'How It Works','modules'=>'Modules','simulator'=>'Scam Simulator'] as $route=>$label)<a class="{{ request()->routeIs($route, $route === 'modules' ? 'lesson' : $route) ? 'active' : '' }}" href="{{ route($route) }}">{{ $label }}</a>@endforeach</nav><a class="help-link" href="{{ route('how') }}" aria-label="Panduan penggunaan">?</a>
    </header>
    <main>@yield('content')</main>
    <footer>VeriFy <span>Belajar. Berlatih. Melindungi.</span></footer>
</body>

</html>