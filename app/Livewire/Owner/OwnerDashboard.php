<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Traits\OwnerAccess;

// Menggunakan konsep OOP (Object-Oriented Programming) Inheritance:
// Class OwnerDashboard mewarisi semua logika (methods dan properties) dari Admin\Dashboard.
// Ini mencegah penulisan kode ulang (Don't Repeat Yourself / DRY principle)
class OwnerDashboard extends Dashboard
{
    // Menggunakan trait untuk mempermudah pengecekan hak akses owner
    use OwnerAccess;

    // Melakukan override pada method mount() milik Admin Dashboard
    public function mount(string $owner = '', string $worker = ''): void
    {
        // Tetap memanggil fungsi mount() bawaan parent (admin) untuk pengecekan awal
        parent::mount($owner, $worker);
        
        // Menandai status tampilan ini menjadi read-only khusus untuk tipe pengguna Owner
        if (!empty($owner)) {
            $this->owner = strtolower($owner);
            $this->readonly = true;
            $this->ensureOwnerOnly(); // Memastikan pengguna login adalah benar seorang Owner
        }
    }

    public function render()
    {
        return view('livewire.owner.dashboard');
    }
}
