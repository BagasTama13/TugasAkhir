<?php

namespace App\Livewire\Worker;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Traits\WorkerAccess;

class WorkerDashboard extends Dashboard
{
    use WorkerAccess;

    public function mount(string $worker = ''): void
    {
        if (!empty($worker)) {
            $this->worker = strtolower($worker);
            $this->ensureWorkerOnly();
        }
    }

    public function render()
    {
        return view('livewire.worker.dashboard');
    }
}