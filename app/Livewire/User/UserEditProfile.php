<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

#[Layout('layouts.user')]
class UserEditProfile extends Component
{
    use WithFileUploads;

    public $name = '';
    public $alamat = '';
    public $no_hp = '';
    public $email = '';
    public $photo;
    public $currentAvatar;

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'alamat' => 'nullable|string|max:500',
        'no_hp' => 'nullable|string|max:20',
        'email' => 'required|email|max:255',
        'photo' => 'nullable|image|max:2048', // Max 2MB
    ];

    public function mount(): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        if ($username === 'admin' || str_starts_with($username, 'owner') || str_starts_with($username, 'worker')) {
            abort(403, 'Use your designated panel.');
        }

        $this->name = $user->name;
        $this->alamat = $user->alamat ?? '';
        $this->no_hp = $user->no_hp ?? '';
        $this->email = $user->email;
        $this->currentAvatar = $user->avatar;
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

        $data = [
            'name' => $this->name,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
        ];

        if ($this->photo) {
            $avatarPath = $this->photo->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        session()->flash('success', 'Profil berhasil diperbarui!');
        return redirect()->route('user.profile');
    }

    public function render()
    {
        return view('livewire.user.edit-profile');
    }
}
