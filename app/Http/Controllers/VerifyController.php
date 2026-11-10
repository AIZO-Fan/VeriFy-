<?php

namespace App\Http\Controllers;

use App\Services\ScamAnalysisService;
use Illuminate\Http\Request;

class VerifyController extends Controllers
{
    public function __construct(private readonly ScamAnalysisService $scamAnalysisService) {}

    private function data(): array
    {
        $dbModules = \App\Models\Module::where('is_active', true)->orderBy('order')->orderBy('id')->get();
        if ($dbModules->isNotEmpty()) {
            return $dbModules->toArray();
        }

        return [['id' => 1, 'title' => 'Introduction to Digital Scam', 'description' => 'Kenali penipuan digital dan cara melindungi diri.', 'color' => 'blue', 'icon' => 'shield', 'lessons' => ['Penipuan digital menggunakan pesan, situs, atau identitas palsu untuk mengambil uang maupun informasi pribadi.', 'Periksa identitas pengirim melalui saluran yang sudah Anda kenal, bukan nomor yang tercantum dalam pesan mencurigakan.', 'Jangan terburu-buru mengambil keputusan. Hentikan interaksi ketika Anda diminta merahasiakan transaksi.']], ['id' => 2, 'title' => 'Social Engineering', 'description' => 'Kenali teknik manipulasi psikologis yang digunakan penipu.', 'color' => 'purple', 'icon' => 'brain', 'lessons' => ['Penipu dapat menyamar sebagai petugas, teman, atau pihak berwenang untuk membangun kepercayaan.', 'Waspadai desakan waktu dan permintaan kode OTP, PIN, atau kata sandi.', 'Verifikasi permintaan melalui kontak resmi yang Anda cari sendiri.']], ['id' => 3, 'title' => 'Phishing & Malicious Link', 'description' => 'Pelajari cara mengenali pesan dan tautan berbahaya.', 'color' => 'green', 'icon' => 'link', 'lessons' => ['Phishing berusaha mengarahkan Anda ke halaman palsu yang meminta data sensitif.', 'Periksa domain, bukan hanya logo atau tampilan situs. HTTPS saja tidak membuktikan suatu situs dapat dipercaya.', 'Jangan memasukkan kata sandi dari tautan pesan yang tidak Anda harapkan.']], ['id' => 4, 'title' => 'Online Shopping & Investment Scams', 'description' => 'Kenali modus penipuan belanja online dan investasi.', 'color' => 'orange', 'icon' => 'cart', 'lessons' => ['Waspadai harga yang tidak masuk akal dan janji keuntungan pasti tanpa risiko.', 'Hindari transaksi di luar platform ketika Anda tidak mengenal penjual.', 'Periksa legalitas penyedia layanan keuangan melalui otoritas terkait.']], ['id' => 5, 'title' => 'Account & Data Protection', 'description' => 'Pelajari cara melindungi akun dan data pribadi.', 'color' => 'cyan', 'icon' => 'lock', 'lessons' => ['Gunakan kata sandi unik untuk setiap akun dan pengelola kata sandi bila memungkinkan.', 'Aktifkan autentikasi multifaktor dan jangan bagikan kode verifikasi.', 'Tinjau perangkat yang login serta perbarui aplikasi secara berkala.']], ['id' => 6, 'title' => 'Scam Reporting & Prevention', 'description' => 'Ketahui cara merespons, melapor, dan mencegah penipuan.', 'color' => 'pink', 'icon' => 'report', 'lessons' => ['Simpan tangkapan layar, waktu kejadian, identitas pengirim, dan bukti transaksi.', 'Jika data akun terungkap, ubah kata sandi melalui aplikasi atau situs resmi.', 'Jika terjadi transfer dana, segera hubungi bank atau penyedia pembayaran melalui saluran resmi.']]];
    }
    public function home()
    {
        return view('pages.home');
    }
    public function detect()
    {
        return view('pages.detect', ['result' => null]);
    }
    public function how()
    {
        return view('pages.how');
    }
    public function modules()
    {
        return view('pages.modules', ['modules' => $this->data()]);
    }
    public function lesson(int $id)
    {
        $module = collect($this->data())->firstWhere('id', $id);
        abort_unless($module, 404);
        return view('pages.lesson', compact('module'));
    }
    public function simulator()
    {
        return view('pages.simulator', ['modules' => $this->data()]);
    }
    public function analyze(Request $request): \Illuminate\View\View
    {
        $input = $request->validate([
            'mode'    => 'required|in:text,url',
            'content' => 'required|string|max:5000',
        ]);

        // Auto-upgrade mode ke 'url' jika input adalah URL murni, atau normalisasi URL
        $trimmed = trim($input['content']);
        if ($input['mode'] === 'url') {
            if (!preg_match('~^https?://~i', $trimmed)) {
                $trimmed = 'https://' . $trimmed;
                $input['content'] = $trimmed;
            }
            if (!filter_var($trimmed, FILTER_VALIDATE_URL)) {
                return back()->withErrors(['content' => 'Format tautan URL tidak valid.'])->withInput();
            }
        } elseif (preg_match('~^https?://\S+$~i', $trimmed)) {
            if (!filter_var($trimmed, FILTER_VALIDATE_URL)) {
                return back()->withErrors(['content' => 'Format tautan URL tidak valid.'])->withInput();
            }
        }

        $result = $this->scamAnalysisService->analyze($input['content']);

        if (auth()->check()) {
            auth()->user()->scanHistories()->create([
                'content'      => $input['content'],
                'mode'         => $input['mode'],
                'score'        => $result['score'] ?? 0,
                'status'       => $result['status'] ?? '',
                'flags'        => $result['flags'] ?? [],
                'urls_checked' => $result['urls_checked'] ?? 0,
                'threat_urls'  => $result['threat_urls'] ?? [],
            ]);
        }

        return view('pages.detect', compact('result'));
    }
}
