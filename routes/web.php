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

    Route::get('/settings', function () {
        return view('pages.settings');
    })->name('settings');


    Route::post('/logout', 'App\\Http\\Controllers\\AuthController@logout')->name('logout');
});
