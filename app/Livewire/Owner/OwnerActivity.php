<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Activity;
use App\Livewire\Traits\OwnerAccess;

class OwnerActivity extends Activity
{
    use OwnerAccess;

    public function mount(string $owner = ''): void
    {
        if (!empty($owner)) {
            $this->owner = strtolower($owner);
            $this->readonly = true;
            $this->panelFilter = 'all'; // Owner can see all activities
            $this->ensureOwnerOnly();
        }
    }

    public function render()
    {
        return view('livewire.owner.activity');
    }
}
