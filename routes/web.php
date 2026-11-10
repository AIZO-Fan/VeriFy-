<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyController;

Route::get('/', [VerifyController::class, 'home'])->name('home');
Route::get('/detect-scam', [VerifyController::class, 'detect'])->name('detect');
Route::post('/detect-scam', [VerifyController::class, 'analyze'])->middleware('throttle:30,1')->name('analyze');
Route::get('/how-it-works', [VerifyController::class, 'how'])->name('how');
Route::get('/modules', [VerifyController::class, 'modules'])->name('modules');
Route::get('/modules/{id}', [VerifyController::class, 'lesson'])->whereNumber('id')->name('lesson');
Route::get('/scam-simulator', [VerifyController::class, 'simulator'])->name('simulator');

Route::middleware('guest')->group(function () {
    // Route bawaan Anda sebelumnya diubah ke AuthController
    Route::get('/login', 'App\\Http\\Controllers\\AuthController@showLogin')->name('login');
    Route::post('/login', 'App\\Http\\Controllers\\AuthController@loginProcess');

    Route::get('/register', 'App\\Http\\Controllers\\AuthController@showRegister')->name('register');
    Route::post('/register', 'App\\Http\\Controllers\\AuthController@registerProcess');
});

Route::middleware('auth')->group(function () {

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Modules Management
        Route::get('/modules', [\App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('modules.index');
        Route::get('/modules/create', [\App\Http\Controllers\Admin\ModuleController::class, 'create'])->name('modules.create');
        Route::post('/modules', [\App\Http\Controllers\Admin\ModuleController::class, 'store'])->name('modules.store');
        Route::get('/modules/{module}/edit', [\App\Http\Controllers\Admin\ModuleController::class, 'edit'])->name('modules.edit');
        Route::put('/modules/{module}', [\App\Http\Controllers\Admin\ModuleController::class, 'update'])->name('modules.update');
        Route::post('/modules/{module}/toggle', [\App\Http\Controllers\Admin\ModuleController::class, 'toggle'])->name('modules.toggle');
        Route::delete('/modules/{module}', [\App\Http\Controllers\Admin\ModuleController::class, 'destroy'])->name('modules.destroy');

        // Simulator Management
        Route::get('/simulator', [\App\Http\Controllers\Admin\SimulatorController::class, 'index'])->name('simulator.index');
        Route::get('/simulator/create', [\App\Http\Controllers\Admin\SimulatorController::class, 'create'])->name('simulator.create');
        Route::post('/simulator', [\App\Http\Controllers\Admin\SimulatorController::class, 'store'])->name('simulator.store');
        Route::get('/simulator/{scenario}/edit', [\App\Http\Controllers\Admin\SimulatorController::class, 'edit'])->name('simulator.edit');
        Route::put('/simulator/{scenario}', [\App\Http\Controllers\Admin\SimulatorController::class, 'update'])->name('simulator.update');
        Route::post('/simulator/{scenario}/toggle', [\App\Http\Controllers\Admin\SimulatorController::class, 'toggle'])->name('simulator.toggle');
        Route::delete('/simulator/{scenario}', [\App\Http\Controllers\Admin\SimulatorController::class, 'destroy'])->name('simulator.destroy');

        // Users Management
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.role');

        // Scan Monitoring
        Route::get('/scans', [\App\Http\Controllers\Admin\ScanController::class, 'index'])->name('scans.index');
        Route::delete('/scans/{scan}', [\App\Http\Controllers\Admin\ScanController::class, 'destroy'])->name('scans.destroy');
    });

    

    Route::get('/settings', function () {
        $scanHistories = auth()->user()->scanHistories()->take(20)->get();
        return view('pages.settings', compact('scanHistories'));
    })->name('settings');


    Route::post('/logout', 'App\\Http\\Controllers\\AuthController@logout')->name('logout');
});