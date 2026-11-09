<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Web</title>
</head>

<body>
    <h2>Form Login</h2>

    <!-- Menampilkan Pesan Sukses (dari register) -->
    @if (session('success'))
    <div style="color: green; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Menampilkan Pesan Error (Password/Email salah) -->
    @if ($errors->any())
    <div style="color: red; margin-bottom: 15px;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div style="margin-bottom: 10px;">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="{{ route('register') }}">Register di sini</a></p>
</body>

</html>