<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Traits\OwnerAccess;

class OwnerDashboard extends Dashboard
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
        return view('livewire.owner.dashboard');
    }
}
