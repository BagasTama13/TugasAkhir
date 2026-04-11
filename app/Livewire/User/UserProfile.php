<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.user')]
class UserProfile extends Component
{
    public function mount(): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        if (in_array($username, ['admin', 'owner', 'worker'], true)) {
            abort(403, 'Use your designated panel.');
        }
    }

    public function render()
    {
        return view('livewire.user.profile', [
            'user' => Auth::user(),
        ]);
    }
}
