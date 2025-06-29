<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return \Illuminate\Support\Facades\Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');

Route::get('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/dashboard', \App\Livewire\Dashboard\DashBoard::class)
    ->middleware('auth')
    ->name('dashboard');
