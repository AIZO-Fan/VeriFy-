<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\SimulationScenario;
use Illuminate\Http\Request;

class VerifyController extends Controllers
{
    public function home()
    {
        return view('pages.home');
    }

    public function detect()
    {
        return view('pages.detect');
    }

    public function analyze(Request $request)
    {
        $input = $request->validate(['mode' => 'required|in:text,url', 'content' => 'required|string|max:5000']);
        if ($input['mode'] === 'url') $request->validate(['content' => 'url:http,https']);
        $text = mb_strtolower($input['content']);
        $flags = [];
        $rules = [['/\b(otp|pin|password|kata sandi)\b/u', 'Menyebut kode OTP, PIN, atau kata sandi'], ['/(hadiah|menang|undian|bonus gratis)/u', 'Menggunakan iming-iming hadiah'], ['/(segera|sekarang|blokir|24 jam|mendesak)/u', 'Menggunakan tekanan waktu atau ancaman'], ['/(transfer|biaya admin|bayar dulu|uang muka)/u', 'Meminta pembayaran atau transfer'], ['/(pasti untung|tanpa risiko|profit tetap)/u', 'Menjanjikan keuntungan tanpa risiko']];
        foreach ($rules as [$pattern, $label]) if (preg_match($pattern, $text)) $flags[] = $label;
        preg_match_all('~https?://[^\s<>]+~iu', $text, $links);
        foreach ($links[0] as $link) {
            $host = parse_url($link, PHP_URL_HOST) ?: '';
            if (filter_var($host, FILTER_VALIDATE_IP)) $flags[] = 'Tautan menggunakan alamat IP';
            if (preg_match('/(^|\.)(bit\.ly|tinyurl\.com|t\.co)$/i', $host)) $flags[] = 'Tujuan tautan disamarkan pemendek URL';
            if (str_starts_with($link, 'http://')) $flags[] = 'Tautan tidak menggunakan HTTPS';
            if (str_contains($host, 'xn--')) $flags[] = 'Domain memakai karakter terenkode; perlu diperiksa';
        }
        $flags = array_values(array_unique($flags));
        $result = ['flags' => $flags, 'status' => count($flags) >= 2 ? 'Perlu kewaspadaan tinggi' : (count($flags) ? 'Perlu diperiksa' : 'Tidak ada indikator pada aturan demo')];
        return view('pages.detect', compact('result'));
    }

    public function how()
    {
        return view('pages.how');
    }

    // Halaman Daftar Modul
    public function modules()
    {
        $modulesData = Module::where('status', 'published')
            ->orderBy('sort_order', 'asc')
            ->withCount('publishedScenarios')
            ->get();

        $modules = $modulesData->map(function ($module, $index) {
            $publishedCount = $module->published_scenarios_count;

            return [
                'id' => $module->id,
                'display_number' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'title' => $module->title,
                'slug' => $module->slug,
                'description' => $module->description,
                'icon' => 'module-' . ($module->id),
                'color' => 'blue',
                'detail_url' => route('lesson', $module->id),
                'practice_available' => $publishedCount > 0,
            ];
        });

        return view('pages.modules', compact('modules'));
    }

    // Halaman Detail Modul (Materi)
    public function lesson($id)
    {
        $module = Module::where('status', 'published')
            ->with(['sections' => function ($query) {
                $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
            }, 'publishedScenarios'])
            ->findOrFail($id);

        $publishedScenarios = $module->publishedScenarios;
        $publishedCount = $publishedScenarios->count();

        // Menyusun Kontrak Data Practice untuk Frontend
        $practice = [
            'available' => $publishedCount > 0,
            'published_scenario_count' => $publishedCount,
            'url' => $publishedCount === 1
                ? route('simulator') . '?topic=' . $module->id
                : ($publishedCount > 1 ? route('simulator') . '?module=' . $module->id : null)
        ];

        return view('pages.lesson', compact('module', 'practice'));
    }

    // Halaman Scam Simulator
    public function simulator(Request $request)
    {
        $modulesData = Module::where('status', 'published')
            ->orderBy('sort_order', 'asc')
            ->get();

        $modules = $modulesData->map(function ($module) {
            return [
                'id' => $module->id,
                'title' => $module->title,
                'icon' => 'module-' . ($module->id),
            ];
        });

        return view('pages.simulator', compact('modules'));
    }
}
