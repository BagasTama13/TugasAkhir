<?php

namespace App\Livewire\Worker;

use App\Livewire\Admin\Activity;
use App\Livewire\Traits\WorkerAccess;

class WorkerActivity extends Activity
{
    use WorkerAccess;

    public function mount(string $worker = ''): void
    {
        if (!empty($worker)) {
            $this->worker = strtolower($worker);
            $this->panelFilter = 'worker'; // Worker sees only worker activities
            $this->ensureWorkerOnly();
        }
    }

    public function render()
    {
        return view('livewire.worker.activity');
    }
}