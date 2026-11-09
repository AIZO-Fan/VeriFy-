<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Akun</title>
</head>

<body>
    <h2>Form Registrasi</h2>

    <!-- Menampilkan Pesan Error Validasi -->
    @if ($errors->any())
    <div style="color: red; margin-bottom: 15px;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf <!-- Wajib ada untuk menghindari error 419 Page Expired -->

        <div style="margin-bottom: 10px;">
            <label for="name">Nama Lengkap:</label><br>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Daftar Sekarang</button>
    </form>

    <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
</body>

</html>