<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Etalase;
use App\Livewire\Traits\OwnerAccess;

// Class OwnerEtalase mewarisi seluruh UI dan Data dari Admin\Etalase (OOP Inheritance)
class OwnerEtalase extends Etalase
{
    use OwnerAccess;

    public function mount(string $owner = ''): void
    {
        if (!empty($owner)) {
            $this->owner = strtolower($owner);
            $this->readonly = true;
            $this->ensureOwnerOnly();
        }
    }



    public function render()
    {
        return view('livewire.owner.etalase');
    }

    public function toggleForm()
    {
        return;
    }

    public function closeForm()
    {
        return;
    }

    public function resetForm()
    {
        return;
    }

    // =========================================================================
    // OVERRIDE METHODS (Membatalkan fungsi aksi yang diwarisi dari Parent)
    // Semua fungsi yang bersifat memanipulasi data (CRUD) diganti menjadi abort(403)
    // Tujuannya agar Owner HANYA BISA MEMBACA (Read-Only) data, tanpa bisa merubahnya.
    // =========================================================================

    public function editProduk($id)
    {
        abort(403, 'Owner users cannot edit produk.'); // Mencegah fitur Edit
    }

    public function deleteProduk($id)
    {
        abort(403, 'Owner users cannot delete produk.'); // Mencegah fitur Hapus
    }

    public function tambahProduk()
    {
        abort(403, 'Owner users cannot create produk.'); // Mencegah fitur Tambah
    }
}
