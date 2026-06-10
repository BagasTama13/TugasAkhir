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
    public string $category = 'all';

    public function mount(): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // Block admin, owner, worker from user panel
        if ($username === 'admin' || str_starts_with($username, 'owner') || str_starts_with($username, 'worker')) {
            abort(403, 'Use your designated panel.');
        }
    }

    #[Computed]
    public function products()
    {
        return Produk::query()
            ->get()
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
            ->filter(function ($item) {
                // Custom filter based on selected category
                if ($this->category === 'bahan_bakar') {
                    $allowed = ['kayu', 'kayu bakar', 'serbuk gergaji'];
                    return in_array(strtolower($item->nama), $allowed);
                }
                if ($this->category === 'sewa_mobil') {
                    return str_contains(strtolower($item->nama), 'sewa');
                }
                if ($this->category === 'bahan_bangunan') {
                    $allowed = ['genteng', 'batu bata'];
                    return in_array(strtolower($item->nama), $allowed);
                }
                // Default: show all
                return true;
            })
            ->values()
            ->sortBy('nama')
            ->values();
    }

    #[Computed]
    public function activeOrdersCount()
    {
        return \App\Models\Pesanan::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'accepted'])
            ->count();
    }

    public function render()
    {
        return view('livewire.user.dashboard');
    }
}
