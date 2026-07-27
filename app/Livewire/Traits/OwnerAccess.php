<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait OwnerAccess
{
    public bool $readonly = false;
    public string $owner = '';

    public function isOwnerUser(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('owner');
    }

    public function ensureAdminOnly(): void
    {
        if ($this->isOwnerUser()) {
            abort(403, 'Owner users cannot access admin panel.');
        }
    }

    public function ensureOwnerOnly(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasRole('owner')) {
            abort(403, 'Hanya owner yang dapat mengakses panel ini.');
        }
    }
}
