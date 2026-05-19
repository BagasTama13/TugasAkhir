<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait OwnerAccess
{
    protected array $allowedOwners = [
        'owner',
    ];

    public bool $readonly = false;
    public string $owner = '';

    public function isOwnerUser(): bool
    {
        $username = strtolower(Auth::user()->username ?? '');
        return str_starts_with($username, 'owner') || in_array($username, $this->allowedOwners, true);
    }

    public function ensureAdminOnly(): void
    {
        if ($this->isOwnerUser()) {
            abort(403, 'Owner users cannot access admin panel.');
        }
    }

    public function ensureOwnerOnly(): void
    {
        $owner = strtolower($this->owner ?? '');
        if ((!str_starts_with($owner, 'owner') && !in_array($owner, $this->allowedOwners, true)) || strtolower(Auth::user()->username ?? '') !== $owner) {
            abort(403, 'Hanya owner yang dapat mengakses panel ini.');
        }
    }
}
