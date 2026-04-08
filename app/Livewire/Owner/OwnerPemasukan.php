<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Pemasukan;
use App\Livewire\Traits\OwnerAccess;

class OwnerPemasukan extends Pemasukan
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
        return view('livewire.owner.pemasukan');
    }

    public function confirmPemasukan($id)
    {
        abort(403, 'Owner users cannot confirm pemasukan.');
    }

    public function rejectPemasukan($id)
    {
        abort(403, 'Owner users cannot reject pemasukan.');
    }

    public function deletePemasukan($id)
    {
        abort(403, 'Owner users cannot delete pemasukan.');
    }
}
