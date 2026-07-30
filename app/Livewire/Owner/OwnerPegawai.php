<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class OwnerPegawai extends Component
{
    // View state: 'category', 'list'
    public $viewState = 'category';
    
    // Filtered role (admin or worker)
    public $selectedRole = null;

    // Modals
    public $showFormModal = false;
    public $showDetailModal = false;

    // Form data
    public $pegawaiId = null;
    public $nama = '';
    public $username = '';
    public $password = '';
    public $no_hp = '';
    public $alamat = '';
    public $jabatan = '';
    public $nomor_armada = '';
    public $role = 'worker'; // Default

    // Detail data
    public $selectedPegawai = null;

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->pegawaiId)],
            'password' => $this->pegawaiId ? 'nullable|min:8' : 'required|min:8',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'jabatan' => 'nullable|string|max:100',
            'role' => 'required|in:admin,worker',
            'nomor_armada' => 'nullable|string|max:50',
        ];
    }

    public function selectCategory($role)
    {
        $this->selectedRole = $role;
        $this->viewState = 'list';
    }

    public function backToCategory()
    {
        $this->viewState = 'category';
        $this->selectedRole = null;
    }

    #[Computed]
    public function pegawais()
    {
        if ($this->viewState === 'category' || !$this->selectedRole) {
            return collect();
        }

        return User::whereHas('roles', function ($query) {
            $query->where('name', $this->selectedRole);
        })->get();
    }

    public function openFormModal()
    {
        $this->resetForm();
        $this->role = $this->selectedRole ?? 'worker';
        $this->showFormModal = true;
    }

    public function closeFormModal()
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->pegawaiId = null;
        $this->nama = '';
        $this->username = '';
        $this->password = '';
        $this->no_hp = '';
        $this->alamat = '';
        $this->jabatan = '';
        $this->nomor_armada = '';
        $this->role = 'worker';
    }

    public function simpanPegawai()
    {
        $this->validate();

        $data = [
            'name' => $this->nama,
            'username' => $this->username,
            'email' => $this->username . '@' . $this->role . '.bptrans.com',
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'jabatan' => $this->jabatan,
            'nomor_armada' => $this->role === 'worker' ? $this->nomor_armada : null,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->pegawaiId) {
            // Edit
            $user = User::findOrFail($this->pegawaiId);
            $user->update($data);
            $message = 'Data pegawai berhasil diperbarui.';
        } else {
            // Create
            $data['email_verified_at'] = now(); // Auto verifikasi
            $user = User::create($data);
            $message = 'Pegawai berhasil ditambahkan.';
        }

        // Sync Role
        $roleObj = Role::where('name', $this->role)->first();
        if ($roleObj) {
            $user->roles()->sync([$roleObj->id]);
        }

        // Reset detail modal if it was open (or update the selected object if we want to keep it open, but closing form modal is better)
        if ($this->selectedPegawai && $this->selectedPegawai->id === $this->pegawaiId) {
            $this->selectedPegawai = User::find($this->pegawaiId); // refresh
        }

        session()->flash('success', $message);
        $this->closeFormModal();
    }

    public function showDetail($id)
    {
        $this->selectedPegawai = User::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function editPegawai()
    {
        if (!$this->selectedPegawai) return;
        
        $this->resetValidation();
        $this->pegawaiId = $this->selectedPegawai->id;
        $this->nama = $this->selectedPegawai->name;
        $this->username = $this->selectedPegawai->username;
        $this->password = ''; // Kosongkan password untuk form edit
        $this->no_hp = $this->selectedPegawai->no_hp;
        $this->alamat = $this->selectedPegawai->alamat;
        $this->jabatan = $this->selectedPegawai->jabatan;
        $this->nomor_armada = $this->selectedPegawai->nomor_armada;
        
        // Cek role saat ini
        if ($this->selectedPegawai->hasRole('admin')) {
            $this->role = 'admin';
        } else {
            $this->role = 'worker';
        }

        $this->showDetailModal = false;
        $this->showFormModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedPegawai = null;
    }

    public function render()
    {
        return view('livewire.owner.owner-pegawai');
    }
}
