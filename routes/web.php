<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Illuminate\Support\Facades\Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');


Route::get('/dashboard', \App\Livewire\Dashboard\DashBoard::class)
    ->middleware('auth')
    ->name('dashboard');
