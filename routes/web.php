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
    // Get all products, group by nama
    // For batu bata & genteng: show only cheapest
    // For kayu: show all types
    $products = \App\Models\Produk::all()
        ->groupBy('nama')
        ->map(function ($group) {
            $nama = $group->first()->nama;
            
            // For kayu (wood), show all types sorted by price
            if ($nama === 'kayu') {
                return $group->sortBy('harga')->values();
            }
            
            // For others (batu bata, genteng), show only the cheapest
            return collect([$group->sortBy('harga')->first()]);
        })
        ->flatten(1)
        ->values()
        ->sortBy('nama')
        ->slice(0, 6)
        ->values();
    
    return view('welcome', ['products' => $products]);
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/pesanan', Pesanan::class)->name('pesanan');
    Route::get('/pemasukan', Pemasukan::class)->name('pemasukan');
    Route::get('/etalase', Etalase::class)->name('etalase');
    Route::get('/activity', Activity::class)->name('activity');
});