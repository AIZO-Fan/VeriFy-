<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimulationScenario;
use App\Services\StoryValidator;

class SimulationScenarioSeeder extends Seeder
{
    public function run()
    {
        $storyData = [
            "schema_version" => 1,
            "start" => "pesan_awal",
            "contact" => ["name" => "Admin Hadiah", "avatar_text" => "H", "subtitle" => "Nomor tidak dikenal · Simulasi"],
            "context" => ["title" => "Kamu menerima kabar menang hadiah", "description" => "Kamu merasa tidak pernah mengikuti program tersebut."],
            "nodes" => [
                "pesan_awal" => [
                    "type" => "conversation",
                    "stage" => "situation",
                    "messages" => [
                        ["sender" => "contact", "text" => "Selamat! Kamu terpilih mendapat hadiah Rp2.000.000."],
                        ["sender" => "contact", "text" => "Untuk pencairan, ada biaya administrasi Rp25.000."]
                    ],
                    "prompt" => "Apa yang ingin kamu lakukan?",
                    "choices" => [
                        ["id" => "setuju", "label" => "Setuju membayar", "reply" => "Baik, saya setuju membayar.", "next" => "instruksi_bayar"],
                        ["id" => "tanya", "label" => "Tanyakan asal program", "reply" => "Ini program apa? Saya tidak merasa mengikutinya.", "next" => "jawaban_pengirim"],
                        ["id" => "periksa", "label" => "Periksa melalui saluran resmi", "reply" => null, "next" => "hasil_aman"]
                    ]
                ],
                "jawaban_pengirim" => [
                    "type" => "conversation",
                    "stage" => "decision",
                    "messages" => [["sender" => "contact", "text" => "Ini program pelanggan terpilih. Harus segera diproses agar hadiahnya tidak hangus."]],
                    "prompt" => "Setelah mendengar jawaban itu, apa langkahmu?",
                    "choices" => [
                        ["id" => "lanjut", "label" => "Lanjutkan ke pembayaran", "reply" => "Baik, bagaimana pembayarannya?", "next" => "instruksi_bayar"],
                        ["id" => "berhenti", "label" => "Berhenti dan periksa dahulu", "reply" => null, "next" => "hasil_aman"]
                    ]
                ],
                "instruksi_bayar" => [
                    "type" => "conversation",
                    "stage" => "decision",
                    "messages" => [["sender" => "contact", "text" => "Silakan bayar biaya administrasi ke penerima berikut."]],
                    "prompt" => "Lihat konfirmasi sebelum melanjutkan.",
                    "choices" => [
                        ["id" => "lihat", "label" => "Lihat pembayaran simulasi", "reply" => null, "next" => "konfirmasi_bayar"],
                        ["id" => "batal", "label" => "Batalkan dan periksa", "reply" => null, "next" => "hasil_aman"]
                    ]
                ],
                "konfirmasi_bayar" => [
                    "type" => "payment",
                    "stage" => "decision",
                    "title" => "Konfirmasi pembayaran simulasi",
                    "purpose" => "Biaya administrasi hadiah",
                    "recipient" => "Penerima Fiktif",
                    "amount" => 25000,
                    "currency" => "IDR",
                    "confirm" => ["id" => "bayar", "label" => "Lanjutkan pembayaran simulasi", "next" => "permintaan_tambahan"],
                    "cancel" => ["id" => "batal_bayar", "label" => "Batalkan dan periksa dahulu", "next" => "hasil_aman"]
                ],
                "permintaan_tambahan" => [
                    "type" => "conversation",
                    "stage" => "consequence",
                    "messages" => [
                        ["sender" => "system", "text" => "Pembayaran fiktif Rp25.000 selesai. Tidak ada uang sungguhan yang dipindahkan."],
                        ["sender" => "contact", "text" => "Ada biaya aktivasi tambahan Rp100.000 agar hadiah bisa dicairkan."]
                    ],
                    "prompt" => "Lihat dampak keputusan dalam cerita ini.",
                    "choices" => [["id" => "lihat_dampak", "label" => "Lihat dampak dan penjelasan", "reply" => null, "next" => "hasil_berisiko"]]
                ],
                "hasil_berisiko" => [
                    "type" => "outcome",
                    "stage" => "consequence",
                    "tone" => "warning",
                    "title" => "Biaya dibayar, hadiah belum diterima",
                    "description" => "Dalam cerita ini, pembayaran awal diikuti permintaan biaya tambahan.",
                    "score" => 0,
                    "max_score" => 100,
                    "scoring_version" => 1,
                    "breakdown" => ["Keputusan" => "Membayar tanpa verifikasi sumber (0 poin)"],
                    "impact" => ["label" => "Dampak dalam simulasi", "text" => "Rp25.000 dibayarkan secara fiktif", "simulated_loss_amount" => 25000],
                    "reflection" => [
                        "summary" => "Mari pahami hubungan antara keputusan dan akibatnya.",
                        "what_happened" => "Kamu membayar sebelum memastikan identitas pengirim.",
                        "why_it_matters" => "Klaim hadiah dan desakan waktu membuat pemeriksaan terlewat.",
                        "safer_alternative" => "Hentikan pembayaran tambahan dan periksa melalui kanal resmi.",
                        "signals" => ["Hadiah disertai biaya awal.", "Asal program tidak jelas.", "Ada desakan waktu."]
                    ]
                ],
                "hasil_aman" => [
                    "type" => "outcome",
                    "stage" => "consequence",
                    "tone" => "positive",
                    "title" => "Kamu berhenti sebelum membayar",
                    "description" => "Pada kanal resmi fiktif dalam cerita ini, program tersebut tidak ditemukan.",
                    "score" => 100,
                    "max_score" => 100,
                    "scoring_version" => 1,
                    "breakdown" => ["Keputusan" => "Berhasil mengidentifikasi penipuan (100 poin)"],
                    "impact" => ["label" => "Dampak dalam simulasi", "text" => "Tidak ada pembayaran", "simulated_loss_amount" => 0],
                    "reflection" => [
                        "summary" => "Kamu memilih memeriksa klaim melalui sumber terpisah.",
                        "what_happened" => "Pembayaran tidak diteruskan.",
                        "why_it_matters" => "Pengakuan pengirim saja belum membuktikan identitas.",
                        "safer_alternative" => "Tetap gunakan kanal resmi untuk memeriksa informasi.",
                        "signals" => ["Permintaan pembayaran untuk hadiah.", "Program belum terverifikasi."]
                    ]
                ]
            ]
        ];

        // Validasi JSON sebelum insert (Mencegah salah seeder)
        StoryValidator::validate($storyData);

        SimulationScenario::create([
            'topic_id' => 1,
            'slug' => 'hadiah-biaya-administrasi',
            'title' => 'Hadiah yang Meminta Biaya',
            'description' => 'Nomor asing menawarkan hadiah dengan biaya awal.',
            'difficulty' => 'beginner',
            'is_active' => true,
            'version' => 1,
            'story' => $storyData
        ]);
    }
}
