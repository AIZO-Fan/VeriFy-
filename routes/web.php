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
Route::get('/settings', function () {
    return view('settings');
})->name('settings');
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
})->name('register');
