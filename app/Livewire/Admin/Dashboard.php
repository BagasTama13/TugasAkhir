<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\OwnerAccess;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Activity;

// Atribut Layout memberitahu Livewire untuk menggunakan 'resources/views/layouts/app.blade.php' sebagai kerangka utama halaman
#[Layout('layouts.app')]
class Dashboard extends Component
{
    // Menggunakan trait OwnerAccess untuk mendapatkan fitur terkait hak akses (mungkin untuk owner/admin)
    use OwnerAccess;

    // Menyimpan path prefix panel aktif (contoh: /owner/admin atau /worker/admin)
    public string $panelPrefix = '';

    public function mount(string $owner = '', string $worker = ''): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Menghitung (parse) segment URL (misal dari /owner/dashboard -> segment 1: owner, segment 2: dashboard)
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
        
        if ($segment1 === 'owner') {
            $this->panelPrefix = '/owner/' . $segment2;
        } elseif ($segment1 === 'worker') {
            $this->panelPrefix = '/worker/' . $segment2;
        } else {
            $this->panelPrefix = '';
        }

        // Middleware Level Component: Jika mencoba akses panel Owner namun URL tidak sesuai, kembalikan 403 Forbidden
        if (!empty($owner) && $segment1 !== 'owner') {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Blokir akses bagi user yang bukan owner atau admin di segment owner
        if ($segment1 === 'owner' && !$user->hasRole('owner') && !$user->hasRole('admin')) {
            abort(403, 'Access denied.');
        }
        
        // Blokir akses bagi user yang bukan worker atau admin di segment worker
        if ($segment1 === 'worker' && !$user->hasRole('worker') && !$user->hasRole('admin')) {
            abort(403, 'Access denied.');
        }

        // Jika URL tanpa prefix (admin asli) namun user bukan admin, blokir akses
        if (!$segment1 && !$user->hasRole('admin')) {
            abort(403, 'Access denied. Use your designated panel.');
        }
    }

    // Atribut #[Computed] berfungsi agar hasil query ini di-cache selama request berjalan, 
    // mengurangi beban query ke database saat diakses dari View.

    #[Computed]
    public function totalPesanan()
    {
        return Pesanan::count(); // Menghitung total semua pesanan
    }

    #[Computed]
    public function pesananTertunda()
    {
        return Pesanan::where('status', 'pending')->count(); // Menghitung pesanan yang belum diproses/tertunda
    }

    #[Computed]
    public function totalEtalase()
    {
        return Produk::count(); // Menghitung total produk katalog
    }

    #[Computed]
    public function totalUser()
    {
        return \App\Models\User::count(); // Menghitung total pengguna terdaftar
    }

    #[Computed]
    public function recentActivities()
    {
        // Mengambil 10 log aktivitas terbaru berserta relasi User (Eager Loading) untuk optimasi
        return Activity::with('user')->latest()->limit(10)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard'); // Mengembalikan view yang akan dirender
    }
}