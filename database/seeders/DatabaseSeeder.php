<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin VeriFy
        User::updateOrCreate(
            ['email' => 'admin@verify.id'],
            [
                'name' => 'Administrator VeriFy',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Demo User Biasa
        User::updateOrCreate(
            ['email' => 'user@verify.id'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // 3. Seed Default Educational Modules
        $initialModules = [
            [
                'id' => 1,
                'title' => 'Introduction to Digital Scam',
                'description' => 'Kenali penipuan digital dan cara melindungi diri.',
                'color' => 'blue',
                'icon' => 'shield',
                'order' => 1,
                'is_active' => true,
                'lessons' => [
                    'Penipuan digital menggunakan pesan, situs, atau identitas palsu untuk mengambil uang maupun informasi pribadi.',
                    'Periksa identitas pengirim melalui saluran yang sudah Anda kenal, bukan nomor yang tercantum dalam pesan mencurigakan.',
                    'Jangan terburu-buru mengambil keputusan. Hentikan interaksi ketika Anda diminta merahasiakan transaksi.',
                ],
            ],
            [
                'id' => 2,
                'title' => 'Social Engineering',
                'description' => 'Kenali teknik manipulasi psikologis yang digunakan penipu.',
                'color' => 'purple',
                'icon' => 'brain',
                'order' => 2,
                'is_active' => true,
                'lessons' => [
                    'Penipu dapat menyamar sebagai petugas, teman, atau pihak berwenang untuk membangun kepercayaan.',
                    'Waspadai desakan waktu dan permintaan kode OTP, PIN, atau kata sandi.',
                    'Verifikasi permintaan melalui kontak resmi yang Anda cari sendiri.',
                ],
            ],
            [
                'id' => 3,
                'title' => 'Phishing & Malicious Link',
                'description' => 'Pelajari cara mengenali pesan dan tautan berbahaya.',
                'color' => 'green',
                'icon' => 'link',
                'order' => 3,
                'is_active' => true,
                'lessons' => [
                    'Phishing berusaha mengarahkan Anda ke halaman palsu yang meminta data sensitif.',
                    'Periksa domain, bukan hanya logo atau tampilan situs. HTTPS saja tidak membuktikan suatu situs dapat dipercaya.',
                    'Jangan memasukkan kata sandi dari tautan pesan yang tidak Anda harapkan.',
                ],
            ],
            [
                'id' => 4,
                'title' => 'Online Shopping & Investment Scams',
                'description' => 'Kenali modus penipuan belanja online dan investasi.',
                'color' => 'orange',
                'icon' => 'cart',
                'order' => 4,
                'is_active' => true,
                'lessons' => [
                    'Waspadai harga yang tidak masuk akal dan janji keuntungan pasti tanpa risiko.',
                    'Hindari transaksi di luar platform ketika Anda tidak mengenal penjual.',
                    'Periksa legalitas penyedia layanan keuangan melalui otoritas terkait.',
                ],
            ],
            [
                'id' => 5,
                'title' => 'Account & Data Protection',
                'description' => 'Pelajari cara melindungi akun dan data pribadi.',
                'color' => 'cyan',
                'icon' => 'lock',
                'order' => 5,
                'is_active' => true,
                'lessons' => [
                    'Gunakan kata sandi unik untuk setiap akun dan pengelola kata sandi bila memungkinkan.',
                    'Aktifkan autentikasi multifaktor dan jangan bagikan kode verifikasi.',
                    'Tinjau perangkat yang login serta perbarui aplikasi secara berkala.',
                ],
            ],
            [
                'id' => 6,
                'title' => 'Scam Reporting & Prevention',
                'description' => 'Ketahui cara merespons, melapor, dan mencegah penipuan.',
                'color' => 'pink',
                'icon' => 'report',
                'order' => 6,
                'is_active' => true,
                'lessons' => [
                    'Simpan tangkapan layar, waktu kejadian, identitas pengirim, dan bukti transaksi.',
                    'Jika data akun terungkap, ubah kata sandi melalui aplikasi atau situs resmi.',
                    'Jika terjadi transfer dana, segera hubungi bank atau penyedia pembayaran melalui saluran resmi. ',
                ],
            ],
        ];

        foreach ($initialModules as $mod) {
            Module::updateOrCreate(
                ['id' => $mod['id']],
                $mod
            );
        }

        // 4. Jalankan seeder simulasi skenario jika ada
        if (class_exists(SimulationScenarioSeeder::class)) {
            $this->call([
                SimulationScenarioSeeder::class,
            ]);
        }
    }
}
