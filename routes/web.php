<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Pesanan;
use App\Livewire\Pemasukan;
use App\Livewire\Etalase;
use App\Livewire\Activity;

// Auth Routes
require __DIR__.'/auth.php';

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/pesanan', Pesanan::class)->name('pesanan');
    Route::get('/pemasukan', Pemasukan::class)->name('pemasukan');
    Route::get('/etalase', Etalase::class)->name('etalase');
    Route::get('/activity', Activity::class)->name('activity');
});