<?php

namespace App\Livewire\Worker;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Traits\WorkerAccess;

// Menggunakan konsep OOP Inheritance:
// Class WorkerDashboard mengambil semua sistem statistik dari Admin\Dashboard,
// sehingga staf lapangan (driver/worker) bisa memiliki dashboard dasar tanpa perlu menulis ulang logika.
class WorkerDashboard extends Dashboard
{
    // Menggunakan trait keamanan khusus untuk Worker
    use WorkerAccess;

    public function mount(string $owner = '', string $worker = ''): void
    {
        // Tetap menjalankan fungsi perlindungan dari parent (Admin)
        parent::mount($owner, $worker);
        
        // Membatasi URL dan status komponen khusus untuk pekerja (Worker)
        if (!empty($worker)) {
            $this->worker = strtolower($worker);
            $this->ensureWorkerOnly(); // Lempar error 403 jika user yang login bukan staf lapangan
        }
    }

    public function render()
    {
        return view('livewire.worker.dashboard');
    }
}