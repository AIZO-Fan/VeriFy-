<?php

namespace App\Http\Controllers;

use App\Models\SimulationAttempt;
use Illuminate\Http\Request;

class ActivityHistoryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'detect');

        if (
            !is_string($category) ||
            !in_array($category, ['detect', 'modules', 'simulator'], true)
        ) {
            $category = 'detect';
        }

        $scanHistories = collect();
        $moduleProgresses = collect();
        $simulationAttempts = collect();

        $scanHistoryConnected = true;
        $moduleHistoryConnected = false;
        $simulatorHistoryConnected = true;

        switch ($category) {
            case 'detect':
                $scanHistories = $request->user()
                    ->scanHistories()
                    ->reorder()
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->paginate(10);

                break;

            case 'modules':
                /*
                 * Belum disambungkan.
                 * Gunakan model/relasi progres aktual setelah tersedia.
                 */
                break;

            case 'simulator':
                $simulationAttempts = SimulationAttempt::query()
                    ->where('user_id', $request->user()->id)
                    ->with('scenario')
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->paginate(10);

                break;
        }

        return view('activity-history.index', compact(
            'category',
            'scanHistories',
            'moduleProgresses',
            'simulationAttempts',
            'scanHistoryConnected',
            'moduleHistoryConnected',
            'simulatorHistoryConnected'
        ));
    }
}