<?php

use App\Livewire\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::livewire('/login', Admin\Login::class)->name('login')->middleware('guest');

    Route::middleware('auth')->group(function () {
        Route::livewire('/', Admin\MenuManager::class)->name('admin.menu');
        Route::livewire('/settings', Admin\Settings::class)->name('admin.settings');
        Route::post('/logout', function () {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });
});
