<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleSection;
use App\Models\SimulationScenario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modulesData = [
            [
                'id' => 1,
                'title' => 'Jelajah Dunia Digital dengan Aman',
                'description' => 'Kenali tanda awal penipuan digital dan tentukan respons yang tepat.',
                'sections' => [
                    [
                        'title' => 'Tujuan Pembelajaran',
                        'type' => 'text',
                        'content' => '<p>Memahami dasar-dasar keamanan digital dan mengenali bentuk penipuan yang sering terjadi di kehidupan sehari-hari.</p>',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Cara Kerja Penipuan Hadiah',
                        'type' => 'example',
                        'content' => '<ul><li><strong>Kontak Awal:</strong> Pelaku mengirim pesan singkat atau menelpon dengan kabar memenangkan hadiah besar.</li><li><strong>Kunci Manipulasi:</strong> Pelaku meminta biaya administrasi/pencairan di awal sebelum hadiah dikirim.</li></ul>',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Ringkasan',
                        'type' => 'summary',
                        'content' => '<p>Jangan pernah mengirimkan uang untuk mengklaim hadiah yang tidak pernah kamu ikuti undiannya.</p>',
                        'sort_order' => 3,
                    ],
                ]
            ],
            [
                'id' => 2,
                'title' => 'Waspada Manipulasi & Rekayasa Sosial',
                'description' => 'Hadapi upaya manipulasi yang memanfaatkan rasa percaya dan kepanikan.',
                'sections' => [
                    [
                        'title' => 'Mengenal Rekayasa Sosial (Social Engineering)',
                        'type' => 'text',
                        'content' => '<p>Rekayasa sosial adalah teknik manipulasi psikologis agar korban memberikan informasi rahasia atau mentransfer uang secara sukarela.</p>',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 3,
                'title' => 'Deteksi Pesan & Tautan Mencurigakan',
                'description' => 'Cermati pesan dan tautan mencurigakan sebelum mengambil tindakan.',
                'sections' => [
                    [
                        'title' => 'Identifikasi Phishing',
                        'type' => 'text',
                        'content' => '<p>Periksa domain URL dengan teliti. Pelaku sering menggunakan nama domain menyerupai situs resmi (misal: penipuan-bank.com).</p>',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 4,
                'title' => 'Evaluasi Penawaran & Transaksi Riskan',
                'description' => 'Tinjau penawaran belanja dan investasi yang terlihat terlalu menggiurkan.',
                'sections' => [
                    [
                        'title' => 'Prinsip Penawaran Menggiurkan',
                        'type' => 'text',
                        'content' => '<p>Jika suatu penawaran investasi atau harga barang terlihat terlalu bagus untuk jadi kenyataan, hampir dipastikan itu adalah penipuan.</p>',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 5,
                'title' => 'Perlindungan Akun & Identitas Pribadi',
                'description' => 'Latih keputusan untuk menjaga akses akun dan informasi pribadimu.',
                'sections' => [
                    [
                        'title' => 'Pentingnya OTP dan Password',
                        'type' => 'text',
                        'content' => '<p>Jangan pernah membagikan kode OTP atau kata sandi kepada siapapun, termasuk pihak yang mengaku petugas resmi.</p>',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 6,
                'title' => 'Langkah Penanganan & Pelaporan',
                'description' => 'Pelajari langkah awal saat menemukan atau mengalami dugaan penipuan.',
                'sections' => [
                    [
                        'title' => 'Tindakan Darurat saat Terjebak Penipuan',
                        'type' => 'summary',
                        'content' => '<ol><li>Blokir rekening/kartu bank terkait.</li><li>Amankan akun digital dan ubah kata sandi.</li><li>Laporkan insiden ke pihak berwajib dan layanan AduanKonten/Lapor.go.id.</li></ol>',
                        'sort_order' => 1,
                    ],
                ]
            ],
        ];

        foreach ($modulesData as $index => $data) {
            $module = Module::updateOrCreate(
                ['id' => $data['id']],
                [
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']),
                    'description' => $data['description'],
                    'status' => 'published',
                    'sort_order' => $index + 1,
                ]
            );

            // Masukkan section materi
            if (isset($data['sections'])) {
                foreach ($data['sections'] as $sec) {
                    ModuleSection::updateOrCreate(
                        [
                            'module_id' => $module->id,
                            'title' => $sec['title']
                        ],
                        [
                            'type' => $sec['type'],
                            'content' => $sec['content'],
                            'sort_order' => $sec['sort_order'],
                        ]
                    );
                }
            }

            // Hubungkan skenario simulasi lama berdasarkan topic_id
            SimulationScenario::where('topic_id', $module->id)->update([
                'module_id' => $module->id,
                'status' => 'published',
            ]);
        }
    }
}
