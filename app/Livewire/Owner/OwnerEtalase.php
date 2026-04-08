<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Etalase;
use App\Livewire\Traits\OwnerAccess;

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

    public function editProduk($id)
    {
        abort(403, 'Owner users cannot edit produk.');
    }

    public function deleteProduk($id)
    {
        abort(403, 'Owner users cannot delete produk.');
    }

    public function tambahProduk()
    {
        abort(403, 'Owner users cannot create produk.');
    }
}
