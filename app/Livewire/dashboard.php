<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Activity;

#[Layout('layouts.app')]
class Dashboard extends Component
{
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
        return view('livewire.dashboard');
    }
}