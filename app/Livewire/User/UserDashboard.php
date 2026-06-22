<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;

// Mendefinisikan template utama (layout) yang digunakan oleh halaman panel pengguna (User Panel)
#[Layout('layouts.user')]
class UserDashboard extends Component
{
    // Kategori terpilih dari antarmuka (default: 'all')
    public string $category = 'all';

    // Method mount() berfungsi sebagai constructor/middleware tingkat komponen
    public function mount(): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // Middleware: Memblokir pengguna dengan hak akses admin, owner, dan worker 
        // agar tidak mengakses halaman dasbor user biasa
        if ($username === 'admin' || str_starts_with($username, 'owner') || str_starts_with($username, 'worker')) {
            abort(403, 'Use your designated panel.');
        }
    }

    // #[Computed] men-cache daftar produk di sisi server untuk satu siklus request
    #[Computed]
    public function products()
    {
        return Produk::query()
            ->get()
            // Melakukan pengelompokan (grouping) data berdasarkan nama produk (mengabaikan kapitalisasi huruf)
            ->groupBy(function ($item) {
                return trim(strtolower($item->nama));
            })
            ->map(function ($group) {
                $nama = trim(strtolower($group->first()->nama));
                
                // Aturan khusus: Untuk produk 'kayu', tampilkan seluruh jenisnya diurutkan berdasarkan harga
                if ($nama === 'kayu') {
                    return $group->sortBy('harga')->values();
                }
                
                // Aturan khusus: Untuk produk lain (misal: batu bata, genteng), 
                // hanya tampilkan 1 produk dengan harga termurah (sebagai etalase utama)
                return collect([$group->sortBy('harga')->first()]);
            })
            // Meratakan (flatten) array hasil mapping yang sebelumnya bersarang
            ->flatten(1)
            ->filter(function ($item) {
                // Logika Filter Dinamis berdasarkan pilihan kategori di antarmuka (UI)
                if ($this->category === 'bahan_bakar') {
                    $allowed = ['kayu', 'kayu bakar', 'serbuk gergaji'];
                    return in_array(strtolower($item->nama), $allowed);
                }
                if ($this->category === 'sewa_mobil') {
                    return str_contains(strtolower($item->nama), 'sewa');
                }
                if ($this->category === 'bahan_bangunan') {
                    $allowed = ['genteng', 'batu bata'];
                    return in_array(strtolower($item->nama), $allowed);
                }
                // Jika kategori 'all' atau tidak dikenali: tampilkan semua
                return true;
            })
            ->values()
            ->sortBy('nama')
            ->values();
    }

    // #[Computed] Mengambil total jumlah pesanan yang masih aktif (belum selesai) milik user yang sedang login
    #[Computed]
    public function activeOrdersCount()
    {
        return \App\Models\Pesanan::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'accepted'])
            ->count();
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}
