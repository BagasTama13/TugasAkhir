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
            $this->ensureOwnerOnly();
            // Don't set readonly = true here if we want owner to have control
            $this->readonly = false;
        }
    }

    public function render()
    {
        return view('livewire.owner.pemasukan');
    }

    // Remove the abort overrides so the parent methods (from Admin\Pemasukan) are used
}
