<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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
use App\Livewire\User\UserDashboard;
use App\Livewire\User\UserPesanan;
use App\Livewire\User\UserDetailPesanan;
use App\Livewire\User\UserProfile;
use App\Livewire\User\UserEditProfile;

// Auth Routes
require __DIR__.'/auth.php';

// Public Routes
Route::get('/', function () {
    // Get all products, group by nama
    // For batu bata & genteng: show only cheapest
    // For kayu: show all types
    $products = \App\Models\Produk::all()
        ->groupBy(function ($item) {
            return trim(strtolower($item->nama));
        })
        ->map(function ($group) {
            $nama = trim(strtolower($group->first()->nama));
            
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
        ->slice(0, 8) // Increased slice to show more variety if available
        ->values();
    
    return view('welcome', ['products' => $products]);
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/pesanan', Pesanan::class)->name('pesanan');
    Route::get('/pemasukan', Pemasukan::class)->name('pemasukan');
    Route::get('/etalase', Etalase::class)->name('etalase');
    Route::get('/activity', Activity::class)->name('activity');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::pattern('owner', 'owner[0-9]*');

Route::middleware(['auth', 'verified'])->prefix('owner/{owner}')->group(function () {
    Route::get('/', function () {
        return redirect()->route('owner.dashboard', ['owner' => request()->route('owner')]);
    });

    Route::get('/dashboard', OwnerDashboard::class)->name('owner.dashboard');
    Route::get('/pesanan', OwnerPesanan::class)->name('owner.pesanan');
    Route::get('/pemasukan', OwnerPemasukan::class)->name('owner.pemasukan');
    Route::get('/etalase', OwnerEtalase::class)->name('owner.etalase');
    Route::get('/activity', OwnerActivity::class)->name('owner.activity');
});

Route::pattern('worker', 'worker[0-9]*');

Route::middleware(['auth', 'verified'])->prefix('worker/{worker}')->group(function () {
    Route::get('/', function () {
        return redirect()->route('worker.dashboard', ['worker' => request()->route('worker')]);
    });

    Route::get('/dashboard', WorkerDashboard::class)->name('worker.dashboard');
    Route::get('/pesanan', WorkerPesanan::class)->name('worker.pesanan');
    Route::get('/activity', WorkerActivity::class)->name('worker.activity');
});

// User Panel Routes
Route::middleware(['auth', 'verified'])->prefix('user')->group(function () {
    Route::get('/', function () {
        return redirect()->route('user.dashboard');
    });

    Route::get('/dashboard', UserDashboard::class)->name('user.dashboard');
    Route::get('/pesanan', UserPesanan::class)->name('user.pesanan');
    Route::get('/pesanan/buat', UserDetailPesanan::class)->name('user.pesanan.detail');
    Route::get('/profile', UserProfile::class)->name('user.profile');
    Route::get('/profile/edit', UserEditProfile::class)->name('user.profile.edit');
});