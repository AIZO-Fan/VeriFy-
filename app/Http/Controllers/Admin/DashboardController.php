<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controllers;
use App\Models\Module;
use App\Models\ScanHistory;
use App\Models\SimulationAttempt;
use App\Models\SimulationScenario;
use App\Models\User;

class DashboardController extends Controllers
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_modules' => Module::count(),
            'total_scenarios' => SimulationScenario::count(),
            'total_scans' => ScanHistory::count(),
            'total_attempts' => SimulationAttempt::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentScans = ScanHistory::with('user')->latest()->take(6)->get();
        $recentScenarios = SimulationScenario::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentScans', 'recentScenarios'));
    }
}

