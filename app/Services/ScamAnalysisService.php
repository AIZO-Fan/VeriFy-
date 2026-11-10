<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScamAnalysisService
{
    /**
     * Bobot skor per indikator (total maks ~100 dari regex & URL check, + 50 dari Safe Browsing).
     */
    private const REGEX_RULES = [
        // 1. Data Sensitif & Akun
        ['/\b(otp|pin|password|kata\s*sandi|kode\s*verifikasi|cvv|cvc|nomor\s*kartu|masa\s*berlaku|foto\s*ktp)\b/ui',
         'Menyebut kode OTP, PIN, kata sandi, atau data rahasia kartu', 25],

        // 2. Hadiah, Undian & Giveaway
        ['/\b(hadiah|menang|undian|bonus\s*gratis|terpilih|pemenang|giveaway|baim\s*wong|tebus\s*murah|voucher\s*(gratis|belanja)|selamat\s*nomor\s*anda|gebyar\s*(undian|tahapan|britama))\b/ui',
         'Menggunakan iming-iming hadiah, undian fiktif, atau giveaway', 20],

        // 3. E-Wallet, Saldo & DANA Kaget
        ['/\b(dana\s*kaget|daget|klaim\s*saldo|saldo\s*gratis|gopay\s*gratis|shopeepay\s*gratis|kuota\s*gratis|pulsa\s*gratis|bagi-bagi\s*saldo)\b/ui',
         'Modus tautan DANA Kaget, saldo gratis, atau kuota/pulsa fiktif', 20],

        // 4. File Berbahaya / APK Malware (Undangan, Resi, Tilang ETLE)
        ['/\b(undangan\s*pernikahan|surat\s*tilang|tilang\s*elektronik|etle|resi\s*paket|foto\s*paket|kurir\s*(j&t|jne|sicepat|pos|anteraja)|surat\s*panggilan|tagihan\s*pln|file\s*apk|\.apk)\b/ui',
         'Indikasi penyebaran file berbahaya / malware APK (undangan, resi kurir, tilang ETLE)', 25],

        // 5. Tekanan Waktu & Ancaman
        ['/\b(segera|sekarang\s*juga|blokir|24\s*jam|1\s*jam|mendesak|batas\s*waktu|hangus|jangan\s*abaikan|sebelum\s*terlambat|peringatan\s*terakhir|kartu\s*akan\s*dinonaktifkan)\b/ui',
         'Menggunakan desakan waktu atau ancaman penonaktifan akun', 15],

        // 6. Permintaan Pembayaran & Transfer
        ['/\b(transfer|biaya\s*admin|bayar\s*dulu|uang\s*muka|dp\s*wajib|biaya\s*pencairan|pajak\s*hadiah|top\s*up\s*dulu|biaya\s*asuransi)\b/ui',
         'Meminta pembayaran biaya admin, pajak hadiah, atau transfer uang muka', 20],

        // 7. Investasi Bodong
        ['/\b(pasti\s*untung|tanpa\s*risiko|profit\s*tetap|investasi\s*aman|titip\s*dana|auto\s*profit|profit\s*\d+%|penghasilan\s*pasti)\b/ui',
         'Menjanjikan keuntungan investasi pasti tanpa risiko', 20],

        // 8. Lowongan Kerja & Freelance Like-Subscribe Palsu
        ['/\b(kerja\s*paruh\s*waktu|tugas\s*(like|follow|subscribe|tonton)|komisi\s*harian|gaji\s*per\s*hari|freelance\s*online|penghasilan\s*tambahan|order\s*fiktif|tugas\s*pesanan)\b/ui',
         'Modus lowongan kerja online / tugas review like & subscribe palsu', 20],

        // 9. Pinjaman Online Ilegal
        ['/\b(pinjaman\s*tanpa\s*(agunan|jaminan)|cair\s*(cepat|instan|kilat)|tanpa\s*bi\s*checking|bunga\s*0%|langsung\s*cair|dana\s*talangan|limit\s*pinjaman\s*tinggi)\b/ui',
         'Tawaran pinjaman online ilegal tanpa jaminan / cair instan', 20],

        // 10. Impersonasi Bank & Kenaikan Tarif
        ['/\b(kenaikan\s*tarif|perubahan\s*tarif|tarif\s*transaksi|teller|nasabah\s*prioritas|cs\s*(bca|bri|bni|mandiri)|halo\s*bca|kontak\s*briva|kartu\s*debit\s*diblokir|rekening\s*dibekukan|rekening\s*disuspend|petugas\s*resmi)\b/ui',
         'Mengaku sebagai pihak resmi bank / modus perubahan tarif transaksi', 20],

        // 11. Ajakan Akses Tautan & Kontak WhatsApp
        ['/\b(klik\s*(link|tautan|di\s*sini|web)|tap\s*link|hubungi\s*(admin|wa|whatsapp)|chat\s*wa|nomor\s*whatsapp|wa\.me)\b/ui',
         'Mendorong klik tautan luar atau chat WhatsApp tidak resmi', 10],

        // 12. Permintaan Kerahasiaan
        ['/\b(rahasia(kan)?|jangan\s*beritahu|jangan\s*cerita|jangan\s*sebar|bersifat\s*rahasia)\b/ui',
         'Meminta merahasiakan transaksi atau komunikasi', 15],
    ];

    /**
     * Domain resmi terverifikasi di Indonesia.
     * Tautan ke domain ini TIDAK AKAN ditandai sebagai phishing/typosquatting.
     */
    private const OFFICIAL_DOMAINS = [
        // Perbankan Indonesia
        'bca.co.id', 'klikbca.com', 'mybca.bca.co.id',
        'bri.co.id', 'ib.bri.co.id', 'brimo.bri.co.id',
        'bni.co.id', 'ibank.bni.co.id',
        'bankmandiri.co.id', 'mandiri.co.id', 'livinbybankmandiri.com',
        'cimbniaga.co.id', 'octoclicks.co.id',
        'btn.co.id', 'bankbsi.co.id', 'permata.co.id', 'permatanet.com',
        'danamon.co.id', 'ocbc.id', 'ocbcnisp.com', 'panin.co.id',
        'jenius.com', 'blu.id', 'jago.com', 'bankneo.co.id', 'seabank.co.id',

        // E-Wallet & Fintech Resmi
        'dana.id', 'ovo.id', 'gopay.co.id', 'linkaja.id',
        'doku.com', 'flip.id', 'midtrans.com', 'xendit.co',

        // E-Commerce & Marketplace Resmi
        'shopee.co.id', 'tokopedia.com', 'bukalapak.com', 'blibli.com', 'lazada.co.id',

        // Ekspedisi & BUMN
        'jne.co.id', 'jet.co.id', 'sicepat.com', 'posindonesia.co.id',
        'anteraja.id', 'tiki.id', 'pln.co.id', 'pertamina.com',

        // Telekomunikasi
        'telkomsel.com', 'indosatooredoo.com', 'tri.co.id', 'xl.co.id', 'smartfren.com',

        // Platform Global Terverifikasi
        'google.com', 'google.co.id', 'youtube.com', 'facebook.com',
        'instagram.com', 'whatsapp.com', 'twitter.com', 'x.com',
        'apple.com', 'microsoft.com', 'netflix.com', 'telegram.org',
    ];

    /**
     * Layanan pemendek tautan yang sering disalahgunakan untuk menyembunyikan alamat asli.
     */
    private const URL_SHORTENERS = [
        'bit.ly', 'tinyurl.com', 't.co', 'goo.gl', 'ow.ly',
        's.id', 'rb.gy', 'cutt.ly', 'short.ly', 'is.gd',
        'v.gd', 't.ly', 'rebrand.ly', 'linktr.ee', 'wa.me',
    ];

    /**
     * TLD (Top-Level Domain) berbiaya sangat murah atau gratis yang sering dipakai website phising/scam.
     */
    private const SUSPICIOUS_TLDS = [
        'xyz', 'top', 'online', 'site', 'buzz', 'club', 'work', 'icu',
        'vip', 'link', 'cc', 'click', 'live', 'shop', 'tk', 'ml', 'ga',
        'cf', 'gq', 'zip', 'mov', 'fun', 'rest', 'pw', 'surf',
    ];

    /**
     * Kata kunci sensitif pada nama domain atau path URL yang umum digunakan penipu pada domain tidak resmi.
     */
    private const SUSPICIOUS_URL_KEYWORDS = [
        'dana-kaget', 'daget', 'undian', 'hadiah', 'klaim', 'pulsa-gratis', 'saldo-gratis',
        'bca', 'klikbca', 'mybca', 'bri', 'brimo', 'briva', 'bni', 'mandiri', 'livin',
        'login', 'masuk', 'verifikasi', 'konfirmasi', 'validasi', 'keamanan', 'update-akun',
        'cs-bca', 'cs-bri', 'promo-bank', 'gebyar', 'bansos', 'prakerja', 'bpjs', 'subsidi',
        'blt', 'kuota', 'giveaway',
    ];

    /**
     * Analisis utama: gabungkan regex rules + cek URL heuristik & whitelist + Google Safe Browsing.
     *
     * @return array{flags: list<string>, score: int, status: string, threat_urls: list<string>, urls_checked: int}
     */
    public function analyze(string $content): array
    {
        $text       = mb_strtolower($content);
        $flags      = [];
        $score      = 0;
        $threatUrls = [];

        // Ekstrak seluruh URL dari konten
        $urls = $this->extractUrls($content);

        // --- 1. Regex rules pada teks pesan ---
        // Jika konten mengandung URL resmi murni, jangan biarkan nama host memicu regex secara salah
        $textToAnalyze = $text;
        foreach ($urls as $u) {
            $uHost = mb_strtolower(parse_url($u, PHP_URL_HOST) ?: '');
            if ($this->isOfficialDomain($uHost)) {
                $textToAnalyze = str_ireplace($u, '', $textToAnalyze);
            }
        }

        $normalizedText = preg_replace('/[-_.\/]/u', ' ', $textToAnalyze);
        foreach (self::REGEX_RULES as [$pattern, $label, $weight]) {
            if (preg_match($pattern, $textToAnalyze) || preg_match($pattern, $normalizedText)) {
                $flags[] = $label;
                $score  += $weight;
            }
        }

        // --- 2. Analisis Struktur & Reputasi URL ---
        foreach ($urls as $url) {
            $parsed = parse_url($url);
            $host   = mb_strtolower($parsed['host'] ?? '');
            $path   = mb_strtolower($parsed['path'] ?? '');

            $isOfficial = $this->isOfficialDomain($host);

            // JIKA DOMAIN RESMI TERVERIFIKASI:
            if ($isOfficial) {
                // Domain resmi hanya dicek apakah ada file unduhan berbahaya (.apk/.exe)
                if (preg_match('/\.(apk|exe|scr|bat|vbs|iso)(\?.*)?$/i', $path)) {
                    $flags[] = 'Tautan mengarah langsung ke pengunduhan file aplikasi (.apk/.exe)';
                    $score  += 40;
                }
                // Skip seluruh pengecekan kecurigaan kata kunci, TLD, atau typosquatting untuk domain resmi!
                continue;
            }

            // JIKA BUKAN DOMAIN RESMI: Lakukan pemeriksaan ketat terhadap pola penipuan:
            // a. Cek apakah menggunakan Alamat IP mentah
            if (filter_var($host, FILTER_VALIDATE_IP)) {
                $flags[] = 'Tautan menggunakan alamat IP, bukan nama domain resmi (' . $host . ')';
                $score  += 25;
            }

            // b. Cek URL Shortener
            foreach (self::URL_SHORTENERS as $shortener) {
                if ($host === $shortener || str_ends_with($host, '.' . $shortener)) {
                    $flags[] = 'Tujuan tautan disamarkan oleh pemendek URL (' . $shortener . ')';
                    $score  += 15;
                    break;
                }
            }

            // c. Cek Protokol HTTP tidak aman
            if (str_starts_with($url, 'http://')) {
                $flags[] = 'Tautan tidak menggunakan HTTPS (koneksi tidak terenkripsi)';
                $score  += 10;
            }

            // d. Domain IDN Punycode
            if (str_contains($host, 'xn--')) {
                $flags[] = 'Domain memakai karakter terenkode (IDN) yang rawan penyamaran';
                $score  += 20;
            }

            // e. Cek Ekstensi Domain (TLD) Berisiko Tinggi
            $hostParts = explode('.', $host);
            $tld = end($hostParts);
            if (in_array($tld, self::SUSPICIOUS_TLDS, true)) {
                $flags[] = 'Domain memakai ekstensi berisiko tinggi (.' . $tld . ') yang umum dipakai situs phishing';
                $score  += 20;
            }

            // f. Cek Subdomain Spoofing (mengecoh dengan menaruh nama bank/layanan di subdomain tidak resmi)
            foreach (self::OFFICIAL_DOMAINS as $official) {
                if (str_contains($host, $official) && !str_ends_with($host, $official)) {
                    $flags[] = "Domain menyamarkan nama layanan resmi ($official) sebagai subdomain palsu";
                    $score  += 30;
                    break;
                }
            }

            // g. Cek Kata Kunci Phishing di Host atau Path URL (HANYA untuk domain tidak resmi)
            $urlContentForKeywords = $host . $path;
            foreach (self::SUSPICIOUS_URL_KEYWORDS as $kw) {
                if (str_contains($urlContentForKeywords, $kw)) {
                    $flags[] = "Alamat tautan mengandung kata kunci sensitif/penipuan ('$kw')";
                    $score  += 25;
                    break;
                }
            }

            // h. Cek Pengunduhan File Berbahaya / Malware APK di URL
            if (preg_match('/\.(apk|exe|scr|bat|vbs|iso)(\?.*)?$/i', $path)) {
                $flags[] = 'Tautan mengarah langsung ke pengunduhan file aplikasi berbahaya (.apk/.exe)';
                $score  += 40;
            }

            // i. Typosquatting Nama Layanan Populer
            if ($this->isTyposquatting($host)) {
                $flags[] = 'Domain menyerupai nama layanan terkenal (kemungkinan typosquatting)';
                $score  += 25;
            }
        }

        // --- 3. Google Safe Browsing Lookup API ---
        $safeBrowsingResult = [];
        if (!empty($urls)) {
            $safeBrowsingResult = $this->checkSafeBrowsing($urls);
            foreach ($safeBrowsingResult as $match) {
                $threatType = $this->translateThreatType($match['threatType'] ?? 'THREAT');
                $matchedUrl = $match['threat']['url'] ?? '';
                $flags[]    = 'Google Safe Browsing: ' . $threatType . ($matchedUrl ? ' — ' . $matchedUrl : '');
                $score     += 50;
                $threatUrls[] = $matchedUrl;
            }
        }

        $score      = min($score, 100);
        $flags      = array_values(array_unique($flags));
        $threatUrls = array_values(array_unique($threatUrls));

        return [
            'flags'        => $flags,
            'score'        => $score,
            'status'       => $this->resolveStatus($score, $safeBrowsingResult),
            'threat_urls'  => $threatUrls,
            'urls_checked' => count($urls),
        ];
    }

    /**
     * Memeriksa apakah domain merupakan domain resmi/terverifikasi.
     */
    private function isOfficialDomain(string $host): bool
    {
        $bare = preg_replace('/^www\./', '', mb_strtolower($host));

        // Domain pemerintah, militer, dan akademis Indonesia (.go.id, .mil.id, .ac.id) otomatis resmi
        if (preg_match('/\.(go|mil|ac)\.id$/i', $bare)) {
            return true;
        }

        foreach (self::OFFICIAL_DOMAINS as $official) {
            if ($bare === $official || str_ends_with($bare, '.' . $official)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ekstrak dan normalisasi seluruh URL dari teks (baik dengan http/https maupun tanpa http).
     *
     * @return list<string>
     */
    private function extractUrls(string $content): array
    {
        $urls = [];

        // 1. Ekstrak URL lengkap (http:// atau https://)
        preg_match_all('~https?://[^\s<>"\']+~iu', $content, $fullMatches);
        foreach ($fullMatches[0] as $u) {
            $cleaned = rtrim($u, '.,;:!?)]}');
            if (filter_var($cleaned, FILTER_VALIDATE_URL)) {
                $urls[] = $cleaned;
            }
        }

        // 2. Ekstrak URL tanpa protokol (seperti bit.ly/xxx, s.id/xxx, nama-web.xyz/xxx, dsb.)
        preg_match_all('~\b(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+(?:com|id|co\.id|net|org|xyz|site|top|online|click|club|buzz|app|io|me|ru|cn|live|shop|link|info|vip|work|icu|cc|ly|gd|gl|co|to)[^\s<>"\']*~iu', $content, $bareMatches);
        foreach ($bareMatches[0] as $u) {
            $cleaned = rtrim($u, '.,;:!?)]}');
            $withHttp = 'https://' . $cleaned;
            if (filter_var($withHttp, FILTER_VALIDATE_URL) && !in_array($withHttp, $urls, true)) {
                $urls[] = $withHttp;
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * Kirim URL ke Google Safe Browsing Lookup API v4.
     * Hasil di-cache 1 jam per kombinasi URL untuk menghemat kuota API.
     *
     * @param  list<string> $urls
     * @return list<array<string, mixed>>
     */
    private function checkSafeBrowsing(array $urls): array
    {
        $apiKey = config('services.google.safe_browsing_key');

        if (empty($apiKey)) {
            return [];
        }

        $cacheKey = 'safe_browsing_' . md5(implode('|', $urls));

        return Cache::remember($cacheKey, 3600, function () use ($urls, $apiKey) {
            try {
                $client   = new Client(['timeout' => 5]);
                $endpoint = 'https://safebrowsing.googleapis.com/v4/threatMatches:find';

                $body = [
                    'client'     => ['clientId' => 'verify-app', 'clientVersion' => '1.0'],
                    'threatInfo' => [
                        'threatTypes'      => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                        'platformTypes'    => ['ANY_PLATFORM'],
                        'threatEntryTypes' => ['URL'],
                        'threatEntries'    => array_map(fn (string $u): array => ['url' => $u], $urls),
                    ],
                ];

                $response = $client->post($endpoint, [
                    'query' => ['key' => $apiKey],
                    'json'  => $body,
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                return $data['matches'] ?? [];
            } catch (GuzzleException $e) {
                Log::warning('Safe Browsing API error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Deteksi typosquatting: bandingkan host dengan daftar domain resmi terkenal.
     */
    private function isTyposquatting(string $host): bool
    {
        $bare = preg_replace('/^www\./', '', $host);

        // Jika domain asli, jangan anggap typosquatting
        if ($this->isOfficialDomain($host)) {
            return false;
        }

        foreach (self::OFFICIAL_DOMAINS as $known) {
            // Levenshtein distance 1-2 dan panjang mirip -> kemungkinan typosquatting
            $dist = levenshtein($bare, $known);
            if ($dist > 0 && $dist <= 2 && abs(strlen($bare) - strlen($known)) <= 2) {
                return true;
            }
        }

        return false;
    }

    /**
     * Terjemahkan kode threat type ke Bahasa Indonesia.
     */
    private function translateThreatType(string $type): string
    {
        return match ($type) {
            'MALWARE'                         => 'URL terdeteksi mengandung Malware',
            'SOCIAL_ENGINEERING'              => 'URL terdeteksi sebagai Phishing / Social Engineering',
            'UNWANTED_SOFTWARE'               => 'URL terdeteksi mengandung software berbahaya',
            'POTENTIALLY_HARMFUL_APPLICATION' => 'URL terdeteksi sebagai aplikasi berbahaya',
            default                           => 'URL terdeteksi sebagai ancaman',
        };
    }

    /**
     * Tentukan label status berdasarkan skor dan hasil Safe Browsing.
     */
    private function resolveStatus(int $score, array $safeBrowsingMatches): string
    {
        if (!empty($safeBrowsingMatches)) {
            return 'Berbahaya — terdeteksi oleh Google Safe Browsing';
        }

        return match (true) {
            $score >= 60 => 'Perlu kewaspadaan tinggi',
            $score >= 30 => 'Perlu diperiksa lebih lanjut',
            $score > 0   => 'Ada satu indikator, tetap waspada',
            default      => 'Tidak ada indikator pada pemeriksaan ini',
        };
    }
}
