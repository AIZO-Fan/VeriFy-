<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SimulatorController;

Route::get('/simulator/scenarios', [SimulatorController::class, 'getScenarios']);
Route::get('/simulator/scenarios/{slug}', [SimulatorController::class, 'getScenarioDetail']);
Route::post('/simulator/attempts', [SimulatorController::class, 'saveAttempt']);
