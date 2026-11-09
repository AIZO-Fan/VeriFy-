<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScamAnalysisService
{
    /**
     * Bobot skor per indikator (total maks ~100 dari regex, URL check, + 50 dari Safe Browsing).
     */
    private const REGEX_RULES = [
        ['/\b(otp|pin|password|kata sandi|kode verifikasi)\b/u',         'Menyebut kode OTP, PIN, atau kata sandi',           20],
        ['/(hadiah|menang|undian|bonus gratis|terpimelih|penang)/u',      'Menggunakan iming-iming hadiah atau undian',         20],
        ['/(segera|sekarang juga|blokir|24 jam|mendesak|batas waktu)/u',  'Menggunakan tekanan waktu atau ancaman',             15],
        ['/(transfer|biaya admin|bayar dulu|uang muka|dp wajib)/u',       'Meminta pembayaran atau transfer dana',              20],
        ['/(pasti untung|tanpa risiko|profit tetap|investasi aman)/u',    'Menjanjikan keuntungan tanpa risiko',                20],
        ['/(klik link|klik tautan|klik di sini|tap link)/u',             'Mendorong klik tautan secara mendesak',              10],
        ['/(rahasia(kan)?|jangan beritahu|jangan cerita)/u',             'Meminta merahasiakan transaksi atau komunikasi',    15],
        ['/(menyamar|mengatasnamakan|pihak bank|customer service)/u',    'Mengaku sebagai pihak resmi atau bank',              15],
    ];

    private const URL_SHORTENERS = [
        'bit.ly', 'tinyurl.com', 't.co', 'goo.gl', 'ow.ly',
        's.id', 'rb.gy', 'cutt.ly', 'short.ly', 'is.gd',
    ];

    /**
     * Analisis utama: gabungkan regex rules + cek URL + Google Safe Browsing.
     *
     * @return array{flags: list<string>, score: int, status: string, threat_urls: list<string>, urls_checked: int}
     */
    public function analyze(string $content): array
    {
        $text       = mb_strtolower($content);
        $flags      = [];
        $score      = 0;
        $threatUrls = [];

        // --- 1. Regex rules pada teks ---
        foreach (self::REGEX_RULES as [$pattern, $label, $weight]) {
            if (preg_match($pattern, $text)) {
                $flags[] = $label;
                $score  += $weight;
            }
        }

        // --- 2. Ekstrak & analisis URL ---
        preg_match_all('~https?://[^\s<>"\']+~iu', $content, $matches);
        $urls = array_values(array_unique($matches[0]));

        foreach ($urls as $url) {
            $host = mb_strtolower(parse_url($url, PHP_URL_HOST) ?: '');

            if (filter_var($host, FILTER_VALIDATE_IP)) {
                $flags[] = 'Tautan menggunakan alamat IP, bukan nama domain';
                $score  += 20;
            }

            foreach (self::URL_SHORTENERS as $shortener) {
                if ($host === $shortener || str_ends_with($host, '.' . $shortener)) {
                    $flags[] = 'Tujuan tautan disamarkan oleh pemendek URL (' . $shortener . ')';
                    $score  += 15;
                    break;
                }
            }

            if (str_starts_with($url, 'http://')) {
                $flags[] = 'Tautan tidak menggunakan HTTPS (tidak terenkripsi)';
                $score  += 10;
            }

            if (str_contains($host, 'xn--')) {
                $flags[] = 'Domain memakai karakter terenkode (IDN) — perlu diperiksa manual';
                $score  += 15;
            }

            if ($this->isTyposquatting($host)) {
                $flags[] = 'Domain menyerupai nama layanan terkenal (kemungkinan typosquatting)';
                $score  += 25;
            }
        }

        // --- 3. Google Safe Browsing ---
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
     * Kirim URL ke Google Safe Browsing Lookup API v4.
     * Hasil di-cache 1 jam per kombinasi URL untuk hemat kuota.
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
     * Deteksi sederhana typosquatting: bandingkan host dengan daftar domain terkenal.
     */
    private function isTyposquatting(string $host): bool
    {
        $knownDomains = [
            'google.com', 'facebook.com', 'instagram.com', 'twitter.com',
            'whatsapp.com', 'tokopedia.com', 'shopee.co.id', 'bukalapak.com',
            'bca.co.id', 'mandiri.co.id', 'bni.co.id', 'bri.co.id',
            'paypal.com', 'amazon.com', 'apple.com', 'microsoft.com',
            'tiktok.com', 'youtube.com', 'netflix.com', 'telegram.org',
        ];

        // Strip www & subdomains
        $bare = preg_replace('/^www\./', '', $host);

        foreach ($knownDomains as $known) {
            if ($bare === $known) {
                return false; // domain asli, skip
            }

            // Levenshtein distance ≤ 2 dan panjang mirip → kemungkinan typosquatting
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
            'MALWARE'                       => 'URL terdeteksi mengandung Malware',
            'SOCIAL_ENGINEERING'            => 'URL terdeteksi sebagai Phishing / Social Engineering',
            'UNWANTED_SOFTWARE'             => 'URL terdeteksi mengandung software berbahaya',
            'POTENTIALLY_HARMFUL_APPLICATION' => 'URL terdeteksi sebagai aplikasi berbahaya',
            default                         => 'URL terdeteksi sebagai ancaman',
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
