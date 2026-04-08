<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Activity;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Etalase;
use App\Livewire\Owner\OwnerActivity;
use App\Livewire\Owner\OwnerDashboard;
use App\Livewire\Owner\OwnerEtalase;
use App\Livewire\Owner\OwnerPemasukan;
use App\Livewire\Owner\OwnerPesanan;
use App\Livewire\Admin\Pemasukan;
use App\Livewire\Admin\Pesanan;
use App\Livewire\Worker\WorkerActivity;
use App\Livewire\Worker\WorkerDashboard;
use App\Livewire\Worker\WorkerPesanan;

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
    Route::get('/etalase', Etalase::class)->name('etalase');
    Route::get('/activity', Activity::class)->name('activity');
});

Route::pattern('owner', 'owner');

Route::middleware('auth')->prefix('owner/{owner}')->group(function () {
    Route::get('/', function () {
        return redirect()->route('owner.dashboard', ['owner' => request()->route('owner')]);
    });

    Route::get('/dashboard', OwnerDashboard::class)->name('owner.dashboard');
    Route::get('/pesanan', OwnerPesanan::class)->name('owner.pesanan');
    Route::get('/pemasukan', OwnerPemasukan::class)->name('owner.pemasukan');
    Route::get('/etalase', OwnerEtalase::class)->name('owner.etalase');
    Route::get('/activity', OwnerActivity::class)->name('owner.activity');
});

Route::pattern('worker', 'worker');

Route::middleware('auth')->prefix('worker/{worker}')->group(function () {
    Route::get('/', function () {
        return redirect()->route('worker.dashboard', ['worker' => request()->route('worker')]);
    });

    Route::get('/dashboard', WorkerDashboard::class)->name('worker.dashboard');
    Route::get('/pesanan', WorkerPesanan::class)->name('worker.pesanan');
    Route::get('/activity', WorkerActivity::class)->name('worker.activity');
});