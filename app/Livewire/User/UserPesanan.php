<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;

#[Layout('layouts.user')]
class UserPesanan extends Component
{
    public function mount(): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        if (in_array($username, ['admin', 'owner', 'worker'], true)) {
            abort(403, 'Use your designated panel.');
        }
    }

    #[Computed]
    public function pesanans()
    {
        return Pesanan::where('user_id', Auth::id())
            ->with('produk')
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.user.pesanan');
    }
}
