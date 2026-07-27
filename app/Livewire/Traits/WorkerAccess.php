<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait WorkerAccess
{
    public bool $readonly = false;
    public string $worker = '';

    public function isWorkerUser(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('worker');
    }

    public function ensureAdminOnly(): void
    {
        if ($this->isWorkerUser()) {
            abort(403, 'Worker users cannot access admin panel.');
        }
    }

    public function ensureWorkerOnly(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasRole('worker')) {
            abort(403, 'Hanya worker yang dapat mengakses panel ini.');
        }
    }
}