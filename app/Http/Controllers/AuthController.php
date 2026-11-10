<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman form register
    public function showRegister()
    {
        return view('pages.register');
    }

    // Memproses data pendaftaran
    // Memproses data pendaftaran
    public function registerProcess(Request $request)
    {
        $validated = $request->validate(
    [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8',
    ],
    [
        'name.required' => 'Masukkan nama lengkap kamu',
        'name.string' => 'Masukkan nama yang valid',
        'name.max' => 'Nama maksimal 255 karakter',

        'email.required' => 'Masukkan alamat email kamu',
        'email.string' => 'Masukkan alamat email yang valid',
        'email.email' => 'Periksa kembali format email kamu',
        'email.unique' => 'Email ini sudah terdaftar, Silakan login',

        'password.required' => 'Buat password untuk akunmu',
        'password.string' => 'Masukkan password yang valid',
        'password.min' => 'Gunakan minimal 8 karakter',
    ]
);

        // Buat akun dan simpan model pengguna ke variabel $user.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Langsung login menggunakan akun yang baru dibuat.
        Auth::login($user);

        // Regenerasi session setelah autentikasi.
        $request->session()->regenerate();

        // Ke tujuan sebelumnya, atau Dashboard jika tidak ada.
        return redirect()
            ->intended(route('home'))
            ->with(
                'success',
                'Akun berhasil dibuat. Selamat datang di VeriFy!'
            );
    }

    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('pages.login');
    }

    // Memproses pengecekan login
    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek kecocokan email dan password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
