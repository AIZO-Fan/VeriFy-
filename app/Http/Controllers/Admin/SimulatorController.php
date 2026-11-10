<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SimulationScenario;
use App\Services\StoryValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SimulatorController extends Controller
{
    public function index()
    {
        // Ambil semua modul terbit untuk dipetakan sebagai topik simulator
        $modules = Module::orderBy('sort_order', 'asc')->get();

        // Ambil seluruh skenario yang sudah dibuat
        $scenarios = SimulationScenario::withCount('attempts')->get()->keyBy('topic_id');

        // Gabungkan data modul dengan skenarionya
        $simulatorRows = $modules->map(function ($module, $index) use ($scenarios) {
            $scenario = $scenarios->get($module->id);

            return [
                'topic_id' => $module->id,
                'display_number' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'module_title' => $module->title,
                'scenario' => $scenario, // null jika belum dibuat
            ];
        });

        return view('admin.simulator.index', compact('simulatorRows'));
    }

    public function create(?Request $request = null)
    {
        $request = $request ?? request();
        $selectedTopicId = $request->query('topic_id');
        $selectedModule = null;

        if ($selectedTopicId) {
            $selectedModule = Module::find($selectedTopicId);
        }

        // Template JSON bersih/kosong agar admin bisa merancang dari awal dengan leluasa
        $defaultStory = [
            "schema_version" => 1,
            "start" => "",
            "contact" => [
                "name" => "",
                "avatar_text" => "",
                "subtitle" => ""
            ],
            "context" => [
                "title" => "",
                "description" => ""
            ],
            "nodes" => []
        ];

        $defaultStoryJson = json_encode($defaultStory, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $modules = Module::orderBy('sort_order', 'asc')->get();

        return view('admin.simulator.create', compact('defaultStoryJson', 'selectedTopicId', 'selectedModule', 'modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:simulation_scenarios,slug',
            'description' => 'required|string|max:1000',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'story_json' => 'required|string',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']) . '-' . rand(100, 999);

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
            'topic_id' => 'required|integer',
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
