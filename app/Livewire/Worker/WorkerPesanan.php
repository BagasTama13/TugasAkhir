<?php

namespace App\Livewire\Worker;

use App\Livewire\Admin\Pesanan;
use App\Livewire\Traits\WorkerAccess;

class WorkerPesanan extends Pesanan
{
    use WorkerAccess;

    public function mount(string $worker = ''): void
    {
        if (!empty($worker)) {
            $this->worker = strtolower($worker);
            $this->readonly = true; // Workers can only change status, not add/edit orders
            $this->ensureWorkerOnly();
        }
    }

    public function render()
    {
        return view('livewire.worker-pesanan');
    }

    // Workers cannot add or edit orders - only customers/users can add orders
    public function tambahPesanan()
    {
        abort(403, 'Worker tidak dapat menambah atau mengubah pesanan. Pesanan hanya dapat ditambah oleh customer.');
    }

    public function editPesanan($id)
    {
        abort(403, 'Worker tidak dapat mengedit pesanan. Pesanan hanya dapat diedit oleh admin.');
    }

    public function deletePesanan($id)
    {
        abort(403, 'Worker tidak dapat menghapus pesanan.');
    }

    // Workers can reject orders if needed
    // public function rejectPesanan($id) - allow inherited

    // Workers can accept and mark as delivered
    // public function acceptPesanan($id) - allow inherited
    // public function markDelivered($id) - allow inherited
}