<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiberGuardController;
Route::get('/', [SiberGuardController::class, 'home'])->name('home');
Route::get('/detect-scam', [SiberGuardController::class, 'detect'])->name('detect');
Route::post('/detect-scam', [SiberGuardController::class, 'analyze'])->middleware('throttle:30,1')->name('analyze');
Route::get('/how-it-works', [SiberGuardController::class, 'how'])->name('how');
Route::get('/modules', [SiberGuardController::class, 'modules'])->name('modules');
Route::get('/modules/{id}', [SiberGuardController::class, 'lesson'])->whereNumber('id')->name('lesson');
Route::get('/scam-simulator', [SiberGuardController::class, 'simulator'])->name('simulator');
