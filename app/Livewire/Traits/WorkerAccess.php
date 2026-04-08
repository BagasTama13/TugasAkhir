<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait WorkerAccess
{
    protected array $allowedWorkers = [
        'worker',
    ];

    public bool $readonly = false;
    public string $worker = '';

    public function isWorkerUser(): bool
    {
        $username = strtolower(Auth::user()->username ?? '');
        return in_array($username, $this->allowedWorkers, true);
    }

    public function ensureAdminOnly(): void
    {
        if ($this->isWorkerUser()) {
            abort(403, 'Worker users cannot access admin panel.');
        }
    }

    public function ensureWorkerOnly(): void
    {
        $worker = strtolower($this->worker ?? '');
        if (!in_array($worker, $this->allowedWorkers, true) || strtolower(Auth::user()->username ?? '') !== $worker) {
            abort(403, 'Hanya worker yang dapat mengakses panel ini.');
        }
    }
}