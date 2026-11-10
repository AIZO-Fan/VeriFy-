<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controllers;
use App\Models\SimulationScenario;
use App\Services\StoryValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SimulatorController extends Controllers
{
    public function index()
    {
        $scenarios = SimulationScenario::withCount('attempts')->latest()->get();
        return view('admin.simulator.index', compact('scenarios'));
    }

    public function create()
    {
        // Default clean story template ready to edit
        $defaultStory = [
            "schema_version" => 1,
            "start" => "pesan_awal",
            "contact" => [
                "name" => "Pengirim Mencurigakan",
                "avatar_text" => "P",
                "subtitle" => "Nomor tidak dikenal · Simulasi"
            ],
            "context" => [
                "title" => "Skenario Penipuan Baru",
                "description" => "Kamu menerima pesan yang mendesak atau menawarkan sesuatu."
            ],
            "nodes" => [
                "pesan_awal" => [
                    "type" => "conversation",
                    "stage" => "situation",
                    "messages" => [
                        ["sender" => "contact", "text" => "Halo, kami dari pihak layanan. Ada verifikasi mendesak untuk akun Anda."],
                        ["sender" => "contact", "text" => "Mohon konfirmasi segera sebelum akun Anda dinonaktifkan permanen."]
                    ],
                    "prompt" => "Apa tindakan yang kamu ambil?",
                    "choices" => [
                        ["id" => "waspada", "label" => "Abaikan & cek kontak resmi", "reply" => null, "next" => "hasil_aman"],
                        ["id" => "ikuti", "label" => "Tanya apa yang harus dilakukan", "reply" => "Bagaimana cara verifikasinya?", "next" => "permintaan_data"]
                    ]
                ],
                "permintaan_data" => [
                    "type" => "conversation",
                    "stage" => "decision",
                    "messages" => [
                        ["sender" => "contact", "text" => "Silakan kirimkan 6 digit kode OTP yang baru saja masuk ke SMS Anda."]
                    ],
                    "prompt" => "Apakah kamu akan mengirimkan kodenya?",
                    "choices" => [
                        ["id" => "tolak", "label" => "Tolak dan laporkan nomor", "reply" => "Kode OTP bersifat rahasia, saya tidak akan memberikannya.", "next" => "hasil_aman"],
                        ["id" => "kirim", "label" => "Kirimkan kode OTP", "reply" => "Kodenya adalah 123456.", "next" => "hasil_jebakan"]
                    ]
                ],
                "hasil_aman" => [
                    "type" => "outcome",
                    "stage" => "consequence",
                    "tone" => "positive",
                    "score" => 100,
                    "max_score" => 100,
                    "scoring_version" => 1,
                    "title" => "Keputusan Tepat!",
                    "summary" => "Kamu waspada dan tidak terburu-buru memberikan data sensitif atau OTP.",
                    "takeaways" => [
                        "Pihak resmi tidak pernah meminta kode OTP atau kata sandi Anda.",
                        "Selalu verifikasi pesan mendesak melalui kontak saluran resmi mandiri."
                    ]
                ],
                "hasil_jebakan" => [
                    "type" => "outcome",
                    "stage" => "consequence",
                    "tone" => "warning",
                    "score" => 20,
                    "max_score" => 100,
                    "scoring_version" => 1,
                    "title" => "Terjebak Penipuan",
                    "summary" => "Memberikan kode OTP memberi penipu akses penuh untuk membajak akun Anda.",
                    "takeaways" => [
                        "Jangan pernah membagikan kode OTP kepada siapa pun termasuk pihak yang mengaku petugas.",
                        "Jika terlanjur, segera hubungi customer service resmi untuk mengamankan akun."
                    ]
                ]
            ]
        ];

        $defaultStoryJson = json_encode($defaultStory, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return view('admin.simulator.create', compact('defaultStoryJson'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|integer|between:1,6',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:simulation_scenarios,slug',
            'description' => 'required|string|max:1000',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'story_json' => 'required|string',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']) . '-' . rand(100, 999);

        // Parse and validate story JSON
        $storyArray = json_decode($validated['story_json'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($storyArray)) {
            return back()->withErrors(['story_json' => 'Format JSON Story tidak valid: ' . json_last_error_msg()])->withInput();
        }

        try {
            StoryValidator::validate($storyArray);
        } catch (\Throwable $e) {
            return back()->withErrors(['story_json' => 'Validasi Alur Story Gagal: ' . $e->getMessage()])->withInput();
        }

        SimulationScenario::create([
            'topic_id' => $validated['topic_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'difficulty' => $validated['difficulty'],
            'is_active' => $request->has('is_active'),
            'version' => 1,
            'story' => $storyArray,
        ]);

        return redirect()->route('admin.simulator.index')->with('success', 'Skenario Scam Simulator berhasil dibuat!');
    }

    public function edit(SimulationScenario $scenario)
    {
        $storyJson = json_encode($scenario->story, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return view('admin.simulator.edit', compact('scenario', 'storyJson'));
    }

    public function update(Request $request, SimulationScenario $scenario)
    {
        $validated = $request->validate([
            'topic_id' => 'required|integer|between:1,6',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:simulation_scenarios,slug,' . $scenario->id,
            'description' => 'required|string|max:1000',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'story_json' => 'required|string',
        ]);

        $storyArray = json_decode($validated['story_json'], true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($storyArray)) {
            return back()->withErrors(['story_json' => 'Format JSON Story tidak valid: ' . json_last_error_msg()])->withInput();
        }

        try {
            StoryValidator::validate($storyArray);
        } catch (\Throwable $e) {
            return back()->withErrors(['story_json' => 'Validasi Alur Story Gagal: ' . $e->getMessage()])->withInput();
        }

        $scenario->update([
            'topic_id' => $validated['topic_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'],
            'difficulty' => $validated['difficulty'],
            'is_active' => $request->has('is_active'),
            'version' => $scenario->version + 1,
            'story' => $storyArray,
        ]);

        return redirect()->route('admin.simulator.index')->with('success', 'Skenario berhasil diperbarui!');
    }

    public function toggle(SimulationScenario $scenario)
    {
        $scenario->update(['is_active' => !$scenario->is_active]);
        $status = $scenario->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Skenario '{$scenario->title}' berhasil {$status}!");
    }

    public function destroy(SimulationScenario $scenario)
    {
        $title = $scenario->title;
        $scenario->delete();
        return redirect()->route('admin.simulator.index')->with('success', "Skenario '{$title}' berhasil dihapus!");
    }
}

