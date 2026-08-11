<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait OwnerAccess
{
    // Deklarasi variabel statis untuk membatasi interaksi antarmuka (read-only)
    public bool $readonly = false;
    public string $owner = '';

    // Fungsi pusat untuk memeriksa keabsahan peran pengguna sebagai 'owner'
    public function isOwnerUser(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('owner');
    }

    // Fungsi proteksi: Memblokir akses Owner masuk ke ranah operasional Admin
    public function ensureAdminOnly(): void
    {
        if ($this->isOwnerUser()) {
            abort(403, 'Owner users cannot access admin panel.');
        }
    }

    // Fungsi proteksi: Memblokir pengguna non-Owner mengakses laporan manajerial
    public function ensureOwnerOnly(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user || !$user->hasRole('owner')) {
            abort(403, 'Hanya owner yang dapat mengakses panel ini.');
        }
    }
}
