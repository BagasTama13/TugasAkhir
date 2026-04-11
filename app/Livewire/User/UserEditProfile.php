<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.user')]
class UserEditProfile extends Component
{
    public $name = '';
    public $alamat = '';
    public $no_hp = '';
    public $email = '';

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'alamat' => 'nullable|string|max:500',
        'no_hp' => 'nullable|string|max:20',
        'email' => 'required|email|max:255',
    ];

    public function mount(): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        if (in_array($username, ['admin', 'owner', 'worker'], true)) {
            abort(403, 'Use your designated panel.');
        }

        $this->name = $user->name;
        $this->alamat = $user->alamat ?? '';
        $this->no_hp = $user->no_hp ?? '';
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();

        // Check email uniqueness (excluding self)
        if ($this->email !== $user->email) {
            $this->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
        }

        $user->update([
            'name' => $this->name,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
        ]);

        session()->flash('success', 'Profil berhasil diperbarui!');
        return redirect()->route('user.profile');
    }

    public function render()
    {
        return view('livewire.user.edit-profile');
    }
}
