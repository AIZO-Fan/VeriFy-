<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\SimulationScenario;
use App\Services\ScamAnalysisService;
use Illuminate\Http\Request;

class VerifyController extends Controllers
{
    public function __construct(private readonly ScamAnalysisService $scamAnalysisService) {}

    public function home()
    {
        return view('pages.home');
    }

    public function detect()
    {
        return view('pages.detect');
    }

    public function analyze(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $input = $request->validate([
            'mode'    => 'required|in:text,url',
            'content' => 'required|string|max:5000',
        ]);

        $trimmed = trim($input['content']);
        if (preg_match('~^https?://\S+$~i', $trimmed)) {
            if (!filter_var($trimmed, FILTER_VALIDATE_URL)) {
                return back()->withErrors(['content' => 'Tautan tidak valid.'])->withInput();
            }
        }

        $result = $this->scamAnalysisService->analyze($input['content']);

        if ($user = $request->user()) {
            $user->scanHistories()->create([
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

    public function how()
    {
        return view('pages.how');
    }

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

        // Mengarahkan ke halaman simulator dengan membawa parameter topic
        $practice = [
            'available' => true, // atau $publishedCount > 0
            'published_scenario_count' => $publishedCount,
            'url' => route('simulator') . '?topic=' . $module->id
        ];

        return view('pages.lesson', compact('module', 'practice'));
    }

    public function simulator(Request $request)
    {
        $modulesData = Module::where('status', 'published')
            ->orderBy('sort_order', 'asc')
            ->get();

        $modules = $modulesData->map(function ($module) {
            $hasScenario = SimulationScenario::where('topic_id', $module->id)
                ->where('is_active', true)
                ->exists();

            return [
                'id' => $module->id,
                'title' => $module->title,
                'icon' => 'module-' . ($module->id),
                'has_scenario' => $hasScenario,
            ];
        });

        return view('pages.simulator', compact('modules'));
    }
}
