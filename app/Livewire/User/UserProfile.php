<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.user')]
class UserProfile extends Component
{
    public function render()
    {
        return view('livewire.user.profile', [
            'user' => Auth::user(),
        ]);
    }
}
