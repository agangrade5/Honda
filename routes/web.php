<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('manage-events.index');
    }

    return redirect()->route('login');
});

// Guest users only
Route::middleware(['guest', 'no.cache'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('login.post');
    });
});

// Authenticated users only
Route::middleware(['admin.auth', 'no.cache'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
