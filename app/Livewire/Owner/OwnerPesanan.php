<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Pesanan;
use App\Livewire\Traits\OwnerAccess;

class OwnerPesanan extends Pesanan
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
        return view('livewire.owner.pesanan');
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

    public function editPesanan($id)
    {
        abort(403, 'Owner users cannot modify pesanan.');
    }

    public function tambahPesanan()
    {
        abort(403, 'Owner users cannot create or update pesanan.');
    }

    public function acceptPesanan($id)
    {
        abort(403, 'Owner users cannot accept pesanan.');
    }

    public function rejectPesanan($id)
    {
        abort(403, 'Owner users cannot reject pesanan.');
    }

    public function markDelivered($id)
    {
        abort(403, 'Owner users cannot update pesanan status.');
    }

    public function deletePesanan($id)
    {
        abort(403, 'Owner users cannot delete pesanan.');
    }
}
