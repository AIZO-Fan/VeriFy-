<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SimulationScenario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SimulatorController extends Controller
{
    public function getScenarios(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|integer|between:1,6',
        ]);

        $scenarios = SimulationScenario::where('topic_id', $request->topic_id)
            ->where('is_active', true)
            ->select('id', 'topic_id', 'slug', 'title', 'description', 'difficulty', 'version')
            ->get();

        return response()->json(['data' => $scenarios]);
    }

    public function getScenarioDetail($slug)
    {
        $scenario = SimulationScenario::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$scenario) {
            return response()->json(['message' => 'Skenario tidak ditemukan atau tidak aktif'], 404);
        }

        return response()->json(['data' => $scenario]);
    }

    public function saveAttempt(Request $request)
    {
        $request->validate([
            'scenario_id' => 'required|exists:simulation_scenarios,id',
            'decision_path' => 'required|array',
            'score' => 'required|integer',
            'max_score' => 'required|integer',
            'scenario_version' => 'required|integer',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $attempt = \App\Models\SimulationAttempt::create([
            // Ambil user_id dari request, atau dari session auth jika sedang login
            'user_id' => $request->user_id ?? Auth::id(),
            'scenario_id' => $request->scenario_id,
            'decision_path' => $request->decision_path,
            'score' => $request->score,
            'max_score' => $request->max_score,
            'scenario_version' => $request->scenario_version,
        ]);

        return response()->json([
            'message' => 'Hasil simulasi berhasil disimpan',
            'data' => $attempt
        ], 201);
    }
}
