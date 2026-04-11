<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;

#[Layout('layouts.user')]
class UserDashboard extends Component
{
    public function mount(): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // Block admin, owner, worker from user panel
        if (in_array($username, ['admin', 'owner', 'worker'], true)) {
            abort(403, 'Use your designated panel.');
        }
    }

    #[Computed]
    public function products()
    {
        return Produk::all()
            ->groupBy('nama')
            ->map(function ($group) {
                $nama = $group->first()->nama;
                if ($nama === 'kayu') {
                    return $group->sortBy('harga')->values();
                }
                return collect([$group->sortBy('harga')->first()]);
            })
            ->flatten(1)
            ->values()
            ->sortBy('nama')
            ->values();
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}
