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
    public $latitude = '';
    public $longitude = '';
    public $gmaps_link = '';
    public $no_hp = '';
    public $email = '';
    public $photo;
    public $currentAvatar;

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'alamat' => 'nullable|string|max:500',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'gmaps_link' => 'nullable|string|max:500',
        'no_hp' => 'nullable|string|max:20',
        'email' => 'required|email|max:255',
        'photo' => 'nullable|image|max:2048', // Max 2MB
    ];

    public function mount(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->alamat = $user->alamat ?? '';
        $this->latitude = $user->latitude ?? '';
        $this->longitude = $user->longitude ?? '';
        $this->gmaps_link = $user->gmaps_link ?? '';
        $this->no_hp = $user->no_hp ?? '';
        $this->email = $user->email;
        $this->currentAvatar = $user->avatar;
    }

    public function updateProfile()
    {
        $this->validate();

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Check email uniqueness (excluding self)
        if ($this->email !== $user->email) {
            $this->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
        }

        $data = [
            'name' => $this->name,
            'alamat' => $this->alamat,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'gmaps_link' => $this->gmaps_link,
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
