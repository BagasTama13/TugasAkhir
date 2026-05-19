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

        if ($username === 'admin' || str_starts_with($username, 'owner') || str_starts_with($username, 'worker')) {
            abort(403, 'Use your designated panel.');
        }
    }

    public $selectedPesananId = null;

    public function showDetail($id)
    {
        $this->selectedPesananId = $id;
    }

    public function closeDetail()
    {
        $this->selectedPesananId = null;
    }

    #[Computed]
    public function selectedPesanan()
    {
        if ($this->selectedPesananId) {
            return Pesanan::with('produk')->find($this->selectedPesananId);
        }
        return null;
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
