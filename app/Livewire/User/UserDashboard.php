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
            ->groupBy(function ($item) {
                return trim(strtolower($item->nama));
            })
            ->map(function ($group) {
                $nama = trim(strtolower($group->first()->nama));
                
                // For kayu (wood), show all types sorted by price
                if ($nama === 'kayu') {
                    return $group->sortBy('harga')->values();
                }
                
                // For others (batu bata, genteng), show only the cheapest
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
