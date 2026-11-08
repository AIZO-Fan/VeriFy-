# VeriFy — Laravel UI Kit

Implementasi lima halaman berdasarkan referensi pengguna. Paket ini adalah overlay kode aplikasi, bukan instalasi Laravel lengkap. Tidak berisi vendor, kredensial, ataupun aset gambar pihak ketiga. Ilustrasi dibuat dengan CSS dan SVG lokal. Tata letak mendekati referensi, bukan reproduksi pixel-perfect.

## Instalasi pada proyek baru

Siapkan PHP dan Composer sesuai kebutuhan versi Laravel yang Anda pasang.

1. Buat proyek Laravel:

   ```bash
   composer create-project laravel/laravel verify
   ```

2. Ekstrak ZIP ini ke folder sementara. Salin isi folder `verify` dari paket (`app`, `routes`, `resources`, `public`, `tests`) ke root proyek Laravel tersebut. Gabungkan folder dan ganti file dengan nama sama. Cadangkan terlebih dahulu jika memakai proyek lama. File `routes/web.php` akan diganti.

3. Masuk ke root proyek:

   ```bash
   cd verify
   ```

4. Pastikan `.env` tersedia dari instalasi Laravel. Jika belum, salin `.env.example` ke `.env` dan jalankan:

   ```bash
   php artisan key:generate
   ```

5. Untuk demo tanpa database, ubah atau tambahkan pada `.env`:

   ```dotenv
   APP_NAME=VeriFy
   SESSION_DRIVER=file
   CACHE_STORE=file
   CACHE_DRIVER=file
   QUEUE_CONNECTION=sync
   ```

6. Bersihkan cache dan jalankan server:

   ```bash
   php artisan optimize:clear
   php artisan serve
   ```

7. Buka http://127.0.0.1:8000.

CSS dan JavaScript ada langsung di `public` sehingga tidak memerlukan npm atau proses build Vite untuk halaman dalam paket ini. Data modul disimpan dalam controller; tidak memerlukan migrasi untuk fitur paket.

## Halaman

- `/` : Landing page dengan ilustrasi ponsel CSS/SVG.
- `/detect-scam` : Form POST, validasi server, CSRF, pembatasan permintaan, dan hasil berbasis aturan.
- `/how-it-works` : Tiga tab dengan navigasi keyboard.
- `/modules` : Enam modul dan halaman detail `/modules/1` sampai `/modules/6`.
- `/scam-simulator` : Carousel enam skenario, dialog latihan, dan umpan balik. Satu soal per topik. Parameter `?topic=1` membuka topik terkait.

## Batasan

Deteksi hanya demo heuristik, bukan AI, antivirus, pemeriksa reputasi domain, ataupun jaminan keamanan. Penyebutan OTP bisa terdeteksi meskipun konteksnya edukatif. Tautan tidak dikunjungi dan tidak dicocokkan dengan basis data pihak ketiga.

Tidak ada login, penyimpanan hasil, backend pelaporan, atau integrasi layanan keamanan. Jawaban simulator ada di JavaScript dan tidak cocok untuk ujian berskor yang memerlukan integritas jawaban. Navbar hanya berisi lima halaman yang diminta; ikon bantuan menuju How It Works.

Permintaan tidak sengaja disimpan ke basis data oleh fitur ini. Namun, validasi gagal dapat menyimpan input sementara pada session Laravel; jangan masukkan data sensitif.

Untuk produksi, tambahkan kebijakan privasi, retensi session/log, audit keamanan, dan layanan analisis yang sesuai.

## Pengujian

Disertakan `tests/Feature/VerifyTest.php`.

Jalankan setelah pemasangan:

```bash
php artisan test --filter=VerifyTest
```

Test dan aplikasi belum dieksekusi dalam runtime PHP/Laravel saat paket ini dibuat; verifikasi pada lingkungan lokal Anda diperlukan.

## Penyesuaian

- Warna, responsivitas, ilustrasi: `public/css/verify.css`
- Interaksi dan soal latihan: `public/js/verify.js`
- Materi dan aturan analisis: `app/Http/Controllers/VerifyController.php`
- Halaman: `resources/views/pages`
- Navigasi: `resources/views/layouts/app.blade.php`