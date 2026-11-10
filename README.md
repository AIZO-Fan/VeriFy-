# 🛡️ VeriFy — Platform Edukasi & Deteksi Penipuan Digital

**VeriFy** adalah platform berbasis web modern yang dirancang untuk membantu masyarakat mengenali, mempelajari, dan mendeteksi berbagai modus penipuan digital (*online scam*) di Indonesia. Aplikasi ini dilengkapi dengan mesin analisis keamanan cerdas, simulator percakapan interaktif, modul edukasi berbasis data dinamis, serta panel kontrol administrasi (*Control Center*).

---

## 🌟 Fitur Utama Aplikasi

1. **🔍 Mesin Deteksi Scam Cerdas (*Detect Scam*)**
   - **Google Safe Browsing Lookup API v4**: Memeriksa URL secara *real-time* terhadap basis data ancaman global Google (*Malware*, *Social Engineering/Phishing*, *Unwanted Software*).
   - **Heuristik & Karakteristik URL**:
     - Deteksi ekstensi domain murah/bebas berisiko tinggi (`.xyz`, `.top`, `.online`, `.site`, `.buzz`, `.club`, `.work`, `.icu`, `.vip`, dll.).
     - Deteksi pemendek tautan (*URL shorteners*: `bit.ly`, `s.id`, `tinyurl.com`, `cutt.ly`, `wa.me`, dll.).
     - Deteksi unduhan file berbahaya / malware Android (`.apk`, `.exe`, dll.).
     - Deteksi penipuan *subdomain spoofing* (mencatut nama institusi resmi sebagai subdomain, misal `link.dana.id.daget.xyz`).
   - **Sistem Whitelist Domain Resmi Terverifikasi**: Mencegah *false positive* untuk institusi perbankan (`bca.co.id`, `bri.co.id`, `mandiri.co.id`), dompet digital (`dana.id`, `gopay.co.id`), e-commerce, serta seluruh portal resmi pemerintah (`.go.id`).
   - **Intelijen Regex Pola Penipuan Indonesia**: Mengenali pola file APK (undangan nikah, surat tilang ETLE, resi kurir paket), klaim saldo/DANA Kaget, undian & giveaway palsu, kenaikan tarif transfer, lowongan freelance fiktif, pinjaman online ilegal, desakan waktu, serta permintaan kode OTP/data rahasia.

2. **🎭 Scam Simulator Interaktif**
   - Simulasi percakapan interaktif berbasis graf skenario (*Story Graph JSON*).
   - Percabangan keputusan (*branching decision*) dengan umpan balik edukatif, skor kewaspadaan, dan konsekuensi tindakan.
   - Riwayat penyelesaian simulasi otomatis tersimpan ke akun pengguna.

3. **📚 Modul Pembelajaran Dinamis (*Learning Modules*)**
   - Modul materi interaktif dengan pembagian topik per kategori.
   - Terintegrasi langsung dengan basis data cloud (dapat ditambah, diubah urutannya, dan diedit secara dinamis).

4. **👤 Autentikasi & Profil Pengguna**
   - Sistem registrasi, login, dan manajemen sesi.
   - Halaman **Pengaturan / Profil** yang menampilkan **Riwayat Deteksi Scam** akun pengguna.

5. **⚙️ Panel Administrasi Lengkap (*Admin Control Center*)**
   - **Dashboard Ringkasan**: Metrik total pengguna, total modul, skenario simulator, dan log pemindaian scam.
   - **Manajemen Modul (`/admin/modules`)**: CRUD modul pembelajaran, kustomisasi ikon/warna, pengaturan nomor urutan, serta penambahan poin materi secara dinamis.
   - **Manajemen Scam Simulator (`/admin/simulator`)**: CRUD skenario simulator lengkap dengan *JSON Story Graph Editor*, validasi struktur alur (*StoryValidator*), dan tombol *Format JSON*.
   - **Manajemen Pengguna (`/admin/users`)**: Pemantauan pengguna, pencarian, filter, dan fitur ubah level/role (User ↔ Admin).
   - **Monitoring Deteksi Scam (`/admin/scans`)**: Log seluruh pemindaian teks/tautan yang masuk ke sistem beserta skor risiko dan temuan ancamannya.

