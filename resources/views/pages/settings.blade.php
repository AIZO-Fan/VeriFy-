@extends('layouts.app')

@section('content')
<div style="padding: 40px; font-family: sans-serif; max-width: 800px; margin: auto;">
    <h2>Pengaturan Akun</h2>

    <!-- Menampilkan nama user yang sedang login -->
    <p>Halo, <strong>{{ auth()->user()->name }}</strong>!</p>

    <hr style="margin: 20px 0; border: 1px solid #eee;">

    <div style="margin-top: 20px;">
        <h3>Sesi Akun</h3>
        <p style="color: gray; margin-bottom: 15px;">Keluar dari sesi aplikasi VeriFy saat ini.</p>

        <!-- Form Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" style="background-color: #ef4444; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection