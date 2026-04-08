<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\OwnerAccess;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Activity;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    use OwnerAccess;

    public function mount(string $owner = ''): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // If owner parameter passed, this is for owner panel - redirect
        if (!empty($owner)) {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Block owner and worker users from admin panel
        if (in_array($username, ['owner', 'worker'], true)) {
            abort(403, 'Access denied. Use your designated panel.');
        }

        // Only admin can access here
        if ($username !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }
    #[Computed]
    public function totalPesanan()
    {
        return Pesanan::count();
    }

    #[Computed]
    public function pesananPending()
    {
        return Pesanan::where('status', 'pending')->count();
    }

    #[Computed]
    public function pesananAccepted()
    {
        return Pesanan::where('status', 'accepted')->count();
    }

    #[Computed]
    public function pesananDelivered()
    {
        return Pesanan::where('status', 'delivered')->count();
    }

    #[Computed]
    public function totalProduk()
    {
        return Produk::count();
    }

    #[Computed]
    public function recentActivities()
    {
        return Activity::with('user')->latest()->limit(5)->get();
    }

    #[Computed]
    public function todayActivities()
    {
        return Activity::whereDate('created_at', today())->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}