---

## 🛠️ Teknologi & Sumber Pustaka (*Tech Stack & Libraries*)

### 1. Backend (Server-Side)
| Pustaka / Teknologi | Versi | Sumber / Dokumentasi | Kegunaan |
| :--- | :--- | :--- | :--- |
| **PHP** | `^8.3` | [php.net](https://www.php.net/) | Bahasa pemrograman utama server-side |
| **Laravel Framework** | `13.x / 11.x` | [laravel.com](https://laravel.com/) | Framework MVC modern untuk perutean, controller, model, dan middleware |
| **GuzzleHTTP** | `^7.8` | [docs.guzzlephp.org](https://docs.guzzlephp.org/) | HTTP Client untuk integrasi API Google Safe Browsing |
| **Laravel Sanctum** | `^4.3` | [laravel.com/docs/sanctum](https://laravel.com/docs/sanctum) | Autentikasi API token untuk endpoint Simulator |
| **Laravel Tinker** | `^3.0` | [github.com/laravel/tinker](https://github.com/laravel/tinker) | REPL interaktif untuk pengujian dan debugging basis data |

### 2. Frontend & Antarmuka Pengguna (UI/UX)
| Pustaka / Aset | Sumber / Lisensi | Kegunaan |
| :--- | :--- | :--- |
| **Laravel Blade Engine** | Bawaan Laravel | Template rendering view dinamis modular |
| **VeriFy Design System** | Custom CSS (`public/css/verify.css`) | Tema kustom responsif dengan dukungan Dark & Light Mode |
| **Plus Jakarta Sans** | [Google Fonts](https://fonts.google.com/specimen/Plus+Jakarta+Sans) | Tipografi wordmark dan antarmuka modern |
| **Lucide Icons** | [lucide.dev](https://lucide.dev/) (Unpkg CDN) | Ikon antarmuka vektor ringan dan bersih |
| **Vite & TailwindCSS** | [vite.dev](https://vite.dev/) / `package.json` | Build tool aset frontend & utilitas pendukung |

### 3. Basis Data & Cloud Infrastructure
| Layanan / Komponen | Provider / Sumber | Spesifikasi / Konfigurasi |
| :--- | :--- | :--- |
| **TiDB Cloud Serverless** | [PingCAP TiDB Cloud](https://tidbcloud.com/) | Basis data Distributed SQL kompatibel MySQL (Region AWS Singapore `ap-southeast-1`, Port `4000`) |
| **SSL/TLS Encryption** | cURL CA Bundle (`curl-ca-bundle.crt`) | Koneksi terenkripsi aman antara Laravel dan TiDB Cloud |
| **Database Sessions** | Laravel Session Driver | Sesi login pengguna disimpan langsung dan tersinkronisasi di cloud |

### 4. Layanan Eksternal & API Keamanan
| Layanan / API | Penyedia | Fungsi |
| :--- | :--- | :--- |
| **Google Safe Browsing Lookup API v4** | [Google Cloud Platform](https://developers.google.com/safe-browsing/v4/lookup-api) | Pemeriksaan reputasi tautan terhadap ancaman global Google |

---

## 🗄️ Struktur Tabel Basis Data (TiDB Cloud)

1. **`users`**: Data pengguna, email, kata sandi terenkripsi, serta kolom `role` (`admin` / `user`).
2. **`modules`**: Data modul pembelajaran, deskripsi, tema warna, ikon, nomor urut, status aktif, dan daftar poin materi (JSON).
3. **`simulation_scenarios`**: Skenario scam simulator, tingkat kesulitan, nomor topik (1–6), status aktif, versi, dan struktur alur percakapan (JSON).
4. **`simulation_attempts`**: Hasil simulasi yang dimainkan pengguna, riwayat pilihan rute (*decision path*), skor, dan nilai maksimal.
5. **`scan_histories`**: Riwayat input pemindaian detect scam, skor risiko, status keamanan, daftar temuan ancaman, dan URL berbahaya yang teridentifikasi.
6. **`sessions`**: Manajemen sesi pengguna aktif.
7. **`migrations`, `cache`, `jobs`, `personal_access_tokens`**: Tabel sistem pendukung Laravel.

---

## 🚀 Panduan Menjalankan Proyek Secara Lokal

### 1. Prasyarat Sistem
* PHP `>= 8.3` dengan ekstensi `pdo_mysql`, `curl`, `mbstring`, `openssl`.
* Composer `>= 2.x`.
* Node.js & NPM (opsional, untuk build frontend).

### 2. Kloning & Instalasi Dependensi
```bash
# Masuk ke direktori proyek
cd VeriFy-

# Pasang dependensi PHP
composer install

# Salin file konfigurasi environment
cp .env.example .env
```

### 3. Konfigurasi Database TiDB Cloud (`.env`)
Pastikan konfigurasi basis data pada `.env` telah mengarah ke TiDB Cloud:
```dotenv
DB_CONNECTION=mysql
DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=verify_db
DB_USERNAME=3VdejVq1rUm6iGr.root
DB_PASSWORD=<PASSWORD_TIDB_ANDA>
MYSQL_ATTR_SSL_CA="C:/xampp/apache/bin/curl-ca-bundle.crt"

# Google Safe Browsing API Key
GOOGLE_SAFE_BROWSING_API_KEY=AIzaSyDSvcA-lI_smfzlhK9DsvDDuFieBDApdvc
```

### 4. Migrasi & Seeder Database
```bash
# Jalankan migrasi tabel ke TiDB Cloud
php artisan migrate

# Isi data awal (Akun Admin default & Modul Pembelajaran)
php artisan db:seed
```

### 5. Jalankan Server Pengembangan
```bash
php artisan serve
```
Akses aplikasi melalui browser di **`http://localhost:8000`**.

---

## 🔐 Kredensial Akun Default

| Peran (*Role*) | Email / Username | Kata Sandi | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@verify.id` | `admin123` | Akses penuh: Panel Admin, CRUD Modul, CRUD Simulator, Manajemen Pengguna, dan Monitoring Scam |
| **User Biasa** | `user@verify.id` | `user123` | Akses publik: Detect Scam, Belajar Modul, Bermain Simulator, dan Riwayat Profil |

---

## 📁 Struktur Direktori Utama

```
VeriFy-/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Controller khusus Panel Admin (Dashboard, Modul, Simulator, User, Scan)
│   │   │   ├── Api/               # API Controller untuk Simulator (Attempt & Scenarios)
│   │   │   ├── AuthController.php # Autentikasi Login, Register, Logout
│   │   │   └── VerifyController.php # Controller halaman utama (Home, Detect, Modules, Lesson, Simulator)
│   │   └── Middleware/
│   │       └── AdminMiddleware.php # Middleware proteksi level Admin (403 Forbidden untuk non-admin)
│   ├── Models/                    # Eloquent Model (User, Module, SimulationScenario, ScanHistory, dll.)
│   └── Services/
│       ├── ScamAnalysisService.php # Mesin analisis URL, Safe Browsing & Intelijen Regex
│       └── StoryValidator.php      # Validasi graf skenario simulator (DFS cycle & reachability)
├── database/
│   ├── migrations/                # Skema basis data
│   └── seeders/                   # Data awal (Admin, Modul edukasi, Skenario simulasi)
├── resources/
│   └── views/
│       ├── admin/                 # Blade templates untuk halaman-halaman Control Center Admin
│       ├── layouts/               # Master layout aplikasi (Header navbar, footer, modal auto-save)
│       └── pages/                 # Halaman pengguna (Home, Detect, Modules, Lesson, Settings)
├── routes/
│   ├── web.php                    # Rute publik, autentikasi, dan grup admin
│   └── api.php                    # Rute endpoint RESTful API Simulator
└── public/
    ├── css/verify.css             # Desain antarmuka & tema aplikasi
    └── js/verify.js              # Skrip interaktivitas klien
```

---

## ⚖️ Lisensi
Proyek ini dikembangkan di bawah lisensi **MIT**. Digunakan untuk tujuan edukasi keamanan digital dan kesadaran siber (*cybersecurity awareness*).
