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

    public string $panelPrefix = '';

    public function mount(string $owner = '', string $worker = ''): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // Calculate panel prefix based on segments
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
        
        if ($segment1 === 'owner') {
            $this->panelPrefix = '/owner/' . $segment2;
        } elseif ($segment1 === 'worker') {
            $this->panelPrefix = '/worker/' . $segment2;
        } else {
            $this->panelPrefix = '';
        }

        // If owner parameter passed, this is for owner panel - redirect if not owner segment
        if (!empty($owner) && $segment1 !== 'owner') {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Block unauthorized users (unless they match the segment)
        if ($segment1 === 'owner' && $username !== 'owner' && $username !== 'admin') {
            abort(403, 'Access denied.');
        }
        
        if ($segment1 === 'worker' && $username !== 'worker' && $username !== 'admin') {
            abort(403, 'Access denied.');
        }

        if (!$segment1 && $username !== 'admin') {
            abort(403, 'Access denied. Use your designated panel.');
        }
    }
    #[Computed]
    public function totalPesanan()
    {
        return Pesanan::count();
    }

    #[Computed]
    public function pesananTertunda()
    {
        return Pesanan::whereIn('status', ['pending', 'accepted'])->count();
    }

    #[Computed]
    public function totalEtalase()
    {
        return Produk::count();
    }

    #[Computed]
    public function totalUser()
    {
        return \App\Models\User::count();
    }

    #[Computed]
    public function recentActivities()
    {
        return Activity::with('user')->latest()->limit(10)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}